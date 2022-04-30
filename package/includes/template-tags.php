<?php 
/**
 * Template tags
 *
 * @since       0.0.1
 * @package     wp-theme
 * @subpackage  includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wp_theme_parse_classes( $classes = array() ) {
	$parsed = array_filter( $classes, function( $is_active, $class ) {
		return boolval( $is_active );
	}, ARRAY_FILTER_USE_BOTH );	
	return implode( ' ', array_map( 'sanitize_key', array_keys( $parsed ) ) );
}

function wp_theme_get_post_id( $suffix = '' ) {
	$post_type = get_post_type();
	$post_id = get_the_ID();
	return sprintf( 
		'%1$s_%2$s%3$s', 
		$post_type, 
		$post_id, 
		$suffix ? '_' . $suffix : '' 
	);
}

function wp_theme_post_id( $suffix = '' ) {
	echo esc_attr( wp_theme_get_post_id( $suffix ) );
}

function wp_theme_get_current_url() {
	global $wp;
	return home_url( add_query_arg( array(), $wp->request ) );
}

function wp_theme_get_asset_url( $path = '' ) {
	return get_template_directory_uri() . '/assets/dist/' . $path;
}

function wp_theme_get_asset_path( $path = '' ) {
	return get_template_directory() . '/assets/dist/' . $path;
}

function wp_theme_kses_title( $text = '' ) {
	return wp_kses( $text, array(
		'a' => array(
			'href' => array(),
			'title' => array(),
			'rel' => array(),
			'target' => array(),
		),
		'br' => array(),
		'em' => array(),
		'strong' => array(),
		'small' => array(),
		'sup' => array(),
		'sub' => array(),
		'time' => array(
			'datetime' => array(),
		),
	) );
}

function wp_theme_kses_description( $text = '' ) {
	return wp_kses( $text, array(
		'a' => array(
			'href' => array(),
			'title' => array(),
			'rel' => array(),
			'target' => array(),
		),
		'br' => array(),
		'em' => array(),
		'strong' => array(),
		'small' => array(),
		'sup' => array(),
		'sub' => array(),
		'time' => array(
			'datetime' => array(),
		),
	) );
}

function wp_theme_kses_button( $text = '' ) {
	return wp_kses( $text, array(
		'em' => array(),
		'strong' => array(),
		'small' => array(),
		'sup' => array(),
		'sub' => array(),
		'time' => array(
			'datetime' => array(),
		),
	) );
}

function wp_theme_svg( $path = '' ) {
	echo file_get_contents( wp_theme_get_asset_path( $path ) );
}

function wp_theme_copyright() {
	$copyright = get_theme_mod( 'wp_theme_copyright', get_bloginfo('name') );
	$copyright_url = get_theme_mod( 'wp_theme_copyright_url', home_url( '/' ) );
	$copyright_is_new_window = boolval( get_theme_mod( 'wp_theme_copyright_is_new_window', '' ) );
	echo wpautop( wp_theme_kses_description( sprintf( 
		'&copy; %1$d <a href="%2$s" target="%3$s" rel="%4$s">%5$s</a>. All rights reserved.', 
		date("Y"),
		$copyright_url,
		$copyright_is_new_window ? '_blank' : '',
		$copyright_is_new_window ? 'noopener noreferrer' : '',
		$copyright
	) ) );
}
