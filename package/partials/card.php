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

	<?php if ( has_post_thumbnail() ) { ?>

		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>

	<?php } ?>

	<a href="<?php the_permalink(); ?>"><?php the_title( '<h2>', '</h2>' ); ?></a>

	<div><?php the_excerpt(); ?></div>

	<div>

		<?php if ( post_type_supports( get_post_type(), 'comments' ) ) { ?>

			<?php wp_theme_comment_count_link(); ?>

		<?php } ?>

		<?php if ( post_type_supports( get_post_type(), 'author' ) ) { ?>

			<?php the_author_posts_link(); ?>

		<?php } ?>

	</div>

	<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'wp-theme' ); ?></a>

</article>
