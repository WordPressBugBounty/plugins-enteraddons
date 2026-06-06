<?php
namespace Enteraddons\AI;
/**
 * Enteraddons admin class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;

class AI_Template_Import {


	function __construct() {

		add_action('elementor/ajax/register_actions', array(
                $this,
                'register_ajax_actions'
            ) , 20);
	}


	public static function get_template_content( $epId ) {

		$actions 	    = isset( $_REQUEST['actions'] ) ? sanitize_text_field( $_REQUEST['actions'] ) : "";
		$getActionData  = json_decode( stripcslashes( $actions ), true );
		$getActionData  = reset( $getActionData );
		$template_id 	= !empty( $getActionData['data']['template_id'] ) ? $getActionData['data']['template_id'] : '';
		$getPrompt 	= !empty( $getActionData['data']['prompt_text'] ) ? $getActionData['data']['prompt_text'] : '';
        $modelType  = !empty( $getActionData['data']['model_type'] ) ? sanitize_text_field( $getActionData['data']['model_type'] ) : 'EA-AI EA1';
        
        //
        $slashCommands = new \Enteraddons\AI\AI_Slash_Commands();
        if( !empty( $getPrompt ) && $slashCommands->is_slash_command( $getPrompt ) ) {
            $getPrompt = $slashCommands->get_slash_commands( $getPrompt );
        }

        $body = [ 'user_prompt' => $getPrompt, 'model' => $modelType, 'package_type' => \Enteraddons\Classes\Helper::versionType(), 'license_key' => get_option('enteraddons_plugin_lic_Key') ];

        $api = new \Enteraddons\AI\AI_API();
        $response = $api->post( 'text/completion', $body );
        
        if( empty( $response ) ) {
            throw new \Exception( 'Something went wrong. Please try again.' );
        }

        if( empty( $response['success'] ) || $response['success'] == false ) {
            throw new \Exception( $response['message'] );
        }
        
        //
        $template_content = [];
        if( !empty( $response['success'] ) && $response['success'] == true ) {
            $template_content = [ 'content' => $response['data'] ];
        }

		$ls = new Template_Library_Source();

		return $ls->get_data( [$template_content, $epId ] );

	}
	
	public function register_ajax_actions( $ajax ) {

		if ( ! current_user_can( 'edit_posts' ) ) {
			throw new \Exception( 'Access Denied' );
		}

		if ( !isset( $_REQUEST['actions'] ) ) {
            return;
        }

        $getActionData = json_decode( stripcslashes( $_REQUEST['actions'] ), true );

        $getActionData  = reset( $getActionData );

        if( 'get_ai_template_data' != $getActionData['action'] ) {
        	return;
        }

 		$ajax->register_ajax_action('get_ai_template_data', function ( $data ) {

 			if( !empty( $data['editor_post_id'] ) ) {

 				$epId = absint( $data['editor_post_id'] );

 				if( !empty( get_post( $epId ) ) ) {
 					\Elementor\Plugin::instance()->db->switch_to_post( $epId );
 				} else {
 					throw new \Exception( esc_html__( 'Post not found.', 'enteraddons' ) );
 				}

 			}
 			//
 			if ( empty( $data['template_id'] ) ) {
				throw new \Exception( esc_html__( 'Template id missing', 'enteraddons' ) );
			}

            return $this->get_template_content( $epId );
        });

    }

}

