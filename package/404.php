<?php
/**
 * 404
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wp-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

	<?php get_template_part( 'partials/error', '404', array(
		'title' => __( '404', 'wp-theme' ),
	) ); ?>

<?php
get_footer();
