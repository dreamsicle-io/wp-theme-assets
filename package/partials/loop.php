<?php 
/**
 * Loop
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @since      0.0.1
 * @package    wp-theme
 * @subpackage partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! have_posts() ) {
	return; 
} ?>

<div>

	<?php while ( have_posts() ) { the_post(); ?>

		<?php get_template_part( 'partials/card', get_post_type() ); ?>

	<?php } ?>

	<?php the_posts_pagination(); ?>

</div>
