<?php 
$custom_fields = array();
foreach ( $contact_field as $key=>$value ) {
	if ( isset($value['custom_fields']) && is_array($value['custom_fields']) ) {
		$custom_fields[] = $value['custom_fields'][0];
	}
} 

$textblock_apper = ( isset( $contact_form['textblock'] ) && $contact_form['textblock'] == 'textblock' ) ? 'mystickyelements-textblock-open' : '' ;
$textblock_text = ( isset($contact_form['textblock_text'])) ? $contact_form['textblock_text'] : ''; 
?>

<div id="mystickyelements-tab-social-media" class="mystickyelements-tab-social-media mystickyelements-options"   style="display: <?php echo ( isset($widget_tab_index) && $widget_tab_index == 'mystickyelements-social-media' ) ? 'block' : 'none'; ?>;">
	<div class="" >
		
		<!-- Social Channels Tabs Section -->
		
		<div class="myStickyelements-container myStickyelements-social-channels-tabs">
			<div class="myStickyelements-header-title">
				<h3><?php _e('Show Chat & Social Icons', 'mystickyelements'); ?></h3>
				<label for="myStickyelements-social-channels-enabled" class="myStickyelements-switch">
					<input type="checkbox" id="myStickyelements-social-channels-enabled" name="social-channels[enable]" value="1" <?php checked( @$social_channels['enable'], '1' );?> />
					<span class="slider round"></span>
				</label>
				<p class="social-disable-info" style="display: none;"><i class="fas fa-info-circle"></i>&nbsp;&nbsp;<span><?php esc_html_e('Social channels in sticky bar has been turned off.','mystickyelements');?></span>&nbsp;&nbsp;<a href="javascript:void(0)" class="mystickyelements-turnit-on" data-turnit="myStickyelements-social-channels-enabled"><?php esc_html_e( 'Turn it on', 'mystickyelements' );?></a><?php esc_html_e( ' to collect user submitted forms from sidebar.', 'mystickyelements' );?></p>
			</div>
			<div class="mystickyelements-header-sub-title">
				<h4><?php _e( 'Enable your preferred social channels', 'mystickyelements' ); ?></h4>
			</div>
			<div class="mystickyelements-disable-wrap">
				<div class="mystickyelements-disable-content-wrap" style="display:none;">
					<div class="mystickyelements-disable-content">
						<i class="fas fa-eye-slash"></i>
						<p><?php esc_html_e( 'DISABLED', 'mystickyelements' );?></p>
					</div>
				</div>

				<div class="mystickyelements-action-popup-open mystickyelements-action-popup-status" id="socialform-status-popup" style="display:none;">
					<div class="popup-ui-widget-header">
						<span id="ui-id-1" class="ui-dialog-title"><?php echo esc_html_e( 'Are you sure?', 'mystickyelement');?></span><span class="close-dialog" data-from ='social-form'> &#10006 </span>
					</div>	
					<div id="widget-delete-confirm" class="ui-widget-content"><p><?php 
						echo esc_html_e( "You're about to turn off ", "mystickyelement");
					?> <span><?php echo esc_html_e( "social chats and channels", "mystickyelement"); ?></span><?php echo esc_html_e( ". By turning it off, this widget won't appear on your website. Are you sure?", "mystickyelement"); ?></p></div>
					<div class="popup-ui-dialog-buttonset"><button type="button" class="btn-disable-cancel button-social-popup-disable"><?php echo esc_html_e('Disable anyway','mystickyelement');?></button><button type="button" class="mystickyelement-keep-widget-btn button-social-popup-keep" data-from = "contact-form" ><?php echo esc_html_e('Keep using','mystickyelement');?></button></div>
				</div>
				<div id="mystickyelement-social-popup-overlay" class="stickyelement-overlay" data-from = "social-form" style="display:none;"></div>
				<div class="myStickyelements-social-search">
					<label><?php _e( 'Quick Search', 'mystickyelements' ); ?></label>
					<div class="myStickyelements-social-search-wrap">
						<input type="text" placeholder="facebook" /><i class="fas fa-search"></i>
					</div>
				</div>
				<ul class="myStickyelements-social-channels-lists">
					<?php foreach( $social_channels_lists as $key=>$value):
						$is_locked = (isset($value['custom']) && $value['custom'] == 1 && isset($value['is_locked']) && !$is_pro_active)?1:0;
						?><li data-search="<?php echo str_replace("_", " ", $key); ?>" <?php if ( $is_locked == 1 ):?> class="upgrade-myStickyelements" <?php endif;?>>
							<label>
								<span class="social-channels-list social-<?php echo esc_attr($key);?>" style="background-color: <?php echo $value['background_color']?>"><i class="<?php echo esc_attr($value['class']);?>"></i></span>
								<input type="checkbox" data-social-channel="<?php echo esc_attr($key);?>" class="social-channel" name="social-channels[<?php echo esc_attr($key);?>]" value="1" <?php checked( @$social_channels[$key], '1' );?>   <?php if( isset($value['is_locked']) && $value['is_locked'] == 1) { echo "disabled";}?>/>
							</label>
							<span class="social-tooltip-popup">
								<?php 
								if ( isset($value['custom_tooltip']) && $value['custom_tooltip'] != "" ) {
									echo $value['custom_tooltip'];
								} else {
									echo ucwords(str_replace("_", " ", $value['hover_text']));
								}											
								?>
							</span>
						</li><?php endforeach;?>
				</ul>
				<?php if(!$is_pro_active) {?>
				<div class="social-channel-popover" style="display:none;">
					<a  href="<?php echo $upgrade_url ?>" target="_blank">
						<?php _e('Get unlimited channels in the Pro plan by', 'mystickyelements'); ?>
						<strong><?php _e('Activating Your Key', 'mystickyelements'); ?></strong>
					</a>
				</div>
				<?php } ?>

				<div class="myStickyelements-social-channels-info">
					<div class="mystickyelements-header-sub-title">
						<h4><?php _e( 'Customize Channel Specific Behavior', 'mystickyelements' ); ?></h4>
					</div>
					<div class="social-channels-tab">
						<?php
						if (!empty($social_channels_tabs)) {
							global $social_channel_count;
							$social_channel_count = 1;
							foreach( $social_channels_tabs as $key=>$value) {
								$this->mystickyelement_social_tab_add( $key, $element_widget_no );
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>