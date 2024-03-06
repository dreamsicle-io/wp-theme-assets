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

		<?php if ( is_singular() ) { ?>

			<?php get_template_part( 'partials/singular', get_post_type() ); ?>

			<?php wp_link_pages(); ?>

			<?php comments_template(); ?>

			<?php the_post_navigation(); ?>

		<?php } else { ?>

			<?php get_template_part( 'partials/list-header' ); ?>

			<?php get_template_part( 'partials/list' ); ?>

			<?php the_posts_pagination(); ?>

		<?php } ?>

	</main>

<?php
get_footer();
