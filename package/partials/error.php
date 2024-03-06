<?php
/**
 * Error
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args(
	$args,
	array(
		'title'       => __( 'Error', 'wp-theme' ),
		'description' => __( 'There was an error finding what you\'re looking for.', 'wp-theme' ),
	)
); ?>

<div>

	<h2><?php echo esc_html( $args['title'] ); ?></h2>

	<div><?php echo wp_kses_post( wpautop( $args['description'] ) ); ?></div>

	<?php wp_nav_menu(
		array(
			'theme_location' => 'error',
			'container'      => 'nav',
			'fallback_cb'    => false,
		)
	); ?>

</div>
