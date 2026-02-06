<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* Utility function to set default property value for multi dimensional arrays
 * https://mekshq.com/recursive-wp-parse-args-wordpress-function/
 * @param $a - to be parsed array
 * @param $b - default array
 */
if ( ! function_exists( 'meks_wp_parse_args' ) ) {
    function meks_wp_parse_args( &$a, $b ) {
        $a      = (array) $a;
        $b      = (array) $b;
        $result = $b;

        foreach ( $a as $k => &$v ) {
            if ( is_array( $v ) && isset( $result[ $k ] ) ) {
                $result[ $k ] = meks_wp_parse_args( $v, $result[ $k ] );
            } else {
                $result[ $k ] = $v;
            }
        }

        return $result;
    }
}

if ( ! function_exists( 'scc_feedback_invocation' ) ) {
    /**
     * Sets feedback modal invocation to compare against 'scc_save_count' option
     *
     * @return int
     */
    function scc_feedback_invocation( $args ) {
        $save_count         = get_option( 'df-scc-save-count' );
        $current_invocation = get_option( 'df-scc_feedback_invoke' );
        $invoke_at          = 0;
        switch ( $args ) {
            case 'skip':
                $invoke_at = $save_count + 30;
                update_option( 'df-scc_feedback_invoke', $invoke_at );
                break;

            case 'yes':
                update_option( 'df-scc_feedback_invoke', 'disabled' );
                break;

            case 'no':
                update_option( 'df-scc_feedback_invoke', 'disabled' );
                break;

            case 'comment_and_rating':
                update_option( 'df-scc_feedback_invoke', 'disabled' );
                break;
            default:
                if ( $current_invocation && $current_invocation != 'disabled' ) {
                    $invoke_at = $current_invocation;
                } elseif ( $current_invocation == 'disabled' ) {
                    $invoke_at = 0;
                } else {
                    $invoke_at = 30;
                }
                break;
        }

        return (int) $invoke_at;
    }
}

/*
 * Recursive sanitation for text or array
 * from https://wordpress.stackexchange.com/questions/24736/wordpress-sanitize-array
 *
 * @param $array_or_string (array|string)
 * @since  0.1
 * @return mixed
 */
if ( ! function_exists( 'sanitize_text_or_array_field' ) ) {
    function sanitize_text_or_array_field( $array_or_string ) {
        if ( is_string( $array_or_string ) ) {
            $array_or_string = sanitize_text_field( $array_or_string );
        } elseif ( is_array( $array_or_string ) ) {
            foreach ( $array_or_string as $key => &$value ) {
                if ( is_array( $value ) ) {
                    $value = sanitize_text_or_array_field( $value );
                } else {
                    $value = sanitize_text_field( $value );
                }
            }
        }

        return $array_or_string;
    }
}

if ( ! function_exists( 'df_scc_get_currency_symbol_by_currency_code' ) ) {
    function df_scc_get_currency_symbol_by_currency_code( $currency ) {
        $currency_symbol_label = $currency;
        $currency_data         = require SCC_DIR . '/lib/currency_data.php';
        // search the currency symbol from the currency data
        foreach ( $currency_data as $key => $value ) {
            if ( $value['code'] === $currency ) {
                $currency_symbol_label = $value['symbol'];
                break;
            }
        }

        return $currency_symbol_label;
    }
}

