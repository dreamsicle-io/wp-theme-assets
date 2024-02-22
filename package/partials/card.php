<?php
/**
 * Card
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<article <?php post_class(); ?>>

	<a href="<?php the_permalink(); ?>">
		<?php the_post_thumbnail( 'medium' ); ?>
	</a>

	<a href="<?php the_permalink(); ?>">
		<?php the_title( '<h2>', '</h2>' ); ?>
	</a>

	<div><?php the_excerpt(); ?></div>

	<div>

		<?php if ( post_type_supports( get_post_type(), 'comments' ) ) { ?>

			<a href="<?php comments_link(); ?>">
				<?php printf(
					/* translators: 1: comment count number. */
					esc_html( _n( '%1$s comment', '%1$s comments', get_comments_number(), 'wp-theme' ) ),
					esc_html( number_format_i18n( get_comments_number() ) ),
				); ?>
			</a>

		<?php } ?>

		<?php if ( post_type_supports( get_post_type(), 'author' ) ) { ?>

			<?php the_author_posts_link(); ?>

		<?php } ?>

	</div>

	<a href="<?php the_permalink(); ?>">
		<?php esc_html_e( 'Read more', 'wp-theme' ); ?>
	</a>

</article>
