<?php
/**
 * 404
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

	<main id="content">

		<?php get_template_part(
			'partials/error',
			null,
			array(
				'title'       => __( '404', 'wp-theme' ),
				'description' => __( 'It looks like we could\'nt find what you\'re looking for.', 'wp-theme' ),
			)
		); ?>

	</main>

<?php
get_footer();
