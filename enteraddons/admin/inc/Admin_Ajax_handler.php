<?php
namespace Enteraddons\Admin;
/**
 * Enteraddons admin
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */


if( !defined( 'WPINC' ) ) {
    die;
}

if( !class_exists('Admin_Ajax_handler') ) {

    class Admin_Ajax_handler {

        private static $instance = null;

        function __construct() {
            add_action( 'wp_ajax_settings_data_save_action',  [$this , 'settings_data_save'] );
        }
        
        public static function getInstance() {
            if( self::$instance == null ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function settings_data_save() {
            $getData = [];

            if ( ! wp_doing_ajax() ) {
                wp_die( 'Invalid request' );
            }

            // Check user permission
            if( !current_user_can('manage_options') ) {
               wp_die( 'Invalid request' );
            }

            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            $getPostedData = !empty( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : '';

            $data = array();
            parse_str( $getPostedData, $data );

            if( !isset( $data['enteraddons_settings_nonce'] ) || ( isset( $data['enteraddons_settings_nonce'] ) &&  !wp_verify_nonce( wp_unslash( $data['enteraddons_settings_nonce'] ), 'enteraddons_settings_nonce_action' ) ) ) {
                wp_send_json_error();
               
            }

            $getData['widgets'] = isset( $data['enteraddons_widgets'] ) && is_array( $data['enteraddons_widgets'] ) ? array_map( 'sanitize_text_field', $data['enteraddons_widgets'] ) : [];

            $getData['integration'] = isset( $data['enteraddons_integration'] ) && is_array( $data['enteraddons_integration'] ) ? array_map( 'sanitize_text_field', $data['enteraddons_integration'] ) : [];
            
            $getData['extensions'] = isset( $data['enteraddons_extensions'] ) && is_array( $data['enteraddons_extensions'] ) ? array_map( 'sanitize_text_field', $data['enteraddons_extensions'] ) : [];
            
            update_option( ENTERADDONS_OPTION_KEY,  $getData );

            // Add WordPress admin notice
            add_settings_error( 'enteraddons_messages', 'enteraddons_message', esc_html__( 'Settings Saved', 'enteraddons' ), 'updated' );


            wp_send_json_success();
            
        }

    }

}