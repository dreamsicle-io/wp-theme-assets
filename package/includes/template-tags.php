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
 * WP Theme Comment Count Message
 *
 * @since 0.0.1
 */
function wp_theme_comment_count_link() {

	$count = get_comments_number();

	$message = sprintf(
		/* translators: 1: comment count number. */
		_n( '%1$s comment', '%1$s comments', $count, 'wp-theme' ),
		number_format_i18n( $count ),
	); ?>

	<a href="<?php comments_link(); ?>"><?php echo esc_html( $message ); ?></a>

<?php }
