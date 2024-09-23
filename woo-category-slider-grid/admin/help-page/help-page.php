<?php
/**
 * The help page for the Woo Category Slider
 *
 * @package Woo Category Slider
 * @subpackage woo-category-slider-grid/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access.

/**
 * The help class for the Woo Category Slider
 */
class Woo_Category_Slider_Help {

	/**
	 * Single instance of the class
	 *
	 * @var null
	 */
	protected static $_instance = null;

	/**
	 * Plugins Path variable.
	 *
	 * @var array
	 */
	protected static $plugins = array(
		'woo-product-slider'             => 'main.php',
		'gallery-slider-for-woocommerce' => 'woo-gallery-slider.php',
		'post-carousel'                  => 'main.php',
		'easy-accordion-free'            => 'plugin-main.php',
		'logo-carousel-free'             => 'main.php',
		'location-weather'               => 'main.php',
		'woo-quickview'                  => 'woo-quick-view.php',
		'wp-expand-tabs-free'            => 'plugin-main.php',

	);

	/**
	 * Welcome pages
	 *
	 * @var array
	 */
	public $pages = array(
		'wcsp_help',
	);


	/**
	 * Not show this plugin list.
	 *
	 * @var array
	 */
	protected static $not_show_plugin_list = array( 'aitasi-coming-soon', 'latest-posts', 'widget-post-slider', 'easy-lightbox-wp' );