if ( ! function_exists( 'scc_get_kses_extended_ruleset' ) ) {
    function scc_get_kses_extended_ruleset( $svg ) {
        $kses_defaults = wp_kses_allowed_html( 'post' );
        $svg_args      = [
            'svg'    => [
                'class'           => true,
                'aria-hidden'     => true,
                'aria-labelledby' => true,
                'role'            => true,
                'xmlns'           => true,
                'width'           => true,
                'height'          => true,
                'stroke'          => true,
                'stroke-width'    => true,
                'fill'            => true,
                'stroke-linecap'  => true,
                'stroke-linejoin' => true,
                'viewbox'         => true, // <= Must be lower case!
            ],
            'g'      => [ 'fill' => true ],
            'title'  => [ 'title' => true ],
            'path'   => [
                'd'               => true,
                'fill'            => true,
                'stroke'          => true,
                'stroke-width'    => true,
                'stroke-linecap'  => true,
                'stroke-linejoin' => true,
            ],
            'circle' => [
                'cx' => true,
                'cy' => true,
                'r'  => true,
            ],
            'line'   => [
                'x1' => true,
                'x2' => true,
                'y1' => true,
            ],
            'rect'   => [
                'x'               => true,
                'y'               => true,
                'width'           => true,
                'height'          => true,
                'rx'              => true,
                'ry'              => true,
                'stroke'          => true,
                'stroke-width'    => true,
                'fill'            => true,
                'stroke-linecap'  => true,
                'stroke-linejoin' => true,
                'stroke-width'    => true,
            ],
        ];
        $allowed_tags  = array_merge( $kses_defaults, $svg_args );

        return wp_kses( $svg, $allowed_tags );
    }
}

/* Utility function to set default property value for multi dimensional arrays
 * https://mekshq.com/recursive-wp-parse-args-wordpress-function/
 * @param $a - to be parsed array
 * @param $b - default array
 */
if ( ! function_exists( 'meks_wp_parse_args' ) ) {
    function meks_wp_parse_args( &$a, $b ) {
        $a      = (array) $a;
        $b      = (array) $b;
        $result = $b;

        foreach ( $a as $k => &$v ) {
            if ( is_array( $v ) && isset( $result[ $k ] ) ) {
                $result[ $k ] = meks_wp_parse_args( $v, $result[ $k ] );
            } else {
                $result[ $k ] = $v;
            }
        }

        return $result;
    }
}

if ( ! function_exists( 'df_scc_find_suggested_element_helplink' ) ) {
    function df_scc_find_suggested_element_helplink( $element_code ) {
        // filter DF_SCC_QUIZ_CHOICES to get the element type
        $all_steps = array_merge(
            DF_SCC_QUIZ_CHOICES['step1'],
            DF_SCC_QUIZ_CHOICES['step2'],
            DF_SCC_QUIZ_CHOICES['step3'],
            DF_SCC_QUIZ_CHOICES['step4'],
            DF_SCC_QUIZ_CHOICES['step5'],
            DF_SCC_QUIZ_CHOICES['elementSuggestions']
        );
        
        $element_code = array_filter( $all_steps, function ( $element ) use ( $element_code ) {
            return $element['key'] === $element_code;
        });

        return empty( $element_code ) ? null : array_values( $element_code )[0];
    }
}

if ( ! function_exists( 'df_scc_find_suggested_feature_helplink' ) ) {
    function df_scc_find_suggested_feature_helplink( $feature_code ) {
        $feature_code = array_filter( DF_SCC_QUIZ_CHOICES['stepResult'], function ( $feature ) use ( $feature_code ) {
            return $feature['key'] === $feature_code;
        } );

        return empty( $feature_code ) ? null : array_values( $feature_code )[0];
    }
}

if ( ! function_exists( 'scc_output_editing_page_element_actions' ) ) {
    /**
     * The allowed types are defined in `elementTooltips` variable in scc-backend.js file
     */
    function scc_output_editing_page_element_actions( $type, $expanded = false ) {
        $scc_icons = require SCC_DIR . '/assets/scc_icons/icon_rsrc.php';
        ob_start();
        ?>
		<div class="element-action-icons">
			<i class="scc-element-action-icon material-icons-outlined" onclick="javascript:collapseElement(this)"><?php echo $expanded ? 'expand_less' : 'expand_more'; ?></i>
			<i class="with-tooltip scc-element-action-icon" data-element-tooltip-type="<?php echo esc_attr( $type ); ?>"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['help-circle'] ); ?></span></i>
			<i class="with-tooltip scc-element-action-icon" onclick="javascript:handleElementCopy(this)" title="Copy this element"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['copy'] ); ?></span></i>
			<i class="sortable_subsection_element scc-element-action-icon with-tooltip" title="Move this element"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['move'] ); ?></span></i>
			<i class="with-tooltip scc-element-action-icon" onclick="preDeletionDialog('element', removeElement, this)" title="Delete this element"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['trash-2'] ); ?></span></i>
		</div>
		<?php
        return ob_get_clean();
    }
}

