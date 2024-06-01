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
	 * Build Directory Relative
	 *
	 * @since 0.0.1
	 * @var string
	 */
	public string $build_directory_rel;

	/**
	 * Build Directory Path
	 *
	 * @since 0.0.1
	 * @var string
	 */
	public string $build_directory_path;

	/**
	 * Build Directory URI
	 *
	 * @since 0.0.1
	 * @var string
	 */
	public string $build_directory_uri;

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
		$this->build_directory_rel     = '/build';
		$this->build_directory_path    = WP_THEME_TEMPLATE_DIRECTORY_PATH . $this->build_directory_rel;
		$this->build_directory_uri     = WP_THEME_TEMPLATE_DIRECTORY_URI . $this->build_directory_rel;
		$this->languages_directory_uri = WP_THEME_TEMPLATE_DIRECTORY_URI . '/languages';
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
	 * Get Asset Version
	 *
	 * @since 0.0.1
	 * @param string $file The name of the file without extensions. Example: `site.min`, `login.min`, `admin.min`, `editor.min`, `customizer-preview.min`, `customizer-controls.min`.
	 */
	public function get_asset_version( string $file ): string {
		$config = require_once $this->$build_directory_path . '/build/' . $file . '.asset.php';
		return ! empty( $config['version'] ) ? $config['version'] : $this->theme_version;
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
		$version = $this->get_asset_version( 'editor.min' );
		add_editor_style(
			array(
				$this->build_directory_rel . '/editor.' . $this->style_suffix . '.css?v=' . $version,
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
		$version = $this->get_asset_version( 'site.min' );
		wp_enqueue_style(
			$this->theme_textdomain,
			$this->build_directory_uri . '/site.' . $this->style_suffix . '.css',
			array(),
			$version
		);
		wp_enqueue_script(
			$this->theme_textdomain,
			$this->build_directory_uri . '/site.min.js',
			array(),
			$version,
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
		$version = $this->get_asset_version( 'admin.min' );
		wp_enqueue_style(
			$this->theme_textdomain . '-admin',
			$this->build_directory_uri . '/admin.' . $this->style_suffix . '.css',
			array(),
			$version
		);
		wp_enqueue_script(
			$this->theme_textdomain . '-admin',
			$this->build_directory_uri . '/admin.min.js',
			array(),
			$version,
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
		$version = $this->get_asset_version( 'login.min' );
		wp_enqueue_style(
			$this->theme_textdomain . '-login',
			$this->build_directory_uri . '/login.' . $this->style_suffix . '.css',
			array(),
			$version
		);
		wp_enqueue_script(
			$this->theme_textdomain . '-login',
			$this->build_directory_uri . '/login.min.js',
			array(),
			$version,
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
		$version = $this->get_asset_version( 'customizer-preview.min' );
		wp_enqueue_style(
			$this->theme_textdomain . '-customizer-preview',
			$this->build_directory_uri . '/customizer-preview.' . $this->style_suffix . '.css',
			array(),
			$version
		);
		wp_enqueue_script(
			$this->theme_textdomain . '-customizer-preview',
			$this->build_directory_uri . '/customizer-preview.min.js',
			array( 'jquery', 'customize-preview' ),
			$version,
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
		$version = $this->get_asset_version( 'customizer-controls.min' );
		wp_enqueue_style(
			$this->theme_textdomain . '-customizer-controls',
			$this->build_directory_uri . '/customizer-controls.' . $this->style_suffix . '.css',
			array(),
			$version
		);
		wp_enqueue_script(
			$this->theme_textdomain . '-customizer-controls',
			$this->build_directory_uri . '/customizer-controls.min.js',
			array( 'jquery', 'customize-controls' ),
			$version,
			true
		);
	}

}
