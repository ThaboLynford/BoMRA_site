<?php
/*
Plugin Name: Smart phone field for Gravity Forms
Plugin Url: https://pluginscafe.com
Version: 1.5
Description: This plugin adds countries flag with ip address on gravity form phone field
Author: KaisarAhmmed
Author URI: https://pluginscafe.com
License: GPLv2 or later
Text Domain: gravityforms
*/


if(!defined('ABSPATH')) {
	exit;
}


if (!defined('GF_SMART_PHONE_FIELD_VERSION_NUM'))
define('GF_SMART_PHONE_FIELD_VERSION_NUM', '1.5');

if ( !defined( 'GF_SMART_PHONE_FIELD_FILE' ) )
define( 'GF_SMART_PHONE_FIELD_FILE', __FILE__ );

if ( !defined( 'GF_SMART_PHONE_FIELD_PATH' ) )
define( 'GF_SMART_PHONE_FIELD_PATH', plugin_dir_path( __FILE__ ) );

if ( !defined( 'GF_SMART_PHONE_FIELD_URL' ) )
define( 'GF_SMART_PHONE_FIELD_URL', plugin_dir_url( __FILE__ ) );

if ( !defined( 'GF_SMART_PHONE_FIELD_DEBUG_MODE' ) )
define( 'GF_SMART_PHONE_FIELD_DEBUG_MODE', false );




class GF_smart_phone_field {

    function __construct() {

        if ( is_admin() ) {
            add_action( 'plugins_loaded', array( $this, 'GF_admin_init' ), 14 );
        }
        else {
            add_action( 'plugins_loaded', array( $this, 'frontend_init' ), 14 );
        }
    }



    /**
     * Init frontend
     */
    function frontend_init() {
        require_once( GF_SMART_PHONE_FIELD_PATH . 'frontend/class-frontend.php' );
    }

    /**
     * Init admin side
     */
    function GF_admin_init() {
        require_once( GF_SMART_PHONE_FIELD_PATH . 'backend/class-backend.php' );
        require_once( GF_SMART_PHONE_FIELD_PATH . 'backend/class-helper.php' );
    }  

}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_premium_version_url' );

function add_premium_version_url( $links ) {
   $links[] = '<a href="https://pluginscafe.com/smart-phone-field-pro/" target="_blank">'.esc_html__("Go For Pro", "gravityforms") .'</a>';
   return $links;
}


new GF_smart_phone_field();
