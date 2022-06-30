<?php 
$custom_fields = array();
foreach ( $contact_field as $key=>$value ) {
	if ( isset($value['custom_fields']) && is_array($value['custom_fields']) ) {
		$custom_fields[] = $value['custom_fields'][0];
	}
} 
$textblock_text = ( isset($contact_form['textblock_text'])) ? $contact_form['textblock_text'] : ''; 
$textblock = ( isset($contact_form['textblock'])) ? $contact_form['textblock'] : ''; 
$textblock_apper = ( isset( $contact_form['textblock'] ) && $contact_form['textblock'] == 'textblock' ) ? 'mystickyelements-textblock-open' : '' ;

$recaptcha_site_key = ( isset($contact_form['recaptcha_site_key'])) ? $contact_form['recaptcha_site_key'] : ''; 
$recaptcha_secrete_key = ( isset($contact_form['recaptcha_secrete_key'])) ? $contact_form['recaptcha_secrete_key'] : '';

?>
<input type="hidden" id="myStickyelements-custom-fields-length" value="<?php echo (!empty($custom_fields) ) ?  max($custom_fields)+1 : 1;?>" />

<input type="hidden" name="hide_tab_index" id="hide_tab_index" />

<div id="mystickyelements-tab-contact-form" class="mystickyelements-tab-contact-form mystickyelements-options" style="display: <?php echo ( isset($widget_tab_index) && $widget_tab_index == 'mystickyelements-contact-form' ) ? 'block' : 'none'; ?>;">
	<div class="" >
		<div class="myStickyelements-header-title mystickyelements-option-field">
			<div class="myStickyelements-header-title-left">
				<h3 for="myStickyelements-contact-form-enabled">
					<?php esc_html_e('Show the Contact Form', 'mystickyelements'); ?>
				</h3>
			</div>
			<div class="myStickyelements-header-title-right">
				<label for="myStickyelements-contact-form-enabled" class="myStickyelements-switch">
					<input type="checkbox" id="myStickyelements-contact-form-enabled" name="contact-form[enable]" value="1" <?php checked( @$contact_form['enable'], '1' );?> />
					<span class="slider round"></span>
				</label>
			</div>
			<p class="contact-form-description" id="contact-form-disabled-info"><?php esc_html_e( 'Collect form submissions right from sticky side, top, or bottom bar of your website.', 'mystickyelements');?></p>
			<div class="turn-off-message" style="display: none;">
				<p><i class="fas fa-info-circle"></i><span><?php esc_html_e('Contact form in sticky bar has been turned off. ','mystickyelements');?></span>&nbsp;<a href="javascript:void(0)" class="mystickyelements-turnit-on" data-turnit="myStickyelements-contact-form-enabled"><?php esc_html_e( 'Turn it on', 'mystickyelements' );?></a><?php esc_html_e( ' to collect user submitted forms from sidebar.', 'mystickyelements' );?></p>
			</div>

			<div class="mystickyelements-action-popup-open mystickyelements-action-popup-status" id="contactform-status-popup" style="display:none;">
				<div class="popup-ui-widget-header">
					<span id="ui-id-1" class="ui-dialog-title"><?php echo esc_html_e( 'Are you sure?', 'mystickyelement');?></span><span class="close-dialog" data-from ='contact-form'> &#10006 </span>
				</div>	
				<div id="widget-delete-confirm" class="ui-widget-content"><p><?php 
					echo esc_html_e( "You're about to turn off the ", "mystickyelement");
				?> <span><?php echo esc_html_e( "contact form", "mystickyelement"); ?></span><?php echo esc_html_e( " widget. By turning it off, this widget won't appear on your website. Are you sure?", "mystickyelement"); ?></p></div>
				<div class="popup-ui-dialog-buttonset"><button type="button" class="btn-disable-cancel button-contact-popup-disable"><?php echo esc_html_e('Disable anyway','mystickyelement');?></button><button type="button" class="mystickyelement-keep-widget-btn button-contact-popup-keep" data-from = "contact-form" ><?php echo esc_html_e('Keep using','mystickyelement');?></button></div>
			</div>
			<div id="mystickyelement-contact-popup-overlay" class="stickyelement-overlay" data-from = "contact-form" style="display:none;"></div>
		</div>
		
		<?php					
		if ( ( isset($_GET['page']) && $_GET['page'] == 'my-sticky-elements-new-widget' )
						|| ( isset($_GET['widget']) && $_GET['widget'] != '' ) || ( isset($_GET['page']) && $_GET['page'] == 'my-sticky-elements' && $counts > 1 ) ) :
			if ( isset($_GET['widget']) && $_GET['widget'] == 0) {
				$widgets = $_GET['widget'];	
			}
			$mystickyelements_widget = ( isset($elements_widgets[$widgets]) && $elements_widgets[$widgets] != '' ) ? $elements_widgets[$widgets] : 'MyStickyElement #' . ($widgets+1);
		?>
		<div id="myStickyelements-widget-title" class="myStickyelements-icon-wrap myStickyelements-widget-title">
			
			<input type="hidden" name="widget_status" value="<?php if(isset($_GET['page'])) : echo $_GET['page']; endif; ?>" />
			<input type="hidden" id="mystickyelements-no-widget" name="mystickyelements-no-widget" value="<?php echo $widgets?>" />
			<input type="text" name="mystickyelements-widget" value="<?php echo esc_attr($mystickyelements_widget);?>" placeholder="<?php echo 'Widget #' . $widgets;?>">
			<i class="fas fa-pencil-alt"></i>
		</div>
		<?php endif;?>
		<div class="mystickyelements-disable-wrap">
			<div class="mystickyelements-disable-content-wrap" style="display:none;">
				<div class="mystickyelements-disable-content">
					<i class="fas fa-eye-slash"></i>
					<p><?php esc_html_e( 'DISABLED', 'mystickyelements' );?></p>
				</div>
			</div>
			<div class="myStickyelements-header-title mystickyelements-option-field mystickyelements-sub-header-color">
				<h3><?php esc_html_e( 'Customize Form Fields', 'mystickyelements' );?></h3>
			</div>
			<div id="mystickyelements-contact-form-fields" class="mystickyelements-contact-form-fields">
				<?php 
				$enable_class = '';
				if( !isset($contact_form['textblock_checkbox']) && @$contact_form['textblock_checkbox'] != 'yes') {
					$enable_class = 'hide_field';
				}	
				?>
				<div id="mystickyelements-option-field-textblock" class="mystickyelements-option-field mystickyelements-textblock-main-field<?php echo $textblock_apper; ?> mystickyelements-textblock_checkbox <?php echo $enable_class; ?>">
					<span class="mystickyelement-field-hide-content"><?php esc_html_e('Field is hidden.', 'mystickyelements');?> <label for="textblock_checkbox"><a><?php esc_html_e('Show the field', 'mystickyelements'); ?></a></label></span>
					<div class="sticky-col-1">
						<label><?php esc_html_e( 'Textblock', 'mystickyelements');?></label>
						<input type="hidden" class="contact-fields" name="contact-form[textblock]" value="<?php echo $textblock; ?>" />
					</div>
					<div class="sticky-col-2">
						<div class="mystickyelements-reqired-wrap">					
							<?php 						
							$settings = array(
								'media_buttons' => false,
								'wpautop' => false,
								'drag_drop_upload' => false,
								'textarea_name' => 'contact-form[textblock_text]',
								'textarea_rows' => 4,
								'quicktags' => false,
								'tinymce'       => array(
									'toolbar1'      => 'bold, italic, underline, link,forecolor',
									'toolbar2'  => '',
									'toolbar3'  => ''
								)
							);
							wp_editor( stripslashes($textblock_text), 'contact-form-textblock', $settings );						
							?>									
						</div>
						<div class="mystickyelements-action">
							<ul>
								<li>									
									<label for="textblock_checkbox" class="myStickyelements-visible-icon mystickyelements-custom-fields-tooltip">
										<input type="checkbox" name="contact-form[textblock_checkbox]" id="textblock_checkbox" value="yes" <?php checked( @$contact_form['textblock_checkbox'], 'yes' );?>  /> 
										<span class="visible-icon">
											<p class="show-field-tooltip"><?php _e('Show Field','mystickyelements');?></p>
											<p class="hide-field-tooltip"><?php _e('Hide Field','mystickyelements');?></p>
										</span>
									</label>
								</li>
								<li>
									<span class="textblock-delete">
										<i class='fas fa-trash-alt stickyelement-textblock-delete'></i>
									</span>
								</li>
							</ul>
							<div class="mystickyelements-hide-field-guide">
								<p><?php esc_html_e( 'The field is hidden and won’t show.', 'mystickyelements');?></p>
							</div>
						</div>
					</div>
				</div>
						
				<!-- finish Contact Block -->
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
							$enable_class = '';
							if( !isset($contact_form['name']) && @$contact_form['name'] != '1') {
								$enable_class = 'hide_field';
							}
							
				?>
							<div class="mystickyelements-option-field contact-form-option myStickyelements-icon-wrap mystickyelements-name_enable <?php echo $enable_class; ?>">
								<span class="mystickyelement-field-hide-content"><?php esc_html_e('Field is hidden.', 'mystickyelements');?> <label for="name_enable"><a><?php esc_html_e('Show the field', 'mystickyelements'); ?></a></label></span>
								<div class="mystickyelements-move-handle"></div>
								<div class="sticky-col-1">
									<input type="hidden" class="contact-fields" name="contact-field[]" value="name" />
									<label><i class="fas fa-user"></i><?php esc_html_e('Name', 'mystickyelements');?></label>
								</div>
								<div class="sticky-col-2">
									<div class="mystickyelements-reqired-wrap">	
										<input type="text" name="contact-form[name_value]" value="<?php echo $contact_form['name_value'];?>" placeholder="<?php _e('Name','mystickyelements');?>" />							
									</div>
									<div class="mystickyelements-action">
										<ul>
											<li>												
												<label  class="myStickyelements-visible-icon mystickyelements-custom-fields-tooltip">
													<input type="checkbox" id= "name_enable" name="contact-form[name]" value="1" <?php checked( @$contact_form['name'], '1' );?> />
													<span class="visible-icon">
														<p class="show-field-tooltip"><?php _e('Show Field','mystickyelements');?></p>
														<p class="hide-field-tooltip"><?php _e('Hide Field','mystickyelements');?></p>
													</span>
												</label>
											</li>
											<li>
												<label for="name_require"><?php _e('Required', 'mystickyelements');?></label>
												<label for="name_require" class="myStickyelements-switch">
													<input type="checkbox" id="name_require" class="required" name="contact-form[name_require]" value="1"  <?php checked( @$contact_form['name_require'], '1' );?> />
													<span class="slider round"></span>
												</label>
											</li>
										</ul>
										<div class="mystickyelements-hide-field-guide">
											<p><?php esc_html_e( 'The field is hidden and won’t show.', 'mystickyelements');?></p>
										</div>
									</div>
								</div>
							</div>
				<?php 
							break;
						case 'phone' : 
							$enable_class = '';
							if( !isset($contact_form['phone']) && @$contact_form['phone'] != '1') {
								$enable_class = 'hide_field';
							}
				?>
							<div class="mystickyelements-option-field contact-form-option myStickyelements-icon-wrap mystickyelements-enable_phone <?php echo $enable_class; ?>">
								<span class="mystickyelement-field-hide-content"><?php esc_html_e('Field is hidden.', 'mystickyelements');?> <label for="enable_phone"><a><?php esc_html_e('Show the field', 'mystickyelements'); ?></a></label></span>
								<div class="mystickyelements-move-handle"></div>
								<div class="sticky-col-1">
									<input type="hidden" class="contact-fields" name="contact-field[]" value="phone" />
									<label><i class="fas fa-phone"></i><?php esc_html_e('Phone', 'mystickyelements');?></label>
								</div>
								<div class="sticky-col-2">
									<div class="mystickyelements-reqired-wrap">	
										<input type="text" name="contact-form[phone_value]" value="<?php echo $contact_form['phone_value'];?>" placeholder="<?php _e('Phone','mystickyelements');?>"/>
									</div>
									<div class="mystickyelements-action">
										<ul>
											<li>												
												<label  class="myStickyelements-visible-icon mystickyelements-custom-fields-tooltip">
													<input type="checkbox" id="enable_phone" name="contact-form[phone]" value="1" <?php checked( @$contact_form['phone'], '1' );?> />
													<span class="visible-icon">
														<p class="show-field-tooltip"><?php _e('Show Field','mystickyelements');?></p>
														<p class="hide-field-tooltip"><?php _e('Hide Field','mystickyelements');?></p>
													</span>
												</label>
											</li>
											<li>
												<label for="phone_require"><?php _e('Required', 'mystickyelements');?></label>
												<label for="phone_require" class="myStickyelements-switch">
													<input type="checkbox" id="phone_require" class="required" name="contact-form[phone_require]" value="1" <?php checked( @$contact_form['phone_require'], '1' );?> />
													<span class="slider round"></span>
												</label>
											</li>
										</ul>
										<div class="mystickyelements-hide-field-guide">
											<p><?php esc_html_e( 'The field is hidden and won’t show.', 'mystickyelements');?></p>
										</div>
									</div>
								</div>
							</div>
				<?php
							break;
							
						case 'email' : 
							$enable_class = '';
							if( !isset($contact_form['email']) && @$contact_form['email'] != '1') {
								$enable_class = 'hide_field';
							}
				?>
							<div class="mystickyelements-option-field contact-form-option myStickyelements-icon-wrap mystickyelements-email_enable <?php echo $enable_class; ?>">
								<span class="mystickyelement-field-hide-content"><?php esc_html_e('Field is hidden.', 'mystickyelements');?> <label for="email_enable"><a><?php esc_html_e('Show the field', 'mystickyelements'); ?></a></label></span>
								<div class="mystickyelements-move-handle"></div>
								<div class="sticky-col-1">
									<input type="hidden" class="contact-fields" name="contact-field[]" value="email" />
									<label><i class="fas fa-envelope"></i><?php esc_html_e('Email', 'mystickyelements');?></label>
								</div>
								<div class="sticky-col-2">
									<div class="mystickyelements-reqired-wrap">	
										<input type="text" name="contact-form[email_value]" value="<?php echo $contact_form['email_value'];?>" placeholder="<?php _e('Email','mystickyelements');?>" />
									</div>
									<div class="mystickyelements-action">
										<ul>
											<li>												
												<label  class="myStickyelements-visible-icon mystickyelements-custom-fields-tooltip">
													<input type="checkbox" id="email_enable" name="contact-form[email]" value="1" <?php checked( @$contact_form['email'], '1' );?> />
													<span class="visible-icon">
														<p class="show-field-tooltip"><?php _e('Show Field','mystickyelements');?></p>
														<p class="hide-field-tooltip"><?php _e('Hide Field','mystickyelements');?></p>
													</span>
												</label>
											</li>
											<li>
												<label for="email_require"><?php _e('Required', 'mystickyelements');?></label>
												<label for="email_require" class="myStickyelements-switch">
													<input type="checkbox" id="email_require" class="required" name="contact-form[email_require]" value="1"  <?php checked( @$contact_form['email_require'], '1' );?> />
													<span class="slider round"></span>
												</label>
											</li>
										</ul>
										<div class="mystickyelements-hide-field-guide">
											<p><?php esc_html_e( 'The field is hidden and won’t show.', 'mystickyelements');?></p>
										</div>
									</div>
								</div>
							</div>
							
				<?php
							break;	
						case 'message' : 
							$enable_class = '';
							if( !isset($contact_form['message']) && @$contact_form['message'] != '1') {
								$enable_class = 'hide_field';
							}
				?>
							<div class="mystickyelements-option-field contact-form-option myStickyelements-icon-wrap mystickyelements-message_enable <?php echo $enable_class; ?>">
								<span class="mystickyelement-field-hide-content"><?php esc_html_e('Field is hidden.', 'mystickyelements');?> <label for="message_enable"><a><?php esc_html_e('Show the field', 'mystickyelements'); ?></a></label></span>
								<div class="mystickyelements-move-handle"></div>
								<div class="sticky-col-1">
									<input type="hidden" class="contact-fields" name="contact-field[]" value="message" />
									<label><i class="fas fa-comment-dots"></i><?php esc_html_e('Message', 'mystickyelements');?></label>
								</div>
								<div class="sticky-col-2">
									<div class="mystickyelements-reqired-wrap">	
										<textarea name="contact-form[message_value]" rows="5" cols="50" placeholder="<?php _e('Message','mystickyelements');?>" ><?php echo $contact_form['message_value'];?></textarea>
									</div>
									<div class="mystickyelements-action">
										<ul>
											<li>												
												<label  class="myStickyelements-visible-icon mystickyelements-custom-fields-tooltip">
													<input type="checkbox" id="message_enable" name="contact-form[message]" value="1" <?php checked( @$contact_form['message'], '1' );?> />
													<span class="visible-icon">
														<p class="show-field-tooltip"><?php _e('Show Field','mystickyelements');?></p>
														<p class="hide-field-tooltip"><?php _e('Hide Field','mystickyelements');?></p>
													</span>
												</label>
											</li>
											<li>
												<label for="message_require"><?php _e('Required', 'mystickyelements');?></label>
												<label for="message_require" class="myStickyelements-switch">
													<input type="checkbox" class="required"  id="message_require" name="contact-form[message_require]" value="1" <?php checked( @$contact_form['message_require'], '1' );?> /> 
													<span class="slider round"></span>
												</label>
											</li>
										</ul>
										<div class="mystickyelements-hide-field-guide">
											<p><?php esc_html_e( 'The field is hidden and won’t show.', 'mystickyelements');?></p>
										</div>
									</div>
								</div>
							</div>
							
				<?php
							break;	
						case 'dropdown' : 
							$enable_class = '';
							if( (isset($contact_form['dropdown']) && $contact_form['dropdown'] == '') || !isset($contact_form['dropdown']) ) {
								$enable_class = 'hide_field';
							}
							
				?>
							<div class="mystickyelements-option-field contact-form-option myStickyelements-icon-wrap  mystickyelements-dropdown_enable  <?php echo !$is_pro_active?" myStickyelements-pro-wrap":"" ?> <?php echo $enable_class; ?>" >
								<?php if(!$is_pro_active) {?>
									<p class="mystickyelement-field-hide-content upgrade-myStickyelements"><a href="<?php echo $upgrade_url ?>" target="_blank"><i class="fas fa-lock"></i><?php _e('ACTIVATE YOUR KEY', 'mystickyelements' );?></a></p>
								<?php } else { ?>
									<span class="mystickyelement-field-hide-content"><?php esc_html_e('Field is hidden.', 'mystickyelements');?> <label for="dropdown_enable"><a><?php esc_html_e('Show the field', 'mystickyelements'); ?></a></label></span>
								<?php } ?>
								<div class="mystickyelements-move-handle"></div>
								<div class="sticky-col-1">
									<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip myStickyelements-hide-tooltip">
										<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
										<p><?php esc_html_e("Show dropdown in contact form", 'mystickyelements'); ?>
											<img src="<?php echo MYSTICKYELEMENTS_PRO_URL ?>/images/dropdown-image.jpeg">
										</p>
									</div>
									<input type="hidden" class="contact-fields" name="contact-field[]" value="dropdown" />
									<label><?php esc_html_e('Dropdown', 'mystickyelements');?></label>
								</div>
								<div class="sticky-col-2">
									<div class="mystickyelements-reqired-wrap">	
										<select name="contact-form[dropdown_value]" id="" <?php echo !$is_pro_active?"disabled":"" ?> >
											<option value=""><?php echo @$contact_form['dropdown-placeholder'];?></option>
											<?php if ( isset( $contact_form['dropdown-option'] ) && !empty($contact_form['dropdown-option']) ) :
											foreach ( $contact_form['dropdown-option'] as $option) :
												if ( $option == '' ) {
													continue;
												}
												echo "<option>" . esc_html($option) . "</option>";
											endforeach;
											endif;
											?>
										</select>
									</div>
									<div class="mystickyelements-action">
										<ul>
											<li>												
												<label  class="myStickyelements-visible-icon mystickyelements-custom-fields-tooltip">
													<input type="checkbox" id="dropdown_enable" name="contact-form[dropdown]" value="1" <?php checked( @$contact_form['dropdown'], '1' );?> <?php echo !$is_pro_active?"disabled":"" ?> /> 
													<span class="visible-icon">
														<p class="show-field-tooltip"><?php _e('Show Field','mystickyelements');?></p>
														<p class="hide-field-tooltip"><?php _e('Hide Field','mystickyelements');?></p>
													</span>
												</label>
											</li>
											<li>
												<label for="dropdown_require"><?php _e('Required', 'mystickyelements');?></label>
												<label for="dropdown_require" class="myStickyelements-switch">
													<input type="checkbox" class="required"  id="dropdown_require" name="contact-form[dropdown_require]" value="1" <?php checked( @$contact_form['dropdown_require'], '1' );?> <?php echo !$is_pro_active?"disabled":"" ?> /> 
													<span class="slider round"></span>
												</label>
											</li>
											<li>
												<label class="myStickyelements-setting-label">
													<span class="contact-form-dropdown-popup contact-form-popup-setting">
														<i class="fas fa-cog"></i>&nbsp;<?php esc_html_e( 'Settings', 'mystickyelements' );?>
													</span>
												</label>
											</li>
										</ul>
										<div class="mystickyelements-hide-field-guide">
											<p><?php esc_html_e( 'The field is hidden and won’t show.', 'mystickyelements');?></p>
										</div>
									</div>
								</div>
							</div>
							
				<?php			
							break;	
						case "custom_fields":
							foreach ( $value as $cutom_field ) {
								
								$cutom_field_value = $contact_form['custom_fields'][$cutom_field];
								$enable_class = '';
								if( !isset( $cutom_field_value['custom_field'] )) {
									$enable_class = 'hide_field';
								}
								?>
									<div class="mystickyelements-customfields mystickyelements-option-field contact-form-option myStickyelements-icon-wrap mystickyelements-enable_<?php echo $cutom_field;?> <?php echo $enable_class; ?>">
										<span class="mystickyelement-field-hide-content"><?php esc_html_e('Field is hidden.', 'mystickyelements');?> <label for="enable_<?php echo $cutom_field;?>"><a><?php esc_html_e('Show the field', 'mystickyelements'); ?></a></label></span>

										<div class="mystickyelements-move-handle"></div>
										<div class="sticky-col-1">
											<input type="hidden" class="contact-fields" name="contact-field[][custom_fields][]" value="<?php echo $cutom_field;?>" />
											<span class="text_label" id="custom_field_label<?php echo $cutom_field;?>">
												<?php echo esc_html_e($cutom_field_value['custom_field_name'],'mystickyelements');?>
											</span>
											<i class="fas fa-pencil-alt stickyelement-edit"></i>
											<input type="text" class="stickyelement-edit-field" name='contact-form[custom_fields][<?php echo $cutom_field;?>][custom_field_name]' value="<?php echo $cutom_field_value['custom_field_name'];?>" />
											<input type="hidden" name="contact-form[custom_fields][<?php echo $cutom_field;?>][field_dropdown]" value="<?php echo $cutom_field_value['field_dropdown']; ?>" />
										</div>
										<div class="sticky-col-2">
											<div class="mystickyelements-reqired-wrap">	
												<?php 
													$field_dropdown = ( isset($cutom_field_value['field_dropdown']) && $cutom_field_value['field_dropdown'] != '' ) ? $cutom_field_value['field_dropdown'] : 'text';
																				
													$cutom_field_val = ( isset ($cutom_field_value['custom_field_value']) && $cutom_field_value['custom_field_value'] != '') ? stripslashes($cutom_field_value['custom_field_value']): '';
													
													/*echo "field_dropdown==>".$field_dropdown;
													echo "cutom_field_value==>".$cutom_field_value;
													echo "cutom_field_val==>".$cutom_field_val;
													*/
													if( $field_dropdown != 'textarea' && $field_dropdown != 'dropdown' ){
														if ( $field_dropdown == 'text' ){
															$custom_field_dropdown = "Enter your message";
														} elseif ( $field_dropdown == 'number' ) {
															$custom_field_dropdown = "Enter a number";
														} elseif ( $field_dropdown == 'url' ) {
															$custom_field_dropdown = "Enter your website";
														} elseif ( $field_dropdown == 'date' ) {
															$custom_field_dropdown = "mm/dd/yyyy";
														} 
														if( $field_dropdown == 'file' ){ ?>
															<input type="file" name="contact-form[custom_fields][<?php echo $cutom_field;?>][custom_field_value]" value="<?php echo $cutom_field_val;?>" style="pointer-events: none;" />
															
														<?php } else { ?>
															<input type="text" name="contact-form[custom_fields][<?php echo $cutom_field;?>][custom_field_value]" value="<?php echo $cutom_field_val;?>" placeholder="<?php _e( ucwords( $custom_field_dropdown ),'mystickyelements');?>" />
														<?php }
														?>
													<?php
													}elseif( $field_dropdown == 'textarea' ) { ?>
														<textarea name="contact-form[custom_fields][<?php echo $cutom_field;?>][custom_field_value]" rows="5" cols="50" placeholder="<?php _e('Enter your Message','mystickyelements');?>" ><?php echo $cutom_field_val;?></textarea>
													<?php 
													}else 
													{ ?>
														<select name="contact-form[custom_fields][<?php echo $cutom_field;?>][custom_field_value]" >
															<option value=""><?php echo @$cutom_field_value['dropdown-placeholder']; ?></option>
															<?php if ( isset( $cutom_field_value['dropdown-option'] ) && !empty($cutom_field_value['dropdown-option']) ) :
															foreach ( $cutom_field_value['dropdown-option'] as $option) :
																if ( $option == '' ) {
																	continue;
																}
																echo "<option value=" . esc_html($option) . " >" . esc_html($option) . "</option>";
															endforeach;
															endif;
															?>
														</select>
													<?php 
													} 
												?>
											</div>
											<div class="mystickyelements-action">
												<ul>
													<li>	
														<label class="myStickyelements-visible-icon mystickyelements-custom-fields-tooltip">
															<input type="checkbox" id="enable_<?php echo $cutom_field;?>" name="contact-form[custom_fields][<?php echo $cutom_field;?>][custom_field]" value="1" <?php checked( @$cutom_field_value['custom_field'], '1' );?> /> 
															<span class="visible-icon">
																<p class="show-field-tooltip"><?php _e('Show Field','mystickyelements');?></p>
																<p class="hide-field-tooltip"><?php _e('Hide Field','mystickyelements');?></p>
															</span>
														</label>
													</li>
													<li>
														<span class="custom-stickyelement-delete">
															<i class='fas fa-trash-alt stickyelement-delete'></i>
														</span>
													</li>
													<li>
														<label for="custom_fields_<?php echo $cutom_field;?>"><?php _e('Required', 'mystickyelements');?></label>
														<label class="myStickyelements-switch">
															<input type="checkbox" id="custom_fields_<?php echo $cutom_field;?>" class="required" name="contact-form[custom_fields][<?php echo $cutom_field;?>][custom_field_require]" value="1"  <?php checked( @$cutom_field_value['custom_field_require'], '1' );?> <?php echo !$is_pro_active?"disabled":"" ?> />
															<span class="slider round"></span>
														</label>
													</li>
													<li <?php if($cutom_field_value['field_dropdown'] != 'dropdown'): ?> style="display:none;" <?php endif;?>>
														<label class="myStickyelements-setting-label">
															<span  class="contact-form-field-popup contact-form-popup-setting dropdown-field-setting"><i class="fas fa-cog"></i> <?php esc_html_e( 'Settings', 'mystickyelements' );?></span>
														</label>
													</li>
												</ul>
												<div class="mystickyelements-hide-field-guide">
													<p><?php esc_html_e( 'The field is hidden and won’t show.', 'mystickyelements');?></p>
												</div>
											</div>
										</div>
										
										<div id="contact_form_field_open<?php echo $cutom_field;?>" class="contact-form-field-open contact-form-setting-popup-open" style="display: none;">
											<div id="contact_form_custom_dropdown<?php echo $cutom_field;?>" class="contact-form-dropdown-main" style="<?php echo ( $cutom_field_value['field_dropdown'] != 'dropdown' ) ? 'display: none;' : '' ; ?>">
												<input type="text" name="contact-form[custom_fields][<?php echo $cutom_field;?>][dropdown-placeholder]" class="contact-form-dropdown-select" value="<?php if( isset($cutom_field_value['dropdown-placeholder']) && $cutom_field_value['dropdown-placeholder']!="" ){echo esc_attr(@$cutom_field_value['dropdown-placeholder']);}else{ echo "- Select -"; }?>" placeholder="<?php esc_html_e( 'Select...', 'mystickyelement' ); ?>"/>
												<div class="contact-form-dropdown-option">
													<div class="option-value-field">
														<span class="move-icon"></span>
														<input type="text" name="contact-form[custom_fields][<?php echo $cutom_field;?>][dropdown-option][]" value=""/>
														<span class="add-customfield-dropdown-option" data-field="<?php echo $cutom_field; ?>"><?php esc_html_e( 'Add', 'mystickyelement' );?></span>
													</div>
													<?php if ( isset( $cutom_field_value['dropdown-option'] ) && !empty($cutom_field_value['dropdown-option']) ) :
														foreach ( $cutom_field_value['dropdown-option'] as $option) :
															if ( $option == '' ) {
																continue;
															}
														?>
														<div class="option-value-field">
															<span class="move-icon"></span>
															<input type="text" name="contact-form[custom_fields][<?php echo $cutom_field;?>][dropdown-option][]" value="<?php echo esc_attr( $option );?>"/>
															<span class="delete-dropdown-option"><i class="fas fa-times"></i></span>
														</div>
													<?php
														endforeach;
													endif;?>

												</div>
												<input type="submit" name="submit" class="button button-primary btn-dropdown-save" value="<?php _e('Save', 'mystickyelements');?>">
											</div>
											<span class="contact-form-dropdfown-close"><i class="fas fa-times"></i></span>
										</div>
									</div>
								<?php
							}
							break;	
					} /* Finish Awitch Case */
				
				endforeach; /* Contact Fields  */

				$enable_class = '';
				if( !isset($contact_form['consent_checkbox']) && @$contact_form['consent_checkbox'] != 'yes' ) {
					$enable_class = 'hide_field';
				}	
				
				?>
				<div class="myStickyelements-consent-main-field">
					<div class="mystickyelements-option-field-iplog mystickyelements-option-field contact-form-option myStickyelements-icon-wrap mystickyelements-consent_checkbox <?php echo $enable_class; ?>">
						
						<?php if(!$is_pro_active) {?><p class="mystickyelement-field-hide-content upgrade-myStickyelements"><a href="<?php echo $upgrade_url ?>" target="_blank"><i class="fas fa-lock"></i><?php _e('ACTIVATE YOUR KEY', 'mystickyelements' );?></a></p><?php }else{ ?>
						<span class="mystickyelement-field-hide-content"><?php esc_html_e('Field is hidden.', 'mystickyelements');?> <label for="consent_checkbox"><a><?php esc_html_e('Show the field', 'mystickyelements'); ?></a></label></span>
						<?php }?>
						<div class="mystickyelements-move-handle"></div>
						<div class="sticky-col-1">
							<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip myStickyelements-hide-tooltip">
								<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
								<p><?php esc_html_e("Add a checkbox that asks for users' consent while submitting a form", 'mystickyelements'); ?>
									<img src="<?php echo MYSTICKYELEMENTS_PRO_URL ?>/images/consent-gif.gif">
								</p>
							</div>
							<label><?php _e( 'Consent Checkbox', 'mystickyelements' );?></label>
							
						</div>
						<div class="sticky-col-2">
							<div class="mystickyelements-reqired-wrap">	
							
								<?php $consent_text = ( isset($contact_form['consent_text'])) ? $contact_form['consent_text'] : 'I agree to the terms and conditions.'; 
								$consent_text_settings = array(
									'media_buttons' => false, 
									'textarea_name' => 'contact-form[consent_text]',
									'tinymce' => false,
									'textarea_rows' => 5,
									'quicktags' => array(
										'buttons' => 'strong,em,link'
									)
								);
								wp_editor( stripslashes($consent_text), 'contact-consent_text', $consent_text_settings );
								?>
								<!--<i class="fas fa-pencil-alt"></i>	 -->
							</div>
							<div class="mystickyelements-action">
								<ul>
									<li>										
										<label class="myStickyelements-visible-icon mystickyelements-custom-fields-tooltip">
											<input type="checkbox" name="contact-form[consent_checkbox]" id="consent_checkbox" value="yes" <?php checked( @$contact_form['consent_checkbox'], 'yes' );?>  />
											<span class="visible-icon">
												<p class="show-field-tooltip"><?php _e('Show Field','mystickyelements');?></p>
												<p class="hide-field-tooltip"><?php _e('Hide Field','mystickyelements');?></p>
											</span>
										</label>
									</li>
									<li>
										<label for="consent_text_require"><?php _e('Required', 'mystickyelements');?></label>
										<label  class="myStickyelements-switch">
											<input type="checkbox" class="required" id="consent_text_require" name="contact-form[consent_text_require]" value="1" <?php checked( @$contact_form['consent_text_require'], '1' );?> />
											<span class="slider round"></span>
										</label>
									</li>
								</ul>	
								<div class="mystickyelements-hide-field-guide">
									<p><?php esc_html_e( 'The field is hidden and won’t show.', 'mystickyelements');?></p>
								</div>	
							</div>
							
						</div>
					</div>
				</div>
				<?php
				if( isset( $contact_form['iplog'] ) && $contact_form['iplog'] == 'iplog' ) {
					?>
						<div id= "mystickyelements-option-field-iplog" class="mystickyelements-option-field-iplog mystickyelements-option-field contact-form-option myStickyelements-icon-wrap mystickyelements-iplog-field">
							<div class="mystickyelements-move-handle"></div>
							<div class="sticky-col-1">
								<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip">
									<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
									<p><?php esc_html_e("When enabled, the plugin will log the IP address of each contact form submission", 'mystickyelements'); ?></p>
								</div>
								<label> 
									<?php _e( 'Enable IP address log', 'mystickyelements' );?>
								</label>
								<input type="hidden" class="contact-fields" name="contact-form[iplog]" value="iplog" />
							</div>
							<div class="sticky-col-2">
								<div class="mystickyelements-reqired-wrap">	
									<label class="myStickyelements-switch">
										<input type="checkbox" name="contact-form[iplog_checkbox]" id="iplog_checkbox" value="yes" <?php checked( @$contact_form['iplog_checkbox'], 'yes' );?>  /> 
										<span class='slider round'></span>
									</label>
								</div>
								<div class="mystickyelements-action">
									<ul>
										<li>
											<span class="iplog-delete">
												<i class='fas fa-trash-alt stickyelement-iplog-delete'></i>
											</span>
										</li>
									</ul>
								</div>
							</div>
						</div>
					<?php
				}
				if( isset( $contact_form['recaptcha'] ) && $contact_form['recaptcha'] == 'recaptcha' ) 
				{ 
					$enable_class = '';
					if( !isset($contact_form['recaptcha_checkbox']) ) {
						$enable_class = 'hide_field';
					}
					?>
						<div id="mystickyelements-option-field-recaptcha" class="mystickyelements-option-field contact-form-option myStickyelements-icon-wrap mystickyelements-recaptcha-field mystickyelements-recaptcha_checkbox <?php echo $enable_class; ?>">
							<span class="mystickyelement-field-hide-content"><?php esc_html_e('Field is hidden.', 'mystickyelements');?> <label for="recaptcha_checkbox"><a><?php esc_html_e('Show the field', 'mystickyelements'); ?></a></label></span>
							<div class="mystickyelements-move-handle"></div>
							<div class="sticky-col-1">
								<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip" >
									<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
									<p><?php _e("Click <a href='https://www.google.com/recaptcha/admin/create' target='_blank'>here</a> to add your website. (please make sure you select V3). After adding your website you'll get your site key and secret key.", 'mystickyelements'); ?></p>
								</div>
								<label><?php esc_html_e( 'reCAPTCHA', 'mystickyelements');?></label>							
								<input type='hidden' class='contact-fields' name='contact-form[recaptcha]' value='recaptcha' />
							</div>
							<div class="sticky-col-2">
								<div class="mystickyelements-reqired-wrap" style="margin-bottom:20px;">	
									<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip" >
										<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
										<p>
											<?php _e("Click COPY SITE KEY from Google reCAPTCHA and paste it here.", 'mystickyelements'); ?>
											<img src="<?php echo MYSTICKYELEMENTS_PRO_URL.'images/site-key.png'?>" />
										</p>
										
									</div>&nbsp;&nbsp;
									<input type='text' id='recaptcha_site_key' name='contact-form[recaptcha_site_key]' value='<?php if(isset($recaptcha_site_key)) : echo $recaptcha_site_key; endif; ?>' placeholder='Enter your reCAPTCHA site key' />
									<!--<i class='fas fa-pencil-alt'></i>-->
								</div>
								<div class="mystickyelements-reqired-wrap">	
									<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip" >
										<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
										<p>
											<?php _e("Click the COPY SECRET KEY from Google reCAPTCHA and paste it here.", 'mystickyelements'); ?>
											<img src="<?php echo MYSTICKYELEMENTS_PRO_URL.'images/secret-key.png'?>" />
										</p>
										
									</div>&nbsp;&nbsp;
									<input type='text' id='recaptcha_secrete_key' name='contact-form[recaptcha_secrete_key]' value='<?php if(isset($recaptcha_secrete_key)) : echo $recaptcha_secrete_key; endif; ?>' placeholder='Enter your reCAPTCHA secret key' />
									<!--<i class='fas fa-pencil-alt'></i> -->
								</div>
								<div class="mystickyelements-reqired-wrap" style="margin-top:20px;">	
									<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip" >
										<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
										<p><?php _e("You're allowed to hide the reCAPTCHA badge from the bottom of your website as long as you comply with <a href='https://developers.google.com/recaptcha/docs/faq#id-like-to-hide-the-recaptcha-badge.-what-is-allowed' target='_blank'>these guidelines</a>", 'mystickyelements'); ?></p>
									</div>&nbsp;&nbsp;
									<label>
										<input type='checkbox' name='contact-form[invisible_recaptcha_checkbox]' id='invisible_recaptcha_checkbox' value='yes' <?php checked( @$contact_form['invisible_recaptcha_checkbox'], 'yes' );?> /> &nbsp; <?php _e( 'Hide reCAPTCHA badge', 'mystickyelements' );?>&nbsp; &nbsp; 
									</label>
								</div>
								<div class="mystickyelements-action">	
									<ul>
										<li>											
											<label  class="myStickyelements-visible-icon mystickyelements-custom-fields-tooltip">
												<input type="checkbox" name="contact-form[recaptcha_checkbox]" id="recaptcha_checkbox" value="yes" <?php checked( @$contact_form['recaptcha_checkbox'], 'yes' );?>  /> 
												<span class="visible-icon">
													<p class="show-field-tooltip"><?php _e('Show Field','mystickyelements');?></p>
													<p class="hide-field-tooltip"><?php _e('Hide Field','mystickyelements');?></p>
												</span>
											</label>
										</li>
										<li>
											<span class="recaptcha-delete">
												<i class='fas fa-trash-alt stickyelement-recaptcha-delete'></i>
											</span>
										</li>
									</ul>
									<div class="mystickyelements-hide-field-guide">
										<p><?php esc_html_e( 'The field is hidden and won’t show.', 'mystickyelements');?></p>
									</div>
								</div>
							</div>
						</div>
					<?php
				}
			?>
			</div>
			
			
			
			<!-- Add Custom field-->
			<div class="myStickyelements-contact-form-field-hide myStickyelements-contact-form-field-option">
				<span class="mystickyelements-add-custom-fields">
					<a href="#" class="mystickyelements-add-custom-fields" data-isactive="<?php echo ( !$is_pro_active ) ? "0" : "1" ; ?>" data-active-page-url = "<?php echo ( !$is_pro_active ) ? $upgrade_url : '' ; ?>"> <?php esc_html_e( 'Add new field', 'mystickyelements');?><i class="fas fa-plus"></i></a>
				</span>
			</div>
			
			<div class="contact-form-dropdown-open contact-form-setting-popup-open" style="display: none;">
				<input type="text" name="contact-form[dropdown-placeholder]" class="contact-form-dropdown-select" value="<?php  if( isset($contact_form['dropdown-placeholder']) && $contact_form['dropdown-placeholder'] != '' ){ echo esc_attr(@$contact_form['dropdown-placeholder']); }else{ echo "- Select -"; }  ?>" placeholder="<?php esc_html_e( 'Select... ', 'mystickyelement' ); ?>"/>
				<div class="contact-form-dropdown-option">
					<div class="option-value-field">
						<span class="move-icon"></span>
						<input type="text" name="contact-form[dropdown-option][]" value=""/> <span class="add-dropdown-option"><?php esc_html_e( 'Add', 'mystickyelement' );?></span>
					</div>
					<?php if ( isset( $contact_form['dropdown-option'] ) && !empty($contact_form['dropdown-option']) ) :
						foreach ( $contact_form['dropdown-option'] as $option) :
							if ( $option == '' ) {
								continue;
							}
						?>
						<div class="option-value-field">
							<span class="move-icon"></span>
							<input type="text" name="contact-form[dropdown-option][]" value="<?php echo esc_attr( $option );?>"/> <span class="delete-dropdown-option"><i class="fas fa-times"></i></span>
						</div>
					<?php
						endforeach;
					endif;?>
				</div>
				<input type="submit" name="submit" class="button button-primary btn-dropdown-save" value="<?php _e('Save', 'mystickyelements');?>">
				<span class="contact-form-dropdfown-close"><i class="fas fa-times"></i></span>
			</div>
			<div class="myStickyelements-content-section mystickyelements-display-main-options">
				<!-- <div class="mystickyelements-header-main-title">
					<h2><i class="fas fa-cog"></i><?php //_e('Contact Form Preference', 'mystickyelements'); ?></h2>
				</div> -->
				<div class="mystickyelements-display-above-options myStickyelements-contact-form-tab">
					<div class="myStickyelements-header-title">
						<h3><?php _e('Contact Tab Settings', 'mystickyelements'); ?></h3>
					</div>
					<div class="myStickyelements-setting-wrap-list-main">
						<div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
							<div class="mystickyelements-setting-wrap-left">
								<label><?php _e( 'Devices', 'mystickyelements');?></label>
							</div>
							<div class="mystickyelements-setting-wrap-right">
								<label>
									<input type="checkbox" name="contact-form[desktop]" value= "1"<?php checked( @$contact_form['desktop'], '1' );?> /> &nbsp;<?php _e( 'Desktop', 'mystickyelements' );?>
								</label>
								<label>
									<input type="checkbox" name="contact-form[mobile]" value="1" <?php checked( @$contact_form['mobile'], '1' );?> /> &nbsp;<?php _e( 'Mobile', 'mystickyelements' );?>
								</label>
							</div>
						</div>
						<div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
							<div class="mystickyelements-setting-wrap-left">
								<label><?php _e( 'Direction', 'mystickyelements');?></label>
							</div>
							<div class="myStickyelements-inputs mystickyelements-setting-wrap-right myStickyelements-direction-rtl">
								<label>
									<input type="radio" name="contact-form[direction]" value= "LTR" <?php checked( @$contact_form['direction'], 'LTR' );?> /> &nbsp;<?php _e( 'LTR', 'mystickyelements' );?>
								</label>
								<label>
									<input type="radio" name="contact-form[direction]" value="RTL" <?php checked( @$contact_form['direction'], 'RTL' );?> /> &nbsp;<?php _e( 'RTL', 'mystickyelements' );?>
								</label>
							</div>
						</div>
						<div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list myStickyelements-setting-half">
							<div class="mystickyelements-setting-wrap-left">
								<label><?php _e( 'Background Color:', 'mystickyelements' );?></label>
							</div>
							<div class="mystickyelements-setting-wrap-right">
								<input type="text" id="tab_background_color" name="contact-form[tab_background_color]" class="mystickyelement-color" value="<?php echo $contact_form['tab_background_color'];?>" />
							</div>
						</div>
						<div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list myStickyelements-setting-half">
							<div class="mystickyelements-setting-wrap-left">
								<label><?php _e( 'Text Color:', 'mystickyelements' );?></label>
							</div>
							<div class="mystickyelements-setting-wrap-right">
								<input type="text" id="tab_text_color" name="contact-form[tab_text_color]" class="mystickyelement-color" value="<?php echo $contact_form['tab_text_color'];?>" />
							</div>
						</div>
						<div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list myStickyelements-setting-half">
							<div class="mystickyelements-setting-wrap-left">
								<label>
									<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip">
										<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
										<p><?php esc_html_e("The background color of the form that appears when someone hover/clicks to open the contact form", 'mystickyelements'); ?></p>
									</div>
									<?php _e('Form Background Color:', 'mystickyelements'); ?>
								</label>
								
							</div>
							<div class="mystickyelements-setting-wrap-right">
								<input type="text" id="form_bg_color" name="contact-form[form_bg_color]" class="mystickyelement-color" value="<?php echo ( isset($contact_form['form_bg_color']))? $contact_form['form_bg_color'] : '#ffffff'; ?>"/>
							</div>
						</div>
						<div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list myStickyelements-setting-half">
							<div class="mystickyelements-setting-wrap-left">
								<label>
									<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip">
										<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
										<p><?php esc_html_e("The headline color of the form that appears when someone hover/clicks to open the contact form", 'mystickyelements'); ?></p>
									</div>	
									<?php _e( 'Form Headline Color:', 'mystickyelements' );?>
								</label>
								
							</div>
							<div class="mystickyelements-setting-wrap-right">
								<input type="text" id="headine_text_color" name="contact-form[headine_text_color]" class="mystickyelement-color" value="<?php echo $contact_form['headine_text_color'];?>" />
								
							</div>
						</div>
						<div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
							<div class="mystickyelements-setting-wrap-left">
								<label><?php _e( 'Text in tab', 'mystickyelements' );?></label>
							</div>
							<div class="mystickyelements-setting-wrap-right">
								<input type="text" name="contact-form[text_in_tab]" value="<?php echo $contact_form['text_in_tab'];?>" placeholder="<?php _e('Enter text here...','mystickyelements');?>" />
							</div>
						</div>
						<div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
							<div class="mystickyelements-setting-wrap-left">
								<label><?php _e( 'Contact Form Title', 'mystickyelements' );?></label>
							</div>
							<?php if( isset( $contact_form['contact_title_text'] ) && $contact_form['contact_title_text'] != '' ) {
								$contact_title_text = $contact_form['contact_title_text']; 
							} else { 
								$contact_title_text = "Contact Form"; 
							} ?>
							<div class="mystickyelements-setting-wrap-right">
								<input type="text" name="contact-form[contact_title_text]" value="<?php echo $contact_title_text; ?>" placeholder="<?php _e('Enter text here...','mystickyelements');?>" />
							</div>
						</div>
						<table>
							<tr class="myStickyelements-contact-form-field-hide">
								<td>
									<div class="multiselect">
										<?php
										if ( isset($contact_form['send_leads']) && !is_array( $contact_form['send_leads'])) {
											$contact_form['send_leads'] = explode(', ', $contact_form['send_leads']);
										}
										
										$elements_mc_api_key = get_option( 'elements_mc_api_key');
										
										$elements_mailpoet_connect = get_option( 'elements_mailpoet_connect');
										?>
										<div id="checkboxes">
											<div class="mystickyelements-custom-fields-tooltip myStickyelements-country-tooltip">
												<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
												<p><?php esc_html_e("Save the leads locally in your website", 'mystickyelements'); ?></p>
											</div>
											<label>
												<input type="checkbox" name="contact-form[send_leads][]" id="send_leads_database" value="database" <?php if ( !empty($contact_form['send_leads']) && in_array( 'database', $contact_form['send_leads']) ) { echo 'checked="checked"'; } ?> <?php if(!$is_pro_active) : ?> checked="checked" disabled = "disabled"<?php endif; ?>  />&nbsp;<?php _e( 'Send Leads to Local database', 'mystickyelements' );?>
                                            </label>
											<a href="<?php echo admin_url('admin.php?page=my-sticky-elements-leads'); ?>" id="send_lead_to_contact_form" target="_blank"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 6H6C4.89543 6 4 6.89543 4 8V18C4 19.1046 4.89543 20 6 20H16C17.1046 20 18 19.1046 18 18V14M14 4H20M20 4V10M20 4L10 14" stroke="#475569" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
										</div>
									</div>
									<div class="multiselect send-lead-email-upgrade">
										<div id="checkboxes">
											<?php 
												if(!$is_pro_active){
													?>
													<label>
														<input type="checkbox" name="contact-form[send_leads][]" id="send_leads_mail" value="mail"  disabled="disabled" />&nbsp;<?php _e( 'Send leads to your email', 'mystickyelements' );?>
													</label>
													<span class="upgrade-myStickyelements"><a href="<?php echo $upgrade_url ?>" target="_blank"><i class="fas fa-lock"></i><?php _e('ACTIVATE YOUR KEY', 'mystickyelements' );?></a></span>	
													<?php
												}
												else{
													?>	
													<label>
														<input type="checkbox" name="contact-form[send_leads][]" id="send_leads_mail" value="mail" <?php if ( !empty($contact_form['send_leads']) && in_array( 'mail', $contact_form['send_leads']) ) { echo 'checked="checked"'; } ?> <?php if(!$is_pro_active) : ?> disabled="disabled" <?php endif; ?>/>&nbsp;<?php _e( 'Send leads to your email', 'mystickyelements' );?>
													</label>
													
												<?php	
												}
											
											?>
										</div>
									</div>
									<div id="contact-form-send-mail" class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list" style="display:none">
										<div class="mystickyelements-setting-wrap-left">
											<div class="mystickyelements-custom-fields-tooltip mystickyelements-email-tooltip">
												<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
												<p><?php esc_html_e( 'If you want to send leads to more than one email address, please add your email addresses separated by commas', 'mystickyelements');?></p>
											</div>
											<label><?php _e( 'Email', 'mystickyelements' );?></label>
										</div>
										<div class="mystickyelements-setting-wrap-right">
											<input type="text" name="contact-form[sent_to_mail]" value="<?php echo @$contact_form['sent_to_mail'];?>" placeholder="<?php _e('Enter your email','mystickyelements');?>" />
											<p class="description"><?php esc_html_e( 'Check your Spam folder and Promotions tab', 'mystickyelements');?></p>
											
										</div>
									</div>
									<div id="contact-form-sendr-name" class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list" style="display:none">
										<div class="mystickyelements-setting-wrap-left">	
											<div class="mystickyelements-custom-fields-tooltip mystickyelements-email-tooltip">
												<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
												<p><?php esc_html_e( 'The name that will appear as the sender name in your email', 'mystickyelements');?></p>
											</div>	
											<label><?php _e( "Sender's name", 'mystickyelements' );?></label>
										</div>
										<div class="mystickyelements-setting-wrap-right">
											<?php $contact_form['sender_name'] = ( isset($contact_form['sender_name'])) ? $contact_form['sender_name'] : '';?>
											<input type="text" name="contact-form[sender_name]" value="<?php echo $contact_form['sender_name'];?>" placeholder="<?php _e('Enter sender name');?>" />											
										</div>
									</div>
									<div id="contact-form-mail-subject-line" class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list" style="display:none">
										<div class="mystickyelements-setting-wrap-left">	
											<div class="mystickyelements-custom-fields-tooltip mystickyelements-email-tooltip">
												<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
												<p><?php esc_html_e( "The subject line of the emails that you'll recieve from each contact form submission", 'mystickyelements');?></p>
											</div>
											<label><?php _e( 'Email subject line', 'mystickyelements' );?></label>
										</div>
										<div class="mystickyelements-setting-wrap-right">
											<?php $email_subject_line = ( isset($contact_form['email_subject_line'])) ? $contact_form['email_subject_line'] : 'New lead from MyStickyElements'; ?>
											<input type="text" name="contact-form[email_subject_line]" value="<?php echo $email_subject_line;?>" placeholder="<?php _e('Enter your email subject line','mystickyelements');?>" />
										</div>
									</div>
									<div class="multiselect">
										<div id="checkboxes">
											<?php 
											if(!$is_pro_active){
												?>
												<label>
													<input type="checkbox" name="contact-form[send_leads][]" id="send_leads_mailchimp" value="mailchimp"  disabled="disabled" />&nbsp;<?php _e( 'Sends leads to MailChimp', 'mystickyelements' );?>
												</label>
												<span class="upgrade-myStickyelements"><a href="<?php echo $upgrade_url ?>" target="_blank"><i class="fas fa-lock"></i><?php _e('ACTIVATE YOUR KEY', 'mystickyelements' );?></a></span>
												<?php
											}	
											else{
												if ( isset($elements_mc_api_key) && 	$elements_mc_api_key != '' ):
												?>
												<label>
													<input type="checkbox" name="contact-form[send_leads][]" id="send_leads_mailchimp" value="mailchimp" <?php if ( !empty($contact_form['send_leads']) && in_array( 'mailchimp', $contact_form['send_leads']) ) { echo 'checked="checked"'; } ?> />&nbsp;<?php _e( 'Sends leads to MailChimp', 'mystickyelements' );?>
												</label>
											<?php endif; }?>	
											
											
										</div>
									</div>
									<?php 
									if ( isset($elements_mc_api_key) && $elements_mc_api_key != '' ){
										include('mystickyelement-mailchimp-integration.php');
									}
									?>
									<div class="multiselect send-lead-mailpoet-upgrade">
										<div id="checkboxes">
											<?php 
											//8echo "elements_mailpoet_connect ".$elements_mailpoet_connect;
											
											if(!$is_pro_active){
												?>
												<label>
													<input type="checkbox" name="contact-form[send_leads][]" id="send_leads_mailpoet" value="mailpoet" <?php if ( !empty($contact_form['send_leads']) && in_array( 'mailpoet', $contact_form['send_leads']) ) { echo 'checked="checked"'; } ?> disabled="disabled"/>&nbsp;<?php _e( 'Sends leads to MailPoet', 'mystickyelements' );?>
												</label><span class="upgrade-myStickyelements"><a href="<?php echo $upgrade_url ?>" target="_blank"><i class="fas fa-lock"></i><?php _e('ACTIVATE YOUR KEY', 'mystickyelements' );?></a></span>
												<?php
											}
											else{
												
												if ( isset($elements_mailpoet_connect) && $elements_mailpoet_connect != '' ):?>
													<label>
														<input type="checkbox" name="contact-form[send_leads][]" id="send_leads_mailpoet" value="mailpoet" <?php if ( !empty($contact_form['send_leads']) && in_array( 'mailpoet', $contact_form['send_leads']) ) { echo 'checked="checked"'; } ?> />&nbsp;<?php _e( 'Sends leads to MailPoet', 'mystickyelements' );?>
													</label>												
											<?php endif; }?>
											
											
											
										</div>
									</div>
									<?php
									if ( isset($elements_mailpoet_connect) && $elements_mailpoet_connect != '' ){
										include('mystickyelement-mailpoet-integration.php');
									}
									?>
								</td>
							</tr>
						</table>
						
					</div>
				
					<div class="myStickyelements-header-title">
						<h3><?php _e('Submit Button Settings', 'mystickyelements'); ?></h3>
					</div>
					<div class="myStickyelements-setting-wrap-list-main"> 
						<div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list myStickyelements-setting-half">
							<div class="mystickyelements-setting-wrap-left">
								<label><?php _e( 'Background Color:', 'mystickyelements' );?></label>
							</div>
							<div class="mystickyelements-setting-wrap-right">
								<input type="text" id="submit_button_background_color" name="contact-form[submit_button_background_color]" class="mystickyelement-color" value="<?php echo esc_attr($contact_form['submit_button_background_color']); ?>" />
							</div>
						</div>
						<div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list myStickyelements-setting-half">
							<div class="mystickyelements-setting-wrap-left">
								<label><?php _e( 'Text Color:', 'mystickyelements' );?></label>
							</div>
							<div class="mystickyelements-setting-wrap-right">
								<input type="text" id="submit_button_text_color" name="contact-form[submit_button_text_color]" class="mystickyelement-color" value="<?php echo esc_attr($contact_form['submit_button_text_color']);?>" />
							</div>
						</div>
						<div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
							<div class="mystickyelements-setting-wrap-left">
								<label><?php _e( 'Text on the submit button', 'mystickyelements' );?></label>
							</div>
							<div class="mystickyelements-setting-wrap-right">
								<input type="text" id="contact-form-submit-button" name="contact-form[submit_button_text]" value="<?php echo $contact_form['submit_button_text'];?>" placeholder="<?php _e('Enter text here...','mystickyelements');?>"  />
							</div>
						</div>
						<div class="myStickyelements-redirect-link-wrap myStickyelements-setting-wrap">
							<div class="myStickyelements-redirect-block">
								<label>
									<input type="checkbox" id="redirect_after_submission" name="contact-form[redirect]" value="1" <?php checked( @$contact_form['redirect'], '1' );?> <?php echo !$is_pro_active?"disabled":"" ?> /> &nbsp; <?php _e('Redirect visitors after submission', 'mystickyelements');?>
								</label>
								<label class="myStickyelements-redirect-new-tab" style="display: none;">
									<input type="checkbox" name="contact-form[open_new_tab]" value= "1"<?php checked( @$contact_form['open_new_tab'], '1' );?> /> &nbsp;<?php _e( 'Open in a new tab', 'mystickyelements' );?>
								</label>
							</div>
							<div class="redirect-link-input">
								<input type="text" name="contact-form[redirect_link]" value="<?php echo @$contact_form['redirect_link'];?>" class="myStickyelements-redirect-link" placeholder="<?php _e('Enter redirect link','mystickyelements');?>" <?php echo !$is_pro_active?"disabled":"" ?> />
								<?php if(!$is_pro_active) {?><span class="upgrade-myStickyelements"><a href="<?php echo $upgrade_url ?>" target="_blank"><i class="fas fa-lock"></i><?php _e('ACTIVATE YOUR KEY', 'mystickyelements' );?></a></span><?php } ?>
							</div>
						</div>
						<div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
							<div class="mystickyelements-setting-wrap-left">
								<label><?php _e( 'Thank you message', 'mystickyelements' );?></label>
								<?php if(!$is_pro_active) {?><span class="upgrade-myStickyelements"><a href="<?php echo $upgrade_url ?>" target="_blank"><i class="fas fa-lock"></i><?php _e('ACTIVATE YOUR KEY', 'mystickyelements' );?></a></span><?php } ?>
							</div>

							<div class="myStickyelements-thankyou-input mystickyelements-setting-wrap-right">
								<?php $thank_you_message = ( isset($contact_form['thank_you_message'])) ? $contact_form['thank_you_message'] : 'Your message was sent successfully';?>
								<input type="text" name="contact-form[thank_you_message]" value="<?php echo $thank_you_message;?>" placeholder="<?php _e('Enter thank you message here...','mystickyelements');?>"  <?php echo !$is_pro_active?"disabled":"" ?> />
							</div>
						</div>
						<div class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
							<div class="mystickyelements-setting-wrap-left">
								<label for="myStickyelements-contact-form-close">
									<span class="mystickyelements-custom-fields-tooltip">
										<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
										<p>Close the form automatically after a few seconds based on your choice</p>
									</span>
									<?php _e( 'Close form automatically after submission', 'mystickyelements' );?>
								</label>
							</div>

							<div class="myStickyelements-thankyou-input mystickyelements-setting-wrap-right">
								<label for="myStickyelements-contact-form-close" class="myStickyelements-switch">
									<input type="checkbox" id="myStickyelements-contact-form-close" name="contact-form[close_form_automatic]" value="1" <?php checked( @$contact_form['close_form_automatic'], '1' );?>>
									<span class="slider round"></span>
								</label>
							</div>
						</div>
						<div id="contact-form-close-after" class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list" <?php if( !isset($contact_form['close_form_automatic']) ):?> style="display:none" <?php endif;?>>
							<div class="mystickyelements-setting-wrap-left">
								<label for="myStickyelements-contact-form-close-after"><?php _e( 'Close after', 'mystickyelements' );?></label>
							</div>

							<div class="myStickyelements-thankyou-input mystickyelements-setting-wrap-right">
								<?php $close_after = ( isset($contact_form['close_after'])) ? $contact_form['close_after'] : '1';?>
								<label>
									<input type="number" name="contact-form[close_after]" value="<?php echo $close_after;?>" placeholder=""  <?php echo !$is_pro_active?"disabled":"" ?> style="width:140px;"/>&nbsp; seconds
								</label>
								<?php if(!$is_pro_active) {?><span class="upgrade-myStickyelements"><a href="<?php echo $upgrade_url ?>" target="_blank"><i class="fas fa-lock"></i><?php _e('ACTIVATE YOUR KEY', 'mystickyelements' );?></a></span><?php } ?>
							</div>
						</div>
					</div>
					
					<!-- work -->
						
				</div>
			</div>
			
			
		</div>
	</div>
</div>
