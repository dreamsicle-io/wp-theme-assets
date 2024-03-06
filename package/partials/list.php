<?php
/**
 * List
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;

$args = wp_parse_args(
	$args,
	array(
		'query'                 => $wp_query,
		'partial_slug'          => 'partials/card',
		'partial_args'          => array(),
		'not_found_title'       => '',
		'not_found_description' => '',
	)
); ?>

<div>

	<?php if ( $args['query']->have_posts() ) { ?>

		<div>

			<?php while ( $args['query']->have_posts() ) { ?>

				<?php $args['query']->the_post(); ?>

				<?php get_template_part( $args['partial_slug'], get_post_type(), $args['partial_args'] ); ?>

			<?php } ?>

		</div>

		<?php if ( ! $args['query']->is_main_query() ) {
			wp_reset_postdata();
		} ?>

	<?php } else { ?>

		<?php get_template_part(
			'partials/error',
			null,
			array(
				'title'       => $args['not_found_title'],
				'description' => $args['not_found_description'],
			)
		); ?>

	<?php } ?>

</div>
