<?php
/**
 * Functions
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define constants.
define( 'WP_THEME_TEMPLATE_DIRECTORY_PATH', get_template_directory() );
define( 'WP_THEME_TEMPLATE_DIRECTORY_URI', get_template_directory_uri() );

// Require files.
require WP_THEME_TEMPLATE_DIRECTORY_PATH . '/vendor/autoload.php';
require WP_THEME_TEMPLATE_DIRECTORY_PATH . '/includes/template-tags.php';
require WP_THEME_TEMPLATE_DIRECTORY_PATH . '/includes/class-wp-theme-backstage.php';
require WP_THEME_TEMPLATE_DIRECTORY_PATH . '/includes/class-wp-theme-setup.php';
require WP_THEME_TEMPLATE_DIRECTORY_PATH . '/includes/class-wp-theme-assets.php';
require WP_THEME_TEMPLATE_DIRECTORY_PATH . '/includes/class-wp-theme-seo.php';

// Initialize classes.
if ( class_exists( 'WP_Backstage' ) ) {
	add_action( 'after_setup_theme', array( new WP_Theme_Backstage(), 'init' ), 0 );
}
add_action( 'after_setup_theme', array( new WP_Theme_Setup(), 'init' ), 0 );
add_action( 'after_setup_theme', array( new WP_Theme_Assets(), 'init' ), 0 );
add_action( 'after_setup_theme', array( new WP_Theme_SEO(), 'init' ), 0 );
