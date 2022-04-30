<?php
/**
 * WP Theme Customizer
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @since       0.0.1
 * @package     wp-theme
 * @subpackage  includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WP_Theme_Customizer {
	
	public function init() {
		add_action( 'customize_register', array( $this, 'register_panels' ), 10 );
		add_action( 'customize_register', array( $this, 'register_sections' ), 10 );
		add_action( 'customize_register', array( $this, 'register_settings' ), 10 );
		add_action( 'customize_register', array( $this, 'register_partials' ), 10 );
	}

	public function register_panels( $wp_customize ) {
		$wp_customize->add_panel( 'wp_theme_general', array(
			'title'       => esc_attr__( 'WP Theme Settings', 'wp-theme' ),
			'description' => esc_attr__( 'All general WP Theme WordPress customizer settings.', 'wp-theme' ),
			'priority'    => 150,
		) );
	}

	public function register_sections( $wp_customize ) {
		$wp_customize->add_section( 'wp_theme_colophon', array(
			'title'       => esc_attr__( 'Colophon', 'wp-theme' ),
			'description' => esc_attr__( 'Customize the theme\'s colophon.', 'wp-theme' ),
			'panel'       => 'wp_theme_general',
			'priority'    => 10,
		) );
	}

	public function register_settings( $wp_customize ) {
		$wp_customize->add_setting( 'wp_theme_copyright', array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'wp_theme_kses_button',
		) );
		$wp_customize->add_control( 'wp_theme_copyright', array(
			'type'        => 'text',
			'section'     => 'wp_theme_colophon',
			'label'       => __( 'Copyright holder', 'wp-theme' ),
			'description' => __( 'Set the organization that holds the copyright for this site. Leave blank to use the site\'s name.', 'wp-theme' ),
			'priority'    => 10,
		) );
		$wp_customize->add_setting( 'wp_theme_copyright_url', array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_url',
		) );
		$wp_customize->add_control( 'wp_theme_copyright_url', array(
			'type'        => 'text',
			'section'     => 'wp_theme_colophon',
			'label'       => __( 'Copyright URL', 'wp-theme' ),
			'description' => __( 'Set the URL that the copyright anchor will link to. Leave blank to use the site\'s homepage.', 'wp-theme' ),
			'priority'    => 10,
		) );
		$wp_customize->add_setting( 'wp_theme_copyright_is_new_window', array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_attr',
		) );
		$wp_customize->add_control( 'wp_theme_copyright_is_new_window', array(
			'type'        => 'checkbox',
			'section'     => 'wp_theme_colophon',
			'label'       => __( 'Open link in a new window', 'wp-theme' ),
			'description' => __( 'Set whether the copyright anchor should open its URL in a new window or not.', 'wp-theme' ),
			'priority'    => 10,
		) );
	}

	public function register_partials( $wp_customize ) {
		// colophon
		$wp_customize->selective_refresh->add_partial( 'wp_theme_colophon', array(
			'selector'            => '[data-wp-theme-partial="wp_theme_colophon"]',
			'container_inclusive' => true,
			'settings'            => array( 
				'wp_theme_copyright',
				'wp_theme_copyright_url',
				'wp_theme_copyright_is_new_window',
			),
			'render_callback'     => function() {
				get_template_part( 'partials/colophon' );
			},
		) );
	}
}
