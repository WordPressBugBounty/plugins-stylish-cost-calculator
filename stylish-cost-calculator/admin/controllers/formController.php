<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// require(dirname(__DIR__, 1) . "/models/Form.php");
/**
 * *This loads data of form with all realtion
 * !this should be use instead of making queries in php file
 * !more that one file uses this queries
 */

if ( ! class_exists( 'formController', false ) ) {
class formController {

	protected $db;
    const RELATIONS_CACHE_GROUP = 'scc_forms';
    const RELATIONS_CACHE_TTL   = 15 * MINUTE_IN_SECONDS;

	private function normalize_json_text_field( $value ) {
		if ( is_array( $value ) || is_object( $value ) ) {
			return wp_json_encode( $value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
		}

		return $value;
	}


    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
    }

    public function __destruct() {
        // $this->db->close();
    }

    private static function get_relations_cache_key( int $form_id ) {
        return 'with_relations:' . $form_id;
    }

    private static function get_relations_transient_key( int $form_id ) {
        return 'scc_form_rel_' . $form_id;
    }

    private static function get_frontend_cache_key( int $form_id ) {
        return 'frontend_form:' . $form_id;
    }

    private static function get_frontend_transient_key( int $form_id ) {
        return 'scc_form_front_' . $form_id;
    }

    private static function get_cached_relations( int $form_id ) {
        $cache_key = self::get_relations_cache_key( $form_id );
        $cached    = wp_cache_get( $cache_key, self::RELATIONS_CACHE_GROUP );

        if ( false !== $cached ) {
            return $cached;
        }

        $cached = get_transient( self::get_relations_transient_key( $form_id ) );

        if ( false !== $cached ) {
            // Re-prime the per-request object cache but keep the transient in place
            // so it keeps serving subsequent requests for its full TTL. On installs
            // without a persistent object cache, deleting it here made the cache
            // single-use. The transient is invalidated on writes via the
            // flush_relations_cache() / flush_by_* helpers.
            wp_cache_set( $cache_key, $cached, self::RELATIONS_CACHE_GROUP, self::RELATIONS_CACHE_TTL );
            return $cached;
        }

        return false;
    }

    private static function set_cached_relations( int $form_id, $data ) {
        wp_cache_set( self::get_relations_cache_key( $form_id ), $data, self::RELATIONS_CACHE_GROUP, self::RELATIONS_CACHE_TTL );
        set_transient( self::get_relations_transient_key( $form_id ), $data, self::RELATIONS_CACHE_TTL );
    }

    /**
     * Cached loader for the public calculator shortcode.
     *
     * Returns the exact structure the frontend has always rendered (the forced
     * turnoff flags and empty-price-to-zero coercion that the old inline get()
     * helper produced). The expensive sectioned walk is cached under its own
     * keys and invalidated through flush_relations_cache(), so it is shared
     * across page views instead of running the N+1 query path on every render.
     * A deep copy is returned because the renderer mutates the object (e.g.
     * $item->opt_default, $form->formFieldsArray) and those per-render mutations
     * must not leak into the shared cache.
     *
     * @param integer $id calculator id
     * @return object|null form with relations, or null when the form is missing
     */
    public function getFrontendForm( int $id ) {
        $object_key = self::get_frontend_cache_key( $id );
        $cached     = wp_cache_get( $object_key, self::RELATIONS_CACHE_GROUP );

        if ( false === $cached ) {
            $cached = get_transient( self::get_frontend_transient_key( $id ) );

            if ( false !== $cached ) {
                wp_cache_set( $object_key, $cached, self::RELATIONS_CACHE_GROUP, self::RELATIONS_CACHE_TTL );
            }
        }

        if ( false === $cached ) {
            $cached = $this->build_frontend_form( $id );

            if ( null === $cached ) {
                return null;
            }

            wp_cache_set( $object_key, $cached, self::RELATIONS_CACHE_GROUP, self::RELATIONS_CACHE_TTL );
            set_transient( self::get_frontend_transient_key( $id ), $cached, self::RELATIONS_CACHE_TTL );
        }

        // Hand back an isolated copy so per-render mutations never touch the cache.
        return json_decode( wp_json_encode( $cached ) );
    }

    private function normalize_id_list( array $ids ) {
        $normalized = [];

        foreach ( $ids as $id ) {
            $id = absint( $id );
            if ( $id > 0 ) {
                $normalized[ $id ] = $id;
            }
        }

        return array_values( $normalized );
    }

    private function get_results_by_ids( string $query, array $ids ) {
        $ids = $this->normalize_id_list( $ids );

        if ( empty( $ids ) ) {
            return [];
        }

        $placeholders = implode( ', ', array_fill( 0, count( $ids ), '%d' ) );
        $query        = str_replace( '%IDS%', $placeholders, $query );

        return $this->db->get_results( $this->db->prepare( $query, ...$ids ) );
    }

    private function build_condition_elementitem_map( array $elementitem_ids, bool $for_frontend = false ) {
        $elementitems = $this->get_results_by_ids( "SELECT `id`,`name`,`uniqueId` FROM {$this->db->prefix}df_scc_elementitems WHERE id IN (%IDS%);", $elementitem_ids );
        $map          = [];

        foreach ( $elementitems as $elementitem ) {
            $map[ $elementitem->id ] = $for_frontend
                ? (object) [ 'name' => $elementitem->name ]
                : (object) [ 'name' => $elementitem->name, 'uniqueId' => $elementitem->uniqueId ];
        }

        return $map;
    }

    private function build_condition_element_map( array $element_ids, bool $for_frontend = false ) {
        $elements = $this->get_results_by_ids( "SELECT `id`,`titleElement`,`type`,`uniqueId` FROM {$this->db->prefix}df_scc_elements WHERE id IN (%IDS%);", $element_ids );
        $map      = [];

        foreach ( $elements as $element ) {
            $map[ $element->id ] = $for_frontend
                ? (object) [ 'titleElement' => $element->titleElement, 'type' => $element->type ]
                : (object) [ 'titleElement' => $element->titleElement, 'type' => $element->type, 'uniqueId' => $element->uniqueId ];
        }

        return $map;
    }

    /**
     * Builds a calculator with sections, subsections, elements, conditions, and
     * element items using batched relation queries instead of nested N+1 loops.
     */
    private function build_form_with_relations( int $id, bool $for_frontend = false ) {
        $scc_form = $this->db->get_row( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_forms WHERE id =%d ;", $id ) );

        if ( ! $scc_form ) {
            return null;
        }

        if ( $for_frontend ) {
            $scc_form->turnoffemailquote = true;
            $scc_form->turnoffcoupon     = true;
        }

        $sections           = $this->db->get_results( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_sections WHERE form_id =%d ORDER By `order`;", $scc_form->id ) );
        $scc_form->sections = $sections;

        if ( empty( $sections ) ) {
            return $scc_form;
        }

        $section_ids    = [];
        $sections_by_id = [];

        foreach ( $sections as $section ) {
            $section->subsection          = [];
            $section_ids[]                = $section->id;
            $sections_by_id[ $section->id ] = $section;
        }

        $subsections       = $this->get_results_by_ids( "SELECT * FROM {$this->db->prefix}df_scc_subsections WHERE section_id IN (%IDS%) ORDER BY id;", $section_ids );
        $subsection_ids    = [];
        $subsections_by_id = [];

        foreach ( $subsections as $subsection ) {
            $subsection->element = [];
            $subsection_ids[]    = $subsection->id;
            $subsections_by_id[ $subsection->id ] = $subsection;

            if ( isset( $sections_by_id[ $subsection->section_id ] ) ) {
                $sections_by_id[ $subsection->section_id ]->subsection[] = $subsection;
            }
        }

        $elements       = $this->get_results_by_ids( "SELECT * FROM {$this->db->prefix}df_scc_elements WHERE subsection_id IN (%IDS%) ORDER By subsection_id, orden +0;", $subsection_ids );
        $element_ids    = [];
        $elements_by_id = [];

        foreach ( $elements as $element ) {
            $element->conditions   = [];
            $element->elementitems = [];
            $element_ids[]         = $element->id;
            $elements_by_id[ $element->id ] = $element;

            if ( isset( $subsections_by_id[ $element->subsection_id ] ) ) {
                $subsections_by_id[ $element->subsection_id ]->element[] = $element;
            }
        }

        $conditions            = $this->get_results_by_ids( "SELECT * FROM {$this->db->prefix}df_scc_conditions WHERE element_id IN (%IDS%) ORDER BY id;", $element_ids );
        $condition_item_ids    = [];
        $condition_element_ids = [];

        foreach ( $conditions as $condition ) {
            if ( isset( $elements_by_id[ $condition->element_id ] ) ) {
                $elements_by_id[ $condition->element_id ]->conditions[] = $condition;
            }

            if ( ! empty( $condition->elementitem_id ) ) {
                $condition_item_ids[] = $condition->elementitem_id;
            }

            if ( ! empty( $condition->condition_element_id ) ) {
                $condition_element_ids[] = $condition->condition_element_id;
            }
        }

        $condition_item_map    = $this->build_condition_elementitem_map( $condition_item_ids, $for_frontend );
        $condition_element_map = $this->build_condition_element_map( $condition_element_ids, $for_frontend );

        foreach ( $conditions as $condition ) {
            if ( ! empty( $condition->elementitem_id ) && isset( $condition_item_map[ $condition->elementitem_id ] ) ) {
                $condition->elementitem_name = $condition_item_map[ $condition->elementitem_id ];
            }

            if ( ! empty( $condition->condition_element_id ) && isset( $condition_element_map[ $condition->condition_element_id ] ) ) {
                $condition->element_condition = $condition_element_map[ $condition->condition_element_id ];
            }
        }

        $elementitems = $this->get_results_by_ids( "SELECT * FROM {$this->db->prefix}df_scc_elementitems WHERE element_id IN (%IDS%) ORDER BY id;", $element_ids );

        foreach ( $elementitems as $elementitem ) {
            if ( $for_frontend && $elementitem->price == '' ) {
                $elementitem->price = 0;
            }

            if ( isset( $elements_by_id[ $elementitem->element_id ] ) ) {
                $elements_by_id[ $elementitem->element_id ]->elementitems[] = $elementitem;
            }
        }

        return $scc_form;
    }

    /**
     * Builds the frontend shortcode payload. Kept equivalent to the loader the
     * shortcode used inline so the rendered markup is unchanged.
     */
    private function build_frontend_form( int $id ) {
        return $this->build_form_with_relations( $id, true );
    }

    public static function flush_relations_cache( int $form_id ) {
        wp_cache_delete( self::get_relations_cache_key( $form_id ), self::RELATIONS_CACHE_GROUP );
        delete_transient( self::get_relations_transient_key( $form_id ) );
        // Keep the frontend shortcode payload in lock-step with the relations
        // cache. Every edit path (section/element/condition CRUD and form-level
        // update/delete) funnels through here, so the cached calculator render
        // never goes stale after an edit.
        wp_cache_delete( self::get_frontend_cache_key( $form_id ), self::RELATIONS_CACHE_GROUP );
        delete_transient( self::get_frontend_transient_key( $form_id ) );
    }

    public static function flush_by_section( int $section_id ) {
        global $wpdb;
        $form_id = $wpdb->get_var( $wpdb->prepare( "SELECT form_id FROM {$wpdb->prefix}df_scc_sections WHERE id = %d", $section_id ) );
        if ( $form_id ) { self::flush_relations_cache( (int) $form_id ); }
    }

    public static function flush_by_subsection( int $subsection_id ) {
        global $wpdb;
        $form_id = $wpdb->get_var( $wpdb->prepare( "SELECT s.form_id FROM {$wpdb->prefix}df_scc_subsections sub JOIN {$wpdb->prefix}df_scc_sections s ON sub.section_id = s.id WHERE sub.id = %d", $subsection_id ) );
        if ( $form_id ) { self::flush_relations_cache( (int) $form_id ); }
    }

    public static function flush_by_element( int $element_id ) {
        global $wpdb;
        $form_id = $wpdb->get_var( $wpdb->prepare( "SELECT s.form_id FROM {$wpdb->prefix}df_scc_elements e JOIN {$wpdb->prefix}df_scc_subsections sub ON e.subsection_id = sub.id JOIN {$wpdb->prefix}df_scc_sections s ON sub.section_id = s.id WHERE e.id = %d", $element_id ) );
        if ( $form_id ) { self::flush_relations_cache( (int) $form_id ); }
    }

    public static function flush_by_elementitem( int $elementitem_id ) {
        global $wpdb;
        $form_id = $wpdb->get_var( $wpdb->prepare( "SELECT s.form_id FROM {$wpdb->prefix}df_scc_elementitems ei JOIN {$wpdb->prefix}df_scc_elements e ON ei.element_id = e.id JOIN {$wpdb->prefix}df_scc_subsections sub ON e.subsection_id = sub.id JOIN {$wpdb->prefix}df_scc_sections s ON sub.section_id = s.id WHERE ei.id = %d", $elementitem_id ) );
        if ( $form_id ) { self::flush_relations_cache( (int) $form_id ); }
    }

    public static function flush_by_condition( int $condition_id ) {
        global $wpdb;
        $form_id = $wpdb->get_var( $wpdb->prepare( "SELECT s.form_id FROM {$wpdb->prefix}df_scc_conditions c JOIN {$wpdb->prefix}df_scc_elements e ON c.element_id = e.id JOIN {$wpdb->prefix}df_scc_subsections sub ON e.subsection_id = sub.id JOIN {$wpdb->prefix}df_scc_sections s ON sub.section_id = s.id WHERE c.id = %d", $condition_id ) );
        if ( $form_id ) { self::flush_relations_cache( (int) $form_id ); }
    }

	/**
	 * @param string $formname
	 * @param string $description
	 * @param string $inheritFontType
	 * @param string $titleFontSize
	 * @param string $titleFontType
	 * @param string $titleFontWeight
	 * @param string $titleColorPicker
	 * @param string $ServicefontSize
	 * @param string $fontType
	 * @param string $fontWeight
	 * @param string $ServiceColorPicker
	 * @param string $objectSize
	 * @param string $objectColorPicker
	 * @param string $elementSkin
	 * @param string $addContainer
	 * @param string $addtoCheckout
	 * @param string $buttonStyle
	 * @param string $turnoffborder
	 * @param string $turnoffemailquote
	 * @param string $turnviewdetails
	 * @param string $turnoffcoupon
	 * @param string $barstyle
	 * @param string $turnofffloating
	 * @param string $removeTotal
	 * @param string $minimumTotal
	 * @param string $minimumTotalChoose
	 * @param string $removeTitle
	 * @param string $turnoffUnit
	 * @param string $turnoffQty
	 * @param string $turnoffSave
	 * @param string $turnoffTax
	 * @param string $taxVat
	 * @param string $symbol
	 * @param string $removeCurrency
	 * @param string $userCompletes
	 * @param string $userClicksf
	 * @param string $showTaxBeforeTotal
	 * @param string $formFieldsArray
	 * @param string $webhookSettings
	 * @param string $showFieldsQuoteArray
	 * @param string $translation
	 * @param string $paypalConfigArray
	 *
	 * @return integer $id returns id of created form
	 */

	function create( array $values ) {
		$now = current_time( 'mysql' );
		//?get last id and adds 1 because id its not autoincrement
		( isset( $values['id'] ) ) ? $form_id = $values['id'] : $form_id = intval( $this->getLastId() ) + 1;

		( isset( $values['formname'] ) ) ? $formname                     = $values['formname'] : $formname = '';
		( isset( $values['description'] ) ) ? $description               = $values['description'] : $description = '';
		( isset( $values['inheritFontType'] ) ) ? $inheritFontType       = $values['inheritFontType'] : $inheritFontType = 'true';
		( isset( $values['titleFontSize'] ) ) ? $titleFontSize           = $values['titleFontSize'] : $titleFontSize = '30px';
		( isset( $values['titleFontType'] ) ) ? $titleFontType           = $values['titleFontType'] : $titleFontType = null;
		( isset( $values['titleFontWeight'] ) ) ? $titleFontWeight       = $values['titleFontWeight'] : $titleFontWeight = null;
		( isset( $values['titleColorPicker'] ) ) ? $titleColorPicker     = $values['titleColorPicker'] : $titleColorPicker = '#000000';
		( isset( $values['ServicefontSize'] ) ) ? $ServicefontSize       = $values['ServicefontSize'] : $ServicefontSize = '18px';
		( isset( $values['fontType'] ) ) ? $fontType                     = $values['fontType'] : $fontType = null;
		( isset( $values['fontWeight'] ) ) ? $fontWeight                 = $values['fontWeight'] : $fontWeight = null;
		( isset( $values['ServiceColorPicker'] ) ) ? $ServiceColorPicker = $values['ServiceColorPicker'] : $ServiceColorPicker = '#000000';
		( isset( $values['objectSize'] ) ) ? $objectSize                 = $values['objectSize'] : $objectSize = null;
		( isset( $values['objectColorPicker'] ) ) ? $objectColorPicker   = $values['objectColorPicker'] : $objectColorPicker = '#000000';
		( isset( $values['elementSkin'] ) ) ? $elementSkin               = $values['elementSkin'] : $elementSkin = 'style_1';
		( isset( $values['addContainer'] ) ) ? $addContainer             = $values['addContainer'] : $addContainer = 'false';
		( isset( $values['addtoCheckout'] ) ) ? $addtoCheckout           = $values['addtoCheckout'] : $addtoCheckout = 'open_cart';
		( isset( $values['buttonStyle'] ) ) ? $buttonStyle               = $values['buttonStyle'] : $buttonStyle = '1';
		( isset( $values['turnoffborder'] ) ) ? $turnoffborder           = $values['turnoffborder'] : $turnoffborder = 'false';
		( isset( $values['turnoffemailquote'] ) ) ? $turnoffemailquote   = $values['turnoffemailquote'] : $turnoffemailquote = 'false';
		( isset( $values['turnviewdetails'] ) ) ? $turnviewdetails       = $values['turnviewdetails'] : $turnviewdetails = 'false';
		( isset( $values['turnoffcoupon'] ) ) ? $turnoffcoupon           = $values['turnoffcoupon'] : $turnoffcoupon = 'false';
		( isset( $values['barstyle'] ) ) ? $barstyle                     = $values['barstyle'] : $barstyle = 'scc_tp_style4';
		( isset( $values['turnofffloating'] ) ) ? $turnofffloating       = $values['turnofffloating'] : $turnofffloating = 'false';
		( isset( $values['removeTotal'] ) ) ? $removeTotal               = $values['removeTotal'] : $removeTotal = 'false';
		( isset( $values['minimumTotal'] ) ) ? $minimumTotal             = $values['minimumTotal'] : $minimumTotal = '0';
		( isset( $values['minimumTotalChoose'] ) ) ? $minimumTotalChoose = $values['minimumTotalChoose'] : $minimumTotalChoose = null;
		( isset( $values['removeTitle'] ) ) ? $removeTitle               = $values['removeTitle'] : $removeTitle = 'false';
		( isset( $values['turnoffUnit'] ) ) ? $turnoffUnit               = $values['turnoffUnit'] : $turnoffUnit = 'fase';
		( isset( $values['turnoffQty'] ) ) ? $turnoffQty                 = $values['turnoffQty'] : $turnoffQty = 'fase';

		( isset( $values['turnoffSave'] ) ) ? $turnoffSave                   = $values['turnoffSave'] : $turnoffSave = 'false';
		( isset( $values['turnoffTax'] ) ) ? $turnoffTax                     = $values['turnoffTax'] : $turnoffTax = 'false';
		( isset( $values['taxVat'] ) ) ? $taxVat                             = $values['taxVat'] : $taxVat = null;
		( isset( $values['symbol'] ) ) ? $symbol                             = $values['symbol'] : $symbol = '0';
		( isset( $values['removeCurrency'] ) ) ? $removeCurrency             = $values['removeCurrency'] : $removeCurrency = 'false';
		( isset( $values['userCompletes'] ) ) ? $userCompletes               = $values['userCompletes'] : $userCompletes = 'false';
		( isset( $values['userClicksf'] ) ) ? $userClicksf                   = $values['userClicksf'] : $userClicksf = 'false';
		( isset( $values['showTaxBeforeTotal'] ) ) ? $showTaxBeforeTotal     = $values['showTaxBeforeTotal'] : $showTaxBeforeTotal = 'false';
		( isset( $values['formFieldsArray'] ) ) ? $formFieldsArray           = $values['formFieldsArray'] : $formFieldsArray = null;
		( isset( $values['webhookSettings'] ) ) ? $webhookSettings           = $values['webhookSettings'] : $webhookSettings = null;
		( isset( $values['showFieldsQuoteArray'] ) ) ? $showFieldsQuoteArray = $this->normalize_json_text_field( $values['showFieldsQuoteArray'] ) : $showFieldsQuoteArray = null;
		( isset( $values['translation'] ) ) ? $translation                   = $values['translation'] : $translation = null;
		( isset( $values['paypalConfigArray'] ) ) ? $paypalConfigArray       = $values['paypalConfigArray'] : $paypalConfigArray = null;
		( isset( $values['isWoocommerceCheckoutEnabled'] ) ) ? $isWoocommerceCheckoutEnabled = $values['isWoocommerceCheckoutEnabled'] : $isWoocommerceCheckoutEnabled = null;
		( isset( $values['isStripeEnabled'] ) ) ? $isStripeEnabled                           = $values['isStripeEnabled'] : $isStripeEnabled = null;
		( isset( $values['ShowFormBuilderOnDetails'] ) ) ? $ShowFormBuilderOnDetails         = $values['ShowFormBuilderOnDetails'] : $ShowFormBuilderOnDetails = 'false';
		$wrapper_max_width = isset( $values['wrapper_max_width'] ) ? $values['wrapper_max_width'] : 800;
		$query   = $this->db->prepare(
			"INSERT INTO {$this->db->prefix}df_scc_forms
            (id, formname, isWoocommerceCheckoutEnabled, isStripeEnabled, `description`, inheritFontType, titleFontSize, titleFontType, titleFontWeight, titleColorPicker, ServicefontSize, fontType, fontWeight, ServiceColorPicker, objectSize, objectColorPicker, elementSkin, addContainer, addtoCheckout,
            buttonStyle, turnoffborder, turnoffemailquote, turnviewdetails, turnoffcoupon, barstyle, turnofffloating, removeTotal, minimumTotal, minimumTotalChoose, removeTitle, turnoffUnit, turnoffQty, turnoffSave, turnoffTax, taxVat, symbol, removeCurrency, userCompletes,
            userClicksf, showTaxBeforeTotal, formFieldsArray, webhookSettings, showFieldsQuoteArray, translation, paypalConfigArray, ShowFormBuilderOnDetails, created_at, wrapper_max_width) VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%d);",
			$form_id,
			$formname,
			$isWoocommerceCheckoutEnabled,
			$isStripeEnabled,
			$description,
			$inheritFontType,
			$titleFontSize,
			$titleFontType,
			$titleFontWeight,
			$titleColorPicker,
			$ServicefontSize,
			$fontType,
			$fontWeight,
			$ServiceColorPicker,
			$objectSize,
			$objectColorPicker,
			$elementSkin,
			$addContainer,
			$addtoCheckout,
			$buttonStyle,
			$turnoffborder,
			$turnoffemailquote,
			$turnviewdetails,
			$turnoffcoupon,
			$barstyle,
			$turnofffloating,
			$removeTotal,
			$minimumTotal,
			$minimumTotalChoose,
			$removeTitle,
			$turnoffUnit,
			$turnoffQty,
			$turnoffSave,
			$turnoffTax,
			$taxVat,
			$symbol,
			$removeCurrency,
			$userCompletes,
			$userClicksf,
			$showTaxBeforeTotal,
			$formFieldsArray,
			$webhookSettings,
			$showFieldsQuoteArray,
			$translation,
			$paypalConfigArray,
			$ShowFormBuilderOnDetails,
			$now,
			$wrapper_max_width
		);
		$result  = $this->db->query( $query );
		$last_id = $this->db->insert_id;
		if ( $result ) {
			return $form_id;
		} else {
			return 0;
		}
	}
	/**
	 * *Returns one calculator data with sections, subsections, elements, elementitems and conditions
	 * !more than one file uses this query
	 * @param integer $id not null,
	 * @return object form with all realtion
	 */
	function readWithRelations( int $id ) {

		$cached = self::get_cached_relations( $id );

        if ( false !== $cached ) {
           return $cached;
        }

		$scc_form = $this->build_form_with_relations( $id );
		if ( $scc_form ) {

			self::set_cached_relations( $id, $scc_form );

			return $scc_form;
		}
	}

	/**
	 * *Returs one or all forms
	 * @param integer $id
	 * @return object returns the object with the id
	 * @return array if parameter is empty returns all forms
	 */

	function read( int $id = 0 ) {
		( $id == 0 ) ? $result = $this->db->get_results( "SELECT * FROM {$this->db->prefix}df_scc_forms" ) :
			$result            = $this->db->get_row( $this->db->prepare( "SELECT * FROM {$this->db->prefix}df_scc_forms WHERE id =%d", $id ) );
		return $result;
	}

     /**
      * *Returns all forms' name and the ID, for use in gutenberg block
      *
      * @param int $id
      *
      * @return array if parameter is empty returns all forms
      */
	  public function read_all_gutenberg() {
		$result = $this->db->get_results( "SELECT id, formname FROM {$this->db->prefix}df_scc_forms", ARRAY_A );

		return $result;
	}

	function getLastId() {
		$result = $this->db->get_row( $this->db->prepare( "SELECT MAX(id) as lastId FROM {$this->db->prefix}df_scc_forms", null ) );
		if ( $result ) {
			if ( $result->lastId == 'null' ) {
				return 0;
			} else {
				return $result->lastId;
			}
		} else {
			return 0;
		}

	}

	/**
	 * *This updates calculator title
	 * @param array $values array to update
	 * @param integer $id key of the form to update
	 * @return bool true or false
	 */
	function update( array $values ) {
		$id   = $values['id'];
		$i    = new self();
		$todo = $i->read( $id );

		( isset( $values['formname'] ) ) ? $formname                     = $values['formname'] : $formname = $todo->formname;
		( isset( $values['description'] ) ) ? $description               = $values['description'] : $description = $todo->description;
		( isset( $values['inheritFontType'] ) ) ? $inheritFontType       = $values['inheritFontType'] : $inheritFontType = $todo->inheritFontType;
		( isset( $values['titleFontSize'] ) ) ? $titleFontSize           = $values['titleFontSize'] : $titleFontSize = $todo->titleFontSize;
		( isset( $values['titleFontType'] ) ) ? $titleFontType           = $values['titleFontType'] : $titleFontType = $todo->titleFontType;
		( isset( $values['titleFontWeight'] ) ) ? $titleFontWeight       = $values['titleFontWeight'] : $titleFontWeight = $todo->titleFontWeight;
		( isset( $values['titleColorPicker'] ) ) ? $titleColorPicker     = $values['titleColorPicker'] : $titleColorPicker = $todo->titleColorPicker;
		( isset( $values['ServicefontSize'] ) ) ? $ServicefontSize       = $values['ServicefontSize'] : $ServicefontSize = $todo->ServicefontSize;
		( isset( $values['fontType'] ) ) ? $fontType                     = $values['fontType'] : $fontType = $todo->fontType;
		( isset( $values['fontWeight'] ) ) ? $fontWeight                 = $values['fontWeight'] : $fontWeight = $todo->fontWeight;
		( isset( $values['ServiceColorPicker'] ) ) ? $ServiceColorPicker = $values['ServiceColorPicker'] : $ServiceColorPicker = $todo->ServiceColorPicker;
		( isset( $values['objectSize'] ) ) ? $objectSize                 = $values['objectSize'] : $objectSize = $todo->objectSize;
		( isset( $values['objectColorPicker'] ) ) ? $objectColorPicker   = $values['objectColorPicker'] : $objectColorPicker = $todo->objectColorPicker;
		( isset( $values['elementSkin'] ) ) ? $elementSkin               = $values['elementSkin'] : $elementSkin = $todo->elementSkin;
		( isset( $values['addContainer'] ) ) ? $addContainer             = $values['addContainer'] : $addContainer = $todo->addContainer;
		( isset( $values['addtoCheckout'] ) ) ? $addtoCheckout           = $values['addtoCheckout'] : $addtoCheckout = $todo->addtoCheckout;
		( isset( $values['buttonStyle'] ) ) ? $buttonStyle               = $values['buttonStyle'] : $buttonStyle = $todo->buttonStyle;
		( isset( $values['turnoffborder'] ) ) ? $turnoffborder           = $values['turnoffborder'] : $turnoffborder = $todo->turnoffborder;
		( isset( $values['turnoffemailquote'] ) ) ? $turnoffemailquote   = $values['turnoffemailquote'] : $turnoffemailquote = $todo->turnoffemailquote;
		( isset( $values['turnviewdetails'] ) ) ? $turnviewdetails       = $values['turnviewdetails'] : $turnviewdetails = $todo->turnviewdetails;
		( isset( $values['turnoffcoupon'] ) ) ? $turnoffcoupon           = $values['turnoffcoupon'] : $turnoffcoupon = $todo->turnoffcoupon;
		( isset( $values['barstyle'] ) ) ? $barstyle                     = $values['barstyle'] : $barstyle = $todo->barstyle;
		( isset( $values['turnofffloating'] ) ) ? $turnofffloating       = $values['turnofffloating'] : $turnofffloating = $todo->turnofffloating;
		( isset( $values['removeTotal'] ) ) ? $removeTotal               = $values['removeTotal'] : $removeTotal = $todo->removeTotal;
		( isset( $values['minimumTotal'] ) ) ? $minimumTotal             = $values['minimumTotal'] : $minimumTotal = $todo->minimumTotal;
		( isset( $values['minimumTotalChoose'] ) ) ? $minimumTotalChoose = $values['minimumTotalChoose'] : $minimumTotalChoose = $todo->minimumTotalChoose;
		( isset( $values['removeTitle'] ) ) ? $removeTitle               = $values['removeTitle'] : $removeTitle = $todo->removeTitle;
		( isset( $values['turnoffUnit'] ) ) ? $turnoffUnit               = $values['turnoffUnit'] : $turnoffUnit = $todo->turnoffUnit;
		( isset( $values['turnoffQty'] ) ) ? $turnoffQty                 = $values['turnoffQty'] : $turnoffQty = $todo->turnoffQty;

		( isset( $values['turnoffSave'] ) ) ? $turnoffSave                   = $values['turnoffSave'] : $turnoffSave = $todo->turnoffSave;
		( isset( $values['turnoffTax'] ) ) ? $turnoffTax                     = $values['turnoffTax'] : $turnoffTax = $todo->turnoffTax;
		( isset( $values['taxVat'] ) ) ? $taxVat                             = $values['taxVat'] : $taxVat = $todo->taxVat;
		( isset( $values['symbol'] ) ) ? $symbol                             = $values['symbol'] : $symbol = $todo->symbol;
		( isset( $values['removeCurrency'] ) ) ? $removeCurrency             = $values['removeCurrency'] : $removeCurrency = $todo->removeCurrency;
		( isset( $values['userCompletes'] ) ) ? $userCompletes               = $values['userCompletes'] : $userCompletes = $todo->userCompletes;
		( isset( $values['userClicksf'] ) ) ? $userClicksf                   = $values['userClicksf'] : $userClicksf = $todo->userClicksf;
		( isset( $values['showTaxBeforeTotal'] ) ) ? $showTaxBeforeTotal     = $values['showTaxBeforeTotal'] : $showTaxBeforeTotal = $todo->showTaxBeforeTotal;
		( isset( $values['formFieldsArray'] ) ) ? $formFieldsArray           = $values['formFieldsArray'] : $formFieldsArray = $todo->formFieldsArray;
		( isset( $values['webhookSettings'] ) ) ? $webhookSettings           = $values['webhookSettings'] : $webhookSettings = $todo->webhookSettings;
		( isset( $values['showFieldsQuoteArray'] ) ) ? $showFieldsQuoteArray = $this->normalize_json_text_field( $values['showFieldsQuoteArray'] ) : $showFieldsQuoteArray = $todo->showFieldsQuoteArray;
		( isset( $values['translation'] ) ) ? $translation                   = $values['translation'] : $translation = $todo->translation;
		( isset( $values['paypalConfigArray'] ) ) ? $paypalConfigArray       = $values['paypalConfigArray'] : $paypalConfigArray = $todo->paypalConfigArray;
		( isset( $values['isWoocommerceCheckoutEnabled'] ) ) ? $isWoocommerceCheckoutEnabled = $values['isWoocommerceCheckoutEnabled'] : $isWoocommerceCheckoutEnabled = $todo->isWoocommerceCheckoutEnabled;
		( isset( $values['isStripeEnabled'] ) ) ? $isStripeEnabled                           = $values['isStripeEnabled'] : $isStripeEnabled = $todo->isStripeEnabled;
		( isset( $values['ShowFormBuilderOnDetails'] ) ) ? $ShowFormBuilderOnDetails         = $values['ShowFormBuilderOnDetails'] : $ShowFormBuilderOnDetails = 'false';
		$wrapper_max_width = isset( $values['wrapper_max_width'] ) ? $values['wrapper_max_width'] : $todo->wrapper_max_width;

		$request = $this->db->query(
			$this->db->prepare(
				"UPDATE {$this->db->prefix}df_scc_forms SET formname=%s, isWoocommerceCheckoutEnabled =%s, isStripeEnabled =%s, `description`=%s,inheritFontType=%s,titleFontSize=%s,titleFontType=%s,titleFontWeight=%s,titleColorPicker=%s,ServicefontSize=%s,
        fontType=%s,fontWeight=%s,ServiceColorPicker=%s,objectSize=%s,objectColorPicker=%s,elementSkin=%s,addContainer=%s,addtoCheckout=%s,buttonStyle=%s,turnoffborder=%s,turnoffemailquote=%s,turnviewdetails=%s,turnoffcoupon=%s,barstyle=%s,
        turnofffloating=%s,removeTotal=%s,minimumTotal=%s,minimumTotalChoose=%s,removeTitle=%s,turnoffUnit=%s,turnoffQty=%s,turnoffSave=%s,turnoffTax=%s,taxVat=%s,symbol=%s,removeCurrency=%s,userCompletes=%s,userClicksf=%s,showTaxBeforeTotal=%s,
        formFieldsArray=%s,webhookSettings=%s,showFieldsQuoteArray=%s,translation=%s,paypalConfigArray=%s,ShowFormBuilderOnDetails=%s,wrapper_max_width=%d WHERE id=%d ;",
				$formname,
				$isWoocommerceCheckoutEnabled,
				$isStripeEnabled,
				$description,
				$inheritFontType,
				$titleFontSize,
				$titleFontType,
				$titleFontWeight,
				$titleColorPicker,
				$ServicefontSize,
				$fontType,
				$fontWeight,
				$ServiceColorPicker,
				$objectSize,
				$objectColorPicker,
				$elementSkin,
				$addContainer,
				$addtoCheckout,
				$buttonStyle,
				$turnoffborder,
				$turnoffemailquote,
				$turnviewdetails,
				$turnoffcoupon,
				$barstyle,
				$turnofffloating,
				$removeTotal,
				$minimumTotal,
				$minimumTotalChoose,
				$removeTitle,
				$turnoffUnit,
				$turnoffQty,
				$turnoffSave,
				$turnoffTax,
				$taxVat,
				$symbol,
				$removeCurrency,
				$userCompletes,
				$userClicksf,
				$showTaxBeforeTotal,
				$formFieldsArray,
				$webhookSettings,
				$showFieldsQuoteArray,
				$translation,
				$paypalConfigArray,
				$ShowFormBuilderOnDetails,
				$wrapper_max_width,
				$id
			)
		);

		if ( $request ) {
            self::flush_relations_cache( (int) $id );
			return json_encode( array( 'msj' => 'the form was updated' ) );
		} else {
			return json_encode( array( 'msj' => 'There was an error' ) );
		}
	}

	/**
	 * *This deletes one calculator
	 * @param integer $id key of the calculator to delete
	 * @return bool true or false
	 */
	function delete( int $id ) {
		$query = $this->db->delete( "{$this->db->prefix}df_scc_forms", array( 'id' => $id ) );
		if ( $query ) {
            self::flush_relations_cache( $id );
			return json_encode( array( 'msj' => 'the form was deleted' ) );
		} else {
			return json_encode( array( 'msj' => 'An error occured, please contact support team' ) );
		}
	}
}
}
