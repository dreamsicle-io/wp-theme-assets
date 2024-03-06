<?php
/**
 * WP Theme Backstage
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP Theme Backstage
 *
 * @since 0.0.1
 */
class WP_Theme_Backstage {

	/**
	 * Init
	 *
	 * @since 0.0.1
	 */
	public function init() {
		$this->init_options();
	}

	/**
	 * Init Options
	 *
	 * @since 0.0.1
	 */
	public function init_options() {
		WP_Backstage_Options::add(
			'wp_theme_options',
			array(
				'title'        => __( 'WP Theme Options', 'wp-theme' ),
				'menu_title'   => __( 'WP Theme', 'wp-theme' ),
				'description'  => __( 'All custom WP Theme WordPress options.', 'wp-theme' ),
				'show_in_rest' => true,
				'sections'     => array(
					array(
						'id'          => 'wp_theme_social',
						'title'       => __( 'Social', 'wp-theme' ),
						'description' => __( 'Configure social usernames and URLs for use in various links and meta tags.', 'wp-theme' ),
						'fields'      => array(
							array(
								'type'        => 'media',
								'name'        => 'wp_theme_share_image',
								'label'       => __( 'Share Image', 'wp-theme' ),
								'description' => __( 'Select or upload an image to be used as the default thumbnail when a more relevant image can\'t be found.', 'wp-theme' ),
							),
							array(
								'type'        => 'text',
								'name'        => 'wp_theme_twitter_handle',
								'label'       => __( 'X Handle', 'wp-theme' ),
								'description' => __( 'Enter a valid X handle (No @). Required for enriched X cards.', 'wp-theme' ),
							),
						),
					),
					array(
						'id'          => 'wp_theme_analytics',
						'title'       => __( 'Analytics', 'wp-theme' ),
						'description' => __( 'Configure tracking codes for third party analytics services.', 'wp-theme' ),
						'fields'      => array(
							array(
								'type'        => 'code',
								'name'        => 'wp_theme_tracking_codes_head',
								'label'       => __( 'Tracking codes (head)', 'wp-theme' ),
								'description' => __( 'Static HTML that will be emebedded at the bottom of the document\'s "head" tag. This will be printed unescaped and unsanitized.', 'wp-theme' ),
								'args'        => array(
									'mime' => 'text/html',
								),
							),
							array(
								'type'        => 'code',
								'name'        => 'wp_theme_tracking_codes_body_top',
								'label'       => __( 'Tracking codes (body top)', 'wp-theme' ),
								'description' => __( 'Static HTML that will be emebedded at the top of the document\'s "body" tag. This will be printed unescaped and unsanitized.', 'wp-theme' ),
								'args'        => array(
									'mime' => 'text/html',
								),
							),
							array(
								'type'        => 'code',
								'name'        => 'wp_theme_tracking_codes_body_bottom',
								'label'       => __( 'Tracking codes (body bottom)', 'wp-theme' ),
								'description' => __( 'Static HTML that will be emebedded at the bottom of the document\'s "body" tag. This will be printed unescaped and unsanitized.', 'wp-theme' ),
								'args'        => array(
									'mime' => 'text/html',
								),
							),
						),
					),
				),
			)
		);
	}
}
