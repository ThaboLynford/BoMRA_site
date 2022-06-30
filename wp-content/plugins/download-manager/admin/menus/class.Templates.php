<?php

namespace WPDM\admin\menus;


class Templates
{

    function __construct()
    {
        add_filter('template_include', array($this, 'livePreview'), 99 );
        //add_action('admin_init', array($this, 'Save'));
        add_action('wp_ajax_template_preview', array($this, 'preview'));
        add_action('wp_ajax_wpdm_delete_template', array($this, 'deleteTemplate'));
        add_action('wp_ajax_wpdm_save_template', array($this, 'save'));
        add_action('wp_ajax_wpdm_save_email_template', array($this, 'saveEmailTemplate'));
        add_action('wp_ajax_update_template_status', array($this, 'updateTemplateStatus'));
        add_action('wp_ajax_wpdm_save_email_setting', array($this, 'saveEmailSetting'));
        add_action('admin_menu', array($this, 'Menu'));
    }

    function livePreview($page_template){

        if(wp_verify_nonce(wpdm_query_var('_tplnonce'), NONCE_KEY) && current_user_can(WPDM_ADMIN_CAP) && wpdm_query_var('template_preview') !== '') {
            add_filter( 'show_admin_bar', '__return_false' );
            remove_filter( 'the_content', 'wpautop' );

            $page_template = wpdm_tpl_path('clean-template.php');
            $type = wpdm_query_var('_type');
            global $post;
            $package = get_posts(array('post_type' => 'wpdmpro', 'posts_per_page' => 1, 'post_status' => 'publish'));
            $package = (array)$package[0];
            $template = $_REQUEST['template_preview'];
            $template = stripslashes_deep(str_replace(array("\r", "\n"), "", html_entity_decode(urldecode($template))));
            $output = wpdm_fetch_template($template, $package, $type);
            $post->post_content = "<div class='w3eden' style='max-width: 900px;margin: 0 auto !important;'>{$output}</div><style>body,html{ overflow-x: hidden;  } .w3eden { padding: 10px !important; }</style><script> jQuery(function($) {  var body = document.body, html = document.documentElement; var height = Math.max( body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight ); window.parent.wpdmifh(height); });</script>";
            include $page_template;
            die();
        }
        return $page_template;
    }

    function Menu()
    {
        add_submenu_page('edit.php?post_type=wpdmpro', __( "Templates &lsaquo; Download Manager" , "download-manager" ), __( "Templates" , "download-manager" ), WPDM_MENU_ACCESS_CAP, 'templates', array($this, 'UI'));
    }

    function UI(){
        $ttype = isset($_GET['_type']) ? wpdm_query_var('_type') : 'link';

        if (isset($_GET['task']) && ($_GET['task'] == 'EditTemplate' || $_GET['task'] == 'NewTemplate'))
            \WPDM\admin\menus\Templates::Editor();
        else if (isset($_GET['task']) && $_GET['task'] == 'EditEmailTemplate')
            \WPDM\admin\menus\Templates::EmailEditor();
        else
            \WPDM\admin\menus\Templates::Show();
    }


    public static function Editor(){
        include(WPDM_BASE_DIR . "admin/tpls/template-editor.php");
    }


    public static function EmailEditor(){
        include(WPDM_BASE_DIR . "admin/tpls/email-template-editor.php");
    }


    public static function Show(){
        include(WPDM_BASE_DIR . "admin/tpls/templates.php");
    }

    /**
     * @usage Delete link/page template
     * @since 4.7.0
     */

    function deleteTemplate(){
        if (current_user_can(WPDM_ADMIN_CAP)) {
            $ttype = wpdm_query_var('ttype');
            $tplid = wpdm_query_var('tplid');
            $tpldata = maybe_unserialize(get_option("_fm_{$ttype}_templates"));
            if (!is_array($tpldata)) $tpldata = array();
            unset($tpldata[$tplid]);
            update_option("_fm_{$ttype}_templates", @serialize($tpldata));
            die('ok');
        }

    }


