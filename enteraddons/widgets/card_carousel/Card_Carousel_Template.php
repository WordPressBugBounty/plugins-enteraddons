<?php
namespace Enteraddons\Widgets\Card_Carousel;
/**
 * Enteraddons widget template class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

class Card_Carousel_Template {

	use \Enteraddons\Widgets\Card_Carousel\Traits\Templates_Components;
	use \Enteraddons\Widgets\Card_Carousel\Traits\Template_1;

	private static $settingsData;
	
	public static function setDisplaySettings( $data ) {
		self::$settingsData = $data;
	}

	private static function getDisplaySettings() {
		return self::$settingsData;
	}

	public static function renderTemplate() {
		self::markup_style_1();
	}

}