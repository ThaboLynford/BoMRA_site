<?php
//include( 'mystickyelements_timezone.php' );
/*echo "<pre>";
print_r($general_settings);
echo "</pre>";*/

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
?>

<div class="myStickyelements-header-title">
	<h3><?php _e('General Settings', 'mystickyelements'); ?></h3>
</div>

<div class="myStickyelements-content-section">
	<table>
		<tr>
			<td>
				<span class="myStickyelements-label" ><?php _e( 'Templates', 'mystickyelements' );?></span>
				<div class="myStickyelements-inputs myStickyelements-label">
					<?php $general_settings['templates'] = (isset($general_settings['templates']) && $general_settings['templates']!= '') ? $general_settings['templates'] : 'default'; ?>
					<select id="myStickyelements-inputs-templete" name="general-settings[templates]" >
						<option value="default" <?php selected( @$general_settings['templates'], 'default' ); ?>><?php _e( 'Default', 'mystickyelements' );?></option>
						<option value="sharp" <?php selected( @$general_settings['templates'], 'sharp' ); ?>><?php _e( 'Sharp ', 'mystickyelements' );?></option>
						<option value="roundad" <?php selected( @$general_settings['templates'], 'roundad' ); ?>><?php _e( 'Rounded', 'mystickyelements' );?></option>
						<option value="leaf_right" <?php selected( @$general_settings['templates'], 'leaf_right' ); ?>><?php _e( 'Leaf right', 'mystickyelements' );?></option>
						<option value="round" <?php selected( @$general_settings['templates'], 'round' ); ?>><?php _e( 'Round', 'mystickyelements' );?></option>
						<option value="diamond" <?php selected( @$general_settings['templates'], 'diamond' ); ?>><?php _e( 'Diamond', 'mystickyelements' );?></option>
						<option value="leaf_left" <?php selected( @$general_settings['templates'], 'leaf_left' ); ?>><?php _e( 'Leaf left', 'mystickyelements' );?></option>
						<option value="arrow" <?php selected( @$general_settings['templates'], 'arrow' ); ?>><?php _e( 'Arrow', 'mystickyelements' );?></option>
						<option value="triangle" <?php selected( @$general_settings['templates'], 'triangle' ); ?>><?php _e( 'Triangle', 'mystickyelements' );?></option>
					</select>
				</div>
			</td>
			<td rowspan="7">

			</td>
		</tr>
		<tr>
			<td>
				<span class="myStickyelements-label" ><?php _e( 'Position on desktop', 'mystickyelements' );?></span>
				<div class="myStickyelements-inputs">
					<ul>
						<li>
							<label>
								<input type="radio" name="general-settings[position]" value="left" <?php checked( @$general_settings['position'], 'left' );?> />
								<?php _e( 'Left', 'mystickyelements' );?>
							</label>
						</li>
						<li class="myStickyelements-pos-rtl">
							<label>
								<input type="radio" name="general-settings[position]" value="right" <?php checked( @$general_settings['position'], 'right' );?> />
								<?php _e( 'Right', 'mystickyelements' );?>
							</label>
						</li>
						<li>
							<label>
								<input type="radio" name="general-settings[position]" value="bottom" <?php checked( @$general_settings['position'], 'bottom' );?> />
								<?php _e( 'Bottom', 'mystickyelements' );?>
							</label>
						</li>
					</ul>
				</div>
			</td>
			<td rowspan="7">

			</td>
		</tr>
		<tr class="myStickyelements-position-on-screen-wrap" style="<?php echo (isset($general_settings['position']) && $general_settings['position'] != 'bottom') ? 'display: none;' : ''; ?>">
			<td>
				<span class="myStickyelements-label" ><?php _e( 'Position on screen', 'mystickyelements' );?></span>
				<div class="myStickyelements-inputs myStickyelements-label">
					<?php $general_settings['position_on_screen'] = (isset($general_settings['position_on_screen']) && $general_settings['position_on_screen']!= '') ? $general_settings['position_on_screen'] : 'center'; ?>
					<select id="myStickyelements-inputs-position-on-screen" name="general-settings[position_on_screen]" >
						<option value="center" <?php selected( @$general_settings['position_on_screen'], 'center' ); ?>><?php _e( 'Center', 'mystickyelements' );?></option>
						<option value="left" <?php selected( @$general_settings['position_on_screen'], 'left' ); ?>><?php _e( 'Left', 'mystickyelements' );?></option>
						<option value="right" <?php selected( @$general_settings['position_on_screen'], 'right' ); ?>><?php _e( 'Right', 'mystickyelements' );?></option>
					</select>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<span class="myStickyelements-label" ><?php _e( 'Position on mobile', 'mystickyelements' );?></span>
				<div class="myStickyelements-inputs">
					<ul>
						<li>
							<label>
								<input type="radio" name="general-settings[position_mobile]" value="left" <?php checked( @$general_settings['position_mobile'], 'left' );?> />
								<?php _e( 'Left', 'mystickyelements' );?>
							</label>
						</li>
						<li class="myStickyelements-pos-rtl">
							<label>
								<input type="radio" name="general-settings[position_mobile]" value="right" <?php checked( @$general_settings['position_mobile'], 'right' );?> />
								<?php _e( 'Right', 'mystickyelements' );?>
							</label>
						</li>
						<li>
							<label>
								<input type="radio" name="general-settings[position_mobile]" value="top" <?php checked( @$general_settings['position_mobile'], 'top' );?> />
								<?php _e( 'Top', 'mystickyelements' );?>
							</label>
						</li>
						<li>
							<label>
								<input type="radio" name="general-settings[position_mobile]" value="bottom" <?php checked( @$general_settings['position_mobile'], 'bottom' );?> />
								<?php _e( 'Bottom', 'mystickyelements' );?>
							</label>
						</li>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<span class="myStickyelements-label" ><?php _e( 'Open tabs when', 'mystickyelements' );?></span>
				<div class="myStickyelements-inputs">
					<ul>
						<li>
							<label>
								<input type="radio" name="general-settings[open_tabs_when]" value="hover" <?php checked( @$general_settings['open_tabs_when'], 'hover' );?> />
								<?php _e( 'Hover', 'mystickyelements' );?>
							</label>
						</li>
						<li>
							<label>
								<input type="radio" name="general-settings[open_tabs_when]" value="click" <?php checked( @$general_settings['open_tabs_when'], 'click' );?> />
								<?php _e( 'Click', 'mystickyelements' );?>
							</label>
						</li>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<span class="myStickyelements-label" >
					<label for="myStickyelements-form_open_automatic"><?php _e( 'Open the form automatically', 'mystickyelements' );?></label>
					<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip">
						<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
						<p><?php esc_html_e("The form will automatically open up on page load until the user closes the form or fills out the form", 'mystickyelements'); ?></p>
					</div>
				</span>
				
				<div class="myStickyelements-inputs myStickyelements-label myStickyelements-form-open">
					<label for="myStickyelements-form_open_automatic" class="myStickyelements-switch" >
						<input type="checkbox" id="myStickyelements-form_open_automatic" name="general-settings[form_open_automatic]"<?php checked( @$general_settings['form_open_automatic'], '1' );?>  value="1" />
						<span class="slider round"></span>
					</label>												
				</div>
			</td>
		</tr>
		<tr class="myStickyelements-position-desktop-wrap" style="<?php echo (isset($general_settings['position']) && $general_settings['position'] == 'bottom') ? 'display: none;' : ''; ?>">
			<td>
				<span class="myStickyelements-label" >
					<label for="custom_position"><?php _e( 'On-Screen Position Y Desktop', 'mystickyelements' );?></label>
					<?php if(!$is_pro_active) {?><span class="upgrade-myStickyelements"><a href="<?php echo $upgrade_url ?>" target="_blank"><i class="fas fa-lock"></i><?php _e('ACTIVATE YOUR KEY', 'mystickyelements' );?></a></span><?php } ?>
				</span>

				<div class="myStickyelements-inputs">
					<div class="px-wrap px-wrap-left">
						<input  <?php echo !$is_pro_active?"disabled":"" ?> type="number" id="custom_position"  name="general-settings[custom_position]" value="<?php echo @$general_settings['custom_position'];?>" placeholder="[optional]" />
						<span class="input-px">PX</span>
					</div>
					<div class="px-wrap px-wrap-right">
						<select name="general-settings[custom_position_from]">
							<option value="bottom" <?php if(isset($general_settings['custom_position_from']) && $general_settings['custom_position_from'] == 'bottom'): echo "selected"; endif; ?>>From bottom</option>
							<option value="top" <?php if(isset($general_settings['custom_position_from']) && $general_settings['custom_position_from'] == 'top'): echo "selected"; endif; ?>>From top</option>
						</select>
					</div>
					
				</div>
			</td>
		</tr>
		<tr class="myStickyelements-position-mobile-wrap" style="<?php echo (isset($general_settings['position_mobile']) && ($general_settings['position'] == 'bottom' || $general_settings['position'] == 'top' )) ? 'display: none;' : ''; ?>">
			<td>
				<span class="myStickyelements-label" >
					<label for="custom_position_mobile"><?php _e( 'On-Screen Position Y Mobile', 'mystickyelements' );?></label>
					<?php if(!$is_pro_active) {?><span class="upgrade-myStickyelements"><a href="<?php echo $upgrade_url ?>" target="_blank"><i class="fas fa-lock"></i><?php _e('ACTIVATE YOUR KEY', 'mystickyelements' );?></a></span><?php } ?>
				</span>

				<div class="myStickyelements-inputs">
					<div class="px-wrap px-wrap-left">
						<input  <?php echo !$is_pro_active?"disabled":"" ?> type="number" id="custom_position_mobile"  name="general-settings[custom_position_mobile]" value="<?php echo @$general_settings['custom_position_mobile'];?>" placeholder="[optional]" />
						<span class="input-px">PX</span>
					</div>
					<div class="px-wrap px-wrap-right">
						<select name="general-settings[custom_position_from_mobile]">
							<option value="bottom" <?php if(isset($general_settings['custom_position_from_mobile']) && $general_settings['custom_position_from_mobile'] == 'bottom'): echo "selected"; endif; ?>>From bottom</option>
							<option value="top" <?php if(isset($general_settings['custom_position_from_mobile']) && $general_settings['custom_position_from_mobile'] == 'top'): echo "selected"; endif; ?>>From top</option>
						</select>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<span class="myStickyelements-label">
					<label for="myStickyelements-minimize-tab">
						<?php esc_html_e( 'Minimize tab', 'mystickyelements' );?>
					</label>
				</span>
				<div class="myStickyelements-inputs myStickyelements-label myStickyelements-minimize-tab">
					<label for="myStickyelements-minimize-tab" class="myStickyelements-switch" >
						<input type="checkbox" id="myStickyelements-minimize-tab" name="general-settings[minimize_tab]"<?php checked( @$general_settings['minimize_tab'], '1' );?>  value="1" />
						<span class="slider round"></span>
					</label>
					&nbsp;
					<input type="text" id="minimize_tab_background_color" name="general-settings[minimize_tab_background_color]" class="mystickyelement-color" value="<?php echo esc_attr($general_settings['minimize_tab_background_color']);?>" />
				</div>
			</td>
		</tr>
		<tr class="myStickyelements-minimized">
			<td>
				<span class="myStickyelements-label">
					<label>
						<?php esc_html_e( 'Minimized bar on load', 'mystickyelements' );?>
					</label>
				</span>
				<div class="myStickyelements-inputs">
					<ul>
						<li>
							<label>
								<input type="checkbox" name="general-settings[minimize_desktop]" value="desktop" <?php checked( @$general_settings['minimize_desktop'], 'desktop' );?> />
								<?php _e( 'Desktop', 'mystickyelements' );?>
							</label>
						</li>
						<li>
							<label>
								<input type="checkbox" name="general-settings[minimize_mobile]" value="mobile" <?php checked( @$general_settings['minimize_mobile'], 'mobile' );?> />
								<?php _e( 'Mobile', 'mystickyelements' );?>
							</label>
						</li>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<span class="myStickyelements-label" >
					<label for="myStickyelements-google-alanytics-enabled"><?php _e( 'Google Analytics Events', 'mystickyelements' );?></label>
					<?php if(!$is_pro_active) {?><span class="upgrade-myStickyelements"><a href="<?php echo $upgrade_url ?>" target="_blank"><i class="fas fa-lock"></i><?php _e('ACTIVATE YOUR KEY', 'mystickyelements' );?></a></span><?php } ?>
				</span>
				<div class="myStickyelements-inputs myStickyelements-label">
					<label for="myStickyelements-google-alanytics-enabled" class="myStickyelements-switch" >
						<input type="checkbox" id="myStickyelements-google-alanytics-enabled" name="general-settings[google_analytics]" value="1" <?php checked( @$general_settings['google_analytics'], '1' );?> <?php echo !$is_pro_active?"disabled":"" ?>  />
						<span class="slider round"></span>
					</label>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<span class="myStickyelements-label" >
					<?php _e( 'Font Family', 'mystickyelements' );?></label>
				</span>
				<div class="myStickyelements-inputs myStickyelements-label">
					<select name="general-settings[font_family]" class="form-fonts">
						<option value=""><?php _e( 'Select font family', 'mystickyelements' );?></option>
						<?php $group= ''; foreach( mystickyelements_fonts() as $key=>$value):
									if ($value != $group){
										echo '<optgroup label="' . $value . '">';
										$group = $value;
									}
								?>
							<option value="<?php echo $key;?>" <?php selected( @$general_settings['font_family'], $key ); ?>><?php echo $key;?></option>
						<?php endforeach;?>
					</select>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<span class="myStickyelements-label" >
					<?php _e( 'Desktop Widget Size', 'mystickyelements' );?>
				</span>
				<div class="myStickyelements-inputs myStickyelements-label">
					<?php $general_settings['widget-size'] = (isset($general_settings['widget-size']) && $general_settings['widget-size']!= '') ? $general_settings['widget-size'] : 'medium'; ?>
					<select id="myStickyelements-widget-size" name="general-settings[widget-size]" >
						<option value="small" <?php selected( @$general_settings['widget-size'], 'small' ); ?>><?php _e( 'Small', 'mystickyelements' );?></option>
						<option value="medium" <?php selected( @$general_settings['widget-size'], 'medium' ); ?>><?php _e( 'Medium', 'mystickyelements' );?></option>
						<option value="large" <?php selected( @$general_settings['widget-size'], 'large' ); ?>><?php _e( 'Large', 'mystickyelements' );?></option>
						<option value="extra-large" <?php selected( @$general_settings['widget-size'], 'extra-large' ); ?>><?php _e( 'Extra Large', 'mystickyelements' );?></option>
					</select>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<span class="myStickyelements-label" >
					<?php _e( 'Mobile Widget size', 'mystickyelements' );?>
				</span>
				<div class="myStickyelements-inputs myStickyelements-label">
					<?php $general_settings['mobile-widget-size'] = (isset($general_settings['mobile-widget-size']) && $general_settings['mobile-widget-size']!= '') ? $general_settings['mobile-widget-size'] : 'medium'; ?>
					<select id="myStickyelements-widget-mobile-size" name="general-settings[mobile-widget-size]" >
						<option value="small" <?php selected( @$general_settings['mobile-widget-size'], 'small' ); ?>><?php _e( 'Small', 'mystickyelements' );?></option>
						<option value="medium" <?php selected( @$general_settings['mobile-widget-size'], 'medium' ); ?>><?php _e( 'Medium', 'mystickyelements' );?></option>
						<option value="large" <?php selected( @$general_settings['mobile-widget-size'], 'large' ); ?>><?php _e( 'Large', 'mystickyelements' );?></option>
					</select>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<span class="myStickyelements-label" >
					<?php _e( 'Entry effect', 'mystickyelements' );?></label>
				</span>
				<div class="myStickyelements-inputs myStickyelements-label">
					<?php $general_settings['entry-effect'] = (isset($general_settings['entry-effect']) && $general_settings['entry-effect']!= '') ? $general_settings['entry-effect'] : 'slide-in'; ?>
					<select id="myStickyelements-entry-effect" name="general-settings[entry-effect]" >
						<option value="none" <?php selected( @$general_settings['entry-effect'], 'none' ); ?>><?php _e( 'None', 'mystickyelements' );?></option>
						<option value="slide-in" <?php selected( @$general_settings['entry-effect'], 'slide-in' ); ?>><?php _e( 'Slide in', 'mystickyelements' );?></option>
						<option value="fade" <?php selected( @$general_settings['entry-effect'], 'fade' ); ?>><?php _e( 'Fade', 'mystickyelements' );?></option>
					</select>
				</div>
			</td>
		</tr>
		<!-- Show On Pages Rules -->
		<tr class="show-on-apper">
			<td colspan="2">
				<div class="myStickyelements-show-on-wrap">
					<span class="myStickyelements-label myStickyelements-extra-label">
						<label><?php _e( 'Show on Pages', 'mystickyelements' );?></label>
						<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip">
							<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
							<p><?php esc_html_e("Show or don't show the widget on specific pages. You can use rules like contains, exact match, starts with, and ends with", 'mystickyelements'); ?></p>
						</div>
					</span>
					<div class="myStickyelements-show-on-right">
						<div class="myStickyelements-page-options myStickyelements-inputs" id="myStickyelements-page-options">
							<?php $page_option = (isset($general_settings['page_settings'])) ? $general_settings['page_settings'] : array();
							$url_options = array(
								'page_contains' => 'pages that contain',
								'page_has_url' => 'a specific page',
								'page_start_with' => 'pages starting with',
								'page_end_with' => 'pages ending with',
							);

							if(!empty($page_option) && is_array($page_option)) {
								$count = 0;
								foreach($page_option as $k=>$option) {
									$count++;
									?>
									<div class="myStickyelements-page-option <?php echo $k==count($page_option)?"last":""; ?>">
										<div class="url-content">
											<div class="myStickyelements-url-select">
												<select name="general-settings[page_settings][<?php echo $count; ?>][shown_on]" id="url_shown_on_<?php echo $count  ?>_option">
													<option value="show_on" <?php echo $option['shown_on']=="show_on"?"selected":"" ?> ><?php esc_html_e( 'Show on', 'mystickyelements' )?></option>
													<option value="not_show_on" <?php echo $option['shown_on']=="not_show_on"?"selected":"" ?>><?php esc_html_e( "Don't show on", "mystickyelements" );?></option>
												</select>
											</div>
											<div class="myStickyelements-url-option">
												<select class="myStickyelements-url-options" name="general-settings[page_settings][<?php echo $count; ?>][option]" id="url_rules_<?php echo $count  ?>_option">
													<option disabled value=""><?php esc_html_e( "Select Rule", "mystickyelements" );?></option>
													<?php foreach($url_options as $key=>$value) {
														$selected = ( isset($option['option']) && $option['option']==$key )?" selected='selected' ":"";
														echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
													} ?>
												</select>
											</div>
											<div class="myStickyelements-url-box">
												<span class='myStickyelements-url'><?php echo site_url("/"); ?></span>
											</div>
											<div class="myStickyelements-url-values">
												<input type="text" value="<?php echo $option['value'] ?>" name="general-settings[page_settings][<?php echo $count; ?>][value]" id="url_rules_<?php echo $count; ?>_value" />
											</div>
											<div class="myStickyelements-url-buttons">
												<a class="myStickyelements-remove-rule" href="javascript:;">x</a>
											</div>
											<div class="clear"></div>
										</div>
									</div>
									<?php
								}
							}
							?>

						</div>
						<a href="javascript:void(0);" class="create-rule" id="create-rule"><?php esc_html_e( "Add Rule", "mystickyelements" );?></a>
						<a href="javascript:void(0);" class="create-rule remove-rule" id="remove-page-rules" <?php if(empty($page_option) ) :?>style="display:none" <?php endif;?>><?php esc_html_e( "Remove Rules", "mystickyelements" );?></a>
					</div>
				</div>
			</td>
		</tr>
		<!-- END Show on Pages -->
		
		<!-- Show On Days & Hours -->
		<tr class="show-on-apper">
			<td colspan="2">
				<div class="myStickyelements-show-on-wrap">
					<span class="myStickyelements-label myStickyelements-extra-label">
						<label><?php _e( 'Days and Hours', 'mystickyelements' );?></label>
						<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip">
							<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
							<p><?php esc_html_e("Display the widget on specific days and hours based on your opening days and hours", 'mystickyelements'); ?></p>
						</div>
					</span>
					<div class="myStickyelements-show-on-right">
						<div class="myStickyelements-days-hours-options myStickyelements-inputs" id="myStickyelements-days-hours-options">
							<?php 
							$days_hours = (isset($general_settings['days-hours'])) ? $general_settings['days-hours'] : array();
							$days = array(
											"0" => "Everyday of week",
											"1" => "Sunday",
											"2" => "Monday",
											"3" => "Tuesday",
											"4" => "Wednesday",
											"5" => "Thursday",
											"6" => "Friday",
											"7" => "Saturday",
											"8" => "Sunday to Thursday",
											"9" => "Monday to Friday",
											"10" => "Weekend",
										);

							if(!empty($days_hours) && is_array($days_hours)) {
								$count = 0;
								foreach($days_hours as $k=>$day_hour) {
									$count++;
									?>
									<div class="myStickyelements-page-option <?php echo $k==count($days_hours)?"last":""; ?>">
										<div class="url-content">
											<div class="myStickyelements-url-select">
												<select name="general-settings[days-hours][<?php echo $count; ?>][days]" id="url_shown_on_<?php echo $count  ?>_option">
													<?php foreach ($days as $key=>$value) { ?>
														<option <?php echo ($key == $day_hour['days'])?'selected="selected"':''; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="myStickyelements-url-option">
												<label class="myStickyelements-days-hours-label-wrap">
													<span class="myStickyelements-days-hours-label">From</span>
													<input type="text" class=" time-picker ui-timepicker-input timepicker_time"  value="<?php echo $day_hour['start_time']; ?>" name="general-settings[days-hours][<?php echo $count; ?>][start_time]" id="start_time_<?php echo $count ?>" />
												</label>
											</div>
											<div class="myStickyelements-url-box">
												<label class="myStickyelements-days-hours-label-wrap">
													<span class="myStickyelements-days-hours-label">To</span>
													<input type="text" class=" time-picker ui-timepicker-input timepicker_time"  value="<?php echo $day_hour['end_time']; ?>" name="general-settings[days-hours][<?php echo $count ?>][end_time]" id="end_time_<?php echo $count ?>" />
												</label>
											</div>
											<div class="myStickyelements-url-values">
												<label class="myStickyelements-days-hours-label-wrap">
													<span class="myStickyelements-days-hours-label">Time Zone</span>
													<select class=" gmt-data stickyelement-gmt-timezone gmt-timezone" name="general-settings[days-hours][<?php echo $count; ?>][gmt]" id="url_shown_on_<?php echo $count; ?>_option">
														<?php echo stickyelement_timezone_choice( $day_hour['gmt'], false );?>
													</select>
												</label>
											</div>
											<div class="myStickyelements-url-buttons">
												<a class="myStickyelements-remove-rule" href="javascript:;">x</a>
											</div>
											<div class="clear"></div>
										</div>
									</div>
									<?php
								}
							}
							?>

						</div>
						<a href="javascript:void(0);" class="create-rule" id="create-data-and-time-rule"><?php esc_html_e( "Add Rule", "mystickyelements" );?></a>
						<a href="javascript:void(0);" class="create-rule remove-rule" id="remove-data-and-time-rule"<?php if(empty($days_hours) ) :?> style="display:none" <?php endif;?>><?php esc_html_e( "Remove Rules", "mystickyelements" );?></a>
					</div>
				</div>
			</td>
		</tr>
		<!-- END Days and Hours -->
		
		
		<!-- Traffic Source -->
		<tr>
			<td>
				<span class="myStickyelements-label myStickyelements-extra-label" >
					<label for="traffic-add-other-source"><?php _e( "Traffic source", 'mystickyelements' );?></label>
					<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip">
						<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
						<p><?php esc_html_e("Show the widget only to visitors who come from specific traffic sources including direct traffic, social networks, search engines, Google Ads, or any other traffic source", 'mystickyelements'); ?></p>
					</div>
				</span>
				<div class="myStickyelements-show-on-right myStickyelements-inputs myStickyelements-traffic-source-right">
					<div class=" myStickyelements-label myStickyelements-traffic-source-inputs traffic-source-option <?php echo esc_attr($is_pro_active?"is-pro":"not-pro") ?>" <?php if ( $direct_visit == '' && $social_network == '' && $search_engines == '' && $google_ads == '' && empty($other_source_url) ):?>style="display:none;" <?php endif;?>>
						<div class="traffic-direct-source clear">
							<label class="myStickyelements-switch">
								<input type="checkbox" id="myStickyelements-direct-traffic-source" <?php if($is_pro_active){ ?>name="general-settings[traffic-source][direct-visit]"<?php } ?> value="1"  <?php if($direct_visit == "1"){ echo "checked"; } ?>  <?php echo !$is_pro_active?"disabled":"" ?> />
								<span class="slider round"></span>
							</label>
							<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip">
								<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
								<p><?php esc_html_e("Show the poptin to visitors who arrived to your website from direct traffic", 'mystickyelements'); ?></p>
							</div>
							<label for="myStickyelements-direct-traffic-source">
								Direct visit
								
							</label>
						</div>
						<br />
						<div class="traffic-social-network-source clear">
							<label class="myStickyelements-switch">
								<input type="checkbox" id="myStickyelements-social-network-traffic-source" <?php if($is_pro_active){ ?>name="general-settings[traffic-source][social-network]"<?php } ?> value="1"  <?php if($social_network == "1"){ echo "checked"; } ?>  <?php echo !$is_pro_active?"disabled":"" ?> />
								<span class="slider round"></span>
							</label>
							<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip">
								<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
								<p><?php esc_html_e("Show the poptin to visitors who arrived to your website from social networks including: Facebook, Twitter, Pinterest, Instagram, Google+, LinkedIn, Delicious, Tumblr, Dribbble, StumbleUpon, Flickr, Plaxo, Digg and more", 'mystickyelements'); ?></p>
							</div>
							<label for="myStickyelements-social-network-traffic-source">
								Social networks
								
							</label>
						</div>
						<br />
						<div class="traffic-search-engines-source clear">
							<label class="myStickyelements-switch">
								<input type="checkbox" id="myStickyelements-search-engines-traffic-source" <?php if($is_pro_active){ ?>name="general-settings[traffic-source][search-engines]"<?php } ?> value="1"  <?php if($search_engines == "1"){ echo "checked"; } ?>  <?php echo !$is_pro_active?"disabled":"" ?> />
								<span class="slider round"></span>
							</label>
							<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip">
								<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
								<p><?php esc_html_e("Show the poptin to visitors who arrived from search engines including: Google, Bing, Yahoo!, Yandex, AOL, Ask, WOW,  WebCrawler, Baidu and more", 'mystickyelements'); ?></p>
							</div>
							<label for="myStickyelements-search-engines-traffic-source">
								Search engines
								
							</label>
						</div>
						<br />
						<div class="traffic-google-ads-source clear">
							<label class="myStickyelements-switch">
								<input type="checkbox" id="myStickyelements-google-ads-traffic-source" <?php if($is_pro_active){ ?>name="general-settings[traffic-source][google-ads]" <?php } ?> value="1" <?php if($google_ads == "1"){ echo "checked"; } ?>  <?php echo !$is_pro_active?"disabled":"" ?> />
								<span class="slider round"></span>
							</label>
							<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip">
								<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
								<p><?php esc_html_e("Show the poptin to visitors who arrived from search engines including: Google, Bing, Yahoo!, Yandex, AOL, Ask, WOW,  WebCrawler, Baidu and more", 'mystickyelements'); ?></p>
							</div>
							<label for="myStickyelements-google-ads-traffic-source">
							
								Google Ads
								
							</label>
						</div>
						<br />
						<div class="traffic-other-source clear">
							<div class="other-source-features clear">
								<table id="custom-traffic-source-lists" width="100%">
									<thead>
										<tr>
											<th colspan="3">Specific URL</th>
										</tr>
									</thead>
									<tbody>
										<?php if ( !empty($other_source_url)): ?>
											<?php for($i=0; $i<sizeof($other_source_url); $i++ ):?>
												<tr>
													<td>
														<select <?php if($is_pro_active){ ?>name="general-settings[traffic-source][other-source-option][]"<?php }?>  <?php echo !$is_pro_active?"disabled":"" ?> >
															<option value="contain" <?php if($other_source_option[$i] == "contain"){ echo "selected"; } ?> >Contains</option>
															<option value="not_contain" <?php if($other_source_option[$i] == "not_contain"){ echo "selected"; } ?> >Not contains</option>
														</select>
													</td>
													<td>
														<input type="text" <?php if($is_pro_active){ ?>name="general-settings[traffic-source][other-source-url][]"<?php }?> value="<?php echo $other_source_url[$i];?>" placeholder="http://www.example.com"  <?php echo !$is_pro_active?"disabled":"" ?> />
													</td>
													<td>
														<div class="day-buttons">
															<a href="javascript:;" class="traffic-delete-other-source">X</a>
														</div>
													</td>									
												</tr>
											<?php endfor;?>
											
										<?php else : ?>
											<tr>
												<td>
													<select <?php if($is_pro_active){ ?>name="general-settings[traffic-source][other-source-option][]"<?php }?> <?php echo !$is_pro_active?"disabled":"" ?> >
														<option value="contain">Contains</option>
														<option value="not_contain">Not contains</option>
													</select>
												</td>
												<td>
													<input type="text" <?php if($is_pro_active){ ?>name="general-settings[traffic-source][other-source-url][]"<?php }?> value="" placeholder="http://www.example.com" <?php echo !$is_pro_active?"disabled":"" ?> />
												</td>
												<td>
													<div class="day-buttons">
														<a href="javascript:;" class="traffic-delete-other-source">X</a>
													</div>
												</td>								
											</tr>
										<?php endif;?>
									</tbody>
								</table>							
							</div>
						</div>
						<?php if(!$is_pro_active) {?>
							<span class="upgrade-myStickyelements"><a href="<?php echo $upgrade_url ?>" target="_blank"><i class="fas fa-lock"></i><?php _e('ACTIVATE YOUR KEY', 'mystickyelements' );?></a></span>
						<?php } ?>
					</div>
					<?php if ( $direct_visit == '' && $social_network == '' && $search_engines == '' && $google_ads == '' && empty($other_source_url) ){
						$traffic_class	= 'traffic-add-source';
					} else {
						$traffic_class	= 'traffic-add-other-source';
					}
					?>
					<a href="javascript:void(0);" class="<?php echo $traffic_class;?> create-rule" id="traffic-add-other-source"><?php esc_html_e( "Add Rule", "mystickyelements" );?></a>
					<a href="javascript:void(0);" class="create-rule remove-rule" id="remove-traffic-add-other-source" <?php if ( $direct_visit == '' && $social_network == '' && $search_engines == '' && $google_ads == '' && empty($other_source_url) ):?> style="display:none"<?php endif;?>><?php esc_html_e( "Remove Rules", "mystickyelements" );?></a>
				</div>
			</td>
		</tr>
		
		<!-- END Traffic Source -->
		<?php $countries = array(array("short_name" => "AF", "country_name" => "Afghanistan"), array("short_name" => "AL", "country_name" => "Albania"), array("short_name" => "DZ", "country_name" => "Algeria"), array("short_name" => "AD", "country_name" => "Andorra"), array("short_name" => "AO", "country_name" => "Angola"), array("short_name" => "AI", "country_name" => "Anguilla"), array("short_name" => "AG", "country_name" => "Antigua and Barbuda"), array("short_name" => "AR", "country_name" => "Argentina"), array("short_name" => "AM", "country_name" => "Armenia"), array("short_name" => "AW", "country_name" => "Aruba"), array("short_name" => "AU", "country_name" => "Australia"), array("short_name" => "AT", "country_name" => "Austria"), array("short_name" => "AZ", "country_name" => "Azerbaijan"), array("short_name" => "BS", "country_name" => "Bahamas"), array("short_name" => "BH", "country_name" => "Bahrain"), array("short_name" => "BD", "country_name" => "Bangladesh"), array("short_name" => "BB", "country_name" => "Barbados"), array("short_name" => "BY", "country_name" => "Belarus"), array("short_name" => "BE", "country_name" => "Belgium"), array("short_name" => "BZ", "country_name" => "Belize"), array("short_name" => "BJ", "country_name" => "Benin"), array("short_name" => "BM", "country_name" => "Bermuda"), array("short_name" => "BT", "country_name" => "Bhutan"), array("short_name" => "BO", "country_name" => "Bolivia"), array("short_name" => "BA", "country_name" => "Bosnia and Herzegowina"), array("short_name" => "BW", "country_name" => "Botswana"), array("short_name" => "BV", "country_name" => "Bouvet Island"), array("short_name" => "BR", "country_name" => "Brazil"), array("short_name" => "IO", "country_name" => "British Indian Ocean Territory"), array("short_name" => "BN", "country_name" => "Brunei Darussalam"), array("short_name" => "BG", "country_name" => "Bulgaria"), array("short_name" => "BF", "country_name" => "Burkina Faso"), array("short_name" => "BI", "country_name" => "Burundi"), array("short_name" => "KH", "country_name" => "Cambodia"), array("short_name" => "CM", "country_name" => "Cameroon (Republic of Cameroon)"), array("short_name" => "CA", "country_name" => "Canada"), array("short_name" => "CV", "country_name" => "Cape Verde"), array("short_name" => "KY", "country_name" => "Cayman Islands"), array("short_name" => "CF", "country_name" => "Central African Republic"), array("short_name" => "TD", "country_name" => "Chad"), array("short_name" => "CL", "country_name" => "Chile"), array("short_name" => "CN", "country_name" => "China"), array("short_name" => "CX", "country_name" => "Christmas Island"), array("short_name" => "CC", "country_name" => "Cocos (Keeling) Islands"), array("short_name" => "CO", "country_name" => "Colombia"), array("short_name" => "KM", "country_name" => "Comoros"), array("short_name" => "CG", "country_name" => "Congo"), array("short_name" => "CK", "country_name" => "Cook Islands"), array("short_name" => "CR", "country_name" => "Costa Rica"), array("short_name" => "CI", "country_name" => "Cote D\Ivoire"), array("short_name" => "HR", "country_name" => "Croatia"), array("short_name" => "CU", "country_name" => "Cuba"), array("short_name" => "CY", "country_name" => "Cyprus"), array("short_name" => "CZ", "country_name" => "Czech Republic"), array("short_name" => "DK", "country_name" => "Denmark"), array("short_name" => "DJ", "country_name" => "Djibouti"), array("short_name" => "DM", "country_name" => "Dominica"), array("short_name" => "DO", "country_name" => "Dominican Republic"), array("short_name" => "EC", "country_name" => "Ecuador"), array("short_name" => "EG", "country_name" => "Egypt"), array("short_name" => "SV", "country_name" => "El Salvador"), array("short_name" => "GQ", "country_name" => "Equatorial Guinea"), array("short_name" => "ER", "country_name" => "Eritrea"), array("short_name" => "EE", "country_name" => "Estonia"), array("short_name" => "ET", "country_name" => "Ethiopia"), array("short_name" => "FK", "country_name" => "Falkland Islands (Malvinas)"), array("short_name" => "FO", "country_name" => "Faroe Islands"), array("short_name" => "FJ", "country_name" => "Fiji"), array("short_name" => "FI", "country_name" => "Finland"), array("short_name" => "FR", "country_name" => "France"), array("short_name" => "Me", "country_name" => "Montenegro"), array("short_name" => "GF", "country_name" => "French Guiana"), array("short_name" => "PF", "country_name" => "French Polynesia"), array("short_name" => "TF", "country_name" => "French Southern Territories"), array("short_name" => "GA", "country_name" => "Gabon"), array("short_name" => "GM", "country_name" => "Gambia"), array("short_name" => "GE", "country_name" => "Georgia"), array("short_name" => "DE", "country_name" => "Germany"), array("short_name" => "GH", "country_name" => "Ghana"), array("short_name" => "GI", "country_name" => "Gibraltar"), array("short_name" => "GR", "country_name" => "Greece"), array("short_name" => "GL", "country_name" => "Greenland"), array("short_name" => "GD", "country_name" => "Grenada"), array("short_name" => "GP", "country_name" => "Guadeloupe"), array("short_name" => "GT", "country_name" => "Guatemala"), array("short_name" => "GN", "country_name" => "Guinea"), array("short_name" => "GW", "country_name" => "Guinea bissau"), array("short_name" => "GY", "country_name" => "Guyana"), array("short_name" => "HT", "country_name" => "Haiti"), array("short_name" => "HM", "country_name" => "Heard Island And Mcdonald Islands"), array("short_name" => "HN", "country_name" => "Honduras"), array("short_name" => "HK", "country_name" => "Hong Kong"), array("short_name" => "HU", "country_name" => "Hungary"), array("short_name" => "IS", "country_name" => "Iceland"), array("short_name" => "IN", "country_name" => "India"), array("short_name" => "ID", "country_name" => "Indonesia"), array("short_name" => "IR", "country_name" => "Iran, Islamic Republic Of"), array("short_name" => "IQ", "country_name" => "Iraq"), array("short_name" => "IE", "country_name" => "Ireland"), array("short_name" => "IL", "country_name" => "Israel"), array("short_name" => "IT", "country_name" => "Italy"), array("short_name" => "JM", "country_name" => "Jamaica"), array("short_name" => "JP", "country_name" => "Japan"), array("short_name" => "JO", "country_name" => "Jordan"), array("short_name" => "KZ", "country_name" => "Kazakhstan"), array("short_name" => "KE", "country_name" => "Kenya"), array("short_name" => "KI", "country_name" => "Kiribati"), array("short_name" => "KP", "country_name" => "Korea, Democratic People's Republic Of"), array("short_name" => "KR", "country_name" => "South Korea"), array("short_name" => "KW", "country_name" => "Kuwait"), array("short_name" => "KG", "country_name" => "Kyrgyzstan"), array("short_name" => "LA", "country_name" => "Lao People\s Democratic Republic"), array("short_name" => "LV", "country_name" => "Latvia"), array("short_name" => "LB", "country_name" => "Lebanon"), array("short_name" => "LS", "country_name" => "Lesotho"), array("short_name" => "LR", "country_name" => "Liberia"), array("short_name" => "LY", "country_name" => "Libyan Arab Jamahiriya"), array("short_name" => "LI", "country_name" => "Liechtenstein"), array("short_name" => "LT", "country_name" => "Lithuania"), array("short_name" => "LU", "country_name" => "Luxembourg"), array("short_name" => "MO", "country_name" => "Macao"), array("short_name" => "MK", "country_name" => "Macedonia"), array("short_name" => "MG", "country_name" => "Madagascar"), array("short_name" => "MW", "country_name" => "Malawi"), array("short_name" => "MY", "country_name" => "Malaysia"), array("short_name" => "MV", "country_name" => "Maldives"), array("short_name" => "ML", "country_name" => "Mali"), array("short_name" => "MT", "country_name" => "Malta"), array("short_name" => "MQ", "country_name" => "Martinique"), array("short_name" => "MR", "country_name" => "Mauritania"), array("short_name" => "MU", "country_name" => "Mauritius"), array("short_name" => "YT", "country_name" => "Mayotte"), array("short_name" => "MD", "country_name" => "Moldova"), array("short_name" => "MC", "country_name" => "Monaco"), array("short_name" => "MN", "country_name" => "Mongolia"), array("short_name" => "MS", "country_name" => "Montserrat"), array("short_name" => "MA", "country_name" => "Morocco"), array("short_name" => "MZ", "country_name" => "Mozambique"), array("short_name" => "MM", "country_name" => "Myanmar"), array("short_name" => "NA", "country_name" => "Namibia"), array("short_name" => "NR", "country_name" => "Nauru"), array("short_name" => "NP", "country_name" => "Nepal"), array("short_name" => "NL", "country_name" => "Netherlands"), array("short_name" => "AN", "country_name" => "Netherlands Antilles"), array("short_name" => "NC", "country_name" => "New Caledonia"), array("short_name" => "NZ", "country_name" => "New Zealand"), array("short_name" => "NI", "country_name" => "Nicaragua"), array("short_name" => "NE", "country_name" => "Niger"), array("short_name" => "NG", "country_name" => "Nigeria"), array("short_name" => "NU", "country_name" => "Niue"), array("short_name" => "NF", "country_name" => "Norfolk Island"), array("short_name" => "NO", "country_name" => "Norway"), array("short_name" => "OM", "country_name" => "Oman"), array("short_name" => "PK", "country_name" => "Pakistan"), array("short_name" => "PA", "country_name" => "Panama"), array("short_name" => "PG", "country_name" => "Papua New Guinea"), array("short_name" => "PY", "country_name" => "Paraguay"), array("short_name" => "PE", "country_name" => "Peru"), array("short_name" => "PH", "country_name" => "Philippines"), array("short_name" => "PN", "country_name" => "Pitcairn"), array("short_name" => "PL", "country_name" => "Poland"), array("short_name" => "PT", "country_name" => "Portugal"), array("short_name" => "QA", "country_name" => "Qatar"), array("short_name" => "RE", "country_name" => "Reunion"), array("short_name" => "RO", "country_name" => "Romania"), array("short_name" => "RU", "country_name" => "Russia"), array("short_name" => "RW", "country_name" => "Rwanda"), array("short_name" => "KN", "country_name" => "Saint Kitts and Nevis"), array("short_name" => "LC", "country_name" => "Saint Lucia"), array("short_name" => "VC", "country_name" => "St. Vincent"), array("short_name" => "WS", "country_name" => "Samoa"), array("short_name" => "SM", "country_name" => "San Marino"), array("short_name" => "ST", "country_name" => "Sao Tome and Principe"), array("short_name" => "SA", "country_name" => "Saudi Arabia"), array("short_name" => "SN", "country_name" => "Senegal"), array("short_name" => "SC", "country_name" => "Seychelles"), array("short_name" => "SL", "country_name" => "Sierra Leone"), array("short_name" => "SG", "country_name" => "Singapore"), array("short_name" => "SK", "country_name" => "Slovakia"), array("short_name" => "SI", "country_name" => "Slovenia"), array("short_name" => "SB", "country_name" => "Solomon Islands"), array("short_name" => "SO", "country_name" => "Somalia"), array("short_name" => "ZA", "country_name" => "South Africa"), array("short_name" => "GS", "country_name" => "South Georgia & South Sandwich Islands"), array("short_name" => "ES", "country_name" => "Spain"), array("short_name" => "LK", "country_name" => "Sri Lanka"), array("short_name" => "SH", "country_name" => "Saint Helena"), array("short_name" => "PM", "country_name" => "Saint Pierre And Miquelon"), array("short_name" => "SD", "country_name" => "Sudan"), array("short_name" => "SR", "country_name" => "Suriname"), array("short_name" => "SJ", "country_name" => "Svalbard And Jan Mayen"), array("short_name" => "SZ", "country_name" => "Swaziland"), array("short_name" => "SE", "country_name" => "Sweden"), array("short_name" => "CH", "country_name" => "Switzerland"), array("short_name" => "SY", "country_name" => "Syria"), array("short_name" => "TW", "country_name" => "Taiwan"), array("short_name" => "TJ", "country_name" => "Tajikistan"), array("short_name" => "TZ", "country_name" => "Tanzania, United Republic Of"), array("short_name" => "TH", "country_name" => "Thailand"), array("short_name" => "TG", "country_name" => "Togo"), array("short_name" => "TK", "country_name" => "Tokelau"), array("short_name" => "TO", "country_name" => "Tonga"), array("short_name" => "TT", "country_name" => "Trinidad and Tobago"), array("short_name" => "TN", "country_name" => "Tunisia"), array("short_name" => "TR", "country_name" => "Turkey"), array("short_name" => "TM", "country_name" => "Turkmenistan"), array("short_name" => "TC", "country_name" => "Turks and Caicos Islands"), array("short_name" => "TV", "country_name" => "Tuvalu"), array("short_name" => "UG", "country_name" => "Uganda"), array("short_name" => "UA", "country_name" => "Ukraine"), array("short_name" => "AE", "country_name" => "United Arab Emirates"), array("short_name" => "GB", "country_name" => "United Kingdom"), array("short_name" => "US", "country_name" => "United States"), array("short_name" => "UM", "country_name" => "United States Minor Outlying Islands"), array("short_name" => "UY", "country_name" => "Uruguay"), array("short_name" => "UZ", "country_name" => "Uzbekistan"), array("short_name" => "VU", "country_name" => "Vanuatu"), array("short_name" => "VA", "country_name" => "Holy See (Vatican City State)"), array("short_name" => "VE", "country_name" => "Venezuela"), array("short_name" => "VN", "country_name" => "Vietnam"), array("short_name" => "VG", "country_name" => "Virgin Islands (British)"), array("short_name" => "WF", "country_name" => "Wallis and Futuna Islands"), array("short_name" => "EH", "country_name" => "Western Sahara"), array("short_name" => "YE", "country_name" => "Yemen"), array("short_name" => "ZM", "country_name" => "Zambia"), array("short_name" => "ZW", "country_name" => "Zimbabwe"), array("short_name" => "AX", "country_name" => "Aland Islands"), array("short_name" => "CD", "country_name" => "Congo, The Democratic Republic Of The"), array("short_name" => "CW", "country_name" => "Curaçao"), array("short_name" => "GG", "country_name" => "Guernsey"), array("short_name" => "IM", "country_name" => "Isle Of Man"), array("short_name" => "JE", "country_name" => "Jersey"), array("short_name" => "KV", "country_name" => "Kosovo"), array("short_name" => "PS", "country_name" => "Palestinian Territory"), array("short_name" => "BL", "country_name" => "Saint Barthélemy"), array("short_name" => "MF", "country_name" => "Saint Martin"), array("short_name" => "RS", "country_name" => "Serbia"), array("short_name" => "SX", "country_name" => "Sint Maarten"), array("short_name" => "TL", "country_name" => "Timor Leste"), array("short_name" => "MX", "country_name" => "Mexico"));
		$selected_countries = (isset($general_settings['countries_list'])) ? $general_settings['countries_list'] : '' ;
		$selected_countries = ( $selected_countries === false || empty($selected_countries) || !is_array($selected_countries) ) ? array() : $selected_countries;
		$count = count($selected_countries);
		$countries_message =  "All countries";
		if($count == 1) {
			$countries_message = "1 country selected";
		} else if($count > 1){
			$countries_message = $count." countries selected";
		}
		?>
		<tr>
			<td>
				<span class="myStickyelements-label" >
					<label for="countries_list"><?php _e( "Country targeting", 'mystickyelements' );?></label>
					<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip">
						<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
						<p><?php esc_html_e("Target your widget to specific countries. You can create different widgets for different countries", 'mystickyelements'); ?></p>
					</div>
				</span>
				<div class="myStickyelements-inputs myStickyelements-label myStickyelements-country-inputs <?php echo esc_attr($is_pro_active?"is-pro":"not-pro") ?>">
					<button type="button" class="myStickyelements-country-button"><?php echo esc_attr($countries_message) ?></button>
					<div class="myStickyelements-country-list-box">
						<select id="countries_list" name="general-settings[countries_list][]" multiple placeholder="Select Country" class="myStickyelements-country-list <?php echo esc_attr($is_pro_active?"is-pro":"not-pro") ?>" <?php echo !$is_pro_active?"disabled":"" ?>>
							<?php foreach($countries as $country) {
								$selected = in_array($country["short_name"], $selected_countries)?"selected":"";
								?>
								<option <?php echo esc_attr($selected) ?> value="<?php echo esc_attr($country["short_name"]) ?>"><?php echo esc_attr($country["country_name"]) ?></option>
							<?php } ?>
						</select>
					</div>
					<?php if(!$is_pro_active) {?>
						<span class="upgrade-myStickyelements">
							<a href="<?php echo $upgrade_url ?>" target="_blank"><i class="fas fa-lock"></i><?php _e('ACTIVATE YOUR KEY', 'mystickyelements' );?></a>
						</span>
					<?php } ?>
				</div>
			</td>
		</tr>									
		<tr>
			<td>
				<span class="myStickyelements-label" ><label for="general-settings-tabs-css"><?php _e( 'Tabs CSS', 'mystickyelements' );?></label></span>

				<?php if(!$is_pro_active) {?><span class="upgrade-myStickyelements"><a href="<?php echo $upgrade_url ?>" target="_blank"><i class="fas fa-lock"></i><?php _e('ACTIVATE YOUR KEY', 'mystickyelements' );?></a></span><?php } ?>
				<textarea  <?php echo !$is_pro_active?"disabled":"" ?> name="general-settings[tabs_css]" rows="5" cols="50" id="general-settings-tabs-css" class="code"><?php echo ( isset($general_settings['tabs_css'])) ? stripslashes($general_settings['tabs_css']) : '';?></textarea>
			</td>
		</tr>
	</table>
	<input type="hidden" id="myStickyelements_site_url" value="<?php echo site_url("/") ?>" >
	<div class="myStickyelements-page-options-html" style="display: none">
		<div class="myStickyelements-page-option">
			<div class="url-content">
				<div class="myStickyelements-url-select">
					<select name="general-settings[page_settings][__count__][shown_on]" id="url_shown_on___count___option" <?php echo !$is_pro_active?"disabled":"" ?>>
						<option value="show_on"><?php esc_html_e("Show on", "mystickyelements" );?></option>
						<option value="not_show_on"><?php esc_html_e("Don't show on", "mystickyelements" );?></option>
					</select>
				</div>
				<div class="myStickyelements-url-option">
					<select class="myStickyelements-url-options" name="general-settings[page_settings][__count__][option]" id="url_rules___count___option" <?php echo !$is_pro_active?"disabled":"" ?>>
						<option selected="selected" disabled value=""><?php esc_html_e("Select Rule", "mystickyelements" );?></option>
						<?php foreach($url_options as $key=>$value) {
							echo '<option value="'.$key.'">'.$value.'</option>';
						} ?>
					</select>
				</div>
				<div class="myStickyelements-url-box">
					<span class='myStickyelements-url'><?php echo site_url("/"); ?></span>
				</div>
				<div class="myStickyelements-url-values">
					<input type="text" value="" name="general-settings[page_settings][__count__][value]" id="url_rules___count___value" <?php echo !$is_pro_active?"disabled":"" ?> />
				</div>
				<div class="myStickyelements-url-buttons">
					<a class="myStickyelements-remove-rule" href="javascript:void(0);">x</a>
				</div>
				<div class="clear"></div>
			</div>
			<?php if(!$is_pro_active) {?>
				<span class="upgrade-myStickyelements"><a href="<?php echo $upgrade_url ?>" target="_blank"><i class="fas fa-lock"></i><?php _e('ACTIVATE YOUR KEY', 'mystickyelements' );?></a></span>
			<?php } ?>

		</div>
	</div>
</div>
<!-- Days & Hours HTML--->
<div class="myStickyelements-days_hours-options-html" style="display: none">
	<div class="myStickyelements-page-option">
		<div class="url-content">
			<div class="myStickyelements-url-select">
				<select name="general-settings[days-hours][__count__][days]" id="url_shown_on___count___option">
					<?php foreach ($days as $key=>$value) { ?>
						<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="myStickyelements-url-option">
				<label class="myStickyelements-days-hours-label-wrap">
					<span class="myStickyelements-days-hours-label">From</span>
					<input type="text" class=" time-picker ui-timepicker-input"  value="" name="general-settings[days-hours][__count__][start_time]" id="start_time___count__" />
				</label>
			</div>
			<div class="myStickyelements-url-box">
				<label class="myStickyelements-days-hours-label-wrap">
					<span class="myStickyelements-days-hours-label">To</span>
					<input type="text" class=" time-picker ui-timepicker-input"  value="" name="general-settings[days-hours][__count__][end_time]" id="end_time___count__" />
				</label>
			</div>
			<div class="myStickyelements-url-values">
				<label class="myStickyelements-days-hours-label-wrap">
					<span class="myStickyelements-days-hours-label">Time Zone</span>
					<select class=" gmt-data stickyelement-gmt-timezone" name="general-settings[days-hours][__count__][gmt]" id="url_shown_on___count___option">
						<?php echo stickyelement_timezone_choice( '', false );?>
					</select>
				</label>
			</div>
			<div class="myStickyelements-url-buttons">
				<a class="myStickyelements-remove-rule" href="javascript:;">x</a>
			</div>
			<div class="clear"></div>
		</div>
		<?php if(!$is_pro_active) {?>
			<span class="upgrade-myStickyelements"><a href="<?php echo $upgrade_url ?>" target="_blank"><i class="fas fa-lock"></i><?php _e('ACTIVATE YOUR KEY', 'mystickyelements' );?></a></span>
		<?php } ?>
	</div>						
</div>