	/**
	 * Help page construct function.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'help_admin_menu' ), 80 );

        $page   = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';// @codingStandardsIgnoreLine
		if ( 'wcsp_help' !== $page ) {
			return;
		}
		add_action( 'admin_print_scripts', array( $this, 'disable_admin_notices' ) );
		add_action( 'spf_enqueue', array( $this, 'help_page_enqueue_scripts' ) );
	}

	/**
	 * Main Help page Instance
	 *
	 * @static
	 * @return self Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Help_page_enqueue_scripts function.
	 *
	 * @return void
	 */
	public function help_page_enqueue_scripts() {
		wp_enqueue_style( 'sp-woo-cat-slider-help', SP_WCS_URL . 'admin/help-page/css/help-page.min.css', array(), SP_WCS_VERSION );
		wp_enqueue_style( 'sp-woo-cat-slider-fontello', SP_WCS_URL . 'admin/help-page/css/fontello.min.css', array(), SP_WCS_VERSION );

		wp_enqueue_script( 'sp-woo-cat-slider-help', SP_WCS_URL . 'admin/help-page/js/help-page.min.js', array(), SP_WCS_VERSION, true );
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function help_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=sp_wcslider',
			__( 'Category Slider', 'woo-category-slider-grid' ),
			__( 'Recommended', 'woo-category-slider-grid' ),
			'manage_options',
			'edit.php?post_type=sp_wcslider&page=wcsp_help#recommended'
		);
		add_submenu_page(
			'edit.php?post_type=sp_wcslider',
			__( 'Category Slider', 'woo-category-slider-grid' ),
			__( 'Lite vs Pro', 'woo-category-slider-grid' ),
			'manage_options',
			'edit.php?post_type=sp_wcslider&page=wcsp_help#lite-to-pro'
		);
		add_submenu_page(
			'edit.php?post_type=sp_wcslider',
			__( 'Woo Category Slider Help', 'woo-category-slider-grid' ),
			__( 'Get Help', 'woo-category-slider-grid' ),
			'manage_options',
			'wcsp_help',
			array(
				$this,
				'help_page_callback',
			)
		);
	}

	/**
	 * Spwoocs_ajax_help_page function.
	 *
	 * @return void
	 */
	public function spwoocs_plugins_info_api_help_page() {
		$plugins_arr = get_transient( 'spwoocs_plugins' );
		if ( false === $plugins_arr ) {
			$args    = (object) array(
				'author'   => 'shapedplugin',
				'per_page' => '120',
				'page'     => '1',
				'fields'   => array(
					'slug',
					'name',
					'version',
					'downloaded',
					'active_installs',
					'last_updated',
					'rating',
					'num_ratings',
					'short_description',
					'author',
				),
			);
			$request = array(
				'action'  => 'query_plugins',
				'timeout' => 30,
				'request' => serialize( $args ),
			);
			// https://codex.wordpress.org/WordPress.org_API.
			$url      = 'http://api.wordpress.org/plugins/info/1.0/';
			$response = wp_remote_post( $url, array( 'body' => $request ) );

			if ( ! is_wp_error( $response ) ) {

				$plugins_arr = array();
				$plugins     = unserialize( $response['body'] );

				if ( isset( $plugins->plugins ) && ( count( $plugins->plugins ) > 0 ) ) {
					foreach ( $plugins->plugins as $pl ) {
						if ( ! in_array( $pl->slug, self::$not_show_plugin_list, true ) ) {
							$plugins_arr[] = array(
								'slug'              => $pl->slug,
								'name'              => $pl->name,
								'version'           => $pl->version,
								'downloaded'        => $pl->downloaded,
								'active_installs'   => $pl->active_installs,
								'last_updated'      => strtotime( $pl->last_updated ),
								'rating'            => $pl->rating,
								'num_ratings'       => $pl->num_ratings,
								'short_description' => $pl->short_description,
							);
						}
					}
				}

				set_transient( 'spwoocs_plugins', $plugins_arr, 24 * HOUR_IN_SECONDS );
			}
		}

		if ( is_array( $plugins_arr ) && ( count( $plugins_arr ) > 0 ) ) {
			array_multisort( array_column( $plugins_arr, 'active_installs' ), SORT_DESC, $plugins_arr );

			foreach ( $plugins_arr as $plugin ) {
				$plugin_slug = $plugin['slug'];
				$image_type  = 'png';
				if ( isset( self::$plugins[ $plugin_slug ] ) ) {
					$plugin_file = self::$plugins[ $plugin_slug ];
				} else {
					$plugin_file = $plugin_slug . '.php';
				}

				switch ( $plugin_slug ) {
					case 'styble':
						$image_type = 'jpg';
						break;
					case 'location-weather':
					case 'gallery-slider-for-woocommerce':
						$image_type = 'gif';
						break;
				}

				$details_link = network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] . '&amp;TB_iframe=true&amp;width=745&amp;height=550' );
				?>
				<div class="plugin-card <?php echo esc_attr( $plugin_slug ); ?>" id="<?php echo esc_attr( $plugin_slug ); ?>">
					<div class="plugin-card-top">
						<div class="name column-name">
							<h3>
								<a class="thickbox" title="<?php echo esc_attr( $plugin['name'] ); ?>" href="<?php echo esc_url( $details_link ); ?>">
						<?php echo esc_html( $plugin['name'] ); ?>
									<img src="<?php echo esc_url( 'https://ps.w.org/' . $plugin_slug . '/assets/icon-256x256.' . $image_type ); ?>" class="plugin-icon"/>
								</a>
							</h3>
						</div>
						<div class="action-links">
							<ul class="plugin-action-buttons">
								<li>
						<?php
						if ( $this->is_plugin_installed( $plugin_slug, $plugin_file ) ) {
							if ( $this->is_plugin_active( $plugin_slug, $plugin_file ) ) {
								?>
										<button type="button" class="button button-disabled" disabled="disabled">Active</button>
									<?php
							} else {
								?>
											<a href="<?php echo esc_url( $this->activate_plugin_link( $plugin_slug, $plugin_file ) ); ?>" class="button button-primary activate-now">
									<?php esc_html_e( 'Activate', 'woo-category-slider-grid' ); ?>
											</a>
									<?php
							}
						} else {
							?>
										<a href="<?php echo esc_url( $this->install_plugin_link( $plugin_slug ) ); ?>" class="button install-now">
								<?php esc_html_e( 'Install Now', 'woo-category-slider-grid' ); ?>
										</a>
								<?php } ?>
								</li>
								<li>
									<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_attr( 'More information about ' . $plugin['name'] ); ?>" title="<?php echo esc_attr( $plugin['name'] ); ?>">
								<?php esc_html_e( 'More Details', 'woo-category-slider-grid' ); ?>
									</a>
								</li>
							</ul>
						</div>
						<div class="desc column-description">
							<p><?php echo esc_html( isset( $plugin['short_description'] ) ? $plugin['short_description'] : '' ); ?></p>
							<p class="authors"> <cite>By <a href="https://shapedplugin.com/">ShapedPlugin LLC</a></cite></p>
						</div>
					</div>
					<?php
					echo '<div class="plugin-card-bottom">';

					if ( isset( $plugin['rating'], $plugin['num_ratings'] ) ) {
						?>
						<div class="vers column-rating">
							<?php
							wp_star_rating(
								array(
									'rating' => $plugin['rating'],
									'type'   => 'percent',
									'number' => $plugin['num_ratings'],
								)
							);
							?>
							<span class="num-ratings">(<?php echo esc_html( number_format_i18n( $plugin['num_ratings'] ) ); ?>)</span>
						</div>
						<?php
					}
					if ( isset( $plugin['version'] ) ) {
						?>
						<div class="column-updated">
							<strong><?php esc_html_e( 'Version:', 'woo-category-slider-grid' ); ?></strong>
							<span><?php echo esc_html( $plugin['version'] ); ?></span>
						</div>
							<?php
					}

					if ( isset( $plugin['active_installs'] ) ) {
						?>
						<div class="column-downloaded">
						<?php echo esc_html( number_format_i18n( $plugin['active_installs'] ) ) . esc_html__( '+ Active Installations', 'woo-category-slider-grid' ); ?>
						</div>
									<?php
					}

					if ( isset( $plugin['last_updated'] ) ) {
						?>
						<div class="column-compatibility">
							<strong><?php esc_html_e( 'Last Updated:', 'woo-category-slider-grid' ); ?></strong>
							<span><?php echo esc_html( human_time_diff( $plugin['last_updated'] ) ) . ' ' . esc_html__( 'ago', 'woo-category-slider-grid' ); ?></span>
						</div>
									<?php
					}

					echo '</div>';
					?>
				</div>
				<?php
			}
		}
	}

	/**
	 * Check plugins installed function.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_installed( $plugin_slug, $plugin_file ) {
		return file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Check active plugin function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_active( $plugin_slug, $plugin_file ) {
		return is_plugin_active( $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Install plugin link.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @return string
	 */
	public function install_plugin_link( $plugin_slug ) {
		return wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug ), 'install-plugin_' . $plugin_slug );
	}

	/**
	 * Active Plugin Link function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return string
	 */
	public function activate_plugin_link( $plugin_slug, $plugin_file ) {
		return wp_nonce_url( admin_url( 'edit.php?post_type=sp_wcslider&page=wcsp_help&action=activate&plugin=' . $plugin_slug . '/' . $plugin_file . '#recommended' ), 'activate-plugin_' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Making page as clean as possible
	 */
	public function disable_admin_notices() {

		global $wp_filter;

		if ( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && 'sp_wcslider' === wp_unslash( $_GET['post_type'] ) && in_array( wp_unslash( $_GET['page'] ), $this->pages ) ) { // @codingStandardsIgnoreLine

			if ( isset( $wp_filter['user_admin_notices'] ) ) {
				unset( $wp_filter['user_admin_notices'] );
			}
			if ( isset( $wp_filter['admin_notices'] ) ) {
				unset( $wp_filter['admin_notices'] );
			}
			if ( isset( $wp_filter['all_admin_notices'] ) ) {
				unset( $wp_filter['all_admin_notices'] );
			}
		}
	}

	/**
	 * The Woo Category Slider Help Callback.
	 *
	 * @return void
	 */
	public function help_page_callback() {
		add_thickbox();

		$action   = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
		$plugin   = isset( $_GET['plugin'] ) ? sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) : '';
		$_wpnonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

		if ( isset( $action, $plugin ) && ( 'activate' === $action ) && wp_verify_nonce( $_wpnonce, 'activate-plugin_' . $plugin ) ) {
			activate_plugin( $plugin, '', false, true );
		}

		if ( isset( $action, $plugin ) && ( 'deactivate' === $action ) && wp_verify_nonce( $_wpnonce, 'deactivate-plugin_' . $plugin ) ) {
			deactivate_plugins( $plugin, '', false, true );
		}

		?>
		<div class="sp-woo-cat-slider-help">
			<!-- Header section start -->
			<section class="spwoocs__help header">
				<div class="spwoocs-header-area-top">
					<p>You’re currently using <b>Woo Category Slider Lite</b>. To access additional features, consider <a target="_blank" href="https://shapedplugin.com/plugin/woocommerce-category-slider-pro/?ref=1" ><b>upgrading to Pro!</b></a> 🚀</p>
				</div>
				<div class="spwoocs-header-area">
					<div class="spwoocs-container">
						<div class="spwoocs-header-logo">
							<img src="<?php echo esc_url( SP_WCS_URL . 'admin/help-page/img/logo.svg' ); ?>" alt="">
							<span><?php echo esc_html( SP_WCS_VERSION ); ?></span>
						</div>
					</div>
					<div class="spwoocs-header-logo-shape">
						<img src="<?php echo esc_url( SP_WCS_URL . 'admin/help-page/img/logo-shape.svg' ); ?>" alt="">
					</div>
				</div>
				<div class="spwoocs-header-nav">
					<div class="spwoocs-container">
						<div class="spwoocs-header-nav-menu">
							<ul>
								<li><a class="active" data-id="get-start-tab"  href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wcslider&page=wcsp_help#get-start' ); ?>"><i class="spwoocs-icon-play"></i> Get Started</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wcslider&page=wcsp_help#recommended' ); ?>" data-id="recommended-tab"><i class="spwoocs-icon-recommended"></i> Recommended</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wcslider&page=wcsp_help#lite-to-pro' ); ?>" data-id="lite-to-pro-tab"><i class="spwoocs-icon-lite-to-pro-icon"></i> Lite Vs Pro</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wcslider&page=wcsp_help#about-us' ); ?>" data-id="about-us-tab"><i class="spwoocs-icon-info-circled-alt"></i> About Us</a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			<!-- Header section end -->

			<!-- Start Page -->
			<section class="spwoocs__help start-page" id="get-start-tab">
				<div class="spwoocs-container">
					<div class="spwoocs-start-page-wrap">
						<div class="spwoocs-video-area">
							<h2 class='spwoocs-section-title'>Welcome to Woo Category Slider!</h2>
							<span class='spwoocs-normal-paragraph'>Thank you for installing Woo Category Slider! This video will help you get started with the plugin. Enjoy!</span>
							<iframe width="724" height="405" src="https://www.youtube.com/embed/X_Czmx3ndjU?si=FG32mVzfhkC-3WEA" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
							<ul>
								<li><a class='spwoocs-medium-btn' href="<?php echo esc_url( home_url( '/' ) . 'wp-admin/post-new.php?post_type=sp_wcslider' ); ?>">Create a Slider</a></li>
								<li><a target="_blank" class='spwoocs-medium-btn' href="https://demo.shapedplugin.com/woocommerce-category-slider/">Live Demo</a></li>
								<li><a target="_blank" class='spwoocs-medium-btn arrow-btn' href="https://shapedplugin.com/plugin/woocommerce-category-slider-pro/?ref=1">Explore Woo Category Slider <i class="spwoocs-icon-button-arrow-icon"></i></a></li>
							</ul>
						</div>
						<div class="spwoocs-start-page-sidebar">
							<div class="spwoocs-start-page-sidebar-info-box">
								<div class="spwoocs-info-box-title">
									<h4><i class="spwoocs-icon-doc-icon"></i> Documentation</h4>
								</div>
								<span class='spwoocs-normal-paragraph'>Explore Woo Category Slider plugin capabilities in our enriched documentation.</span>
								<a target="_blank" class='spwoocs-small-btn' href="https://docs.shapedplugin.com/docs/woocommerce-category-slider/introduction/">Browse Now</a>
							</div>
							<div class="spwoocs-start-page-sidebar-info-box">
								<div class="spwoocs-info-box-title">
									<h4><i class="spwoocs-icon-support"></i> Technical Support</h4>
								</div>
								<span class='spwoocs-normal-paragraph'>For personalized assistance, reach out to our skilled support team for prompt help.</span>
								<a target="_blank" class='spwoocs-small-btn' href="https://shapedplugin.com/create-new-ticket/">Ask Now</a>
							</div>
							<div class="spwoocs-start-page-sidebar-info-box">
								<div class="spwoocs-info-box-title">
									<h4><i class="spwoocs-icon-team-icon"></i> Join The Community</h4>
								</div>
								<span class='spwoocs-normal-paragraph'>Join the official ShapedPlugin Facebook group to share your experiences, thoughts, and ideas.</span>
								<a target="_blank" class='spwoocs-small-btn' href="https://www.facebook.com/groups/ShapedPlugin/">Join Now</a>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Lite To Pro Page -->
			<section class="spwoocs__help lite-to-pro-page" id="lite-to-pro-tab">
				<div class="spwoocs-container">
					<div class="spwoocs-call-to-action-top">
						<h2 class="spwoocs-section-title">Lite vs Pro Comparison</h2>
						<a target="_blank" href="https://shapedplugin.com/plugin/woocommerce-category-slider-pro/?ref=1" class='spwoocs-big-btn'>Upgrade to Pro Now!</a>
					</div>
					<div class="spwoocs-lite-to-pro-wrap">
						<div class="spwoocs-features">
							<ul>
								<li class='spwoocs-header'>
									<span class='spwoocs-title'>FEATURES</span>
									<span class='spwoocs-free'>Lite</span>
									<span class='spwoocs-pro'><i class='spwoocs-icon-pro'></i> PRO</span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>All Free Version Features</span>
									<span class='spwoocs-free spwoocs-check-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Layout Presets (Slider, Grid, Block)</span>
									<span class='spwoocs-free'><b>1</b></span>
									<span class='spwoocs-pro'><b>3</b></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Ticker Mode Carousel</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Show Child Category on Archive Page <i class="spwoocs-new">New</i></span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Display Parent, Child, Grand Child, and Great-grand Child Individually (Parent/All) <i class="spwoocs-hot">Hot</i></span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Parent and First-Level Child Selection Option <i class="spwoocs-new">New</i><i class="spwoocs-hot">Hot</i></span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Display Child Categories (Beside parent, below parent) <i class="spwoocs-hot">Hot</i></span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Filtering WooCommerce Categories, you want to show</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Child Categories Product Count</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Hide Empty Categories</span>
									<span class='spwoocs-free spwoocs-check-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Hide Category without Thumbnail</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Display Categories Randomly</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Category Content Position <i class="spwoocs-new">New</i></span>
									<span class='spwoocs-free'><b>1</b></span>
									<span class='spwoocs-pro'><b>5</b></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Display Card Style Category Showcase</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Stylize Overlay Visibility, Content Color Type, Content Type (Fully Covered and Caption Style)</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Equalize Categories/Items Height</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Manage Category Content (Category Name, Product Count, Description, Name and Description Margin)</span>
									<span class='spwoocs-free spwoocs-check-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Display Category Icon, Icon Size, Custom Category Text</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Add Category Icon from Icon Library <i class="spwoocs-hot">Hot</i></span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Add Thumbnail for Category Archive Pages</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Stylize the Shop Now Button Label, Color, Border, Margin, etc.</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Multiple Ajax Paginations (Load More, Number, and Infinite Scroll)</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Category Items to Show Per Page/Click</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Category Thumbnail Custom Dimension and Retina Ready Supported</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Add Custom Thumbnail used as Placeholder.</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Category Thumbnail Shapes (Square, Rounded, Circle, Custom) </span>
									<span class='spwoocs-free'><b>1</b></span>
									<span class='spwoocs-pro'><b>4</b></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Category Thumbnail Border, Box-shadow, Inner Padding, etc.</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Category Thumbnail Zoom and Grayscale Modes</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Powerful Slider Controls (Autoplay, Autoplay Speed, Pause on hover, Slide to Scroll, Navigation, Pagination, etc.) </span>
									<span class='spwoocs-free spwoocs-check-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Fade Effect and Multi-row Category Sliders</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Export or Import Category Showcases/Views</span>
									<span class='spwoocs-free spwoocs-check-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Stylize your Category Showcase Typography with 1500+ Google Fonts</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>All Premium Features, Security Enhancements, and Compatibility</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
								<li class='spwoocs-body'>
									<span class='spwoocs-title'>Priority Top-notch Support</span>
									<span class='spwoocs-free spwoocs-close-icon'></span>
									<span class='spwoocs-pro spwoocs-check-icon'></span>
								</li>
							</ul>
						</div>
						<div class="spwoocs-upgrade-to-pro">
							<h2 class='spwoocs-section-title'>Upgrade to PRO & Enjoy Advanced Features!</h2>
							<span class='spwoocs-section-subtitle'>Already, <b>15000+</b> people are using Woo Category Slider on their websites to create beautiful showcase, why won’t you!</span>
							<div class="spwoocs-upgrade-to-pro-btn">
								<div class="spwoocs-action-btn">
									<a target="_blank" href="https://shapedplugin.com/plugin/woocommerce-category-slider-pro/?ref=1" class='spwoocs-big-btn'>Upgrade to Pro Now!</a>
									<span class='spwoocs-small-paragraph'>14-Day No-Questions-Asked <a target="_blank" href="https://shapedplugin.com/refund-policy/">Refund Policy</a></span>
								</div>
								<a target="_blank" href="https://shapedplugin.com/plugin/woocommerce-category-slider-pro/?ref=1" class='spwoocs-big-btn-border'>See All Features</a>
								<a target="_blank" href="https://demo.shapedplugin.com/woocommerce-category-slider/" class='spwoocs-big-btn-border spwoocs-live-pro-demo'>Pro Live Demo</a>
							</div>
						</div>
					</div>
					<div class="spwoocs-testimonial">
						<div class="spwoocs-testimonial-title-section">
							<span class='spwoocs-testimonial-subtitle'>NO NEED TO TAKE OUR WORD FOR IT</span>
							<h2 class="spwoocs-section-title">Our Users Love Woo Category Slider Pro!</h2>
						</div>
						<div class="spwoocs-testimonial-wrap">
							<div class="spwoocs-testimonial-area">
								<div class="spwoocs-testimonial-content">
									<p>This is the best plugin for managing categories, i was having some issues and starting looking at others and was very disappointed at whas was available, they are not even close. Thankfully they were...</p>
								</div>
								<div class="spwoocs-testimonial-info">
									<div class="spwoocs-img">
										<img src="<?php echo esc_url( SP_WCS_URL . 'admin/help-page/img/green.png' ); ?>" alt="">
									</div>
									<div class="spwoocs-info">
										<h3>Green Rep Exchange</h3>
										<div class="spwoocs-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
							<div class="spwoocs-testimonial-area">
								<div class="spwoocs-testimonial-content">
									<p>It’s taken me years to find a plugin to do this what this plugin does.It allows us to simplify our static menu structure and show the sub-categories above each category page...</p>
								</div>
								<div class="spwoocs-testimonial-info">
									<div class="spwoocs-img">
										<img src="<?php echo esc_url( SP_WCS_URL . 'admin/help-page/img/testimonial.svg' ); ?>" alt="">
									</div>
									<div class="spwoocs-info">
										<h3>Martynsaunders</h3>
										<div class="spwoocs-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
							<div class="spwoocs-testimonial-area">
								<div class="spwoocs-testimonial-content">
									<p>The problem was a conflict with the theme, but he solved my problem with a kind consultation.Exactly the problem was the conflict with the theme of delay load.I’m satisfied with your service. Tha...</p>
								</div>
								<div class="spwoocs-testimonial-info">
									<div class="spwoocs-img">
										<img src="<?php echo esc_url( SP_WCS_URL . 'admin/help-page/img/ncia.png' ); ?>" alt="">
									</div>
									<div class="spwoocs-info">
										<h3>Ncia</h3>
										<div class="spwoocs-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Recommended Page -->
			<section id="recommended-tab" class="spwoocs-recommended-page">
				<div class="spwoocs-container">
					<h2 class="spwoocs-section-title">Enhance your Website with our Free Robust Plugins</h2>
					<div class="spwoocs-wp-list-table plugin-install-php">
						<div class="spwoocs-recommended-plugins" id="the-list">
							<?php
								$this->spwoocs_plugins_info_api_help_page();
							?>
						</div>
					</div>
				</div>
			</section>

			<!-- About Page -->
			<section id="about-us-tab" class="spwoocs__help about-page">
				<div class="spwoocs-container">
					<div class="spwoocs-about-box">
						<div class="spwoocs-about-info">
							<h3>The Best WooCommerce Category Showcase plugin by the Woo Category Slider Team, ShapedPlugin, LLC</h3>
							<p>At <b>ShapedPlugin LLC</b>, we always want to help WooCommerce store owners boost sales with different easy sales booster plugins. However, we have yet to find a plugin that effectively displays product categories when it's vital for customers to know what products you offer.</p>
							<p>Hence, we have created a plugin that beautifully displays WooCommerce categories. You can easily filter categories, including parent, child, grandchild, great-grandchild, and more. Check it out now, and you'll love it!</p>
							<div class="spwoocs-about-btn">
								<a target="_blank" href="https://shapedplugin.com/plugin/woocommerce-category-slider-pro/?ref=1" class='spwoocs-medium-btn'>Explore Woo Category Slider</a>
								<a target="_blank" href="https://shapedplugin.com/about-us/" class='spwoocs-medium-btn spwoocs-arrow-btn'>More About Us <i class="spwoocs-icon-button-arrow-icon"></i></a>
							</div>
						</div>
						<div class="spwoocs-about-img">
							<img src="https://shapedplugin.com/wp-content/uploads/2024/01/shapedplugin-team.jpg" alt="">
							<span>Team ShapedPlugin LLC at WordCamp Sylhet</span>
						</div>
					</div>
					<div class="spwoocs-our-plugin-list">
						<h3 class="spwoocs-section-title">Upgrade your Website with our High-quality Plugins!</h3>
						<div class="spwoocs-our-plugin-list-wrap">
							<a target="_blank" class="spwoocs-our-plugin-list-box" href="https://wordpresscarousel.com/">
								<i class="spwoocs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/wp-carousel-free/assets/icon-256x256.png" alt="">
								<h4>WP Carousel</h4>
								<p>The most powerful and user-friendly multi-purpose carousel, slider, & gallery plugin for WordPress.</p>
							</a>
							<a target="_blank" class="spwoocs-our-plugin-list-box" href="https://realtestimonials.io/">
								<i class="spwoocs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/testimonial-free/assets/icon-256x256.png" alt="">
								<h4>Real Testimonials</h4>
								<p>Simply collect, manage, and display Testimonials on your website and boost conversions.</p>
							</a>
							<a target="_blank" class="spwoocs-our-plugin-list-box" href="https://smartpostshow.com/">
								<i class="spwoocs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/post-carousel/assets/icon-256x256.png" alt="">
								<h4>Smart Post Show</h4>
								<p>Filter and display posts (any post types), pages, taxonomy, custom taxonomy, and custom field, in beautiful layouts.</p>
							</a>
							<a target="_blank" href="https://wooproductslider.io/" class="spwoocs-our-plugin-list-box">
								<i class="spwoocs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-product-slider/assets/icon-256x256.png" alt="">
								<h4>Product Slider for WooCommerce</h4>
								<p>Boost sales by interactive product Slider, Grid, and Table in your WooCommerce website or store.</p>
							</a>
							<a target="_blank" class="spwoocs-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-gallery-slider-pro/">
								<i class="spwoocs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/gallery-slider-for-woocommerce/assets/icon-256x256.png" alt="">
								<h4>Gallery Slider for WooCommerce</h4>
								<p>Product gallery slider and additional variation images gallery for WooCommerce and boost your sales.</p>
							</a>
							<a target="_blank" class="spwoocs-our-plugin-list-box" href="https://getwpteam.com/">
								<i class="spwoocs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/team-free/assets/icon-256x256.png" alt="">
								<h4>WP Team</h4>
								<p>Display your team members smartly who are at the heart of your company or organization!</p>
							</a>
							<a target="_blank" class="spwoocs-our-plugin-list-box" href="https://logocarousel.com/">
								<i class="spwoocs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/logo-carousel-free/assets/icon-256x256.png" alt="">
								<h4>Logo Carousel</h4>
								<p>Showcase a group of logo images with Title, Description, Tooltips, Links, and Popup as a grid or in a carousel.</p>
							</a>
							<a target="_blank" class="spwoocs-our-plugin-list-box" href="https://easyaccordion.io/">
								<i class="spwoocs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/easy-accordion-free/assets/icon-256x256.png" alt="">
								<h4>Easy Accordion</h4>
								<p>Minimize customer support by offering comprehensive FAQs and increasing conversions.</p>
							</a>
							<a target="_blank" class="spwoocs-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-category-slider-pro/">
								<i class="spwoocs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-category-slider-grid/assets/icon-256x256.png" alt="">
								<h4>Category Slider for WooCommerce</h4>
								<p>Display by filtering the list of categories aesthetically and boosting sales.</p>
							</a>
							<a target="_blank" class="spwoocs-our-plugin-list-box" href="https://wptabs.com/">
								<i class="spwoocs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/wp-expand-tabs-free/assets/icon-256x256.png" alt="">
								<h4>WP Tabs</h4>
								<p>Display tabbed content smartly & quickly on your WordPress site without coding skills.</p>
							</a>
							<a target="_blank" class="spwoocs-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-quick-view-pro/">
								<i class="spwoocs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-quickview/assets/icon-256x256.png" alt="">
								<h4>Quick View for WooCommerce</h4>
								<p>Quickly view product information with smooth animation via AJAX in a nice Modal without opening the product page.</p>
							</a>
							<a target="_blank" class="spwoocs-our-plugin-list-box" href="https://shapedplugin.com/plugin/smart-brands-for-woocommerce/">
								<i class="spwoocs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/smart-brands-for-woocommerce/assets/icon-256x256.png" alt="">
								<h4>Smart Brands for WooCommerce</h4>
								<p>Smart Brands for WooCommerce Pro helps you display product brands in an attractive way on your online store.</p>
							</a>
						</div>
					</div>
				</div>
			</section>

			<!-- Footer Section -->
			<section class="spwoocs-footer">
				<div class="spwoocs-footer-top">
					<p><span>Made With <i class="spwoocs-icon-heart"></i> </span> By the <a target="_blank" href="https://shapedplugin.com/">ShapedPlugin LLC</a> Team</p>
					<p>Get connected with</p>
					<ul>
						<li><a target="_blank" href="https://www.facebook.com/ShapedPlugin/"><i class="spwoocs-icon-fb"></i></a></li>
						<li><a target="_blank" href="https://twitter.com/intent/follow?screen_name=ShapedPlugin"><i class="spwoocs-icon-x"></i></a></li>
						<li><a target="_blank" href="https://profiles.wordpress.org/shapedplugin/#content-plugins"><i class="spwoocs-icon-wp-icon"></i></a></li>
						<li><a target="_blank" href="https://youtube.com/@ShapedPlugin?sub_confirmation=1"><i class="spwoocs-icon-youtube-play"></i></a></li>
					</ul>
				</div>
			</section>
		</div>
		<?php
	}

}

Woo_Category_Slider_Help::instance();
