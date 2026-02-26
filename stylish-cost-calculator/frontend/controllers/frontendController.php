<?php
use DF_SCC\Admin\Frontend\Views\EmbededQuoteForm;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
class SccFrontendController {
    protected $db;
    protected $calc_id;
    protected $form; // will be replaced by own db function on this controller
    protected $style_models_path;
    protected $scc_font_variables;
    private $scc_icons;

    private $gdpr_mode;

    // private $footer_printed_calc_ids = [];
    private $rendered_calc_ids = [];
    public function __construct( $calc_id, $form = null, $scc_font_variables = null ) {
        global $wpdb;
        $rendered_calc_ids = df_scc_plugin::get_shared_data( 'rendered_calc_ids', [] );

        if ( ! in_array( $calc_id, $rendered_calc_ids ) ) {
            $rendered_calc_ids[] = $calc_id;
            df_scc_plugin::set_shared_data( 'rendered_calc_ids', $rendered_calc_ids );
        }
        $this->db                 = $wpdb;
        $this->calc_id            = $calc_id;
        $this->form               = $form;
        $this->rendered_calc_ids  = $rendered_calc_ids;
        $this->style_models_path  = plugin_dir_path( __DIR__ ) . 'models/style-models/';
        $this->scc_font_variables = $scc_font_variables;
        $this->scc_icons          = require SCC_DIR . '/assets/scc_icons/icon_rsrc.php';
        $this->gdpr_mode          = (bool) intval( get_option( 'df_scc_gdpr_mode', 0 ) );
        add_action( 'df_scc_print_quote_form', [ $this, 'scc_print_quote_form' ], 10, 4 );
        // Note: CTA button actions removed to prevent duplication issues
        // Use render_cta_buttons() method directly instead of do_action
    }

