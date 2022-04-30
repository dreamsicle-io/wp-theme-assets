<?php 
/**
 * Functions
 *
 * @package wp-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$template_directory = get_template_directory();

require $template_directory . '/includes/template-tags.php';
require $template_directory . '/includes/class-wp-theme-setup.php';
require $template_directory . '/includes/class-wp-theme-seo.php';
require $template_directory . '/includes/class-wp-theme-assets.php';
require $template_directory . '/includes/class-wp-theme-backstage.php';
require $template_directory . '/includes/class-wp-theme-customizer.php';

add_action( 'after_setup_theme', array( new WP_Theme_Setup, 'init' ), 0 );
add_action( 'after_setup_theme', array( new WP_Theme_SEO, 'init' ), 0 );
add_action( 'after_setup_theme', array( new WP_Theme_Assets, 'init' ), 0 );
add_action( 'after_setup_theme', array( new WP_Theme_Customizer, 'init' ), 0 );

if ( class_exists( 'WP_Backstage' ) ) {
	add_action( 'after_setup_theme', array( new WP_Theme_Backstage, 'init' ), 0 );
}
