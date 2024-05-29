<?php
/**
 * Masthead
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<header>

	<?php wp_theme_branding(); ?>

	<?php wp_nav_menu(
		array(
			'theme_location' => 'header',
			'container'      => 'nav',
			'fallback_cb'    => false,
		)
	); ?>

	<?php get_search_form(); ?>

</header>
