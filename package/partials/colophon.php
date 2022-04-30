<?php 
/**
 * Footer
 *
 * @since       0.0.1
 * @package     wp-theme
 * @subpackage  partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<footer data-wp-theme-partial="wp_theme_colophon">

	<?php wp_nav_menu( array(
		'theme_location' => 'colophon',
		'container' => 'nav',
		'fallback_cb' => false,
		'depth' => 1,
	) ); ?>

	<div>
		<?php wp_theme_copyright(); ?>
	</div>

</footer>
