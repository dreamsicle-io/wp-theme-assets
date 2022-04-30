<?php
/**
 * Comments
 *
 * This is the template that displays the area of the page that contains both 
 * the current comments and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @since    0.0.1
 * @package  wp-theme
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

$comments_number = get_comments_number();
$post_type = get_post_type_object( get_post_type() ); ?>

<div>
	
	<?php if ( have_comments() ) { ?>

		<h2><?php 
			printf( 
				/* translators: 1: comment count number. */
				esc_html( _nx( '%1$s comment', '%1$s comments', $comments_number, 'Comments title', 'wp-theme' ) ),
				number_format_i18n( $comments_number ),
			);
		?></h2>

		<ol><?php
			wp_list_comments( array(
				'style' => 'ol',
			) );
		?></ol>

		<?php the_comments_navigation(); ?>

		<?php if ( ! comments_open() ) {
			get_template_part( 'partials/error', 'comments', array(
				'title'       => __( 'Comments are closed', 'wp-theme' ),
				'description' => sprintf(
					/* translators: 1: post type name. */
					__( 'The comment period for this %1$s is over.', 'wp-theme' ),
					$post_type->labels->singular_name
				),
			) );
		}
	} ?>

	<?php comment_form(); ?>

<div>
