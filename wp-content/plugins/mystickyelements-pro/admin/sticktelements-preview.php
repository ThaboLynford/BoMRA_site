<div class="mystickyelements-preview">
	<div class="myStickyelements-preview-tab">
		<div class="myStickyelements-preview-screen">
			<div class="mystickyelements-fixed <?php echo (isset($contact_form['direction']) && $contact_form['direction'] == "RTL")?"is-rtl":"" ?> mystickyelements-position-<?php echo esc_attr($general_settings['position'])?> <?php echo (isset($general_settings['position_on_screen']) && $general_settings['position_on_screen']!= '') ? 'mystickyelements-position-screen-' .$general_settings['position_on_screen'] : 'mystickyelements-position-screen-center';?> mystickyelements-position-mobile-<?php echo esc_attr($general_settings['position_mobile'])?> <?php echo (isset($general_settings['widget-size']) && $general_settings['widget-size']!= '') ? 'mystickyelements-size-' .$general_settings['widget-size'] : 'mystickyelements-size-medium';?> <?php echo (isset($general_settings['mobile-widget-size']) && $general_settings['mobile-widget-size']!= '') ? 'mystickyelements-mobile-size-' .$general_settings['mobile-widget-size'] : 'mystickyelements-mobile-size-medium';?> <?php echo (isset($general_settings['entry-effect']) && $general_settings['entry-effect']!= '') ? 'mystickyelements-entry-effect-' .$general_settings['entry-effect'] : 'mystickyelements-entry-effect-slide-in';?> <?php echo (isset($general_settings['templates']) && $general_settings['templates']!= '') ? 'mystickyelements-templates-' .$general_settings['templates'] : 'mystickyelements-templates-default';?>">
				<ul class="myStickyelements-preview-ul <?php if ( !isset($general_settings['minimize_tab'])) :?>remove-minimize <?php endif;?> ">
					<?php if ( isset($general_settings['minimize_tab'])) :?>
					<li class="mystickyelements-minimize">
						<span class="mystickyelements-minimize minimize-position-<?php echo esc_attr($general_settings['position'])?> minimize-position-mobile-<?php echo esc_attr($general_settings['position_mobile'])?>"  <?php if (isset($general_settings['minimize_tab_background_color']) && $general_settings['minimize_tab_background_color'] != ''): ?>style="background: <?php echo esc_attr($general_settings['minimize_tab_background_color']); ?>" <?php endif;
						?>>
						<?php
						if ( $general_settings['position'] == 'left' ) :
							echo "&larr;";
						endif;
						if( $general_settings['position'] == 'right' ):
							echo "&rarr;";
						endif;
						if( $general_settings['position'] == 'bottom' ):
							echo "&darr;";
						endif;
						?>
						</span>
					</li>
					<?php endif;?>
					<li id="myStickyelements-preview-contact" class="mystickyelements-contact-form element-desktop-on element-mobile-on <?php if (!isset($contact_form['enable'])) : ?> mystickyelements-contact-form-hide <?php endif; ?>" <?php if ( !isset($contact_form['enable'])) : ?> style="display:none;" <?php endif;?>>
					<?php
					$contact_form_text_class = '';
					if ($contact_form['text_in_tab'] == '') {
						$contact_form_text_class = "mystickyelements-contact-notext";
					}?>
						<span class="mystickyelements-social-icon <?php echo $contact_form_text_class?>" style="background-color: <?php echo esc_attr($contact_form['tab_background_color']);?>; color: <?php echo $contact_form['tab_text_color'];?>;">
							<i class="far fa-envelope"></i><?php echo isset($contact_form['text_in_tab'])?$contact_form['text_in_tab']:"Contact Us";?>
						</span>
					</li>
					<?php
					if (!empty($social_channels_tabs) && !isset($social_channels_tabs['is_empty'])) {
						foreach( $social_channels_tabs as $key=>$value) {
							$social_channels_list = $social_channels_lists[$key];
							if ( empty($value)) {
								$value['bg_color'] = $social_channels_list['background_color'];
							}
							$element_class = '';
							if (isset($value['desktop']) && $value['desktop'] == 1) {
								$element_class .= ' element-desktop-on';
							}
							if (isset($value['mobile']) && $value['mobile'] == 1) {
								$element_class .= ' element-mobile-on';
							}
							$value['is_locked'] = (isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1 && !$is_pro_active)?1:0;
							?>
							<li id="mystickyelements-social-<?php echo esc_attr($key);?>" class="mystickyelements-social-<?php echo esc_attr($key);?> mystickyelements-social-preview  <?php echo esc_attr($element_class);?>">
								<?php
								/*diamond template css*/
								if ( isset($value['bg_color']) && $value['bg_color'] != '' ) {
									?>
									<style>
										.myStickyelements-preview-mobile-screen .mystickyelements-position-mobile-bottom.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>,.myStickyelements-preview-mobile-screen .mystickyelements-position-mobile-bottom.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>,
										.myStickyelements-preview-mobile-screen .mystickyelements-position-mobile-top.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>,.myStickyelements-preview-mobile-screen .mystickyelements-position-mobile-top.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?> {
											background-color: <?php echo $value['bg_color']; ?> !important;
										}
										<?php
										if( isset($general_settings['templates']) && $general_settings['templates'] == 'diamond' ) {
										?>
											.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
												background: <?php echo $value['bg_color']; ?>;
											}
										<?php
										}
										if( isset($general_settings['templates']) && $general_settings['templates'] == 'arrow' ) {
										?>
											.myStickyelements-preview-screen:not(.myStickyelements-preview-mobile-screen) .mystickyelements-position-left.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
												border-left-color: <?php echo $value['bg_color']; ?>;
											}
											.myStickyelements-preview-screen:not(.myStickyelements-preview-mobile-screen) .mystickyelements-position-right.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
												border-right-color: <?php echo $value['bg_color']; ?>;
											}
											.myStickyelements-preview-screen:not(.myStickyelements-preview-mobile-screen) .mystickyelements-position-bottom.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
												border-bottom-color: <?php echo $value['bg_color']; ?>;
											}
											.myStickyelements-preview-screen.myStickyelements-preview-mobile-screen .mystickyelements-position-mobile-left.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
												border-left-color: <?php echo $value['bg_color']; ?>;
											}
											.myStickyelements-preview-screen.myStickyelements-preview-mobile-screen .mystickyelements-position-mobile-right.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
												border-right-color: <?php echo $value['bg_color']; ?>;
											}
											<?php if( $key == 'insagram' ) { ?>
											.myStickyelements-preview-screen:not(.myStickyelements-preview-mobile-screen) .mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
												background: <?php echo $value['bg_color']; ?>;
											}
											.myStickyelements-preview-screen.myStickyelements-preview-mobile-screen .mystickyelements-templates-arrow li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
												background: <?php echo $value['bg_color']; ?>;
											}
											<?php } ?>
										<?php
										}
										if( isset($general_settings['templates']) && $general_settings['templates'] == 'triangle' ) {
										?>
											.myStickyelements-preview-screen:not(.myStickyelements-preview-mobile-screen) .mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
												background: <?php echo $value['bg_color']; ?>;
											}
											.myStickyelements-preview-screen.myStickyelements-preview-mobile-screen .mystickyelements-templates-triangle li:not(.mystickyelements-contact-form) span.mystickyelements-social-icon.social-<?php echo esc_attr($key);?>::before {
												background: <?php echo $value['bg_color']; ?>;
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
								<span class="mystickyelements-social-icon social-<?php echo esc_attr($key);?> social-<?php echo esc_attr($channel_type); ?>" style="background: <?php echo esc_attr($value['bg_color']);?>">

									<?php 
									if ( isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1 && $value['custom_icon'] != '' && $value['fontawesome_icon'] == '' ):?>
										<img class="<?php echo ( isset($value['stretch_custom_icon']) && $value['stretch_custom_icon'] == 1 ) ? 'mystickyelements-stretch-custom-img' : '';  ?>" src="<?php echo esc_url($value['custom_icon']);?>" width="40" height="40" />
									<?php else:
										if ( isset($social_channels_list['custom']) && $social_channels_list['custom'] == 1 && $value['fontawesome_icon'] != '' ) {
											$social_channels_list['class'] = $value['fontawesome_icon'];
										}
										if ( isset($social_channels_list['custom_svg_icon']) && $social_channels_list['custom_svg_icon'] != '' ) :
											echo $social_channels_list['custom_svg_icon'];
										else: ?>
										<i class="<?php echo esc_attr($social_channels_list['class']);?>" <?php if ( isset($value['icon_color']) && $value['icon_color'] != '') : echo "style='color:" . $value['icon_color'] . "'"; endif; ?>></i>
									<?php endif;
									endif;
									$icon_text_size = "display: none;";
									$value['icon_text'] = ( isset($value['icon_text']) && $value['icon_text'] != '' ) ? $value['icon_text'] : '';
									
									if ( isset($value['icon_text']) && $value['icon_text'] != '' && isset($general_settings['templates']) && $general_settings['templates'] == 'default' ) {
										$icon_text_size .= "display: block;";
										if ( isset($value['icon_text_size']) && $value['icon_text_size'] != '') {
											$icon_text_size .= "font-size: " . $value['icon_text_size'] . "px;";
										}
									}
									echo "<span class='mystickyelements-icon-below-text' style='".$icon_text_size."'>" . esc_html($value['icon_text']) . "</span>";
									if ( $key == 'line') {
										echo "<style>.mystickyelements-social-icon.social-". $key ." svg .fil1{ fill:" .$value['icon_color']. "}</style>";
									}
									if ( $key == 'qzone') {
										echo "<style>.mystickyelements-social-icon.social-". $key ." svg .fil2{ fill:" . $value['icon_color'] . "}</style>";
									}
									?>
								</span>
							</li>
							<?php
						}
					}
					?>
				</ul>
			</div>
		</div>
		<p class="description" id="myStickyelements_mobile_templete_desc" style="display: none;">
			<strong><?php esc_html_e( 'The default template is the only template that is currently available for the mobile bottom position', 'mystickyelements');?></strong>
		</p>
		<div class="mystickyelements-preivew-below-sec" data-id="<?php 
			if(isset($_GET['page']) && $_GET['page'] == 'my-sticky-elements-new-widget'):?>my-sticky-elements-new-widget<?php endif; ?>">
			<div class="myStickyelements-header-title">
				<h3><?php _e('Live Preview', 'mystickyelements'); ?>
				<!-- <p class="description" ><strong><?php //esc_html_e( 'See the full functionality on your live site', 'mystickyelements');?></strong></p> -->
				</h3>
				<span class="myStickyelements-preview-window">
					<ul>
						<li class="preview-desktop preview-active"><i class="fas fa-desktop"></i></li>
						<li class="preview-mobile"><i class="fas fa-mobile-alt"></i></li>
					</ul>
				</span>
			</div>
			<div class="mystickyelements-preivew-save-btn">
				<p class="save">
					
					<button type="submit" name="submit" value="Save" id="save" data-id="" class="button button-primary preview-publish"><?php _e('Save', 'mystickyelements');?></button>
					&nbsp;
					<button type="submit" name="next-button" id="next-button-prev" class="button button-primary"><?php _e('Next', 'mystickyelements');?></button>
				</p>
			</div>
		</div>
	</div>
