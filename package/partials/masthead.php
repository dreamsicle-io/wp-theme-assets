<?php 
/**
 * Masthead
 *
 * @since       0.0.1
 * @package     wp-theme
 * @subpackage  partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<header class="wp-theme-masthead">

	<div class="wp-theme-masthead__container">

		<?php the_custom_logo(); ?>

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php 
			bloginfo( 'name' );
		?></a>

		<?php wp_nav_menu( array(
			'theme_location' => 'masthead',
			'container' => 'nav',
			'fallback_cb' => false,
		) ); ?>
	
	</div>

</header>