if ( ! function_exists( 'scc_output_editing_page_element_actions_js_template' ) ) {
    function scc_output_editing_page_element_actions_js_template( $type ) {
        $scc_icons = require SCC_DIR . '/assets/scc_icons/icon_rsrc.php';
        ob_start();
        ?>
			<div class="element-action-icons">
				<i class="scc-element-action-icon material-icons-outlined" onclick="javascript:collapseElement(this)">expand_less</i>
				<i class="with-tooltip scc-element-action-icon" data-element-tooltip-type="<?php echo esc_attr( $type ); ?>"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['help-circle'] ); ?></span></i>
				<i class="with-tooltip scc-element-action-icon" onclick="javascript:handleElementCopy(this)" title="Copy this element"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['copy'] ); ?></span></i>
				<i class="sortable_subsection_element scc-element-action-icon  with-tooltip" title="Move this element"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['move'] ); ?></span></i>
				<i class="with-tooltip scc-element-action-icon" onclick="preDeletionDialog(\'element\', removeElement, this)" title="Delete this element"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['trash-2'] ); ?></span></i>
			</div>
		<?php
        return ob_get_clean();
    }
}

if ( ! function_exists( 'scc_frontend_alerts' ) ) {
    function scc_frontend_alerts( $type ) {
        $scc_icons = require SCC_DIR . '/assets/scc_icons/icon_rsrc.php';
        ob_start();
        switch ( $type ) {
            case 'mandatory-element':
                ?>
                <span class="scc-mandatory-msg scc-warning-alert scc-hidden">
                    <span class="scc-warning-alert-icon"><?php echo scc_get_kses_extended_ruleset( $scc_icons['alert-triangle'] ); ?></span>
                    <span class="scc-mandatory-text">
                        <span class="scc-mandatory-translated-text trn" data-trn-key="Please choose an option">Please choose an option☝️</span><span>!</span>
                    </span>
                </span>
                <?php
                break;

            default:
                break;
        }
        $html = ob_get_clean();

        return $html;
    }
}
if( ! function_exists('scc_get_helpdesk_link_list') ){
    function scc_get_helpdesk_link_list(){
        return SCC_HELPDESK_LINKS;
    }
}
if ( ! function_exists( 'df_scc_render_alert' ) ) {
    function df_scc_render_alert( $cond, $msg, $custom_class = '', $type = 'warning' ) {
        $render =  '<div class="alert alert-' . $type . ' align-items-center mt-1 scc-err-btn-style ' . $custom_class . ' ' . ( $cond ? 'd-flex' : 'd-none' ) . '">';
        $render .= '<svg class="bi flex-shrink-0 me-3" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>';
        $render .= '<div>' . $msg . '</div>';
        $render .= '</div>';

        return $render;
    }
}

if ( ! function_exists( 'df_scc_output_texthtml' ) ) {
    function df_scc_output_texthtml( $echo = false ) {
        $parts = [];

        $output = implode( ' ', $parts );

        if ( $echo ) {
            echo trim( $output ); // phpcs:ignore
        } else {
            return trim( $output );
        }
    }
}

