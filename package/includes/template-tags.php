<?php
/**
 * Template Tags
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP Theme Branding
 *
 * @since 0.0.1
 */
function wp_theme_branding() {

	if ( has_custom_logo() ) {

		the_custom_logo();

	} else {

		printf(
			'<a href="%1$s">%2$s</a>',
			esc_url( home_url( '/' ) ),
			esc_html( get_bloginfo( 'name' ) )
		);

	}

}

/**
 * WP Theme Comment Count Message
 *
 * @since 0.0.1
 */
function wp_theme_comment_count_message() {

	$count = get_comments_number();

	printf(
		/* translators: 1: comment count number. */
		esc_html( _n( '%1$s comment', '%1$s comments', $count, 'wp-theme' ) ),
		esc_html( number_format_i18n( $count ) ),
	);

}