    /**
     * This function returns the calculator config for using in the script tag with json data for use in the frontend/backend
     *
     * @param object $form
     * @param bool   $no_gfont_css_output - if true, the function will not output the google fonts css, thus suitable for the backend ajax function
     */
    public function get_config( $form, $no_gfont_css_output = false ) {
        $isSCCFreeVersion = defined( 'STYLISH_COST_CALCULATOR_VERSION' );
        $is_activated     = get_option(
            join(
                '',
                array_map(
                    function ( $d ) {
                        return hex2bin( $d );
                    },
                    [ '64', '66', '5f', '73', '63', '63', '5f', '6c', '69', '63', '65', '6e', '73', '65', '64' ]
                )
            ),
            0
        ) ? true : false;

        $scc_font_variables = $this->get_scc_font_variables( $form, $no_gfont_css_output );

        $price_range_total_settings_default = [
            'rangePercent' => '0',
        ];
        $price_range_total_settings         = wp_parse_args( json_decode( wp_unslash( ! empty( $form->total_price_range_settings ) ? $form->total_price_range_settings : '' ), true ), $price_range_total_settings_default );
        $is_price_range_total_enabled       = $price_range_total_settings['rangePercent'] > 0;

        if ( $is_price_range_total_enabled ) {
            // disabling payment gateways
            $form->isStripeEnabled              = false;
            $form->paypalConfigArray            = json_encode(
                [
                    'paypal_email'               => null,
                    'paypal_shopping_cart_name'  => null,
                    'paypal_checked'             => false,
                    'paypalSuccessURL'           => null,
                    'paypalCancelURL'            => null,
                    'objectTaxInclusionInPayPal' => false,
                ]
            );
            $form->isWoocommerceCheckoutEnabled = 'false';
        }

        $currency_style                = get_option( 'df_scc_currency_style', 'default' ); // dot or comma
        $currency                      = get_option( 'df_scc_currency', 'USD' );
        $currency_conversion_mode      = get_option( 'df_scc_currency_coversion_mode', 'off' );
        $currency_conversion_selection = get_option( 'df_scc_currency_coversion_manual_selection' );
        $fontFamilyTitle2              = $scc_font_variables['fontFamilyTitle2'];
        $fontFamilyService2            = $scc_font_variables['fontFamilyService2'];
        new SccSero();
        $defaultFields      = DF_SCC_DEFAULT_FORM_FIELDS;
        $default_field_keys = [ 'name', 'email', 'phone' ];
        $formFieldsArray    = empty( $form->formFieldsArray ) || $form->formFieldsArray === 'null' ? $defaultFields : json_decode( $form->formFieldsArray, true );
        // manually add email field if it is missing somehow, and patching it if vital properties are out of order
        $quoteformKeys = array_map(
            function ( $d ) {
                return array_keys( $d )[0];
            },
            $formFieldsArray
        );

        if ( ! in_array( 'email', $quoteformKeys ) ) {
            $emailField = [
                'email' => [
                    'name'           => 'Your Email',
                    'description'    => 'Type in your email',
                    'type'           => 'email',
                    'isMandatory'    => true,
                    'trnKey'         => 'Your Email',
                    'deletable'      => false,
                    'isDefaultField' => true,
                    'view_state'     => true,
                ],
            ];
            array_push( $formFieldsArray, $emailField );
        }
        // setting the default values for the form fields
        for ( $i = 0; $i < count( $formFieldsArray ); $i++ ) {
            $current_key                           = array_keys( $formFieldsArray[ $i ] )[0];
            $formFieldsArray[ $i ][ $current_key ] = scc_parse_quote_form_fields( $current_key, $formFieldsArray[ $i ][ $current_key ] );
        }
        $form->formFieldsArray  = $formFieldsArray;
        $paypalConfigArray      = wp_parse_args(
            json_decode( $form->paypalConfigArray, true ),
            [
                'paypal_email'               => null,
                'paypal_shopping_cart_name'  => null,
                'paypal_checked'             => false,
                'paypalSuccessURL'           => null,
                'paypalCancelURL'            => null,
                'objectTaxInclusionInPayPal' => false,
            ]
        );
        $gdpr_acceptance_config = wp_parse_args(
            json_decode( wp_unslash( ! empty( $form->gdprAcceptanceConfigArray ) ? $form->gdprAcceptanceConfigArray : '' ), true ),
            [
                'show'        => false,
                'gdrpMsg'     => '',
                'gdprTooltip' => '',
            ]
        );

        $customButtonsArray = wp_parse_args(
            json_decode( wp_unslash( ! empty( $form->customButtonsArray ) ? $form->customButtonsArray : '' ), true ),
            [
                'enable_custom_payment_btns' => 0,
                'paypal_text'                => '',
                'stripe_text'                => '',
            ]
        );
        // Above here can be moved to formController safely

        $stripe_common_config            = ( get_option( 'df_scc_stripe_keys' ) == '' ) ? [
            'pubKey'             => null,
            'privKey'            => null,
            'successRedirectURL' => null,
            'cancelRedirectURL'  => null,
            'includeTaxAmount'   => 0,
        ] : get_option( 'df_scc_stripe_keys' );
        $stripe_common_config['enabled'] = $form->isStripeEnabled && ( $form->isStripeEnabled !== 'false' ) ? true : false;
        $webhookConfigArray              = $isSCCFreeVersion ? [] : json_decode( $form->webhookSettings, true );

        $customJsConfig = $isSCCFreeVersion ? [] : json_decode( get_option( 'scc_cstmjs_calc_' . $form->id, '[{}]' ), true );
        // handles error if webhook is not correct
        if ( ! is_array( $webhookConfigArray ) ) {
            $webhookConfigArray = [];
        }
        $webhookQuote       = meks_wp_parse_args(
            $webhookConfigArray[0],
            [
                'scc_set_webhook_quote' => [
                    'enabled' => false,
                    'webhook' => '',
                ],
            ]
        );
        $webhookDetailView  = meks_wp_parse_args(
            $webhookConfigArray[1],
            [
                'scc_set_webhook_detail_view' => [
                    'enabled' => false,
                    'webhook' => '',
                ],
            ]
        );
        $customJsQuote      = meks_wp_parse_args(
            $customJsConfig[0],
            [
                'scc_set_customJs_quote' => [
                    'enabled'  => false,
                    'customJs' => '',
                ],
            ]
        );
        $customJsDetailView = meks_wp_parse_args(
            $customJsConfig[1],
            [
                'scc_set_customJs_detail_view' => [
                    'enabled'  => false,
                    'customJs' => '',
                ],
            ]
        );

        $detailedViewCustomJs = $customJsDetailView['scc_set_customJs_detail_view']['customJs'];
        $postQuoteCustomJs    = $customJsQuote['scc_set_customJs_quote']['customJs'];

        // unset webhook endpoint if the user is not in wp-admin dashboard
        if ( ! is_admin() ) {
            unset( $webhookQuote['scc_set_webhook_quote']['webhook'] );
            unset( $webhookDetailView['scc_set_webhook_detail_view']['webhook'] );
            unset( $customJsQuote['scc_set_customJs_quote']['customJs'] );
            unset( $customJsDetailView['scc_set_customJs_detail_view']['customJs'] );
        }
        $webhookSettings  = [
            $webhookQuote,
            $webhookDetailView,
        ];
        $customJsSettings = [
            $customJsQuote,
            $customJsDetailView,
        ];
        /**
         * *Translates Preview
         * ?Form translate array if column is empty
         */
        $translateArray = $form->translation;
        $translateArray = json_decode( stripslashes( $translateArray ) );

        if ( ! function_exists( 'getTranslatables' ) ) {
            function getTranslatables( $translateArray ) {
                $arrt = [];

                foreach ( $translateArray as $value ) {
                    if ( $value->translation != '' ) {
                        $a                = [];
                        $a['key']         = $value->key;
                        $a['translation'] = $value->translation;
                        array_push( $arrt, $a );
                    }
                }

                return $arrt;
            }
        }
        ( $translateArray != null || $translateArray != '' ) ? $transletables = getTranslatables( $translateArray ) : $transletables = [];
        $df_scc_email_validation_settings                                     = get_option( 'df_scc_email_validation_settings', [ '', 0 ] );

        $current_email_template_config = wp_parse_args(
            get_option( 'df-scc-email-template-settings-' . $form->id ),
            [
                'sender_name'     => get_option( 'df_scc_sendername' ),
                'sender_email'    => get_option( 'df_scc_emailsender' ),
                'email_extra_bcc' => get_option( 'df_scc_email_extra_bcc' ),
                'emailsubject'    => get_option( 'df_scc_emailsubject' ),
                'email_send_copy' => get_option( 'df_scc_email_send_copy' ),
                'message_form'    => get_option( 'df_scc_messageform' ),
                'email_footer'    => get_option( 'df_scc_footerdisclaimer' ),
            ]
        );

        $stripe_subscription_settings = wp_parse_args(
            json_decode( wp_unslash( ! empty( $form->stripe_subscription_settings ) ? $form->stripe_subscription_settings : '' ), true ),
            [
                'enable_subscription' => false,
                'plan_name'           => '',
                'plan_period'         => 'monthly',
            ]
        );
        $unit_price_config_array      = wp_parse_args(
            json_decode( wp_unslash( ! empty( $form->unit_price_config_array ) ? $form->unit_price_config_array : '' ), true ),
            [
                'element_id' => '',
            ]
        );

        $custom_css_config_array     = wp_parse_args(
            json_decode( wp_unslash( ! empty( $form->custom_css_config_array ) ? $form->custom_css_config_array : '' ), true ),
            [
                'css' => '',
            ]
        );
        $price_rounding_config_array = wp_parse_args(
            json_decode( wp_unslash( ! empty( $form->price_rounding_config_array ) ? $form->price_rounding_config_array : '' ), true ),
            [
                'rounding_type' => '',
            ]
        );

        $floating_bar_config_array = wp_parse_args(
            json_decode( wp_unslash( ! empty( $form->floating_bar_config_array ) ? $form->floating_bar_config_array : '' ), true ),
            [
                'floating_bar_config' => '',
            ]
        );

        $pdf_banner_images = wp_parse_args(
            json_decode( wp_unslash( ! empty( $form->pdf_banner_images ) ? $form->pdf_banner_images : '[]' ), true ),
            [
                'email_banner_image'     => get_option( 'df_scc_email_banner_image', false ),
                'email_logo_image'       => get_option( 'df_scc_email_logo_image', false ),
                'transparent_logo_image' => get_option( 'df_scc_transparent_logo_image', false ),
            ]
        );

        $captcha_provider_site_key_ids = [
            'recaptcha_v2' => 'df_scc-recaptcha-site-key',
            'turnstile'    => 'df_scc-turnstile-site-key',
        ];
        $captcha_provider              = get_option( 'df_scc-captcha-provider', 'recaptcha_v2' );
        $captcha_site_key              = get_option( $captcha_provider_site_key_ids[ $captcha_provider ], null );
        $captcha_config                = apply_filters(
            'df_scc_modify_captcha_config',
            [
                'enabled'  => get_option( 'df_scc-captcha-enablement-status', false ),
                'provider' => $captcha_provider,
                'siteKey'  => $captcha_site_key,
            ]
        );

        if ( $this->gdpr_mode ) {
            $captcha_config['enabled'] = false;
            $form->isStripeEnabled     = 'false';
        }

        $price_range_total_settings_default = [
            'rangePercent' => '0',
        ];
        $price_range_total_settings         = wp_parse_args( $price_range_total_settings_default );
        
        $scc_config = [
            'form_id'                            => $form->id,
            'formname'                           => $form->formname,
            'quoteFormFields'                    => $formFieldsArray,
            'quoteRecipient'                     => $form->emailQuoteRecipients,
            'elementSkin'                        => $form->elementSkin,
            'enable_floating_totalbar'           => $form->turnofffloating == 'true' ? true : false,
            'enable_floating_totalbar_on_mobile' => 1 === intval( $form->show_total_price_float_mobile ),
            'enable_cost_per_unit'               => $form->showUnitPrice === 'true',
            //fonts
            'section_title'                     => [
                'color'       => $form->titleColorPicker,
                'size'        => $form->titleFontSize,
                'font_family' => $fontFamilyTitle2,
                'font_weight' => empty( $form->titleFontWeight ) ? 'bold' : $form->titleFontWeight,
            ],
            'service'                            => [
                'color'       => $form->ServiceColorPicker,
                'size'        => $form->ServicefontSize,
                'font_family' => $fontFamilyService2,
                'font_weight' => empty( $form->fontWeight ) ? 'bold' : $form->fontWeight,
            ],

            'fontConfig'                         => [
                'serviceFont' => $fontFamilyService2,
                'titleFont'   => $fontFamilyTitle2,
            ],
            'objectColor'                       => $form->objectColorPicker,
            'ctaBtnColor'                       => $form->ctaBtnColorPicker ? $form->ctaBtnColorPicker : $form->objectColorPicker,
            'cta_btn_text_color'                => $form->cta_btn_text_color,
            'userActionBtns'                    => [
                'isCouponBtnEnabled'       => $form->turnoffcoupon === 'true' ? false : true,
                'isWoocommerceBtnEnabled'  => $form->isWoocommerceCheckoutEnabled === 'true' && scc_has_woocommerce_products_configured( $form ),
                'isDetailedListBtnEnabled' => $form->turnviewdetails !== 'true' && $form->blurTotalPrice !== 'true',
                'isEmailQuoteBtnEnabled'   => $form->turnoffemailquote !== 'true' && $form->blurTotalPrice !== 'true',
                'isStripeBtnEnabled'       => $stripe_common_config['enabled'] === true,
                'isPaypalBtnEnabled'       => $paypalConfigArray['paypal_checked'] === 'true',
            ],
            'isTotalBarHidden'                  => $form->removeTotal === 'true',
            'hasBlurredTotal'                   => $form->blurTotalPrice == 'true',
            'captcha'                           => [
                'isCaptchaEnabled' => false,
                'recaptchaSiteKey' => '',
            ],
            'pdf'                               => [
                'isTotalBarHidden'       => $form->removeTotal === 'true',
                'disableUnitColumn'      => $form->turnoffUnit == 'true' ? true : false,
                'disableQtyColumn'       => $form->turnoffQty == 'true' ? true : false,
                'showPriceColumn'        => intval( $form->show_price_column ),
                'dateFormat'             => get_option( 'scc_pdf_datefmt', 'mm-dd-yyyy' ),
                'turnoffSave'            => $form->turnoffSave == 'true' ? true : false,
                'turnoffTax'             => $form->turnoffTax == 'true' ? true : false,
                'removeTitle'            => $form->removeTitle == 'true' ? true : false,
                'footer'                 => wp_kses_post( html_entity_decode( $current_email_template_config['email_footer'] ) ),
                'bannerImage'            => $pdf_banner_images['email_banner_image'],
                'logo'                   => $pdf_banner_images['email_logo_image'],
                'isPremium'              => ! $isSCCFreeVersion,
                'isAdmin'                => is_admin(),
                'objectColor'            => $form->objectColorPicker,
                'showPdfNameOnQuoteForm' => $form->quote_form_show_pdf_name,
                'removePdfAttachment'    => intval( $form->remove_pdf_attachment ),
            ],
            'showFormInDetail'                   => $form->ShowFormBuilderOnDetails,
            'paypalConfig'                       => $paypalConfigArray,
            'webhookConfig'                      => $webhookSettings,
            'customJsConfig'                     => $customJsSettings,
            'quoteFormPosition'                  => $form->quote_form_position,
            'custom_css_config'                  => $form->custom_css_config_array,
            'price_rounding_config'              => json_decode( wp_unslash( ! empty( $form->price_rounding_config_array ) ? $form->price_rounding_config_array : '' ), true ),
            'floating_bar_config'                => json_decode( wp_unslash( ! empty( $form->floating_bar_config_array ) ? $form->floating_bar_config_array : '' ), true ),
            'addToCartRedirect'                  => $form->addtoCheckout,
            'useCurrencyLetters'                 => (bool) $form->symbol,
            'taxVat'                             => $form->taxVat,
            'currencyCode'                       => $currency,
            'currency_conversion_mode'           => $currency_conversion_mode,
            'currency_conversion_selection'      => $currency_conversion_selection,
            'removeCurrency'                     => $form->removeCurrency === 'true',
            'minimumTotal'                       => $form->minimumTotal,
            'minimumTotalChoose'                 => $form->minimumTotalChoose,
            'sections'                           => $form->sections,
            'enableStripe'                       => $stripe_common_config['enabled'] == 'true' ? true : false,
            'enableWoocommerceCheckout'          => $form->isWoocommerceCheckoutEnabled == 'true' && scc_has_woocommerce_products_configured( $form ),
            'captcha'                            => $captcha_config,
            'disableServerValidation'            => get_option( 'df_scc_disable_server_validation' ),
            'tseparator'                         => get_option( 'df_scc_currency_style' ),
            'translation'                        => $transletables,
            'stripePubKey'                       => $stripe_common_config['pubKey'],
            'stripeIncludeTax'                   => $stripe_common_config['includeTaxAmount'],
            'preCheckoutQuoteForm'               => $form->preCheckoutQuoteForm == 'true' ? true : false,
            'woocommerceCombinedCheckoutProdId'  => intval( $form->combine_checkout_woocommerce_product_id ),
            'isCombinedCheckoutEnabled'          => isset( $form->combine_checkout_items ) && $form->combine_checkout_items,
            'coupon'                             => '',
            'showSearchBar'                      => $form->showSearchBar,
            'enableSmartURL'                     => boolval( $form->enable_smarturl ),
            'progress_indicator_style'           => $form->progress_indicator_style,
            'progress_buttons_style'             => $form->progress_buttons_style ?? 'space-between',
            'allowCurrencySwitching'             => $form->allow_currency_switching,
            'isEmailValidationActive'            => $df_scc_email_validation_settings[1] && strlen( $df_scc_email_validation_settings[0] ),
            'sendQuoteFormDataToUser'            => $form->send_quote_form_data_to_user && $form->emailQuoteRecipients !== 0 ? 1 : 0,
            'sendQuoteFormDataToAdmin'           => $form->include_quote_form_data && $form->emailQuoteRecipients !== 2 ? 1 : 0,
            'gdprAcceptanceConfig'               => $gdpr_acceptance_config,
            'scc_rate_label_convertion'          => null,
            'accordionStyle'                     => $form->accordionStyle,
            'customButtonsArray'                 => $customButtonsArray,
            'isTextingEnabled'                   => df_scc_get_text_messaging_config( $form->id )['enableTextingLeads'],
            'priceRangeTotalSettings'            => $price_range_total_settings,
            'stripe_subscription_settings'       => $stripe_subscription_settings,
            'unit_price_config_array'            => $unit_price_config_array,
            'flatpickr_localization'             => $form->flatpickr_localization,
            'block_free_email_domain'            => get_option( 'df_scc_block_free_email_domains', 0 ),
            'post_quote_redirection_page_link'   => $form->post_quote_redirect_page ? get_permalink( $form->post_quote_redirect_page ) : null,
            'gdpr_mode'                          => $this->gdpr_mode,
            'transparent_logo_image'             => $pdf_banner_images['transparent_logo_image'],
        ];

        return [
            'scc_config'                   => $scc_config,
            'stripe_subscription_settings' => $stripe_subscription_settings,
            'is_activated'                 => $is_activated,
            'detailedViewCustomJs'         => $detailedViewCustomJs,
            'postQuoteCustomJs'            => $postQuoteCustomJs,
            'stripe_common_config'         => $stripe_common_config,
            'currency'                     => $currency,
            'currency_conversion_mode'     => $currency_conversion_mode,
            'isSCCFreeVersion'             => $isSCCFreeVersion,
            'custom_css_config'            => $custom_css_config_array,
            'price_rounding_config'        => $price_rounding_config_array,
            'floating_bar_config'          => $floating_bar_config_array,
        ];
    }

