<?php
/**
 * Style settings section in settings page.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin/partials/section/settings
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

// Cannot access directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * This class is responsible for Style Settings in Settings page.
 *
 * @since 1.1.0
 */
class SP_WCS_License {
	/**
	 * Settings section.
	 *
	 * @param string $prefix Settings section prefix.
	 * @return void
	 */
	public static function section( $prefix ) {

		SP_WCS::createSection(
			$prefix,
			array(
				'id'     => 'license_key_section',
				'title'  => __( 'License', 'woo-category-slider-grid' ),
				'icon'   => 'fa wcsp-icon-key-01',
				'fields' => array(
					array(
						'id'   => 'license_key',
						'type' => 'license',
					),
				),
			)
		);
	}
}
