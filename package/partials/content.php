<?php 
/**
 * Content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @since   0.0.1
 * @package wp_theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<article <?php post_class(); ?>>

	<?php the_post_thumbnail( 'large' ); ?>

	<?php the_title( '<h1>', '</h1>' ); ?>

	<?php the_content(); ?>

	<?php wp_link_pages(); ?>
	
	<?php foreach ( get_post_taxonomies() as $taxonomy ) {
		the_terms( 
			0, 
			$taxonomy, 
			sprintf( '<h4>%1$s:</h4>', get_taxonomy( $taxonomy )->labels->name ),
		);
	} ?>
	
</article>
