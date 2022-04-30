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

	<h1>
		<?php bloginfo( 'name' ); ?>
	</h1>

	<div>
		<p><?php bloginfo('description'); ?></p>
	</div>

	<?php if ( have_posts() ) { ?>

		<?php get_template_part( 'partials/loop', 'index' ); ?>

	<?php } else { ?>

		<?php get_template_part( 'partials/error', 'index' ); ?>

	<?php } ?>

<?php
get_footer();
