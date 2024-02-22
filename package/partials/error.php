<?php
/**
 * Not Found
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div>

	<h2><?php esc_html_e( 'Error', 'wp-theme' ); ?></h2>

	<?php wp_nav_menu(
		array(
			'theme_location' => 'error',
			'container'      => 'nav',
			'fallback_cb'    => false,
		)
	); ?>

</div>
