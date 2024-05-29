<?php
/**
 * Comments
 *
 * @link https://developer.wordpress.org/themes/template-files-section/partial-and-miscellaneous-template-files/comment-template/
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() || ! post_type_supports( get_post_type(), 'comments' ) ) {
	return;
} ?>

<div id="comments">

	<?php if ( have_comments() ) { ?>

		<h2><?php wp_theme_comment_count_message(); ?></h2>

		<ol>

			<?php wp_list_comments(
				array(
					'style' => 'ol',
				)
			); ?>

		</ol>

		<?php the_comments_navigation(); ?>

	<?php } ?>

	<?php if ( comments_open() ) { ?>

		<?php comment_form(); ?>

	<?php } else { ?>

		<p><?php esc_html_e( 'Comments are closed.', 'wp-theme' ); ?></p>

	<?php } ?>

</div>
