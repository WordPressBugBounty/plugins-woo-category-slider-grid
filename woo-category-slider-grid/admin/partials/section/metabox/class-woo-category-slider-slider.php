<?php
/**
 * Slider settings tab.
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
 * This class is responsible for Slider settings tab.
 *
 * @since 1.0.0
 */
class SP_WCS_Slider {
	/**
	 * Slider section.
	 *
	 * @param string $prefix slider section prefix.
	 * @return void
	 */
	public static function section( $prefix ) {
		SP_WCS::createSection(
			$prefix,
			array(
				'title'  => __( 'Slider Settings', 'woo-category-slider-grid' ),
				'icon'   => 'fa fa-sliders',
				'fields' => array(
					array(
						'type'  => 'tabbed',
						'class' => 'sp-category-slider-display-settings',
						'tabs'  => array(
							array(
								'title'  => __( 'Slider Controls', 'woo-category-slider-grid' ),
								'icon'   => '<i class="sp--category-icon wcsp-icon-basic-settings"></i>',
								'fields' => array(

									array(
										'id'         => 'wcsp_slider_orientation',
										'type'       => 'image_select',
										'title'      => __( 'Slider Orientation', 'woo-category-slider-grid' ),
										'class'      => 'wcsp_layout_presets carousel-style sp-no-selected-icon',
										'options'    => array(
											'horizontal' => array(
												'image' => SP_WCS_URL . 'admin/img/slider-orientation/horizontal.svg',
												'option_name' => __( 'Horizontal', 'woo-category-slider-grid' ),
											),
											'vertical'   => array(
												'image'    => SP_WCS_URL . 'admin/img/slider-orientation/vertical.svg',
												'option_name' => __( 'Vertical', 'woo-category-slider-grid' ),
												'pro_only' => true,
											),
										),
										'default'    => 'horizontal',
										'dependency' => array(
											'wcsp_layout_presets',
											'==',
											'slider',
											true,
										),
									),

									array(
										'id'         => 'wcsp_auto_play',
										'type'       => 'switcher',
										'title'      => __( 'AutoPlay', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Enable/Disable auto play.', 'woo-category-slider-grid' ),
										'text_on'    => __( 'Enabled', 'woo-category-slider-grid' ),
										'text_off'   => __( 'Disabled', 'woo-category-slider-grid' ),
										'text_width' => 94,
										'default'    => true,
									),
									array(
										'id'         => 'wcsp_auto_play_speed',
										'type'       => 'slider',
										'title'      => __( 'AutoPlay Delay', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Set auto play delay time in millisecond.', 'woo-category-slider-grid' ),
										'title_help' => '<div class="spf-info-label">' . __( 'AutoPlay Delay Time', 'woo-category-slider-grid' ) . '</div><div class="spf-short-content">' . __( 'Set autoplay delay or interval time. The amount of time to delay between automatically cycling a category item. e.g. 1000 milliseconds(ms) = 1 second.', 'woo-category-slider-grid' ) . '</div>',
										'unit'       => __( 'ms', 'woo-category-slider-grid' ),
										'step'       => 100,
										'max'        => 20000,
										'min'        => 100,
										'default'    => 3000,
										'dependency' => array(
											'wcsp_auto_play|wcsp_carousel_style',
											'==|!=',
											'true|ticker',
											true,
										),
									),
									array(
										'id'         => 'wcsp_standard_scroll_speed',
										'type'       => 'slider',
										'title'      => __( 'Scroll Speed', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Set pagination speed in millisecond.', 'woo-category-slider-grid' ),
										'title_help' => '<div class="spf-info-label">' . __( 'Scroll Speed', 'woo-category-slider-grid' ) . '</div><div class="spf-short-content">' . __( 'Set carousel scrolling speed. e.g. 1000 milliseconds(ms) = 1 second.', 'woo-category-slider-grid' ) . '</div>',
										'unit'       => __( 'ms', 'woo-category-slider-grid' ),
										'step'       => 100,
										'max'        => 20000,
										'min'        => 100,
										'default'    => 600,
									),
									array(
										'id'       => 'wcsp_slide_to_scroll',
										'type'     => 'column',
										'title'    => __( 'Slide To Scroll', 'woo-category-slider-grid' ),
										'subtitle' => __( 'Set slide to scroll in different devices.', 'woo-category-slider-grid' ),
										'min'      => '1',
										'default'  => array(
											'large_desktop' => '1',
											'desktop' => '1',
											'laptop'  => '1',
											'tablet'  => '1',
											'mobile'  => '1',
										),
									),
									array(
										'id'         => 'wcsp_pause_on_hover',
										'type'       => 'switcher',
										'title'      => __( 'Pause on Hover', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Enable/Disable slider pause on hover.', 'woo-category-slider-grid' ),
										'text_on'    => __( 'Enabled', 'woo-category-slider-grid' ),
										'text_off'   => __( 'Disabled', 'woo-category-slider-grid' ),
										'text_width' => 94,
										'default'    => true,
										'dependency' => array(
											'wcsp_auto_play',
											'==',
											'true',
											true,
										),
									),
									array(
										'id'         => 'wcsp_infinite_loop',
										'type'       => 'switcher',
										'title'      => __( 'Infinite Loop', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Enable/Disable infinite loop mode.', 'woo-category-slider-grid' ),
										'text_on'    => __( 'Enabled', 'woo-category-slider-grid' ),
										'text_off'   => __( 'Disabled', 'woo-category-slider-grid' ),
										'text_width' => 94,
										'default'    => true,
									),
									array(
										'id'         => 'wcsp_auto_height',
										'type'       => 'switcher',
										'title'      => __( 'Adaptive Height', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Enable/Disable adaptive height.', 'woo-category-slider-grid' ),
										'text_on'    => __( 'Enabled', 'woo-category-slider-grid' ),
										'text_off'   => __( 'Disabled', 'woo-category-slider-grid' ),
										'text_width' => 94,
										'default'    => true,
									),
									array(
										'id'       => 'wcsp_direction',
										'type'     => 'button_set',
										'pro_only' => true,
										'title'    => __( 'Slider Direction', 'woo-category-slider-grid' ),
										'subtitle' => __( 'Set slider direction as you need.', 'woo-category-slider-grid' ),
										'options'  => array(
											'ltr' => array(
												'option_name' => __( 'Right to Left', 'woo-category-slider-grid' ),
												'pro_only' => true,
											),
											'rtl' => array(
												'option_name' => __( 'Left to Right', 'woo-category-slider-grid' ),
												'pro_only' => true,
											),
										),
										'default'  => 'ltr',
									),
								),
							),
							array(
								'title'  => __( 'Navigation', 'woo-category-slider-grid' ),
								'icon'   => '<i class="sp--category-icon wcsp-icon-navigation"></i>',
								'fields' => array(
									// Navigation.
									array(
										'type'    => 'notice',
										'style'   => 'normal',
										'content' => sprintf(
											/* translators: 1: start link and bold tag, 2: close tag. */
											__( 'Want even more fine-tuned control over your %3$sCategory Slider%4$s navigation display? %1$sUpgrade to Pro!%2$s', 'woo-category-slider-grid' ),
											'<a href="https://shapedplugin.com/woocategory/?ref=115#pricing" target="_blank"><b>',
											'</b></a>',
											'<a href="https://demo.shapedplugin.com/woocategory/slider/#Full-width-Category-Slider" target="_blank"><b>',
											'</b></a>'
										),
									),
									array(
										'id'     => 'wcsp_carousel_navigation',
										'class'  => 'wcsp-navigation-and-pagination-style',
										'type'   => 'fieldset',
										'fields' => array(
											array(
												'id'       => 'navigation',
												'type'     => 'switcher',
												'class'    => 'wcsp_navigation',
												'title'    => __( 'Navigation', 'woo-category-slider-grid' ),
												'subtitle' => __( 'Show/Hide carousel navigation.', 'woo-category-slider-grid' ),
												'default'  => true,
												'text_on'  => __( 'Show', 'woo-category-slider-grid' ),
												'text_off' => __( 'Hide', 'woo-category-slider-grid' ),
												'text_width' => 80,
												'dependency' => array( 'wcsp_carousel_style', '!=', 'ticker', true ),
											),
											array(
												'id'      => 'navigation_hide_on_mobile',
												'type'    => 'checkbox',
												'class'   => 'wcsp_hide_on_mobile',
												'title'   => __( 'Hide on Mobile', 'woo-category-slider-grid' ),
												'default' => false,
												'dependency' => array( 'wcsp_carousel_style|navigation', '!=|==', 'ticker|true', true ),
											),
										),
									),
									array(
										'id'         => 'wcsp_nav_position',
										'type'       => 'select',
										'only_pro'   => true,
										'class'      => 'chosen sp_category-carousel-nav-position',
										'title'      => __( 'Navigation Position', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Select navigation position.', 'woo-category-slider-grid' ),
										'preview'    => true,
										'desc'       => sprintf(
											/* translators: %1$s: Help text starting tag, %2$s: starting of anchor tag, %3$s: ending of anchor tag. */
											__( '%1$sThis is a %2$sPro Feature!%3$s', 'woo-category-slider-grid' ),
											'<div class="sp_carousel-navigation-notice">',
											'<a target="_blank" href="https://realtestimonials.io/pricing/?ref=1">',
											'</a></div>'
										),
										'options'    => array(
											'top-right'    => __( 'Top Right', 'woo-category-slider-grid' ),
											'top-center'   => __( 'Top Center', 'woo-category-slider-grid' ),
											'top-left'     => __( 'Top Left', 'woo-category-slider-grid' ),
											'bottom-left'  => __( 'Bottom Left', 'woo-category-slider-grid' ),
											'bottom-center' => __( 'Bottom Center', 'woo-category-slider-grid' ),
											'bottom-right' => __( 'Bottom Right', 'woo-category-slider-grid' ),
											'vertically-center-inner' => __( 'Vertical Inner', 'woo-category-slider-grid' ),
											'vertically-center-outer' => __( 'Vertical Outer', 'woo-category-slider-grid' ),
											'vertically-center' => __( 'Vertical Center', 'woo-category-slider-grid' ),
										),
										'default'    => 'top-right',
										'dependency' => array(
											'navigation|wcsp_carousel_style',
											'==|!=',
											'true|ticker',
											true,
										),
									),
									array(
										'id'         => 'nav_visible_on_hover',
										'type'       => 'checkbox',
										'class'      => 'pro_only_field',
										'title'      => __( 'Show On Hover', 'woo-category-slider-grid' ),
										'title_help' => '<div class="spf-info-label">' . __( 'Card Style', 'woo-category-slider-grid' ) . '</div><div class="spf-short-content">' . __( 'Check to show navigation on hover in the carousel or slider area.', 'woo-category-slider-grid' ) . '</div>',
										'default'    => false,
									),
									array(
										'id'       => 'wcsp_navigation_icon',
										'type'     => 'button_set',
										'pro_only' => true,
										'title'    => __( 'Navigation Arrow Icon', 'woo-category-slider-grid' ),
										'subtitle' => __( 'Choose a slider navigation arrow icon.', 'woo-category-slider-grid' ),
										'class'    => 'wcsp_navigation_icon',
										'options'  => array(
											'right_open'   => array(
												'option_name' => '<i class="wcsp-icon-right-open"></i>',
												// 'pro_only' => true,
											),
											'angle'        => array(
												'option_name' => '<i class="wcsp-icon-angle-right"></i>',
												// 'pro_only' => true,
											),
											'chevron_open_big' => array(
												'option_name' => '<i class="wcsp-icon-right-open-big"></i>',
												// 'pro_only' => true,
											),
											'chevron'      => array(
												'option_name' => '<i class="wcsp-icon-right-open-1"></i>',
												// 'pro_only' => true,
											),
											'right_open_3' => array(
												'option_name' => '<i class="wcsp-icon-right-open-3"></i>',
												// 'pro_only' => true,
											),
											'right_open_outline' => array(
												'option_name' => '<i class="wcsp-icon-right-open-outline"></i>',
												// 'pro_only' => true,
											),
											'arrow'        => array(
												'option_name' => '<i class="wcsp-icon-right"></i>',
												// 'pro_only' => true,
											),
											'triangle'     => array(
												'option_name' => '<i class="wcsp-icon-arrow-triangle-right"></i>',
												'pro_only' => true,
											),
										),
										'default'  => 'right_open',
									),
									array(
										'id'              => 'wcsp_nav_arrow_icon_size',
										'type'            => 'spacing',
										'class'           => 'wcsp_single_input pro_only_field',
										'title'           => __( 'Icon Size', 'woo-category-slider-grid' ),
										'subtitle'        => __( 'Set navigation arrow icon size.', 'woo-category-slider-grid' ),
										'all'             => true,
										'all_text'        => false,
										'all_placeholder' => __( 'size', 'woo-category-slider-grid' ),
										'units'           => array(
											esc_html__( 'px', 'woo-category-slider-grid' ),
										),
										'default'         => array(
											'all' => '22',
										),
									),
									array(
										'id'         => 'wcsp_border_and_background',
										'type'       => 'switcher',
										'class'      => 'pro_only_field',
										'title'      => __( 'Icon Border & Background', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Show/Hide icon border & background.', 'woo-category-slider-grid' ),
										'default'    => true,
										'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
										'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
										'text_width' => 80,
									),
									array(
										'id'          => 'wcsp_nav_border',
										'type'        => 'border',
										'class'       => 'wcsp-nav-border',
										'title'       => __( 'Navigation Border', 'woo-category-slider-grid' ),
										'subtitle'    => __( 'Set border for the slider navigation.', 'woo-category-slider-grid' ),
										'all'         => true,
										'hover_color' => true,
										'default'     => array(
											'all'         => '1',
											'color'       => '#aaaaaa',
											'hover_color' => '#cc2b5e',
										),
										'dependency'  => array(
											'navigation',
											'!=',
											'false',
											true,
										),
									),
									array(
										'id'         => 'wcsp_nav_colors',
										'type'       => 'color_group',
										'title'      => __( 'Navigation Color', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Set color for the slider navigation.', 'woo-category-slider-grid' ),
										'options'    => array(
											'color'       => __( 'Color', 'woo-category-slider-grid' ),
											'hover_color' => __( 'Hover Color', 'woo-category-slider-grid' ),
											'background'  => __( 'Background', 'woo-category-slider-grid' ),
											'hover_background' => __( 'Hover Background', 'woo-category-slider-grid' ),
										),
										'default'    => array(
											'color'       => '#aaaaaa',
											'hover_color' => '#ffffff',
											'background'  => 'transparent',
											'hover_background' => '#cc2b5e',
										),
										'dependency' => array(
											'navigation',
											'!=',
											'false',
											true,
										),
									),
								),
							),
							array(
								'title'  => __( 'Pagination', 'woo-category-slider-grid' ),
								'icon'   => '<i class="sp--category-icon wcsp-icon-pagination"></i>',
								'fields' => array(
									// Pagination.
									array(
										'type'    => 'notice',
										'style'   => 'normal',
										'content' => sprintf(
											/* translators: 1: start link and bold tag, 2: close tag. */
											__( 'Want even more fine-tuned control over your %3$sCategory Slider%4$s pagination display? %1$sUpgrade to Pro!%2$s', 'woo-category-slider-grid' ),
											'<a href="https://shapedplugin.com/woocategory/?ref=115#pricing" target="_blank"><b>',
											'</b></a>',
											'<a href="https://demo.shapedplugin.com/woocategory/slider/#Full-width-Category-Slider" target="_blank"><b>',
											'</b></a>'
										),
									),
									array(
										'id'     => 'wcsp_carousel_pagination',
										'class'  => 'wcsp-navigation-and-pagination-style',
										'type'   => 'fieldset',
										'fields' => array(
											array(
												'id'       => 'pagination',
												'type'     => 'switcher',
												'class'    => 'wcsp_pagination',
												'title'    => __( 'Pagination', 'woo-category-slider-grid' ),
												'subtitle' => __( 'Show/Hide carousel pagination.', 'woo-category-slider-grid' ),
												'default'  => true,
												'text_on'  => __( 'Show', 'woo-category-slider-grid' ),
												'text_off' => __( 'Hide', 'woo-category-slider-grid' ),
												'text_width' => 80,
												'dependency' => array( 'wcsp_carousel_style', '!=', 'ticker', true ),
											),
											array(
												'id'      => 'pagination_hide_on_mobile',
												'type'    => 'checkbox',
												'class'   => 'wcsp_hide_on_mobile',
												'title'   => __( 'Hide on Mobile', 'woo-category-slider-grid' ),
												'default' => false,
												'dependency' => array( 'wcsp_carousel_style|pagination', '!=|==', 'ticker|true', true ),
											),
										),
									),
									array(
										'id'         => 'wcsp_pagination_type',
										'type'       => 'image_select',
										'only_pro'   => true,
										'class'      => 'carousel_pagination_style sp-no-selected-icon',
										'title'      => __( 'Pagination Style', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Select carousel pagination type.', 'woo-category-slider-grid' ),
										'options'    => array(
											'dots'      => array(
												'image' => SP_WCS_URL . 'admin/img/carousel-pagination-type/bullets.svg',
												'option_name' => __( 'Bullets', 'woo-category-slider-grid' ),
											),
											'dynamic'   => array(
												'image'    => SP_WCS_URL . 'admin/img/carousel-pagination-type/dynamic.svg',
												'option_name' => __( 'Dynamic', 'woo-category-slider-grid' ),
												'pro_only' => true,
											),
											'strokes'   => array(
												'image'    => SP_WCS_URL . 'admin/img/carousel-pagination-type/strokes.svg',
												'option_name' => __( 'Strokes', 'woo-category-slider-grid' ),
												'pro_only' => true,
											),
											'scrollbar' => array(
												'image'    => SP_WCS_URL . 'admin/img/carousel-pagination-type/scrollbar.svg',
												'option_name' => __( 'Scrollbar', 'woo-category-slider-grid' ),
												'pro_only' => true,
											),
											'number'    => array(
												'image'    => SP_WCS_URL . 'admin/img/carousel-pagination-type/numbers.svg',
												'option_name' => __( 'Numbers', 'woo-category-slider-grid' ),
												'pro_only' => true,
											),
										),
										'radio'      => true,
										'default'    => 'dots',
										'dependency' => array(
											'pagination|wcsp_carousel_style',
											'==|!=',
											'true|ticker',
											true,
										),
									),

									array(
										'id'         => 'wcsp_pagination_colors',
										'type'       => 'color_group',
										'title'      => __( 'Pagination Color', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Set color for the slider pagination.', 'woo-category-slider-grid' ),
										'options'    => array(
											'color'        => __( 'Color', 'woo-category-slider-grid' ),
											'active_color' => __( 'Active Color', 'woo-category-slider-grid' ),
										),
										'default'    => array(
											'color'        => '#aaaaaa',
											'active_color' => '#cc2b5e',
										),
										'dependency' => array(
											'pagination',
											'!=',
											'false',
											true,
										),
									),
								),
							),
							array(
								'title'  => __( 'Miscellaneous', 'woo-category-slider-grid' ),
								'icon'   => '<i class="sp--category-icon wcsp-icon-miscellaneous"></i>',
								'fields' => array(
									// Misc.
									array(
										'id'         => 'wcsp_touch_swipe',
										'type'       => 'switcher',
										'title'      => __( 'Touch Swipe', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Enable/Disable touch swipe.', 'woo-category-slider-grid' ),
										'text_on'    => __( 'Enabled', 'woo-category-slider-grid' ),
										'text_off'   => __( 'Disabled', 'woo-category-slider-grid' ),
										'text_width' => 94,
										'default'    => true,
									),
									array(
										'id'         => 'wcsp_slider_mouse_wheel',
										'type'       => 'switcher',
										'title'      => __( 'Mousewheel Control', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Enable/Disable mousewheel control.', 'woo-category-slider-grid' ),
										'text_on'    => __( 'Enabled', 'woo-category-slider-grid' ),
										'text_off'   => __( 'Disabled', 'woo-category-slider-grid' ),
										'text_width' => 94,
										'default'    => false,
									),
									array(
										'id'         => 'wcsp_slider_mouse_draggable',
										'type'       => 'switcher',
										'title'      => __( 'Mouse Draggable', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Enable/Disable mouse draggable.', 'woo-category-slider-grid' ),
										'text_on'    => __( 'Enabled', 'woo-category-slider-grid' ),
										'text_off'   => __( 'Disabled', 'woo-category-slider-grid' ),
										'text_width' => 94,
										'default'    => false,
									),
									array(
										'id'         => 'free_mode',
										'type'       => 'switcher',
										'title'      => __( 'Free Mode', 'woo-category-slider-grid' ),
										'subtitle'   => __( 'Enable/Disable free mode.', 'woo-category-slider-grid' ),
										'text_on'    => __( 'Enabled', 'woo-category-slider-grid' ),
										'text_off'   => __( 'Disabled', 'woo-category-slider-grid' ),
										'text_width' => 94,
										'default'    => false,
									),
								),
							),
						),
					),
				),
			)
		); // Slider Controls section end.
	}
}
