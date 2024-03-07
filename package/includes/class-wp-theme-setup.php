<?php
/**
 * WP Theme Setup
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP Theme Setup
 *
 * @since 0.0.1
 */
class WP_Theme_Setup {

	/**
	 * Init
	 *
	 * @since 0.0.1
	 */
	public function init() {
		add_action( 'after_setup_theme', array( $this, 'manage_theme_support' ), 10 );
		add_action( 'init', array( $this, 'manage_post_type_support' ), 9999 );
		add_action( 'after_setup_theme', array( $this, 'register_nav_menus' ), 10 );
		add_action( 'comment_form_before', array( $this, 'enqueue_comment_reply_script' ), 10 );
		add_action( 'admin_notices', array( $this, 'print_admin_notices' ), 10 );
		add_action( 'admin_head', array( $this, 'manage_front_page_editor' ), 50 );
		add_action( 'wp_head', array( $this, 'add_head_tracking_codes' ), 9999 );
		add_action( 'wp_body_open', array( $this, 'add_body_top_tracking_codes' ), 0 );
		add_action( 'wp_footer', array( $this, 'add_body_bottom_tracking_codes' ), 9999 );
		add_filter( 'excerpt_length', array( $this, 'manage_excerpt_length' ), 10 );
		add_filter( 'excerpt_more', array( $this, 'manage_excerpt_more' ), 10 );
		add_filter( 'tiny_mce_before_init', array( $this, 'manage_editor_args' ), 10, 2 );
		add_filter( 'mce_buttons', array( $this, 'manage_editor_buttons_row_1' ), 10, 2 );
		add_filter( 'mce_buttons_2', array( $this, 'manage_editor_buttons_row_2' ), 10, 2 );
		add_filter( 'get_the_archive_title', array( $this, 'manage_archive_title' ), 10, 2 );
	}

	/**
	 * Register Nav Menus
	 *
	 * @since 0.0.1
	 */
	public function register_nav_menus() {
		register_nav_menus(
			array(
				'header' => __( 'Header', 'wp-theme' ),
				'footer' => __( 'Footer', 'wp-theme' ),
				'legal'  => __( 'Legal', 'wp-theme' ),
				'error'  => __( 'Error', 'wp-theme' ),
			)
		);
	}

	/**
	 * Manage Theme Support
	 *
	 * @since 0.0.1
	 */
	public function manage_theme_support() {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support(
			'html5',
			array(
				'comment-list',
				'comment-form',
				'search-form',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 512,
				'width'       => 512,
				'flex-height' => true,
				'flex-width'  => true,
				'header-text' => array(),
			)
		);
	}

	/**
	 * Manage Post Type Support
	 *
	 * @since 0.0.1
	 */
	public function manage_post_type_support() {
		remove_post_type_support( 'page', 'trackbacks' );
		remove_post_type_support( 'page', 'comments' );
		remove_post_type_support( 'page', 'author' );
		add_post_type_support( 'page', 'excerpt' );
	}

