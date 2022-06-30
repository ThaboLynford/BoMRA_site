<?php
$dropdown = ( isset($contact_form['dropdown'])) ? $contact_form['dropdown'] : '';
if ( $dropdown == 1 ) {
	$contact_form['custom_fields'][] = array('custom_field_name' => 'Dropdown');
}


$mailchimp_list = ( isset($contact_form['mailchimp_list'])) ? $contact_form['mailchimp_list'] : '';
$mailchimp_enable_tag = ( isset($contact_form['mailchimp_enable_tag'])) ? $contact_form['mailchimp_enable_tag'] : '';
$mailchimp_tags = ( isset($contact_form['mailchimp_tags'])) ? $contact_form['mailchimp_tags'] : '';

$mailchimp_enable_group = ( isset($contact_form['mailchimp-enable-group'])) ? $contact_form['mailchimp-enable-group'] : '';
$mailchimp_group = ( isset($contact_form['mailchimp-group'])) ? $contact_form['mailchimp-group'] : array();
$mailchimp_field_mapping = ( isset($contact_form['mailchimp-field-mapping'])) ? $contact_form['mailchimp-field-mapping'] : array();

$mc_fields = mystickyelements_get_mailchimp_lists_fields( $mailchimp_list );
?>
<div id="contact-form-mailchimp" class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
	<h4>MailChimp integration settings</h4>
	<div class="myStickyelements-mailchimp-settings">
		<label><?php esc_html_e( 'Select a MailChimp list' );?></label>
		<?php $element_mc_lists = get_option( 'element_mc_lists');?>
		<select id="stickyelement_mailchimp_lists" name="contact-form[mailchimp_list]">
			<option value="">Select a list</option>
			<?php 
			if ( !empty($element_mc_lists)) :
			foreach($element_mc_lists as $lists):?>
				<option value="<?php echo $lists['id']?>" <?php selected($lists['id'], @$mailchimp_list, true);?>><?php echo $lists['name']?></option>
			<?php endforeach;
			endif;
			?>
		</select>
	</div>
	<div class="myStickyelements-mailchimp-settings myStickyelements-setting-wrap-list1">
		<div class="myStickyelements-setting-wrap-list" style="display:inline-block;">
			<label class="mailchimp-enable-tag" style="margin-right:15px;"><?php esc_html_e( 'Enable tags' );?></label>
			<label class="myStickyelements-switch">
				<input type="checkbox" id="mailchimp-enable-tag" name="contact-form[mailchimp_enable_tag]" value="yes" <?php checked( @$mailchimp_enable_tag, 'yes' ); ?> />
				<span class="slider round"></span>
			</label>			
			
		</div>
		<div class="myStickyelements-mailchimp-tags-info" <?php if ( @$mailchimp_enable_tag != 'yes'):?>style="display:none" <?php endif;?>>
			<input type="text" name="contact-form[mailchimp_tags]" value="<?php echo @$mailchimp_tags;?>" placeholder="Example: WP tag, Another tag" style="float: none; width: 100%"/>
			<p class="description">The listed tags will be applied to all subscribers added by this form. Separate multiple values with a comma. </p>
		</div>
	</div>
	<?php $mailchimp_groups = myStickyelements_get_mailchimp_groups( $mailchimp_list ); ?>
	<div class="myStickyelements-mailchimp-groups myStickyelements-setting-wrap-list" <?php if( empty($mailchimp_groups)  ):?> style="display:none;" <?php endif;?>>
		<div class="myStickyelements-setting-wrap-list">
			<label class="mailchimp-enable-group"><?php esc_html_e( 'Enable groups' );?></label>
			<label class="myStickyelements-switch">
				<input type="checkbox" id="mailchimp-enable-group" name="contact-form[mailchimp-enable-group]" value="yes" <?php checked( $mailchimp_enable_group, 'yes' ); ?> />
				<span class="slider round"></span>
			</label>
		</div>
		<div class="mailchimp-group-info" <?php if ( $mailchimp_enable_group != 'yes'):?>style="display:none" <?php endif;?>>
			<select id="mailchimp-group" name="contact-form[mailchimp-group][]" multiple>
				<option value="">Select Groups</option>
				<?php if ( !empty($mailchimp_groups)) :?>
					<?php foreach( $mailchimp_groups as $key=>$groups ):?>
						<optgroup label="<?php echo esc_html($key);?>">
						<?php foreach($groups as $group ):?>
							<option value="<?php echo esc_html($group['id']);?>" <?php if ( in_array($group['id'] ,$mailchimp_group)){ echo "selected"; }?>><?php echo esc_html($group['name']);?></option>
						<?php endforeach;?>
						</optgroup>
					<?php endforeach;?>
				<?php endif;?>
			</select>
		</div>
	</div>
	<div class="myStickyelements-mailchimp-field-mapping myStickyelements-setting-wrap-list" <?php if( !isset($contact_form['custom_fields']) && empty( $contact_form['custom_fields'] ) || $mailchimp_list == '' ) :?> style="display:none;" <?php endif;?>>
		<label>
		Field mapping
			<span class="mystickyelements-custom-fields-tooltip">
				<a href="javascript:void(0);" class="mystickyelements-tooltip mystickyelements-new-custom-btn"><i class="fas fa-info"></i></a>
				<p>Your default fields (email, name, etc) will be automatically synced. Use the field mapping option to decide which My Sticky Elements fields are pushed to your integration's fields.</p>
			</span>
		</label>
		<div class="myStickyelements-mailchimp-field-lists">
			<?php if( isset($contact_form['custom_fields']) && !empty( $contact_form['custom_fields'] )) :?>
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
				<?php endforeach;?>
			<?php endif;?>
		</div>
	</div>
</div>