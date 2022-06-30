<?php
/**
 * Class DoawnloadStats
 */
namespace WPDM\libs;

use WPDM\Session;

class DownloadStats{
    
    function __construct(){
        
    }
    
    function newStat($pid, $uid, $oid){
        global $wpdb, $current_user;
        //if(isset($_SESSION['downloaded_'.$pid])) return;
        //if(isset($_COOKIE['downloaded_'.$pid])) return;
        if(Session::get('downloaded_'.$pid)) return;
        $ip = (get_option('__wpdm_noip') == 0)?$_SERVER['REMOTE_ADDR']:"";
        $wpdb->insert("{$wpdb->prefix}ahm_download_stats",array('pid'=>(int)$pid, 'uid'=>(int)$uid,'oid'=>$oid, 'year'=> date("Y"), 'month'=> date("m"), 'day'=> date("d"), 'timestamp'=> time(),'ip'=>"$ip"));
        update_post_meta($pid, '__wpdm_download_count',intval(get_post_meta($pid, '__wpdm_download_count', true))+1);

        //Email lock stat
        if(wpdm_query_var('__wpdm_els')){
            $elsd = \WPDM\Session::get('__wpdm_email_lock_verified');
            if(is_array($elsd) && isset($elsd['email'])) {
                $cff = isset($elsd['custom_form_field']) ? $elsd['custom_form_field'] : array();
                $wpdb->insert("{$wpdb->prefix}ahm_emails", array('email' => $elsd['email'], 'pid' => $pid, 'date' => time(), 'custom_data' => serialize($cff), 'request_status' => 1));
            }

        }

        $udl = maybe_unserialize(get_post_meta($pid, "__wpdmx_user_download_count", true));
        if (is_user_logged_in()) {
            $index = $current_user->ID;
        }
        else {
            $index = str_replace(".", "_", $_SERVER['REMOTE_ADDR']);
        }
        $udl["{$index}"] = isset($udl["{$index}"]) && intval($udl["{$index}"]) > 0?(int)$udl["{$index}"]+1:1;
        update_post_meta($pid, '__wpdmx_user_download_count', $udl);
        //setcookie('downloaded_'.$pid,  $ip, 1800);
        if($ip == '') $ip = uniqid();
        Session::set('downloaded_'.$pid, $ip);
    }

    
    
}