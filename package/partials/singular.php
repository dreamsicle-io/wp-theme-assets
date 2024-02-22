<?php
/**
 * Singular
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<article <?php post_class(); ?>>

	<?php if ( has_post_thumbnail() ) { ?>

		<?php the_post_thumbnail( 'large' ); ?>

	<?php } ?>

	<?php the_title( '<h1>', '</h1>' ); ?>

	<div><?php the_content(); ?></div>

</article>
