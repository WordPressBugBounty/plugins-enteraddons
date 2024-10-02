<?php 
namespace Enteraddons\Widgets\Calltoaction\Traits;
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
use \Enteraddons\Classes\Helper;
trait Templates_Components {
	
    // Set Settings options
    protected static function getSettings() {
        return self::getDisplaySettings();
    }

    protected static function title() {
        $settings = self::getSettings();
        //
        $tag = 'h1'; // Default tag
    
        if ( ! empty( $settings['tag'] ) ) {                                
            // Check if the tag is allowed, case-insensitive
            if ( in_array( strtolower( $settings['tag'] ), [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ], true ) ) {
                $tag = $settings['tag']; 
            }
        }
        if( !empty( $settings['title'] ) ) {
            echo '<'.tag_escape( $tag ).' class="cta-title">'.esc_html( $settings['title'] ).'</'.tag_escape( $tag ).'>';
        }
    }

    protected static function descriptions() {
        $settings = self::getSettings();
        //
        if( !empty( $settings['descriptions'] ) ) {
            echo '<p class="cta-desc">'.wp_kses( $settings['descriptions'], 'post' ).'</p>';
        }
    }

    protected static function button() {

        $settings = self::getSettings();
        $btnClass = 'enteraddons-btn'.' '.$settings['btn_hover_effect'];

        $beforeIcon = !empty( $settings['before_icon'] ) ? Helper::getElementorIcon( $settings['before_icon'] ) : '';
        $afterIcon  = !empty( $settings['after_icon'] ) ? Helper::getElementorIcon( $settings['after_icon']) : '';

        $btnContent = '<span class="dual-icon-left">'.Helper::allowFormattingTagHtml($beforeIcon).'</span>'.esc_html($settings['link_label']).'<span class="dual-icon-right">'.Helper::allowFormattingTagHtml($afterIcon).'</span>';
        //
        echo '<div class="cta-button-wrapper">';
        echo wp_kses_post( Helper::getElementorLinkHandler( $settings['link'], wp_kses_post( $btnContent ), esc_attr( $btnClass ) ) );
        echo '</div>';
    }

}