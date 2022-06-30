<?php
/*
Plugin Name: Download Manager
Plugin URI: https://www.wpdownloadmanager.com/purchases/
Description: Manage, Protect and Track file downloads, and sell digital products from your WordPress site. A complete digital asset management solution.
Author: W3 Eden, Inc.
Author URI: https://www.wpdownloadmanager.com/
Version: 5.0.3
Text Domain: download-manager
Domain Path: /languages
*/

namespace WPDM;

use WPDM\admin\WordPressDownloadManagerAdmin;
use WPDM\libs\Apply;
use WPDM\libs\CategoryHandler;
use WPDM\libs\MediaHandler;
use WPDM\libs\PageTemplate;

global $WPDM, $wpdm_asset;

//if(!isset($_SESSION) && !strstr($_SERVER['REQUEST_URI'], 'wpdm-media/') && (!isset($_REQUEST['action']) || $_REQUEST['action'] !== 'edit-theme-plugin-file') && !strstr($_SERVER['REQUEST_URI'], 'wpdm-download/') && !isset($_REQUEST['wpdmdl']) && !isset($_GET['health-check-troubleshoot-enable-plugin']))
//    @session_start();

define('WPDM_Version','5.0.3');

$upload_dir = wp_upload_dir();
$upload_base_url = $upload_dir['baseurl'];
$upload_dir = $upload_dir['basedir'];

if(!defined('WPDM_ADMIN_CAP'))
    define('WPDM_ADMIN_CAP','manage_options');

if(!defined('WPDM_MENU_ACCESS_CAP'))
    define('WPDM_MENU_ACCESS_CAP','manage_options');

define('WPDM_BASE_DIR',dirname(__FILE__).'/');

define('WPDM_BASE_URL',plugins_url('/download-manager/'));

if(!defined('UPLOAD_DIR'))
    define('UPLOAD_DIR',$upload_dir.'/download-manager-files/');

if(!defined('WPDM_CACHE_DIR')) {
    define('WPDM_CACHE_DIR', $upload_dir . '/wpdm-cache/');
    define('WPDM_CACHE_URL', $upload_base_url . '/wpdm-cache/');
}

if(!defined('WPDM_TPL_FALLBACK'))
    define('WPDM_TPL_FALLBACK', dirname(__FILE__) . '/tpls/');

if(!defined('WPDM_TPL_DIR')) {
    if((int)get_option('__wpdm_bsversion', '') !== 3)
        define('WPDM_TPL_DIR', dirname(__FILE__) . '/tpls/');
    else
        define('WPDM_TPL_DIR', dirname(__FILE__) . '/tpls3/');
}

if(!defined('UPLOAD_BASE'))
    define('UPLOAD_BASE',$upload_dir);


if(!defined('WPDM_FONTAWESOME_URL'))
    define('WPDM_FONTAWESOME_URL','https://use.fontawesome.com/releases/v5.11.2/css/all.css');



@ini_set('upload_tmp_dir',WPDM_CACHE_DIR);


class WordPressDownloadManager{

    public $authorDashboard;
    public $userDashboard;
    public $userProfile;
    public $apply;
    public $admin;
    public $category;
    public $asset;
    public $shortCode;

    function __construct(){

        register_activation_hook(__FILE__, array($this, 'install'));

        add_action( 'init', array($this, 'registerPostTypeTaxonomy'), 1 );
        add_action( 'init', array($this, 'registerScripts'), 1 );

        add_action( 'plugins_loaded', array($this, 'loadTextdomain') );
        add_action( 'wp_enqueue_scripts', array($this, 'enqueueScripts') );

        add_action( 'wp_head', array($this, 'wpHead'), 0 );
        add_action( 'wp_footer', array($this, 'wpFooter') );

        spl_autoload_register( array( $this, 'autoLoad' ) );


        include_once(dirname(__FILE__) . "/wpdm-functions.php");
        include_once(dirname(__FILE__) . "/libs/class.AssetManager.php");
        include_once(dirname(__FILE__) . "/libs/class.SocialConnect.php");

        include(dirname(__FILE__)."/wpdm-core.php");


        $this->authorDashboard  = new AuthorDashboard();
        $this->userDashboard    = new UserDashboard();
        $this->userProfile      = new UserProfile();
        $this->apply            = new Apply();
        $this->admin            = new WordPressDownloadManagerAdmin();
        $this->shortCode        = new ShortCodes();
        new MediaHandler();
        $this->category         = new CategoryHandler();

        new PageTemplate();



    }

