<?php
/**
 * Framework license fields.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin/partials/section/settings
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_WCS_Field_license' ) ) {
	/**
	 *
	 * Field: license
	 *
	 * @since 3.3.16
	 * @version 3.3.16
	 */
	class SP_WCS_Field_license extends SP_WCS_Fields {

		/**
		 * Field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Render
		 *
		 * @return void
		 */
		public function render() {
			echo wp_kses_post( $this->field_before() );
			?>
				<div class="woo-category-slider-license text-center">
					<h3><?php esc_html_e( 'You\'re using Woo Category Slider Lite - No License Needed. Enjoy', 'woo-category-slider-grid' ); ?>! 🙂</h3>
					<p><?php esc_html_e( 'Upgrade to Woo Category Slider Pro and unlock all the features.', 'woo-category-slider-grid' ); ?></p>
					<div class="woo-category-slider-license-area">
						<div class="woo-category-slider-license-key">
							<div class="spwps-upgrade-button"><a href="https://shapedplugin.com/woocategory/#pricing" target="_blank"><?php esc_html_e( 'Upgrade To Pro Now', 'woo-category-slider-grid' ); ?></a></div>
						</div>
					</div>
				</div>
				<?php
				echo wp_kses_post( $this->field_after() );
		}
	}
}
