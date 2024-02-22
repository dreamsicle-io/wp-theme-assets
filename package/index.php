<?php
/**
 * Index
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

	<main id="content">

		<?php if ( is_archive() ) { ?>

			<?php the_archive_title( '<h1>', '</h1>' ); ?>

			<?php the_archive_description( '<div>', '</div>' ); ?>

		<?php } ?>

		<?php if ( have_posts() ) { ?>

			<?php while ( have_posts() ) { ?>

				<?php the_post(); ?>

				<?php if ( is_singular() ) { ?>

					<?php get_template_part( 'partials/singular', get_post_type() ); ?>

					<?php wp_link_pages(
						array(
							'before' => '<nav>',
							'after'  => '</nav>',
						)
					); ?>

					<?php comments_template(); ?>

					<?php the_post_navigation(); ?>

				<?php } else { ?>

					<?php get_template_part( 'partials/card', get_post_type() ); ?>

				<?php } ?>

			<?php } ?>

			<?php if ( ! is_singular() ) { ?>

				<?php the_posts_pagination(); ?>

			<?php } ?>

		<?php } else { ?>

			<?php get_template_part( 'partials/error' ); ?>

		<?php } ?>

	</main>

<?php
get_footer();
