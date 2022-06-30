<?php
if (!class_exists('MyStickyElementsFrontPage_pro')) {
    class MyStickyElementsFrontPage_pro {
        public function __construct() {
            add_action('wp_enqueue_scripts', array($this, 'mystickyelements_enqueue_script'), 9999 );
            add_action('wp_footer', array($this, 'mystickyelement_element_footer'), 999 );

            add_action('wp_ajax_mystickyelements_contact_form', array($this, 'mystickyelements_contact_form'));
            add_action('wp_ajax_nopriv_mystickyelements_contact_form', array($this, 'mystickyelements_contact_form'));
        }

		public function mystickyelements_google_fonts_url() {
			$elements_widgets = get_option( 'mystickyelements-widgets' );
			if ( empty($elements_widgets) || $elements_widgets == '' ){
				$elements_widgets[] = 'default';
			}
			$default_fonts = array('Arial', 'Tahoma', 'Verdana', 'Helvetica', 'Times New Roman', 'Trebuchet MS', 'Georgia', 'Open Sans Hebrew');
			$fonts_url        = '';
			$fonts            = array();
			$font_args        = array();
			$base_url         =  "https://fonts.googleapis.com/css";

			foreach ( $elements_widgets as $key=>$value ) {
				$element_widget_no = '';
				if ($key != 0 ) {
					$element_widget_no = '-' . $key;
				}
				$general_settings = get_option( 'mystickyelements-general-settings' . $element_widget_no );
				if ( isset($general_settings['font_family']) && $general_settings['font_family'] != '' && !in_array( $general_settings['font_family'], $default_fonts) ) {
					$fonts['family'][$general_settings['font_family']] = $general_settings['font_family'] . ':400,500,600,700';
				} else {
					$fonts['family']['Poppins'] = 'Poppins:400,500,600,700';
				}
			}

			/* Prepapre URL if font family defined. */
			if( !empty( $fonts['family'] ) ) {
				/* format family to string */
				if( is_array($fonts['family']) ){
					$fonts['family'] = implode( '|', $fonts['family'] );
				}
				$font_args['family'] = urlencode( trim( $fonts['family'] ) );
				if( !empty( $fonts['subsets'] ) ){
					/* format subsets to string */
					if( is_array( $fonts['subsets'] ) ){
						$fonts['subsets'] = implode( ',', $fonts['subsets'] );
					}
					$font_args['subsets'] = urlencode( trim( $fonts['subsets'] ) );
				}
				$fonts_url = add_query_arg( $font_args, $base_url );
			}
			return esc_url_raw( $fonts_url );
		}

		public function mystickyelements_enqueue_script() {
			$is_min = ( !WP_DEBUG ) ? '.min' : '';
			$contact_form = get_option('mystickyelements-contact-form');
            $general_settings = get_option('mystickyelements-general-settings');

			wp_enqueue_style( 'mystickyelements-google-fonts', $this->mystickyelements_google_fonts_url(),array(), PRO_MY_STICKY_ELEMENT_VERSION );

            wp_enqueue_style('font-awesome-css', plugins_url('/css/font-awesome.min.css', __FILE__), array(), PRO_MY_STICKY_ELEMENT_VERSION );
            wp_enqueue_style('mystickyelements-front-css', plugins_url('/css/mystickyelements-front'. $is_min .'.css', __FILE__), array(), PRO_MY_STICKY_ELEMENT_VERSION);

            wp_enqueue_script('mystickyelements-cookie-js', plugins_url('/js/jquery.cookie.js', __FILE__), array('jquery'), PRO_MY_STICKY_ELEMENT_VERSION, true);
            wp_enqueue_script('mystickyelements-fronted-js', plugins_url('/js/mystickyelements-fronted'. $is_min .'.js', __FILE__), array('jquery'), PRO_MY_STICKY_ELEMENT_VERSION, true);

            $locale_settings = array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('mystickyelements'),
				'google_analytics'	=> (isset($general_settings['google_analytics']) && $general_settings['google_analytics'] == 1)? true : false,
            );
            wp_localize_script('mystickyelements-fronted-js', 'mystickyelements', $locale_settings);

        }

        public function mystickyelement_element_footer() {
			global $wp;
			$elements_widgets = get_option( 'mystickyelements-widgets' );
			if ( empty($elements_widgets) || $elements_widgets == '' ){
				$elements_widgets[] = 'default';
			}
            $social_channels_lists = mystickyelements_social_channels();
			$stickyelements_widgets = get_option('stickyelements_widgets');
			$page_options = array();
			if ( !empty($elements_widgets)):
				$widget_status = 0;
				foreach( $elements_widgets as $ekey=>$evalue):
					$element_widget_no = '';
					if ($ekey != 0 ) {
						$element_widget_no = '-' . $ekey;
					}
					if ( !isset( $stickyelements_widgets[$ekey]['status'])) {
						$widget_status = 1;
					}
					if ( isset( $stickyelements_widgets[$ekey]['status']) ) {
						$widget_status = $stickyelements_widgets[$ekey]['status'];
					}
					
					if ( $widget_status == 0 ) {
						continue;
					}
					
					$general_settings = get_option('mystickyelements-general-settings' . $element_widget_no );
					$page_rule_options = (isset($general_settings['page_settings'])) ? $general_settings['page_settings'] : array();
					$page_rule_flag = 1;       // for page Rule contain
					$page_options[$ekey] = 1;

					/* Unset Page rule when value empty */
					if ( !empty($page_rule_options) && is_array($page_rule_options) ) {
						foreach( $page_rule_options as $key=>$value ) {
							if ( trim($value['value']) == '' ) {
								unset($page_rule_options[$key]);
							}
						}
					}
					/* checking for page visibility settings */
					if (!empty($page_rule_options) && is_array($page_rule_options)) {

						$url = strtolower($_SERVER['REQUEST_URI']);
						$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" .$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
						$site_url = site_url("/");
						$site_url = get_option('siteurl');
						$request_url = substr($link, strlen($site_url));
						$url = trim($request_url, "/");
						$page_rule_flag = 0;
						$page_options[$ekey] = 0;
						$total_option = count($page_rule_options);

						$options = 0;
						/* checking for each page options */
						foreach ($page_rule_options as $option) {
							$key = $option['option'];
							$value = trim(strtolower($option['value']));
							if ($key != '' && $value != '') {
								if($option['shown_on'] == "show_on") {
									$value = trim($value, "/");
									switch ($key) {
										case 'page_contains':
											$index = strpos($url, $value);
											if($index !== false) {
												$page_rule_flag = 1;
												$page_options[$ekey] = 1;
											}
											break;
										case 'page_has_url':
											if ($url === $value) {
												$page_rule_flag = 1;
												$page_options[$ekey] = 1;
											}
											break;
										case 'page_start_with':
											$length = strlen($value);
											$result = substr($url, 0, $length);
											if ($result == $value) {
												$page_rule_flag = 1;
												$page_options[$ekey] = 1;
											}
											break;
										case 'page_end_with':
											$length = strlen($value);
											$result = substr($url, (-1) * $length);
											if ($result == $value) {
												$page_rule_flag = 1;
												$page_options[$ekey] = 1;
											}
											break;
									}
								} else {
									$options++;
								}
							}
						}

						if($total_option == $options) {
							$page_rule_flag = 1;
							$page_options[$ekey] = 1;
						}

						foreach ($page_rule_options as $option) {
							$key = $option['option'];
							$value = trim(strtolower($option['value']));
							if ($key != '' && $option['shown_on'] == "not_show_on" && $value != '') {
								$value = trim($value, "/");
								switch ($key) {
									case 'page_contains':
										$index = strpos($url, $value);
										if($index !== false) {
											 $page_rule_flag = 0;
											 $page_options[$ekey] = 0;
										}
										break;
									case 'page_has_url':
										if ($url === $value) {
											$page_rule_flag = 0;
											$page_options[$ekey] = 0;
										}
										break;
									case 'page_start_with':
										$length = strlen($value);
										$result = substr($url, 0, $length);
										if ($result == $value) {
											$page_rule_flag = 0;
											$page_options[$ekey] = 0;
										}
										break;
									case 'page_end_with':
										$length = strlen($value);
										$result = substr($url, (-1) * $length);
										if ($result == $value) {
											$page_rule_flag = 0;
											$page_options[$ekey] = 0;
										}
										break;
								}
							}
						}

					}
				endforeach;/* */
			endif; /* */

			$element_widgetno = '';
			$widget_name = '';
			if ( !empty($page_options)) {
				$element_widget_no = '';

				foreach($page_options as $key=>$value ) {
					$element_widget_no = '';
					if ( $key != 0 ) {
						$element_widget_no = "-" . $key;
					}
					$general_settings = get_option('mystickyelements-general-settings' . $element_widget_no );
					$country_list = ( isset($general_settings['countries_list']) && $general_settings['countries_list'] != '' ) ? $general_settings['countries_list'] : array() ;
				}
				$current_country = '';
				if ( !empty($country_list)) {

					if( !isset( $_COOKIE['country_data'] ) ) {
						?>
						<script>
						(function( $ ) {
							'use strict';
							$(document).ready(function(){
								var $ipurl = 'https://www.cloudflare.com/cdn-cgi/trace';
									$.get($ipurl, function(cloudflaredata) {
										var currentCountry = cloudflaredata.match("loc=(.*)");
										document.cookie = "country_data=" + currentCountry[1];
									});
							});
						})( jQuery );
						</script>
						<?php
					}
					if( !isset( $_COOKIE['country_data'] ) ) {
						$url = "https://www.cloudflare.com/cdn-cgi/trace";
						$data = wp_remote_get( $url);
						$data_code = explode('loc=', $data['body']);
						$data_code = explode( 'tls=', $data_code[1]);
						$current_country = trim($data_code[0]);
						//setcookie( 'country_data', $current_country );
					} else {
						$current_country = $_COOKIE['country_data'];
					}

				}
				$page_rule_flag = 1;
				$qtag_apper = 1;
				foreach($page_options as $key=>$value ) {
					if ( $value == 1) {
						$element_widget_no = '';
						if ( $key != 0 ) {
							$element_widget_no = "-" . $key;
							$widget_name = $elements_widgets[$key];
						}
						$page_rule_flag = 0;
						$element_widgetno = 1;
						$general_settings = get_option('mystickyelements-general-settings' . $element_widget_no );
						$country_list = ( isset($general_settings['countries_list']) && $general_settings['countries_list'] != '' ) ? $general_settings['countries_list'] : array() ;

						if( !empty($country_list) ) {
							if ( in_array($current_country, $country_list)) {
								$page_rule_flag = 1;
								//break;
							} else {
								continue;
							}
						} else {
							$page_rule_flag = 1;
							//break;
						}
					} else {
						$general_settings = get_option('mystickyelements-general-settings' . $element_widget_no );
						$country_list = ( isset($general_settings['countries_list']) && $general_settings['countries_list'] != '' ) ? $general_settings['countries_list'] : array() ;

						if( !empty($country_list) ) {
							if (!in_array($current_country, $country_list)) {
								$page_rule_flag = 0;
								$element_widgetno = '';
							}
						}  else {
							$page_rule_flag = 0;
							$element_widgetno = '';
						}
					}

					/*if ( $element_widgetno == '' ) {
						return;
					}*/

					$contact_form = get_option('mystickyelements-contact-form' . $element_widget_no );
					$social_channels = get_option('mystickyelements-social-channels' . $element_widget_no );
					$social_channels_tabs = get_option('mystickyelements-social-channels-tabs' . $element_widget_no );
					$general_settings = get_option('mystickyelements-general-settings' . $element_widget_no );

					/* Traffic Source Roles */
					$is_traffic_source = $this->getVisitorTrafficSource($general_settings, $element_widget_no);
					if ( !$is_traffic_source ) {
						continue;
					}

					/* Days & hours Rules */
					$is_days_hours = $this->getDaysHoursTime($general_settings);
					if ( !$is_days_hours ) {
						continue;
					}

					if( $page_rule_flag == 1 ) {
						if (!isset($contact_form['enable']) && !isset($social_channels['enable'])) {
							continue;
						}

						$contact_field = get_option( 'mystickyelements-contact-field' . $element_widget_no );
						if ( empty( $contact_field ) ) {
							$contact_field = array( 'name', 'phone', 'email', 'message', 'dropdown' );
						}

						$contact_form_class = '';
						if (isset($contact_form['desktop']) && $contact_form['desktop'] == 1) {
							$contact_form_class .= ' element-desktop-on';
						}
						if (isset($contact_form['mobile']) && $contact_form['mobile'] == 1) {
							$contact_form_class .= ' element-mobile-on';
						}

						$close_after = '';
						if ( isset($contact_form['close_form_automatic']) && $contact_form['close_form_automatic'] == 1 && isset($contact_form['close_after']) ) {
							$close_after = 'data-close-after="' . $contact_form['close_after'] . '"';
						}

						if ( !isset($general_settings['position_mobile']) ) {
							$general_settings['position_mobile'] = 'left';
						}

						$minimize_class = '';
						if ( isset($general_settings['minimize_tab']) && $general_settings['minimize_tab'] == 1 ) {
							if ( !isset($_COOKIE['minimize_desktop_' . $key]) && isset($general_settings['minimize_desktop']) && $general_settings['minimize_desktop'] == 'desktop' && !wp_is_mobile() ) {
								$minimize_class = 'element-minimize';
							} elseif ( !isset($_COOKIE['minimize_mobile_' . $key]) && isset($general_settings['minimize_mobile']) && $general_settings['minimize_mobile'] == 'mobile' && wp_is_mobile() ) {
								$minimize_class = 'element-minimize';
							} else if ( isset($_COOKIE['minimize_desktop_' . $key]) && $_COOKIE['minimize_desktop_' . $key] == 'minimize' && !wp_is_mobile() ) {
								$minimize_class = 'element-minimize';
							} elseif (isset($_COOKIE['minimize_mobile_' . $key]) && $_COOKIE['minimize_mobile_' . $key] == 'minimize' && wp_is_mobile()) {
								$minimize_class = 'element-minimize';
							}
						}else {
							$minimize_class = 'no-minimize';
						}


						/* Change Open Tabs click to hover on Mobile device */
						if ( $general_settings['open_tabs_when'] == 'click' && wp_is_mobile() ) {
							$general_settings['open_tabs_when'] = 'hover';
						}
						$general_settings['widget-size'] = (isset($general_settings['widget-size']) && $general_settings['widget-size']!= '') ? $general_settings['widget-size'] : 'medium';

						$general_settings['mobile-widget-size'] = (isset($general_settings['mobile-widget-size']) && $general_settings['mobile-widget-size']!= '') ? $general_settings['mobile-widget-size'] : 'medium';

						$general_settings['entry-effect'] = (isset($general_settings['entry-effect']) && $general_settings['entry-effect']!= '') ? $general_settings['entry-effect'] : 'slide-in';
						$general_settings['templates'] = (isset($general_settings['templates']) && $general_settings['templates']!= '') ? $general_settings['templates'] : 'default';
						$mystickyelements_widget_count = $key;
						$mystickyelements_class = array();
						$mystickyelements_class[] = 'mystickyelements-fixed';
						$mystickyelements_class[] = 'mystickyelements-fixed-widget-' . $mystickyelements_widget_count;
						$mystickyelements_class[] = 'mystickyelements-position-' . $general_settings['position'];
						$mystickyelements_class[] = 'mystickyelements-position-screen-' . @$general_settings['position_on_screen'];
						$mystickyelements_class[] = 'mystickyelements-position-mobile-' . $general_settings['position_mobile'];
						$mystickyelements_class[] = 'mystickyelements-on-' . $general_settings['open_tabs_when'];
						$mystickyelements_class[] = 'mystickyelements-size-' . $general_settings['widget-size'];
						$mystickyelements_class[] = 'mystickyelements-mobile-size-' . $general_settings['mobile-widget-size'];
						$mystickyelements_class[] = 'mystickyelements-entry-effect-' . $general_settings['entry-effect'];
						$mystickyelements_class[] = 'mystickyelements-templates-' . $general_settings['templates'];

						$mystickyelements_classes = join( ' ', $mystickyelements_class );
						$general_settings['custom_position'] = ( isset($general_settings['custom_position'])) ? $general_settings['custom_position'] : '';
						$general_settings['custom_position_mobile'] = ( isset($general_settings['custom_position_mobile'])) ? $general_settings['custom_position_mobile'] : '';
						$country_list = ( isset($general_settings['countries_list']) && $general_settings['countries_list'] != '' ) ? $general_settings['countries_list'] : array() ;
						$mystickyelement_country_list = implode( ',', $country_list );

						if (isset($general_settings['form_open_automatic']) && $general_settings['form_open_automatic'] == 1 && !isset($_COOKIE['closed_contactform_'. $key])) {
							$contact_form_class .= ' elements-active';
						}
						?>
						<input type="hidden" class="mystickyelement-country-list-hidden" value="<?php echo $mystickyelement_country_list; ?>" />
						<div class="<?php echo esc_attr($mystickyelements_classes);?>" <?php if (isset($contact_form['direction']) && $contact_form['direction'] == 'RTL') : ?> dir="rtl" <?php endif; ?> data-custom-position="<?php echo $general_settings['custom_position'] ?>" data-custom-position-mobile="<?php echo $general_settings['custom_position_mobile'] ?>" data-mystickyelement-widget="<?php echo $mystickyelements_widget_count; ?>">
							<div class="mystickyelement-lists-wrap">
								<ul class="mystickyelements-lists <?php echo esc_attr('mysticky' . $minimize_class);?>" data-mystickyelement-widget="<?php echo $mystickyelements_widget_count; ?>">
									<?php if ( isset($general_settings['minimize_tab']) && $general_settings['minimize_tab'] == 1 ):?>
										<li class="mystickyelements-minimize <?php echo esc_attr($minimize_class);?>" data-mystickyelement-widget="<?php echo $mystickyelements_widget_count; ?>">
											<span class="mystickyelements-minimize minimize-position-<?php echo esc_attr($general_settings['position'])?> minimize-position-mobile-<?php echo esc_attr($general_settings['position_mobile'])?>" <?php if (isset($general_settings['minimize_tab_background_color']) && $general_settings['minimize_tab_background_color'] != ''): ?>style="background: <?php echo esc_attr($general_settings['minimize_tab_background_color']); ?>" <?php endif;
											?>>
											<?php
											if ( !isset($_COOKIE['minimize_desktop_' . $mystickyelements_widget_count]) && isset($general_settings['minimize_desktop']) && $general_settings['minimize_desktop'] == 'desktop' && !wp_is_mobile() ) :
												echo "<i class='fas fa-envelope'></i>";
											elseif ( !isset($_COOKIE['minimize_mobile_' . $mystickyelements_widget_count]) && isset($general_settings['minimize_mobile']) && $general_settings['minimize_mobile'] == 'mobile' && wp_is_mobile() ) :
												echo "<i class='fas fa-envelope'></i>";
											elseif ( $general_settings['position'] == 'left' && !wp_is_mobile() ) :
												echo  ($minimize_class == "" ) ? "&larr;" : "&rarr;";
											elseif ( $general_settings['position'] == 'right' && !wp_is_mobile() ) :
												echo  ($minimize_class == "" ) ? "&rarr;" : "&larr;";
											elseif ( $general_settings['position'] == 'bottom' && !wp_is_mobile() ) :
												echo  ($minimize_class == "" ) ? "&darr;" : "&uarr;";
											elseif ( $general_settings['position_mobile'] == 'left' && wp_is_mobile() ) :
												echo  ($minimize_class == "" ) ? "&larr;" : "&rarr;" ;
											elseif ( $general_settings['position_mobile'] == 'right' && wp_is_mobile() ) :
												echo  ($minimize_class == "" ) ? "&rarr;" : "&larr;";
											elseif ( $general_settings['position_mobile'] == 'bottom' && wp_is_mobile() ) :
												echo  ($minimize_class == "" ) ? "&darr;" : "&uarr;";
											elseif ( $general_settings['position_mobile'] == 'top' && wp_is_mobile() ) :
												echo  ($minimize_class == "" ) ? "&uarr;" : "&darr;";
											endif;
											?>
											</span>
										</li>
									<?php endif;?>

									<?php if (isset($contact_form['enable']) && $contact_form['enable'] == 1): ?>
										<li id="mystickyelements-contact-form" class="mystickyelements-contact-form <?php echo esc_attr($contact_form_class); ?>" <?php if (isset($contact_form['direction']) && $contact_form['direction'] == 'RTL') : ?> dir="rtl" <?php endif; ?>>
											<?php
											$contact_form_text_class = '';
											if ($contact_form['text_in_tab'] == '') {
												$contact_form_text_class = "mystickyelements-contact-notext";
											}?>
											<span class="mystickyelements-social-icon <?php echo $contact_form_text_class?>" style="background-color: <?php echo esc_attr($contact_form['tab_background_color']); ?>; color: <?php echo esc_attr($contact_form['tab_text_color']); ?>;" data-mystickyelement-widget="<?php echo $key; ?>"><i class="far fa-envelope"></i><?php echo esc_html($contact_form['text_in_tab']); ?></span>
											<?php
											$submit_button_text = ($contact_form['submit_button_text'] != '') ? $contact_form['submit_button_text'] : 'Submit';
											$submit_button_style = ($contact_form['submit_button_background_color'] != '') ? "background-color: " . $contact_form['submit_button_background_color'] . ";" : '';
											$submit_button_style .= ($contact_form['submit_button_text_color'] != '') ? "color:" . $contact_form['submit_button_text_color'] . ";" : '';

											$heading_color = ( isset($contact_form['headine_text_color']) && $contact_form['headine_text_color'] != '') ? "color: " . $contact_form['headine_text_color'] . ";" : ( ($contact_form['submit_button_background_color'] != '') ? "color: " . $contact_form['submit_button_background_color'] . ";" : 'color:#7761DF;' );

											$heading_color .= (isset($contact_form['form_bg_color']) && $contact_form['form_bg_color'] != '') ? "background-color:". $contact_form['form_bg_color'] : '';

											$contact_form['name_value'] = ($contact_form['name_value'] != '') ? $contact_form['name_value'] : esc_html__('Name', 'mystickyelements');
											$contact_form['phone_value'] = ($contact_form['phone_value'] != '') ? $contact_form['phone_value'] : esc_html__('Phone', 'mystickyelements');
											$contact_form['email_value'] = ($contact_form['email_value'] != '') ? $contact_form['email_value'] : esc_html__('Email', 'mystickyelements');
											$contact_form['message_value'] = ($contact_form['message_value'] != '') ? $contact_form['message_value'] : esc_html__('Message', 'mystickyelements');

											?>
											<div class="element-contact-form" style="background-color: <?php echo ( isset($contact_form['form_bg_color']))? $contact_form['form_bg_color'] : '#ffffff'; ?>">
												<?php if( isset( $contact_form['contact_title_text'] ) && $contact_form['contact_title_text'] != '' ) {
													$contact_title_text = $contact_form['contact_title_text'];
												} else {
													$contact_title_text = "Contact Form";
												} ?>
												<h3 style="<?php echo esc_attr($heading_color); ?>">
													<?php echo $contact_title_text; ?>
													<a href="javascript:void(0);" class="element-contact-close" data-mystickyelement-widget="<?php echo $key; ?>"><i class="fas fa-times"></i></a>
												</h3>
												<?php if ( isset( $contact_form['textblock_checkbox'] ) && $contact_form['textblock_checkbox'] == 'yes' && isset( $contact_form['textblock'] ) && $contact_form['textblock'] == 'textblock' ) { ?>
													<div class="stickyelements-textblock-content">
														<?php echo wpautop( isset($contact_form['textblock_text'])? stripslashes($contact_form['textblock_text']) : "" ); ?>
													</div>
												<?php } ?>
												<form id="stickyelements-form" class="stickyelements-form" action="" method="post" autocomplete="off" enctype="multipart/form-data" data-mystickyelement-widget="<?php echo $key; ?>" <?php echo $close_after;?>>
													<input type="hidden" name="action" value="mystickyelements_contact_form" />
													<input type="hidden" name="security" value="<?php echo wp_create_nonce('mystickyelements');?>" />
													<?php foreach ( $contact_field as $key=>$value ) :
														$val = $value;
														if ( !is_numeric($key) && $key == 'custom_fields' ) {
															$val = 'custom_fields';
														}
														if ( isset($value['custom_fields']) && is_array($value['custom_fields']) ) {
															$val = 'custom_fields';
															$value = $value['custom_fields'];
														}
														switch ( $val ) {
															case 'name' :

													if (isset($contact_form['name']) && $contact_form['name'] == 1): ?>
														<input
															class="<?php if (isset($contact_form['name_require']) && $contact_form['name_require'] == 1): ?> required<?php endif; ?>"
															type="text" id="contact-form-name" name="contact-form-name" value=""
															placeholder="<?php echo esc_attr($contact_form['name_value']); if (isset($contact_form['name_require']) && $contact_form['name_require'] == 1): echo esc_html__("*", 'mystickyelements'); endif;?>"  <?php if (isset($contact_form['name_require']) && $contact_form['name_require'] == 1): ?> required<?php endif; ?> autocomplete="off"/>
													<?php endif;
															break;
														case 'phone' :

													if (isset($contact_form['phone']) && $contact_form['phone'] == 1): ?>
														<input
															class="<?php if (isset($contact_form['phone_require']) && $contact_form['phone_require'] == 1): ?> required<?php endif; ?>"
															type="tel" id="contact-form-phone" name="contact-form-phone" value=""
															placeholder="<?php echo esc_attr($contact_form['phone_value']); if (isset($contact_form['phone_require']) && $contact_form['phone_require'] == 1): echo esc_html__("*", 'mystickyelements'); endif;?>" <?php if (isset($contact_form['phone_require']) && $contact_form['phone_require'] == 1): ?> required <?php endif; ?> autocomplete="off"/>
													<?php endif;
															break;
														case 'email' :

													if (isset($contact_form['email']) && $contact_form['email'] == 1): ?>
														<input
															class="email <?php if (isset($contact_form['email_require']) && $contact_form['email_require'] == 1): ?> required<?php endif; ?>"
															type="email" id="contact-form-email" name="contact-form-email" value=""
															placeholder="<?php echo esc_attr($contact_form['email_value']); if (isset($contact_form['email_require']) && $contact_form['email_require'] == 1): echo esc_html__("*", 'mystickyelements'); endif;?>" <?php if (isset($contact_form['email_require']) && $contact_form['email_require'] == 1): ?> required <?php endif; ?> autocomplete="off"/>
													<?php endif;
															break;
														case 'message' :

													if (isset($contact_form['message']) && $contact_form['message'] == 1): ?>
														<textarea
															class="<?php if (isset($contact_form['message_require']) && $contact_form['message_require'] == 1): ?> required<?php endif; ?>"
															id="contact-form-message" name="contact-form-message"
															placeholder="<?php echo esc_attr($contact_form['message_value']); if (isset($contact_form['message_require']) && $contact_form['message_require'] == 1): echo esc_html__("*", 'mystickyelements'); endif;?>" <?php if (isset($contact_form['message_require']) && $contact_form['message_require'] == 1): ?> required <?php endif; ?>></textarea>
													<?php endif;
															break;
														case 'dropdown' :
														if (isset($contact_form['dropdown']) && $contact_form['dropdown'] == 1): ?>
														<select id="contact-form-dropdown" name="contact-form-dropdown" class="<?php if (isset($contact_form['dropdown_require']) && $contact_form['dropdown_require'] == 1): ?> required<?php endif; ?>" <?php if (isset($contact_form['dropdown_require']) && $contact_form['dropdown_require'] == 1): ?> required <?php endif; ?>>

															<option value="" disabled selected><?php echo esc_html( $contact_form['dropdown-placeholder'] );  if (isset($contact_form['dropdown_require']) && $contact_form['dropdown_require'] == 1): echo esc_html__("*", 'mystickyelements'); endif;?></option>
															<?php foreach( $contact_form['dropdown-option'] as $option ):
																if ( $option == '' ) {
																	continue;
																}
																?>
																<option value="<?php echo esc_html($option);?>"><?php echo esc_html($option);?></option>
															<?php endforeach;?>
														</select>

													<?php endif;
															break;
														case 'custom_fields':
															foreach ( $value as $cutom_field ) {
																$cutom_field_value = $contact_form['custom_fields'][$cutom_field];
																$custom_field_name = sanitize_title($cutom_field_value['custom_field_name']);

																if ( isset($cutom_field_value['custom_field']) && $cutom_field_value['custom_field'] == 1) {
																	$field_dropdown = ( isset($cutom_field_value['field_dropdown']) && $cutom_field_value['field_dropdown'] != '' ) ? $cutom_field_value['field_dropdown'] : 'text';
																	if( $field_dropdown != 'textarea' && $field_dropdown != 'dropdown' ) {
																		$file_accept = "";
																		if(isset($cutom_field_value['custom_field_value']) && $cutom_field_value['custom_field_value'] != '' ) {
																			$field_name = stripslashes($cutom_field_value['custom_field_value']);
																		} else {

																			if ( $field_dropdown == 'text' ){
																				$field_name = "Enter your message";
																			} elseif ( $field_dropdown == 'number' ) {
																				$field_name = "Enter a number";
																			} elseif ( $field_dropdown == 'url' ) {
																				$field_name = "Enter your website";
																			} elseif ( $field_dropdown == 'date' ) {
																				$field_name = "mm/dd/yyyy";
																			} elseif ( $field_dropdown == 'file' ) {
																				$field_name = "Select File";
																				$file_accept = ".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.ppt,.pptx,.pps,.ppsx,.odt,.xls,.xlsx,.mp3,.mp4,.wav,.mpg,.avi,.mov,.wmv,.3gp,.ogv";
																			}
																		}
																		if( $field_dropdown == 'file' ) {  ?>
																			<label class="contact-form-label"><?php echo  esc_attr($cutom_field_value['custom_field_name']);?><?php if (isset($cutom_field_value['custom_field_require']) && $cutom_field_value['custom_field_require'] == 1): echo esc_html__("*", 'mystickyelements'); endif; ?></label>
																		<?php }
																		?>
																		<input type="<?php echo $field_dropdown; ?>" data-field="<?php echo esc_attr($cutom_field)?>" id="contact-form-<?php echo esc_attr($custom_field_name)?>" name="contact-form[custom_field][<?php echo esc_attr($cutom_field)?>]" value="" placeholder="<?php echo  esc_attr($field_name);?><?php if (isset($cutom_field_value['custom_field_require']) && $cutom_field_value['custom_field_require'] == 1): echo esc_html__("*", 'mystickyelements'); endif; ?>" <?php if (isset($cutom_field_value['custom_field_require']) && $cutom_field_value['custom_field_require'] == 1): ?> required <?php endif; ?>  class="<?php if (isset($cutom_field_value['custom_field_require']) && $cutom_field_value['custom_field_require'] == 1): ?> required<?php endif; ?>"  autocomplete="off" accept="<?php echo $file_accept; ?>" />
																	<?php } elseif( $field_dropdown == 'textarea' ) {
																			$field_name = (isset($cutom_field_value['custom_field_value']) && $cutom_field_value['custom_field_value'] != '' )? $cutom_field_value['custom_field_value'] : "Enter Your message";
																		?>
																		<textarea id="contact-form-<?php echo esc_attr($custom_field_name)?>" name="contact-form[custom_field][<?php echo esc_attr($cutom_field)?>]" placeholder="<?php echo  esc_attr($field_name);?><?php if (isset($cutom_field_value['custom_field_require']) && $cutom_field_value['custom_field_require'] == 1): echo esc_html__("*", 'mystickyelements'); endif; ?>" <?php if (isset($cutom_field_value['custom_field_require']) && $cutom_field_value['custom_field_require'] == 1): ?> required <?php endif; ?>  class="<?php if (isset($cutom_field_value['custom_field_require']) && $cutom_field_value['custom_field_require'] == 1): ?> required<?php endif; ?>"  autocomplete="off"></textarea>
																	<?php } else { ?>
																		<select id="contact-form-<?php echo esc_attr($custom_field_name)?>" name="contact-form[custom_field][<?php echo esc_attr($cutom_field)?>]" class="<?php if (isset($cutom_field_value['custom_field_require']) && $cutom_field_value['custom_field_require'] == 1): ?> required<?php endif; ?>" <?php if (isset($cutom_field_value['custom_field_require']) && $cutom_field_value['custom_field_require'] == 1): ?> required <?php endif; ?>>
																			<option value="" disabled selected><?php echo esc_html( $cutom_field_value['dropdown-placeholder'] );?><?php if (isset($cutom_field_value['custom_field_require']) && $cutom_field_value['custom_field_require'] == 1): echo esc_html__("*", 'mystickyelements'); endif; ?></option>
																			<?php foreach( $cutom_field_value['dropdown-option'] as $option ):
																				if ( $option == '' ) {
																					continue;
																				}
																				?>
																				<option value="<?php echo esc_html($option);?>"><?php echo esc_html($option);?></option>
																			<?php endforeach;?>
																		</select>
																	<?php } ?>
																<?php
																}/* End IF*/
															} /* End Foreach*/
															break;
														} /* End Switch case */
													endforeach;

													if ( isset( $contact_form['recaptcha_checkbox'] ) && $contact_form['recaptcha_checkbox'] == 'yes') {
														$recaptcha_site_key = ( isset($contact_form['recaptcha_site_key'])) ? $contact_form['recaptcha_site_key'] : '';
														$recaptcha_secrete_key = ( isset($contact_form['recaptcha_secrete_key'])) ? $contact_form['recaptcha_secrete_key'] : '';
														$invisible_recaptcha_checkbox_class = '';
														if ( isset( $contact_form['invisible_recaptcha_checkbox'] ) && $contact_form['invisible_recaptcha_checkbox'] == 'yes') {
															echo "<style>.grecaptcha-badge { visibility: hidden;}</style>";
															$invisible_recaptcha_checkbox_class = 'mystickyelement-invisible-recaptcha';
														} ?>
														<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $recaptcha_site_key; ?>"></script>
														<script>
															function getRecaptcha() {
																grecaptcha.ready(function () {
																	grecaptcha.execute('<?php echo $recaptcha_site_key; ?>', { action: 'submit' }).then(function (token) {
																		//var recaptchaResponse = document.getElementById('g-recaptcha-response');
																		var recaptchaResponse = document.getElementsByClassName("mse-g-recaptcha-response");
																		for (var i = 0; i < recaptchaResponse.length; i++) {
																			recaptchaResponse[i].value = token;
																		}
																		//recaptchaResponse.value = token;
																	});
																});
															}
														</script>
														<div id="contact-form-recaptcha" class="<?php echo $invisible_recaptcha_checkbox_class; ?>" ></div>
														<input type="hidden" name="g-recaptcha-response" id="mse-g-recaptcha-response" class="mse-g-recaptcha-response">
														<?php
													}
													if ( isset( $contact_form['consent_checkbox'] ) && $contact_form['consent_checkbox'] == 'yes') : ?>
														<p id="contact-form-consent-fields" class="contact-form-consent-fields">
															<label>
																<input type="checkbox" name="contact-form-consent-fields" value="1" <?php if (isset($contact_form['consent_text_require']) && $contact_form['consent_text_require'] == 1): ?> required <?php endif; ?> />
																<span class="contact_form_consent_txt"><?php echo stripslashes($contact_form['consent_text']); if (isset($contact_form['consent_text_require']) && $contact_form['consent_text_require'] == 1): echo esc_html__("*", 'mystickyelements'); endif; ?></span>
															</label>
														</p>
													<?php endif;?>
													<p class="mse-form-success-message" id="mse-form-error" style="display:none;"></p>
													<input id="stickyelements-submit-form" type="submit" name="contact-form-submit"
														   value="<?php echo esc_html($submit_button_text); ?>"
														   style="<?php echo esc_attr($submit_button_style); ?>"/>
													<?php $unique_id = uniqid() . time() . uniqid(); ?>
													<input type="hidden" name="nonce" value="<?php echo $unique_id ?>">
													<input type="hidden" name="widget_name" value="<?php echo esc_attr($widget_name); ?>">
													<input type="hidden" name="widget_number" value="<?php echo esc_attr($element_widget_no); ?>">
													<input type="hidden" name="form_id"
														   value="<?php echo wp_create_nonce($unique_id) ?>">
													<input type="hidden" id="stickyelements-page-link" name="stickyelements-page-link" value="<?php echo esc_url(home_url( $wp->request ))?>" />
												</form>
											</div>
										</li>
									<?php endif; /* Contact Form */
									if (!empty($social_channels_tabs) && isset($social_channels['enable']) && $social_channels['enable'] == 1) :
										$protocols = array('http', 'https', 'mailto', 'tel', 'sms', 'javascript','viber','skype');
										foreach ($social_channels_tabs as $key => $value):
											if ( $key == 'is_empty') {
												continue;
											}
											$link_target = 1;
											$social_channels_list = $social_channels_lists[$key];
											$element_class = '';
											if (isset($value['desktop']) && $value['desktop'] == 1) {
												$element_class .= ' element-desktop-on';
											}
											if (isset($value['mobile']) && $value['mobile'] == 1) {
												$element_class .= ' element-mobile-on';
											}

											//$hover_text = ($value['hover_text'] != '') ? $value['hover_text'] : $social_channels_list['hover_text'];

											$hover_text = ($value['hover_text'] != '') ? $value['hover_text'] : '';
											$social_link = '';
											$channel_type = (isset($value['channel_type'])) ? $value['channel_type'] : '';

											switch ($key) {
												case 'whatsapp':
													$value['text'] = str_replace( array('http://', 'https://') , array('','') , $value['text']);
													if ( isset($value['pre_set_message']) && $value['pre_set_message'] != '' ) {
														$social_link = 'https://api.whatsapp.com/send?phone=' .str_replace('+', '', $value['text']) . '&text=' . $value['pre_set_message'];
													} else {
														$social_link = 'https://api.whatsapp.com/send?phone=' . str_replace('+', '', $value['text']);
													}
													if ( wp_is_mobile()) {
														$link_target = 0;
													}
													break;
												case 'phone':
													$value['text'] = str_replace( array('http://', 'https://') , array('','') , $value['text']);
													if (strpos($value['text'], 'tel:') == false) {
														$social_link = "tel:".$value['text'];
													} else {
														$social_link = $value['text'];
													}
													$link_target = 0;
													break;
												case 'email':
													if (strpos($value['text'], 'mailto:') == false) {
														$social_link = "mailto:".$value['text'];
													} else {
														$social_link = $value['text'];
													}
													$link_target = 0;
													break;
												case 'wechat':
													$social_link = '';
													break;
												case 'facebook_messenger';
													$value['text'] = str_replace( array('http://', 'https://') , array('','') , $value['text']);
													$value_dash_count = substr_count ($value['text'], '-');
													if( $value_dash_count > 0 ) {
														$split_value = explode( '-', $value['text'] );
														$value_final = $split_value[count($split_value)-1];
													} else {
														$value_final = $value['text'];
													}
													$social_link = 'https://m.me/' . $value_final;
													if ( wp_is_mobile()) {
														$link_target = 0;
													}
													break;
												case 'address':
													$social_link = '';
													$value['text'] = str_replace( array('http://', 'https://') , array('','') , $value['text']);
													if ($value['text'] != '') {
														$hover_text .= ': ' . $value['text'];
													}
													break;
												case 'business_hours':
													$social_link = '';
													$value['text'] = str_replace( array('http://', 'https://') , array('','') , $value['text']);
													if ($value['text'] != '') {
														$hover_text .= ': ' . $value['text'];
													}
													break;
												case 'telegram' :
												
													if ( strpos( $value['text'], '//t.me') == '' ) {
														$social_link = "https://t.me/" . str_replace( '@', '', $value['text'] );
													} else {
														$social_link = $value['text'];
													}
													break;
												case 'vk' :
													$social_link = 'https://vk.me/' . $value['text'];
													break;
												case 'viber' :
													$value['text'] = str_replace('+','', $value['text']);
													$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
													$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
													$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
													if( $iPod || $iPhone ){
														$value['text'] = '+'.$value['text'];
													}else if($iPad){
														$value['text'] = '+'.$value['text'];
													}
													$social_link = "viber://chat?number=" . $value['text'];
													if ( wp_is_mobile()) {
														$link_target = 0;
													}
													break;
												case 'snapchat' :
													 $social_link = "https://www.snapchat.com/add/" . $value['text'];
													break;
												case 'skype' :
													$social_link = "skype:" . $value['text'] . "?chat";
													$link_target = 0;
													break;
												case 'SMS' :
													$social_link = "sms:" . $value['text'];
													$link_target = 0;
													break;
												case 'qq':
													$social_link = '';
													$value['text'] = str_replace( array('http://', 'https://') , array('','') , $value['text']);
													if ($value['text'] != '') {
														$hover_text .= ': ' . $value['text'];
													}
													break;
												default:
													if ( $channel_type == 'whatsapp') {
														$value['text'] = str_replace( array('http://', 'https://') , array('','') , $value['text']);
														if ( isset($value['pre_set_message']) && $value['pre_set_message'] != '' ) {
															$social_link = 'https://api.whatsapp.com/send?phone=' .str_replace('+', '', $value['text']) . '&text=' . $value['pre_set_message'];
														} else {
															$social_link = 'https://api.whatsapp.com/send?phone=' . str_replace('+', '', $value['text']);
														}
														if ( wp_is_mobile()) {
															$link_target = 0;
														}
													} else {
														$social_link = $value['text'];
													}
													break;
											}
											if ( isset($social_channels_list['custom_html']) && $social_channels_list['custom_html'] == 1) {
												$social_link = '';
												$element_class .= ' mystickyelements-custom-html-main';
											}
											if(preg_match('/^<iframe /',$value['text'])){
												$element_class .=" mystickyelements-custom-html-iframe";
											}
											/*if(preg_match('/^<[a-z]/',$value['text']) && ! preg_match('/^<iframe /',$value['text'])){
												$element_class .=" mystickyelements-custom-html-div";
											}*/

											if( isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1 ) {
												if( isset($value['open_newtab']) && $value['open_newtab'] == 1 ) {
													$link_target = 1;
												} else {
													$link_target = 0;
												}
											}
											?>
											<li id="mystickyelements-social-<?php echo esc_attr($key);?>"
												class="mystickyelements-social-icon-li mystickyelements-social-<?php echo esc_attr($key);?> <?php echo esc_attr($element_class);?>">
												<?php
												/*diamond template css*/
												$widget_class = '.mystickyelements-fixed-widget-' . $mystickyelements_widget_count;
												if ( isset($value['bg_color']) && $value['bg_color'] != '' ) { ?>
													<style>
														<?php if( $general_settings['templates'] == 'diamond' ) { ?>
															<?php echo $widget_class; ?>.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
																background: <?php echo $value['bg_color']; ?>;
															}
															@media only screen and (min-width: 1025px) {
																<?php echo $widget_class; ?>.mystickyelements-position-left.mystickyelements-on-click.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form).elements-active span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after,
																<?php echo $widget_class; ?>.mystickyelements-position-left.mystickyelements-on-hover.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form):hover span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after	{
																	background-color: <?php echo $value['bg_color']; ?>;
																}
																<?php echo $widget_class; ?>.mystickyelements-position-right.mystickyelements-on-click.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form).elements-active span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after,
																<?php echo $widget_class; ?>.mystickyelements-position-right.mystickyelements-on-hover.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form):hover span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after {
																	background-color: <?php echo $value['bg_color']; ?>;
																}
																<?php echo $widget_class; ?>.mystickyelements-position-left.mystickyelements-templates-diamond .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
																	border-left-color: <?php echo $value['bg_color']; ?>;
																}
																<?php echo $widget_class; ?>.mystickyelements-position-right.mystickyelements-templates-diamond .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
																	border-right-color: <?php echo $value['bg_color']; ?>;
																}
															}
															@media only screen and (max-width: 1024px) {
																<?php echo $widget_class; ?>.mystickyelements-position-mobile-left.mystickyelements-on-click.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form).elements-active span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after,
																<?php echo $widget_class; ?>.mystickyelements-position-mobile-left.mystickyelements-on-hover.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form):hover span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after	{
																	background-color: <?php echo $value['bg_color']; ?>;
																}
																<?php echo $widget_class; ?>.mystickyelements-position-mobile-right.mystickyelements-on-click.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form).elements-active span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after,
																<?php echo $widget_class; ?>.mystickyelements-position-mobile-right.mystickyelements-on-hover.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form):hover span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after {
																	background-color: <?php echo $value['bg_color']; ?>;
																}
																<?php echo $widget_class; ?>.mystickyelements-position-mobile-left.mystickyelements-templates-diamond .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
																	border-left-color: <?php echo $value['bg_color']; ?>;
																}
																<?php echo $widget_class; ?>.mystickyelements-position-mobile-right.mystickyelements-templates-diamond .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
																	border-right-color: <?php echo $value['bg_color']; ?>;
																}
															}
														<?php
														}
														if( $general_settings['templates'] == 'arrow' ) {
														?>
															<?php if( $key == 'insagram' ) { ?>
															<?php echo $widget_class; ?>.mystickyelements-position-left.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
															<?php echo $widget_class; ?>.mystickyelements-position-left.mystickyelements-templates-arrow .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
																background: <?php echo $value['bg_color']; ?>;
															}
															<?php } ?>
															@media only screen and (min-width: 1025px) {
																<?php echo $widget_class; ?>.mystickyelements-position-left.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
																<?php echo $widget_class; ?>.mystickyelements-position-left.mystickyelements-templates-arrow .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
																	border-left-color: <?php echo $value['bg_color']; ?>;
																}
																<?php echo $widget_class; ?>.mystickyelements-position-right.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
																<?php echo $widget_class; ?>.mystickyelements-position-right.mystickyelements-templates-arrow .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
																	border-right-color: <?php echo $value['bg_color']; ?>;
																}
																<?php echo $widget_class; ?>.mystickyelements-position-bottom.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
																<?php echo $widget_class; ?>.mystickyelements-position-bottom.mystickyelements-templates-arrow .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
																	border-bottom-color: <?php echo $value['bg_color']; ?>;
																}
															}
															@media only screen and (max-width: 1024px) {
																<?php echo $widget_class; ?>.mystickyelements-position-mobile-left.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
																<?php echo $widget_class; ?>.mystickyelements-position-mobile-left.mystickyelements-templates-arrow .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
																	border-left-color: <?php echo $value['bg_color']; ?>;
																}
																<?php echo $widget_class; ?>.mystickyelements-position-mobile-right.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
																<?php echo $widget_class; ?>.mystickyelements-position-mobile-right.mystickyelements-templates-arrow .social-<?php echo esc_attr($key);?> +  span.mystickyelements-social-text:before {
																	border-right-color: <?php echo $value['bg_color']; ?>;
																}
															}
														<?php
														}
														if( $general_settings['templates'] == 'triangle' ) {
														?>
															<?php echo $widget_class; ?>.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
															<?php echo $widget_class; ?>.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::after {
																background: <?php echo $value['bg_color']; ?>;
															}
															@media only screen and (min-width: 1025px) {
																<?php echo $widget_class; ?>.mystickyelements-position-left.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) .social-<?php echo esc_attr($key);?> + span.mystickyelements-social-text::before {
																	border-left-color: <?php echo $value['bg_color']; ?>;
																}
																<?php echo $widget_class; ?>.mystickyelements-position-right.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) .social-<?php echo esc_attr($key);?> + span.mystickyelements-social-text::before {
																	border-right-color: <?php echo $value['bg_color']; ?>;
																}
																<?php echo $widget_class; ?>.mystickyelements-position-bottom.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
																<?php echo $widget_class; ?>.mystickyelements-position-bottom.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) .social-<?php echo esc_attr($key);?> + span.mystickyelements-social-text::before {
																	border-bottom-color: <?php echo $value['bg_color']; ?>;
																}
																<?php echo $widget_class; ?>.mystickyelements-position-bottom.mystickyelements-on-click.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form).elements-active span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
																<?php echo $widget_class; ?>.mystickyelements-position-bottom.mystickyelements-on-hover.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form):hover span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
																	background-color: <?php echo $value['bg_color']; ?>;
																}
															}
															@media only screen and (max-width: 1024px) {
																<?php echo $widget_class; ?>.mystickyelements-position-mobile-left.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) .social-<?php echo esc_attr($key);?> + span.mystickyelements-social-text::before {
																	border-left-color: <?php echo $value['bg_color']; ?>;
																}
																<?php echo $widget_class; ?>.mystickyelements-position-mobile-right.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) .social-<?php echo esc_attr($key);?> + span.mystickyelements-social-text::before {
																	border-right-color: <?php echo $value['bg_color']; ?>;
																}
																<?php echo $widget_class; ?>.mystickyelements-position-mobile-left.mystickyelements-on-click.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form).elements-active span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
																<?php echo $widget_class; ?>.mystickyelements-position-mobile-left.mystickyelements-on-hover.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form):hover span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before	{
																	background-color: <?php echo $value['bg_color']; ?>;
																}
																<?php echo $widget_class; ?>.mystickyelements-position-mobile-right.mystickyelements-on-click.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form).elements-active span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before,
																<?php echo $widget_class; ?>.mystickyelements-position-mobile-right.mystickyelements-on-hover.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form):hover span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
																	background-color: <?php echo $value['bg_color']; ?>;
																}
															}
														<?php
														}
														?>
													</style>
													<?php
												}
												$channel_type = (isset($value['channel_type'])) ? $value['channel_type'] : '';
												if ( $channel_type != 'custom' && $channel_type != '' ) {
													if ( isset($social_channels_lists[$channel_type]['custom_svg_icon']) ) {
														$social_channels_list['custom_svg_icon'] = $social_channels_lists[$channel_type]['custom_svg_icon'];
													}
													$social_channels_list['class'] 	= $social_channels_lists[$channel_type]['class'];
													$value['fontawesome_icon']		= $social_channels_lists[$channel_type]['class'];
												}
												?>
												<span class="mystickyelements-social-icon social-<?php echo esc_attr($key);?> social-<?php echo esc_attr($channel_type);?>"
													  <?php if (isset($value['bg_color']) && $value['bg_color'] != ''): ?>style="background: <?php echo esc_attr($value['bg_color']); ?>" <?php endif;
												?>>
													<?php if ($social_link != ''):	?>
														<a href="<?php echo esc_url($social_link, $protocols); ?>"  <?php if ( $link_target == 1 ):?> target="_blank" rel="noopener" <?php endif;?>>
													<?php endif;
															if (isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1 && $value['custom_icon'] != '' &&  $value['fontawesome_icon'] == ''): ?>
																<img class="<?php echo ( isset($value['stretch_custom_icon']) && $value['stretch_custom_icon'] == 1 ) ? 'mystickyelements-stretch-custom-img' : '';  ?>" src="<?php echo esc_url($value['custom_icon']); ?>"/>
															<?php else:
																if ( isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1 && $value['fontawesome_icon'] != '' ) {
																	$social_channels_list['class'] = $value['fontawesome_icon'];
																}
																if ( isset($social_channels_list['custom_svg_icon']) && $social_channels_list['custom_svg_icon'] != '' ) :
																	echo $social_channels_list['custom_svg_icon'];
																else: ?>
																<i class="<?php echo esc_attr($social_channels_list['class']); ?>" <?php if ( isset($value['icon_color']) && $value['icon_color'] != '') : echo "style='color:" . $value['icon_color'] . "'"; endif; ?>></i>
															<?php endif;
															endif;
															if ( isset($value['icon_text']) && $value['icon_text'] != '' && isset($general_settings['templates']) && $general_settings['templates'] == 'default' ) {
																$icon_text_size = '';
																if ( isset($value['icon_text_size']) && $value['icon_text_size'] != '') {
																	$icon_text_size = "font-size: " . $value['icon_text_size'] . "px";
																}
																echo "<span class='mystickyelements-icon-below-text' style='".$icon_text_size."'>" . esc_html($value['icon_text']) . "</span>";
															}
													if ($social_link != ''  ): ?>
														</a>
													<?php endif;
													if ( $key == 'line') {
														echo "<style>.mystickyelements-social-icon.social-". $key ." svg .fil1{ fill:" .$value['icon_color']. "}</style>";
													}
													if ( $key == 'qzone') {
														echo "<style>.mystickyelements-social-icon.social-". $key ." svg .fil2{ fill:" . $value['icon_color'] . "}</style>";
													}
													?>
												</span>

											<?php if ( isset($social_channels_list['custom_html']) && $social_channels_list['custom_html'] == 1  ) :?>
												<div class="mystickyelements-custom-html" <?php if (isset($value['bg_color']) && $value['bg_color'] != ''): ?>style="background: <?php echo esc_attr($value['bg_color']); ?>" <?php endif; ?>>
													<div class="mystickyelements-custom-html-wrap">
														<?php echo do_shortcode( str_replace('\"', '"', stripslashes($value['text'])));?>
													</div>
												</div>
											<?php else :
												$icon_bg_color = $icon_text_color = '';
												if (isset($value['bg_color']) && $value['bg_color'] != '') {
													$icon_bg_color = "background: " . esc_attr($value['bg_color']) . ";";
												}
												if (isset($value['icon_color']) && $value['icon_color'] != '') {
													$icon_text_color = "color: " . esc_attr($value['icon_color']) . ";";
												}
												if ( $hover_text != '' ):
											?>
												<span class="mystickyelements-social-text <?php echo ($social_link == '') ? 'mystickyelements-social-no-link' : '';?>" style= "<?php echo $icon_bg_color.$icon_text_color ?>">
													<?php if ($social_link != ''): ?>
													<a href="<?php echo esc_url($social_link, $protocols); ?>" <?php if ( $link_target == 1 ):?> target="_blank" rel="noopener" <?php endif;?> <?php if ( isset($value['icon_color']) && $value['icon_color'] != '') : echo "style='color:" . $value['icon_color'] . "'"; endif; ?>>
													<?php endif;
														if ($key == 'wechat') {
															echo esc_html($hover_text . ': ' . $value['text']);
														} else {
															echo esc_html(stripslashes($hover_text));
														}?>
														<?php if ($social_link != ''): ?>
													</a>
												<?php endif; ?>
												</span>
											<?php endif; /* Hover Text not equal to blank condition */
											endif;?>

											</li>

										<?php endforeach;
									endif;
									?>
								</ul>
							</div>
						</div>
						<?php

						/* Include Custom CSS */
						// Add Themme custom CSS
						$destop_position = ( isset($general_settings['custom_position_from']) ) ? $general_settings['custom_position_from'] : 'bottom';
						$reverse_position = ( $destop_position == 'bottom' ) ? 'top' : 'bottom';

						$mobile_position = ( isset($general_settings['custom_position_from_mobile']) ) ? $general_settings['custom_position_from_mobile'] : 'bottom';
						$reverse_mobile_position = ( $mobile_position == 'bottom' ) ? 'top' : 'bottom';
						if (  isset($contact_form['form_css']) || isset($general_settings['tabs_css']) || ( isset($general_settings['font_family']) && $general_settings['font_family'] != '') ) {
							$widget_class = '.mystickyelements-fixed-widget-' . $mystickyelements_widget_count;
							$custom_css = '';
							if ( isset($general_settings['font_family']) && $general_settings['font_family'] != '' ) {
								$custom_css .= $widget_class . '.mystickyelements-fixed,
												' . $widget_class . ' form#stickyelements-form select,
												' . $widget_class . ' form#stickyelements-form input,
												' . $widget_class . ' form#stickyelements-form textarea,
												' . $widget_class . ' .element-contact-form h3 {
													font-family: "' . $general_settings['font_family'] . '";
												}';
								$custom_css .= $widget_class . ' .mystickyelements-contact-form[dir="rtl"],
												' . $widget_class . ' .mystickyelements-contact-form[dir="rtl"] .element-contact-form h3,
												' . $widget_class . ' .mystickyelements-contact-form[dir="rtl"] form#stickyelements-form input,
												' . $widget_class . ' .mystickyelements-contact-form[dir="rtl"] form#stickyelements-form textarea,
												' . $widget_class . ' .mystickyelements-fixed[dir="rtl"] .mystickyelements-social-icon,
												' . $widget_class . ' .mystickyelements-fixed[dir="rtl"] .mystickyelements-social-text,
												html[dir="rtl"] ' . $widget_class . ' .mystickyelements-contact-form,
												html[dir="rtl"] ' . $widget_class . ' .mystickyelements-contact-form .element-contact-form h3,
												html[dir="rtl"] ' . $widget_class . ' .mystickyelements-contact-form form#stickyelements-form input,
												html[dir="rtl"] ' . $widget_class . ' .mystickyelements-contact-form form#stickyelements-form textarea,
												html[dir="rtl"] ' . $widget_class . ' .mystickyelements-fixed .mystickyelements-social-icon,
												html[dir="rtl"] ' . $widget_class . ' .mystickyelements-fixed .mystickyelements-social-text {
													font-family: "' . $general_settings['font_family'] . '";
												}';
							}
							if (isset($general_settings['custom_position']) && $general_settings['custom_position'] != '') {
								$custom_css .= '@media only screen and (min-width:1025px) {';
								$custom_css .= $widget_class . '.mystickyelements-fixed {
												' . $destop_position . ': ' . $general_settings['custom_position'] . 'px;
												' . $reverse_position . ': auto;
												-webkit-transform: translateY(0);
												-moz-transform: translateY(0);
												transform: translateY(0);
											}';
								$custom_css .= $widget_class . '.mystickyelements-fixed.mystickyelements-custom-html-iframe-open {
												' . $reverse_position . ': auto;
												' . $destop_position . ': ' . $general_settings['custom_position'] . 'px;
											}';
								$custom_css .= $widget_class . '.mystickyelements-fixed ul {
													position: relative;
												}';
								$custom_css .= $widget_class . ' .mystickyelements-custom-html-iframe .mystickyelements-custom-html {
													top: auto;
													bottom: 0;
													-webkit-transform: rotateY(90deg) translateY(0);
													-moz-transform: rotateY(90deg) translateY(0);
													transform: rotateY(90deg) translateY(0);
												}';
								$custom_css .= $widget_class . '.mystickyelements-on-click.mystickyelements-fixed ul li.mystickyelements-custom-html-main.mystickyelements-custom-html-iframe.elements-active .mystickyelements-custom-html, ' . $widget_class . '.mystickyelements-on-hover.mystickyelements-fixed ul li.mystickyelements-custom-html-main.mystickyelements-custom-html-iframe:hover .mystickyelements-custom-html {
													-webkit-transform: rotateY(0deg) translateY(0);
													-moz-transform: rotateY(0deg) translateY(0);
													transform: rotateY(0deg) translateY(0);
												}';
								$custom_css .= '}';
							}
							if (isset($general_settings['custom_position_mobile']) && $general_settings['custom_position_mobile'] != '') {
								$custom_css .= '@media only screen and (max-width:1024px) {';
								$custom_css .= $widget_class . '.mystickyelements-fixed {
												'. $mobile_position .': ' . $general_settings['custom_position_mobile'] . 'px;
												'. $reverse_mobile_position .': auto;
												-webkit-transform: translateY(0);
												-moz-transform: translateY(0);
												transform: translateY(0);
											}';
								$custom_css .= $widget_class . '.mystickyelements-fixed.mystickyelements-custom-html-iframe-open {
												'. $reverse_mobile_position .': auto;
												'. $mobile_position .': ' . $general_settings['custom_position_mobile'] . 'px;
											}';
								$custom_css .= $widget_class . '.mystickyelements-fixed ul {
													position: relative;
												}';
								$custom_css .= $widget_class . ' .mystickyelements-custom-html-iframe .mystickyelements-custom-html {
													top: auto;
													bottom: 0;
													-webkit-transform: rotateY(90deg) translateY(0);
													-moz-transform: rotateY(90deg) translateY(0);
													transform: rotateY(90deg) translateY(0);
												}';
								$custom_css .= $widget_class . '.mystickyelements-on-click.mystickyelements-fixed ul li.mystickyelements-custom-html-main.mystickyelements-custom-html-iframe.elements-active .mystickyelements-custom-html,' . $widget_class . '.mystickyelements-on-hover.mystickyelements-fixed ul li.mystickyelements-custom-html-main.mystickyelements-custom-html-iframe:hover .mystickyelements-custom-html {
													-webkit-transform: rotateY(0deg) translateY(0);
													-moz-transform: rotateY(0deg) translateY(0);
													transform: rotateY(0deg) translateY(0);
												}';
								$custom_css .= '}';
							}
							if (isset($contact_form['form_css']) && $contact_form['form_css'] !='' ) {
								$custom_css .= trim(strip_tags($contact_form['form_css']));
							}
							if (isset($general_settings['tabs_css']) && $general_settings['tabs_css'] !='' ) {
								$custom_css .= trim(strip_tags($general_settings['tabs_css']));
							}
							if (!empty($custom_css)) {
								?>
								<style>
									<?php echo $custom_css; ?>
								</style>
								<?php
							}
						}
						/* END Include custom css*/
					}
				}
			}

		}

        public function mystickyelements_contact_form() {
            global $wpdb;
			if ( is_user_logged_in() && ! current_user_can( 'manage_options' ) ) {
				wp_die(0);
			}
            check_ajax_referer('mystickyelements', 'security');

            $errors = array();
			$element_widget_no = $_POST['widget_number'];
			$element_widget_name = ( isset($_POST['widget_name']) && $_POST['widget_name'] != '' ) ? sanitize_text_field($_POST['widget_name']) : 'default';

            $contact_form = get_option('mystickyelements-contact-form' . $element_widget_no );
            if (isset($contact_form['name']) && $contact_form['name'] == 1) {
                if (isset($contact_form['name_require']) && $contact_form['name_require'] == 1 && (!isset($_POST['contact-form-name']) || empty($_POST['contact-form-name']))) {
                    $error = array(
                        'key' => "contact-form-name",
                        'message' => __( "This field is required", "mystickyelements" )
                    );
                    $errors[] = $error;
                }
            }
            if (isset($contact_form['email']) && $contact_form['email'] == 1) {
                if (isset($contact_form['email_require']) && $contact_form['email_require'] == 1 && (!isset($_POST['contact-form-email']) || empty($_POST['contact-form-email']))) {
                    $error = array(
                        'key' => "contact-form-email",
                        'message' => __( "This field is required", "mystickyelements" )
                    );
                    $errors[] = $error;
                } else if ( isset($contact_form['email_require']) && $contact_form['email_require'] == 1 && isset($_POST['contact-form-email']) && !filter_var($_POST['contact-form-email'], FILTER_VALIDATE_EMAIL)) {
                    $error = array(
                        'key' => "contact-form-email",
                        'message' => __( "Email address is not valid", "mystickyelements")
                    );
                    $errors[] = $error;
                }
            }

            if (isset($contact_form['message']) && $contact_form['message'] == 1) {
                if (isset($contact_form['message_require']) && $contact_form['message_require'] == 1 && (!isset($_POST['contact-form-message']) || empty($_POST['contact-form-message']))) {
                    $error = array(
                        'key' => "contact-form-message",
                        'message' => __( "This field is required", "mystickyelements" )
                    );
                    $errors[] = $error;
                }
            }

            if (isset($contact_form['phone']) && $contact_form['phone'] == 1) {
                if (isset($contact_form['phone_require']) && $contact_form['phone_require'] == 1 && (!isset($_POST['contact-form-phone']) || empty($_POST['contact-form-phone']))) {
                    $error = array(
                        'key' => "contact-form-phone",
                        'message' => __( "This field is required", "mystickyelements" )
                    );
                    $errors[] = $error;
                }
            }
			if (isset($contact_form['dropdown']) && $contact_form['dropdown'] == 1) {
                if (isset($contact_form['dropdown_require']) && $contact_form['dropdown_require'] == 1 && (!isset($_POST['contact-form-dropdown']) || empty($_POST['contact-form-dropdown']))) {
                    $error = array(
                        'key' => "contact-form-dropdown",
                        'message' => __( "This field is required", "mystickyelements" )
                    );
                    $errors[] = $error;
                }
            }

			/* Custom Field validation */
			$custom_fields_value = array();
			if ( isset($_POST['contact-form']['custom_field']) && !empty($_POST['contact-form']['custom_field'])) {
				foreach($_POST['contact-form']['custom_field'] as $key=>$value) {
					if ( isset($contact_form['custom_fields'][$key]['custom_field_require']) && $contact_form['custom_fields'][$key]['custom_field_require'] == 1 && ( $value == '' || empty($value))  ) {
						$custom_field_name = sanitize_title($contact_form['custom_fields'][$key]['custom_field_name']);
						$error = array(
							'key' => "contact-form-" . $custom_field_name,
							'message' => __( "This field is required", "mystickyelements" )
						);
						$errors[] = $error;
					}
					if(isset($contact_form['custom_fields'][$key]['custom_field']) && $contact_form['custom_fields'][$key]['custom_field'] == 1 &&  $value != '' ) {
						$custom_fields_value[$contact_form['custom_fields'][$key]['custom_field_name']] = $value;
					}
				}
			}

			/*recaptcha validation*/
			if ( isset($contact_form['recaptcha_checkbox']) && $contact_form['recaptcha_checkbox'] == 'yes' ) {
				if( isset( $_POST['g-recaptcha-response'] ) && $_POST['g-recaptcha-response'] != '' ) {
					$captcha = $_POST['g-recaptcha-response'];
					$secretKey = $contact_form['recaptcha_secrete_key'];
					// post request to server
					$captcha_url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
					$captcha_response = wp_remote_get( $captcha_url,array() );
					$responseKeys = json_decode( wp_remote_retrieve_body( $captcha_response ), true );


					if( $responseKeys["success"] ) {

					} else {
						$error_codes = array(
						  'missing-input-secret' 	=> 'The secret key is missing Please add a secret key',
						  'invalid-input-secret' 	=> 'You have added the wrong secret Key. Please add a correct secret key',
						  'missing-input-response' 	=> 'Captcha token is missing please check you site key',
						  'invalid-input-response' 	=> 'Captcha token is invalid please check site key and secret key',
						  'timeout-or-duplicate' 	=> 'captcha token expire please refresh the page and submit the form again',
						);
						$captchaerrormsg = '';
						$captchaerrors = $responseKeys['error-codes'];
						foreach( $captchaerrors as $captchaerror ) {
							if( array_key_exists( $captchaerror, $error_codes ) ) {
								$captchaerrormsg .= $error_codes[$captchaerror]. "<br>";
							} else {
								$captchaerrormsg .= 'The form submission was blocked by reCAPTCHA<br>';
							}
						}
						$error = array(
							'key' => "contact-form-recaptcha",
							'message' => __( $captchaerrormsg, 'mystickyelements' )
						);
						$errors[] = $error;
					}
				} else {
					$error = array(
						'key' => "contact-form-recaptcha",
						'message' => __( "You have added the wrong site Key. Please add a correct site key", "mystickyelements" )
					);
					$errors[] = $error;
				}
			}

			if( isset( $_FILES['contact-form']['tmp_name']['custom_field'] ) && !empty( $_FILES['contact-form']['tmp_name']['custom_field'] ) ) {
				foreach($_FILES['contact-form']['tmp_name']['custom_field'] as $key=>$value) {
					$wp_upload_dir = wp_upload_dir();
					$path = $wp_upload_dir['basedir'] . '/myStickyelements-attachments';
					if ( ! is_dir($path)) {
						mkdir($path);
					}
					$upload_path = $path . '/' . basename($_FILES['contact-form']['name']['custom_field'][$key]);
					$file_allowed = array("jpg","jpeg","png","gif","pdf","doc","docx","ppt","pptx","pps","ppsx","odt","xls","xlsx","mp3","mp4","wav","mpg","avi","mov","wmv","3gp","ogv");
					$filename = $_FILES['contact-form']['name']['custom_field'][$key];
					$file_ext = pathinfo($filename, PATHINFO_EXTENSION);
					if ( $filename != '' && ! in_array( $file_ext, $file_allowed ) ) {
						$custom_field_name = sanitize_title($contact_form['custom_fields'][$key]['custom_field_name']);
						$error = array(
							'key' => "contact-form-" . $custom_field_name,
							'message' => __( "Please Upload .jpg, .jpeg, .png, .gif, .pdf, .doc, .docx, .ppt, .pptx, .pps, .ppsx, .odt, .xls, .xlsx, .mp3, .mp4, .wav, .mpg, .avi, .mov, .wmv, .3gp, .ogv file extension only", "mystickyelements" )
						);
						$errors[] = $error;
					} else {
						if( move_uploaded_file( $value, $upload_path ) ){
							$attachment_url = $wp_upload_dir['baseurl'] . '/myStickyelements-attachments/' . basename($_FILES['contact-form']['name']['custom_field'][$key]);
							$custom_fields_value[$contact_form['custom_fields'][$key]['custom_field_name']] = $attachment_url;
							$custom_file_dir[$key] = $path . '/' . basename($_FILES['contact-form']['name']['custom_field'][$key]);
						}
					}
				}
			}

            $message = "There is error. We are not able to complete your request";

            if (empty($errors)) {
                if (!isset($_POST['nonce']) || empty($_POST['nonce'])) {
                    $error = array(
                        'key' => "mse-form-error",
                        'message' => "There is error. We are not able to complete your request"
                    );
                    $errors[] = $error;
                } else if (!isset($_POST['form_id']) || empty($_POST['form_id'])) {
                    $error = array(
                        'key' => "mse-form-error",
                        'message' => "There is error. We are not able to complete your request"
                    );
                    $errors[] = $error;
                } else if (!wp_verify_nonce($_POST['form_id'], $_POST['nonce'])) {
                    $error = array(
                        'key' => "mse-form-error",
                        'message' => "There is error. We are not able to complete your request"
                    );
                    $errors[] = $error;
                }
                if (!empty($errors)) {
                    echo json_encode(array("status" => 0, "error" => 1, "errors" => $errors, "message" => $message));
                    die;
                }
            } else {
                echo json_encode(array("status" => 0, "error" => 1, "errors" => $errors, "message" => $message));
                die;
            }

			/* Check redirct Link set */
			$redirect_link = '';
			if ( ( isset($contact_form['redirect']) && $contact_form['redirect'] == 1 ) && ( isset($contact_form['redirect_link']) && $contact_form['redirect_link'] != '' ) ) {
				$redirect_link = $contact_form['redirect_link'];
			}
			$open_new_tab = '';
			if ( ( isset($contact_form['open_new_tab']) && $contact_form['open_new_tab'] == 1 ) ) {
				$open_new_tab = $contact_form['open_new_tab'];
			}

             if (isset($_POST['contact-form-email']) || isset($_POST['contact-form-name']) || isset($_POST['contact-form-phone']) || isset($_POST['contact-form-message']) || ( isset($_POST['contact-form']['custom_field']) && !empty($_POST['contact-form']['custom_field']) ) ) {
				$flg = false;

				//$ip_address = $this->get_user_ipaddress();
				$ip_address = '';
				if ( isset( $contact_form['iplog_checkbox'] ) && $contact_form['iplog_checkbox'] == 'yes') {
					$ip_address = $this->get_user_ipaddress();
				}

				if ( !is_array( $contact_form['send_leads'])) {
					$contact_form['send_leads'] = explode(', ', $contact_form['send_leads']);
				}




				/* Send Contact form Data by email. */
                if ( in_array( 'mail', $contact_form['send_leads'] )  ) {
                    $send_mail = (isset($contact_form['sent_to_mail']) && $contact_form['sent_to_mail'] != '') ? $contact_form['sent_to_mail'] : get_option('admin_email');
					$email_subject_line = ( isset($contact_form['email_subject_line']) && $contact_form['email_subject_line'] != '' ) ? $contact_form['email_subject_line'] : 'New lead from MyStickyElements';
					$contact_form_name = ( isset( $_POST['contact-form-name'] ) && $_POST['contact-form-name'] != '' ) ? $_POST['contact-form-name'] : '';
                    $subject = $email_subject_line ." - " . $contact_form_name;
                    $message = "" ;

                    if (isset($_POST['contact-form-name']) && $_POST['contact-form-name'] != '') {
                        $message .= "<p>Name: " . sanitize_text_field($_POST['contact-form-name']) . "<p>\r\n";
                    }
                    if (isset($_POST['contact-form-phone']) && $_POST['contact-form-phone'] != '') {
                        $message .= "<p>Phone: " . sanitize_text_field($_POST['contact-form-phone']) . "</p>\r\n";
                    }
                    if (isset($_POST['contact-form-email']) && $_POST['contact-form-email'] != '') {
                        $message .= "<p>Email: " . sanitize_email($_POST['contact-form-email']) . "</p>\r\n";
                    }
					if (isset($_POST['contact-form-dropdown']) && $_POST['contact-form-dropdown'] != '') {
                        $message .= "<p>" . $contact_form['dropdown-placeholder'] . ": " . sanitize_text_field($_POST['contact-form-dropdown']) . "</p>\r\n";
                    }
					if ( !empty($custom_fields_value) && $custom_fields_value != '') {
						foreach( $custom_fields_value as $key=>$value ){
							//if( strpos( $value, 'http' ) === false ) {
								$message .= "<p>" . $key ." : " . wp_kses_post($value) . "</p>\r\n";
							//}
						}
                    }
                    if (isset($_POST['contact-form-message']) && $_POST['contact-form-message'] != '') {
                        $message .= "<p>Message: " . sanitize_text_field(stripslashes($_POST['contact-form-message'])) . "</p>\r\n\r\n";
                    }
					if ( $element_widget_name != '' && $element_widget_name != 'default' ) {
                        $message .= "<p>Widget element Name: " . sanitize_text_field(stripslashes($element_widget_name)) . "</p>\r\n";
                    }
                    $message .= "<p>Submission URL: " . sanitize_text_field($_POST['stickyelements-page-link']) . "</p>\r\n\r\n";

					if ( isset( $contact_form['iplog_checkbox'] ) && $contact_form['iplog_checkbox'] == 'yes') {
						$message .= "<p>User IP Address: " . $ip_address . "</p>\r\n";
					}

					if ( isset($contact_form['consent_checkbox']) ) {
						$contact_form_consent_fields = "False";
						if ( isset( $_POST['contact-form-consent-fields'] ) ) {
							$contact_form_consent_fields = "True";
						}
						$message .= "<p>Consent Checkbox: " . $contact_form_consent_fields . "</p>\r\n\r\n";
					}


                    //$message .= "<p>Thank You" . "</p>\r\n";
                    $message .= "<p>" . get_bloginfo('name') . "</p>\r\n";

                    $blog_name = get_bloginfo('name');
                    $blog_email = get_bloginfo('admin_email');

					$blog_name = (isset($contact_form['sender_name']) && $contact_form['sender_name'] != '') ? $contact_form['sender_name'] : get_bloginfo('name');

                    $headers = "";
                    $headers .= 'From: ' . $blog_name . ' <' . $blog_email . '>' . "\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
					if (isset($_POST['contact-form-email']) && $_POST['contact-form-email'] != '') {
						$headers .= "Reply-To: " . sanitize_text_field($_POST['contact-form-name']) ." <" . sanitize_email($_POST['contact-form-email']) . ">\r\n";
					}
					$attachments = array();
					if ( !empty($custom_file_dir) && $custom_file_dir != '') {
						foreach( $custom_file_dir as $key=>$value ){
							$attachments[] = $value;
						}
                    }
                    if (wp_mail($send_mail, $subject, $message, $headers, $attachments)) {
						$flg = true;
                    } else {
						if ( in_array( 'database', $contact_form['send_leads'] ) ) {
							$flg = true;
						} else {
							$flg = false;
						}
                    }
                }
				/* Saved Data into Mailchimp */
				if ( in_array( 'mailchimp', $contact_form['send_leads'] ) ) {
					$this->contactleadpushmailchimp( $contact_form, $custom_fields_value );
					$flg = true;
				}
				if ( in_array( 'mailpoet', $contact_form['send_leads'] ) ) {
					$this->contactleadpushmailpoet( $contact_form, $custom_fields_value );
					$flg = true;
				}
				/* Saved Data into Database */
				if ( in_array( 'database', $contact_form['send_leads'] ) ) {
                    $resultss = $wpdb->insert(
                        $wpdb->prefix . 'mystickyelement_contact_lists',
                        array(
                            'contact_name' 		=> isset($_POST['contact-form-name']) ? esc_sql(sanitize_text_field($_POST['contact-form-name'])) : '',
                            'contact_phone' 	=> isset($_POST['contact-form-phone']) ? esc_sql(sanitize_text_field($_POST['contact-form-phone'])) : '',
                            'contact_email' 	=> isset($_POST['contact-form-email']) ? esc_sql(sanitize_email($_POST['contact-form-email'])) : '',
                            'contact_message' 	=> isset($_POST['contact-form-message']) ? sanitize_textarea_field(stripslashes($_POST['contact-form-message'])) : '',
							'contact_option' 	=> (isset($_POST['contact-form-dropdown'])) ? esc_sql(sanitize_textarea_field($_POST['contact-form-dropdown'])) : '',
							'message_date' 		=> date('Y-m-d H:i:s'),
							'widget_element_name'	=> $element_widget_name,
							//'custom_fields'	=> json_encode($_POST['contact-form']['custom_field']),
							'custom_fields'		=> ( !empty($custom_fields_value) && $custom_fields_value != '' ) ? json_encode($custom_fields_value) : '',
							'page_link' 		=> esc_sql(sanitize_text_field($_POST['stickyelements-page-link'])),
							'consent_checkbox' 	=> isset($_POST['contact-form-consent-fields']) ? true : false,
							'ip_address' 		=> $ip_address,
                        )
                    );
					$flg = true;
                }

				if ( $flg == true ) {
					$thank_you_message = ( isset($contact_form['thank_you_message']) && $contact_form['thank_you_message'] != '' ) ? $contact_form['thank_you_message'] : esc_html__('Your message was sent successfully', 'mystickyelements');

					$message = $thank_you_message;
					echo json_encode(array("status" => 1, "error" => 0, "errors" => array(), "message" => $message , "redirect_link" => $redirect_link, "open_new_tab" => $open_new_tab));
					die;
				} else {
					$message = esc_html__('Something went wrong. Please contact site administrator', 'mystickyelements');
					echo json_encode(array("status" => 0, "error" => 0, "errors" => array(), "message" => $message));
					die;
				}

            }

            wp_die();
        }

		function contactleadpushmailpoet( $contact_form, $custom_fields_value ) {
			
			if (  class_exists( '\MailPoet\API\API' ) ) {

				$mailpoet_fields = \MailPoet\API\API::MP( 'v1' )->getSubscriberFields();
				$field_missing = [];
				/* Get the Mailpoet fields*/
				if ( !empty($mailpoet_fields) && !empty($contact_form['custom_fields']) ) {
					$mp_fields = [];
					foreach( $contact_form['custom_fields'] as $custom_field ) {

						$flg = 0;
						foreach( $mailpoet_fields as $field) {
							if ( sanitize_title($field['name']) == sanitize_title($custom_field[ 'custom_field_name']) ) {
								$mp_fields[$custom_field[ 'custom_field_name']] = $field['id'];
								$flg = 1;
							}
						}
						if ( $flg == 0 ) {
							$field_missing[] = $custom_field;
						}
					}

					$additional_fields = array( 'Phone', 'Message', 'Dropdown');
					foreach( $additional_fields as $afield) {
						$aflg = 0;
						foreach( $mailpoet_fields as $field) {
							if ( sanitize_title($field['name']) == sanitize_title($afield) ) {
								$amp_fields[$custom_field[ 'custom_field_name']] = $field['id'];
								$aflg = 1;
							}
						}
						if ( $aflg == 0 ) {
							$afield_missing[] = $afield;
						}
					}
					/* Create Phone and Message field */
					if ( !empty($afield_missing)) {
						if ( in_array( 'Phone', $afield_missing)) {
							$subscriber_field_data = [
							'name' => 'Phone',
							'type' => 'text',
							'params ' => [
										'required' => 0,
										'label' => 'Phone',
										'values' => '',
									],
							];
							try{
								\MailPoet\API\API::MP( 'v1' )->addSubscriberField( $subscriber_field_data  );
							} catch( Exception $e ) {
								echo 'Caught exception: ',  $e->getMessage(), "\n";
							}
						}

						if ( in_array( 'Message', $afield_missing)) {
							$subscriber_field_data = [
							'name' => 'Message',
							'type' => 'textarea',
							'params ' => [
										'required' => 0,
										'label' => 'Message',
										'values' => '',
									],
							];
							try{
								\MailPoet\API\API::MP( 'v1' )->addSubscriberField( $subscriber_field_data  );
							} catch( Exception $e ) {
								echo 'Caught exception: ',  $e->getMessage(), "\n";
							}
						}
						
						if ( isset($contact_form['dropdown']) && $contact_form['dropdown'] == 1 && in_array( 'Dropdown', $afield_missing)) {
							
							$subscriber_field_data = [
							'name' => 'Dropdown',
							'type' => 'select',
							'params ' => [
										'required' => 0,
										'label' => 'Message',
										'values' => '',
									],
							];
							
							unset($subscriber_field_data['params']);
							unset($subscriber_field_data['params']);
							$dropdown_option = [];
							foreach( array_filter($contact_form['dropdown-option']) as $option) {
								$dropdown_option[] = ['value' =>$option ];
							}
							$subscriber_field_data['params']['values'] = $dropdown_option;
							try{
								\MailPoet\API\API::MP( 'v1' )->addSubscriberField( $subscriber_field_data  );
							} catch( Exception $e ) {
								echo 'Caught exception: ',  $e->getMessage(), "\n";
							}
						}
					}

				}

				if ( !empty($field_missing)) {
					foreach( $field_missing as $field ) {
						$subscriber_field_data = [
							'name' => $field['custom_field_name'],
							'type' => ($field['field_dropdown'] == 'number' || $field['field_dropdown'] == 'url' || $field['field_dropdown']== 'date') ? 'text' : ( ( $field['field_dropdown'] == 'dropdown') ? 'select' : $field['field_dropdown'] ),


							'params ' => [
										'required' => 0,
										'label' => $field['custom_field_name'],
										'values' => ( $field['field_dropdown'] == 'dropdown') ? array_filter($field['dropdown-option']) : '',
									],
							];

						if ($field['field_dropdown']== 'date' ) {
							$subscriber_field_data['params']['date_type'] = 'year_month_day';
							$subscriber_field_data['params']['date_format'] = 'YYYY/MM/DD';
						}

						if ($field['field_dropdown']== 'dropdown' ) {
							unset($subscriber_field_data['params']);
							unset($subscriber_field_data['params']);
							$dropdown_option = [];
							foreach( array_filter($field['dropdown-option']) as $option) {
								$dropdown_option[] = ['value' =>$option ];
							}
							$subscriber_field_data['params']['values'] = $dropdown_option;
						}

						try{
							\MailPoet\API\API::MP( 'v1' )->addSubscriberField( $subscriber_field_data  );
						} catch( Exception $e ) {
							echo 'Caught exception: ',  $e->getMessage(), "\n";
						}
					}
				}
				$mailpoet_fields = \MailPoet\API\API::MP( 'v1' )->getSubscriberFields();

				$mp_fields = [];
				if ( !empty($contact_form['custom_fields']) ) {
					foreach( $contact_form['custom_fields'] as $custom_field ) {
						foreach( $mailpoet_fields as $field) {
							if ( sanitize_title($field['name']) == sanitize_title($custom_field[ 'custom_field_name']) ) {
								$mp_fields[$custom_field[ 'custom_field_name']] = $field['id'];

							}
						}
					}
				}

				$subscriber_data = array( 'email' => sanitize_email($_POST['contact-form-email']), 'first_name' => sanitize_text_field($_POST['contact-form-name']), 'status' => 'Subscribed' );

				foreach( $mp_fields as $key=>$field ){
					$subscriber_data[$field] = $custom_fields_value[$key];
				}
				foreach( $mailpoet_fields as $field) {
					if ( $field['name'] == 'Phone') {
						if (isset($_POST['contact-form-phone']) && $_POST['contact-form-phone'] != '') {
							$subscriber_data[$field['id']] =  sanitize_text_field($_POST['contact-form-phone']);
						}
					}
					if ( $field['name'] == 'Message') {
						if (isset($_POST['contact-form-message']) && $_POST['contact-form-message'] != '') {
							$subscriber_data[$field['id']] =  sanitize_text_field($_POST['contact-form-message']);
						}
					}
					if ( $field['name'] == 'Dropdown') {
						if (isset($_POST['contact-form-dropdown']) && $_POST['contact-form-dropdown'] != '') {
							$subscriber_data[$field['id']] =  sanitize_text_field($_POST['contact-form-dropdown']);
						}
					}
				}

				$result          = 'success';
				$list_id = $contact_form['mailpoet_list'];
				$lists           = array( $list_id );				
				try {
					\MailPoet\API\API::MP( 'v1' )->addSubscriber( $subscriber_data, $lists );
				} catch ( Exception $exception ) {
					$result = $exception->getMessage();
				}
			}

		}
		public function contactleadpushmailchimp($contact_form, $custom_fields_value) {

			$elements_mc_api_key = get_option('elements_mc_api_key');
            $list_id = $contact_form['mailchimp_list'];
			$mailchimp_tags = $contact_form['mailchimp_tags'];
			$mailchimp_status = 'subscribed';
			$sfba_mailchimp_groups = $contact_form['mailchimp-group'];
			$interests = array();
			if( isset($contact_form['mailchimp-enable-group']) && $contact_form['mailchimp-enable-group'] == 'yes' && !empty($sfba_mailchimp_groups)) {
				foreach( $sfba_mailchimp_groups as $group ){
					$interests[ $group] = true ;
				}
			}
			$merge_fields = [
				'FNAME'		=> sanitize_text_field($_POST['contact-form-name']),
				'LNAME'		=> '',
				'PHONE'		=> sanitize_text_field($_POST['contact-form-phone']),
				'MESSAGE'	=> sanitize_text_field($_POST['contact-form-message']),
			];

			/* Merge Custom fields*/
			if( isset($contact_form['mailchimp-field-mapping']) &&  is_array($contact_form['mailchimp-field-mapping']) ) {
				foreach( $contact_form['mailchimp-field-mapping'] as $fields_key=>$fields) {
					foreach($custom_fields_value as $custom_field_key=>$custom_field_value ) {
						if ( $fields_key == sanitize_title($custom_field_key) ) {
							$merge_fields[$fields] = $custom_field_value;
						}
					}
				}
			}

			/* Merge Dropdown fields */
			if (isset($_POST['contact-form-dropdown']) && $_POST['contact-form-dropdown'] != '') {
				$merge_fields[$contact_form['mailchimp-field-mapping']['dropdown']] = $_POST['contact-form-dropdown'];
			}


			/*
			echo "<pre>";
			print_r($contact_form['mailchimp-field-mapping']);
			print_r($merge_fields);
			print_r($custom_fields_value);
			echo "</pre>";
			exit;
			*/
			$post_data = [
						'email_address' => sanitize_email($_POST['contact-form-email']),
						'status'        => $mailchimp_status, // "subscribed","unsubscribed","cleaned","pending"
						'merge_fields'  => $merge_fields,
						'email_type'	=> 'html',
						'interests'		=> $interests
					];

			if (empty($interests) ) {
				unset($post_data['interests']);
			}

			$headers = array(
				'Authorization' => 'Basic ' . base64_encode('user:'.$elements_mc_api_key),
				'Content-Type'	=> 'application/json',
			);
			$args = array(
				'method' 		=> 'PUT',
				'timeout' 		=> 45,
				'redirection' 	=> 5,
				'httpversion' 	=> '1.0',
				'blocking' 		=> true,
				'user-agent'  	=> 'WordPress/' . $wp_version . '; ' . home_url(),
				'headers'     	=> $headers,
				'body'		  	=> wp_json_encode($post_data),
				'cookies' 		=> array(),
				'sslverify'		=> true,
			);
			$memberId = md5(strtolower( sanitize_email($_POST['contact-form-email']) ));
			$dataCenter = substr($elements_mc_api_key,strpos($elements_mc_api_key,'-')+1);
			$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . $memberId;
			$response = wp_remote_post( $url, $args );
			$api_response_body = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( $mailchimp_tags != '' && isset($contact_form['mailchimp_enable_tag']) && $contact_form['mailchimp_enable_tag'] == 'yes' ) {
				$tags = explode( ',', $mailchimp_tags);
				$tags = array_map( 'trim', $tags );

				// remove empty tag values
				foreach ( $tags as $i => $tag ) {
					if ( $tag === '' ) {
						unset( $tags[ $i ] );
					}
				}

				$tags = array_values( $tags );

				$post_data = [
							"tags" => myStickyelements_merge_and_format_member_tags(array(),$tags)
						];
				$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . $memberId . '/tags';
				$headers = array(
					'Authorization' => 'Basic ' . base64_encode('user:'.$elements_mc_api_key),
					'Content-Type'	=> 'application/json',
				);
				$args = array(
					'method' 		=> 'POST',
					'timeout' 		=> 45,
					'redirection' 	=> 5,
					'httpversion' 	=> '1.0',
					'blocking' 		=> true,
					'user-agent'  	=> 'WordPress/' . $wp_version . '; ' . home_url(),
					'headers'     	=> $headers,
					'body'		  	=> wp_json_encode($post_data),
					'cookies' 		=> array(),
					'sslverify'		=> true,
				);
				$response = wp_remote_post( $url, $args );
				$api_response_body = json_decode( wp_remote_retrieve_body( $response ), true );
			}


		}
		function get_user_ipaddress() {
			if(!empty($_SERVER['HTTP_CLIENT_IP'])){
				//ip from share internet
				$ip = sanitize_text_field( wp_unslash($_SERVER['HTTP_CLIENT_IP']));
			}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
				//ip pass from proxy
				$ip = sanitize_text_field( wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR']));
			}else{
				$ip = sanitize_text_field( wp_unslash($_SERVER['REMOTE_ADDR']));
			}
			return $ip;
		}

		function mystickyelement_traffic_source() {
			$traffic_source = [
				"search_engine" => array(
					'accoona',
					'ansearch',
					'biglobe',
					'daum',
					'egerin	',
					'leit.is',
					'maktoob',
					'miner.hu',
					'najdi.si',
					'najdi.org',
					'naver',
					'rambler',
					'rediff',
					'sapo',
					'search.ch',
					'sesam',
					'seznam',
					'walla',
					'zipLoca',
					'slurp',
					'search.msn.com',
					'nutch',
					'simpy',
					'bot.',
					'aspSeek',
					'crawler.',
					'msnbot',
					'libwww-perl',
					'fast',
					'baidu.',
					'bing.',
					'google.',
					'duckduckgo',
					'ecosia',
					'exalead',
					'giablast',
					'munax',
					'qwant',
					'sogou',
					'soso',
					'yahoo.',
					'yandex.',
					'youdao',
					'aol.',
					'hotbot.',
					'webcrawler.',
					'eniro',
					'naver',
					'lycos',
					'ask',
					'altavista',
					'netscape',
					'about',
					'mamma',
					'alltheweb',
					'voila',
					'live',
					'alice',
					'mama',
					'wp.pl',
					'onecenter',
					'szukacz',
					'yam',
					'kvasir',
					'ozu',
					'terra',
					'pchome',
					'mynet',
					'ekolay',
					'rembler',
				),
				"social_media" => array(
					"facebook.",
					"instagram.",
					"linkedin.",
					"myspace.",
					"twitter.",
					"t.co",
					"plus.google",
					"disqus.",
					"snapchat.",
					"tumbler.",
					"pinterest.",
					"twoo",
					"mymfb",
					"youtube.",
					"vine",
					"whatsapp",
					"vk.com",
					"secret",
					"medium",
					"bebo",
					"friendster",
					"hi5",
					"habbo",
					"ning",
					"classmates",
					"tagged",
					"myyearbook",
					"meetup",
					"mylife",
					"reunion",
					"flixster",
					"myheritage",
					"multiply",
					"orkut",
					"badoo",
					"gaiaonline",
					"blackplanet",
					"skyrock",
					"perfspot",
					"zorpia",
					"netlog",
					"tuenti",
					"nasza-klasa.pl",
					"irc-gallery",
					"studivz",
					"xing",
					"renren",
					"kaixin001",
					"hyves.nl",
					"MillatFacebook",
					"ibibo",
					"sonico",
					"wer-kennt-wen",
					"cyworld",
					"iwiw",
					"dribbble.",
					"stumbleupon.",
					"flickr.",
					"plaxo.",
					"digg.",
					"del.icio.us"
				),
			];

			return $traffic_source;
		}

		function getVisitorTrafficSource( $general_settings, $element_widget_no ) {

			$element_widget_no = ($element_widget_no != '') ? $element_widget_no : 0;
			$origin_landing_page	= '';
			$HTTP_REFERER 			= ( isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
			if ( isset( $_COOKIE['MSE_HTTP_REFERER']) && $_COOKIE['MSE_HTTP_REFERER'] != '' ) {
				$HTTP_REFERER = $_COOKIE['MSE_HTTP_REFERER'];
			}
			if ( $HTTP_REFERER != '' ) {
				@setcookie('MSE_HTTP_REFERER', $HTTP_REFERER, time() + (86400 * 30), "/"); // 86400 = 1 day
			}

			$traffic_source  = ( isset($general_settings['traffic-source'])) ? $general_settings['traffic-source'] : '';
			$direct_visit  	 = ( isset($traffic_source['direct-visit'])) ? $traffic_source['direct-visit'] : '';
			$social_network  = ( isset($traffic_source['social-network'])) ? $traffic_source['social-network'] : '';
			$search_engines  = ( isset($traffic_source['search-engines'])) ? $traffic_source['search-engines'] : '';
			$google_ads      = ( isset($traffic_source['google-ads'])) ? $traffic_source['google-ads'] : '';
			$other_source_option	= ( isset($traffic_source['other-source-option'])) ? $traffic_source['other-source-option'] : '';
			$other_source_url      	= ( isset($traffic_source['other-source-url'])) ? $traffic_source['other-source-url'] : array('');

			$furl = false;
			foreach( $other_source_url as $surl ){
				if ( $surl != '') {
					$furl = true;
				}
			}
			if ( !$furl){
				$other_source_url = array();
			}

			if ( $direct_visit =='' && $social_network == '' &&  $search_engines == '' && $google_ads == '' && empty($other_source_url) ) {
				return true;
			}

			/* is_traffic_source is enable */
			if ( isset($_COOKIE['traffic_source-'. $element_widget_no]) &&  $_COOKIE['traffic_source-'. $element_widget_no] != '' ) {
				return $_COOKIE['traffic_source-'. $element_widget_no];
			}

			$coupon_traffic_source = $this->mystickyelement_traffic_source();

			$response = false;
			$visitor_referel = ( (isset($HTTP_REFERER) && $HTTP_REFERER !='' ) ? parse_url($HTTP_REFERER)['host'] : '' );

			// if Direct link
			if ( ( ( empty($visitor_referel) || $_SERVER['HTTP_HOST'] == $visitor_referel || (isset($_SERVER['HTTP_ORIGIN']) && (parse_url($_SERVER['HTTP_ORIGIN'])['host'] == $visitor_referel )) ) ) &&  $direct_visit == true ){
				$response = "direct_link";

			}

			// if search_engine
			if ( !$response && $search_engines == true ) {
				foreach($coupon_traffic_source['search_engine'] as $source){
					if ( (strpos($visitor_referel, $source) !== false) ) {
						if ( $source == "google." && strpos($visitor_referel,"plus.google" ) !== false  ){
							break;
						}else{
							$response = "search_engine";
							break;
						}
					}
				}
			}

			// if social_media
			if ( !$response && $social_network == true){
					foreach($coupon_traffic_source['social_media'] as $source){
						if ( strpos($visitor_referel, $source) !== false ) {
							$response = "social_media";
							break;
						}
					}
			}

			// if google_ads
			if ( $google_ads == true && !$response &&  isset($origin_landing_page) && !empty($origin_landing_page) ){
				if ((strpos($origin_landing_page, 'gclid=') !== false)){
					$response = "google_ads";
				}
			}

			// if contein specific url
			if ( !empty( $other_source_url) && !$response) {
				$flag =  $this->checkSpecifixUrlInRolesTrafficSource( $general_settings );
				if ( $flag ){
					$response = "specific_url";
				}else{
					$response = false;
				}
			}

			if ( $response != '' ) {
				@setcookie('traffic_source-' . $element_widget_no, $response, time() + (86400 * 30), "/"); // 86400 = 1 day
			}
			return $response;
		}
		 function checkSpecifixUrlInRolesTrafficSource( $general_settings ) {
			$flag = true;
			$flag_array = array();
			$contain_flag_array = array();
			$not_contain_flag_array = array();

			$traffic_source  = ( isset($general_settings['traffic-source'])) ? $general_settings['traffic-source'] : '';
			$other_source_option	= ( isset($traffic_source['other-source-option'])) ? $traffic_source['other-source-option'] : '';
			$other_source_url      	= ( isset($traffic_source['other-source-url'])) ? $traffic_source['other-source-url'] : '';
			$HTTP_REFERER 			= ( isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
			if ( isset( $_COOKIE['MSE_HTTP_REFERER']) && $_COOKIE['MSE_HTTP_REFERER'] != '' ) {
				$HTTP_REFERER = $_COOKIE['MSE_HTTP_REFERER'];
			}

			$referer = (isset($HTTP_REFERER) ? parse_url($HTTP_REFERER) : 'empty' );

			if ($referer == 'empty' || !isset($referer['host'])){
				return true;
			}
			$referer_host =  $this->removeWWW($referer['host']);
			$query = (isset($referer['query']) && !empty($referer['query']) ? '?'.$referer['query']:'');
			$referer_path =  $referer['path'].$query;
			$referer_path =  strtolower(str_replace("/", "%2f", $referer_path));
			$contain_array = array();
			$not_contain_array = array();


			for($i=0; $i<sizeof($other_source_url); $i++ ) {
				if ( isset( $other_source_url[$i] ) && $other_source_url[$i] != '' ) {
					if ( $other_source_option[$i] === 'contain' ){
						$contain_array[] = array( $other_source_option[$i], $other_source_url[$i] );
					}else{
						$not_contain_array[] = array( $other_source_option[$i], $other_source_url[$i] );
					}
				}
			}


			if ( !empty($contain_array) ) {
				foreach($contain_array as $key=>$value){
					$role_link = parse_url($value[1]);
					$role_host = $this->removeWWW( $role_link['host'] );
					$role_path = '';
					if(isset($role_link['path'])){
						$role_path =  $role_link['path'];
					}else{
						$role_path = '';
					}
					if(isset($role_link['query'])){
						$role_path .=  '?'.$role_link['query'];
					}

					$role_path = (preg_match("/\p{Hebrew}/u", $role_path) ? $role_path : str_replace("/","%2f",$role_path));
					$role_path = strtolower(str_replace("&amp;","&",$role_path));
					$role_path = trim($role_path);
					if ($role_path == ''){
						$role_path = '/';
					}
					if ($referer_path == ''){
						$referer_path = '/';
					}
					if ($role_host != $referer_host){

						$flag = false;
					}else if(empty($role_path) && empty($referer_path)){

						$flag = true;
					}else if(strtolower(urlencode($role_path)) == strtolower($referer_path) && strtolower($referer_path)=='%2f'){

						$flag = true;
					}else{
						switch($value[0]){
							case 'contain':
								if (empty($role_path) && !empty($referer_path)){
									$flag = true;
								}else if ($role_path == "/" || $role_path=="%2f") {
									$flag = true;
								}else if (strpos($referer_path,( preg_match("/\p{Hebrew}/u", $role_path) ? strtolower(urlencode($role_path)) : strtolower($role_path) )) !== false){
									$flag = true;
								}else if (strpos($referer_path.'/',( preg_match("/\p{Hebrew}/u", $role_path) ? strtolower(urlencode($role_path)) : strtolower($role_path) )) !== false){
									$flag = true;
								}else if (strpos($referer_path.'%2f',( preg_match("/\p{Hebrew}/u", $role_path) ? strtolower(urlencode($role_path)) : strtolower($role_path) )) !== false){
									$flag = true;
								}else{
									$flag = false;
								}
								break;
						}
						$and = $flag;
					}
					$flag_array[] = $flag;
					$contain_flag_array[] = $flag;
				}
			}

			if ( !empty( $not_contain_array ) ) {
				foreach($not_contain_array as $key=>$value){
					$role_link = parse_url($value[1]);

					$role_host = $this->removeWWW( $role_link['host'] );

					$role_path = '';
					if(isset($role_link['path'])){
						$role_path =  $role_link['path'];
					}else{
						$role_path = '';
					}
					if(isset($role_link['query'])){
						$role_path .=  '?'.$role_link['query'];
					}
					$role_path = (preg_match("/\p{Hebrew}/u", $role_path) ? $role_path : str_replace("/","%2f",$role_path));
					$role_path = str_replace("&amp;","&",$role_path);
					$role_path = trim($role_path);
					if ($role_path == ''){
						$role_path = '/';
					}
					if ($referer_path == ''){
						$referer_path = '/';
					}

					if ($role_host == $referer_host && (empty($role_path) || $role_path=="%2f") && (empty($referer_path) || $referer_path=="%2f")){
						$flag = false;
					} else{
						switch($value[0]){
							case 'not_contain':
								if (isset($referer_path) && strpos(strtolower($referer_path),((preg_match("/\p{Hebrew}/u", $role_path)) ? strtolower(urlencode($role_path)) : strtolower($role_path)))!== false){
									$flag = false;
								}else if ($role_path == "/" || $role_path=="%2f"){
									$flag = false;
								}else{
									$flag = true;
								}
							break;
						}
					}
					$flag_array[] = $flag;
					$not_contain_flag_array[] = $flag;
				}
			}

			if (!empty($not_contain_array) && empty($contain_array)){
				return (in_array(false, $not_contain_flag_array) ? false : true );
			}else if (!empty($not_contain_array) && !empty($contain_array)){
				if (in_array(false, $not_contain_flag_array)){
					return false;
				}else{
					return (in_array(true, $contain_flag_array) ? true : false );
				}
			}else if (empty($not_contain_array) && !empty($contain_array)){
				return (in_array(true, $contain_flag_array) ? true : false );
			}else{
				return $flag;
			}
		}

		function removeWWW( $url ) {
			return str_replace('www.','',$url );
		}

		function getDaysHoursTime( $general_settings ) {
			$displayStatus = 0;

			if ( isset($general_settings['days-hours']) && !empty($general_settings['days-hours']) ) {

				foreach ($general_settings['days-hours'] as $key => $value) {
					$record = array();
					$record['days'] = $value['days'] - 1;
					$record['start_time'] = $value['start_time'];
					$record['start_hours'] = intval(date("G", strtotime(date("Y-m-d " . $value['start_time']))));
					$record['start_min'] = intval(date("i", strtotime(date("Y-m-d " . $value['start_time']))));
					$record['end_time'] = $value['end_time'];
					$record['end_hours'] = intval(date("G", strtotime(date("Y-m-d " . $value['end_time']))));
					$record['end_min'] = intval(date("i", strtotime(date("Y-m-d " . $value['end_time']))));
					$record['gmt'] = $value['gmt'];
					$display_rules[] = $record;
				}

				$date = date('d-m-y h:i:s');
				foreach ( $display_rules as $key=>$value ) {
					$gmt = str_replace('UTC','',$value['gmt']);
					if ( is_numeric($gmt)) {
						$current_date =  gmdate("Y-m-d H:i", time() + 3600*($gmt+date("I")));
					} else {
						date_default_timezone_set(str_replace('UTC','',$gmt));
						$current_date = date('Y-m-d H:i');
					}
					$utcHours = date( 'H', strtotime( $current_date ) );
					$utcMin = date( 'i', strtotime( $current_date ) );
					$utcDay = date( 'w', strtotime( $current_date ) );

					$hourStatus = 0;
                    $minStatus = 0;
                    $checkForTime = 0;
                    $nextday = 0;
					//echo $current_date ."==" . $utcHours ."==" . $utcMin ." == " . $utcDay. "\r\n<br>";

					if ( $value['start_hours'] == 0 && $value['end_hours'] == 0 && $value['start_time'] == '' && $value['end_time'] == '' ) {
						$displayStatus = 1;
					}

					if ($value['days'] == -1) {
                        $checkForTime = 1;
                    } else if ($value['days'] >= 0 && $value['days'] <= 6) {
                        if ($value['days'] == $utcDay) {
                            $checkForTime = 1;
                        }
                    } else if ($value['days'] == 7) {
                        if ($utcDay >= 0 && $utcDay <= 4) {
                            $checkForTime = 1;
                        }
                    } else if ($value['days'] == 8) {
                        if ($utcDay >= 1 && $utcDay <= 5) {
                            $checkForTime = 1;
                        }
                    } else if ($value['days'] == 9) {
                        if ($utcDay == 5 || $utcDay == 6) {
                            $checkForTime = 1;
                        }
                    }

					if ( $value['start_hours'] > $value['end_hours']) {
                        if($checkForTime == 0){
                            $value['days'] = $value['days'] + 1;
                        }
                        if ($value['days'] == 0) {
                            $checkForTime = 1;
                        }else if ($value['days'] >= 1 && $value['days'] <= 7) {
                            $checkForTime = 1;
                        }else if ($value['days'] == 8) {
                            if ($utcDay >= 1 && $utcDay <= 5) {
                                $checkForTime = 1;
                            }
                        }else if ($value['days'] == 9) {
                            if ($utcDay >= 2 && $utcDay <= 6) {
                                $checkForTime = 1;
                            }
                        } else if ($value['days'] == 10) {
                            if ($utcDay == 6 || $utcDay == 0) {
                                $checkForTime = 1;
                            }
                        }
                        if(0 <= $value['end_hours'] && $utcHours>= $value['end_hours']){
                            $nextday = 1;
                        }
                    }
					if ( $checkForTime == 1) {

                        if ( $utcHours > $value['start_hours'] && $utcHours < $value['end_hours'] ) {
                            $hourStatus = 1;
                        } else if ( $utcHours == $value['start_hours'] && $utcHours < $value['end_hours']) {
                            if ( $utcMin >= $value['start_min']) {
                                $hourStatus = 1;
                            }
                        } else if ( $utcHours > $value['start_hours'] && $utcHours == $value['end_hours']) {
                            if ( $utcMin <= $value['end_min']) {
                                $hourStatus = 1;
                            }
                        } else if ($utcHours == $value['start_hours'] && $utcHours == $value['end_hours']) {
                            if ( $utcMin >= $value['start_min'] && $utcMin <= $value['end_min']) {
                                $hourStatus = 1;
                            }
                        } else if ($value['start_hours'] > $value['end_hours']) {
                            if( $utcHours >= $value['start_hours']){
                                if ($utcMin >= $value['start_min'] && $utcMin <= $value['end_min']) {
                                    $hourStatus = 1;
                                }
                            }

                            if( $nextday == 1 && $utcHours <= $value['end_hours']){
                                if ($utcMin >= $value['start_min'] && $utcMin <= $value['end_min']) {
                                    $hourStatus = 1;
                                }
                            }
                        }

                        if ( $hourStatus == 1) {
                            if ( $utcMin >= $value['start_min'] && $utcMin <= $value['end_min'] ) {
                                $minStatus = 1;
                            }
                        }
                    }

					if ( $hourStatus == 1 && $checkForTime == 1) {
						$displayStatus = 1;
						break;
					}

				}
			} else {
				$displayStatus = 1;
			}

			return $displayStatus ;
		}

	}

}
global $front_settings_page;
$front_settings_page = new MyStickyElementsFrontPage_pro();
