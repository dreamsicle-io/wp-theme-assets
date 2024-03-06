<?php
/**
 * List Header
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div>

	<?php if ( is_archive() ) { ?>

		<?php the_archive_title( '<h1>', '</h1>' ); ?>

		<?php the_archive_description( '<div>', '</div>' ); ?>

	<?php } elseif ( is_search() ) { ?>

		<h1><?php the_search_query(); ?></h1>

	<?php } ?>

</div>
