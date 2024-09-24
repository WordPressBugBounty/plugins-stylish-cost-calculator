<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
class AiWizardController {

    protected $db;
    protected $is_activated;
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
        $api_endpoint = 'https://api.stylishcostcalculator.com/rest/ai-vmath-request';
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
        try {
            $request_data = [
                'type'         => $type,
                'data'         => $data,
                'thread'       => $thread,
                'metadata'     => $metadata,
                'license_key'  => null,
                'is_activated' => false,
                'url'          => $this->get_base_url(),
            ];

            $api_endpoint = 'https://api.stylishcostcalculator.com/rest/ai-wizard-suggest-elements';
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
        } catch ( Exception $e ) {
            return $e->getMessage();
        }
    }

    public function optimize_form( $type, $data, $thread = null ) {
        $request_data = [
            'type'         => $type,
            'data'         => $data,
            'thread'       => $thread,
            'license_key'  => null,
            'is_activated' => false,
            'url'          => $this->get_base_url(),
        ];
        $api_endpoint = 'https://api.stylishcostcalculator.com/rest/ai-wizard-optimize-form';
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
    public function setup_wizard( $type, $data, $thread = null ) {
        $request_data = [
            'type'         => $type,
            'data'         => $data,
            'thread'       => $thread,
            'license_key'  => null,
            'is_activated' => false,
            'url'          => $this->get_base_url(),
        ];
        $api_endpoint = 'https://api.stylishcostcalculator.com/rest/ai-wizard-setup-wizard-step-by-step';
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
