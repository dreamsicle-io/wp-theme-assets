<?php 
/**
 * Error
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

$args = wp_parse_args( $args, array(
	'title' => __( 'Nothing found', 'wp-theme' ),
	'description' => __( 'The content you\'re looking for has moved or doesn\'t exist. Check the URL and try again. If you\'re having trouble finding what you\'re looking for, try the links below to get going in the right direction.', 'wp-theme' )
) ); ?>

<div>

	<?php if ( $args['title'] ) { ?>
		<h2>
			<?php echo wpautop( wp_theme_kses_title( $args['title'] ) ); ?>
		</h2>
	<?php } ?>

	<?php if ( $args['description'] ) { ?>
		<div>
			<?php echo wpautop( wp_theme_kses_description( $args['description'] ) ); ?>
		</div>
	<?php } ?>

	<?php wp_nav_menu( array(
		'theme_location' => 'error',
		'container' => 'nav',
		'fallback_cb' => false,
		'depth' => 1,
	) ); ?>

</div>