if ( ! function_exists( 'scc_render_floating_support_button' ) ) {
    /**
     * Renders a floating support button in the bottom right corner
     * Automatically adjusts position when AI Wizard is present on editing pages
     *
     * @return void
     */
    function scc_render_floating_support_button() {
        // Only show on SCC admin pages
        if ( ! isset( $_GET['page'] ) || strpos( $_GET['page'], 'scc' ) === false ) {
            return;
        }

        // Get the help-circle icon
        $scc_icons = require SCC_DIR . '/assets/scc_icons/icon_rsrc.php';
        $support_url = admin_url( 'admin.php?page=scc-support' );
        
        // Check if we're on an editing page (where AI Wizard might be present)
        $is_editing_page = isset( $_GET['page'] ) && ( 
            $_GET['page'] === 'scc-edit-calculator' || 
            strpos( $_GET['page'], 'edit' ) !== false 
        );
        
        // Add CSS class for editing pages to adjust positioning
        $position_class = $is_editing_page ? 'scc-support-btn-with-ai-wizard' : 'scc-support-btn-default';
        
        ob_start();
        ?>
        <div class="scc-floating-support-button-container <?php echo esc_attr( $position_class ); ?>">
            <a href="<?php echo esc_url( $support_url ); ?>" 
               class="scc-floating-support-button use-tooltip" 
               data-bs-original-title="<?php esc_attr_e( 'Help & Support', 'stylish-cost-calculator' ); ?>"
               
               title="<?php esc_attr_e( 'Help & Support', 'stylish-cost-calculator' ); ?>"
               aria-label="<?php esc_attr_e( 'Go to Help & Support page', 'stylish-cost-calculator' ); ?>">
                <span class="scc-icn-wrapper">
                    <?php echo scc_get_kses_extended_ruleset( $scc_icons['help-circle'] ); ?>
                </span>
            </a>
        </div>
        
        <style>
            .scc-floating-support-button-container {
                position: fixed;
                bottom: 19px;
                right: 20px;
                z-index: 98; /* Below AI Wizard (z-index: 99) but above most content */
            }
            
            /* Adjust position when AI Wizard is present on editing pages */
            .scc-floating-support-button-container.scc-support-btn-with-ai-wizard {
                right: 110px; /* Leave space for AI Wizard button */
            }
            
            .scc-floating-support-button {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 60px;
                height: 60px;
                background: var(--scc-color-primary, #314AF3);
                color: white;
                border-radius: 50%;
                text-decoration: none;
                box-shadow: 0 4px 12px rgba(49, 74, 243, 0.3);
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
            }
            
            .scc-floating-support-button:hover {
                background: var(--scc-color-primary-dark, #273ccd);
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(49, 74, 243, 0.4);
                color: white;
                text-decoration: none;
            }
            
            .scc-floating-support-button:focus {
                outline: 2px solid var(--scc-color-primary, #314AF3);
                outline-offset: 2px;
                color: white;
                text-decoration: none;
            }
            
            .scc-floating-support-button .scc-icn-wrapper {
                width: 35px;
                height: 35px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .scc-floating-support-button .scc-icn-wrapper svg {
                width: 100%;
                height: 100%;
                stroke: currentColor;
            }
            
            /* Animation for attention */
            @keyframes scc-support-pulse {
                0%, 100% {
                    transform: scale(1);
                }
                50% {
                    transform: scale(1.05);
                }
            }
            
            .scc-floating-support-button:hover {
                animation: scc-support-pulse 2s infinite;
            }
            
            /* Responsive adjustments */
            @media (max-width: 768px) {
                .scc-floating-support-button-container {
                    bottom: 15px;
                    right: 15px;
                }
                
                .scc-floating-support-button-container.scc-support-btn-with-ai-wizard {
                    right: 110px;
                }
                
                .scc-floating-support-button {
                    width: 48px;
                    height: 48px;
                }
                
                .scc-floating-support-button .scc-icn-wrapper {
                    width: 20px;
                    height: 20px;
                }
            }
        </style>
        <?php
        echo ob_get_clean();
    }
}

if ( ! function_exists( 'scc_init_floating_support_button' ) ) {
    /**
     * Initialize the floating support button on admin pages
     *
     * @return void
     */
    function scc_init_floating_support_button() {
        // Only show on admin pages
        if ( ! is_admin() ) {
            return;
        }
        
        // Hook into admin_footer to render the button
        add_action( 'admin_footer', 'scc_render_floating_support_button' );
    }
    
    // Initialize the floating support button
    add_action( 'admin_init', 'scc_init_floating_support_button' );
}
