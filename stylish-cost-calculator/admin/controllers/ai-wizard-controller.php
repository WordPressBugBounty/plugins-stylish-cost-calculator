<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
class AiWizardController {

    protected $db;
    protected $is_activated;
    private $element_response_test;
    private $ai_calculator_settings_response_test;

    public function __construct() {
        global $wpdb;
        $this->db           = $wpdb;
        $this->is_activated = get_option(
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
        $this->element_response_test = [
            [
                'element_title' => 'Dropdown 1',
                'type' => 'Dropdown Menu',
                'sub_type' => '',
                'value' => '',
                'mandatory' => '0',
                'min' => '',
                'max' => '',
                'default' => '',
                'config_array' => '',
                'order' => 0,
                'element_items' => [
                    [
                        'name' => 'Dropdown Item 1',
                        'price' => '10',
                        'description' => 'Description Item 1',
                        'type' => '',
                        'image' => '',
                        'min' => '',
                        'max' => '',
                        'default' => '0', //1,0
                        'order' => 0,
                    ],
                    [
                        'name' => 'Dropdown Item 2',
                        'price' => '5',
                        'description' => 'Description Item 2',
                        'type' => '',
                        'image' => '',
                        'min' => '',
                        'max' => '',
                        'default' => '0', //1,0
                        'order' => 1,
                    ],
                ],
            ],
            [
                'element_title' => 'Slider With AI',
                'type' => 'slider',
                'sub_type' => 'default', //default, quantity_mod, bulk, bulk-2, sliding
                'value' => '',
                'mandatory' => '0',
                'min' => '',
                'max' => '',
                'default' => '',
                'config_array' => '',
                'order' => 1,
                'element_items' => [
                    [
                        'name' => '',
                        'price' => '10',
                        'description' => '',
                        'type' => '',
                        'image' => '',
                        'min' => '1',
                        'max' => '20',
                        'default' => '0', //Selected by default 1,0
                        'order' => 0,
                    ],
                ],
            ],
            [
                'element_title' => 'Slider Bulk pricing With AI',
                'type' => 'slider',
                'sub_type' => 'bulk', //default, quantity_mod, bulk, bulk-2, sliding
                'value' => '',
                'mandatory' => '0',
                'min' => '',
                'max' => '',
                'default' => '',
                'config_array' => '',
                'order' => 2,
                'element_items' => [
                    [
                        'name' => '',
                        'price' => '10',
                        'description' => '',
                        'type' => '',
                        'image' => '',
                        'min' => '1',
                        'max' => '20',
                        'default' => '0',
                        'order' => 0,
                    ],
                    [
                        'name' => '',
                        'price' => '8',
                        'description' => '',
                        'type' => '',
                        'image' => '',
                        'min' => '21',
                        'max' => '30',
                        'default' => '0',
                        'order' => 1,
                    ],
                ],
            ],
            [
                'element_title' => 'Comment Box With AI',
                'type' => 'comment box',
                'sub_type' => '',
                'value' => '1', // Lines of textbox (1, 2, 3, 4). Choose 1 for single line
                'mandatory' => '0',
                'min' => '',
                'max' => '',
                'default' => '',
                'element_items' => [],
                'config_array' => '',
                'order' => 3,
            ],
            [
                'element_title' => 'Checkbox With AI',
                'type' => 'checkbox',
                'sub_type' => '6', //1 (Circle checkbox), 2 (Square checkbox Animated), 3 (Rectangle toggle switch), 4 (Rounded toggle switch), 5 (Circle Checkbox), 6 ( Simple Buttons), 7 (Radio Single choice), 8 (Image buttons), 9 (Multi-items Radio Switch - Single Choice)
                'value' => '',
                'mandatory' => '0',
                'min' => '',
                'max' => '',
                'default' => '',
                'config_array' => '',
                'order' => 4,
                'element_items' => [
                    [
                        'name' => 'Checkbox Item 1',
                        'price' => '10',
                        'description' => 'Checkbox description',
                        'type' => '',
                        'image' => '',
                        'min' => '',
                        'max' => '',
                        'default' => '0', //Selected by default 1,0
                        'order' => 0,
                    ],
                    [
                        'name' => 'Checkbox Item 2',
                        'price' => '10',
                        'description' => 'Checkbox description',
                        'type' => '',
                        'image' => '',
                        'min' => '',
                        'max' => '',
                        'default' => '0', //Selected by default 1,0
                        'order' => 1,
                    ],
                ],
            ],
            [
                'element_title' => 'Image button With AI',
                'type' => 'checkbox',
                'sub_type' => '8', //1 (Circle checkbox), 2 (Square checkbox Animated), 3 (Rectangle toggle switch), 4 (Rounded toggle switch), 5 (Circle Checkbox), 6 ( Simple Buttons), 7 (Radio Single choice), 8 (Image buttons), 9 (Multi-items Radio Switch - Single Choice)
                'value' => '',
                'mandatory' => '0',
                'min' => '',
                'max' => '',
                'default' => '',
                'config_array' => '',
                'order' => 5,
                'element_items' => [
                    [
                        'name' => 'Image Item 1',
                        'price' => '5',
                        'description' => 'Image button item description',
                        'type' => '',
                        'image' => '',
                        'min' => '',
                        'max' => '',
                        'default' => '0', //Selected by default 1,0
                        'order' => 0,
                    ],
                    [
                        'name' => 'Image Item 2',
                        'price' => '5',
                        'description' => 'Image button item description',
                        'type' => '',
                        'image' => '',
                        'min' => '',
                        'max' => '',
                        'default' => '0', //Selected by default 1,0
                        'order' => 1,
                    ],
                ],
            ],
            [
                'element_title' => 'Date Range With AI',
                'type' => 'date',
                'sub_type' => 'date_range', //single_date, date_range
                'value' => '5',
                'mandatory' => '0',
                'min' => '',
                'max' => '',
                'default' => '',
                'element_items' => [],
                'config_array' => '',
                'order' => 6,
            ],
            [
                'element_title' => 'Date With AI',
                'type' => 'date',
                'sub_type' => 'single_date', //single_date, date_range
                'value' => '',
                'mandatory' => '0',
                'min' => '',
                'max' => '',
                'default' => '',
                'element_items' => [],
                'config_array' => '',
                'order' => 7,
            ],
            [
                'element_title' => 'Distance With AI',
                'type' => 'distance',
                'sub_type' => 'km', //km, mi
                'value' => '',
                'mandatory' => '0',
                'min' => '',
                'max' => '',
                'default' => '',
                'config_array' => '',
                'order' => 8,
                'element_items' => [
                    [
                        'name' => '',
                        'price' => '10',
                        'description' => '',
                        'type' => '',
                        'image' => '',
                        'min' => '',
                        'max' => '',
                        'default' => '0', 
                        'order' => 0,
                    ],
                ],
            ],
            [
                'element_title' => 'Advanced Pricing Formula With AI',
                'type' => 'math',
                'sub_type' => '',
                'value' => 'Input1 + Input2',
                'mandatory' => '0',
                'min' => '',
                'max' => '',
                'default' => '',
                'config_array' => '',
                'order' => 8,
                'element_items' => [
                    [
                        'name' => 'AI Item 1',
                        'price' => '10',
                        'description' => '',
                        'type' => '',
                        'image' => '',
                        'min' => '',
                        'max' => '100',
                        'default' => '0', 
                        'order' => 0,
                    ],
                    [
                        'name' => 'AI Item 2',
                        'price' => '5',
                        'description' => '',
                        'type' => '',
                        'image' => '',
                        'min' => '',
                        'max' => '100',
                        'default' => '0', 
                        'order' => 1,
                    ],
                ],
            ],
        ];

        $this->ai_calculator_settings_response_test = [
            'calculator_id' => 16,
            'calculator_name' => 'Test Calculator with AI',
            'calculator_description' => 'This is a test calculator',
            'minimum_total' => 100,
            'detailed_list_button' => 1,
            'email_quote_button' => 1,
            'coupon_code_button' => 1,
            'show_unit_price' => 1
        ];
    }
    public function __destruct() {
        // $this->db->close();
    }

    public function ai_validate_prompt_length( $text ) {
        $limit = 400;

        return strlen( $text ) < $limit;
    }

    //This function is used to send the user prompt to the Assistant API
    //The response is the Assistant message or an function that requires action
    public function ai_api_call( $type, $data ) {
        $request_data = [
            'type'         => $type,
            'data'         => $data,
            'license_key'  => get_option( 'df-scc-key-in-use' ),
            'is_activated' => $this->is_activated,
            'url'          => $this->get_base_url(),
        ];
        $api_endpoint = 'https://api.stylishcostcalculator.com/rest/ai-assistant-api-request';
        $response     = wp_remote_post( $api_endpoint, [
            'method'  => 'POST',
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode( $request_data ),
            'timeout' => 60,
        ] );

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();

            return $error_message;
        } else {
            $decoded_response = json_decode( wp_remote_retrieve_body( $response ), true );

            return $decoded_response;
        }
    }

