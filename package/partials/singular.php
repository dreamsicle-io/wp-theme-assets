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

	<?php the_post_thumbnail( 'large' ); ?>

	<?php the_title( '<h1>', '</h1>' ); ?>

	<div><?php the_content(); ?></div>

</article>
