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
		add_theme_support(
			'html5',
			array(
				'comment-list',
				'comment-form',
				'search-form',
				'gallery',
				'caption',
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
		add_theme_support( 'customize-selective-refresh-widgets' );
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
		$this->print_admin_notice(
			array(
				'type'      => 'warning',
				'is_inline' => true,
				'message'   => __( 'You are currently editing the page that controls your front page.', 'wp-theme' ),
			)
		);
	}

	/**
	 * Print Admin Notice
	 *
	 * @since 0.0.1
	 * @param array $args The notice args.
	 */
	public function print_admin_notice( array $args = array() ) {
		$args    = wp_parse_args(
			$args,
			array(
				'type'           => '', // success, error, warning.
				'is_dismissible' => false,
				'is_inline'      => false,
				'message'        => false,
			)
		);
		$classes = array(
			'notice',
		);
		if ( $args['type'] ) {
			$classes[] = sprintf( 'notice-%1$s', $args['type'] );
		}
		if ( $args['is_dismissible'] ) {
			$classes[] = 'is-dismissible';
		}
		if ( $args['is_inline'] ) {
			$classes[] = 'inline';
		} ?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<p><?php
				echo wp_kses( $args['message'], 'wp_theme_description' );
			?></p>
		</div>
	<?php }

	/**
	 * Print Admin Notices
	 *
	 * @since 0.0.1
	 */
	public function print_admin_notices() {
		if ( ! in_array( 'wp-backstage/wp-backstage.php', get_option( 'active_plugins' ) ) ) {
			$this->print_admin_notice(
				array(
					'type'           => 'error',
					'is_dismissible' => false,
					'message'        => sprintf(
						/* translators: 1: wp-backstage link, 2: permalink settings link. */
						__( 'This theme depends on the %1$s plugin. Please install and activate it. Once activated, head over to the %2$s and click "Save Changes" to flush the rewrite rules.', 'wp-theme' ),
						'<a href="https://github.com/dreamsicle-io/wp-backstage/releases" target="_blank" rel="nofollow noopener"><strong>' . __( 'WP Backstage', 'wp-theme' ) . '</strong></a>',
						'<a href="' . admin_url( '/options-permalink.php' ) . '"><strong>' . __( 'Permalink Settings', 'wp-theme' ) . '</strong></a>'
					),
				)
			);
		}
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