    public function confirm_action_to_assistant( $thread_id, $run_id, $tool_call_id, $tool_output ) {
        $request_data = [
            'thread_id'    => $thread_id,
            'run_id'       => $run_id,
            'tool_call_id' => $tool_call_id,
            'tool_output'  => $tool_output,
            'license_key'  => get_option( 'df-scc-key-in-use' ),
            'is_activated' => $this->is_activated,
        ];
        $api_endpoint = 'https://api.stylishcostcalculator.com/rest/ai-confirm-action-to-assistant';
        $response     = wp_remote_post( $api_endpoint, [
            'method'  => 'POST',
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode( $request_data ),
            'timeout' => 60,
        ] );

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();

            return $error_message;
        } else {
            return $request_data;
        }
    }

    public function vmath_model_formula_request( $type, $data, $thread ) {
        $request_data = [
            'type'         => $type,
            'data'         => $data,
            'thread'       => $thread,
            'license_key'  => get_option( 'df-scc-key-in-use' ),
            'is_activated' => $this->is_activated,
            'url'          => $this->get_base_url(),
        ];
        $api_endpoint = 'https://api.stylishcostcalculator.com/rest/ai-vmath-request-rag';
        $response     = wp_remote_post( $api_endpoint, [
            'method'  => 'POST',
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode( $request_data ),
            'timeout' => 60,
        ] );

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            //wp_send_json( [ 'error' => "Something went wrong: $error_message" ] );
            return $error_message;
        } else {
            $decoded_response = json_decode( wp_remote_retrieve_body( $response ), true );
            //wp_send_json( $decoded_response );
            return $decoded_response;
        }
    }

    public function suggest_elements( $type, $data, $thread = null, $metadata = null ) {
        $request_data = [
            'type'         => $type,
            'data'         => $data,
            'thread'       => $thread,
            'metadata'     => $metadata,
            'license_key'  => get_option( 'df-scc-key-in-use' ),
            'is_activated' => $this->is_activated,
            'url'          => $this->get_base_url(),
        ];
        $api_endpoint = 'https://api.stylishcostcalculator.com/rest/ai-wizard-suggest-elements-v2';
        $response     = wp_remote_post( $api_endpoint, [
            'method'  => 'POST',
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode( $request_data ),
            'timeout' => 60,
        ] );

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            return $error_message;
        } else {
            $decoded_response = json_decode( wp_remote_retrieve_body( $response ), true );
            return $decoded_response;
        }
    }

    public function optimize_form( $type, $data, $thread = null ) {
        $request_data = [
            'type'         => $type,
            'data'         => $data,
            'thread'       => $thread,
            'license_key'  => get_option( 'df-scc-key-in-use' ),
            'is_activated' => $this->is_activated,
            'url'          => $this->get_base_url(),
        ];
        $api_endpoint = 'https://api.stylishcostcalculator.com/rest/ai-wizard-optimize-form-v2';
        $response     = wp_remote_post( $api_endpoint, [
            'method'  => 'POST',
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode( $request_data ),
            'timeout' => 60,
        ] );

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            return $error_message;
        } else {
            $decoded_response = json_decode( wp_remote_retrieve_body( $response ), true );
            return $decoded_response;
        }
    }
    public function setup_wizard( $type, $data, $thread = null ) {
        $request_data = [
            'type'         => $type,
            'data'         => $data,
            'thread'       => $thread,
            'license_key'  => get_option( 'df-scc-key-in-use' ),
            'is_activated' => $this->is_activated,
            'url'          => $this->get_base_url(),
        ];
        $api_endpoint = 'https://api.stylishcostcalculator.com/rest/ai-wizard-setup-wizard-step-by-step-v2';
        $response     = wp_remote_post( $api_endpoint, [
            'method'  => 'POST',
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode( $request_data ),
            'timeout' => 60,
        ] );

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            return $error_message;
        } else {
            $decoded_response = json_decode( wp_remote_retrieve_body( $response ), true );
            return $decoded_response;
        }
    }

    public function vmath_formula_to_item_item_array_helper( $vmath_formula ) {
        preg_match_all( '/Input\d+/', $vmath_formula, $matches );
        $inputs = $matches[0];

        // Map inputs to an array of items with name and price
        $items = array_map( function ( $input ) {
            return [
                'name'  => $input,
                'price' => 10,
            ];
        }, $inputs );

        return $items;
    }
    public function vmath_item_array_helper( $item_values ) {
        // Map inputs to an array of items with name and price
        $items = array_map( function ( $item ) {
            return [
                'name'         => $item->name,
                'price'        => $item->value,
                'max_quantity' => $item->max_quantity,
            ];
        }, $item_values );

        return $items;
    }

    public function get_ai_elements_array( $ai_requested_elements, $calculator_id )
    {
        $thread = [];
        $request_data = [
            'type'         => 'add-elements',
            'data'         => $ai_requested_elements,
            'thread'       => $thread,
            'license_key'  => get_option( 'df-scc-key-in-use' ),
            'is_activated' => $this->is_activated,
            'url'          => $this->get_base_url(),
        ];

        $api_endpoint = 'https://api.stylishcostcalculator.com/rest/ai-wizard-add-elements-array';
        $response     = wp_remote_post( $api_endpoint, [
            'method'  => 'POST',
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode( $request_data ),
            'timeout' => 60,
        ] );
        //$response =  $element_test;

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            //wp_send_json( [ 'error' => "Something went wrong: $error_message" ] );
            return $error_message;
        } else {
            //$decoded_response = json_decode( wp_remote_retrieve_body( $response ), true );
            $response_body = wp_remote_retrieve_body($response);
            $first_decode = json_decode($response_body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log('First JSON decoding error: ' . json_last_error_msg());
                $decoded_response = array();
            } else {
                // Decode the nested JSON in 'ai_message'
                $ai_message = str_replace('\n', "\n", $first_decode['ai_message']);
                $decoded_response = json_decode($ai_message, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    error_log('Second JSON decoding error: ' . json_last_error_msg());
                    $decoded_response = array();
                }
            }
            return $decoded_response;
        }
    }

    public function get_ai_calculator_settings_array( $ai_requested_settings, $calculator_id ) {
        $thread = [];
        $request_data = [
            'type'         => 'add-calculator-settings',
            'data'         => $ai_requested_settings,
            'thread'       => $thread,
            'license_key'  => get_option( 'df-scc-key-in-use' ),
            'is_activated' => $this->is_activated,
            'url'          => $this->get_base_url(),
        ];
        $api_endpoint = 'https://api.stylishcostcalculator.com/rest/ai-wizard-add-calculator-settings-array';
        $response     = wp_remote_post( $api_endpoint, [
            'method'  => 'POST',
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode( $request_data ),
            'timeout' => 60,
        ] );

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();

            return $error_message;
        } else {
            $response_body = wp_remote_retrieve_body($response);
            $first_decode = json_decode($response_body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log('First JSON decoding error: ' . json_last_error_msg());
                $decoded_response = array();
            } else {
                // Decode the nested JSON in 'ai_message'
                $ai_message = str_replace('\n', "\n", $first_decode['ai_message']);
                $decoded_response = json_decode($ai_message, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    error_log('Second JSON decoding error: ' . json_last_error_msg());
                    $decoded_response = array();
                }
            }
            return $decoded_response;
        }

        //return $this->ai_calculator_settings_response_test;
    }

    public function scc_ai_get_site_info( $siteURL = null ) {
        $thread = [];
        $request_data = [
            'type'         => 'get-site-info',
            'data'         => $siteURL,
            'thread'       => $thread,
            'license_key'  => get_option( 'df-scc-key-in-use' ),
            'is_activated' => $this->is_activated,
            'url'          => $siteURL,
        ];
        $api_endpoint = 'https://api.stylishcostcalculator.com/rest/ai-wizard-web-search';
        $response     = wp_remote_post( $api_endpoint, [
            'method'  => 'POST',
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode( $request_data ),
            'timeout' => 60,
        ] );

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            //wp_send_json( [ 'error' => "Something went wrong: $error_message" ] );
            return $error_message;
        } else {
            $decoded_response = json_decode( wp_remote_retrieve_body( $response ), true );
            //wp_send_json( $decoded_response );
            return $decoded_response;
        }
    }

    public function scc_ai_estimate_tokens( $text ) {
        $word_count       = str_word_count( $text );
        $character_count  = strlen( $text );
        $estimated_tokens = $word_count + ( $character_count - $word_count ) / 4;

        return $estimated_tokens;
    }

    public function sanitize_text_or_array_field( $data ) {
        if ( is_array( $data ) ) {
            foreach ( $data as $key => $value ) {
                $data[$key] = filter_var( $value, FILTER_SANITIZE_STRING );
            }
        } else {
            $data = filter_var( $data, FILTER_SANITIZE_STRING );
        }

        return $data;
    }
    public function get_base_url() {
        $scheme = isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
        $url    = $scheme . '://' . $_SERVER['HTTP_HOST'];

        return $url;
    }
}
