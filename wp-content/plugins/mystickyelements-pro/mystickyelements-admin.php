<?php

defined('ABSPATH') or die("Cannot access pages directly.");

if ( !class_exists('MyStickyElementsPage_pro') ) {
	class MyStickyElementsPage_pro {
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'mystickyelements_load_plugin_textdomain' ) );
			add_action( 'admin_enqueue_scripts',  array( $this, 'mystickyelements_admin_enqueue_script' ), 99 );
			add_action( 'admin_menu', array( $this, 'add_mystickyelement_plugin_page' ) );
			add_action( 'wp_ajax_mystickyelement-social-tab', array( $this, 'mystickyelement_social_tab_add' ) );
			add_action( 'wp_ajax_mystickyelement-mailchimp-field', array( $this, 'mystickyelement_mailchimp_field' ) );
			add_action( 'wp_ajax_mystickyelement-mailchimp-group', array( $this, 'mystickyelement_mailchimp_group' ) );
			add_action( 'wp_ajax_mystickyelement_delete_db_record', array( $this, 'mystickyelement_delete_db_record' ) );
			add_action( 'wp_ajax_mystickyelement_widget_status', array( $this, 'mystickyelement_widget_status' ) );
			
			add_action( 'wp_ajax_mystickyelement_widget_rename', array( $this, 'mystickyelement_widget_rename' ) );
			add_action( 'wp_ajax_mystickyelement_widget_delete', array( $this, 'mystickyelement_widget_delete' ) );

			add_action( 'admin_footer', array( $this, 'mystickyelements_deactivate' ) );
			/* Send message to owner */
			add_action( 'wp_ajax_mystickyelements_plugin_deactivate', array( $this, 'mystickyelements_plugin_deactivate' ) );
			add_filter( 'plugin_action_links_mystickyelements-pro/mystickyelements.php', array( $this, 'settings_link' )  );
		}
		public function settings_link( $links ) {
			$settings_link = '<a href="'.admin_url("admin.php?page=my-sticky-elements").'">Settings</a>';
			$links['need_help'] = '<a href="https://premio.io/help/mystickyelements/?utm_source=pluginspage" target="_blank">'.__( 'Need help?', 'mystickyelements' ).'</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}
		/*
		 * Load Plugin text domain.
		 */

		public function mystickyelements_load_plugin_textdomain() {
			load_plugin_textdomain('mystickyelements', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
		}

		/*
		 * enqueue admin side script and style.
		 */
		public  function mystickyelements_admin_enqueue_script( ) {
			if ( isset($_GET['page']) && ( $_GET['page'] == 'my-sticky-elements'
					|| $_GET['page'] == 'my-sticky-elements-new-widget'
					|| $_GET['page'] == 'my-sticky-elements-leads'
					|| $_GET['page'] == 'my-sticky-elements-integration'  
					|| $_GET['page'] == 'recommended-plugins') ) {
				wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Poppins:400,500,600,700' );
				wp_enqueue_style( 'font-awesome-css', plugins_url('/css/font-awesome.min.css', __FILE__), array(), PRO_MY_STICKY_ELEMENT_VERSION ) ;
				wp_enqueue_style( 'jquery-ui-css', plugins_url('/css/jquery-ui.min.css', __FILE__), array(), PRO_MY_STICKY_ELEMENT_VERSION ) ;
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_style('mystickyelements-admin-css', plugins_url('/css/mystickyelements-admin.css', __FILE__), array(), PRO_MY_STICKY_ELEMENT_VERSION);
				wp_style_add_data( 'mystickyelements-admin-css', 'rtl', 'replace' );
				wp_enqueue_style('select2-css', plugins_url('/css/select2.min.css', __FILE__), array(), PRO_MY_STICKY_ELEMENT_VERSION);
				wp_enqueue_style('mystickyelements-front-css', plugins_url('/css/mystickyelements-front.css', __FILE__), array(), PRO_MY_STICKY_ELEMENT_VERSION);
				wp_enqueue_style( 'wp-jquery-ui-dialog' );
				wp_enqueue_script( 'jquery-ui-dialog' );
				wp_enqueue_script( 'wp-color-picker');
				wp_enqueue_script( 'jquery-ui-sortable');
				wp_enqueue_script( 'jquery-effects-shake');
				wp_enqueue_media();
				// include the javascript
				wp_enqueue_script('thickbox', null, array('jquery'));

				// include the thickbox styles
				wp_enqueue_style('thickbox.css', '/'.WPINC.'/js/thickbox/thickbox.css', null, '1.0');
				
				wp_enqueue_script('plugin-install', admin_url('/js/plugin-install.min', __FILE__), array( 'jquery' ), PRO_MY_STICKY_ELEMENT_VERSION, false ) ;
				
				wp_enqueue_script('select2-js', plugins_url('/js/select2.min.js', __FILE__), array( 'jquery' ), PRO_MY_STICKY_ELEMENT_VERSION, false ) ;
				
				wp_enqueue_script('timepicker-js', plugins_url('/js/timepicker.min.js', __FILE__), array( 'jquery' ), PRO_MY_STICKY_ELEMENT_VERSION, false ) ;
				
				wp_enqueue_script('confetti-js', plugins_url('/js/confetti.min.js', __FILE__), array( 'jquery' ), PRO_MY_STICKY_ELEMENT_VERSION, false ) ;
				wp_enqueue_script('mystickyelements-js', plugins_url('/js/mystickyelements-admin.js', __FILE__), array( 'jquery' ), PRO_MY_STICKY_ELEMENT_VERSION, false ) ;
				
				$locale_settings = array(
					'ajaxurl' => admin_url('admin-ajax.php'),
					'mystickyelement_pro_url' => MYSTICKYELEMENTS_PRO_URL,
					'ajax_nonce' => wp_create_nonce('mystickyelements'),					
				);
				wp_localize_script('mystickyelements-js', 'mystickyelements', $locale_settings);			
			}
		}

		/*
		 * Add My Sticky Element Page in admin menu.
		 */
		public function add_mystickyelement_plugin_page() {
			if ( isset($_GET['hide_mserecommended_plugin']) && $_GET['hide_mserecommended_plugin'] == 1) {
				update_option('hide_mserecommended_plugin',true);				
			}
			$hide_mserecommended_plugin = get_option('hide_mserecommended_plugin');			
			$default_widget = '';
			$default_widget_name = 'Dashboard';
			add_menu_page(
				'Settings Admin',
				'myStickyelements',
				'manage_options',
				'my-sticky-elements',
				array( $this, 'mystickyelements_admin_settings_page' ),
				'dashicons-sticky'
			);

			add_submenu_page(
				'my-sticky-elements',
				'Settings Admin',
				$default_widget_name,
				'manage_options',
				'my-sticky-elements' . $default_widget,
				array( $this, 'mystickyelements_admin_settings_page' )
			);

			/* Date: 2019-07-26 */
			/*
			if ( !empty($elements_widgets) && $elements_widgets != '' ) {
				foreach( $elements_widgets as $key=>$value ) {
					if ( $key != 0 ) {
						$widget_name = ( $value != '' ) ? "Settings " . $value : 'Settings Widget #' . $key;
						add_submenu_page(
							'my-sticky-elements',
							'Settings Admin',
							$widget_name,
							'manage_options',
							'my-sticky-elements&widget=' . $key,
							array( $this, 'mystickyelements_admin_settings_page' )
						);
					}
				}
			}
			*/
						
			$license_data = MyStickyElementLicense::get_license_data();
			$is_pro_active = 0;
			if(!empty($license_data)) {
				if($license_data['license'] == "expired" || $license_data['license'] == "valid") {
					$is_pro_active = 1;
				}
			}
			if ( $is_pro_active == 1 ) {
				add_submenu_page(
					'my-sticky-elements',
					'Settings Admin',
					'+ Create New Widget',
					'manage_options',
					'my-sticky-elements-new-widget',
					array( $this, 'mystickyelements_admin_settings_page' )
				);
			} else {
				add_submenu_page(
					'my-sticky-elements',
					'Settings Admin',
					'+ Create New Widget',
					'manage_options',
					'my-sticky-elements-new-widget',
					array( $this, 'mystickyelements_admin_new_widget_page' )
				);
				
			}
			
			/* Date: 2019-07-26*/
			
			add_submenu_page(
				'my-sticky-elements',
				'Settings Admin',
				'Integrations',
				'manage_options',
				'my-sticky-elements-integration',
				array( $this, 'mystickyelements_admin_integration_page' )
			);

			add_submenu_page(
				'my-sticky-elements',
				'Settings Admin',
				'Contact Form Leads',
				'manage_options',
				'my-sticky-elements-leads',
				array( $this, 'mystickyelements_admin_leads_page' )
			);
			if ( !$hide_mserecommended_plugin){
				add_submenu_page(
					'my-sticky-elements',
					'Recommended Plugins',
					'Recommended Plugins',
					'manage_options',
					'recommended-plugins',
					array( $this, 'mystickyelements_recommended_plugins' )
				);
			}
			
			add_submenu_page(
				'my-sticky-elements',
				'License Key',
				'License Key',
				'manage_options',
				'my-sticky-license-key',
				array( $this, 'mystickyelements_admin_license_key' )
			);
		}
		public function mystickyelements_recommended_plugins(){
			include_once 'recommended-plugins.php';
		}
		public function mystickyelements_admin_license_key() {
            delete_transient("MSE_license_key_data");
			$license_data = MyStickyElementLicense::get_license_data();
			include_once 'license-key.php';
		}

		public static function sanitize_options($value, $type = "") {
			$value = stripslashes($value);
			if($type == "int") {
				$value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
			} else if($type == "email") {
				$value = sanitize_email($value);
			} else if($type == "url") {
				$value = esc_url_raw($value);
			} else if($type == "sql") {
				$value = esc_sql($value);
			} else {
				$value = sanitize_text_field($value);
			}
			return $value;
		}
		
		public function mystickyelement_widget_delete(){
			
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die(0); 
			}
			check_ajax_referer( 'mystickyelements', 'wpnonce' );
			
			if ( isset($_POST['widget_id']) && $_POST['widget_id'] != '' && isset($_POST['widget_delete']) && $_POST['widget_delete'] == 1  ) {
				$elements_widgets = get_option( 'mystickyelements-widgets' );
				$stickyelements_widgets_status = get_option('stickyelements_widgets');
				$mystickyelements_widget = self::sanitize_options($_POST['widget_id']);
				unset( $elements_widgets[$mystickyelements_widget] );
				unset( $stickyelements_widgets_status[$mystickyelements_widget] );
				$element_widget_no = '';
				if ( $mystickyelements_widget != 0 ) {
					$element_widget_no = '-' . $mystickyelements_widget;
				}
				delete_option( 'mystickyelements-contact-field' . $element_widget_no );
				delete_option( 'mystickyelements-contact-form' . $element_widget_no );
				delete_option( 'mystickyelements-social-channels' . $element_widget_no );
				delete_option( 'mystickyelements-social-channels-tabs' . $element_widget_no );
				delete_option( 'mystickyelements-general-settings' . $element_widget_no );
				update_option( 'mystickyelements-widgets', $elements_widgets );
				update_option( 'stickyelements_widgets', $stickyelements_widgets_status );
				
			}
			wp_die();
		}

		/*
		 * My Sticky Elements Settings Page
		 *
		 */
		public function mystickyelements_admin_settings_page() {
			global $wpdb;
			$elements_widgets = get_option( 'mystickyelements-widgets' );
			$widget_available = 1;
			$widget_tab_index = 'mystickyelements-contact-form';
			
			if( isset( $elements_widgets ) &&  empty($elements_widgets)){
				$widget_available = 0;
			}

			
			/* Delete Sticky Element Widget */
			if ( isset($_POST['mystickyelement-submit-delete']) && !wp_verify_nonce( $_POST['mystickyelement-submit-delete'], 'mystickyelement-submit-delete' ) ) {
				echo '<div class="error settings-error notice is-dismissible "><p><strong>' . esc_html__('Unable to complete your request','mystickyelements'). '</p></strong></div>';
			} else if (  isset($_POST['action']) && $_POST['action'] == 'delete' && wp_verify_nonce( $_POST['mystickyelement-submit-delete'], 'mystickyelement-submit-delete' ) ) {
				$mystickyelements_widget = self::sanitize_options($_POST['mystickyelements-no-widget']);
				unset( $elements_widgets[$mystickyelements_widget] );
				$element_widget_no = '-' . $mystickyelements_widget;
				delete_option( 'mystickyelements-contact-field' . $element_widget_no );
				delete_option( 'mystickyelements-contact-form' . $element_widget_no );
				delete_option( 'mystickyelements-social-channels' . $element_widget_no );
				delete_option( 'mystickyelements-social-channels-tabs' . $element_widget_no );
				delete_option( 'mystickyelements-general-settings' . $element_widget_no );
				update_option( 'mystickyelements-widgets', $elements_widgets );
				?>
				<script>
				window.location.href = <?php echo "'".admin_url("admin.php?page=my-sticky-elements") . "'";?>;
				</script>
				<?php
			}
			
			
			
			if ( isset($_POST['mystickyelement-submit']) && !wp_verify_nonce( $_POST['mystickyelement-submit'], 'mystickyelement-submit' ) ) {
				echo '<div class="error settings-error notice is-dismissible "><p><strong>' . esc_html__('Unable to complete your request','mystickyelements'). '</p></strong></div>';
			} else if (  isset($_POST['general-settings']) && !empty($_POST['general-settings']) && wp_verify_nonce( $_POST['mystickyelement-submit'], 'mystickyelement-submit' ) ) {
				/*  Date: 2019-07-26*/
				/* Save/Update Contact Form tab */		

				$widget_tab_index = isset($_POST['hide_tab_index']) ? $_POST['hide_tab_index'] : 'mystickyelements-contact-form';

				if ( $elements_widgets == '' || empty($elements_widgets)) {
					//$elements_widgets[] = 'default';
					if (isset($_POST['mystickyelements-widget']) && $_POST['mystickyelements-widget']!='' ) {
						$mystickyelements_no_widget = $_POST['mystickyelements-no-widget'];
						$mystickyelements_widget = self::sanitize_options($_POST['mystickyelements-widget']);
						$elements_widgets[$mystickyelements_no_widget] = $mystickyelements_widget;
					}
					update_option( 'mystickyelements-widgets', $elements_widgets );
				} else {
					$elements_widgets = get_option( 'mystickyelements-widgets' );
					if (isset($_POST['mystickyelements-no-widget']) && $_POST['mystickyelements-no-widget'] !='' ) {
						$mystickyelements_no_widget = $_POST['mystickyelements-no-widget'];
						$mystickyelements_widget = self::sanitize_options($_POST['mystickyelements-widget']);
						$elements_widgets[$mystickyelements_no_widget] = $mystickyelements_widget;
					}
					update_option( 'mystickyelements-widgets', $elements_widgets );
				}
				$elements_widgets = get_option( 'mystickyelements-widgets' );
				$element_widget_no = '';
				if (isset($_POST['mystickyelements-no-widget']) && ( $_POST['mystickyelements-no-widget'] !='' && $_POST['mystickyelements-no-widget'] !='0' ) ) {
					$element_widget_no = '-' . $mystickyelements_no_widget;
				}
				
				/* End Code Date: 2019-07-26 */

				$contact_field = filter_var_array( $_POST['contact-field'], FILTER_SANITIZE_STRING );
				update_option('mystickyelements-contact-field' . $element_widget_no, $contact_field);
				$post = array();
				if(isset($_POST['contact-form'])) {
					$contact = $_POST['contact-form'];
					if(isset($contact['enable'])) {
						$post['enable'] = self::sanitize_options($contact['enable'], "int");
					}

					if(isset($contact['name'])) {
						$post['name'] = self::sanitize_options($contact['name'], "int");
					}

					if(isset($contact['name_require'])) {
						$post['name_require'] = self::sanitize_options($contact['name_require'], "int");
					}

					if(isset($contact['name_value'])) {
						$post['name_value'] = self::sanitize_options($contact['name_value']);
					}

					if(isset($contact['phone'])) {
						$post['phone'] = self::sanitize_options($contact['phone'], "int");
					}

					if(isset($contact['phone_require'])) {
						$post['phone_require'] = self::sanitize_options($contact['phone_require'], "int");
					}

					if(isset($contact['phone_value'])) {
						$post['phone_value'] = self::sanitize_options($contact['phone_value']);
					}

					if(isset($contact['email'])) {
						$post['email'] = self::sanitize_options($contact['email'], "int");
					}

					if(isset($contact['email_require'])) {
						$post['email_require'] = self::sanitize_options($contact['email_require'], "int");
					}

					if(isset($contact['email_value'])) {
						$post['email_value'] = self::sanitize_options($contact['email_value']);
					}

					if(isset($contact['message'])) {
						$post['message'] = self::sanitize_options($contact['message'], "int");
					}

					if(isset($contact['message_require'])) {
						$post['message_require'] = self::sanitize_options($contact['message_require'], "int");
					}

					if(isset($contact['message_value'])) {
						$post['message_value'] = self::sanitize_options($contact['message_value']);
					}

					if(isset($contact['dropdown'])) {
						$post['dropdown'] = self::sanitize_options($contact['dropdown'], "int");
					}

					if(isset($contact['dropdown_require'])) {
						$post['dropdown_require'] = self::sanitize_options($contact['dropdown_require'], "int");
					}
					
					if(isset($contact['consent_text_require'])) {
						$post['consent_text_require'] = self::sanitize_options($contact['consent_text_require'], "int");
					}

					if(isset($contact['consent_text'])) {
						$post['consent_text'] = self::sanitize_options($contact['consent_text']);
					}

					if(isset($contact['submit_button_background_color'])) {
						$post['submit_button_background_color'] = self::sanitize_options($contact['submit_button_background_color']);
					}

					if(isset($contact['submit_button_text_color'])) {
						$post['submit_button_text_color'] = self::sanitize_options($contact['submit_button_text_color']);
					}

					if(isset($contact['submit_button_text'])) {
						$post['submit_button_text'] = self::sanitize_options($contact['submit_button_text']);
					}

					if(isset($contact['tab_background_color'])) {
						$post['tab_background_color'] = self::sanitize_options($contact['tab_background_color']);
					}

					if(isset($contact['tab_text_color'])) {
						$post['tab_text_color'] = self::sanitize_options($contact['tab_text_color']);
					}
					if(isset($contact['form_bg_color'])) {
						$post['form_bg_color'] = self::sanitize_options($contact['form_bg_color']);
					}
					if(isset($contact['headine_text_color'])) {
						$post['headine_text_color'] = self::sanitize_options($contact['headine_text_color']);
					}

					if(isset($contact['text_in_tab'])) {
						$post['text_in_tab'] = self::sanitize_options($contact['text_in_tab']);
					}

					if(isset($contact['contact_title_text'])) {
						$post['contact_title_text'] = self::sanitize_options($contact['contact_title_text']);
					}

					if(isset($contact['thank_you_message'])) {
						$post['thank_you_message'] = self::sanitize_options($contact['thank_you_message']);
					}
					
					if(isset($contact['close_form_automatic'])) {						
						$post['close_form_automatic'] = self::sanitize_options($contact['close_form_automatic'], "int");
					}
					
					if(isset($contact['close_after'])) {						
						$post['close_after'] = self::sanitize_options($contact['close_after'], "int");
					}

					if(isset($contact['send_leads'])) {
						$post['send_leads'] = filter_var_array( $contact['send_leads'], FILTER_SANITIZE_STRING );
					}

					if(isset($contact['sent_to_mail'])) {
						$post['sent_to_mail'] = self::sanitize_options($contact['sent_to_mail']);
					}
					if(isset($contact['sender_name'])) {
						$post['sender_name'] = self::sanitize_options($contact['sender_name']);
					}
					if(isset($contact['email_subject_line'])) {
						$post['email_subject_line'] = self::sanitize_options($contact['email_subject_line']);
					}

					if(isset($contact['direction'])) {
						$post['direction'] = self::sanitize_options($contact['direction']);
					}

					if(isset($contact['direction'])) {
						$post['direction'] = self::sanitize_options($contact['direction']);
					}

					if(isset($contact['desktop'])) {
						$post['desktop'] = self::sanitize_options($contact['desktop'], "int");
					}

					if(isset($contact['mobile'])) {
						$post['mobile'] = self::sanitize_options($contact['mobile'], "int");
					}

					if(isset($contact['form_css'])) {
						$post['form_css'] = self::sanitize_options($contact['form_css']);
					}
					if(isset($contact['dropdown-placeholder'])) {
						$post['dropdown-placeholder'] = self::sanitize_options($contact['dropdown-placeholder']);
					}
					if(isset($contact['dropdown-option'])) {
						$post['dropdown-option'] = filter_var_array( $contact['dropdown-option'], FILTER_SANITIZE_STRING );
					}
					if(isset($contact['redirect'])) {
						$post['redirect'] = self::sanitize_options($contact['redirect'], "int");
					}
					if(isset($contact['redirect_link'])) {
						$post['redirect_link'] = self::sanitize_options($contact['redirect_link']);
					}
					if(isset($contact['open_new_tab'])) {
						$post['open_new_tab'] = self::sanitize_options($contact['open_new_tab'], "int");
					}

					if(isset($contact['custom_fields'])) {
						$post['custom_fields'] = filter_var_array( $contact['custom_fields'], FILTER_SANITIZE_STRING );
					}

					if(isset($contact['consent_checkbox'])) {
						$post['consent_checkbox'] = self::sanitize_options( $contact['consent_checkbox']);
					}
					
					if(isset($contact['consent_text'])) {
						$post['consent_text'] = $contact['consent_text'];
					}
					
					if(isset($contact['iplog'])) {
						$post['iplog'] = self::sanitize_options( $contact['iplog']);
					}
					
					if(isset($contact['iplog_checkbox'])) {
						$post['iplog_checkbox'] = self::sanitize_options( $contact['iplog_checkbox']);
					}
					
					if(isset($contact['recaptcha'])) {
						$post['recaptcha'] = self::sanitize_options( $contact['recaptcha']);
					}
					
					if(isset($contact['recaptcha_checkbox'])) {
						$post['recaptcha_checkbox'] = self::sanitize_options( $contact['recaptcha_checkbox']);
					}
					
					if(isset($contact['invisible_recaptcha_checkbox'])) {
						$post['invisible_recaptcha_checkbox'] = self::sanitize_options( $contact['invisible_recaptcha_checkbox']);
					}
					
					if(isset($contact['recaptcha_site_key'])) {
						$post['recaptcha_site_key'] = self::sanitize_options($contact['recaptcha_site_key']);
					}
					
					if(isset($contact['recaptcha_secrete_key'])) {
						$post['recaptcha_secrete_key'] = self::sanitize_options($contact['recaptcha_secrete_key']);
					}
					
					if(isset($contact['textblock'])) {
						$post['textblock'] = self::sanitize_options( $contact['textblock']);
					}
					
					if(isset($contact['textblock_checkbox'])) {
						$post['textblock_checkbox'] = self::sanitize_options( $contact['textblock_checkbox']);
					}
					
					if(isset($contact['textblock_text'])) {
						$post['textblock_text'] = $contact['textblock_text'];
					}
					
					if(isset($contact['mailpoet_list'])) {
						$post['mailpoet_list'] = self::sanitize_options($contact['mailpoet_list']);
					}
					if(isset($contact['mailchimp_list'])) {
						$post['mailchimp_list'] = self::sanitize_options($contact['mailchimp_list']);
					}
					
					if(isset($contact['mailchimp_enable_tag'])) {
						$post['mailchimp_enable_tag'] = $contact['mailchimp_enable_tag'];
					} else {
						$post['mailchimp_enable_tag'] = '';
					}
					
					if(isset($contact['mailchimp_tags'])) {
						$post['mailchimp_tags'] = $contact['mailchimp_tags'];
					} 
					
					if(isset($contact['mailchimp-enable-group'])) {
						$post['mailchimp-enable-group'] = $contact['mailchimp-enable-group'];
					} else {
						$post['mailchimp-enable-group'] = '';
					}
					
					if(isset($contact['mailchimp-group'])) {
						$post['mailchimp-group'] = $contact['mailchimp-group'];
					}
					if(isset($contact['mailchimp-field-mapping'])) {
						$post['mailchimp-field-mapping'] = $contact['mailchimp-field-mapping'];
					} 					
					
				}
				update_option('mystickyelements-contact-form' . $element_widget_no, $post);

				/* Save/Update Social Channels tabs */
				$social_channels = array();
				if(isset($_POST['social-channels'])) {
					if(!empty($_POST['social-channels'])) {
						$social_channels = $_POST['social-channels'];
						foreach($social_channels as $key=>$val) {
							$social_channels[$key] = self::sanitize_options($val, "int");
						}
					}
				} else {
					$social_channels['is_empty'] = 1;
				}
				update_option('mystickyelements-social-channels' . $element_widget_no, $social_channels);

				$social_channels_tab = array();
				if(isset($_POST['social-channels-tab'])) {
					if(!empty($_POST['social-channels-tab'])) {
						foreach($_POST['social-channels-tab'] as $key=>$option) {
							if(isset($option['text'])) {
								$option['text'] = $option['text'];
							}
							if(isset($option['desktop'])) {
								$option['desktop'] = self::sanitize_options($option['desktop'], "int");
							}
							if(isset($option['mobile'])) {
								$option['mobile'] = self::sanitize_options($option['mobile'], "int");
							}
							if(isset($option['bg_color'])) {
								$option['bg_color'] = self::sanitize_options($option['bg_color']);
							}
							if(isset($option['hover_text'])) {
								$option['hover_text'] = self::sanitize_options($option['hover_text']);
							}
							if(isset($option['open_newtab'])) {
								$option['open_newtab'] = self::sanitize_options($option['open_newtab']);
							}
							$social_channels_tab[$key] = $option;
						}
					}
				} else {
					$social_channels_tab['is_empty'] = 1;
				}
				update_option( 'mystickyelements-social-channels-tabs' . $element_widget_no, $social_channels_tab);

				/* Save/Update General Settings */
				$general_setting = filter_var_array( $_POST['general-settings'], FILTER_SANITIZE_STRING );
				/* Unset Page rule when value empty */
				if ( !empty($general_setting['page_settings']) && is_array($general_setting['page_settings']) ) {
					foreach( $general_setting['page_settings'] as $key=>$value ) {
						if ( trim($value['value']) == '' ) {
							unset($general_setting['page_settings'][$key]);
						}
					}
				}
				update_option('mystickyelements-general-settings' . $element_widget_no, $general_setting);

				/* Send Email Afte set email */
				if ( isset($_POST['contact-form']['send_leads']) && in_array( 'mail', $_POST['contact-form']['send_leads'])  && $_POST['contact-form']['sent_to_mail'] != '' && !get_option('mystickyelements-contact-mail-sent') ) {
					$send_mail = $_POST['contact-form']['sent_to_mail'];

					$subject = "Great job! You created your contact form successfully";
					$message = 'Thanks for using MyStickyElements! If you see this message in your spam folder, please click on "Report not spam" so you will get the next leads into your inbox.';


					$blog_name = get_bloginfo('name');
					$blog_email = get_bloginfo('admin_email');

					$headers = "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
					$headers .= 'From: ' . $blog_name . ' <' . $blog_email . '>' ."\r\n";
					$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";

					if ( wp_mail( $send_mail, $subject, $message, $headers ) ) {
						update_option( 'mystickyelements-contact-mail-sent', true );
					}
				}
				$this->mystickyelements_clear_all_caches();
				
				
				/* Date: 2019-07-26 Redirect Page After Save */
				if ( isset($mystickyelements_no_widget) && $mystickyelements_no_widget != '' && isset($_GET['page']) && $_GET['page'] == 'my-sticky-elements-new-widget' && $widget_available == 0 ) {
				?>
					<script>
					window.location.href = <?php echo "'".admin_url("admin.php?page=my-sticky-elements&widget_available=0&new_widget=1&widget=" . $mystickyelements_no_widget ) . "'";?>;
					</script>
					<?php
				}
				else if(isset($mystickyelements_no_widget) && $mystickyelements_no_widget != '' && isset($_GET['page']) && $_GET['page'] == 'my-sticky-elements-new-widget' && $widget_available == 1){
					?>
					<script>
					window.location.href = <?php echo "'".admin_url("admin.php?page=my-sticky-elements&widget_available=1&new_widget=1&widget=" . $mystickyelements_no_widget ) . "'";?>;
					</script>
					<?php
				}

				/* End Date: 2019-07-26*/
			}

			$element_widget_no = '';
			$widgets = '';
			/* Check Elements Widget is blank or empty */
			if ( ( $elements_widgets == '' || empty($elements_widgets) ) && ( isset($_GET['page']) && $_GET['page'] == 'my-sticky-elements-new-widget' ) ) {
				$widgets = 1;
				$element_widget_no = '-' . $widgets;
			} else if ( isset($_GET['page']) && $_GET['page'] == 'my-sticky-elements-new-widget' ) {

				end($elements_widgets);
				$last_key = key($elements_widgets);
				$widgets = $last_key + 1;
				$element_widget_no = '-' . $widgets;
			}
			if ( isset($_GET['widget']) && $_GET['widget'] != '' && $_GET['widget'] != '0' ) {
				$widgets = $_GET['widget'];
				$element_widget_no = '-' . $widgets;
			}
			if ( isset($_GET['copy-from']) && $_GET['copy-from'] != '' ) {
				$widgets = $_GET['copy-from'];				
				$element_widget_no = ($widgets != 0 ) ? '-' . $widgets : '';				
			}
			$counts = (!empty($elements_widgets)) ? count($elements_widgets) : 0 ;
			//$counts = $counts + 1;
			if ( isset($_GET['page']) && $_GET['page'] == 'my-sticky-elements' && !isset($_GET['widget']) && $counts > 1  ) {
				$widgets = 0;
			}
			/* END Date: 2019-07-26 */
			$contact_field = get_option( 'mystickyelements-contact-field' . $element_widget_no );
			if ( empty( $contact_field ) ) {
				$contact_field = array( 'name', 'phone', 'email', 'message', 'dropdown' );
			}
			$contact_form = get_option( 'mystickyelements-contact-form' . $element_widget_no );
			/* Get the Default Contact Form */
			if ( empty( $contact_form)) {
				$contact_form = mystickyelements_pro_widget_default_fields( 'contact_form' );
			}
			$social_channels = get_option( 'mystickyelements-social-channels' . $element_widget_no );
			/* Get the Default social_channels*/
			if ( empty( $social_channels)) {
				$social_channels = mystickyelements_pro_widget_default_fields( 'social_channels' );
			}
			$social_channels_tabs = get_option( 'mystickyelements-social-channels-tabs' . $element_widget_no );
			/* Get the Default social_channels*/
			if ( empty( $social_channels_tabs)) {
				$social_channels_tabs = mystickyelements_pro_widget_default_fields( 'social_channels_tabs' );
			}
			$general_settings = get_option( 'mystickyelements-general-settings' . $element_widget_no );
			/* Get the Default social_channels*/
			if ( empty( $general_settings)) {
				$general_settings = mystickyelements_pro_widget_default_fields( 'general_settings' );
			}
			if ( !isset($general_settings['position_mobile']) ) {
				$general_settings['position_mobile'] = 'left';
			}
			$social_channels_lists = mystickyelements_social_channels();

			$upgrade_url = admin_url("admin.php?page=my-sticky-license-key");
			$license_data = MyStickyElementLicense::get_license_data();
			$is_pro_active = 0;
			if(!empty($license_data)) {
				if($license_data['license'] == "expired" || $license_data['license'] == "valid") {
					$is_pro_active = 1;
				}
			}
			if ( isset($_GET['copy-from']) && $_GET['copy-from'] != '' ) {
				end($elements_widgets);
				$last_key = key($elements_widgets);
				$widgets = $last_key + 1;
			}
			$default_fonts = array('Arial', 'Tahoma', 'Verdana', 'Helvetica', 'Times New Roman', 'Trebuchet MS', 'Georgia', 'Open Sans Hebrew');
			if (isset($general_settings['font_family']) && $general_settings['font_family'] !="" ) :
				if ( !in_array( $general_settings['font_family'], $default_fonts) ):
			?>
				<link href="https://fonts.googleapis.com/css?family=<?php echo $general_settings['font_family'];?>:400,500,600,700" rel="stylesheet" type="text/css" class="sfba-google-font">
				<?php endif;?>
				<style>
					.myStickyelements-preview-ul .mystickyelements-social-icon{ font-family: <?php echo $general_settings['font_family'];?>}
				</style>
			<?php endif;
			
			if ( !isset($_GET['widget']) && isset($_GET['page']) && $_GET['page'] != 'my-sticky-elements-new-widget') {
				include_once( 'admin/stickyelements-dashboard.php');
			} else {
				include_once( 'admin/stickyelements-settings.php');
			}
			?>
			<?php
				$table_name = $wpdb->prefix . "mystickyelement_contact_lists";
				$result = $wpdb->get_results ( "SELECT count(*) as count FROM ".$table_name ." ORDER BY ID DESC" );

				if ( $result[0]->count != 0 && !get_option( 'myStickyelements_show_leads' )) { ?>
					<div id="myStickyelements-new-lead-confirm" style="display:none;" title="<?php esc_attr_e( 'Congratulations 🎉', 'mystickyelement-submit-delete' ); ?>">
						<p><?php _e('You just got your first My Sticky Elements lead. Click on the Show Me button to display your contact form leads' ); ?></p>
					</div>
					<script>
						( function( $ ) {
							"use strict";
							$(document).ready(function(){
								jQuery( "#myStickyelements-new-lead-confirm" ).dialog({
									resizable: false,
									modal: true,
									draggable: false,
									height: 'auto',
									width: 400,
									buttons: {
										"Show Me": {
											click: function () {
												window.location = "<?php echo admin_url('admin.php?page=my-sticky-elements-leads')?>";
												//$(this).dialog('close');
											},
											text: 'Show Me',
											class: 'purple-btn'
										},
										"Not Now": {
											click: function () {
												confetti.remove();
												$(this).dialog('close');
											},
											text: 'Not Now',
											class: 'gray-btn'
										},								
									}
								});
								confetti.start();
								$('#myStickyelements-new-lead-confirm').bind('dialogclose', function(event) {
									confetti.remove();
								});
							});
						})( jQuery );
					</script>
					<?php
					update_option( 'myStickyelements_show_leads', 1 );
				}
				?>
				<div id="mystickyelement-save-confirm" style="display:none;" title="<?php esc_attr_e( 'Icons\' text isn\'t supported in this template', 'mystickyelement-submit-delete' ); ?>">
					<p>
						<?php _e("The selected template doesn't support icons'text, please change to the Default templates. Would you like to publish it anyway?", 'mystickyelement' ); ?>
					</p>
				</div>
				<?php
				//include_once( 'mystickyelements-duplicate-widget.php' );
			
				
		}

		public function mystickyelement_social_tab_add( $key, $element_widget_no = '' ) {
			global $social_channel_count;
			if ( isset($_POST['is_ajax']) && $_POST['is_ajax'] == true ) {
				if ( ! current_user_can( 'manage_options' ) ) {
					wp_die(0); 
				}
				check_ajax_referer( 'mystickyelements', 'wpnonce' );
			}
			
			$social_channel = (isset($_POST['social_channel'])) ? $_POST['social_channel'] : $key ;
			
			
			if ( $social_channel != '') {
				$social_channels_tabs = get_option( 'mystickyelements-social-channels-tabs' . $element_widget_no, true );
				/* Return when Is Empty key found and isajax not set */
				if ( isset($social_channels_tabs['is_empty']) && $social_channels_tabs['is_empty'] == 1 && !isset($_POST['is_ajax']) ) {
					return;
				}
				
				
				$social_channels_lists = mystickyelements_social_channels();
				$social_channels_list = $social_channels_lists[$social_channel];
				
				$social_channel_value = ( isset($social_channels_tabs[$key])) ? $social_channels_tabs[$key] : array();
				
				$social_channels_list['text'] = isset($social_channels_list['text'])?$social_channels_list['text']:"";
				$social_channels_list['icon_text'] = isset($social_channels_list['icon_text'])?$social_channels_list['icon_text']:"";
                $social_channels_list['icon_text_size'] = isset($social_channels_list['icon_text_size'])?$social_channels_list['icon_text_size']:"";
                $social_channels_list['background_color'] = isset($social_channels_list['background_color'])?$social_channels_list['background_color']:"";
                $social_channels_list['hover_text'] = isset($social_channels_list['hover_text'])?$social_channels_list['hover_text']:"";
				
				if ( empty($social_channel_value)) {
					
					$social_channel_value['text'] = '';//$social_channels_list['text'];
					
					$social_channel_value['bg_color'] = $social_channels_list['background_color'];
					$social_channel_value['icon_text'] = $social_channels_list['icon_text'];
					$social_channel_value['icon_text_size'] = $social_channels_list['icon_text_size'];
					$social_channel_value['hover_text'] = $social_channels_list['hover_text'];
					$social_channel_value['desktop'] = 1;
					$social_channel_value['mobile'] = 1;
					$social_channel_value['icon_color'] = '';
				}
				
				//echo "texttttt" . $social_channel_value['text'];
				
				if ( !isset($social_channel_value['icon_text'])) {
					$social_channel_value['icon_text'] = '';
				}
				if ( !isset($social_channel_value['icon_text_size'])) {
					$social_channel_value['icon_text_size'] = '';
				}
				if ( !isset($social_channel_value['icon_color'])) {
					$social_channel_value['icon_color'] = '';
				}
				if ( !isset($social_channel_value['pre_set_message'])) {
					$social_channel_value['pre_set_message'] = '';
				}

				if ( isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1 && isset($social_channel_value['fontawesome_icon']) && $social_channel_value['fontawesome_icon'] != '' ) {
					$social_channels_list['class'] = $social_channel_value['fontawesome_icon'];
				} else {
					$social_channel_value['fontawesome_icon'] = '';
				}

				if ( !isset($social_channels_list['custom_icon']) && !isset($social_channel_value['custom_icon']) ) {
					$social_channel_value['custom_icon'] = '';
				}

				if ( $key == 'line') {
					echo "<style>.social-channels-item .social-channel-input-box .social-". $key ." svg .fil1{ fill:" .$social_channel_value['icon_color']. "}</style>";
				}
				if ( $key == 'qzone') {
					echo "<style>.social-channels-item .social-channel-input-box .social-". $key ." svg .fil2{ fill:" . $social_channel_value['icon_color'] . "}</style>";
				}

				$social_channel_value['text'] = str_replace('\"', '"', $social_channel_value['text']);
				$social_channel_value['channel_type'] = (isset($social_channel_value['channel_type'])) ? $social_channel_value['channel_type'] : '';
				$channel_type = (isset($social_channel_value['channel_type'])) ? $social_channel_value['channel_type'] : '';
				if ( $channel_type != 'custom' && $channel_type != '' ) {
					if ( isset($social_channels_lists[$channel_type]['custom_svg_icon']) ) {
						$social_channels_list['custom_svg_icon'] = $social_channels_lists[$channel_type]['custom_svg_icon'];
					}
					$social_channels_list['class'] = $social_channels_lists[$channel_type]['class'];
					if ( $channel_type == 'whatsapp') {
						$social_channels_list['is_pre_set_message'] = 1;
					}
				}
				?>
				<div id="social-channel-<?php echo esc_attr($social_channel); ?>" class="social-channels-item" data-slug="<?php echo esc_attr($social_channel); ?>">
					<div class="mystickyelements-move-handle"></div>
					<div class="social-channels-item-title social-channel-input-box-section">
						<label>
							
							<span class="social-channels-list social-<?php echo esc_attr($social_channel);?> social-<?php echo esc_attr($channel_type);?>" style="background-color: <?php echo esc_attr($social_channel_value['bg_color'])?>; color: <?php echo esc_attr($social_channel_value['icon_color'])?>; position:relative;">
								<?php if (isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1 && isset($social_channel_value['custom_icon']) && $social_channel_value['custom_icon'] != '' && isset($social_channel_value['fontawesome_icon']) && $social_channel_value['fontawesome_icon'] == ''): ?>
									<img class="<?php echo ( isset($social_channel_value['stretch_custom_icon']) && $social_channel_value['stretch_custom_icon'] == 1 ) ? 'mystickyelements-stretch-custom-img' : '';  ?>" src="<?php echo esc_url($social_channel_value['custom_icon']); ?>" width="25" height="25"/>
								<?php
								else:
									if ( isset($social_channels_list['custom_svg_icon'] ) && $social_channels_list['custom_svg_icon'] != '' ) :
										echo $social_channels_list['custom_svg_icon'];
									else:?>
									<i class="<?php echo esc_attr($social_channels_list['class'])?>"></i>
									<?php endif;
								endif; ?>
							</span>
							<span><?php echo esc_html($social_channels_list['text']. " Settings");?> </span>
						</label>
					</div> 
					<div class="myStickyelements-setting-wrap-list input-link">
						<label><?php if( isset($social_channels_list['icon_label']) && $social_channels_list['icon_label']!="") { echo _e($social_channels_list['icon_label'], 'mystickyelements'); } else{ echo _e('Icon link', 'mystickyelements'); } ?></label>
						<div class="px-wrap myStickyelements-inputs">
							<!-- toolteap -->	
							<?php if ( isset($social_channels_list['tooltip']) && $social_channels_list['tooltip'] != "" ) : ?>
								<label class="social-tooltip" >
									<span>
										<i class="fas fa-info"></i>
										<span class="social-tooltip-popup" >
											<?php echo $social_channels_list['tooltip']; ?>
										</span>
									</span>
								</label>
							<?php endif; ?>
						
							<input type="text" class="mystickyelement-social-links-input<?php if( isset($social_channels_list['number_validation']) && $social_channels_list['number_validation'] == 1) : ?> mystickyelement-social-text-input<?php endif; ?>" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][text]" value="<?php echo esc_attr(stripslashes($social_channel_value['text']));?>" placeholder="<?php echo esc_attr($social_channels_list['placeholder'])?>"/>
						</div>	
					</div>
					<div class="myStickyelements-setting-wrap-list device-option">
						<label><?php echo _e('Devices', 'mystickyelements');?></label>
						<div class="px-wrap myStickyelements-inputs">
							<ul>
								<li>
									<label>
										<input type="checkbox" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][desktop]" data-social-channel-view="<?php echo esc_attr($social_channel);?>" value= "1" class="social-channel-view-desktop" id="social_channel_<?php echo esc_attr($social_channel);?>_desktop" <?php checked( @$social_channel_value['desktop'], '1' );?> />&nbsp
										<span>
											<i class="fas fa-desktop">&nbsp</i><?php echo _e('Desktop', 'mystickyelements');?>
										</span>
									</label>
								</li>
								<li>
									<label>
										<input type="checkbox" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][mobile]" data-social-channel-view="<?php echo esc_attr($social_channel);?>" value="1" class="social-channel-view-mobile" id="social_channel_<?php echo esc_attr($social_channel);?>_mobile" <?php checked( @$social_channel_value['mobile'], '1' );?> />&nbsp
										<span>
											<i class="fas fa-mobile-alt"></i>&nbsp<?php echo _e('Mobile', 'mystickyelements');?>
										</span>
									</label>
								</li>
							</ul>
						</div>
					</div>
					<?php if( !isset($social_channels_list['icon_new_tab']) ) : ?>
					<div class="myStickyelements-setting-wrap-list open-new-link-tab">
						<label><?php echo _e('Open link in a new tab', 'mystickyelements');?></label>
						<div class="px-wrap myStickyelements-inputs">
							<input type="checkbox" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][open_new_tab]" value= "1" class="social-channel-view-desktop" id="social_channel_<?php echo esc_attr($social_channel);?>_open_tab" checked="checked" /> 
						</div>	
					</div>
					<?php endif; ?>
					<div class="myStickyelements-setting-wrap-list myStickyelements-channel-view">
						<label>
							<i class="fas fa-palette"></i>&nbsp&nbsp
							<span class="social-setting" data-slug="<?php echo $social_channel; ?>"><?php echo _e('Apperance Settings', 'mystickyelements');?>&nbsp<i class="fas fa-chevron-down"></i></span>
						</label>	
					</div>
					<div class="social-channel-setting" style="display:none;">
						<table>
							<tr class="myStickyelements-custom-icon-image" <?php if ( !isset($social_channels_list['custom']) || ($channel_type != '' && $channel_type !='custom')) :?>style="display:none;" <?php endif;?>>
								<td colspan="2" style="text-align:left;">
									<div class="myStickyelements-custom-image-icon">
										<div class="myStickyelements-custom-image">
											<input type="button" data-slug="<?php echo esc_attr($social_channel);?>" name="social-channels-icon"  class="button-secondary social-custom-icon-upload-button" value="<?php esc_attr_e( 'Upload Custom Icon', 'mystickyelements'); ?>" />

											<div id="social-channel-<?php echo esc_attr($social_channel);?>-icon" class="social-channel-icon" style="display:none; ">
												<img src="<?php echo esc_url($social_channel_value['custom_icon'])?>" id="social-channel-<?php echo esc_attr($social_channel);?>-custom-icon-img"  width="38" height="38"/>
												<span class="social-channel-icon-close" data-slug="<?php echo esc_attr($social_channel);?>">x</span>
											</div>

											<input type="hidden" id="social-channel-<?php echo esc_attr($social_channel);?>-custom-icon" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][custom_icon]" value="<?php echo esc_url($social_channel_value['custom_icon'])?>" />
											<div class="myStickyelements-setting-wrap-list myStickyelements-stretch-icon-wrap">
												<label>
													<input type="checkbox" data-slug="<?php echo esc_attr($social_channel);?>" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][stretch_custom_icon]" value="1" <?php if ( isset($social_channel_value['stretch_custom_icon']) && $social_channel_value['stretch_custom_icon'] == 1 ) { echo 'checked="checked"'; } ?>  />&nbsp;<?php _e( 'Stretch custom icon', 'mystickyelements' );?>
												</label>
											</div>
										</div>
										<div class="myStickyelements-custom-icon">
											<span>Or</span>
											<?php $fontawesome_icons = mystickyelements_fontawesome_icons();?>
											<select id="mystickyelements-<?php echo esc_attr($social_channel);?>-custom-icon" data-slug="<?php echo esc_attr($social_channel);?>" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][fontawesome_icon]" class="social-channel-fontawesome-icon">
												<option value=""><?php esc_html_e( 'Select FontAwesome Icon', 'mystickyelements');?></option>
												<?php foreach( $fontawesome_icons as $icons):
													$icon_html = '<i class="' . $icons . '"></i>';
												?>
													<option value="<?php echo $icons?>" <?php selected( $social_channel_value['fontawesome_icon'] , $icons)?>><?php echo $icons;?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
								</td>
							</tr>
							<tr <?php if ( !isset($social_channels_list['custom']) || (isset($social_channels_list['custom_html']))) :?>style="display:none;" <?php endif;?>>
								<td>
									<div class="myStickyelements-setting-wrap-list">
										<label><?php _e( 'Channel Type', 'mystickyelements' );?></label>
										<div class="px-wrap myStickyelements-inputs">
											<select class="social-custom-channel-type" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][channel_type]"  data-id="social-channel-<?php echo esc_attr($social_channel); ?>" data-slug="social-<?php echo esc_attr($social_channel);?>">
												<option value="custom" data-social-channel='<?php echo wp_json_encode($social_channels_list);?>' <?php selected($social_channel_value['channel_type'], 'custom', true)?>><?php _e( 'Custom channel', 'mystickyelements' );?></option>
												<?php foreach(mystickyelements_social_channels() as $csc_key=>$csc_val): 
													if ( isset($csc_val['custom']) && $csc_val['custom'] == 1 ) {
														continue;
													}
												?>
													<option value="<?php echo $csc_key;?>" data-social-channel='<?php echo wp_json_encode($csc_val);?>' <?php selected($social_channel_value['channel_type'], $csc_key, true)?>><?php echo $csc_val['hover_text'];?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
								</td>
							</tr>
							
							<tr>
								<td>
									<div class="myStickyelements-setting-wrap-list myStickyelements-background-color">
										<label><?php _e( 'Background Color', 'mystickyelements' );?></label>
										<input type="text" data-slug="<?php echo esc_attr($social_channel); ?>" id="social-<?php echo esc_attr($social_channel);?>-bg_color" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][bg_color]" class="mystickyelement-color" value="<?php echo esc_attr($social_channel_value['bg_color']);?>" />
									</div>
									<?php if ( isset($social_channels_list['icon_color']) && $social_channels_list['icon_color'] == 1) :?>
									<div class="myStickyelements-setting-wrap-list myStickyelements-custom-icon-color">
										<label><?php _e( 'Icon Color', 'mystickyelements' );?></label>
										<input type="text" data-soical-icon="<?php echo esc_attr($social_channel); ?>" id="social-<?php echo esc_attr($social_channel);?>-icon_color" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][icon_color]" class="mystickyelement-color" value="<?php echo esc_attr($social_channel_value['icon_color']);?>" />
									</div>
									<?php endif;?>
									<div class="myStickyelements-setting-wrap-list">
										<label><?php _e( 'Icon Text', 'mystickyelements' );?></label>
										<div class="px-wrap myStickyelements-inputs">
											<input type="text" class="myStickyelements-icon-text-input" id="social-<?php echo esc_attr($social_channel);?>-icon_text" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][icon_text]" value="<?php echo esc_attr($social_channel_value['icon_text']);?>" data-icontext="<?php echo esc_attr($social_channel);?>" placeholder="<?php _e('Enter text here...','mystickyelements');?>" />
										</div>
									</div>
									<div class="myStickyelements-setting-wrap-list">
										<label><?php _e( 'Icon Text Size', 'mystickyelements' );?></label>
										<div class="px-wrap myStickyelements-inputs">
											<input type="number" class="myStickyelements-icon-text-size" id="social-<?php echo esc_attr($social_channel);?>-icon_text_size" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][icon_text_size]" value="<?php echo esc_attr($social_channel_value['icon_text_size']);?>" min="0" data-icontextsize="<?php echo esc_attr($social_channel);?>" placeholder="<?php _e('Enter font size here...','mystickyelements');?>" />
											<span class="input-px">PX</span>
										</div>
									</div>
									<div class="myStickyelements-setting-wrap-list myStickyelements-on-hover-text">
										<label><?php _e( 'On Hover Text', 'mystickyelements' );?></label>
										<div class="px-wrap myStickyelements-inputs">
											<input type="text" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][hover_text]" value="<?php echo esc_attr($social_channel_value['hover_text']);?>" placeholder="<?php _e('Enter text here...','mystickyelements');?>" />
										</div>
									</div>
									
									<div class="myStickyelements-setting-wrap-list myStickyelements-custom-pre-message" <?php if ( !isset($social_channels_list['is_pre_set_message']) ) :?>style="display:none;" <?php endif;?>>
										<label><?php _e( 'Pre Set Message', 'mystickyelements' );?></label>
										<div class="px-wrap myStickyelements-inputs">
											<input type="text" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][pre_set_message]" value="<?php echo esc_attr($social_channel_value['pre_set_message']);?>" placeholder="<?php _e('Enter message here...','mystickyelements');?>" />
										</div>
									</div>
									

									<?php if ( !isset($social_channels_list['custom_html']) && isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1) :?>
									<div class="myStickyelements-setting-wrap-list myStickyelements-custom-tab">
										<div id="checkboxes">
											<label>
												<input type="checkbox" name="social-channels-tab[<?php echo esc_attr($social_channel);?>][open_newtab]" value="1" <?php if ( isset($social_channel_value['open_newtab']) && $social_channel_value['open_newtab'] == 1 ) { echo 'checked="checked"'; } ?>  />&nbsp;<?php _e( 'Open in a new tab', 'mystickyelements' );?>
											</label>
										</div>
									</div>
									<?php endif;?>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<!-- end social channel tabs-->
				<?php

			}
			if ( isset($_POST['is_ajax']) && $_POST['is_ajax'] == true ) {
				wp_die();
			}
		}
		
		/*
		 * My Sticky Elements Integration page
		 *
		 */
		public function mystickyelements_admin_integration_page(){
			include( 'mystickyelements-admin-integration.php' );
		}

		/*
		 * My Sticky Elements Contact Leads
		 *
		 */
		public function mystickyelements_admin_leads_page(){
			global $wpdb;

			$table_name = $wpdb->prefix . "mystickyelement_contact_lists";

			if ( isset($_POST['stickyelement-contatc-submit']) && !wp_verify_nonce( $_POST['stickyelement-contatc-submit'], 'stickyelement-contatc-submit' ) ) {

				echo '<div class="error settings-error notice is-dismissible "><p><strong>' . esc_html__('Unable to complete your request','mystickyelements'). '</p></strong></div>';

			} else if ( isset($_POST['stickyelement-contatc-submit']) && wp_verify_nonce( $_POST['stickyelement-contatc-submit'], 'stickyelement-contatc-submit' )  ) {
				if ( isset($_POST['delete_message']) && !empty($_POST['delete_message'])) {

					$count = count($_POST['delete_message']);
					foreach ( $_POST['delete_message'] as $key=>$ID) {
						$delete = $wpdb->query("DELETE FROM $table_name WHERE ID = " . $ID);
					}
					echo '<div class="updated settings-error notice is-dismissible "><p><strong>' . esc_html__( $count . ' message deleted.','mystickyelements'). '</p></strong></div>';

				}
			}
			$elements_widgets = get_option( 'mystickyelements-widgets' );
			$custom_fields = array();
			if ( !empty($elements_widgets)) {
				foreach( $elements_widgets as $key=>$value) {
					$widget_no = '-'.$key;
					if ( $key == 0 ) {
						$widget_no = '';
					}
					$contact_form = get_option( 'mystickyelements-contact-form' . $widget_no);

					if ( !empty($contact_form['custom_fields'])) {
						foreach($contact_form['custom_fields'] as $value ) {
							$custom_fields[] = $value['custom_field_name'];
						}
					}

				}
			}
			?>
			<div class="wrap mystickyelement-contact-wrap">
				<h2><?php _e( 'Contact Form Leads', 'mystickyelements' ); ?></h2>
				<p class="description">
					<strong><?php esc_html_e("Contact's data is saved locally do make backup or export before uninstalling plugin", 'mystickyelements');?></strong>
				</p>
				<div>
					<table id="mystickyelement_contact_tab">
						<tr>
							<td><strong><?php esc_html_e('Download & Export All Subscriber to CSV file:','mystickyelements' );?> </strong></td>
							<td><a href="<?php echo plugins_url('mystickyelements-contact-leads.php?download_file=mystickyelements_contact_leads.csv',__FILE__); ?>" class="wpappp_buton" id="wpappp_export_to_csv" value="Export to CSV" href="#"><?php esc_html_e('Download & Export to CSV', 'mystickyelements' );?></a></td>
							<td><strong><?php esc_html_e('Delete All Subscibers from Database:','mystickyelements');?> </strong></td>
							<td><input type="button" class="wpappp_buton" id="mystickyelement_delete_all_leads" value="<?php esc_attr_e('Delete All Data', 'mystickyelements' );?>" /></td>
						</tr>
					</table>
					<input type="hidden" id="delete_nonce" name="delete_nonce" value="<?php echo wp_create_nonce("mysticky_elements_delete_nonce") ?>" />
				</div>

				<div>
					<form action="" method="post">
						<div class="tablenav top">
							<div class="alignleft actions bulkactions">
								<select name="action" id="bulk-action-selector-top">
								<option value="">Bulk Actions</option>
								<option value="delete_message">Delete</option>
								</select>
								<input type="submit" id="doaction" class="button action" value="Apply">
								<?php wp_nonce_field( 'stickyelement-contatc-submit', 'stickyelement-contatc-submit' );  ?>
							</div>
						</div>
						<table border="1" class="responstable">
							<tr>
								<th style="width:1%"><?php esc_html_e( 'Bulk', 'mystickyelements' );?></th>
								<th><?php esc_html_e( 'ID', 'mystickyelements');?></th>
								<th><?php esc_html_e( 'Widget Name', 'mystickyelements');?></th>
								<th><?php esc_html_e( 'Name', 'mystickyelements');?></th>
								<th><?php esc_html_e( 'Phone', 'mystickyelements');?></th>
								<th><?php esc_html_e( 'Email', 'mystickyelements');?></th>
								<th><?php esc_html_e( 'Option', 'mystickyelements');?></th>
								<th><?php esc_html_e( 'Message', 'mystickyelements');?></th>
								<th><?php esc_html_e( 'Consent', 'mystickyelements');?></th>
								<?php
								if (!empty($custom_fields)){
									foreach( $custom_fields as $value ) {
										?>
										<th><?php echo esc_html($value);?></th>
										<?php
									}
								}
								?>
								<th><?php esc_html_e( 'Date', 'mystickyelements');?></th>
								<th><?php esc_html_e( 'URL', 'mystickyelements');?></th>
								<th><?php esc_html_e( 'IP Address', 'mystickyelements');?></th>
								<th style="width:11%"><?php esc_html_e( 'Delete', 'mystickyelements');?></th>
							</tr>
						<?php
						$result = $wpdb->get_results ( "SELECT * FROM ".$table_name ." ORDER BY ID DESC" );
						if($result){
							foreach ( $result as $res ) { ?>
								<tr>
									<td><input id="cb-select-80" type="checkbox" name="delete_message[]" value="<?php echo esc_attr($res->ID);?>"></td>
									<td><a href="<?php echo esc_url(admin_url( 'admin.php?page=my-sticky-elements-leads&id=' . $res->ID ));?>"><?php echo $res->ID;?></a></td>
									<td><a href="<?php echo esc_url(admin_url( 'admin.php?page=my-sticky-elements-leads&id=' . $res->ID ));?>"><?php echo $res->widget_element_name;?></a></td>
									<td><?php echo $res->contact_name ;?></td>
									<td><?php echo $res->contact_phone;?></td>
									<td><?php echo $res->contact_email;?></td>
									<td><?php echo $res->contact_option;?></td>
									<td><?php echo wpautop($res->contact_message);?></td>
									<td><?php echo ( isset($res->consent_checkbox) && $res->consent_checkbox == 1 ) ? "True" : "False";?></td>
									<?php
									if (!empty($custom_fields)){
										$custom_field = json_decode($res->custom_fields, true );
										foreach( $custom_fields as $value ) {
											?>
											<td>
												<?php 
												//if( isset( $custom_field[$value] ) && $custom_field[$value] != '' && strpos( $custom_field[$value], 'http' ) !== false ) {													
												if( isset( $custom_field[$value] ) && $custom_field[$value] != '' && ( substr( $custom_field[$value], 0, 7 ) == 'http://' || substr( $custom_field[$value], 0, 7 ) == 'https:/' ) ) {												
													$parts = parse_url($custom_field[$value]);
													if ( isset($parts['path']) ) {
														$file_name = basename($parts['path']);
														$file_url = '<a href="'.esc_url($custom_field[$value]).'" target="_blank" download >'.$file_name.'</a>';
														echo ( isset( $custom_field[$value] ) && $custom_field[$value] != '' ) ? $file_url : $custom_field[$value] ;
													} else {
														echo $custom_field[$value];
													}
												} else {
													echo ( isset( $custom_field[$value] ) && $custom_field[$value] != '' ) ? wp_kses_post($custom_field[$value]) : '' ;
												} ?>
											</td>
											<?php
										}
									}
									?>
									<td><?php echo ( isset($res->message_date) ) ? $res->message_date : '-' ;?></td>
									<td>
										<?php if ( $res->page_link) :?>
										<a href="<?php echo esc_url($res->page_link);?>" target="_blank"><i class="fas fa-external-link-alt"></i></a>
										<?php endif;?>
									</td>
									<td>
										<?php echo $res->ip_address; ?>
									</td>
									<td>
										<input type="button" data-delete="<?php echo $res->ID;?>" class="mystickyelement-delete-entry" value="<?php esc_attr_e('Delete Record', 'mystickyelements');?>" />
									</td>
								</tr>
							<?php }
						} else { ?>
							<tr>
								<td colspan="6" align="center">
									<p class="mystickyelement-no-contact"> <?php esc_html_e('No Contact Form Leads Found!','mystickyelements');?>
									</p>
								</td>
							</tr>
						<?php }	?>

						</table>
					</form>
				</div>
			</div>
			<?php
		}
		
		public function mystickyelement_mailchimp_field(){
			global $wpdb;
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die(0); 
			}
			check_ajax_referer( 'mystickyelements', 'wpnonce' );
			
			if ( isset($_POST['list_id']) && $_POST['list_id'] != '' ) {
				$widget_no = $_POST['widget_no'];
				if ( $widget_no == 0) {
					$widget_no = '';
				}
				$mc_fields = mystickyelements_get_mailchimp_lists_fields( $_POST['list_id'] );
				$contact_form = get_option( 'mystickyelements-contact-form' . $widget_no );
				$dropdown = ( isset($contact_form['dropdown'])) ? $contact_form['dropdown'] : '';
				if ( $dropdown == 1 ) {
					$contact_form['custom_fields'][] = array('custom_field_name' => 'Dropdown');
				}
				$mailchimp_field_mapping = ( isset($contact_form['mailchimp-field-mapping'])) ? $contact_form['mailchimp-field-mapping'] : array();
				if( isset($contact_form['custom_fields']) && !empty( $contact_form['custom_fields'] )) :?>
				<?php foreach( $contact_form['custom_fields'] as $fields ):
				$custom_field_name = sanitize_title($fields['custom_field_name']);
				?>
					<div class="mailchimp-field-control">
						<label class="field-control-title" for="<?php echo esc_attr($custom_field_name);?>"><?php echo $fields['custom_field_name']?></label>
						<select class="field-control-dropdown" id="<?php echo esc_attr($custom_field_name);?>" name="contact-form[mailchimp-field-mapping][<?php echo esc_attr($custom_field_name);?>]">
							<option value="">Select fields</option>
							<?php foreach( $mc_fields as $field):?>
								<option value="<?php echo $field['field_id'];?>" <?php if( isset($mailchimp_field_mapping[$custom_field_name]) && $mailchimp_field_mapping[$custom_field_name] == $field['field_id'] ):?> selected <?php endif;?>><?php echo $field['field_label'];?></option>
							<?php endforeach;?>
						</select>
					</div>
				<?php endforeach;
				endif;
			}
			wp_die();
		}
		
		public function  mystickyelement_mailchimp_group() {
			global $wpdb;
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die(0); 
			}
			check_ajax_referer( 'mystickyelements', 'wpnonce' );
			
			$mailchimp_groups = myStickyelements_get_mailchimp_groups( $_POST['list_id'] );
			ob_start();
			?>
			<option value="">Select Groups</option>
			<?php if ( !empty($mailchimp_groups)) :?>
				<?php foreach( $mailchimp_groups as $key=>$groups ):?>
					<optgroup label="<?php echo esc_html($key);?>">
					<?php foreach($groups as $group ):?>
						<option value="<?php echo esc_html($group['id']);?>" <?php if ( in_array($group['id'] ,$mailchimp_group)){ echo "selected"; }?>><?php echo esc_html($group['name']);?></option>
					<?php endforeach;?>
					</optgroup>
				<?php endforeach;?>
			<?php endif;
			echo ob_get_clean();
			wp_die();
		}
		
		public function mystickyelement_widget_status() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die(0); 
			}
			check_ajax_referer( 'mystickyelements', 'wpnonce' );
			
			if ( isset($_POST['widget_id']) && $_POST['widget_id'] != '' && isset($_POST['widget_status']) && $_POST['widget_status'] != ''  ) {
				$stickyelements_widgets = get_option('stickyelements_widgets');
				
				$widget_id = $_POST['widget_id'];
				$widget_status = $_POST['widget_status'];
				$stickyelements_widgets[$widget_id]['status'] = $widget_status;				
				update_option( 'stickyelements_widgets',$stickyelements_widgets);
				
			}
			wp_die();
		}
		
		public function mystickyelement_widget_rename(){
			
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die(0); 
			}
			check_ajax_referer( 'mystickyelements', 'wpnonce' );
			
			if ( isset($_POST['widget_id']) && $_POST['widget_id'] != '' && isset($_POST['widget_rename']) && $_POST['widget_rename'] != ''  ) {
				
				$stickyelements_widgets = get_option('mystickyelements-widgets');
				$widget_id = $_POST['widget_id'];
				$widget_rename = $_POST['widget_rename'];
				
				$stickyelements_widgets[$widget_id] = $widget_rename;				
				update_option( 'mystickyelements-widgets' ,$stickyelements_widgets);
			}
			wp_die();
		}
		
		/*
		 * My Sticky Elements Create New Widget
		 *
		 */
		public function mystickyelements_admin_new_widget_page(){
			$upgarde_url = admin_url("admin.php?page=my-sticky-elements-upgrade");
			?>
			<div class="mystickyelement-new-widget-wrap">
				<?php include_once MYSTICKYELEMENTS_PRO_PATH . 'mystickyelements-widget.php';?>				
			</div>
			<?php
		}

		
		public function mystickyelement_delete_db_record(){
			global $wpdb;
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die(0); 
			}
			check_ajax_referer( 'mystickyelements', 'wpnonce' );
			
			if ( isset($_POST['ID']) && $_POST['ID'] != '' && wp_verify_nonce($_POST['delete_nonce'], "mysticky_elements_delete_nonce") ) {
				$ID = sanitize_text_field($_POST['ID']);
				$table = $wpdb->prefix . 'mystickyelement_contact_lists';
				$ID = self::sanitize_options($ID, "sql");
				$delete_sql = $wpdb->prepare("DELETE FROM {$table} WHERE id = %d",$ID);
				$delete = $wpdb->query($delete_sql);
			}

			if ( isset($_POST['all_leads']) && $_POST['all_leads'] == 1 && wp_verify_nonce($_POST['delete_nonce'], "mysticky_elements_delete_nonce")) {
				$table = $wpdb->prefix . 'mystickyelement_contact_lists';
				$delete = $wpdb->query("TRUNCATE TABLE $table");
			}
			wp_die();
		}

		public function mystickyelements_deactivate() {
			global $pagenow;

			if ( 'plugins.php' !== $pagenow ) {
				return;
			}

			include MYSTICKYELEMENTS_PRO_PATH . 'mystickyelements-deactivate-form.php';
		}

		public function mystickyelements_plugin_deactivate() {			
			global $current_user;
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die(0); 
			}
			check_ajax_referer( 'mystickyelements_deactivate_nonce', 'nonce' );
			$postData = $_POST;
			$errorCounter = 0;
			$response = array();
			$response['status'] = 0;
			$response['message'] = "";
			$response['valid'] = 1;
			if(!isset($postData['reason']) || empty($postData['reason'])) {
				$errorCounter++;
				$response['message'] = "Please provide reason";
			} else if (!isset($postData['nonce']) || empty($postData['nonce'])) {
				$response['message'] = __("Your request is not valid", "mystickyelements");
				$errorCounter++;
				$response['valid'] = 0;
			} else {
				$nonce = $postData['nonce'];
				if(!wp_verify_nonce($nonce, 'mystickyelements_deactivate_nonce')) {
					$response['message'] = __("Your request is not valid", "mystickyelements");
					$errorCounter++;
					$response['valid'] = 0;
				}
			}
			if($errorCounter == 0) {
				global $current_user;
				$plugin_info = get_plugin_data( MYSTICKYELEMENTS_PRO_PATH. 'mystickyelements.php');
				$postData = $_POST;
                $email = "none@none.none";

                if (isset($postData['email_id']) && !empty($postData['email_id']) && filter_var($postData['email_id'], FILTER_VALIDATE_EMAIL)) {
                    $email = $postData['email_id'];
                }
				$domain = site_url();
				$user_name = $current_user->first_name . " " . $current_user->last_name;
				
				$response['status'] = 1;

                /* sending message to Crisp */
                $post_message = array();

                $message_data = array();
                $message_data['key'] = "Plugin";
                $message_data['value'] = "MSE (Pro)";
                $post_message[] = $message_data;

                $message_data = array();
                $message_data['key'] = "Plugin Version";
                $message_data['value'] = $plugin_info['Version'];
                $post_message[] = $message_data;

                $message_data = array();
                $message_data['key'] = "Domain";
                $message_data['value'] = $domain;
                $post_message[] = $message_data;

                $message_data = array();
                $message_data['key'] = "Email";
                $message_data['value'] = $email;
                $post_message[] = $message_data;

                $message_data = array();
                $message_data['key'] = "WordPress Version";
                $message_data['value'] = esc_attr(get_bloginfo('version'));
                $post_message[] = $message_data;

                $message_data = array();
                $message_data['key'] = "PHP Version";
                $message_data['value'] = PHP_VERSION;
                $post_message[] = $message_data;

                $message_data = array();
                $message_data['key'] = "Message";
                $message_data['value'] = $postData['reason'];
                $post_message[] = $message_data;

                $api_params = array(
                    'domain' => $domain,
                    'email' => $email,
                    'url' => site_url(),
                    'name' => $user_name,
                    'message' => $post_message,
                    'plugin' => "MSE (Pro)",
                    'type' => "Uninstall",
                );

                /* Sending message to Crisp API */
                $crisp_response = wp_safe_remote_post("https://go.premio.io/crisp/crisp-send-message.php", array('body' => $api_params, 'timeout' => 15, 'sslverify' => true));

                if (is_wp_error($crisp_response)) {
                    wp_safe_remote_post("https://go.premio.io/crisp/crisp-send-message.php", array('body' => $api_params, 'timeout' => 15, 'sslverify' => false));
                }
			}
			echo json_encode($response);
			wp_die();
		}

		/*
		 * clear cache when any option is updated
		 *
		 */
		public function mystickyelements_clear_all_caches(){

			try {
				global $wp_fastest_cache;

				// if W3 Total Cache is being used, clear the cache
				if (function_exists('w3tc_flush_all')) {
					w3tc_flush_all();
				}
				/* if WP Super Cache is being used, clear the cache */
				if (function_exists('wp_cache_clean_cache')) {
					global $file_prefix, $supercachedir;
					if (empty($supercachedir) && function_exists('get_supercache_dir')) {
						$supercachedir = get_supercache_dir();
					}
					wp_cache_clean_cache($file_prefix);
				}

				if (class_exists('WpeCommon')) {
					//be extra careful, just in case 3rd party changes things on us
					if (method_exists('WpeCommon', 'purge_memcached')) {
						//WpeCommon::purge_memcached();
					}
					if (method_exists('WpeCommon', 'clear_maxcdn_cache')) {
						//WpeCommon::clear_maxcdn_cache();
					}
					if (method_exists('WpeCommon', 'purge_varnish_cache')) {
						//WpeCommon::purge_varnish_cache();
					}
				}

				if (method_exists('WpFastestCache', 'deleteCache') && !empty($wp_fastest_cache)) {
					$wp_fastest_cache->deleteCache();
				}
				if (function_exists('rocket_clean_domain')) {
					rocket_clean_domain();
					// Preload cache.
					if (function_exists('run_rocket_sitemap_preload')) {
						run_rocket_sitemap_preload();
					}
				}

				if (class_exists("autoptimizeCache") && method_exists("autoptimizeCache", "clearall")) {
					autoptimizeCache::clearall();
				}

				if (class_exists("LiteSpeed_Cache_API") && method_exists("autoptimizeCache", "purge_all")) {
					LiteSpeed_Cache_API::purge_all();
				}

				if ( class_exists( '\Hummingbird\Core\Utils' ) ) {

					$modules   = \Hummingbird\Core\Utils::get_active_cache_modules();
					foreach ( $modules as $module => $name ) {
						$mod = \Hummingbird\Core\Utils::get_module( $module );

						if ( $mod->is_active() ) {
							if ( 'minify' === $module ) {
								$mod->clear_files();
							} else {
								$mod->clear_cache();
							}
						}
					}
				}

			} catch (Exception $e) {
				return 1;
			}
		}

	}

}

