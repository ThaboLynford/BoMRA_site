<?php
/**
 * Plugin Name: WP Featured Content and Slider
 * Plugin URI: https://www.essentialplugin.com/wordpress-plugin/wp-featured-content-and-slider/
 * Text Domain: wp-featured-content-and-slider
 * Domain Path: /languages/
 * Description: Easy to add and display what features your company, product or service offers, using our shortcode OR template code. Also work with Gutenberg shortcode block.
 * Author: WP OnlineSupport, Essential Plugin
 * Version: 1.4.4
 * Author URI: https://www.essentialplugin.com/wordpress-plugin/wp-featured-content-and-slider/
 *
 * @package WordPress
 * @author WP OnlineSupport
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! defined( 'WPFCAS_VERSION' ) ) {
	define( 'WPFCAS_VERSION', '1.4.4' ); // Version of plugin
}
if( ! defined( 'WPFCAS_DIR' ) ) {
	define( 'WPFCAS_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( ! defined( 'WPFCAS_URL' ) ) {
	define( 'WPFCAS_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( ! defined( 'WPFCAS_PLUGIN_BASENAME' ) ) {
	define( 'WPFCAS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); // Plugin base name
}
if( ! defined( 'WPFCAS_POST_TYPE' ) ) {
	define( 'WPFCAS_POST_TYPE', 'featured_post' ); // Plugin post type
}
if( ! defined( 'WPFCAS_CAT' ) ) {
	define( 'WPFCAS_CAT', 'wpfcas-category' ); // Plugin category
}
if( ! defined( 'WPFCAS_PLUGIN_LINK' ) ) {
	define('WPFCAS_PLUGIN_LINK','https://www.essentialplugin.com/wordpress-plugin/wp-featured-content-and-slider/?utm_source=WP&utm_medium=Featured-Content&utm_campaign=Features-PRO'); // Plugin link
}

if(!defined( 'WPFCAS_PLUGIN_UPGRADE' ) ) {
	define('WPFCAS_PLUGIN_UPGRADE','https://www.essentialplugin.com/wordpress-plugin/wp-featured-content-and-slider/?utm_source=WP&utm_medium=Featured-Content&utm_campaign=Upgrade-PRO'); // Plugin Check link
}

if( ! defined( 'WPFCAS_SITE_LINK' ) ) {
	define('WPFCAS_SITE_LINK','https://www.essentialplugin.com'); // Plugin link
}

/**
 * Activation Hook
 * 
 * Register plugin load text domain.
 * 
 * @package WP Featured Content and Slider
 * @since 1.0.0
 */
function wpfcas_load_textdomain() {

	global $wp_version;

	// Set filter for plugin's languages directory
	$wpfcas_pro_lang_dir = dirname( WPFCAS_PLUGIN_BASENAME ) . '/languages/';
	$wpfcas_pro_lang_dir = apply_filters( 'wp_fcasp_languages_directory', $wpfcas_pro_lang_dir );

	// Traditional WordPress plugin locale filter.
	$get_locale = get_locale();

	if ( $wp_version >= 4.7 ) {
		$get_locale = get_user_locale();
	}

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale',  $get_locale, 'wp-featured-content-and-slider' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'wp-featured-content-and-slider', $locale );

	// Setup paths to current locale file
	$mofile_global = WP_LANG_DIR . '/plugins/' . basename( WPFCAS_DIR ) . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
		load_textdomain( 'wp-featured-content-and-slider', $mofile_global );
	} else { // Load the default language files
		load_plugin_textdomain( 'wp-featured-content-and-slider', false, $wpfcas_pro_lang_dir );
	}

}
// Add action Plugin loaded
add_action('plugins_loaded', 'wpfcas_load_textdomain');

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package WP Featured Content and Slider
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wpfcas_install' );

/**
 * Deactivation Hook
 * 
 * Register plugin deactivation hook.
 * 
 * @package WP Featured Content and Slider
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'wpfcas_uninstall');

/**
 * Plugin Activation Function
 * Does the initial setup, sets the default values for the plugin options
 * 
 * @package WP Featured Content and Slider
 * @since 1.0.0
 */
