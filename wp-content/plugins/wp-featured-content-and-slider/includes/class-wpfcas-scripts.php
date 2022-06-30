<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package WP Featured Content and Slider
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WpFcas_Script {

	function __construct() {

		// Action to add style in backend
		add_action( 'admin_enqueue_scripts', array( $this, 'wpfcas_admin_script_style' ) );

		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array( $this, 'wpfcas_front_script_style' ) );
	}

	/**
	 * Function to register admin scripts and styles
	 * 
	 * @package WP Featured Content and Slider
	 * @since 1.4
	 */
	function wpfcas_register_admin_assets() {

		/* Styles */
		// Registring admin css
		wp_register_style( 'wpfcas-admin-style', WPFCAS_URL.'assets/css/wpfcas-admin.css', array(), WPFCAS_VERSION );

		/* Scripts */
		wp_register_script( 'wpfcas-admin-script', WPFCAS_URL.'assets/js/wpfcas-admin.js', array('jquery'), WPFCAS_VERSION, true );
	}

	/**
	 * Enqueue admin style && script
	 * 
	 * @package WP Featured Content and Slider
	 * @since 1.4
	 */
	function wpfcas_admin_script_style( $hook ) {

		global $typenow;

		$this->wpfcas_register_admin_assets();

		// Taking pages array
		$pages_arr = array( WPFCAS_POST_TYPE );

		if( in_array($typenow, $pages_arr) ) {
			wp_enqueue_style( 'wpfcas-admin-style' );
		}

		/* Admin Script Load*/
		if( $hook == WPFCAS_POST_TYPE . '_page_wpfcasm-designs' ) {
			wp_enqueue_script( 'wpfcas-admin-script' );
		}
	}

	/**
	 * Function to add style at front side
	 * 
	 * @package WP Featured Content and Slider
	 * @since 1.4
	 */
	function wpfcas_front_script_style() {

		global $post;

		/* Style */
		// Font awesome CSS
		wp_register_style( 'wpfcas-font-awesome', WPFCAS_URL. 'assets/css/font-awesome.min.css', array(), WPFCAS_VERSION);

		// style CSS
		wp_register_style( 'wpfcas_style', WPFCAS_URL. 'assets/css/featured-content-style.css', array(), WPFCAS_VERSION);

		// Slick slider
		wp_register_style( 'wpfcas_slick_style', WPFCAS_URL. 'assets/css/slick.css', array(), WPFCAS_VERSION );
		
		// Enqueue Style
		wp_enqueue_style( 'wpfcas-font-awesome' );
		wp_enqueue_style( 'wpfcas_style' );
		wp_enqueue_style( 'wpfcas_slick_style' );

		/* Script */
		// Registring slick slider script
		if( !wp_script_is( 'wpos-slick-jquery', 'registered' ) ) {
			wp_register_script( 'wpos-slick-jquery', WPFCAS_URL.'assets/js/slick.min.js', array('jquery'), WPFCAS_VERSION, true );		
		}

		// Register Elementor script
		wp_register_script( 'wpfcas-elementor-js', WPFCAS_URL.'assets/js/elementor/wpfcas-elementor.js', array('jquery'), WPFCAS_VERSION, true );

		// Register Public JS
		wp_register_script( 'wpfcas-public-js', WPFCAS_URL . 'assets/js/wpfcas-public.js', array('jquery'), WPFCAS_VERSION, true );
		wp_localize_script( 'wpfcas-public-js', 'WpFcas', array(
															'is_avada' 			=> (class_exists( 'FusionBuilder' ))	? 1 : 0,
														));

		// Enqueue Script for Elementor Preview
		if ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) {


			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'wpfcas-public-js' );
			wp_enqueue_script( 'wpfcas-elementor-js' );
		}

		// Enqueue Style & Script for Beaver Builder
		if( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {

			$this->wpfcas_register_admin_assets();

			wp_enqueue_script( 'wpfcas-admin-script' );
			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'wpfcas-public-js' );
		}

		// Enqueue Admin Style & Script for Divi Page Builder
		if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_GET['et_fb'] ) && $_GET['et_fb'] == 1 ) {
			$this->wpfcas_register_admin_assets();

			wp_enqueue_style( 'wpfcas-admin-style');
		}

		// Enqueue Admin Style for Fusion Page Builder
		if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) ) ) {
			$this->wpfcas_register_admin_assets();

			wp_enqueue_style( 'wpfcas-admin-style');
		}

	}
}

$wpfcas_script = new WpFcas_Script();