	/**
	 * Manage Comment Reply Script
	 *
	 * @since 0.0.1
	 */
	public function enqueue_comment_reply_script() {
		if ( get_option( 'thread_comments' ) && ! wp_script_is( 'comment-reply', 'enqueued' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Manage Front Page Editor
	 *
	 * @since 0.0.1
	 */
	public function manage_front_page_editor() {
		if ( is_admin() ) {
			$screen = get_current_screen();
			if ( $screen->id === 'page' ) {
				global $post;
				$front_page_id = get_option( 'page_on_front' );
				if ( ( intval( $front_page_id ) === intval( $post->ID ) ) && empty( $post->post_content ) ) {
					remove_post_type_support( 'page', 'editor' );
					add_action( 'edit_form_after_title', array( $this, 'print_front_page_editor_admin_notice' ) );
				}
			}
		}
	}

	/**
	 * Print Front Page Editor Admin Notice
	 *
	 * @since 0.0.1
	 */
	public function print_front_page_editor_admin_notice() {
		wp_admin_notice(
			__( 'You are currently editing the page that controls your front page.', 'wp-theme' ),
			array(
				'type'           => 'warning',
				'dismissible'    => false,
				'paragraph_wrap' => true,
			)
		);
	}

	/**
	 * Print Admin Notices
	 *
	 * @since 0.0.1
	 */
	public function print_admin_notices() {
		if ( ! in_array( 'wp-backstage/wp-backstage.php', get_option( 'active_plugins' ) ) ) {
			wp_admin_notice(
				sprintf(
					/* translators: 1: wp-backstage link, 2: permalink settings link. */
					__( 'This theme depends on the %1$s plugin. Please install and activate it. Once activated, head over to the %2$s and click "Save Changes" to flush the rewrite rules.', 'wp-theme' ),
					'<a href="https://github.com/dreamsicle-io/wp-backstage/releases" target="_blank" rel="nofollow noopener"><strong>' . __( 'WP Backstage', 'wp-theme' ) . '</strong></a>',
					'<a href="' . admin_url( '/options-permalink.php' ) . '"><strong>' . __( 'Permalink Settings', 'wp-theme' ) . '</strong></a>'
				),
				array(
					'type'           => 'error',
					'dismissible'    => false,
					'paragraph_wrap' => true,
				)
			);
		}
	}

	/**
	 * Manage Excerpt Length
	 *
	 * @since 0.0.1
	 * @param int $length The length of the excerpt in words.
	 */
	public function manage_excerpt_length( int $length ): int {
		$length = 20;
		return $length;
	}

	/**
	 * Manage Excerpt More
	 *
	 * @since 0.0.1
	 * @param string $more The length of the excerpt in words.
	 */
	public function manage_excerpt_more( string $more ): string {
		$more = '...';
		return $more;
	}

	/**
	 * Manage Editor Args
	 *
	 * @since 0.0.1
	 * @param array $args An array of editor arguments.
	 */
	public function manage_editor_args( array $args = array() ): array {
		$args['paste_as_text'] = true;
		return $args;
	}

	/**
	 * Manage Editor Buttons Row 1
	 *
	 * @since 0.0.1
	 * @param array  $buttons An array of TinyMCE button IDs.
	 * @param string $editor_id The ID of the editor.
	 */
	public function manage_editor_buttons_row_1( array $buttons = array(), $editor_id = null ): array {
		$removed = array(
			'alignleft',
			'alignright',
			'wp_more',
		);
		foreach ( $buttons as $key => $value ) {
			if ( in_array( $value, $removed ) ) {
				unset( $buttons[ $key ] );
			}
		}
		return $buttons;
	}

	/**
	 * Manage Editor Buttons Row 2
	 *
	 * @since 0.0.1
	 * @param array  $buttons An array of TinyMCE button IDs.
	 * @param string $editor_id The ID of the editor.
	 */
	public function manage_editor_buttons_row_2( array $buttons, string $editor_id ): array {
		$removed = array(
			'forecolor',
		);
		foreach ( $buttons as $key => $value ) {
			if ( in_array( $value, $removed ) ) {
				unset( $buttons[ $key ] );
			}
		}
		return $buttons;
	}

	/**
	 * Add Head Tracking Codes
	 *
	 * @since 0.0.1
	 */
	public function add_head_tracking_codes() {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo get_option( 'wp_theme_tracking_codes_head' );
	}

	/**
	 * Add Body Top Tracking Codes
	 *
	 * @since 0.0.1
	 */
	public function add_body_top_tracking_codes() {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo get_option( 'wp_theme_tracking_codes_body_top' );
	}

	/**
	 * Add Body Bottom Tracking Codes
	 *
	 * @since 0.0.1
	 */
	public function add_body_bottom_tracking_codes() {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo get_option( 'wp_theme_tracking_codes_body_bottom' );
	}
}
