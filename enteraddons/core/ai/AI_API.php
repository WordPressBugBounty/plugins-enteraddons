<?php
namespace Enteraddons\AI;

/**
 * Enteraddons ai
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */


class AI_API {

    private $api_url = 'https://api.enteraddons.com/wp-json/enteraddons/ai/v1/';
    private $api_key;
    private $timeout = 120;

    public function __construct() {
        $this->api_key = 'EAT809-TAE854-AEE956-YUT235';
    }

    /**
     * Send POST request
     */
    public function post( $endpoint = '', $body = [] ) {

        $url = trailingslashit( $this->api_url ) . ltrim($endpoint, '/');

        $response = wp_remote_post( $url, [
            'timeout'     => $this->timeout,
            'httpversion' => '1.1',
            'sslverify'   => false, // disable only in dev
            'headers'     => [
                'Content-Type' => 'application/json',
                'X-API-Key'    => $this->api_key,
            ],
            'body'        => wp_json_encode( $body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ),
            'data_format' => 'body'
        ]);

        //
        if ( is_wp_error( $response ) ) {
            return [
                'success' => false,
                'message' => $response->get_error_message()
            ];
        }

        //
        $getResponse = $this->handle_response( $response );

        if( empty( $getResponse['success'] ) && !empty( $getResponse['message'] ) ) {
            return [
                'success' => false,
                'message' => $getResponse['message']
            ];
        }

        return $getResponse;
    }

    /**
     * Handle API response
     */
    private function handle_response( $response ) {

        $body = wp_remote_retrieve_body( $response );

        if ( json_last_error() !== JSON_ERROR_NONE ) {
            return [
                'success' => false,
                'message' => 'Invalid JSON response',
                'raw'     => $body
            ];
        }

        $decoded = json_decode( $body, true );

        if( !empty( $decoded['code'] ) && $decoded['code'] == 'error_code' ) {

            return [
                'success' => false,
                'message' => $decoded['message'] ?? '',
            ];

        }

        //
        if( !empty( $decoded['code'] ) && $decoded['code'] == 'internal_server_error' ) {

            return [
                'success' => false,
                'message' => 'Unable to complete the request. Please try again or adjust your input.',
            ];

        }
        
        return [
            'success' => true,
            'data'    => $decoded
        ];

    }

}
