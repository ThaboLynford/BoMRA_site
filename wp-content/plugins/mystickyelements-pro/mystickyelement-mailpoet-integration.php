<?php if (  class_exists( '\MailPoet\API\API' ) ) :

$mailpoet_list = ( isset($contact_form['mailpoet_list'])) ? $contact_form['mailpoet_list'] : '';
?>

<div id="contact-form-MailPoet" class="myStickyelements-setting-wrap myStickyelements-setting-wrap-list">
	<h4>MailPoet integration settings</h4>
	<div class="myStickyelements-mailchimp-settings">
		<label><?php esc_html_e( 'Select a MailPoet list' );?></label>
		<?php $mailpoet_lists = \MailPoet\API\API::MP( 'v1' )->getLists();?>
		<select id="sfba_mailchimp_lists" name="contact-form[mailpoet_list]">
			<option value="">Select a list</option>
			<?php 
			if ( !empty($mailpoet_lists)) :
			foreach($mailpoet_lists as $lists):
				if ( $lists['id'] != '' && $lists['name'] != '' ) {
				?>
				<option value="<?php echo $lists['id']?>" <?php selected($lists['id'], @$mailpoet_list, true);?>><?php echo $lists['name']?></option>
				<?php }
				endforeach;
			endif;
			?>
		</select>
	</div>	
</div>

<?php endif;?>