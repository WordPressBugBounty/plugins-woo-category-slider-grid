<?php
/**
 * Thumbnail settings tab.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin/partials/section/metabox
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

// Cannot access directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * This class is responsible for Thumbnail settings tab.
 *
 * @since 1.0.0
 */
class SP_WCS_Thumbnail {
	/**
	 * Thumbnail section.
	 *
	 * @param array $prefix Thumbnail section prefix.
	 * @return void
	 */
	public static function section( $prefix ) {

		SP_WCS::createSection(
			$prefix,
			array(
				'title'  => __( 'Thumbnail Settings', 'woo-category-slider-grid' ),
				'icon'   => 'fa fa-image',
				'fields' => array(
					array(
						'id'         => 'wcsp_thumbnail',
						'type'       => 'switcher',
						'title'      => __( 'Thumbnail', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Show/Hide thumbnail.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
						'text_width' => 80,
						'default'    => true,
					),
					array(
						'id'         => 'wcsp_thumbnail_size',
						'type'       => 'image_sizes',
						'title'      => __( 'Dimensions', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Set dimension for thumbnail.', 'woo-category-slider-grid' ),
						'chosen'     => true,
						'default'    => 'full',
						'dependency' => array(
							'wcsp_thumbnail',
							'==',
							'true',
						),
					),
					array(
						'id'                => 'wcsp_cat_thumb_width_height',
						'type'              => 'dimensions_advanced',
						'only_pro'          => true,
						'title'             => __( 'Custom Size', 'woo-category-slider-grid' ),
						'subtitle'          => __( 'Set a custom width and height of the thumbnail.', 'woo-category-slider-grid' ),
						'chosen'            => true,
						'class'             => 'wcsp-cat-thum-size',
						'bottom'            => false,
						'left'              => false,
						'color'             => false,
						'pro_only'          => true,
						'top_icon'          => '<i class="fa fa-arrows-h"></i>',
						'right_icon'        => '<i class="fa fa-arrows-v"></i>',
						'top_placeholder'   => 'width',
						'right_placeholder' => 'height',
						'styles'            => array(
							'Hard-crop',
							'Soft-crop',
						),
						'default'           => array(
							'top'   => '400',
							'right' => '445',
							'style' => 'Hard-crop',
							'unit'  => 'px',
						),
						'attributes'        => array(
							'min' => 0,
						),
						'dependency'        => array(
							'wcsp_thumbnail|wcsp_thumbnail_size',
							'==|==',
							'true|custom',
						),
					),
					array(
						'id'         => 'wcsp_thumbnail_2x_size',
						'class'      => 'pro_only_field',
						'type'       => 'switcher',
						'only_pro'   => true,
						'title'      => __( 'Load 2x Resolution Image in Retina Display' ),
						'subtitle'   => __(
							'You should upload 2x sized images to show in retina display.
						',
							'woo-category-slider-grid'
						),
						'text_on'    => __( 'ENABLED', 'woo-category-slider-grid' ),
						'text_off'   => __( 'DISABLED', 'woo-category-slider-grid' ),
						'text_width' => 96,
						'default'    => false,
						'dependency' => array(
							'wcsp_thumbnail|wcsp_thumbnail_size',
							'==|==',
							'true|custom',
						),
					),
					array(
						'id'         => 'wcsp_custom_thumb',
						'type'       => 'switcher',
						'class'      => 'pro_only_field',
						'only_pro'   => true,
						'title'      => __( 'Custom Thumbnail', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'A placeholder image for those category(s) which  has no thumbnail.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
						'text_width' => 80,
						'default'    => true,
						'dependency' => array(
							'wcsp_thumbnail',
							'==',
							'true',
						),
					),
					array(
						'id'          => 'wcsp_cat_thumbnail_shape',
						'class'       => 'wcsp_cat_content_position thumbnail_shape sp-no-selected-icon',
						'type'        => 'image_select',
						'only_pro'    => true,
						'option_name' => true,
						'title'       => __( 'Shape', 'woo-category-slider-grid' ),
						'subtitle'    => __( 'Choose a shape for thumbnail.', 'woo-category-slider-grid' ),
						'desc'        => __( 'To unleash your creativity with flexible Category Thumbnail styling options, <a href="https://shapedplugin.com/plugin/woocommerce-category-slider-pro/?ref=115" target="_blank"><b>Upgrade to Pro!</b></a>', 'woo-category-slider-grid' ),
						'options'     => array(
							'square'  => array(
								'image'       => SP_WCS_URL . 'admin/img/shapes/square.svg',
								'option_name' => __( 'Square', 'woo-category-slider-grid' ),
							),
							'rounded' => array(
								'image'       => SP_WCS_URL . 'admin/img/shapes/rounded.svg',
								'option_name' => __( 'Rounded', 'woo-category-slider-grid' ),
								'pro_only'    => true,
							),
							'circle'  => array(
								'image'       => SP_WCS_URL . 'admin/img/shapes/circle.svg',
								'option_name' => __( 'Circle', 'woo-category-slider-grid' ),
								'pro_only'    => true,
							),
							'custom'  => array(
								'image'       => SP_WCS_URL . 'admin/img/shapes/custom-border-radius.svg',
								'option_name' => __( 'Custom', 'woo-category-slider-grid' ),
								'pro_only'    => true,
							),
						),
						'default'     => 'square',
						'dependency'  => array(
							'wcsp_thumbnail',
							'==',
							'true',
						),
					),

					array(
						'id'         => 'wcsp_category_thumb_border',
						'type'       => 'switcher',
						'title'      => __( 'Border', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Set border for the category thumbnail.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
						'text_width' => 80,
						'default'    => true,
						'dependency' => array(
							'wcsp_thumbnail',
							'==',
							'true',
						),
					),
					array(
						'id'          => 'wcsp_cat_thumb_border',
						'type'        => 'spacing',
						'title'       => __( 'Border Width', 'woo-category-slider-grid' ),
						'subtitle'    => __( 'Set border for thumbnail.', 'woo-category-slider-grid' ),
						'hover_color' => false,
						'default'     => array(
							'top'    => '1',
							'left'   => '1',
							'right'  => '1',
							'bottom' => '1',
						),
						'dependency'  => array(
							'wcsp_thumbnail|wcsp_category_thumb_border',
							'==|==',
							'true|true',
						),
					),
					array(
						'id'          => 'wcsp_cat_thumb_border_style',
						'type'        => 'border',
						'title'       => __( 'Border Style', 'woo-category-slider-grid' ),
						'subtitle'    => __( 'Set border for thumbnail.', 'woo-category-slider-grid' ),
						'hover_color' => true,
						'top'         => false,
						'left'        => false,
						'right'       => false,
						'bottom'      => false,
						'default'     => array(
							'style'       => 'solid',
							'color'       => '#e2e2e2',
							'hover_color' => '#e2e2e2',
						),
						'dependency'  => array(
							'wcsp_thumbnail|wcsp_category_thumb_border',
							'==|==',
							'true|true',
						),
					),
					array(
						'id'          => 'wcsp_cat_box_shadow',
						'class'       => 'wcsp_cat_content_position thumbnail_shape sp-no-selected-icon',
						'type'        => 'image_select',
						'title'       => __( 'BoxShadow', 'woo-category-slider-grid' ),
						'subtitle'    => __( 'Set box-shadow for thumbnail.', 'woo-category-slider-grid' ),
						'option_name' => true,
						'options'     => array(
							'inset'  => array(
								'image'       => SP_WCS_URL . 'admin/img/shadow-icons/inset.svg',
								'option_name' => __( 'Inset', 'woo-category-slider-grid' ),
								'pro_only'    => true,
							),
							'outset' => array(
								'image'       => SP_WCS_URL . 'admin/img/shadow-icons/outset.svg',
								'option_name' => __( 'Outset', 'woo-category-slider-grid' ),
								'pro_only'    => true,
							),
							'none'   => array(
								'image'       => SP_WCS_URL . 'admin/img/shadow-icons/none.svg',
								'option_name' => __( 'None', 'woo-category-slider-grid' ),
							),
						),
						'default'     => 'none',
						'dependency'  => array(
							'wcsp_thumbnail',
							'==',
							'true',
						),
					),
					array(
						'id'         => 'wcsp_thumb_margin',
						'type'       => 'spacing',
						'title'      => __( 'Margin', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Set margin for thumbnail.', 'woo-category-slider-grid' ),
						'class'      => 'wcsp-thumb-margin',
						'units'      => array( 'px', '%' ),
						'default'    => array(
							'top'    => '0',
							'right'  => '0',
							'bottom' => '0',
							'left'   => '0',
							'unit'   => 'px',
						),
						'dependency' => array(
							'wcsp_thumbnail',
							'==',
							'true',
						),
					),

					array(
						'id'         => 'wcsp_cat_zoom',
						'type'       => 'select',
						'title'      => __( 'Zoom', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Set a zoom effect for thumbnail.', 'woo-category-slider-grid' ),
						'options'    => array(
							'none'     => __( 'None', 'woo-category-slider-grid' ),
							'zoom-in'  => __( 'Zoom In (Pro)', 'woo-category-slider-grid' ),
							'zoom-out' => __( 'Zoom Out (Pro)', 'woo-category-slider-grid' ),
						),
						'default'    => 'none',
						'dependency' => array(
							'wcsp_thumbnail',
							'==',
							'true',
						),
					),
					array(
						'id'         => 'wcsp_cat_grayscale',
						'type'       => 'button_set',
						'class'      => 'wcsp_cat_grayscale',
						'only_pro'   => true,
						'title'      => __( 'Image Mode', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Set a mode for category thumbnail or image.', 'woo-category-slider-grid' ),
						'options'    => array(
							'normal'       => __( 'Normal', 'woo-category-slider-grid' ),
							'grayscale'    => array(
								'option_name' => __( 'Grayscale', 'woo-category-slider-grid' ),
								'pro_only'    => true,
							),
							'custom_color' => array(
								'option_name' => __( 'Custom Color', 'woo-category-slider-grid' ),
								'pro_only'    => true,
							),
						),
						'default'    => 'normal',
						'dependency' => array(
							'wcsp_thumbnail',
							'==',
							'true',
						),
					),
				), // End of fields array.
			)
		); // Thumbnail settings section end.
	}
}
