<?php
/**
 * The metabox of the plugin.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin/partials
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

/**
 * The metabox main class.
 */
class SP_WCS_Metaboxs {

	/**
	 * Preview metabox.
	 *
	 * @param string $prefix The metabox main Key.
	 * @return void
	 */
	public static function preview_metabox( $prefix ) {
		SP_WCS::createMetabox(
			$prefix,
			array(
				'title'        => __( 'Live Preview', 'woo-category-slider-grid' ),
				'post_type'    => 'sp_wcslider',
				'show_restore' => false,
				'context'      => 'normal',
			)
		);
		SP_WCS::createSection(
			$prefix,
			array(
				'fields' => array(
					array(
						'type' => 'preview',
					),
				),
			)
		);
	}

	/**
	 * Side metabox.
	 *
	 * @return void
	 */
	public static function side_metabox() {
		SP_WCS::createMetabox(
			'sp_wcsp_copy_shortcode',
			array(
				'title'             => __( 'How To Use', 'woo-category-slider-grid' ),
				'post_type'         => 'sp_wcslider',
				'context'           => 'side',
				'show_restore'      => false,
				'sp_wcsp_shortcode' => false,
			)
		);

		SP_WCS::createSection(
			'sp_wcsp_copy_shortcode',
			array(
				'fields' => array(
					array(
						'type'      => 'shortcode',
						'shortcode' => 'manage_view',
						'class'     => 'sp_tpro-admin-sidebar',
					),
				),
			)
		);

		/**
		 * Page builder supported metabox.
		 *
		 * @param string 'sp_wcsp_promotion_section' The metabox main Key.
		 * @return void
		 */
		SP_WCS::createMetabox(
			'sp_wcsp_promotion_section',
			array(
				'title'             => __( 'Page Builders', 'woo-category-slider-grid' ),
				'post_type'         => 'sp_wcslider',
				'context'           => 'side',
				'show_restore'      => false,
				'sp_wcsp_shortcode' => false,
			)
		);

		SP_WCS::createSection(
			'sp_wcsp_promotion_section',
			array(
				'fields' => array(
					array(
						'type'      => 'shortcode',
						'shortcode' => false,
						'class'     => 'sp_tpro-admin-sidebar',
					),
				),
			)
		);
	}