    /**
     * @usage Install Plugin
     */
    function install(){
        global $wpdb;

        delete_option('wpdm_latest');

        $sqls[] = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ahm_download_stats` (
              `id` bigint(20) NOT NULL AUTO_INCREMENT,
              `pid` bigint(20) NOT NULL,
              `uid` int(11) NOT NULL,
              `oid` varchar(100) NOT NULL,
              `year` int(4) NOT NULL,
              `month` int(2) NOT NULL,
              `day` int(2) NOT NULL,
              `timestamp` int(11) NOT NULL,
              `ip` varchar(20) NOT NULL,
              PRIMARY KEY (`id`)
            )";

        $sqls[] = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ahm_emails` (
              `id` bigint(20) NOT NULL AUTO_INCREMENT,
              `email` varchar(255) NOT NULL,
              `pid` bigint(20) NOT NULL,
              `date` int(11) NOT NULL,
              `custom_data` text NOT NULL,
              `request_status` INT( 1 ) NOT NULL,
              PRIMARY KEY (`id`)
            )";

        $sqls[] = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ahm_social_conns` (
              `ID` bigint(20) NOT NULL AUTO_INCREMENT,
              `pid` bigint(20) NOT NULL,
              `email` varchar(200) NOT NULL,
              `name` varchar(200) NOT NULL,
              `user_data` text NOT NULL,
              `access_token` text NOT NULL,
              `refresh_token` text NOT NULL,
              `source` varchar(200) NOT NULL,
              `timestamp` int(11) NOT NULL,
              `processed` tinyint(1) NOT NULL DEFAULT '0',
              PRIMARY KEY (`ID`)
            )";

        $sqls[] = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ahm_assets` (
              `ID` bigint(20) NOT NULL AUTO_INCREMENT,
              `path` text NOT NULL,
              `owner` int(11) NOT NULL,
              `activities` text NOT NULL,
              `comments` text NOT NULL,
              `access` text NOT NULL,
              `metadata` text NOT NULL,
              PRIMARY KEY (`ID`)
            )";

        $sqls[] = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ahm_asset_links` (
              `ID` bigint(20) NOT NULL AUTO_INCREMENT,
              `asset_ID` bigint(20) NOT NULL,
              `asset_key` varchar(255) NOT NULL,
              `access` text NOT NULL,
              `time` int(11) NOT NULL,
              PRIMARY KEY (`ID`)
            )";

        $sqls[] = "ALTER TABLE `{$wpdb->prefix}ahm_emails` ADD `request_status` INT(1) NOT NULL";
        $sqls[] = "ALTER TABLE `{$wpdb->prefix}ahm_asset_links` ADD UNIQUE(`asset_key`);";


        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        foreach($sqls as $sql){
            $wpdb->query($sql);
            //dbDelta($sql);
        }

        $ach = get_option("__wpdm_activation_history", array());
        $ach = maybe_unserialize($ach);
        $ach[] = time();
        update_option("__wpdm_activation_history", $ach);

        $this->registerPostTypeTaxonomy();

        flush_rewrite_rules();
        self::createDir();

    }

    /**
     * @usage Load Plugin Text Domain
     */
    function loadTextdomain(){
        load_plugin_textdomain('download-manager', WP_PLUGIN_URL . "/download-manager/languages/", 'download-manager/languages/');
    }

    /**
     * @usage Register WPDM Post Type and Taxonomy
     */
    public function registerPostTypeTaxonomy()
    {
        $labels = array(
            'name'                  => __( "Downloads" , "download-manager" ),
            'singular_name'         => __( "Package" , "download-manager" ),
            'add_new'               => __( "Add New" , "download-manager" ),
            'add_new_item'          => __( "Add New Package" , "download-manager" ),
            'edit_item'             => __( "Edit Package" , "download-manager" ),
            'new_item'              => __( "New Package" , "download-manager" ),
            'all_items'             => __( "All Packages" , "download-manager" ),
            'view_item'             => __( "View Package" , "download-manager" ),
            'search_items'          => __( "Search Packages" , "download-manager" ),
            'not_found'             => __( "No Package Found" , "download-manager" ),
            'not_found_in_trash'    => __( "No Packages found in Trash" , "download-manager" ),
            'parent_item_colon'     => '',
            'menu_name'             => __( "Downloads" , "download-manager" )

        );

        $tslug = get_option('__wpdm_purl_base', 'download');
        if(!strpos("_$tslug", "%"))
            $slug = sanitize_title($tslug);
        else
            $slug = $tslug;

        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => get_option('__wpdm_publicly_queryable', 1),
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => true,
            'query_var'             => true,
            'rewrite'               => array('slug' => $slug, 'with_front' => (bool)get_option('__wpdm_purl_with_front', false)), //get_option('__wpdm_purl_base','download')
            'capability_type'       => 'post',
            'has_archive'           => (get_option('__wpdm_has_archive', false)==false?false:sanitize_title(get_option('__wpdm_archive_page_slug', 'all-downloads'))),
            'hierarchical'          => false,
            'taxonomies'            => array('post_tag'),
            'menu_icon'             => 'dashicons-download',
            'exclude_from_search'   => (bool)get_option('__wpdm_exclude_from_search', false),
            'supports'              => array('title', 'editor', 'publicize', 'excerpt', 'custom-fields', 'thumbnail', 'tags', 'comments','author')

        );


        register_post_type('wpdmpro', $args);


        $labels = array(
            'name'                  => __( "Categories" , "download-manager" ),
            'singular_name'         => __( "Category" , "download-manager" ),
            'search_items'          => __( "Search Categories" , "download-manager" ),
            'all_items'             => __( "All Categories" , "download-manager" ),
            'parent_item'           => __( "Parent Category" , "download-manager" ),
            'parent_item_colon'     => __( "Parent Category:" , "download-manager" ),
            'edit_item'             => __( "Edit Category" , "download-manager" ),
            'update_item'           => __( "Update Category" , "download-manager" ),
            'add_new_item'          => __( "Add New Category" , "download-manager" ),
            'new_item_name'         => __( "New Category Name" , "download-manager" ),
            'menu_name'             => __( "Categories" , "download-manager" ),
        );

        $args = array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
            'rewrite'               => array('slug' => sanitize_title(get_option('__wpdm_curl_base', 'download-category'))),
        );

        register_taxonomy('wpdmcategory', array('wpdmpro'), $args);


        //if(!session_id() && !strstr($_SERVER['REQUEST_URI'], 'wpdm-media/') && (!isset($_REQUEST['action']) || $_REQUEST['action'] !== 'edit-theme-plugin-file') && !strstr($_SERVER['REQUEST_URI'], 'wpdm-download/') && !isset($_REQUEST['wpdmdl']) && !isset($_GET['health-check-troubleshoot-enable-plugin']))
        //    @session_start();

    }

    /**
     * @usage Create upload dir
     */
    public static function createDir()
    {
        if (!file_exists(UPLOAD_BASE)) {
            @mkdir(UPLOAD_BASE, 0755);
            @chmod(UPLOAD_BASE, 0755);
        }
        if(!file_exists(UPLOAD_DIR)) {
            @mkdir(UPLOAD_DIR, 0755);
            @chmod(UPLOAD_DIR, 0755);
        }

        if(!file_exists(WPDM_CACHE_DIR)) {
            @mkdir(WPDM_CACHE_DIR, 0755);
            @chmod(WPDM_CACHE_DIR, 0755);
        }

        $_upload_dir = wp_upload_dir();
        $_upload_dir = $_upload_dir['basedir'];
        if(!file_exists($_upload_dir.'/wpdm-file-type-icons/')) {
            @mkdir($_upload_dir.'/wpdm-file-type-icons/', 0755);
            @chmod($_upload_dir.'/wpdm-file-type-icons/', 0755);
        }

        self::setHtaccess();

        if (isset($_GET['re']) && $_GET['re'] == 1) {
            if (file_exists(UPLOAD_DIR)) $s = 1;
            else $s = 0;
            echo "<script>
        location.href='{$_SERVER['HTTP_REFERER']}&success={$s}';
        </script>";
            die();
        }
    }


    /**
     * @usage Protect Download Dir using .htaccess rules
     */
    public static function setHtaccess()
    {
        \WPDM\libs\FileSystem::blockHTTPAccess(UPLOAD_DIR);
    }

    function registerScripts(){

        if(is_admin()) return;

        wp_register_style('wpdm-front-bootstrap3', plugins_url('/download-manager/assets/bootstrap3/css/bootstrap.min.css'));
        wp_register_style('wpdm-front-bootstrap', plugins_url('/download-manager/assets/bootstrap/css/bootstrap.min.css'));
        wp_register_style('wpdm-font-awesome', WPDM_FONTAWESOME_URL);
        wp_register_style('wpdm-front3', plugins_url() . '/download-manager/assets/css/front3.css');
        wp_register_style('wpdm-front', plugins_url() . '/download-manager/assets/css/front.css');

        wp_register_script('wpdm-front-bootstrap3', plugins_url('/download-manager/assets/bootstrap3/js/bootstrap.min.js'), array('jquery'));
        wp_register_script('wpdm-front-bootstrap', plugins_url('/download-manager/assets/bootstrap/js/bootstrap.min.js'), array('jquery'));
        wp_register_script('jquery-validate', plugins_url('/download-manager/assets/js/jquery.validate.min.js'), array('jquery'));

    }

    /**
     * @usage Enqueue all styles and scripts
     */
    function enqueueScripts()
    {
        global $post, $wpdm_asset;

        if(is_admin()) return;

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-form');

        //wp_register_style('font-awesome', WPDM_BASE_URL . 'assets/font-awesome/css/font-awesome.min.css');

        $wpdmss = maybe_unserialize(get_option('__wpdm_disable_scripts', array()));

        if (is_array($wpdmss) && !in_array('wpdm-font-awesome', $wpdmss)) {
            wp_enqueue_style('wpdm-font-awesome');
        }


        if(is_object($post) && ( wpdm_query_var('adb_page') != '' || get_option('__wpdm_author_dashboard') == $post->ID || has_shortcode($post->post_content,'wpdm_frontend') || has_shortcode($post->post_content,'wpdm_package_form') || has_shortcode($post->post_content,'wpdm_user_dashboard') || has_shortcode($post->post_content,'wpdm-file-browser') ) ){
            wp_enqueue_script('jquery-validate');
            wp_enqueue_script('jquery-ui');
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('thickbox');
            wp_enqueue_style('thickbox');
            wp_enqueue_script('media-upload');
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('jquery-ui-slider');
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-timepicker', WPDM_BASE_URL.'assets/js/jquery-ui-timepicker-addon.js',array('jquery','jquery-ui-core','jquery-ui-datepicker','jquery-ui-slider') );
            wp_enqueue_media();
            wp_enqueue_style('jqui-css', plugins_url('/download-manager/assets/jqui/theme/jquery-ui.css'));

            wp_enqueue_script('chosen', plugins_url('/download-manager/assets/js/chosen.jquery.min.js'), array('jquery'));
            wp_enqueue_style('chosen-css', plugins_url('/download-manager/assets/css/chosen.css'), 999999);
        }

        if(is_singular('wpdmpro') || get_option('__wpdm_nivo_everywhere', 0) == 1){

            wp_enqueue_script("nivo-lightbox", plugins_url('/download-manager/assets/js/nivo-lightbox.min.js'), array('jquery'));
            wp_enqueue_style("nivo-lightbox", plugins_url('/download-manager/assets/css/nivo-lightbox.css'));
            wp_enqueue_style("nivo-lightbox-theme", plugins_url('/download-manager/assets/css/themes/default/default.css'));
        }

        $bsversion = get_option('__wpdm_bsversion', '');

        if (is_array($wpdmss) && !in_array('wpdm-bootstrap-css', $wpdmss)) {
            wp_enqueue_style('wpdm-front-bootstrap' . $bsversion);
        }

        if (is_array($wpdmss) && !in_array('wpdm-front', $wpdmss)) {
            wp_enqueue_style('wpdm-front' . $bsversion, 9999999999);
        }


        if (is_array($wpdmss) && !in_array('wpdm-bootstrap-js', $wpdmss)) {
            wp_enqueue_script('wpdm-front-bootstrap' . $bsversion);
        }

        wp_register_script('frontjs', plugins_url('/download-manager/assets/js/front.js'), array('jquery'));

        $wpdm_asset = array(
            'spinner' => '<i class="fas fa-sun fa-spin"></i>'
        );
        $this->asset = $wpdm_asset;
        $wpdm_asset = apply_filters("wpdm_js_vars", $wpdm_asset);


        wp_localize_script('frontjs', 'wpdm_url', array(
            'home' => esc_url_raw(home_url('/')),
            'site' => esc_url_raw(site_url('/')),
            'ajax' => esc_url_raw(admin_url('/admin-ajax.php'))
        ));

        wp_localize_script('frontjs', 'wpdm_asset', $wpdm_asset);

        wp_enqueue_script('frontjs');


    }

    /**
     * @usage insert code in wp head
     */
    function wpHead(){
        ?>

        <script>
            var wpdm_site_url = '<?php echo site_url('/'); ?>';
            var wpdm_home_url = '<?php echo home_url('/'); ?>';
            var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
            var wpdm_ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
            var wpdm_ajax_popup = '<?php echo get_option('__wpdm_ajax_popup', 0); ?>';
        </script>


        <?php
    }

    /**
     * @usage insert code in wp footer
     */
    function wpFooter(){

            ?>
            <script>
                jQuery(function($){
                    <?php if(is_singular('wpdmpro')){ ?>
                    $.get('<?php echo home_url('/?__wpdm_view_count='.wp_create_nonce(NONCE_KEY).'&id='.get_the_ID()); ?>');
                    <?php } ?>
                    try {
                        $('a.wpdm-lightbox').nivoLightbox();
                    } catch (e) {

                    }
                });
            </script>

            <?php
    }



    /**
     * @param $name
     * @usage Class autoloader
     */
    function autoLoad($name) {
        if(!strstr("_".$name, 'WPDM')) return;
        /*
        $name = str_replace("WPDM_","", $name);
        $name = str_replace("WPDM\\","", $name);
        $relative_path = str_replace("\\", "/", $name);
        $parts = explode("\\", $name);
        $class_file = end($parts);
        $name = basename($name);
        if(strlen($name) < 40 && file_exists(WPDM_BASE_DIR."libs/class.{$name}.php")){
            require_once WPDM_BASE_DIR."libs/class.{$name}.php";
        } else if(file_exists(WPDM_BASE_DIR.str_replace($class_file, 'class.'.$class_file.'.php', $relative_path))){             
            require_once WPDM_BASE_DIR.str_replace($class_file, 'class.'.$class_file.'.php', $relative_path);
        }
        */
        $originClass = $name;
        $name = str_replace("WPDM_","", $name);
        $name = str_replace("WPDM\\","", $name);
        //$relative_path = str_replace("\\", "/", $name);
        $parts = explode("\\", $name);
        $class_file = end($parts);
        $class_file = 'class.'.$class_file.'.php';
        $parts[count($parts)-1] = $class_file;
        $relative_path = implode("/", $parts);


        $classPath = WPDM_BASE_DIR.$relative_path;
        $x_classPath = WPDM_BASE_DIR.str_replace("class.", "libs/class.", $relative_path);

        if(strlen($class_file) < 40 && file_exists($classPath)){
            require_once $classPath;
        } else if(strlen($class_file) < 40 && file_exists($x_classPath)){
            require_once $x_classPath;
        }
    }

}

$WPDM = new \WPDM\WordPressDownloadManager();

