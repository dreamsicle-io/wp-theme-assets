<?php
/**
 * Functions
 *
 * @since 0.0.1
 */

// Require files.
require get_template_directory() . '/includes/class-wp-theme-assets.php';

// Initialize classes.
add_action( 'after_setup_theme', array( new WP_Theme_Assets(), 'init' ), 0 );