	/**
	 * Metabox banner.
	 *
	 * @param string $prefix metabox.
	 * @return void
	 */
	public static function metabox_layout( $prefix ) {

		// Create a metabox.
		SP_WCS::createMetabox(
			$prefix,
			array(
				'title'        => __( 'WooCategory', 'woo-category-slider-grid' ),
				'post_type'    => 'sp_wcslider',
				'show_restore' => false,
				'context'      => 'normal',
				'priority'     => 'default',
			)
		);

		// Create a section.
		SP_WCS::createSection(
			$prefix,
			array(
				'fields' => array(
					array(
						'type'  => 'heading',
						'image' => plugin_dir_url( __DIR__ ) . 'img/woo-category-slider-logo-new.svg',
						'after' => '<i class="fa fa-life-ring"></i> Support',
						'link'  => 'https://shapedplugin.com/support/',
						'class' => 'wcsp-admin-header',
					),
					array(
						'id'          => 'wcsp_layout_presets',
						'type'        => 'image_select',
						'title'       => __( 'Layout Preset', 'woo-category-slider-grid' ),
						'class'       => 'wcsp_layout_presets',
						'option_name' => true,
						'options'     => array(
							'carousel'  => array(
								'image'           => SP_WCS_URL . 'admin/img/layout-presets/carousel.svg',
								'option_name'     => __( 'Carousel', 'woo-category-slider-grid' ),
								'option_demo_url' => 'https://demo.shapedplugin.com/woocommerce-category-slider/#carousel',
							),
							'slider'    => array(
								'image'           => SP_WCS_URL . 'admin/img/layout-presets/slider.svg',
								'option_name'     => __( 'Slider', 'woo-category-slider-grid' ),
								'option_demo_url' => 'https://demo.shapedplugin.com/woocommerce-category-slider/#slider',
							),
							'multi_row' => array(
								'image'           => SP_WCS_URL . 'admin/img/layout-presets/multi_row_carousel.svg',
								'option_name'     => __( 'Multi-row', 'woo-category-slider-grid' ),
								'option_demo_url' => 'https://demo.shapedplugin.com/woocommerce-category-slider/#multi-row-carousel',
								'class'           => 'wcsp-pro-feature',
								'pro_only'        => true,
							),
							'grid'      => array(
								'image'           => SP_WCS_URL . 'admin/img/layout-presets/grid.svg',
								'option_name'     => __( 'Grid', 'woo-category-slider-grid' ),
								'option_demo_url' => 'https://demo.shapedplugin.com/woocommerce-category-slider/#grid',
								'class'           => 'wcsp-pro-feature',
								'pro_only'        => true,
							),
							'block-1'   => array(
								'image'           => SP_WCS_URL . 'admin/img/layout-presets/hierarchical_grid.svg',
								'option_name'     => __( 'Hierarchy Grid', 'woo-category-slider-grid' ),
								'option_demo_url' => 'https://demo.shapedplugin.com/woocommerce-category-slider/#hierarchical-grid',
								'class'           => 'wcsp-pro-feature',
								'pro_only'        => true,
							),
							'block-2'   => array(
								'image'           => SP_WCS_URL . 'admin/img/layout-presets/hierarchical_1.svg',
								'option_name'     => __( 'Hierarchy One', 'woo-category-slider-grid' ),
								'option_demo_url' => 'https://demo.shapedplugin.com/woocommerce-category-slider/#hierarchy-one',
								'class'           => 'wcsp-pro-feature',
								'pro_only'        => true,
							),
							'block-3'   => array(
								'image'           => SP_WCS_URL . 'admin/img/layout-presets/hierarchical_2.svg',
								'option_name'     => __( 'Hierarchy Two', 'woo-category-slider-grid' ),
								'option_demo_url' => 'https://demo.shapedplugin.com/woocommerce-category-slider/#hierarchy-two',
								'class'           => 'wcsp-pro-feature',
								'pro_only'        => true,
							),
							'block-4'   => array(
								'image'           => SP_WCS_URL . 'admin/img/layout-presets/hierarchical_3.svg',
								'option_name'     => __( 'Hierarchy Three', 'woo-category-slider-grid' ),
								'option_demo_url' => 'https://demo.shapedplugin.com/woocommerce-category-slider/#hierarchy-three',
								'class'           => 'wcsp-pro-feature',
								'pro_only'        => true,
							),
							'block-5'   => array(
								'image'           => SP_WCS_URL . 'admin/img/layout-presets/hierarchical_4.svg',
								'option_name'     => __( 'Hierarchy Four', 'woo-category-slider-grid' ),
								'option_demo_url' => 'https://demo.shapedplugin.com/woocommerce-category-slider/#hierarchy-four',
								'class'           => 'wcsp-pro-feature',
								'pro_only'        => true,
							),
							'inline'    => array(
								'image'           => SP_WCS_URL . 'admin/img/layout-presets/inline.svg',
								'option_name'     => __( 'Inline', 'woo-category-slider-grid' ),
								'option_demo_url' => 'https://demo.shapedplugin.com/woocommerce-category-slider/#inline',
								'class'           => 'wcsp-pro-feature',
								'pro_only'        => true,
							),
						),
						'default'     => 'carousel',
					),
					array(
						'id'          => 'wcsp_carousel_style',
						'type'        => 'image_select',
						'title'       => __( 'Carousel Style', 'woo-category-slider-grid' ),
						'class'       => 'wcsp_layout_presets carousel-style sp-no-selected-icon',
						'option_name' => true,
						'options'     => array(
							'standard' => array(
								'image'       => SP_WCS_URL . 'admin/img/carousel-style/standard.svg',
								'option_name' => __( 'Standard', 'woo-category-slider-grid' ),
							),
							'ticker'   => array(
								'image'       => SP_WCS_URL . 'admin/img/carousel-style/ticker.svg',
								'option_name' => __( 'Ticker', 'woo-category-slider-grid' ),
								'pro_only'    => true,
								'class'       => 'wcsp-pro-feature',
							),
						),
						'default'     => 'standard',
						'dependency'  => array(
							'wcsp_layout_presets',
							'==',
							'carousel',
						),
					),
					array(
						'id'          => 'wcsp_slider_orientation',
						'type'        => 'image_select',
						'title'       => __( 'Slider Orientation', 'woo-category-slider-grid' ),
						'class'       => 'wcsp_layout_presets carousel-style sp-no-selected-icon',
						'option_name' => true,
						'options'     => array(
							'horizontal' => array(
								'image'       => SP_WCS_URL . 'admin/img/slider-orientation/horizontal.svg',
								'option_name' => __( 'Horizontal', 'woo-category-slider-grid' ),
							),
							'vertical'   => array(
								'image'       => SP_WCS_URL . 'admin/img/slider-orientation/vertical.svg',
								'option_name' => __( 'Vertical', 'woo-category-slider-grid' ),
								'pro_only'    => true,
								'class'       => 'wcsp-pro-feature',
							),
						),
						'default'     => 'horizontal',
						'dependency'  => array(
							'wcsp_layout_presets',
							'==',
							'slider',
						),
					),
					array(
						'id'          => 'wcsp_block_orientation',
						'type'        => 'image_select',
						'title'       => __( 'Hierarchical Grid Style', 'woo-category-slider-grid' ),
						'class'       => 'wcsp_layout_presets wcsp_block_orientation sp-no-selected-icon',
						'only_pro'    => true,
						'option_name' => true,
						'options'     => array(
							'block-1' => array(
								'image'       => SP_WCS_URL . 'admin/img/hierarchical-grid-style/block-1.svg',
								'option_name' => __( 'Hierarchical Grid', 'woo-category-slider-grid' ),
								'pro_only'    => true,
							),
							'block-2' => array(
								'image'       => SP_WCS_URL . 'admin/img/hierarchical-grid-style/block-2.svg',
								'option_name' => __( 'Hierarchical 1', 'woo-category-slider-grid' ),
								'pro_only'    => true,
							),
							'block-3' => array(
								'image'       => SP_WCS_URL . 'admin/img/hierarchical-grid-style/block-3.svg',
								'option_name' => __( 'Hierarchical 2', 'woo-category-slider-grid' ),
								'pro_only'    => true,
							),
							'block-4' => array(
								'image'       => SP_WCS_URL . 'admin/img/hierarchical-grid-style/block-4.svg',
								'option_name' => __( 'Hierarchial 3', 'woo-category-slider-grid' ),
								'pro_only'    => true,
							),
							'block-5' => array(
								'image'       => SP_WCS_URL . 'admin/img/hierarchical-grid-style/block-5.svg',
								'option_name' => __( 'Hierarchical 4', 'woo-category-slider-grid' ),
								'pro_only'    => true,
							),
						),
						'default'     => 'block-1',
						'dependency'  => array(
							'wcsp_layout_presets',
							'==',
							'block',
						),
					),
					array(
						'type'    => 'notice',
						'style'   => 'normal',
						'content' => sprintf(
							/* translators: 1: start link and bold tag, 2: close tag. */
							__( 'To enhance your store with beautiful Ticker, Multi-row, Grid, Hierarchy Grid, Inline layouts, and more to boost sales, %1$sUpgrade to Pro!%2$s', 'woo-category-slider-grid' ),
							'<a href="https://shapedplugin.com/woocategory/?ref=115#pricing" target="_blank"><b>',
							'</b></a>'
						),
					),
				), // End of fields array.
			)
		);
	}

	/**
	 * Metabox.
	 *
	 * @param string $prefix metabox prefix.
	 * @return void
	 */
	public static function metabox( $prefix ) {

		//
		// Create a metabox.
		//
		SP_WCS::createMetabox(
			$prefix,
			array(
				'title'        => __( 'Shortcode Section', 'woo-category-slider-grid' ),
				'post_type'    => 'sp_wcslider',
				'show_restore' => false,
				'theme'        => 'light',
				'context'      => 'normal',
				'priority'     => 'default',
				'class'        => 'sp_wcsp_shortcode_generator',
			)
		);
		SP_WCS_General::section( $prefix );
		SP_WCS_Display::section( $prefix );
		SP_WCS_Thumbnail::section( $prefix );
		SP_WCS_Slider::section( $prefix );
	}
}