class MyStickyElementLicense {

	public $license_data;

	public function __construct() {
		add_action('wp_ajax_sticky_element_activate_key',array( $this, 'activate_key'));
		add_action('wp_ajax_sticky_element_deactivate_key', array( $this, 'deactivate_key'));
	}

	public static function get_license_data($license_key = "") {
               update_option("sticky_element_license_key", "3p9d4z66z8wsv4e64nniobwdqcg9labu");
		return array('license' => 'valid','status' => 1, 'expires' => '09.06.2025');
		if(empty($license_key)) {
			$license_key = get_option("sticky_element_license_key");
		}

	
        $api_params = array(
            'edd_action' => 'check_license',
            'license' => $license_key,
            'item_id' => PRO_MY_STICKY_ELEMENT_ID,
            'url' => site_url()
        );

        /* Request to premio.io for checking Licence key */
        $response = wp_safe_remote_post(PRO_MY_STICKY_ELEMENT_API_URL, array('body' => $api_params, 'timeout' => 15, 'sslverify' => true));

        if (is_wp_error($response)) {
            $response = wp_safe_remote_post(PRO_MY_STICKY_ELEMENT_API_URL, array('body' => $api_params, 'timeout' => 15, 'sslverify' => false));
        }

        if (is_wp_error($response)) {
            return array();                                                     // return empty array if error in response
        } else {
            $response = json_decode(wp_remote_retrieve_body($response), true);  // return response
            if(!empty($response)) {
                set_transient("MSE_license_key_data", $response, DAY_IN_SECONDS);
            }
            return $response;
        }
	}

