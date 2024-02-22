<?php
/**
 * Colophon
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<footer>

	<a href="<?php echo esc_url( home_url( '/' ) ); ?>">

		<?php if ( has_custom_logo() ) { ?> 

			<?php the_custom_logo(); ?>

		<?php } else { ?>

			<h2><?php bloginfo( 'name' ); ?></h2>

		<?php } ?>

	</a>

	<?php wp_nav_menu(
		array(
			'theme_location' => 'footer',
			'container'      => 'nav',
			'fallback_cb'    => false,
		)
	); ?>

	<?php wp_nav_menu(
		array(
			'theme_location' => 'social',
			'container'      => 'nav',
			'fallback_cb'    => false,
		)
	); ?>

	<?php wp_nav_menu(
		array(
			'theme_location' => 'legal',
			'container'      => 'nav',
			'fallback_cb'    => false,
		)
	); ?>

</footer>