</div>
<?php
	
	if(isset($_GET['new_widget']) && $_GET['new_widget'] == 1){
		show_save_popup();
	}

	if( isset($_POST['submit']) && $_POST['submit'] == 'Save'){
		echo '<script type="text/javascript"> jQuery("#flash_message").addClass("show");setTimeout(function(){jQuery("#flash_message").removeClass("show");
		}, 3000);</script>';
		
		//echo '<script>setTimeout(function(){ window.location.href = "'.admin_url( "admin.php?page=my-sticky-elements&mysticky_tab_index=").'" }, 800);</script>';
	
		//echo '<div class="settings-save-toast" style="margin:0 auto; max-width:450px;"><p><strong style="color:#014737;">' . esc_html__('Settings saved.','mystickyelements'). '</p></strong></div>';
	}

	if( isset($_POST['submit']) && $_POST['submit'] == 'Publish'){

		if( isset($_POST['widget_status']) && $_POST['widget_status'] == 'my-sticky-elements' ){
			
			echo '<script type="text/javascript"> jQuery("#loader").show(); </script>';
			echo '<script>setTimeout(function(){ window.location.href = "'.admin_url( "admin.php?page=my-sticky-elements" ).'" }, 800);</script>';
		}
	}

	if(isset($_POST['save_view']) && $_POST['save_view'] == 'Save View'){

		if( isset($_POST['widget_status']) && $_POST['widget_status'] != 'my-sticky-elements-new-widget')
		{
			echo '<script type="text/javascript"> jQuery("#loader").show(); </script>';
			echo '<script>setTimeout(function(){ window.location.href = "'.admin_url( "admin.php?page=my-sticky-elements" ).'" }, 800);</script>';
		}
	}
	
	function show_save_popup(){
		
		$popup_html = '<div class="stickyelement-action-popup-open stickyelement-save-popup">';
		$popup_html .= '<div class="stickyelement-save-close-wrap"><a href="' . admin_url( "admin.php?page=my-sticky-elements" ) . '"><img draggable="false" role="img" class="emoji" alt="✖" src="https://s.w.org/images/core/emoji/13.0.1/svg/2716.svg"></a></div>';
		$popup_html .= '<div class="stickyelement-save-check-wrap"><i class="far fa-check-circle"></i></div>';
		$popup_html .= '<div class="stickyelement-save-content-wrap">';
		$popup_html .= '<div class="stickyelement-save-content-title">';
		
		if( isset($_GET['widget_available']) && $_GET['widget_available'] == 0 )
			$popup_html .= '<h2>First Widget Successfully Added 🎉</h2>';
		else
			$popup_html .= '<h2>Widget Successfully Added 🎉</h2>';
			
		$popup_html .= '</div>';
		$popup_html .= '<div class="stickyelement-save-content">';
		if( isset($_GET['widget_available']) && $_GET['widget_available'] == 0 )
			$popup_html .= '<p><strong>Congrats! </strong>You have added your first widget.</p>';
		else
			$popup_html .= '<p><strong>Congrats! </strong>You have added your widget.</p>';
		$popup_html .= '<p>Go to our knowledge base <a href="https://premio.io/help/mystickyelements/" target="_blank">My Sticky Elements doc</a> for more helpful resources and explore different ways My Sticky Elements can be used.</p>';
		//$popup_html .= '<p>If you love our plugin, <a href="https://wordpress.org/support/plugin/mystickymenu/reviews/#new-post" target="_blank">leave a 5 star review </a>on WordPress.org</p>';
		$popup_html .= '</div>';
		$popup_html .= '<div class="stickyelement-save-content-btn">';
		$popup_html .= '<a href="' . admin_url( "admin.php?page=my-sticky-elements" ) . '">Go to Dashboard</a>';
		$popup_html .= '</div>';
		$popup_html .= '</div>';
		$popup_html .= '</div>';
		$popup_html .= '<div class="stickyelement-overlay" id="success-popup-overlay" data-id="'.admin_url( "admin.php?page=my-sticky-elements" ).'"></div>';
		
		if( isset($_GET['widget_available']) && $_GET['widget_available'] == 0 ){
			echo @$popup_html;	
		}
		else
		{
			echo '<script type="text/javascript"> jQuery("#loader").show(); </script>';
			echo '<script>setTimeout(function(){ window.location.href = "'.admin_url( "admin.php?page=my-sticky-elements" ).'" }, 800);</script>';
		}
		
	}
?>
<div class="mystickyelements-action-popup-open mystickyelements-missing-link-popup mystickyelements-action-popup-status" id="mystickyelements-missing-link-popup" style="display:none;">
											
	<div class="popup-ui-widget-header">
		<span id="ui-id-1" class="ui-dialog-title"><?php esc_html_e('Missing link','mystickyelement')?></span>
		<span class="close-dialog" data-id="0" data-from='widget-social-link'>&#10006</span>
	</div>
	<div id="widget-delete-confirm" class="ui-widget-content">
		<p>Please fill out the link information for all the selected channels</p>
	</div>
	
	<div class="popup-ui-dialog-buttonset"><button type="button" class="mystickyelement-cancel-widget-btn mystickyelement-dolater-widget-btn"  data-popupfrom=""><?php esc_html_e("I'll do it later",'mystickyelement');?></button><button type="button" class="mystickyelement-btn-orange mystickyelement-btn-ok"><?php esc_html_e('Ok','mystickyelement');?></button></div>
</div>
<div id="mystickyelement-missing-link-overlay" class="stickyelement-overlay" style="display:none;"></div>