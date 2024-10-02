<?php 
namespace Enteraddons\Widgets\Service_Card\Traits;
/**
 * Enteraddons template class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait Templates_Components {
	
    // Set Settings options
    protected static function getSettings() {
        return self::getDisplaySettings();
    }

    protected static function title() {
        $settings = self::getSettings();

        if( !empty( $settings['title'] ) ) {
           
            $tag = 'h1'; // Default tag
    
            if ( ! empty( $settings['title_tag'] ) ) {                                
                // Check if the tag is allowed, case-insensitive
                if ( in_array( strtolower( $settings['title_tag'] ), [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ], true ) ) {
                    $tag = $settings['title_tag']; 
                }
            }
            echo '<div class="media--body"><'.tag_escape( $tag ).' class="enteraddons-service-title">'.esc_html($settings['title']).'</'.tag_escape( $tag ).'></div>';
        }

    }

    protected static function image() {

        $settings = self::getSettings();
        $altText     = \Elementor\Control_Media::get_image_alt( $settings['img'] );
        echo '<div class="card-thumb">
                <img src="'.esc_url( $settings['img']['url'] ).'" alt="'.esc_attr( $altText ).'">
            </div>';
    }

    protected static function icon() {
        $settings = self::getSettings();        
        echo '<span class="card-icon">'.\Enteraddons\Classes\Helper::getElementorIcon( $settings['icon'] ).'</span>';
    }

    protected static function linkOpen() {
        $settings = self::getSettings();
        //
        $target = '_self';
        if( !empty( $settings['link']['is_external'] ) && $settings['link']['is_external'] == 'on' ) {
            $target = '_blank';
        }

        return '<a class="enteraddons-card-anchor" href="'.esc_url( $settings['link']['url'] ).'" target="'.esc_attr( $target ).'">';
    }
    protected static function linkClose() {
        return '</a>';
    }


}