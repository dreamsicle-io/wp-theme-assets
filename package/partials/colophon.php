<?php
/**
 * Colophon
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<footer>

	<?php wp_theme_branding(); ?>

	<?php wp_nav_menu(
		array(
			'theme_location' => 'footer',
			'container'      => 'nav',
			'fallback_cb'    => false,
		)
	); ?>

	<?php wp_theme_copyright(); ?>

	<?php wp_nav_menu(
		array(
			'theme_location' => 'legal',
			'container'      => 'nav',
			'fallback_cb'    => false,
		)
	); ?>

</footer>
