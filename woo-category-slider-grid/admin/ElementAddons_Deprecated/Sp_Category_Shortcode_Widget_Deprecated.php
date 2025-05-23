<?php

/**
 * Elementor WooCategory slider free shortcode Widget.
 *
 * @since 1.4.4
 */
class Sp_Category_Shortcode_Widget_Deprecated extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.4.4
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'sp_woo_category_slider_free_shortcode';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.4.4
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WooCategory Deprecated', 'woo-category-slider-grid' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.4.4
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wcsp-icon-wcs-icon deprecated-widget-icon';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 1.4.4
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'deprecated' );
	}

	/**
	 * Get all post list.
	 *
	 * @since 1.4.4
	 * @return array
	 */
	public function sp_wcsp_post_list() {
		$post_list     = array();
		$sp_wcsp_posts = new \WP_Query(
			array(
				'post_type'      => 'sp_wcslider',
				'post_status'    => 'publish',
				'posts_per_page' => 10000,
			)
		);
		$posts         = $sp_wcsp_posts->posts;
		foreach ( $posts as $post ) {
			$post_list[ $post->ID ] = $post->post_title;
		}
		krsort( $post_list );
		return $post_list;
	}

	/**
	 * Controls register.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'woo-category-slider-grid' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'sp_woo_category_slider_free_shortcode',
			array(
				'label'       => __( 'WooCategory Shortcode(s)', 'woo-category-slider-grid' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => '',
				'options'     => $this->sp_wcsp_post_list(),
			)
		);

		$this->add_control(
			'deprecated_notice',
			array(
				'type'            => \Elementor\Controls_Manager::DEPRECATED_NOTICE,
				'widget'          => 'WooCategory Deprecated',
				'since'           => '1.4.6',
				'last'            => '2.2.0',
				'plugin'          => 'WooCategory',
				'replacement'     => 'WooCategory',
				'content_classes' => 'sp-woo-category-slider-deprecated',
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render WooCategory slider free shortcode widget output on the frontend.
	 *
	 * @since 1.4.4
	 * @access protected
	 */
	protected function render() {

		$settings          = $this->get_settings_for_display();
		$sp_wcsp_shortcode = $settings['sp_woo_category_slider_free_shortcode'];

		if ( '' === $sp_wcsp_shortcode ) {
			echo '<div style="text-align: center; margin-top: 0; padding: 10px" class="elementor-add-section-drag-title">Select a shortcode</div>';
			return;
		}

		$generator_id = $sp_wcsp_shortcode;

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$post_id = $generator_id;

			// Preset Layouts.
			$shortcode_meta = get_post_meta( $post_id, 'sp_wcsp_shortcode_options', true );
			$layout_meta    = get_post_meta( $post_id, 'sp_wcsp_layout_options', true );
			$title          = get_the_title( $post_id );
			// Load dynamic style.
			$dynamic_style = Woo_Category_Slider_Public::load_dynamic_style( $post_id, $shortcode_meta );
			echo '<style id="sp_category_dynamic_css' . esc_attr( $post_id ) . '">' . wp_strip_all_tags( $dynamic_style['dynamic_css'] ) . '</style>'; // phpcs:ignore

			Woo_Category_Slider_Shortcode::sp_wcsp_html_show( $post_id, $shortcode_meta, $title, $layout_meta );
			?>
			<script src="<?php echo esc_url( SP_WCS_URL . 'public/js/swiper-config.min.js' ); ?>" ></script>
			<script src="<?php echo esc_url( SP_WCS_URL . 'public/js/preloader.min.js' ); ?>" ></script>
			<?php
		} else {
			echo do_shortcode( '[woocatslider id="' . $generator_id . '"]' );
		}

	}

}
