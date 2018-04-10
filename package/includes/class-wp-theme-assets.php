<?php 

class WP_Theme_Assets {

	public $theme_textdomain;

	public $theme_version;

	public $assets_directory_uri;

	public $languages_directory_uri;

	function __construct() {
		$theme = wp_get_theme();
		$this->theme_textdomain = $theme->get('TextDomain');
		$this->theme_version = $theme->get('Version');
		$this->assets_directory_uri = get_template_directory_uri() . '/assets/dist';
		$this->languages_directory_uri = get_template_directory_uri() . '/languages';
	}

	public function init() {
		add_action( 'after_setup_theme', array( $this, 'load_languages' ), 10 );
		add_action( 'after_setup_theme', array( $this, 'enqueue_editor_assets' ), 10 );
		add_action( 'enqueue_scripts', array( $this, 'enqueue_site_assets' ), 10 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ), 10 );
		add_action( 'login_enqueue_scripts', array( $this, 'enqueue_login_assets' ), 10 );
		add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_preview_assets' ), 10 );
		add_action( 'customize_controls_enqueue_scripts',  array( $this, 'enqueue_customizer_controls_assets' ), 10 );
	}

	public function load_languages() {
		load_theme_textdomain( 
			$this->theme_textdomain, 
			$this->languages_directory_uri 
		);
	}

	public function enqueue_editor_assets() {
		add_editor_style( $this->assets_directory_uri . '/css/editor.min.css' );
	}

	public static function enqueue_site_assets() {
		wp_enqueue_style( 
			$this->theme_textdomain, 
			$this->assets_directory_uri . '/css/site.min.css', 
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

	public static function enqueue_admin_assets() {
		wp_enqueue_style( 
			$this->theme_textdomain . '-admin', 
			$this->assets_directory_uri . '/css/admin.min.css', 
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

	public static function enqueue_login_assets() {
		wp_enqueue_style( 
			$this->theme_textdomain . '-login', 
			$this->assets_directory_uri . '/css/login.min.css', 
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

	public static function enqueue_customizer_preview_assets() {
		wp_enqueue_style( 
			$this->theme_textdomain . '-customizer-preview', 
			$this->assets_directory_uri . '/css/customizer-preview.min.css', 
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

	public static function enqueue_customizer_controls_assets() {
		wp_enqueue_style( 
			$this->theme_textdomain . '-customizer-controls', 
			$this->assets_directory_uri . '/css/customizer-controls.min.css', 
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

	public static function test_translation() {
		return sprintf(
			/* translators: 1: __(), 2: esc_html__(), 3: _nx(). */
			_x( '%1$s %2$s %3$s', 'test string _x context message', 'wp-theme-assets' ), 
			__( 'Test String', $this->theme_textdomain ), 
			esc_html__( 'Test String (HTML escaped)', 'wp-theme-assets' ), 
			_nx( '%s Item', '%s Items', 5, 'test string _nx context message', 'wp-theme-assets' )
		);
	}

}
