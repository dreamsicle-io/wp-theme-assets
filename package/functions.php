<?php
/**
 * Functions
 *
 * @since 0.0.1
 */

// Define constants.
define( 'WP_THEME_PATH', get_template_directory() );

// Require files.
require WP_THEME_PATH . '/includes/class-wp-theme-assets.php';

// Initialize classes.
add_action( 'after_setup_theme', array( new WP_Theme_Assets(), 'init' ), 0 );
