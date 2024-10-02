<?php 
namespace Enteraddons\Widgets\Logo_Carousel\Traits;
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

    public static function carouselSettings() {

        $settings = self::getDisplaySettings();

        $responsive = [];
        if( !empty( $settings['slider_items_mobile'] ) || !empty( $settings['slider_items_tablet'] ) ) {

            $items_mobile = !empty( $settings['slider_items_mobile'] ) ? $settings['slider_items_mobile'] : '';
            $items_tablet = !empty( $settings['slider_items_tablet'] ) ? $settings['slider_items_tablet'] : '';
            $items_desktop = !empty( $settings['slider_items'] ) ? $settings['slider_items'] : 3;

            //
            $mm = !empty( $settings['slider_margin_mobile'] ) ? $settings['slider_margin_mobile'] : '';
            $tablet = !empty( $settings['slider_margin_tablet'] ) ? $settings['slider_margin_tablet'] : '';
            $marginDesktop = !empty( $settings['slider_margin'] ) ? $settings['slider_margin'] : '';
            
           $responsive = [ '0' => ['items' => $items_mobile, 'margin' => $mm  ], '768' => [ 'items' => $items_tablet, 'margin' => $tablet ], '1025' => ['items' => $items_desktop, 'margin' => $marginDesktop] ]; 
        }
        
        $sliderSettings = [

            'items'         => !empty( $settings['slider_items'] ) ? $settings['slider_items'] : 3,
            'margin'        => !empty( $settings['slider_margin'] ) ? $settings['slider_margin'] : 0,
            'loop'          => !empty( $settings['slider_loop'] ) && $settings['slider_loop'] == 'yes' ? true : false,
            'smartSpeed'    => !empty( $settings['slider_smartSpeed'] ) ? $settings['slider_smartSpeed'] : 450,
            'autoplay'      => !empty( $settings['slider_autoplay'] ) && $settings['slider_autoplay'] == 'yes' ? true : false,
            'autoplayTimeout'  => !empty( $settings['slider_autoplayTimeout'] ) ? $settings['slider_autoplayTimeout'] : 8000,
            'center'        => !empty( $settings['slider_center'] ) && $settings['slider_center'] == 'yes' ? true : false,
            'animateIn'     => !empty( $settings['slider_animateIn'] ) && $settings['slider_animateIn'] == 'yes' ? true : false,
            'animateOut'    => !empty( $settings['slider_animateOut'] ) && $settings['slider_animateOut'] == 'yes' ? true : false,
            'nav'           => !empty( $settings['slider_nav'] ) && $settings['slider_nav'] == 'yes' ? true : false,
            'dots'          => !empty( $settings['slider_dots'] ) && $settings['slider_dots'] == 'yes' ? true : false,
            'mousedrag'     => !empty( $settings['slider_mouseDrag'] ) && $settings['slider_mouseDrag'] == 'yes' ? true : false,
            'autoWidth'     => !empty( $settings['slider_autoWidth'] ) && $settings['slider_autoWidth'] == 'yes' ? true : false,
            'responsive'     => $responsive

        ];

        return wp_json_encode( $sliderSettings );

    }

    protected static function linkOpen( $link, $class = '' ) {
        //
        $target = '_self';
        if( !empty( $link['is_external'] ) && $link['is_external'] == 'on' ) {
            $target = '_blank';
        }

        return '<a class="'.esc_attr( $class ).'" href="'.esc_url( $link['url'] ).'" target="'.esc_attr( $target ).'">';
    }
    protected static function linkClose() {
        return '</a>';
    }

    
}