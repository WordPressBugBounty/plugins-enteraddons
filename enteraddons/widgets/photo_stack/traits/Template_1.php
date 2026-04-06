<?php 
namespace Enteraddons\Widgets\Photo_Stack\Traits;
/**
 * Enteraddons team template class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait Template_1 {
	
	public static function markup_style_1() {
        $settings   = self::getSettings();
		?>
        <div class="ea-photo-stack-wrap">
          <?php   if(!empty($settings['image_list'])):
          foreach ($settings['image_list'] as $item): ?>

            <div class="ea-photo-stack-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?> <?php echo esc_attr( $settings['image_animation']); ?>">
               <?php 
                if( !empty( $item['link']['url'] ) ) {
                    echo self::linkOpen( $item['link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                self::image($item);

                if( !empty( $item['link']['url'] ) ) {
                echo self::linkClose(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                    ?>
            </div>
            <?php endforeach; endif; ?>
        </div>
             


		<?php
	}
}