    /**
     * @usage Save Link/Page Templates
     */
    function save()
    {
        //if (!isset($_GET['page']) || $_GET['page'] != 'templates') return;

        $ttype = isset($_REQUEST['_type']) ? wpdm_query_var('_type') : 'link';

        if (isset($_REQUEST['task']) && $_REQUEST['task'] == 'DeleteTemplate') {
            $tpldata = maybe_unserialize(get_option("_fm_{$ttype}_templates"));
            if (!is_array($tpldata)) $tpldata = array();
            unset($tpldata[wpdm_query_var('tplid')]);
            update_option("_fm_{$ttype}_templates", @serialize($tpldata));
            wp_send_json(array('success' => true));
            //header("location: edit.php?post_type=wpdmpro&page=templates&_type=$ttype");
            //die();
        }

        if (isset($_POST['tpl'])) {
            if (is_array(get_option("_fm_{$ttype}_templates")))
                $tpldata = (get_option("_fm_{$ttype}_templates"));
            else
                $tpldata = maybe_unserialize(get_option("_fm_{$ttype}_templates"));
            if (!is_array($tpldata)) $tpldata = array();
            $tplid = wpdm_query_var('tplid');
            $tpldata[$tplid] = $_POST['tpl'];
            update_option("_fm_{$ttype}_templates", @serialize($tpldata));
            wp_send_json(array('success' => true, 'message' => 'Template Saved!'));
            //header("location: edit.php?post_type=wpdmpro&&page=templates&_type=$ttype");
            die();
        }


    }

    function saveEmailTemplate(){
        if (isset($_POST['email_template'])) {
            $email_template = wpdm_query_var('email_template', array('validate' => array('subject' => '', 'message' => 'escs', 'from_name' => '', 'from_email' => '')));
            update_option("__wpdm_etpl_".wpdm_query_var('id'), $email_template);
            wp_send_json(array('success' => true, 'message' => 'Email Template Saved!'));
            //header("location: edit.php?post_type=wpdmpro&page=templates&_type=$ttype");
            die();
        }
    }

    /**
     * @usage Preview link/page template
     */
    function preview()
    {
        error_reporting(0);

        $wposts = array();

        if(!current_user_can(WPDM_ADMIN_CAP)) die(__( "Unauthorized Access!", "download-manager" ));

        $template = isset($_REQUEST['template'])?wpdm_query_var('template', 'escs'):'';
        $type = wpdm_query_var("_type");
        $css = wpdm_query_var("css","txt");


        $args=array(
            'post_type' => 'wpdmpro',
            'posts_per_page' => 1
        );

        $wposts = get_posts( $args  );
        $template = stripslashes($template);
        $template = urlencode($template);
        $tplnonce = wp_create_nonce(NONCE_KEY);
        $preview_link = home_url("/?template_preview={$template}&_type={$type}&_tplnonce={$tplnonce}");

        if(count($wposts)==0) $html = "<div class='w3eden'><div class='col-md-12'><div class='alert alert-info'>".__( "No package found! Please create at least 1 package to see template preview" , "download-manager" )."</div> </div></div>";
        else
            $html = "<a class='btn btn-link btn-block' href='{$preview_link}' target='_blank'><i class='fa fa-external-link-alt'></i> Preview in a new window</a><iframe id='templateiframe' src='{$preview_link}' style='border: 0;width: 100%;height: 200px;overflow: hidden'></iframe><script>function wpdmifh( h ){ jQuery('#templateiframe').height(h); }</script>";

        echo $html;
        die();

    }