	public function activate_key() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(0); 
		}
		check_ajax_referer( 'sticky_element_activate_key_nonce', 'nonce' );
		$response_data = array(
			'status' => 0,
			'message' => "",
			'type' => 'activate'
		);

		if(!isset($_REQUEST['license_key']) || empty($_REQUEST['license_key'])) {
			$response_data['message'] = "Invalid request";
		} else if(!isset($_REQUEST['nonce']) || empty($_REQUEST['nonce'])) {
			$response_data['message'] = "Invalid request";
		} else if(!wp_verify_nonce($_REQUEST['nonce'], 'sticky_element_activate_key_nonce')){
			$response_data['message'] = "Invalid request";
		}
		if($response_data['message'] == "") {
			$response_data['message'] = "Invalid license key";
			$license_key = $_REQUEST['license_key'];

			$api_params = array(
                'edd_action' => 'activate_license',
                'license' => $license_key,
                'item_id' => PRO_MY_STICKY_ELEMENT_ID,
                'url' => site_url()
            );

            /* Request to premio.io for key activation */
            $response = wp_safe_remote_post(PRO_MY_STICKY_ELEMENT_API_URL, array('body' => $api_params, 'timeout' => 15, 'sslverify' => true));

            if (is_wp_error($response)) {
                $response = wp_safe_remote_post(PRO_MY_STICKY_ELEMENT_API_URL, array('body' => $api_params, 'timeout' => 15, 'sslverify' => false));
            }

            if (is_wp_error($response)) {
                $response = array();                                                     // return empty array if error in response
            } else {
                $response = json_decode(wp_remote_retrieve_body($response), true);  // return response
            }

			if (!empty($response)) {
			    delete_transient("MSE_license_key_data");
				if ($response['success']) {
					update_option("sticky_element_license_key", $license_key);
					$response_data['status'] = 1;
                    if ($response['license'] == "valid" || $response['expires'] == "expires") {
                        $response_data['message'] = "You have a lifetime license";
                    } else if ($response['license'] == "valid") {
                        $expire_on = $response['expires'];
						$response_data['message'] = "Your License key will expire on " . date("d F, Y", strtotime($expire_on)) . ".";
					} else if ($response['license'] == "expired") {
						$url = "https://go.premio.io/checkout/?edd_license_key=" . $license_key . "&download_id=" . PRO_MY_STICKY_ELEMENT_ID;
						$response_data['message'] = "Your License key has been expired on " . date("d F, Y", strtotime($expire_on)) . ". <a target='_blank' href='" . $url . "'>Click here</a> to renew";
					}
				}
			}
		}
		echo json_encode($response_data);
		die;
	}

	public function deactivate_key() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(0); 
		}
		check_ajax_referer( 'sticky_element_deactivate_key_nonce', 'nonce' );
		
		$response_data = array(
			'status' => 0,
			'message' => "",
			'type' => 'activate'
		);

		if(!isset($_REQUEST['license_key']) || empty($_REQUEST['license_key'])) {
			$response_data['message'] = "Invalid request";
		} else if(!isset($_REQUEST['nonce']) || empty($_REQUEST['nonce'])) {
			$response_data['message'] = "Invalid request";
		} else if(!wp_verify_nonce($_REQUEST['nonce'], 'sticky_element_deactivate_key_nonce')){
			$response_data['message'] = "Invalid request";
		}
		if($response_data['message'] == "") {
			$response_data['message'] = "Invalid license key";
			$license_key = $_REQUEST['license_key'];
			if (empty($license_key)) {
				$license_key = get_option("sticky_element_license_key");
			}

            $api_params = array(
                'edd_action' => 'deactivate_license',
                'license' => $license_key,
                'item_id' => PRO_MY_STICKY_ELEMENT_ID,
                'url' => site_url()
            );

            /* Request to premio.io for key deactivation */
            $response = wp_safe_remote_post(PRO_MY_STICKY_ELEMENT_API_URL, array('body' => $api_params, 'timeout' => 15, 'sslverify' => true));

            if (is_wp_error($response)) {
                $response = wp_safe_remote_post(PRO_MY_STICKY_ELEMENT_API_URL, array('body' => $api_params, 'timeout' => 15, 'sslverify' => false));
            }

            if (is_wp_error($response)) {
                $response = array();                                                     // return empty array if error in response
            } else {
                $response = json_decode(wp_remote_retrieve_body($response), true);  // return response
            }

			if (!empty($response)) {
                delete_transient("MSE_license_key_data");
				update_option("sticky_element_license_key", "");
				$response_data['status'] = 1;
				if ($response['license'] == "failed" || $response['license'] == "deactivated") {
					$response_data['message'] = "Your License key has been deactivated";
				}
			}
		}
		echo json_encode($response_data);
		die;
	}

}
$MyStickyElementLicense = new MyStickyElementLicense();

if( is_admin() ) {
    $my_settings_page = new MyStickyElementsPage_pro();
    include_once "class-review-box.php";
    include_once "license-key-box.php";
}
