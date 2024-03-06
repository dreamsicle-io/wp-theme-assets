<?php
/**
 * Masthead
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<header>

	<?php if ( has_custom_logo() ) { ?> 

		<?php the_custom_logo(); ?>

	<?php } else { ?>

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>

	<?php } ?>

	<?php wp_nav_menu(
		array(
			'theme_location' => 'header',
			'container'      => 'nav',
			'fallback_cb'    => false,
		)
	); ?>

	<?php get_search_form(); ?>

</header>
