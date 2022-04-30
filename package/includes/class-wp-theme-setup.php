<?php
/**
 * WP Theme Setup
 *
 * @since       0.0.1
 * @package     wp-theme
 * @subpackage  includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WP_Theme_Setup {

	private $plugin_dependencies = array();

	public function __construct() {
		$this->plugin_dependencies = array(
			array(
				'key'  => 'wp-backstage/wp-backstage.php',
				'name' => __( 'WP Backstage', 'wp_backstage' ),
				'url'  => 'https://github.com/dreamsicle-io/wp-backstage/releases',
			),
		);
	}
	
	public function init() {
		// hooks
		add_action( 'admin_notices', array( $this, 'print_admin_notices' ), 10 );
		add_action( 'after_setup_theme', array( $this, 'manage_theme_support' ), 10 );
		add_action( 'init', array( $this, 'manage_post_type_support' ), 9999 );
		add_action( 'widgets_init', array( $this, 'register_widget_areas' ), 10 );
		add_action( 'after_setup_theme', array( $this, 'register_nav_menus' ), 10 );
		add_action( 'after_setup_theme', array( $this, 'register_image_sizes' ), 10 );
		add_action( 'template_redirect', array( $this, 'manage_template_redirects'), 10 );
		add_action( 'wp_head', array( $this, 'add_head_tracking_codes' ), 9999 );
		add_action( 'wp_body_open', array( $this, 'add_body_top_tracking_codes' ), 0 );
		add_action( 'wp_footer', array( $this, 'add_body_bottom_tracking_codes' ), 9999 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_comment_reply_script' ), 10 );
		// filters
		add_filter( 'upload_mimes', array( $this, 'manage_upload_mimes' ), 10 );
		add_filter( 'query_vars', array( $this, 'manage_query_vars'), 10 );
		add_filter( 'wp_sitemaps_add_provider', array( $this, 'manage_sitemap' ), 10, 2 );
	}

	public function manage_theme_support() {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 
			'comment-list', 
			'comment-form', 
			'search-form', 
			'gallery', 
			'caption', 
		) );
		add_theme_support( 'custom-logo', array(
			'height'               => 512,
			'width'                => 512,
			'flex-height'          => true,
			'flex-width'           => true,
			'header-text'          => array(),
		) );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'wp-backstage' );
	}

	public function manage_post_type_support() {
		// Add or remove post type support for specific features.
		// remove_post_type_support( 'page', 'comments' );
		add_post_type_support( 'page', 'excerpt' );
	}

	public function manage_template_redirects() {
		// Redirect author archives to the homepage.
		// if ( is_author() ) {
		// 	wp_redirect( home_url( '/' ) );
		// 	exit();
		// }
	}

	public function manage_query_vars( $query_vars = array() ) {
		// Add arbitrary query vars so that they are available in the query.
		// $query_vars[] = 'foo';
		// $query_vars[] = 'bar';
		return $query_vars;
	}

	public function manage_upload_mimes( $mimes = array() ) {
		// add mime types to whitelist for media uploader
		// $mimes['svg'] = 'image/svg+xml';
		// $mimes['ics'] = 'text/calendar';
		return $mimes;
	}

	public function register_image_sizes() {
		// Add image size that is not cropped.
		// add_image_size( 'wp_theme_hero_large', 1920, 1080, true );
		// Add image size that is cropped.
		// add_image_size( 'wp_theme_thumbnail_medium', 768, 768, true );
	}

	public function manage_sitemap( $provider = null, $name = '' ) {
		// Remove users from the sitemap.
		// if ( $name === 'users' ) {
		// 	$provider = null;
		// }
		return $provider;
	}

	public function register_nav_menus() {
		register_nav_menus( array(
			'masthead' => __( 'Masthead', 'wp-theme' ), 
			'colophon' => __( 'Colophon', 'wp-theme' ), 
			'error' => __( 'Error', 'wp-theme' ), 
		) );
	}

	function register_widget_areas() {
		$posts = get_posts( array(
			'post_type' => array( 'page', 'post' ),
			'post_status' => array( 'publish', 'draft' ),
			'posts_per_page' => -1,
			'meta_key' => '_wp_page_template',
			'meta_value' => 'page-templates/widgets.php',
		) );
		foreach( $posts as $post ) {
			$post_title = get_the_title( $post->ID );
			$post_type = get_post_type( $post->ID );
			register_sidebar( array(
				'id'          => sprintf( 'widgets_template_%1$d', $post->ID ),
				'name'        => sprintf( __( 'Widgets Template: "%1$s" (%2$s #%3$d)', 'wp-theme'), $post_title, $post_type, $post->ID	),
				'description' => sprintf( __( 'This widget area appears on "%1$s" (%2$s #%3$d) because it is using the "Widgets" page template.', 'wp-theme'), $post_title, $post_type, $post->ID ),
				'before_widget' => '<div>',
				'after_widget' => '<div>',
			) );
		}
	}

	public function enqueue_comment_reply_script() {
		// Enqueue comment reply script when on singular pages
		// of post types that support comments and threaded 
		// comments are enabled.
		if ( is_singular() 
		&& post_type_supports( get_post_type(), 'comments' )
		&& get_option( 'thread_comments' ) 
		&& ! wp_script_is( 'comment-reply', 'enqueued' ) )  {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	public function print_admin_notices() {
		// Print a notice for each plugin dependency.
		if ( is_array( $this->plugin_dependencies ) && ! empty( $this->plugin_dependencies ) ) {
			$active_plugins = get_option('active_plugins');
			foreach ( $this->plugin_dependencies as $plugin_dependency ) {
				if ( ! in_array( $plugin_dependency['key'], $active_plugins ) ) {
					get_template_part( 'partials/admin-notice', '', array(
						'type' => 'error',
						'message' => sprintf( 
							/* translators: 1: A linked plugin name. */
							__( '[WP Theme Plugin Dependency] The %1$s plugin must be installed and activated for this theme to function properly.', 'wp-backstage' ), 
							sprintf( 
								'<a href="%1$s">%2$s</a>', 
								esc_url( $plugin_dependency['url'] ), 
								esc_html( $plugin_dependency['name'] ) 
							)
						),
					) );
				}
			}
		}
	}

	public function add_head_tracking_codes() {
		// unescaped
		echo get_option( 'wp_theme_tracking_codes_head' );
	}

	public function add_body_top_tracking_codes() {
		// unescaped
		echo get_option( 'wp_theme_tracking_codes_body_top' );
	}

	public function add_body_bottom_tracking_codes() {
		// unescaped
		echo get_option( 'wp_theme_tracking_codes_body_bottom' );
	}
}
