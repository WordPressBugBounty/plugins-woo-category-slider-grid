<?php
/**
 * Display settings tab.
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
 * This class is responsible for Display settings tab.
 *
 * @since 1.0.0
 */
class SP_WCS_Display {
	/**
	 * Display section.
	 *
	 * @param string $prefix Section prefix.
	 * @return void
	 */
	public static function section( $prefix ) {

		SP_WCS::createSection(
			$prefix,
			array(
				'title'  => __( 'Display Settings', 'woo-category-slider-grid' ),
				'icon'   => 'fa fa-th-large',
				'fields' => array(
					array(
						'type'  => 'tabbed',
						'class' => 'sp-category-slider-display-settings',
						'tabs'  => array(
							array(
								'title'  => __( 'Basic Styles', 'woo-category-slider-grid' ),
								'icon'   => '<i class="sp--category-icon wcsp-icon-basic-style"></i>',
								'fields' => array(
									array(
										'id'         => 'wcsp_section_title',
										'type'       => 'switcher',
										'title'      => __( 'Section Title', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Show/Hide category showcase section title.', 'woo-category-slider-grid' ),
										'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
										'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
										'text_width' => 80,
										'default'    => true,
									),
									array(
										'id'          => 'wcsp_cat_content_position',
										'class'       => 'wcsp_cat_content_position sp-no-selected-icon',
										'type'        => 'image_select',
										'title'       => __( 'Category Content Position', 'woo-category-slider-grid' ),
										'subtitle'    => __( 'Select a position for the category content.', 'woo-category-slider-grid' ),
										'desc'        => __( 'To access amazing category Content Positions and Settings, ', 'woo-category-slider-grid' ) . '<a href="https://shapedplugin.com/woocategory/?ref=115#pricing" target="_blank"><b>' . __( 'Upgrade to Pro!', 'woo-category-slider-grid' ) . '</b></a>',
										'option_name' => true,
										'options'     => array(
											'thumb-above-cont-below' => array(
												'image' => SP_WCS_URL . 'admin/img/content-positions/below-content.svg',
												'option_name' => __( 'Below Content', 'woo-category-slider-grid' ),
											),
											'cont-above-thumb-below' => array(
												'image'    => SP_WCS_URL . 'admin/img/content-positions/above-content.svg',
												'option_name' => __( 'Above Content', 'woo-category-slider-grid' ),
												'pro_only' => true,
											),
											'left-thumb-right-cont'  => array(
												'image'    => SP_WCS_URL . 'admin/img/content-positions/right-content.svg',
												'option_name' => __( 'Right Content', 'woo-category-slider-grid' ),
												'pro_only' => true,
											),
											'left-cont-right-thumb'  => array(
												'image'    => SP_WCS_URL . 'admin/img/content-positions/left-content.svg',
												'option_name' => __( 'Left Content', 'woo-category-slider-grid' ),
												'pro_only' => true,
											),
											'cont-over-thumb'        => array(
												'image'    => SP_WCS_URL . 'admin/img/content-positions/overlay-content.svg',
												'option_name' => __( 'Overlay', 'woo-category-slider-grid' ),
												'pro_only' => true,
											),
											'top-overlay-cont'        => array(
												'image'    => SP_WCS_URL . 'admin/img/content-positions/overlay-content-top.svg',
												'option_name' => __( 'Overlay Top', 'woo-category-slider-grid' ),
												'pro_only' => true,
											),
											'middle-overlay-cont'        => array(
												'image'    => SP_WCS_URL . 'admin/img/content-positions/overlay-content-middle.svg',
												'option_name' => __( 'Overlay Mid', 'woo-category-slider-grid' ),
												'pro_only' => true,
											),
											'bottom-overlay-cont'        => array(
												'image'    => SP_WCS_URL . 'admin/img/content-positions/overlay-content-bottom.svg',
												'option_name' => __( 'Overlay Bottom', 'woo-category-slider-grid' ),
												'pro_only' => true,
											),
										),
										'default'     => 'thumb-above-cont-below',
									),
									array(
										'id'       => 'wcsp_make_it_card_style',
										'type'     => 'checkbox',
										'title'    => __( 'Make it Card Style', 'woo-category-slider-grid' ),
										'subtitle' => __( 'By checking it, you can bring a material feel into your design through customization.', 'woo-category-slider-grid' ),
										'default'  => false,
									),
									array(
										'id'            => 'wcsp_cat_border',
										'type'          => 'spacing',
										'title'         => __( 'Border Width', 'woo-category-slider-grid' ),
										'subtitle'      => __( 'Set category content border width for the slider item.', 'woo-category-slider-grid' ),
										'hover_color'   => false,
										'color'         => false,
										'border_radius' => true,
										'default'       => array(
											'top'    => '1',
											'left'   => '1',
											'right'  => '1',
											'bottom' => '1',
										),
										'dependency'    => array(
											'wcsp_make_it_card_style',
											'==',
											'true',
											true,
										),
									),
									array(
										'id'          => 'wcsp_cat_border_style',
										'type'        => 'border',
										'title'       => __( 'Border Style', 'woo-category-slider-grid' ),
										'subtitle'    => __( 'Set category content border style for the slider item.', 'woo-category-slider-grid' ),
										'hover_color' => true,
										'top'         => false,
										'left'        => false,
										'right'       => false,
										'bottom'      => false,
										'radius'      => true,
										'default'     => array(
											'style'       => 'solid',
											'color'       => '#e2e2e2',
											'hover_color' => '#e2e2e2',
										),
										'dependency'  => array(
											'wcsp_make_it_card_style',
											'==',
											'true',
											true,
										),
									),
									array(
										'id'         => 'wcsp_cat_background',
										'type'       => 'color_group',
										'title'      => __( 'Background', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Set color for the category content background.', 'woo-category-slider-grid' ),
										'options'    => array(
											'background' => __( 'Background', 'woo-category-slider-grid' ),
											'hover_background' => __( 'Hover Background', 'woo-category-slider-grid' ),
										),
										'default'    => array(
											'background' => '#f8f8f8',
											'hover_background' => '#f8f8f8',
										),
										'dependency' => array(
											'wcsp_make_it_card_style',
											'==',
											'true',
											true,
										),
									),
									array(
										'id'          => 'wcsp_cat_padding',
										'type'        => 'spacing',
										'class'       => 'wcsp-cat-padding',
										'title'       => __( 'Inner Padding', 'woo-category-slider-grid' ),
										'subtitle'    => __( 'Set category content inner padding.', 'woo-category-slider-grid' ),
										'output_mode' => 'padding',
										'unit'        => true,
										'units'       => array( 'px' ),
										'default'     => array(
											'top'    => '16',
											'right'  => '16',
											'bottom' => '16',
											'left'   => '16',
											'unit'   => 'px',
										),
									),
									array(
										'id'         => 'same_height_items',
										'type'       => 'switcher',
										'class'      => 'pro_only_field',
										'only_pro'   => true,
										'attributes' => array( 'disabled' => 'disabled' ),
										'title'      => __( 'Enable Equal Height', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Enable to adjust the height of all the category items to match the tallest one.', 'woo-category-slider-grid' ),
										'text_on'    => __( 'Enabled', 'woo-category-slider-grid' ),
										'text_off'   => __( 'Disabled', 'woo-category-slider-grid' ),
										'text_width' => 95,
										'default'    => false,
									),
								),
							),
							array(
								'title'  => __( 'Category Content', 'woo-category-slider-grid' ),
								'icon'   => '<i class="sp--category-icon wcsp-icon-cat-content"></i>',
								'fields' => array(
									array(
										'type'    => 'notice',
										'style'   => 'normal',
										'content' => sprintf(
											/* translators: 1: start link and bold tag, 2: close tag. */
											__( 'To access the Category Icon library and Custom Text for your product category, %1$sUpgrade to Pro!%2$s', 'woo-category-slider-grid' ),
											'<a href="https://shapedplugin.com/woocategory/?ref=115#pricing" target="_blank"><b>',
											'</b></a>'
										),
									),
									array(
										'id'         => 'wcsp_cat_icon',
										'class'      => 'pro_only_field',
										'type'       => 'switcher',
										'only_pro'   => true,
										'title'      => __( 'Category Icon', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Show/Hide category icon.', 'woo-category-slider-grid' ),
										'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
										'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
										'text_width' => 80,
										'default'    => false,
									),
									array(
										'id'         => 'wcsp_cat_name',
										'type'       => 'switcher',
										'title'      => __( 'Category Name', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Show/Hide category name.', 'woo-category-slider-grid' ),
										'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
										'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
										'text_width' => 80,
										'default'    => true,
									),
									array(
										'id'          => 'wcsp_cat_name_margin',
										'type'        => 'spacing',
										'class'       => 'wcsp-cat-name-margin',
										'title'       => __( 'Category Name Margin', 'woo-category-slider-grid' ),
										'subtitle'    => __( 'Set category name margin.', 'woo-category-slider-grid' ),
										'output_mode' => 'margin',
										'units'       => array(
											esc_html__( 'px', 'woo-category-slider-grid' ),
											esc_html__( 'em', 'woo-category-slider-grid' ),
										),
										'default'     => array(
											'top'    => '0',
											'right'  => '0',
											'bottom' => '6',
											'left'   => '0',
											'unit'   => 'px',
										),
										'dependency'  => array(
											'wcsp_cat_name',
											'==',
											'true',
											true,
										),
									),
									array(
										'id'         => 'wcsp_cat_product_count',
										'type'       => 'switcher',
										'title'      => __( 'Product Count', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Show/Hide product count.', 'woo-category-slider-grid' ),
										'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
										'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
										'text_width' => 80,
										'default'    => true,
										'dependency' => array( 'wcsp_cat_name', '==', 'true', true ),
									),
									array(
										'id'         => 'wcsp_cat_product_count_position',
										'type'       => 'radio',
										'title'      => __( 'Product Count Position', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Set product count position.', 'woo-category-slider-grid' ),
										'options'    => array(
											'beside_cat' => __( 'Beside category name', 'woo-category-slider-grid' ),
											'under_cat'  => __( 'Under category name', 'woo-category-slider-grid' ),
										),
										'default'    => 'beside_cat',
										'dependency' => array( 'wcsp_cat_name|wcsp_cat_product_count', '==|==', 'true|true', true ),
									),
									array(
										'id'         => 'wcsp_cat_product_count_before',
										'type'       => 'text',
										'title'      => __( 'Product Count Before', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Set product count before text.', 'woo-category-slider-grid' ),
										'default'    => ' (',
										'dependency' => array( 'wcsp_cat_name|wcsp_cat_product_count', '==|==', 'true|true', true ),
									),
									array(
										'id'         => 'wcsp_cat_product_count_after',
										'type'       => 'text',
										'title'      => __( 'Product Count After', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Set product count after text.', 'woo-category-slider-grid' ),
										'default'    => ')',
										'dependency' => array( 'wcsp_cat_name|wcsp_cat_product_count', '==|==', 'true|true', true ),
									),
									array(
										'id'          => 'wcsp_product_count_margin',
										'type'        => 'spacing',
										'title'       => __( 'Product Count Margin', 'woo-category-slider-grid' ),
										'subtitle'    => __( 'Set product count margin.', 'woo-category-slider-grid' ),
										'output_mode' => 'margin',
										'units'       => array(
											esc_html__( 'px', 'woo-category-slider-grid' ),
											esc_html__( 'em', 'woo-category-slider-grid' ),
										),
										'default'     => array(
											'top'    => '0',
											'right'  => '0',
											'bottom' => '6',
											'left'   => '0',
											'unit'   => 'px',
										),
										'dependency'  => array(
											'wcsp_cat_product_count_position|wcsp_cat_product_count|wcsp_cat_name',
											'==|==|==',
											'under_cat|true|true',
											true,
										),
									),
									array(
										'id'         => 'wcsp_cat_custom_text',
										'class'      => 'pro_only_field',
										'attributes' => array( 'disabled' => 'disabled' ),
										'type'       => 'switcher',
										'only_pro'   => true,
										'title'      => __( 'Custom Text', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Show/Hide custom text.', 'woo-category-slider-grid' ),
										'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
										'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
										'text_width' => 80,
										'default'    => false,

									),
									array(
										'id'         => 'wcsp_cat_description',
										'type'       => 'switcher',
										'title'      => __( 'Description', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Show/Hide description.', 'woo-category-slider-grid' ),
										'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
										'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
										'text_width' => 80,
										'default'    => true,
									),
									array(
										'id'          => 'wcsp_description_margin',
										'type'        => 'spacing',
										'class'       => 'wcsp-description-margin',
										'title'       => __( 'Description Margin', 'woo-category-slider-grid' ),
										'subtitle'    => __( 'Set description margin.', 'woo-category-slider-grid' ),
										'output_mode' => 'margin',
										'units'       => array(
											esc_html__( 'px', 'woo-category-slider-grid' ),
											esc_html__( 'em', 'woo-category-slider-grid' ),
										),
										'default'     => array(
											'top'    => '0',
											'right'  => '0',
											'bottom' => '14',
											'left'   => '0',
											'unit'   => 'px',
										),
										'dependency'  => array(
											'wcsp_cat_description',
											'==',
											'true',
											true,
										),
									),
								),
							),
							array(
								'title'  => __( 'Shop Now Button', 'woo-category-slider-grid' ),
								'icon'   => '<i class="sp--category-icon wcsp-icon-shop-now-button"></i>',
								'fields' => array(
									array(
										'type'    => 'notice',
										'style'   => 'normal',
										'content' => sprintf(
											/* translators: 1: start link and bold tag, 2: close tag. */
											__( 'To attract more potential customers with a customized "Shop Now" button, %1$sUpgrade to Pro!%2$s', 'woo-category-slider-grid' ),
											'<a href="https://shapedplugin.com/woocategory/?ref=115#pricing" target="_blank"><b>',
											'</b></a>'
										),
									),
									array(
										'id'         => 'wcsp_cat_shop_now_button',
										'type'       => 'switcher',
										'title'      => __( 'Shop Now Button', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Show/Hide shop now button.', 'woo-category-slider-grid' ),
										'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
										'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
										'text_width' => 80,
										'default'    => true,
									),
									array(
										'id'         => 'wcsp_cat_shop_now_button_text',
										'type'       => 'text',
										'title'      => __( 'Shop Now Button Label', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Type shop now button label.', 'woo-category-slider-grid' ),
										'default'    => 'Shop Now',
										'dependency' => array( 'wcsp_cat_shop_now_button', '==', 'true', true ),
									),
									array(
										'id'         => 'wcsp_cat_shop_button_color',
										'type'       => 'color_group',
										'title'      => __( 'Shop Now Button Color', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Set shop now button color.', 'woo-category-slider-grid' ),
										'options'    => array(
											'background' => __( 'Background', 'woo-category-slider-grid' ),
											'hover_background' => __( 'Hover Background', 'woo-category-slider-grid' ),
										),
										'default'    => array(
											'background' => '#cc2b5e',
											'hover_background' => '#af2435',
										),
										'dependency' => array( 'wcsp_cat_shop_now_button', '==', 'true', true ),
									),
									array(
										'id'          => 'wcsp_cat_shop_button_border',
										'type'        => 'border',
										'title'       => __( 'Shop Now Button Border', 'woo-category-slider-grid' ),
										'subtitle'    => __( 'Set border for the shop now button.', 'woo-category-slider-grid' ),
										'all'         => true,
										'hover_color' => true,
										'default'     => array(
											'all'         => '0',
											'color'       => '#cc2b5e',
											'hover_color' => '#af2435',
										),
										'dependency'  => array( 'wcsp_cat_shop_now_button', '==', 'true', true ),
									),
									array(
										'id'          => 'wcsp_cat_button_margin',
										'type'        => 'spacing',
										'class'       => 'wcsp-cat-button-margin',
										'title'       => __( 'Button Margin', 'woo-category-slider-grid' ),
										'subtitle'    => __( 'Set shop now button margin.', 'woo-category-slider-grid' ),
										'output_mode' => 'margin',
										'units'       => array(
											esc_html__( 'px', 'woo-category-slider-grid' ),
											esc_html__( 'em', 'woo-category-slider-grid' ),
										),
										'default'     => array(
											'top'    => '0',
											'right'  => '0',
											'bottom' => '5',
											'left'   => '0',
											'unit'   => 'px',
										),
										'dependency'  => array( 'wcsp_cat_shop_now_button', '==', 'true', true ),
									),
									array(
										'id'         => 'wcsp_cat_link_target',
										'type'       => 'select',
										'title'      => __( 'Link Target', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Set link target.', 'woo-category-slider-grid' ),
										'options'    => array(
											'_self'     => __( '_self', 'woo-category-slider-grid' ),
											'_blank'    => __( '_blank', 'woo-category-slider-grid' ),
											'_parent'   => __( '_parent', 'woo-category-slider-grid' ),
											'_top'      => __( '_top', 'woo-category-slider-grid' ),
											'framename' => __( 'framename', 'woo-category-slider-grid' ),
										),
										'default'    => '_self',
										'dependency' => array( 'wcsp_cat_shop_now_button', '==', 'true', true ),
									),
								),
							),
							array(
								'title'           => __( 'Typography', 'woo-category-slider-grid' ),
								'icon'            => '<i class="sp--category-icon wcsp-icon-typhograpy"></i>',
								'enqueue_webfont' => true,
								'fields'          => array(
									array(
										'type'    => 'notice',
										'style'   => 'normal',
										'content' => sprintf(
											/* translators: 1: start link and bold tag, 2: close tag. */
											__( 'Want to customize everything (typography, color, and margin) easily? %1$sUpgrade to Pro!%2$s', 'woo-category-slider-grid' ),
											'<a href="https://shapedplugin.com/woocategory/?ref=115#pricing" target="_blank"><b>',
											'</b></a>'
										) . __( ' P.S. Note: The color and margin options work in the lite version.', 'woo-category-slider-grid' ),
									),
									array(
										'id'         => 'wpsp_section_title_font_load',
										'type'       => 'switcherf',
										'title'      => __( 'Load Slider Section Title Font', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'On/Off google font for the slider section title.', 'woo-category-slider-grid' ),
										'default'    => false,
										'dependency' => array( 'wcsp_section_title', '==', 'true', true ),
									),
									array(
										'id'           => 'wpsp_section_title_typography',
										'type'         => 'typography',
										'title'        => __( 'Slider Section Title Font', 'woo-category-slider-grid' ),
										'subtitle'     => __( 'Set slider section title font properties.', 'woo-category-slider-grid' ),
										'default'      => array(
											'color'       => '#444444',
											'font-family' => 'Open Sans',
											'font-weight' => '600',
											'font-size'   => '20',
											'line-height' => '20',
											'letter-spacing' => '0',
											'text-align'  => 'left',
											'text-transform' => 'none',
											'type'        => 'google',
											'unit'        => 'px',
										),
										'preview'      => 'always',
										'preview_text' => 'Slider Section Title',
										'dependency'   => array( 'wcsp_section_title', '==', 'true', true ),
									),
									array(
										'id'          => 'wcsp_section_title_margin',
										'type'        => 'spacing',
										'class'       => 'wcsp-section-title-margin',
										'title'       => __( 'Margin from Section Title', 'woo-category-slider-grid' ),
										'subtitle'    => __( 'Set margin for category showcase section title.', 'woo-category-slider-grid' ),
										'output_mode' => 'margin',
										'units'       => array(
											esc_html__( 'px', 'woo-category-slider-grid' ),
											esc_html__( 'em', 'woo-category-slider-grid' ),
										),
										'default'     => array(
											'top'    => '0',
											'right'  => '0',
											'bottom' => '30',
											'left'   => '0',
											'unit'   => 'px',
										),
										'dependency'  => array(
											'wcsp_section_title',
											'==',
											'true',
											true,
										),
									),
									array(
										'id'         => 'wcsp_cat_name_font_load',
										'type'       => 'switcherf',
										'title'      => __( 'Load Category Name Font', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'On/Off google font for the category name.', 'woo-category-slider-grid' ),
										'default'    => false,
										'dependency' => array( 'wcsp_cat_name', '==', 'true', true ),
									),
									array(
										'id'           => 'wcsp_cat_name_typography',
										'type'         => 'typography',
										'title'        => __( 'Category Name Font', 'woo-category-slider-grid' ),
										'subtitle'     => __( 'Set category name font properties.', 'woo-category-slider-grid' ),
										'hover-color'  => true,
										'default'      => array(
											'color'       => '#444444',
											'hover-color' => '#444444',
											'font-family' => 'Lato',
											'font-style'  => '700',
											'font-size'   => '16',
											'line-height' => '22',
											'letter-spacing' => '0',
											'text-align'  => 'center',
											'text-transform' => 'none',
											'type'        => 'google',
										),
										'preview'      => 'always',
										'preview_text' => 'Kids Fashion',
										'dependency'   => array( 'wcsp_cat_name', '==', 'true', true ),
									),
									array(
										'id'         => 'wcsp_product_count_font_load',
										'type'       => 'switcherf',
										'title'      => __( 'Load Product Count Font', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'On/Off google font for the product count.', 'woo-category-slider-grid' ),
										'default'    => false,
										'dependency' => array( 'wcsp_cat_product_count', '==', 'true', true ),
									),
									array(
										'id'           => 'wcsp_product_count_typography',
										'type'         => 'typography',
										'title'        => __( 'Product Count Font', 'woo-category-slider-grid' ),
										'subtitle'     => __( 'Set product count font properties.', 'woo-category-slider-grid' ),
										'default'      => array(
											'color'       => '#777777',
											'font-family' => 'Open Sans',
											'font-style'  => '400',
											'font-size'   => '14',
											'line-height' => '20',
											'letter-spacing' => '0',
											'text-align'  => 'center',
											'text-transform' => 'none',
											'type'        => 'google',
										),
										'preview'      => 'always',
										'preview_text' => '23 Products',
										'dependency'   => array( 'wcsp_cat_product_count', '==', 'true', true ),
									),
									array(
										'id'       => 'wcsp_child_cat_font_load',
										'type'     => 'switcherf',
										'title'    => __( 'Load Child Category Font', 'woo-category-slider-grid' ),
										'subtitle' => __( 'On/Off google font for the child category.', 'woo-category-slider-grid' ),
										'default'  => false,
									),
									array(
										'id'           => 'wcsp_child_cat_typography',
										'type'         => 'typography',
										'class'        => 'wcsp_child_cat_typography',
										'title'        => __( 'Child Category Font', 'woo-category-slider-grid' ),
										'subtitle'     => __( 'Set child category font properties.', 'woo-category-slider-grid' ),
										'hover-color'  => true,
										'default'      => array(
											'color'       => '#636363',
											'hover-color' => '#cc2b5e',
											'font-family' => 'Open Sans',
											'font-style'  => '400',
											'font-size'   => '14',
											'line-height' => '18',
											'letter-spacing' => '0',
											'text-align'  => 'center',
											'text-transform' => 'none',
											'type'        => 'google',
										),
										'preview'      => 'always',
										'preview_text' => 'Child Category',
									),
									array(
										'id'       => 'wcsp_custom_text_font_load',
										'type'     => 'switcherf',
										'title'    => __( 'Load Custom Text Font', 'woo-category-slider-grid' ),
										'subtitle' => __( 'On/Off google font for the custom text.', 'woo-category-slider-grid' ),
										'default'  => false,
									),
									array(
										'id'           => 'wcsp_custom_text_typography',
										'type'         => 'typography',
										'class'        => 'wcsp_custom_text_typography',
										'title'        => __( 'Custom Text Font', 'woo-category-slider-grid' ),
										'subtitle'     => __( 'Set custom text font properties.', 'woo-category-slider-grid' ),
										'default'      => array(
											'color'       => '#535353',
											'font-family' => 'Lato',
											'font-style'  => '400',
											'font-size'   => '14',
											'line-height' => '18',
											'letter-spacing' => '0',
											'text-align'  => 'center',
											'text-transform' => 'uppercase',
											'type'        => 'google',
										),
										'preview'      => 'always',
										'preview_text' => 'Black Friday Offer 50% Off',
									),
									array(
										'id'         => 'wcsp_description_font_load',
										'type'       => 'switcherf',
										'title'      => __( 'Load Description Font', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'On/Off google font for the description.', 'woo-category-slider-grid' ),
										'default'    => false,
										'dependency' => array( 'wcsp_cat_description', '==', 'true', true ),

									),
									array(
										'id'           => 'wcsp_description_typography',
										'type'         => 'typography',
										'title'        => __( 'Description Font', 'woo-category-slider-grid' ),
										'subtitle'     => __( 'Set description font properties.', 'woo-category-slider-grid' ),
										'default'      => array(
											'color'       => '#444444',
											'font-family' => 'Open Sans',
											'font-style'  => '300',
											'font-size'   => '14',
											'line-height' => '18',
											'letter-spacing' => '0',
											'text-align'  => 'center',
											'text-transform' => 'none',
											'type'        => 'google',
										),
										'preview'      => 'always',
										'preview_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer semper congue ultricies. Suspendisse a congue magna. Fusce at lacinia risus.',
										'dependency'   => array( 'wcsp_cat_description', '==', 'true', true ),
									),
									array(
										'id'         => 'wcsp_shop_now_font_load',
										'type'       => 'switcherf',
										'title'      => __( 'Load Shop Now Button Font', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'On/Off google font for the shop now button.', 'woo-category-slider-grid' ),
										'default'    => false,
										'dependency' => array( 'wcsp_cat_shop_now_button', '==', 'true', true ),

									),
									array(
										'id'           => 'wcsp_shop_now_typography',
										'type'         => 'typography',
										'title'        => __( 'Shop Now Button Font', 'woo-category-slider-grid' ),
										'subtitle'     => __( 'Set shop now button font properties.', 'woo-category-slider-grid' ),
										'hover-color'  => true,
										'default'      => array(
											'color'       => '#ffffff',
											'hover-color' => '#ffffff',
											'font-family' => 'Lato',
											'font-style'  => '700',
											'font-size'   => '15',
											'line-height' => '20',
											'letter-spacing' => '0',
											'text-align'  => 'center',
											'text-transform' => 'none',
											'type'        => 'google',
										),
										'preview'      => 'always',
										'preview_text' => 'Shop Now',
										'dependency'   => array( 'wcsp_cat_shop_now_button', '==', 'true', true ),
									),
								), // End of fields array.
							),
						),
					),

				), // End of fields array.
			)
		); // Display Settings section end.
	}
}
