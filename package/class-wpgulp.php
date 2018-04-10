<?php 

class WPGulp {

	public $theme_textdomain;

	public $theme_version;

	public $assets_directory_uri;

	function __construct() {
		$theme = wp_get_theme();
		$this->theme_textdomain = $theme->get('TextDomain');
		$this->theme_version = $theme->get('Version');
		$this->assets_directory_uri = get_template_directory_uri() . '/assets/dist';
	}

	public function init() {
		add_action( 'after_setup_theme', array( $this, 'load_textdomain' ), 10 );
		add_action( 'enqueue_scripts', array( $this, 'enqueue_site_assets' ), 10 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ), 10 );
		add_action( 'login_enqueue_scripts', array( $this, 'enqueue_login_assets' ), 10 );
		add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_preview_assets' ), 10 );
		add_action( 'customize_controls_enqueue_scripts',  array( $this, 'enqueue_customizer_controls_assets' ), 10 );
	}

	public function load_textdomain() {
		load_theme_textdomain( 
			$this->theme_textdomain, 
			$this->assets_directory_uri . '/languages' 
		);
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

}
