<?php

/**
 * Plugin Name: Yet Another Stars Rating (Premium)
 * Plugin URI: http://wordpress.org/plugins/yet-another-stars-rating/
 * Description: Boost the way people interact with your site with an easy WordPress stars rating system! With schema.org rich snippets YASR will improve your SEO
 * Version: 2.8.6
 * Requires at least: 5.6
 * Requires PHP: 5.4
 * Author: Dario Curvino
 * Author URI: https://dariocurvino.it/
 * Text Domain: yet-another-stars-rating
 * Domain Path: /languages
 * License: GPL2
 *
 * @fs_premium_only /yasr_pro/
 *
 */
/*

Copyright 2015 Dario Curvino (email : d.curvino@tiscali.it)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/
if ( !defined( 'ABSPATH' ) ) {
    exit( 'You\'re not allowed to see this page' );
}
// Exit if accessed directly
//Auto disable yasr free when yasr pro is activated
class yasrFsNull {
    public function is_trial() {
        return false;
    }
    public function is_free_plan() {
        return false;
    }
    public function is_plan() {
        return 'yasr_platinum';
    }
    public function is__premium_only() {
        return true;
    }
    public function can_use_premium_code() {
        return true;
    }
    public function get_upgrade_url() {
        return '';
    }
    public function get_trial_url() {
        return '';
    }
}
if ( !function_exists( 'yasr_fs' ) ) {
    // Create a helper function for easy SDK access.
    function yasr_fs()
    {
        global  $yasr_fs ;
        
        if ( !isset( $yasr_fs ) ) {
            // Include Freemius SDK.
            require_once __DIR__ . '/freemius/start.php';
            try {
                $yasr_fs = new yasrFsNull();
            } catch ( Freemius_Exception $e ) {
            }
        }
        
        return $yasr_fs;
    }
    
    // Init Freemius.
    yasr_fs();
    // Signal that SDK was initiated.
    do_action( 'yasr_fs_loaded' );
    define( 'YASR_VERSION_NUM', '2.8.6' );
    //Plugin absolute path
    //e.g. /var/www/html/plugin_development/wp-content/plugins/yet-another-stars-rating
    define( 'YASR_ABSOLUTE_PATH', __DIR__ );
    //Plugin RELATIVE PATH without slashes (just the directory's name)
    //Do not use just 'yet-another-stars-rating' here, because the directory name
    //can be different, e.g. yet-another-stars-rating-premium or
    //yasr-2.3.1 (branch name)
    define( 'YASR_RELATIVE_PATH', dirname( plugin_basename( __FILE__ ) ) );
    //admin absolute path
    define( 'YASR_ABSOLUTE_PATH_ADMIN', YASR_ABSOLUTE_PATH . '/admin' );
    //includes absolute path
    define( 'YASR_ABSOLUTE_PATH_INCLUDES', YASR_ABSOLUTE_PATH . '/includes' );
    //public absolute path
    define( 'YASR_ABSOLUTE_PATH_PUBLIC', YASR_ABSOLUTE_PATH . '/public' );
    //admin relative path
    define( 'YASR_RELATIVE_PATH_ADMIN', YASR_RELATIVE_PATH . '/admin' );
    //includes relative path
    define( 'YASR_RELATIVE_PATH_INCLUDES', YASR_RELATIVE_PATH . '/includes' );
    //public relative path
    define( 'YASR_RELATIVE_PATH_PUBLIC', YASR_RELATIVE_PATH . '/public' );
    //IMG directory absolute URL
    define( 'YASR_IMG_DIR', plugins_url() . '/' . YASR_RELATIVE_PATH_INCLUDES . '/img/' );
    //Plugin language directory: here I've to use relative path
    //because load_plugin_textdomain wants relative and not absolute path
    define( 'YASR_LANG_DIR', YASR_RELATIVE_PATH . '/languages/' );
    // Include function file both sides
    require YASR_ABSOLUTE_PATH_INCLUDES . '/yasr-includes-init.php';
    //only admin files
    
    if ( is_admin() ) {
        require YASR_ABSOLUTE_PATH_ADMIN . '/yasr-admin-init.php';
    } else {
        require YASR_ABSOLUTE_PATH_PUBLIC . '/yasr-public-init.php';
    }
    
    //role to save overall_rating and multi set
    if ( !defined( 'YASR_USER_CAPABILITY_EDIT_POST' ) ) {
        define( 'YASR_USER_CAPABILITY_EDIT_POST', 'edit_posts' );
    }
    $yasr_version_installed = get_option( 'yasr-version' );
    global  $yasr_version_installed ;
    $yasr_plugin_imported = get_option( 'yasr_plugin_imported' );
    global  $yasr_plugin_imported ;
    //Run this only on plugin activation (doesn't work on update)
    register_activation_hook( __FILE__, 'yasr_on_activation' );
    function yasr_on_activation( $network_wide )
    {
        global  $yasr_version_installed ;
        //If this is a fresh new installation
        if ( !$yasr_version_installed ) {
            new YasrOnInstall( $network_wide );
        }
    }
    
    //this is called when in multisite a new blog is added
    add_action(
        'wp_insert_site',
        'yasr_on_create_blog',
        10,
        6
    );
    //when blog is deleted
    add_filter( 'wpmu_drop_tables', 'yasr_on_delete_blog' );
    //this add a link under the plugin name, must be in the main plugin file
    add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'yasr_add_links_below_plugin_name' );
    function yasr_add_links_below_plugin_name( $links )
    {
        $settings_link = '<a href="' . admin_url( 'options-general.php?page=yasr_settings_page' ) . '">General Settings</a>';
        //array_unshit adds to the begin of array
        array_unshift( $links, $settings_link );
        return $links;
    }
    
    //this add a link under the plugin description
    add_filter(
        'plugin_row_meta',
        'yasr_plugin_row_meta',
        10,
        5
    );
    function yasr_plugin_row_meta( $links, $file )
    {
        $plugin = plugin_basename( __FILE__ );
        //Show buy yasr pro only if this is free plan
        
        if ( yasr_fs()->is_free_plan() ) {
            // create link
            if ( $file === $plugin ) {
                $links[] = '<a href="https://yetanotherstarsrating.com/?utm_source=wp-plugin&utm_medium=above_description&utm_campaign=yasr_plugin_list">' . __( 'Buy Yasr Pro', 'yet-another-stars-rating' ) . '</a>';
            }
        } else {
            // create link
            if ( $file === $plugin ) {
                $links[] = __( 'Thank you for using Yasr Pro', 'yet-another-stars-rating' );
            }
        }
        
        return $links;
    }
    
    //End function
    if ( yasr_fs()->is__premium_only() ) {
        //these if can't be merged
        if ( yasr_fs()->can_use_premium_code() ) {
            //Init Yasr Pro
            require YASR_ABSOLUTE_PATH . '/yasr_pro/yasr-pro-init.php';
        }
    }
}
