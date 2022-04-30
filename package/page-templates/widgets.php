<?php 
/*
Template Name: Widgets
Template Post Type: post, page
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$widget_area = sprintf( 'widgets_template_%1$d', get_the_ID() );

get_header(); ?>

	<?php while ( have_posts() ) { the_post(); ?>
		
		<?php get_template_part( 'partials/content', get_post_type() ); ?>

		<?php if ( is_active_sidebar( $widget_area ) ) { ?>

			<?php dynamic_sidebar( $widget_area ); ?>
		
		<?php } ?>
		
		<?php if ( comments_open() || ( get_comments_number() > 0 ) ) { ?>
			
			<?php comments_template(); ?>

		<?php } ?>

		<?php the_post_navigation(); ?>

	<?php } ?>

<?php
get_footer();