    public static function Dropdown($params, $activeOnly = false)
    {
        extract($params);
        $type = isset($type) && in_array($type, array('link', 'page', 'email')) ? esc_attr($type) : 'link';
        $tplstatus = maybe_unserialize(get_option("_fm_{$type}_template_status"));

        $xactivetpls = array();
        $activetpls = array();
        if(is_array($tplstatus)) {
            foreach ($tplstatus as $tpl => $active) {
                if (!$active)
                    $xactivetpls[] = $tpl;
                else
                    $activetpls[] = $tpl;
            }
        }

        $ttpldir = get_stylesheet_directory() . '/download-manager/' . $type . '-templates/';
        $ttpls = array();
        if(file_exists($ttpldir)) {
            $ttpls = scandir($ttpldir);
            array_shift($ttpls);
            array_shift($ttpls);
        }

        $ltpldir = WPDM_TPL_DIR . $type . '-templates/';
        $ctpls = scandir($ltpldir);
        array_shift($ctpls);
        array_shift($ctpls);

        foreach($ctpls as $ind => $tpl){
                $ctpls[$ind] = $ltpldir.$tpl;
        }

        foreach($ttpls as $tpl){
            if(!in_array($ltpldir.$tpl, $ctpls)) {
                    $ctpls[] = $ttpldir . $tpl;
            }
        }

        $custom_templates = maybe_unserialize(get_option("_fm_{$type}_templates",true));

        $name = isset($name)?$name:$type.'_template';
        $css = isset($css)?"style='$css'":'';
        $id = isset($id)?$id:uniqid();
        $default = $type == 'link'?'link-template-default.php':'page-template-default.php';
        $xdf = str_replace(".php", "", $default);
        $html = "";
        if(is_array($xactivetpls) && count($xactivetpls) > 0)
            $default = in_array($xdf, $xactivetpls) || in_array($default, $xactivetpls)?$activetpls[0]:$default;

        $html .= "<select name='$name' id='$id' class='form-control template {$type}_template' {$css}><option value='$default'>Select ".ucfirst($type)." Template</option>";
        $data = array();
        foreach ($ctpls as $ctpl) {
            $ind = str_replace(".php", "", basename($ctpl));
            if(!$activeOnly || ($activeOnly && (!isset($tplstatus[$ind]) || $tplstatus[$ind] == 1))) {
                $tmpdata = file_get_contents($ctpl);
                $regx = "/WPDM.*Template[\s]*:([^\-\->]+)/";
                if (preg_match($regx, $tmpdata, $matches)) {
                    $data[basename($ctpl)] = $matches[1];
                    $eselected = isset($selected) && $selected == basename($ctpl) ? 'selected=selected' : '';

                    $html .= "<option value='" . basename($ctpl) . "' {$eselected}>{$matches[1]}</option>";
                }
            }
        }

        if(is_array($custom_templates)) {
            foreach ($custom_templates as $id => $template) {
                if(!$activeOnly || ($activeOnly && (!isset($tplstatus[$id]) || $tplstatus[$id] == 1))) {
                    $data[$id] = $template['title'];
                    $eselected = isset($selected) && $selected == $id ? 'selected=selected' : '';
                    $html .= "<option value='{$id}' {$eselected}>{$template['title']}</option>";
                }
            }
        }
        $html .= "</select>";

        return isset($data_type) && $data_type == 'ARRAY'? $data : $html;
    }

    function saveEmailSetting(){
        update_option('__wpdm_email_template', wpdm_query_var('__wpdm_email_template'));
        $email_settings = wpdm_query_var('__wpdm_email_setting', array('validate' => array('logo' => 'url', 'banner' => 'url', 'youtube' => 'url', 'twitter' => 'url', 'facebook' => 'url', 'footer_text' => 'txts')));
        update_option('__wpdm_email_setting', $email_settings);
        die("Done!");
    }

    function updateTemplateStatus(){
        if(!current_user_can(WPDM_ADMIN_CAP)) die('error');
        $type = wpdm_query_var('type');
        $tpldata = maybe_unserialize(get_option("_fm_{$type}_template_status"));
        $tpldata[wpdm_query_var('template')] = wpdm_query_var('status');
        update_option("_fm_{$type}_template_status", @serialize($tpldata));
        echo "OK";
        die();
    }
}