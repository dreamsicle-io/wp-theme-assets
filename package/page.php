<?php
/**
 * Singular
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @since   0.0.1
 * @package wp_theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

	<?php while ( have_posts() ) { the_post(); ?>
		
		<?php get_template_part( 'partials/content', get_post_type() ); ?>
		
		<?php if ( comments_open() || ( get_comments_number() > 0 ) ) { ?>
			
			<?php comments_template(); ?>

		<?php } ?>

	<?php } ?>

<?php
get_footer();