function wpfcas_install() {

	// Post type function
	wpfcas_setup_post_types();

    flush_rewrite_rules();

	// Deactivate free version
	if( is_plugin_active('wp-featured-content-and-slider-pro/wp-featured-content-and-slider.php') ) {
		add_action('update_option_active_plugins', 'wpfcas_deactivate_premium_version');
	}
}

/**
 * Deactivate free plugin
 * 
 * @package WP Featured Content and Slider
 * @since 1.0.0
 */
function wpfcas_deactivate_premium_version() {
	deactivate_plugins('wp-featured-content-and-slider-pro/wp-featured-content-and-slider.php', true);
}

/**
 * Plugin Deactivation Function
 * Delete  plugin options
 * 
 * @package WP Featured Content and Slider
 * @since 1.0.0
 */
function wpfcas_uninstall() {
}

/**
 * Function to display admin notice of activated plugin.
 * 
 * @package WP Featured Content and Slider
 * @since 1.0.0
 */
function wpfcas_admin_notice() {

	global $pagenow;

	// If PRO plugin is active and free plugin exist
	$dir                = WP_PLUGIN_DIR . '/wp-featured-content-and-slider-pro/wp-featured-content-and-slider.php';
	$notice_link        = add_query_arg( array('message' => 'wpfcas-plugin-notice'), admin_url('plugins.php') );
	$notice_transient   = get_transient( 'wpfcas_install_notice' );

	if ( $notice_transient == false &&  $pagenow == 'plugins.php' && file_exists($dir) && current_user_can( 'install_plugins' ) ) {
		echo '<div class="updated notice" style="position:relative;">
				<p>
					<strong>'.sprintf( __('Thank you for activating %s', 'wp-featured-content-and-slider'), 'WP Featured Content and Slider').'</strong>.<br/>
					'.sprintf( __('It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'wp-featured-content-and-slider'), '<strong>(<em>WP Featured Content and Slider PRO</em>)</strong>' ).'
				</p>
				<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
			</div>';      
	}
}

// Action to admin notice
add_action( 'admin_notices', 'wpfcas_admin_notice');


// Admin Class files
require_once( WPFCAS_DIR . '/includes/admin/class-wpfcas-admin.php' );

// Custom post type files
require_once( WPFCAS_DIR . '/includes/wpfcas-post-types.php' );

// Functions files
require_once( WPFCAS_DIR . '/includes/wpfcas-functions.php' );

// Scripts files
require_once( WPFCAS_DIR . '/includes/class-wpfcas-scripts.php' );

// Shortcode files
require_once( WPFCAS_DIR . '/includes/shortcode/wpfcas-grid.php' );
require_once( WPFCAS_DIR . '/includes/shortcode/wpfcas-slider.php' );

// Gutenberg Block Initializer
if ( function_exists( 'register_block_type' ) ) {
	require_once( WPFCAS_DIR . '/includes/admin/supports/gutenberg-block.php' );
}

// How it work file, Load admin files
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
	require_once( WPFCAS_DIR . '/includes/admin/settings/wpfcas-how-it-work.php' );
}

/* Plugin Wpos Analytics Data Starts */
function wpos_analytics_anl35_load() {

	require_once dirname( __FILE__ ) . '/wpos-analytics/wpos-analytics.php';

	$wpos_analytics =  wpos_anylc_init_module( array(
							'id'			=> 35,
							'file'			=> plugin_basename( __FILE__ ),
							'name'			=> 'WP Featured Content and Slider',
							'slug'			=> 'wp-featured-content-and-slider',
							'type'			=> 'plugin',
							'menu'			=> 'edit.php?post_type=featured_post',
							'text_domain'	=> 'wp-featured-content-and-slider',
						));

	return $wpos_analytics;
}

// Init Analytics
wpos_analytics_anl35_load();
/* Plugin Wpos Analytics Data Ends */