    public function get_scc_font_variables( $form, $no_gfont_css_output = false ) {
        require SCC_DIR . '/lib/wp-google-fonts/google-fonts.php';
        $form->showFieldsQuoteArray = json_decode( stripslashes( ! empty( $form->showFieldsQuoteArray ) ? $form->showFieldsQuoteArray : '' ), true );
        $allfonts2                  = json_decode( $scc_googlefonts_var->gf_get_local_fonts() );
        $allfonts2i                 = $allfonts2->items;
        $fontUsed2                  = ! empty( $form->titleFontType ) || '0' === $form->titleFontType ? $allfonts2i[ $form->titleFontType ] : $allfonts2i['432'];
        $fontUsed2Variant           = ( $form->titleFontWeight != '' ) ? $form->titleFontWeight : 'regular';

        /**
         * *Title font
         */
        $fontFamilyService2 = 'inherit';
        $fontFamilyTitle2   = 'inherit';

        if ( $this->gdpr_mode ) {
            $form->inheritFontType = 'true';
        }

        if ( $form->inheritFontType == 'null' || $form->inheritFontType == 'false' ) {
            $fonts[0]['kind']     = $fontUsed2->kind;
            $fonts[0]['family']   = $fontUsed2->family;
            $fonts[0]['variants'] = [ $fontUsed2Variant ];
            $fonts[0]['subsets']  = $fontUsed2->subsets;
            $fontFamilyTitle2     = $fonts[0]['family'];

            if ( ! $no_gfont_css_output ) {
                $scc_googlefonts_var->style_late( $fonts ); //load google fonts css
            }
        }
        /**
         * *Service font
         */
        $allfonts3i       = $allfonts2->items;
        $fontUsed3        = ! empty( $form->fontType ) || '0' === $form->fontType ? $allfonts3i[ $form->fontType ] : $allfonts2i['432'];
        $fontUsed3Variant = ( $form->fontWeight != '' ) ? $form->fontWeight : 'regular';

        if ( $form->inheritFontType == 'null' || $form->inheritFontType == 'false' ) {
            $fonts2[0]['kind']     = $fontUsed3->kind;
            $fonts2[0]['family']   = $fontUsed3->family;
            $fonts2[0]['variants'] = [ $fontUsed3Variant ];
            $fonts2[0]['subsets']  = $fontUsed3->subsets;
            $fontFamilyService2    = $fonts2[0]['family'];

            if ( ! $no_gfont_css_output ) {
                $scc_googlefonts_var->style_late( $fonts2 ); //load google fonts css
            }
        }
        /**
         * *Object font
         */
        $scc_font_variables = [
            'fontFamilyTitle2'   => $fontFamilyTitle2,
            'fontFamilyService2' => $fontFamilyService2,
        ];

        return $scc_font_variables;
    }
}
