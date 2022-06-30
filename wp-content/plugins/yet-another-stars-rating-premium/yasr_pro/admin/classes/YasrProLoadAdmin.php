<?php

/*

Copyright 2014 Dario Curvino (email : d.curvino@tiscali.it)

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

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

class YasrProLoadAdmin {

    //Use these proprieties to hook
    //https://wordpress.stackexchange.com/questions/386498/remove-action-how-to-access-to-a-method-in-an-child-class
    public $yasr_fake_ratings;
    public $yasr_stylish_admin;
    public $yasr_ur_admin;

    public function init() {
        //load js or css before the ones in the free version are loaded
        add_action('yasr_add_admin_scripts_begin', array($this, 'loadBefore'));

        //load js or css after the ones in the free version are loaded
        add_action('yasr_add_admin_scripts_end',   array($this, 'loadAfter'));

        //load gutenberg dependencies
        add_action('enqueue_block_editor_assets', array($this, 'loadGutenbergScripts'));

        //Show support boxes
        add_action('yasr_right_settings_panel_box', 'yasr_pro_settings_panel_support');

        //This will load fake rating metabox
        $this->yasr_fake_ratings  = new YasrProFakeRatings();
        $this->yasr_fake_ratings->init();

        $this->yasr_stylish_admin = new YasrProStylishAdmin();
        $this->yasr_stylish_admin->init();

        $this->yasr_ur_admin = new YasrProUrAdmin();
        $this->yasr_ur_admin->init();

        //Change lock icon
        add_filter('yasr_feature_locked', static function (){
            $text = __('You\'ve unlocked this feature!', 'yasr-pro');
            return '<span class="dashicons dashicons-unlock" title="'.$text.'"></span>';
        }, 10, 1);

        //Shows form in edit category page
        add_action('plugins_loaded', static function(){
            if (current_user_can('manage_options')) {
                YasrProEditCategory::init();
            }
        }, 11);
    }

    /**
     * Load scripts before the one in the free versions are loaded
     *
     * @author Dario Curvino <@dudo>
     * @since  2.6.2
     *
     * @param $hook
     */
    public function loadBefore($hook) {
        //required to load file uploader
        if (($hook === 'toplevel_page_yasr_settings_page') &&
            (isset($_GET['tab']) && $_GET['tab'] === 'style_options')) {
            wp_enqueue_media();
        }

        //Load javascript hooks
        wp_enqueue_script(
            'yasrproadmin-hooks',
            YASR_PRO_JS_DIR . 'yasr-pro-admin-hooks.js',
            array('jquery'),
            YASR_VERSION_NUM,
            true
        ); //js
    }

    /**
     * Load Css and JS in admin area
     * @author Dario Curvino <@dudo>
     * @since 2.6.2
     *
     * @param $hook
     */
    public function loadAfter($hook) {
        global $yasr_settings_page;

        wp_enqueue_style(
            'yasrcrcssadmin',
            YASR_PRO_CSS_DIR . 'yasr-pro-admin.css',
            false, YASR_VERSION_NUM
        );

        wp_enqueue_script(
            'yasrproadmin',
            YASR_PRO_JS_DIR . 'yasr-pro-admin.js',
            array('jquery', 'tippy', 'yasradmin'),
            YASR_VERSION_NUM,
            true
        ); //js

        //add this only in yasr setting page (admin.php?page=yasr_settings_page)
        if ($hook === $yasr_settings_page) {
            wp_enqueue_script(
                'yasrprosettings',
                YASR_PRO_JS_DIR . 'yasr-pro-settings.js', array('jquery', 'tippy', 'yasradmin'),
                YASR_VERSION_NUM,
                true
            ); //js
        }
    }


    /**
     * @author Dario Curvino <@dudo>
     * @since  2.6.8
     */
    public function loadGutenbergScripts () {

        $current_screen = get_current_screen();
        if (property_exists($current_screen, 'base')) {
            if ($current_screen->base === 'post') {
                //Bundled pro file
                wp_enqueue_script(
                    'yasr_pro_gutenberg',
                    YASR_PRO_JS_DIR . 'yasr-pro-gutenberg.js',
                    array('wp-i18n'),
                    YASR_VERSION_NUM, true
                );
            }
        }
    }

}