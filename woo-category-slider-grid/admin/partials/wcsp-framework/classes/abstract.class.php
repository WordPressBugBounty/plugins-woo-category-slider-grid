<?php

/**
 * Framework abstract.class file.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/framework
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WCS_Abstract' ) ) {
	/**
	 *
	 * Abstract Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	abstract class SP_WCS_Abstract {

		/**
		 * Abstract
		 *
		 * @var string
		 */
		public $abstract = '';
		/**
		 * Output css
		 *
		 * @var string
		 */
		public $output_css = '';
		/**
		 * Typographies
		 *
		 * @var array
		 */
		public $typographies = array();

		/**
		 * Constructor of the class.
		 */
		public function __construct() {

			// Check for embed google web fonts.
			if ( ! empty( $this->args['enqueue_webfont'] ) ) {
				add_action( 'wp_enqueue_scripts', array( &$this, 'add_enqueue_google_fonts' ), 100 );
			}

			// Check for embed custom css styles.
			if ( ! empty( $this->args['output_css'] ) ) {
				add_action( 'wp_head', array( &$this, 'add_output_css' ), 100 );
			}
		}

		/**
		 * Add output CSS.
		 *
		 * @return void
		 */
		public function add_enqueue_google_fonts() {

			if ( ! empty( $this->pre_fields ) ) {

				foreach ( $this->pre_fields as $field ) {

					$field_id     = ( ! empty( $field['id'] ) ) ? $field['id'] : '';
					$field_type   = ( ! empty( $field['type'] ) ) ? $field['type'] : '';
					$field_output = ( ! empty( $field['output'] ) ) ? $field['output'] : '';
					$field_check  = ( 'typography' === $field_type || $field_output ) ? true : false;

					if ( $field_type && $field_id ) {

						SP_WCS::maybe_include_field( $field_type );

						$class_name = 'SP_WCS_Field_' . $field_type;

						if ( class_exists( $class_name ) ) {

							if ( method_exists( $class_name, 'output' ) || method_exists( $class_name, 'enqueue_google_fonts' ) ) {

								$field_value = '';

								if ( $field_check && ( 'options' === $this->abstract || 'customize' === $this->abstract ) ) {
									$field_value = ( isset( $this->options[ $field_id ] ) && '' !== $this->options[ $field_id ] ) ? $this->options[ $field_id ] : '';
								} elseif ( $field_check && 'metabox' === $this->abstract ) {
									$field_value = $this->get_meta_value( $field );
								}

								$instance = new $class_name( $field, $field_value, $this->unique, 'wp/enqueue', $this );

								// typography enqueue and embed google web fonts.
								if ( 'typography' === $field_type && $this->args['enqueue_webfont'] && ! empty( $field_value['font-family'] ) ) {
									$instance->enqueue_google_fonts();
								}

								// output css.
								if ( $field_output && $this->args['output_css'] ) {
									$instance->output();
								}
								unset( $instance );
							}
						}
					}
				}
			}

			if ( ! empty( $this->typographies ) && empty( $this->args['async_webfont'] ) ) {

				$query  = array( 'family' => urlencode( implode( '|', $this->typographies ) ) );
				$api    = '//fonts.googleapis.com/css';
				$handle = 'SP_WCS-google-web-fonts-' . $this->unique;
				$src    = esc_url( add_query_arg( $query, $api ) );

				wp_enqueue_style( $handle, $src, array(), '1.0.0', 'all' );
				wp_enqueue_style( $handle );
			}

			if ( ! empty( $this->typographies ) && ! empty( $this->args['async_webfont'] ) ) {

				$api = '//ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';
				echo '<script type="text/javascript">';
				echo 'WebFontConfig={google:{families:[' . "'" . implode( "','", $this->typographies ) . "'" . ']}};';// phpcs:ignore
				echo '!function(e){var t=e.createElement("script"),s=e.scripts[0];t.src="' . esc_attr( $api ) . '",t.async=!0,s.parentNode.insertBefore(t,s)}(document);';
				echo '</script>';
			}
		}

		/**
		 * Add output CSS.
		 *
		 * @return void
		 */
		public function add_output_css() {
			$this->output_css = apply_filters( "SP_WCS_{$this->unique}_output_css", $this->output_css, $this );// phpcs:ignore

			if ( ! empty( $this->output_css ) ) {
				echo '<style type="text/css">' . $this->output_css . '</style>'; // phpcs:ignore
			}
		}
	}
}
