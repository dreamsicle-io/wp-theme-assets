<?php
/**
 * WP Theme Assets
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP Theme Assets
 *
 * @since 0.0.1
 */
class WP_Theme_Assets {

	/**
	 * Theme Directory URI
	 *
	 * @since 0.0.1
	 * @var string
	 */
	public string $theme_directory_uri;

	/**
	 * Theme Textdomain
	 *
	 * @since 0.0.1
	 * @var string
	 */
	public string $theme_textdomain;

	/**
	 * Theme Version
	 *
	 * @since 0.0.1
	 * @var string
	 */
	public string $theme_version;

	/**
	 * Assets Directory Relative
	 *
	 * @since 0.0.1
	 * @var string
	 */
	public string $assets_directory_rel;

	/**
	 * Assets Directory URI
	 *
	 * @since 0.0.1
	 * @var string
	 */
	public string $assets_directory_uri;

	/**
	 * Languages Directory URI
	 *
	 * @since 0.0.1
	 * @var string
	 */
	public string $languages_directory_uri;

	/**
	 * Style Suffix
	 *
	 * @since 0.0.1
	 * @var bool
	 */
	public bool $style_suffix;

	/**
	 * Construct
	 *
	 * @since 0.0.1
	 */
	public function __construct() {
		$theme                         = wp_get_theme();
		$this->theme_textdomain        = $theme->get( 'TextDomain' );
		$this->theme_version           = $theme->get( 'Version' );
		$this->assets_directory_rel    = '/build';
		$this->assets_directory_uri    = WP_THEME_TEMPLATE_DIRECTORY . $this->assets_directory_rel;
		$this->languages_directory_uri = WP_THEME_TEMPLATE_DIRECTORY . '/languages';
		$this->style_suffix            = is_rtl() ? 'min-rtl' : 'min';
	}

	/**
	 * Init
	 *
	 * @since 0.0.1
	 * @return void
	 */
	public function init(): void {
		add_action( 'after_setup_theme', array( $this, 'load_languages' ), 10 );
		add_action( 'after_setup_theme', array( $this, 'enqueue_editor_assets' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_site_assets' ), 10 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ), 10 );
		add_action( 'login_enqueue_scripts', array( $this, 'enqueue_login_assets' ), 10 );
		add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_preview_assets' ), 10 );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_customizer_controls_assets' ), 10 );
	}

	/**
	 * Load Languages
	 *
	 * @since 0.0.1
	 * @return void
	 */
	public function load_languages(): void {
		load_theme_textdomain(
			$this->theme_textdomain,
			$this->languages_directory_uri
		);
	}

	/**
	 * Enqueue Editor Assets
	 *
	 * @since 0.0.1
	 * @return void
	 */
	public function enqueue_editor_assets(): void {
		add_editor_style(
			array(
				$this->assets_directory_rel . '/css/editor.' . $this->style_suffix . '.css',
			)
		);
	}

	/**
	 * Enqueue Site Assets
	 *
	 * @since 0.0.1
	 * @return void
	 */
	public function enqueue_site_assets(): void {
		wp_enqueue_style(
			$this->theme_textdomain,
			$this->assets_directory_uri . '/css/site.' . $this->style_suffix . '.css',
			array(),
			$this->theme_version
		);
		wp_enqueue_script(
			$this->theme_textdomain,
			$this->assets_directory_uri . '/js/site.min.js',
			array(),
			$this->theme_version,
			true
		);
	}

	/**
	 * Enqueue Admin Assets
	 *
	 * @since 0.0.1
	 * @return void
	 */
	public function enqueue_admin_assets(): void {
		wp_enqueue_style(
			$this->theme_textdomain . '-admin',
			$this->assets_directory_uri . '/css/admin.' . $this->style_suffix . '.css',
			array(),
			$this->theme_version
		);
		wp_enqueue_script(
			$this->theme_textdomain . '-admin',
			$this->assets_directory_uri . '/js/admin.min.js',
			array(),
			$this->theme_version,
			true
		);
	}

	/**
	 * Enqueue Login Assets
	 *
	 * @since 0.0.1
	 * @return void
	 */
	public function enqueue_login_assets(): void {
		wp_enqueue_style(
			$this->theme_textdomain . '-login',
			$this->assets_directory_uri . '/css/login.' . $this->style_suffix . '.css',
			array(),
			$this->theme_version
		);
		wp_enqueue_script(
			$this->theme_textdomain . '-login',
			$this->assets_directory_uri . '/js/login.min.js',
			array(),
			$this->theme_version,
			true
		);
	}

	/**
	 * Enqueue Customizer Preview Assets
	 *
	 * @since 0.0.1
	 * @return void
	 */
	public function enqueue_customizer_preview_assets(): void {
		wp_enqueue_style(
			$this->theme_textdomain . '-customizer-preview',
			$this->assets_directory_uri . '/css/customizer-preview.' . $this->style_suffix . '.css',
			array(),
			$this->theme_version
		);
		wp_enqueue_script(
			$this->theme_textdomain . '-customizer-preview',
			$this->assets_directory_uri . '/js/customizer-preview.min.js',
			array( 'jquery', 'customize-preview' ),
			$this->theme_version,
			true
		);
	}

	/**
	 * Enqueue Customizer Controls Assets
	 *
	 * @since 0.0.1
	 * @return void
	 */
	public function enqueue_customizer_controls_assets(): void {
		wp_enqueue_style(
			$this->theme_textdomain . '-customizer-controls',
			$this->assets_directory_uri . '/css/customizer-controls.' . $this->style_suffix . '.css',
			array(),
			$this->theme_version
		);
		wp_enqueue_script(
			$this->theme_textdomain . '-customizer-controls',
			$this->assets_directory_uri . '/js/customizer-controls.min.js',
			array( 'jquery', 'customize-controls' ),
			$this->theme_version,
			true
		);
	}

}
