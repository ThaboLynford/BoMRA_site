<?php
/*
Plugin Name: myStickyElements Pro
Plugin URI: https://premio.io/
Description: myStickyElements is simple yet very effective plugin. It is perfect to fill out usually unused side space on webpages with some additional messages, videos, social widgets ...
Version: 2.0.3
Author: Premio
Author URI: https://premio.io/
Domain Path: /languages
License: GPLv2 or later
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

define('MYSTICKYELEMENTS_PRO_URL', plugins_url('/', __FILE__));  // Define Plugin URL
define('MYSTICKYELEMENTS_PRO_PATH', plugin_dir_path(__FILE__));  // Define Plugin Directory Path

/*PRO Vars*/
define("PRO_MY_STICKY_ELEMENT_API_URL", "https://go.premio.io/");
define("PRO_MY_STICKY_ELEMENT_ID", "3432");
define("PRO_MY_STICKY_ELEMENT_VERSION", "2.0.3");

/* Checking for updates */
require_once("sticky-element.class.php");
$license_key = get_option("sticky_element_license_key");
new Sticky_element_Plugin_Updater(PRO_MY_STICKY_ELEMENT_API_URL, __FILE__, array(
		'version' => PRO_MY_STICKY_ELEMENT_VERSION,
		'license' => $license_key,
		'item_id' => PRO_MY_STICKY_ELEMENT_ID,
		'item_name' => "My Sticky Elements",
		'author' => 'Premio.io',
		'url' => home_url()
	)
);

/*
 * redirect my sticky element setting page after plugin activated
 */
add_action( 'activated_plugin', 'mystickyelement_activation_redirect_pro' );
function mystickyelement_activation_redirect_pro($plugin){

	if( $plugin == plugin_basename( __FILE__ ) ) {
		wp_redirect( admin_url( 'admin.php?page=my-sticky-license-key' ) ) ;
		exit;
	}
}

if ( !function_exists( 'mystickyelement_pro_activate' )) {
	function mystickyelement_pro_activate() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
        $charset_collate = $wpdb->get_charset_collate();

		$contact_lists_table = $wpdb->prefix . 'mystickyelement_contact_lists';
		if ($wpdb->get_var("show tables like '$contact_lists_table'") != $contact_lists_table) {

			$contact_lists_table_sql = "CREATE TABLE $contact_lists_table (
				ID int(11) NOT NULL AUTO_INCREMENT,
				contact_name varchar(255) NULL,
				contact_phone varchar(255) NULL,
				contact_email varchar(255) NULL,
				contact_message text NULL,
				contact_option varchar(255) NULL,
				message_date DATETIME NOT NULL default '0000-00-00 00:00:00',
				PRIMARY KEY  (ID)
			) $charset_collate;";
			dbDelta($contact_lists_table_sql);
		}

		if ( get_option('mystickyelements-contact-form') == false ) {
			$contact_form = array(
								'enable' 		=> 1,
								'name' 			=> 1,
								'name_require' 	=> '',
								'name_value' 	=> '',
								'phone' 		=> 1,
								'phone_require' => 1,
								'phone_value' 	=> '',
								'email' 		=> 1,
								'email_require' => 1,
								'email_value' 	=> '',
								'message' 		=> 1,
								'message_value' => '',
								'dropdown'		=> '',
								'dropdown_require' => '',
								'submit_button_background_color'=> '#7761DF',
								'submit_button_text_color' 		=> '#FFFFFF',
								'submit_button_text' 	=> 'Submit',
								'desktop' 	=> 1,
								'mobile' 	=> 1,
								'direction' 	=> 'LTR',
								'tab_background_color' 	=> '#7761DF',
								'tab_text_color' 		=> '#FFFFFF',
								'headine_text_color' 	=> '#7761DF',
								'text_in_tab' 			=> 'Contact Us',
								'thank_you_message' 	=> 'Your message was sent successfully',
								'send_leads' 			=> 'database',
								'email_subject_line' 	=> 'New lead from MyStickyElements',
								'sent_to_mail' 			=> '',
								'form_css' 				=> '' ,
							);

			update_option( 'mystickyelements-contact-form', $contact_form);
		}

		if ( get_option('mystickyelements-social-channels') == false ) {
			$social_channels = array(
									'enable' 			=> 1,
									'whatsapp' 			=> 1,
									'facebook_messenger'=> 1,
								);

			update_option( 'mystickyelements-social-channels', $social_channels);
		}
		if ( get_option('mystickyelements-social-channels-tabs') == false ) {
			$social_channels_tabs['whatsapp'] = array(
													'text' => "Whatsapp",
													'hover_text' => "WhatsApp",
													'bg_color' => "#26D367",
													'desktop' => 1,
													'mobile' => 1,
												);
			$social_channels_tabs['facebook_messenger'] = array(
													'text' => "Facebook",
													'hover_text' => "Facebook Messenger",
													'bg_color' => "#007FF7",
													'desktop' => 1,
													'mobile' => 1,
												);

			update_option( 'mystickyelements-social-channels-tabs', $social_channels_tabs);
		}
		if ( get_option('mystickyelements-general-settings') == false ) {
			$general_settings = array(
									'position' 			=> 'left',
									'position_mobile' 	=> 'left',
									'open_tabs_when' 	=> 'hover',
									'custom_position' 	=> '',
									'tabs_css' 			=> '',
									'minimize_tab'		=> '1',
									'on_load_when'		=> 'open',
									'minimize_tab_background_color'	=> '#000000',
									'page_settings'     => '',
								);

			update_option( 'mystickyelements-general-settings', $general_settings);
		}

		$DS = DIRECTORY_SEPARATOR;
		$dirName = ABSPATH . "wp-content{$DS}plugins{$DS}mystickyelements{$DS}";
		if(is_dir($dirName)) {
			if (is_plugin_active("mystickyelements/mystickyelements.php")) {
				deactivate_plugins("mystickyelements/mystickyelements.php");
			}
			mystickyelement_delete_directory($dirName);
		}
	}
}

