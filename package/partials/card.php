<?php 
/**
 * Card
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @since       0.0.1
 * @package     wp-theme
 * @subpackage  partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<article <?php post_class(); ?>>

	<?php the_post_thumbnail( 'medium' ); ?>

	<?php the_title( 
		sprintf( '<h3><a href="%1$s">', esc_url( get_permalink() ) ),
		'</a></h3>' 
	); ?>

	<?php the_excerpt(); ?>

	<a href="<?php the_permalink(); ?>">
		<?php echo esc_html_e( 'Read more', 'wp-theme' ) ?>
	</a>

</article>
