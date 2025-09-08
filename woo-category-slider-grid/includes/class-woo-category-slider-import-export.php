<?php
/**
 * Custom import export by ShapedPlugin.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package Woo_category_Slider.
 * @subpackage Woo_Category_Slider/includes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Woo_Category_Slider_Import_Export' ) ) {
	/**
	 * Custom import export.
	 */
	class Woo_Category_Slider_Import_Export {

		/**
		 * Export
		 *
		 * @param  mixed $shortcode_ids Export woo-category-slider shortcode ids.
		 * @return object
		 */
		public function export( $shortcode_ids ) {
			$export = array();
			if ( ! empty( $shortcode_ids ) ) {

				$post_in    = 'all_shortcodes' === $shortcode_ids ? '' : $shortcode_ids;
				$args       = array(
					'post_type'        => 'sp_wcslider',
					'post_status'      => array( 'inherit', 'publish' ),
					'orderby'          => 'modified',
					'suppress_filters' => 1, // wpml, ignore language filter.
					'posts_per_page'   => -1,
					'post__in'         => $post_in,
				);
				$shortcodes = get_posts( $args );
				if ( ! empty( $shortcodes ) ) {
					foreach ( $shortcodes as $shortcode ) {
						$shortcode_export = array(
							'title'       => sanitize_text_field( $shortcode->post_title ),
							'original_id' => absint( $shortcode->ID ),
							'meta'        => array(),
						);
						foreach ( get_post_meta( $shortcode->ID ) as $metakey => $value ) {
							$meta_key                              = sanitize_key( $metakey );
							$meta_value                            = is_serialized( $value[0] ) ? $value[0] : sanitize_text_field( $value[0] );
							$shortcode_export['meta'][ $meta_key ] = $meta_value;
							// $shortcode_export['meta'][ $metakey ] = $value[0];
						}
						$export['shortcode'][] = $shortcode_export;

						unset( $shortcode_export );
					}
					$export['metadata'] = array(
						'version' => SP_WCS_VERSION,
						'date'    => gmdate( 'Y/m/d' ),
					);
				}
				return $export;
			}
		}

		/**
		 * Export woo-category-slider by ajax.
		 *
		 * @return void
		 */
		public function export_shortcodes() {
			$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			if ( ! wp_verify_nonce( $nonce, 'spf_options_nonce' ) ) {
				wp_send_json_error(
					array(
						'message' => esc_html__( 'Error: Invalid nonce verification!', 'woo-category-slider-grid' ),
					),
					401
				);
			}

			$_capability = apply_filters( 'sp_category_slider_import_export_capability', 'manage_options' );
			if ( ! current_user_can( $_capability ) ) {
				wp_send_json_error( array( 'error' => esc_html__( 'You do not have permission to export.', 'woo-category-slider-grid' ) ) );
			}

			$shortcode_ids = '';
			if ( isset( $_POST['wcsp_ids'] ) ) {
				$shortcode_ids = is_array( $_POST['wcsp_ids'] ) ? wp_unslash( array_map( 'absint', $_POST['wcsp_ids'] ) ) : sanitize_text_field( wp_unslash( $_POST['wcsp_ids'] ) );
			}

			$export = $this->export( $shortcode_ids );

			if ( is_wp_error( $export ) ) {
				wp_send_json_error(
					array(
						'message' => esc_html( $export->get_error_message() ),
					),
					400
				);
			}

			if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
				// @codingStandardsIgnoreLine
				echo wp_json_encode($export, JSON_PRETTY_PRINT);
				die;
			}

			wp_send_json( $export, 200 );
		}

		/**
		 * Import shortcode.
		 *
		 * @param  mixed $shortcodes Import sliders shortcode array.
		 * @throws \Exception Get error messages.
		 * @return object
		 */
		public function import( $shortcodes ) {
			$errors = array();
			foreach ( $shortcodes as $index => $shortcode ) {
				$errors[ $index ] = array();
				$new_shortcode_id = 0;
				try {
					$new_shortcode_id = wp_insert_post(
						array(
							'post_title'  => isset( $shortcode['title'] ) ? sanitize_text_field( $shortcode['title'] ) : '',
							'post_status' => 'publish',
							'post_type'   => 'sp_wcslider',
						),
						true
					);
					if ( is_wp_error( $new_shortcode_id ) ) {
						throw new \Exception( $new_shortcode_id->get_error_message() );
					}

					if ( isset( $shortcode['meta'] ) && is_array( $shortcode['meta'] ) ) {
						foreach ( $shortcode['meta'] as $key => $value ) {
							update_post_meta(
								$new_shortcode_id,
								$key,
								maybe_unserialize( str_replace( '{#ID#}', $new_shortcode_id, $value ) )
							);
						}
					}
				} catch ( \Exception $e ) {
					array_push( $errors[ $index ], $e->getMessage() );

					// If there was a failure somewhere, clean up.
					wp_trash_post( $new_shortcode_id );
				}

				// If no errors, remove the index.
				if ( ! count( $errors[ $index ] ) ) {
					unset( $errors[ $index ] );
				}

				// External modules manipulate data here.
				do_action( 'sp_woo_category_slider_imported', $new_shortcode_id );
			}

			$errors = reset( $errors );
			return isset( $errors[0] ) ? new \WP_Error( 'import_woo_category_slider_error', $errors[0] ) : '';
		}

		/**
		 * Import woo-category-slider-grid by ajax.
		 *
		 * @return void
		 */
		public function import_shortcodes() {
			$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			if ( ! wp_verify_nonce( $nonce, 'spf_options_nonce' ) ) {
				wp_send_json_error(
					array(
						'message' => esc_html__( 'Error: Invalid nonce verification!', 'woo-category-slider-grid' ),
					),
					401
				);
			}

			$_capability = apply_filters( 'sp_category_slider_import_export_capability', 'manage_options' );
			if ( ! current_user_can( $_capability ) ) {
				wp_send_json_error( array( 'error' => esc_html__( 'You do not have permission to import.', 'woo-category-slider-grid' ) ) );
			}

			$data = isset( $_POST['shortcode'] ) ? wp_kses_data( wp_unslash( $_POST['shortcode'] ) ) : '';
			if ( ! $data ) {
				wp_send_json_error(
					array(
						'message' => esc_html__( 'Nothing to import.', 'woo-category-slider-grid' ),
					),
					400
				);
			}

			// Decode JSON with error checking.
			$decoded_data = json_decode( $data, true );
			if ( is_string( $decoded_data ) ) {
				$decoded_data = json_decode( $decoded_data, true );
			}

			if ( json_last_error() !== JSON_ERROR_NONE ) {
				wp_send_json_error(
					array(
						'message' => esc_html__( 'Invalid JSON data.', 'woo-category-slider-grid' ),
					),
					400
				);
			}

			// Check if shortcode key exists and is valid.
			if ( ! isset( $decoded_data['shortcode'] ) || ! is_array( $decoded_data['shortcode'] ) ) {
				wp_send_json_error(
					array(
						'message' => esc_html__( 'Invalid shortcode data structure.', 'woo-category-slider-grid' ),
					),
					400
				);
			}

			$shortcodes = map_deep(
				$decoded_data['shortcode'],
				function ( $value ) {
					return is_string( $value ) ? sanitize_text_field( $value ) : $value;
				}
			);

			$status = $this->import( $shortcodes );

			if ( is_wp_error( $status ) ) {
				wp_send_json_error(
					array(
						'message' => $status->get_error_message(),
					),
					400
				);
			}

			wp_send_json_success( $status, 200 );
		}
	}
}