register_activation_hook( __FILE__, 'mystickyelement_pro_activate' );



function mystickyelement_delete_directory($dirname) {
	if (is_dir($dirname))
		$dir_handle = opendir($dirname);
	if (!$dir_handle)
		return false;
	while($file = readdir($dir_handle)) {
		if ($file != "." && $file != "..") {
			if (!is_dir($dirname."/".$file))
				unlink($dirname."/".$file);
			else
				mystickyelement_delete_directory($dirname.'/'.$file);
		}
	}
	closedir($dir_handle);
	rmdir($dirname);
	return true;
}

if ( !function_exists('mystickyelements_social_channels')) {

	function mystickyelements_social_channels() {
		$social_channels = array(
							'facebook' => array(
											'text' => "Facebook",
											'icon_text' => "",
											'hover_text' => "Facebook",
											'background_color' => "#4267B2",
											'placeholder'	=> 'Example: https://facebook.com/coca-cola/',
											'class' => "fab fa-facebook-f",
											'tooltip'	=> 'Add the link of of your Facebook page or URL. E.g., <a href="https://facebook.com/cocacola" target="_blank">https://facebook.com/cocacola</a>',
											'icon_color' => 1
										),
							'twitter'	=> array(
											'text' => "Twitter",
											'icon_text' => "",
											'hover_text' => "Twitter",
											'background_color' => "#1C9DEB",
											'placeholder'	=> 'Example: https://twitter.com/cocacola',
											'class' => "fab fa-twitter",
											'tooltip'	=> 'Add the link of of your Twitter profile E.g., <a href="https://twitter.com/cocacola" target="_blank">https://twitter.com/cocacola</a>',
											'icon_color' => 1
										),							
							'insagram'	=> array(
											'text' => "Instagram",
											'icon_text' => "",
											'hover_text' => "Instagram",
											'background_color' => "",
											'placeholder'	=> 'Example: https://instagram.com/cocacola',
											'class' => "fab fa-instagram",
											'tooltip'	=> 'Add the link of of your Instagram profile E.g., <a href="https://instagram.com/cocacola" target="_blank">https://instagram.com/cocacola</a>',
											'icon_color' => 1
										),
							'pinterest'	=> array(
											'text' => "Pinterest",
											'icon_text' => "",
											'hover_text' => "Pinterest",
											'background_color' => "#E85F65",
											'placeholder'	=> 'Example: https://pinterest/username',
											'class' => "fab fa-pinterest-p",
											'tooltip'	=> 'Add the link of of your Pinterest profile E.g., <a href="https://pinterest.com/username" target="_blank">https://pinterest.com/username</a>',
											'icon_color' => 1
										),
							'whatsapp'	=> array(
											'text' => "WhatsApp",
											'icon_text' => "",
											'hover_text' => "WhatsApp",
											'background_color' => "#26D367",
											'placeholder'	=> 'Example: +18006927753',
											'class' => "fab fa-whatsapp",
											'tooltip'	=> 'Add your full WhatsApp number with country code. E.g., +18006927753',
											'is_pre_set_message' => 1,
											'number_validation' => 1,
											'icon_color' => 1
										),
							'youtube'	=> array(
											'text' => "YouTube",
											'icon_text' => "",
											'hover_text' => "YouTube",
											'background_color' => "#F54E4E",
											'placeholder'	=> 'Example: https://youtube.com/username',
											'class' => "fab fa-youtube",
											'tooltip'	=> 'Add your YouTube channel link. E.g., <a href="https://youtube.com/username" target="_blank">https://youtube.com/username</a>',
											'icon_color' => 1
										),
							'phone'		=> array(
											'text' => "Phone",
											'icon_text' => "",
											'hover_text' => "Phone",
											'background_color' => "#26D37C",
											'placeholder'	=> 'Example: +18006927753',
											'class' => "fa fa-phone",
											'tooltip'	=> 'Add your full phone number with country code. E.g., +18006927753',
											'number_validation' => 1,
											'icon_color' => 1
										),
							'facebook_messenger'	=> array(
											'text' => "Facebook Messenger",
											'icon_text' => "",
											'hover_text' => "Facebook Messenger",
											'background_color' => "#007FF7",
											'placeholder'	=> 'Example: Coca-Cola',
											'class' => "fab fa-facebook-messenger",
											'tooltip'	=> '<ul><li>1. Go to <a href="" target="_blank">Facebook.com</a></li><li>2. Click on your name tab</li><li>3. Copy the last part of the URL <img src="'.MYSTICKYELEMENTS_PRO_URL.'images/facebook-image.png" /></li><li>4. Add your Messenger username. If your page\'s username is "cocacola" add only the username part. E.g., cocacola</li></ul>',
											'icon_color' => 1
										),
							'email'		=> array(
											'text' => "Email",
											'icon_text' => "",
											'hover_text' => "Email",
											'background_color' => "#DC483C",
											'placeholder'	=> 'Example: john@example.com',
											'class' => "far fa-envelope",
											'tooltip'	=> 'Add your email address. E.g., support@premio.io',
											'icon_color' => 1
										),
							'address'	=> array(
											'text' => "Address",
											'icon_text' => "",
											'icon_label' => "Address",
											'icon_new_tab' => "0",
											'hover_text' => "Address",
											'background_color' => "#23D28C",
											'placeholder'	=> 'Example: 3229, Royalway, Houston, TX 77058, US',
											'class' => "fas fa-map-marker-alt",
											'tooltip'	=> 'Add your full address. E.g., 3229, Royalway, Houston, TX 77058, US',
											'icon_color' => 1
										),
							'business_hours'	=> array(
											'text' => "Open Hours",
											'icon_text' => "",
											'icon_label' => "Opening Hours",
											'icon_new_tab' => "0",
											'hover_text' => "Open Hours",
											'background_color' => "#E85F65",
											'placeholder'	=> 'Example: 9:00 - 5:00',
											'class' => "fas fa-calendar-alt",
											'tooltip'	=> 'Write your opening hours. E.g, 9:00am - 5:00pm or 9:00 - 5:00 or 9:00 - 15:30',
											'icon_color' => 1
										),
							'poptin_popups'	=> array(
											'text' => "Poptin Popups",
											'icon_text' => "",
											"icon_label"=>"Popup link",
											'hover_text' => "Poptin Popups",
											'background_color' => "#47a2b1",
											'placeholder'	=> 'Example: https://app.popt.in/APIRequest/click/96Y4a02XXa15e',
											'class' => "mystickyelement_poptin_icon",
											'tooltip'	=> 'Copy your Poptin popup link from "On-click", from Display Rules. Check the <a href="https://premio.io/help/mystickyelements/how-to-launch-a-poptin-pop-up-in-my-sticky-elements/" target="_blank">documentation</a> for more. E.g., <a href="https://app.popt.in/APIRequest/click/96Y4a02XXa15e" target="_blank">https://app.popt.in/APIRequest/click/96Y4a02XXa15e</a>',
											'icon_color' => 1
										),
							'wechat'	=> array(
											'text' => "WeChat",
											'icon_text' => "",
											'hover_text' => "WeChat",
											'background_color' => "#00AD19",
											'placeholder'	=> 'Enter weChat ID. E.g., cocacola',
											'class' => "fab fa-weixin",
											'tooltip'	=> "Enter the weChat ID of the profile you want to add. You will usually find the 'WeChat ID' written next to the avatar photo of the profile",
											'icon_color' => 1
										),
							'telegram'	=> array(
											'text' => "Telegram",
											'icon_text' => "",
											'hover_text' => "Telegram",
											'background_color' => "#2CA5E0",
											'placeholder'	=> 'Enter Telegram username of channel or personal profile',
											'class' => "fab fa-telegram-plane",
											'tooltip'	=> 'Enter the username of your Telegram profile or  channel. You can find your username by going into the Telegram profile. E.g., TelegramTips',
											'icon_color' => 1
										),
							'linkedin'	=> array(
											'text' => "Linkedin",
											'icon_text' => "",
											'hover_text' => "Linkedin",
											'background_color' => "#0077b5",
											'placeholder'	=> 'Example: https://linkedin.com/in/username',
											'class' => "fab fa-linkedin-in",
											'tooltip'	=> 'Enter the full link of your LinkedIn profile. E.g., <a href="https://linkedin.com/in/username" target="_blank">https://linkedin.com/in/username</a>',
											'icon_color' => 1
										),
							'vimeo'	=> array(
											'text' => "Vimeo",
											'icon_text' => "",
											'hover_text' => "Vimeo",
											'background_color' => "#1ab7ea",
											'placeholder'	=> 'Example: https://vimeo.com/channel-name',
											'class' => "fab fa-vimeo-v",
											'tooltip'	=> 'Add your Vimeo channel link. E.g., <a href="https://vimeo.com/channel-name" target="_blank">https://vimeo.com/channel-name</a>',
											'icon_color' => 1,
										),
							'spotify'	=> array(
											'text' => "Spotify",
											'icon_text' => "",
											'hover_text' => "Spotify",
											'background_color' => "#ff5500",
											'placeholder'	=> 'Example: https://www.spotify.com/channel-link',
											'class' => "fab fa-spotify",
											'tooltip'	=> 'Add your Spotify channel link. E.g., <a href="https://www.spotify.com/channel-link" target="_blank">https://www.spotify.com/channel-link</a>',
											'icon_color' => 1
										),
							'itunes'	=> array(
											'text' => "iTunes",
											'icon_text' => "",
											'hover_text' => "iTunes",
											'background_color' => "#495057",
											'placeholder'	=> 'Example: https://www.apple.com/us/itunes/channel-link',
											'class' => "fab fa-itunes-note",
											'tooltip'	=> 'Add your iTunes channel link. E.g., <a href="https://www.apple.com/us/itunes/channel-link" target="_blank">https://www.apple.com/us/itunes/channel-link</a>',
											'icon_color' => 1
										),
							'SoundCloud'	=> array(
											'text' => "SoundCloud",
											'icon_text' => "",
											'hover_text' => "SoundCloud",
											'background_color' => "#ff5500",
											'placeholder'	=> 'Example: https://soundcloud.com/channel-link',
											'class' => "fab fa-soundcloud",
											'tooltip'	=> 'Add your SoundCloud channel link. E.g., <a href="https://soundcloud.com/channel-link" target="_blank">https://soundcloud.com/channel-link</a>',
											'icon_color' => 1
										),
							'vk'	=> array(
											'text' => "Vkontakte",
											'icon_text' => "",
											'hover_text' => "Vkontakte",
											'background_color' => "#4a76a8",
											'placeholder'	=> 'Enter your Vk username. If "vk.com/example" is the URL, username is "example"',
											'class' => "fab fa-vk",
											'tooltip'	=> 'Username for the VK account part of the web page address, for "vk.com/example" the username is "example". Only enter the username.',
											'icon_color' => 1
										),
							'viber'	=> array(
											'text' => "Viber",
											'icon_text' => "",
											'hover_text' => "Viber",
											'background_color' => "#59267c",
											'placeholder'	=> 'Example: +1507854875',
											'class' => "fab fa-viber",
											'tooltip'	=> 'Enter your full phone number that you registered with Viber. E.g., +1507854875',
											'number_validation' => 1,
											'icon_color' => 1
										),
							'snapchat'	=> array(
											'text' => "Snapchat",
											'icon_text' => "",
											'hover_text' => "Snapchat",
											'background_color' => "#fffc00",
											'placeholder'	=> 'Example: Enter your Snapchat Username',
											'class' => "fab fa-snapchat-ghost",
											'tooltip'	=> 'Enter your Snapchat username. E.g., username',
											'icon_color' => 1
										),
							'skype'	=> array(
											'text' => "Skype",
											'icon_text' => "",
											'hover_text' => "Skype",
											'background_color' => "#00aff0",
											'placeholder'	=> 'Example: Enter your Skype Username',
											'class' => "fab fa-skype",
											'tooltip'	=> 'Enter your Skype username. E.g., username',
											'icon_color' => 1
										),
							'line'	=> array(
											'text' => "Line",
											'icon_text' => "",
											'hover_text' => "Line",
											'background_color' => "#00c300",
											'placeholder'	=> 'Example: http://line.me/ti/p/2a-s5A2B8B',
											'class' => "mystickyelement_line_icon",
											'tooltip'	=> 'Add your full profile link of Line. E.g., <a href="http://line.me/ti/p/2a-s5A2B8B" target="_blank">http://line.me/ti/p/2a-s5A2B8B</a>',
											'icon_color' => 1,
											'custom_svg_icon'	=> file_get_contents( MYSTICKYELEMENTS_PRO_PATH . '/images/line-logo.svg')
										),
							'SMS'	=> array(
											'text' => "SMS",
											'icon_text' => "",
											'hover_text' => "SMS",
											'background_color' => "#ff549c",
											'placeholder'	=> 'Example: +1507854875',
											'class' => "fas fa-sms",
											'tooltip'	=> 'Add your full phone number with country code. E.g., +18006927753',
											'number_validation' => 1,
											'icon_color' => 1
										),
							'tumblr'	=> array(
											'text' => "Tumblr",
											'icon_text' => "",
											'hover_text' => "Tumblr",
											'background_color' => "#35465d",
											'placeholder'	=> 'Example: https://www.tumblr.com/channel-link',
											'class' => "fab fa-tumblr",
											'tooltip'	=> 'Add your full profile link of Tumblr. E.g, <a href="https://www.tumblr.com/channel-link" target="_blank">https://www.tumblr.com/channel-link</a>',
											'icon_color' => 1
										),
							'qzone'		=> array(
											'text' => "Qzone",
											'icon_text' => "",
											'hover_text' => "Qzone",
											'background_color' => "#1a87da",
											'placeholder'	=> 'Example: https://qzone.qq.com/channel-link',
											'class' => "mystickyelement_qzone_icon",
											'tooltip'	=> 'Add your full profile link of Qzone. E.g, <a href="https://qzone.qq.com/channel-link" target="_blank">https://qzone.qq.com/channel-link</a>',
											'icon_color' => 1,
											'custom_svg_icon'	=> file_get_contents( MYSTICKYELEMENTS_PRO_PATH . '/images/qzone-logo.svg')
										),
							'qq'		=> array(
											'text' => "QQ",
											'icon_text' => "",
											'hover_text' => "QQ",
											'background_color' => "#212529",
											'placeholder'	=> 'Example: Enter your QQ Username',
											'class' => "fab fa-qq",
											'tooltip'	=> 'Enter your QQ username. E.g., username',
											'icon_color' => 1
										),
							'behance'	=> array(
											'text' => "Behance",
											'icon_text' => "",
											'hover_text' => "Behance",
											'background_color' => "#131418",
											'placeholder'	=> 'Example: https://www.behance.net/channel-link',
											'class' => "fab fa-behance",
											'tooltip'	=> 'Add your full profile link of Behance. E.g, <a href="https://www.behance.net/channel-link" target="_blank">https://www.behance.net/channel-link</a>',
											'icon_color' => 1
										),
							'dribbble'	=> array(
											'text' => "Dribbble",
											'icon_text' => "",
											'hover_text' => "Dribbble",
											'background_color' => "#ea4c89",
											'placeholder'	=> 'Example: https://dribbble.com/channel-link',
											'class' => "fab fa-dribbble",
											'tooltip'	=> 'Add your full profile link of Dribble. E.g, <a href="https://dribbble.com/channel-link" target="_blank">https://dribbble.com/channel-link</a>',
											'icon_color' => 1
										),
							'quora'	=> array(
											'text' => "Quora",
											'icon_text' => "",
											'hover_text' => "Quora",
											'background_color' => "#aa2200",
											'placeholder'	=> 'Example: https://www.quora.com/channel-link',
											'class' => "fab fa-quora",
											'tooltip'	=> 'Add your full profile link of Quora. E.g, <a href="https://www.quora.com/channel-link" target="_blank">https://www.quora.com/channel-link</a>',
											'icon_color' => 1
										),
							'yelp'	=> array(
											'text' => "yelp",
											'icon_text' => "",
											'hover_text' => "yelp",
											'background_color' => "#c41200",
											'placeholder'	=> 'Example: https://www.yelp.com/biz/your_business_here',
											'class' => "fab fa-yelp",
											'tooltip'	=> 'Add your Yelp business link. E.g, <a href="https://www.yelp.com/biz/your_business_here" target="_blank">https://www.yelp.com/biz/your_business_here</a>',
											'icon_color' => 1
										),
							'amazon'	=> array(
											'text' => "Amazon",
											'icon_text' => "",
											'hover_text' => "Amazon",
											'background_color' => "#3b7a57",
											'placeholder'	=> 'Example: https://www.amazon.com/your_store_or_product',
											'class' => "mystickyelement_amazon_icon",
											'tooltip'	=> 'Add your Amazon product link. E.g, <a href="https://www.amazon.com/your_store_or_product" target="_blank">https://www.amazon.com/your_store_or_product</a>',
											'icon_color' => 1
										),
							'reddit'	=> array(
											'text' => "Reddit",
											'icon_text' => "",
											'hover_text' => "Reddit",
											'background_color' => "#FF4301",
											'placeholder'	=> 'Example: https://www.reddit.com/r/your_community',
											'class' => "fab fa-reddit-alien",
											'tooltip'	=> 'Add your Reddit community or profile link. E.g, <a href="https://www.reddit.com/r/your_community" target="_blank">https://www.reddit.com/r/your_community</a>',
											'icon_color' => 1
										),
							'RSS'	=> array(
											'text' => "RSS",
											'icon_text' => "",
											'hover_text' => "RSS",
											'background_color' => "#ee802f",
											'placeholder'	=> 'Example: https://www.example.com/your_rss_feed',
											'class' => "fa fa-rss",
											'tooltip'	=> 'Add your RSS feed link. E.g, <a href="https://www.example.com/your_rss_feed" target="_blank">https://www.example.com/your_rss_feed<a/>',
											'icon_color' => 1
										),
							'flickr'	=> array(
											'text' => "Flickr",
											'icon_text' => "",
											'hover_text' => "Flickr",
											'background_color' => "#ff0084",
											'placeholder'	=> 'Example: https://www.flickr.com/photos/your_profile',
											'class' => "mystickyelement_flickr_icon",
											'tooltip'	=> 'Add your full profile link of Flickr E.g, <a href="https://www.flickr.com/photos/your_profile" target="_blank">https://www.flickr.com/photos/your_profile</a>',
											'icon_color' => 1
										),
							'ebay'	=> array(
											'text' => "eBay",
											'icon_text' => "",
											'hover_text' => "eBay",
											'background_color' => "#000000",
											'placeholder'	=> 'Example: https://www.ebay.com/str/your_store',
											'class' => "mystickyelement_ebay_icon",
											'tooltip'	=> 'Add your eBay profile/product link. E.g, <a href="https://www.ebay.com/str/your_store" target="_blank">https://www.ebay.com/str/your_store</a>',
											'icon_color' => 1
										),
							'etsy'	=> array(
											'text' => "Etsy",
											'icon_text' => "",
											'hover_text' => "Etsy",
											'background_color' => "#eb6d20",
											'placeholder'	=> 'Example: https://www.etsy.com/shop/your_shop',
											'class' => "fab fa-etsy",
											'tooltip'	=> 'Add your full shop link of Etsy. E.g, <a href="https://www.etsy.com/shop/your_shop" target="_blank">https://www.etsy.com/shop/your_shop</a>',
											'icon_color' => 1
										),
							'slack'	=> array(
											'text' => "Slack",
											'icon_text' => "",
											'hover_text' => "Slack",
											'background_color' => "#3f0e40",
											'placeholder'	=> 'Example: https://your_workspace.slack.com/',
											'class' => "mystickyelement_slack_icon",
											'tooltip'	=> 'Add your Slack workspace link. E.g, <a href="https://your_workspace.slack.com/" target="">https://your_workspace.slack.com/</a>',
											'icon_color' => 1
										),
							'trip_advisor'	=> array(
											'text' => "Trip Advisor",
											'icon_text' => "",
											'hover_text' => "Trip Advisor",
											'background_color' => "#00af87",
											'placeholder'	=> 'Example: https://www.tripadvisor.com/your_place',
											'class' => "fab fa-tripadvisor",
											'tooltip'	=> 'Add your place link of TripAdvisor E.g, <a href="https://www.tripadvisor.com/your_place" target="_blank">https://www.tripadvisor.com/your_place</a>',
											'icon_color' => 1
										),
							'medium'	=> array(
											'text' => "Medium",
											'icon_text' => "",
											'hover_text' => "Medium",
											'background_color' => "#0000cd",
											'placeholder'	=> 'Example: https://medium.com/your_publication',
											'class' => "fab fa-medium",
											'tooltip'	=> 'Add your full profile link of Medium. E.g, <a href="https://medium.com/your_publication" target="">https://medium.com/your_publication</a>',
											'icon_color' => 1
										),
							'google_play'	=> array(
											'text' => "Google Play",
											'icon_text' => "",
											'hover_text' => "Google Play",
											'background_color' => "#747474",
											'placeholder'	=> 'Example: https://play.google.com/store/apps/details?id=your_app',
											'class' => "mystickyelement_google_play_icon",
											'tooltip'	=> 'Add your Google Play link. E.g, <a href="https://play.google.com/store/apps/details?id=your_app" target="_blank">https://play.google.com/store/apps/details?id=your_app</a>',
											'icon_color' => 1
										),
							'app_store'	=> array(
											'text' => "App Store (apple)",
											'icon_text' => "",
											'hover_text' => "App Store (apple)",
											'background_color' => "#1d77f2",
											'placeholder'	=> 'Example: https://apps.apple.com/app/your_app',
											'class' => "fab fa-app-store",
											'tooltip'	=> 'Add your Apple Appstore link. E.g, <a href="https://apps.apple.com/app/your_app" target="_blank">https://apps.apple.com/app/your_app</a>',
											'icon_color' => 1
										),
							'fiverr'	=> array(
											'text' => "Fiverr",
											'icon_text' => "",
											'hover_text' => "Fiverr",
											'background_color' => "#00b22d",
											'placeholder'	=> 'Example: https://www.fiverr.com/your_profile',
											'class' => "mystickyelement_fiverr_icon",
											'tooltip'	=> 'Add your Fiverr profile link. E.g, <a href="https://www.fiverr.com/your_profile" target="_blank">https://www.fiverr.com/your_profile</a>',
											'icon_color' => 1
										),
							'shopify'	=> array(
											'text' => "Shopify",
											'icon_text' => "",
											'hover_text' => "Shopify",
											'background_color' => "#96BF47",
											'placeholder'	=> 'Example: http://your_storemyshopify.com/',
											'class' => "mystickyelement_shopify_icon",
											'tooltip'	=> 'Add your Shopify store or product link. E.g, <a href="http://your_storemyshopify.com/" target="_blank">http://your_storemyshopify.com/</a>',
											'icon_color' => 1
										),
							'printful'	=> array(
											'text' => "Printful",
											'icon_text' => "",
											'hover_text' => "Printful",
											'background_color' => "#000",
											'placeholder'	=> 'Example: https://www.printful.com/your_prudct',
											'class' => "mystickyelement_printful_icon",
											'tooltip'	=> 'Add your Printful product link. E.g, <a href="https://www.printful.com/your_prudct" target="_blank">https://www.printful.com/your_prudct</a>',
											'icon_color' => 1
										),
							'gumroad'	=> array(
											'text' => "Gumroad",
											'icon_text' => "",
											'hover_text' => "Gumroad",
											'background_color' => "#36a9ae",
											'placeholder'	=> 'Example: https://gumroad.com/your_profile',
											'class' => "mystickyelement_gumroad_icon",
											'tooltip'	=> 'Add your Gumroad product link. E.g, <a href="https://gumroad.com/your_profile" target="_blank">https://gumroad.com/your_profile</a>',
											'icon_color' => 1
										),
							'ok'		=> array(
											'text' => "OK.ru",
											'icon_text' => "",
											'hover_text' => "OK.ru",
											'background_color' => "#F6902C",
											'placeholder'	=> 'Example: https://ok.ru/your_proflie',
											'class' => "fab fa-odnoklassniki",
											'tooltip'	=> 'Add your full profile link of Ok.ru. E.g, <a href="https://ok.ru/your_proflie" target="_blank">https://ok.ru/your_proflie</a>',
											'icon_color' => 1
										),
							'custom_one'	=> array(
											'text' => "Custom Link 1",
											'custom_tooltip' => "Custom Link 1",
											'icon_text' => "",
											'hover_text' => "Custom Link 1",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your custom social link',
											'class' => "fas fa-cloud-upload-alt",
											'custom'	=> 1,
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'custom_two'	=> array(
											'text' => "Custom Link 2",
											'custom_tooltip' => "Custom Link 2",
											'icon_text' => "",
											'hover_text' => "Custom Link 2",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your custom social link',
											'class' => "fas fa-cloud-upload-alt",
											'is_locked'	=> 0,
											'custom'	=> 1,
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'custom_three'	=> array(
											'text' => "Custom Link 3",
											'custom_tooltip' => "Custom Link 3",
											'icon_text' => "",
											'hover_text' => "Custom Link 3",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your custom social link',
											'class' => "fas fa-cloud-upload-alt",
											'is_locked'	=> 0,
											'custom'	=> 1,
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'custom_four'	=> array(
											'text' => "Custom Link 4",
											'custom_tooltip' => "Custom Link 4",
											'icon_text' => "",
											'hover_text' => "Custom Link 4",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your custom social link',
											'class' => "fas fa-cloud-upload-alt",
											'is_locked'	=> 0,
											'custom'	=> 1,
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'custom_five'	=> array(
											'text' => "Custom Link 5",
											'custom_tooltip' => "Custom Link 5",
											'icon_text' => "",
											'hover_text' => "Custom Link 5",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your custom social link',
											'class' => "fas fa-cloud-upload-alt",
											'is_locked'	=> 0,
											'custom'	=> 1,
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'custom_six'	=> array(
											'text' => "Custom Link 6",
											'custom_tooltip' => "Custom Link 6",
											'icon_text' => "",
											'hover_text' => "Custom Link 6",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your custom social link',
											'class' => "fas fa-cloud-upload-alt",
											'is_locked'	=> 0,
											'custom'	=> 1,
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'custom_seven'	=> array(
											'text' => "Custom Shortcode/HTML 1",
											'custom_tooltip' => "Custom Shortcode/HTML 1",
											'icon_text' => "",
											'hover_text' => "Custom Shortcode/HTML 1",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your shortcode or custom IFRAME/HTML code',
											'class' => "fas fa-code",
											'custom'	=> 1,
											'custom_html'	=> 1,
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'custom_eight'	=> array(
											'text' => "Custom Shortcode/HTML 2",
											'custom_tooltip' => "Custom Shortcode/HTML 2",
											'icon_text' => "",
											'hover_text' => "Custom Shortcode/HTML 2",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your shortcode or custom IFRAME/HTML code',
											'class' => "fas fa-code",
											'is_locked'	=> 0,
											'custom'	=> 1,
											'custom_html'	=> 1,
											'icon_color' => 1,
											'tooltip'	=> ''
										),
							'custom_nine'	=> array(
											'text' => "Custom Shortcode/HTML 3",
											'custom_tooltip' => "Custom Shortcode/HTML 3",
											'icon_text' => "",
											'hover_text' => "Custom Shortcode/HTML 3",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your shortcode or custom IFRAME/HTML code',
											'class' => "fas fa-code",
											'is_locked'	=> 0,
											'custom'	=> 1,
											'custom_html'	=> 1,
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'custom_ten'	=> array(
											'text' => "Custom Shortcode/HTML 4",
											'custom_tooltip' => "Custom Shortcode/HTML 4",
											'icon_text' => "",
											'hover_text' => "Custom Shortcode/HTML 4",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your shortcode or custom IFRAME/HTML code',
											'class' => "fas fa-code",
											'is_locked'	=> 0,
											'custom'	=> 1,
											'custom_html'	=> 1,
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'custom_eleven'	=> array(
											'text' => "Custom Shortcode/HTML 5",
											'custom_tooltip' => "Custom Shortcode/HTML 5",
											'icon_text' => "",
											'hover_text' => "Custom Shortcode/HTML 5",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your shortcode or custom IFRAME/HTML code',
											'class' => "fas fa-code",
											'is_locked'	=> 0,
											'custom'	=> 1,
											'custom_html'	=> 1,
											'tooltip'	=> '',
											'icon_color' => 1
										),
							'custom_twelve'	=> array(
											'text' => "Custom Shortcode/HTML 6",
											'custom_tooltip' => "Custom Shortcode/HTML 6",
											'icon_text' => "",
											'hover_text' => "Custom Shortcode/HTML 6",
											'background_color' => "#7761DF",
											'placeholder'	=> 'Enter your shortcode or custom IFRAME/HTML code',
											'class' => "fas fa-code",
											'is_locked'	=> 0,
											'custom'	=> 1,
											'custom_html'	=> 1,
											'tooltip'	=> '',
											'icon_color' => 1
										),
						);

		return apply_filters( 'mystickyelements_social_channels_info',  $social_channels);
	}
}
add_action( 'admin_init' , 'mystickyelements_pro_admin_init' );
function mystickyelements_pro_admin_init() {
	global $wpdb, $pagenow;
	
	if ( $pagenow == 'plugins.php'  || ( isset($_GET['page']) && $_GET['page'] == 'my-sticky-elements' ) ) {
		/* add Contact Option field */
		$field_check = $wpdb->get_var( "SHOW COLUMNS FROM {$wpdb->prefix}mystickyelement_contact_lists LIKE 'contact_option'" );
		if ( 'contact_option' != $field_check ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}mystickyelement_contact_lists ADD contact_option VARCHAR(255) NULL DEFAULT NULL" );
		}
		
		/* add Contact Message date field */
		$field_check = $wpdb->get_var( "SHOW COLUMNS FROM {$wpdb->prefix}mystickyelement_contact_lists LIKE 'message_date'" );	
		if ( 'message_date' != $field_check ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}mystickyelement_contact_lists ADD message_date DATETIME NOT NULL default '0000-00-00 00:00:00'" );
		}
		
		/* add Contact Widget Name field */
		$field_check = $wpdb->get_var( "SHOW COLUMNS FROM {$wpdb->prefix}mystickyelement_contact_lists LIKE 'widget_element_name'" );	
		if ( 'widget_element_name' != $field_check ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}mystickyelement_contact_lists ADD widget_element_name VARCHAR(255) NULL DEFAULT 'default'" );
		}
		
		/* add Contact Custom Fields field */
		$field_check = $wpdb->get_var( "SHOW COLUMNS FROM {$wpdb->prefix}mystickyelement_contact_lists LIKE 'custom_fields'" );	
		if ( 'custom_fields' != $field_check ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}mystickyelement_contact_lists ADD custom_fields longtext" );
		}
		
		/* add Page Link field */
		$field_check = $wpdb->get_var( "SHOW COLUMNS FROM {$wpdb->prefix}mystickyelement_contact_lists LIKE 'page_link'" );
		if ( 'page_link' != $field_check ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}mystickyelement_contact_lists ADD page_link TEXT NULL DEFAULT NULL" );
		}
		
		/* add consent checkbox */
		$field_check = $wpdb->get_var( "SHOW COLUMNS FROM {$wpdb->prefix}mystickyelement_contact_lists LIKE 'consent_checkbox'" );
		if ( 'consent_checkbox' != $field_check ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}mystickyelement_contact_lists ADD consent_checkbox BOOLEAN NULL DEFAULT false" );
		}
		/* add IP Address*/
		$field_check = $wpdb->get_var( "SHOW COLUMNS FROM {$wpdb->prefix}mystickyelement_contact_lists LIKE 'ip_address'" );
		if ( 'ip_address' != $field_check ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}mystickyelement_contact_lists ADD ip_address TEXT NULL DEFAULT NULL" );
		}
	}
}

/* Get The Default fields */
function mystickyelements_pro_widget_default_fields ( $mystickyelements_option ) {
	
	if ( $mystickyelements_option == '') {
		return array();
	}
	if ( $mystickyelements_option == 'contact_form' ) {
		return array(
						'enable' 		=> 1,
						'name' 			=> 1,
						'name_require' 	=> '',
						'name_value' 	=> '',
						'phone' 		=> 1,
						'phone_require' => 1,
						'phone_value' 	=> '',
						'email' 		=> 1,
						'email_require' => 1,
						'email_value' 	=> '',
						'message' 		=> 1,
						'message_value' => '',
						'dropdown'		=> '',
						'dropdown_require' => '',
						'submit_button_background_color'=> '#7761DF',
						'submit_button_text_color' 		=> '#FFFFFF',
						'submit_button_text' 	=> 'Submit',
						'desktop' 	=> 1,
						'mobile' 	=> 1,
						'direction' 	=> 'LTR',
						'tab_background_color' 	=> '#7761DF',
						'tab_text_color' 		=> '#FFFFFF',
						'headine_text_color' 	=> '#7761DF',
						'text_in_tab' 			=> 'Contact Us',
						'thank_you_message' 	=> 'Your message was sent successfully',
						'send_leads' 			=> 'database',
						'email_subject_line' 	=> 'New lead from MyStickyElements',
						'sent_to_mail' 			=> '',
						'form_css' 				=> '' ,
					);
	}
	
	if ( $mystickyelements_option == 'social_channels' ) {
		return array(
							'enable' 			=> 1,
							'whatsapp' 			=> 1,
							'facebook_messenger'=> 1,
						);
	}
	if ( $mystickyelements_option == 'social_channels_tabs' ) {
		$social_channels_tabs['whatsapp'] = array(
												'text' => "",
												'hover_text' => "WhatsApp",
												'bg_color' => "#26D367",
												'desktop' => 1,
												'mobile' => 1,
											);
		$social_channels_tabs['facebook_messenger'] = array(
													'text' => "",
													'hover_text' => "Facebook Messenger",
													'bg_color' => "#007FF7",
													'desktop' => 1,
													'mobile' => 1,
												);
		return $social_channels_tabs;
	}
	
	
	if ( $mystickyelements_option == 'general_settings' ) {
		return array(
							'position' 			=> 'left',
							'position_mobile' 	=> 'left',
							'open_tabs_when' 	=> 'hover',
							'custom_position' 	=> '',
							'tabs_css' 			=> '',
							'minimize_tab'		=> '1',
							'on_load_when'		=> 'open',
							'minimize_tab_background_color'	=> '#000000',
							'page_settings'     => '',
						);
	}	
			
}

require_once MYSTICKYELEMENTS_PRO_PATH . 'mystickyelements-fonts.php';
require_once MYSTICKYELEMENTS_PRO_PATH . 'mystickyelements-fontawesome-icons.php';
require_once MYSTICKYELEMENTS_PRO_PATH . 'mystickyelements-admin.php';
require_once MYSTICKYELEMENTS_PRO_PATH . 'mystickyelements-front.php';


function myStickyelements_get_mailchimp_groups( $list_id ) {
	global $wp_version;
	$mailchimp_groups = array();
	$apikey = get_option( 'elements_mc_api_key' );
	$dataCenter = substr($apikey,strpos($apikey,'-')+1);
	$url = 'https://'.$dataCenter.'.api.mailchimp.com/3.0/lists/'.$list_id . '/interest-categories';


	$headers = array(
		'Authorization' => 'Basic ' . base64_encode('user:'.$apikey),
	);
	$args = array(
		'method' 		=> 'GET',
		'timeout' 		=> 45,
		'redirection' 	=> 5,
		'httpversion' 	=> '1.0',
		'blocking' 		=> true,
		'user-agent'  	=> 'WordPress/' . $wp_version . '; ' . home_url(),
		'headers'     	=> $headers,
		'body'		  	=> array(),
		'cookies' 		=> array(),
		'sslverify'		=> true,
	);

	$response = wp_remote_get( $url, $args );
	$api_response_body = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( isset($api_response_body['categories']) && !empty($api_response_body['categories'])) {
		foreach( $api_response_body['categories'] as $categories ) {

			$url = 'https://'.$dataCenter.'.api.mailchimp.com/3.0/lists/'.$list_id . '/interest-categories/'. $categories['id'] . '/interests';

			$response = wp_remote_get( $url, $args );
			$api_response_body = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( isset($api_response_body['interests']) && !empty($api_response_body['interests']) ) {

				foreach( $api_response_body['interests'] as $interests ) {
					$mailchimp_groups[$categories['title']][] = [
											'id'	=> $interests['id'],
											'name'	=> $interests['name']
										];
				}
			}

		}

	}
	return $mailchimp_groups;
}


function myStickyelements_merge_and_format_member_tags($mailchimp_tags, $new_tags) {
	$mailchimp_tags = array_map(
		function ( $tag ) {
			return $tag->name;
		},
		$mailchimp_tags
	);

	$tags = array_unique( array_merge( $mailchimp_tags, $new_tags ), SORT_REGULAR );

	return array_map(
		function ( $tag ) {
			return array(
			'name' => $tag,
			'status' => 'active',
			);
		},
		$tags
	);
}

function mystickyelements_get_mailchimp_lists_fields( $list_id ) {
	global $wp_version;
	$mailchimp_groups = array();
	$apikey = get_option( 'elements_mc_api_key' );
	$dataCenter = substr($apikey,strpos($apikey,'-')+1);	
	$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $list_id . '/merge-fields?count=999';

	$headers = array(
		'Authorization' => 'Basic ' . base64_encode('user:'.$apikey),		
	);
	$args = array(
		'method' 		=> 'GET',
		'timeout' 		=> 45,
		'redirection' 	=> 5,
		'httpversion' 	=> '1.0',
		'blocking' 		=> true,
		'user-agent'  	=> 'WordPress/' . $wp_version . '; ' . home_url(),
		'headers'     	=> $headers,		
		'cookies' 		=> array(),
		'sslverify'		=> true,
	);
	
	$response = wp_remote_get( $url, $args );
	$api_response_body = json_decode( wp_remote_retrieve_body( $response ), true );	
	$skip_fields = ['FNAME','LNAME','MESSAGE'];
	$fields = [];
	$types = [
		'text' => 'text',
		'number' => 'number',
		'address' => 'text',
		'phone' => 'text',
		'date' => 'text',
		'url' => 'url',
		'imageurl' => 'url',
		'radio' => 'radio',
		'dropdown' => 'select',
		'birthday' => 'text',
		'zip' => 'text',
	];
	
	if ( ! empty( $api_response_body['merge_fields'] ) ) {
		foreach ( $api_response_body['merge_fields'] as $field ) {
			if( !in_array( $field['tag'], $skip_fields )) {
				$fields[] = [
					'field_label' 	=> $field['name'],
					'field_type' 	=> $types[ $field['type'] ],
					'field_id' 		=> $field['tag'],
					'field_required'=> $field['required'],
				];
			}
		}
	}
	
	return $fields;
}