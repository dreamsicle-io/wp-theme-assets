<?php
/**
 * Header
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @since       0.0.1
 * @package     wp-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<meta name="theme-color" content="#000000">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>
		<?php get_template_part( 'partials/masthead' ); ?>
		<main>
