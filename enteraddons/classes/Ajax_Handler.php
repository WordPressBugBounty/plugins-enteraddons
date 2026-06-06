<?php
namespace Enteraddons\Classes;

/**
 * Enteraddons helper class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

if( !class_exists('Ajax_Handler') ) {

	class Ajax_Handler {

		function __construct() {

			add_action('wp_ajax_mailchimp_action_fire', [__CLASS__, 'mailchimp_ajax_maping'] );
			add_action('wp_ajax_nopriv_mailchimp_action_fire', [__CLASS__, 'mailchimp_ajax_maping']);
		}

		public static function mailchimp_ajax_maping() {

            if( !isset( $_POST['nonce_id'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce_id'] ) ), 'enteraddons_newsletter_nonce_action'  ) ) {
                echo wp_json_encode( [ 'status' => false, 'type' => 'danger', 'msg' => esc_html__( 'Invalid request.', 'enteraddons' ) ] );
	        	wp_die();
            }

			$msg = [];
			$getKey = get_option(ENTERADDONS_OPTION_KEY);

			if(  !isset( $getKey['integration']['mailchimp_token'] ) && empty( $getKey['integration']['mailchimp_token'] ) ) {
				echo wp_json_encode( [ 'status' => false, 'type' => 'danger', 'msg' => esc_html__( 'Please set your mailchimp API key', 'enteraddons' ) ] );
				wp_die();
			}

			$listid = $email = '';

			// List Id Check 
			if( !empty( $_POST['list_id'] ) ) {
				$listid = sanitize_text_field( wp_unslash( $_POST['list_id'] ) );
			} else {
				echo wp_json_encode( [ 'status' => false, 'type' => 'danger', 'msg' => esc_html__( 'Mailchimp list ID missing.', 'enteraddons' ) ] );
				wp_die();
			}
			// Mail Id Check
	        if( !empty( $_POST['email'] ) && filter_var( wp_unslash( $_POST['email'] ), FILTER_VALIDATE_EMAIL ) ){
	        	$email = sanitize_email( wp_unslash( $_POST['email'] ) );
	        } else {
	        	echo wp_json_encode( [ 'status' => false, 'type' => 'danger', 'msg' => esc_html__( 'Please enter valid mail ID.', 'enteraddons' ) ] );
	        	wp_die();
	        }

	        //
			if( !empty( $email ) && !empty( $listid ) ) {

                // Mailchimp credentials
                $api_key = $getKey['integration']['mailchimp_token'] ?? '';
                $list_id = $listid;
                $dc      = substr($api_key, strpos($api_key, '-') + 1); // datacenter

                // Mailchimp API URL
                $url = "https://$dc.api.mailchimp.com/3.0/lists/$list_id/members";

                // Payload
                $body = [
                    "email_address" => $email,
                    "status"        => "pending",
                ];

                // API request using wp_remote_post()
                $response = wp_remote_post($url, [
                    'method'      => 'POST',
                    'headers'     => [
                        'Authorization' => !empty( $api_key ) ? "Basic " . base64_encode("user:$api_key") : '',
                        'Content-Type'  => 'application/json'
                    ],
                    'body'        => json_encode($body),
                    'timeout'     => 20,
                ]);

                // Check WordPress-level error
                if (is_wp_error($response)) {
                    $msg = [ 'status' => false, 'type' => 'danger', 'msg' => $response->get_error_message() ];
                }

                // // Decode Mailchimp response
                $api_result = json_decode(wp_remote_retrieve_body($response));

                // Mailchimp error handling
                if (!empty($api_result->status) && $api_result->status == 400) {
                    $msg = [ 'status' => false, 'type' => 'danger', 'msg' => $api_result->detail ];
                }
                
                // Mailchimp success
                if (!empty($api_result->status) && $api_result->status == 'pending') {
                    $msg = [ 'status' => true, 'type' => 'success', 'msg' => esc_html__( 'Thank you! Please check your email to confirm your subscription.', 'enteraddons' ) ];
                }

			}  else {
				$msg = [ 'status' => false, 'type' => 'danger', 'msg' => esc_html__( 'Sorry something went wrong. Please try again.', 'enteraddons' ) ];
			}

			echo wp_json_encode($msg);

	        wp_die();


		}

	} // Class End

} // Check condition end
