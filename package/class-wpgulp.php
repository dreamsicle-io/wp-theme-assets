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
		add_action( 'enqueue_scripts', array( $this, 'enqueue_site_assets') );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets') );
		add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_assets') );
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

	public static function enqueue_customizer_assets() {
		wp_enqueue_script( 
			$this->theme_textdomain . '-customizer', 
			$this->assets_directory_uri . '/js/customizer.min.js', 
			array( $this->theme_textdomain ), 
			$this->theme_version, 
			true 
		);
	}

}
