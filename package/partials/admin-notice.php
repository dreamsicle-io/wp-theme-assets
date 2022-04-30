<?php
/**
 * Admin Notice
 *
 * @since       0.0.1
 * @package     wp-theme
 * @subpackage  partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args( $args, array(
	'type'           => '', // success, error, warning
	'is_dismissible' => false, 
	'is_inline'      => false,
	'message'        => '', 
) );

$class = wp_theme_parse_classes( array(
	'wp-theme-notice' => true,
	'notice' => true,
	'is-dismissible' => $args['is_dismissible'],
	'inline' => $args['is_inline'],
	'notice-success' => ( $args['type'] === 'success' ),
	'notice-error' => ( $args['type'] === 'error' ),
	'notice-warning' => ( $args['type'] === 'warning' ),
) ); ?>

<div class="<?php echo esc_attr( $class ); ?>">
	<?php echo wpautop( wp_theme_kses_description( $args['message'] ) ); ?>
</div>
