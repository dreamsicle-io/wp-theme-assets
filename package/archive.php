<?php
/**
 * Index
 * 
 * This is the main template that all pages will use unless a more
 * relevant template is found in the template hierarchy.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wp-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

	<?php the_archive_title( '<h1>', '</h1>' ); ?>

	<?php the_archive_description( '<div>', '</div>' ); ?>

	<?php if ( have_posts() ) { ?>

		<?php get_template_part( 'partials/loop', 'archive' ); ?>

	<?php } else { ?>

		<?php get_template_part( 'partials/error', 'archive' ); ?>

	<?php } ?>

<?php
get_footer();
