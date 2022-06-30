<?php
global $wp_version ;

$contact_form = get_option( 'mystickyelements-contact-form' );
$show_mailchimp_integration_popup = get_option( 'mystickyelements_show_mailchimp_integration_popup' );
$show_mailpoet_integration_popup = get_option( 'mystickyelements_show_mailpoet_integration_popup' );

$mailchimp_flg = false;
$mailpoet_flg = false;

$license_data = MyStickyElementLicense::get_license_data();
$is_pro_active = 0;
if(!empty($license_data)) {
	if($license_data['license'] == "expired" || $license_data['license'] == "valid") {
		$is_pro_active = 1;
	}
}
if( isset($_POST['elements_mc_api_key'])) {
	$mc_api_key = $_POST['elements_mc_api_key'];
	$dataCenter = substr($mc_api_key,strpos($mc_api_key,'-')+1);
	
	$headers = array(
		'Authorization' => 'Basic ' . base64_encode('user:'.$mc_api_key),
		'Content-Type: application/json',
	);
	$data = array(
		'fields' => 'lists', // total_items, _links		
		'count' => 100, // the number of lists to return, default - all
	);
	
	$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/?' . http_build_query($data);
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
	
	if ( isset($api_response_body['lists']) && !empty($api_response_body['lists']) ) {
		update_option( 'elements_mc_api_key', $_POST['elements_mc_api_key']);
		$mailchimp_lists = array();
		foreach( $api_response_body['lists'] as $lists) {
			$mailchimp_lists[] = array('id' => $lists['id'], 'name' => $lists['name']);
		}
		update_option( 'element_mc_lists', $mailchimp_lists);
		$mailchimp_flg = true;
	} else {
		update_option( 'elements_mc_api_key', '');
		update_option( 'element_mc_lists', '');
	}	
}

if ( isset($_POST['mailpoet_connect']) && $_POST['mailpoet_connect'] == 1 ) {
	update_option( 'elements_mailpoet_connect', '1');
	$mailpoet_flg = true;
}

if ( isset($_POST['disconnect_mailchimp']) && $_POST['disconnect_mailchimp'] == 1 ) {
	update_option( 'elements_mc_api_key', '');
	update_option( 'element_mc_lists', '');
}

if ( isset($_POST['disconnect_mailpoet']) && $_POST['disconnect_mailpoet'] == 1 ) {
	update_option( 'elements_mailpoet_connect', '');
}
$elements_mc_api_key = get_option( 'elements_mc_api_key');
$elements_mailpoet_connect = get_option( 'elements_mailpoet_connect');
?>
<div class="mystickyelement-new-widget-wrap">
	<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins" />	
	<h2 class="text-center"><?php esc_html_e( 'Connect your My Sticky Elements form to the following platforms. Your leads will be added to the select platforms', 'mystickyelements' ); ?></h2>
	<div class="mystickyelement-new-widget-row">
		<div class="mystickyelement-features">
			<ul>
				<li>
					<div class="elements-int-container mystickyelement-feature <?php echo ( !$is_pro_active ) ? 'mystickyelement-free' : '';?>">
						<div class="mystickyelement-feature-top">
							<img src="<?php echo MYSTICKYELEMENTS_PRO_URL ?>/images/mailchimp.png" />
						</div>
						<div class="feature-title">Connect your forms to MailChimp</div>
						<div id="elements-int-container-content feature-description">
							<form method="post" action="" id="elements-mc-form">
								<?php if($is_pro_active) : ?>
								<input type="text" id="elements-mc-api-key" name="elements_mc_api_key" value="<?php echo $elements_mc_api_key;?>" placeholder="<?php esc_html_e( 'Enter Your MailChimp API Key','mystickyelements' );?>" style="width: 100%;">
								<?php endif; ?>
								<p>
								<button class="integrate-element-form button-primary <?php echo ($elements_mc_api_key !='') ? 'btn-connected' : '';?>">
									<?php echo ($elements_mc_api_key !='') ? 'Connected' : 'Connect to MailChimp';?>
								</button>
								</p>
								<?php if ( $elements_mc_api_key == '' ) :?>
								<p>
									<a href="https://mailchimp.com/help/about-api-keys/#Find_or_generate_your_API_key" target="_blank" <?php if(!$is_pro_active) : ?> style="display:none;"<?php endif; ?>>
										How to create your API key 
									</a>
								</p>
								<?php endif;?>
								
							</form>
							<?php  if ( $elements_mc_api_key != '' ):?>								
								<form method="post" action="" id="elements-mc-form">
									<p><button class="integrate-element-form button-primary">
										Disconnect 
									</button></p>
									<input type="hidden" name="disconnect_mailchimp" value ="1" />
								</form>
							<?php endif;?>
							
						</div>
						<?php if(!$is_pro_active) : ?>
						<div class="mystickyelement-integration-button">
							<a href="<?php echo esc_url(admin_url("admin.php?page=my-sticky-license-key")); ?>" class="new-upgrade-button" target="blank">ACTIVATE YOUR KEY</a>
						</div>
						<?php endif; ?>
					</div>
				</li>
				<li>
					<div class="elements-int-container mystickyelement-feature <?php echo ( !$is_pro_active ) ? 'mystickyelement-free' : '';?>">
						<div class="mystickyelement-feature-top">
							<img src="<?php echo MYSTICKYELEMENTS_PRO_URL ?>/images/mailpoet.png" />
						</div>
						
						<div class="feature-title">Connect your forms to MailPoet</div>
						
						<div id="elements-int-container-content feature-description">
							<?php
							$plugin = 'mailpoet/mailpoet.php';
							$installed_plugins = get_plugins();	
							if ( isset($installed_plugins[$plugin]) && !is_plugin_active($plugin)) {
					
								if ( ! current_user_can( 'activate_plugins' ) ) {
									return; 
								}
								$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
								
								$admin_message = '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__( 'Connect to MailPoet' ) ) . '</p>';
								
							} elseif ( ! class_exists( '\MailPoet\API\API' ) ) {
								
								$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=mailpoet' ), 'install-plugin_mailpoet' );
								  	
								$admin_message = '<p>' . esc_html__( 'Install MailPoet plugin to connect your forms' ) . '</p>';
								
								$admin_message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__( 'Install MailPoet Now' ) ) . '</p>';
							} else {			
								$admin_message = '<form method="post" action="" id="elements-mc-form">';
								$admin_message .='<p>&nbsp;</p><p><button class="integrate-element-form button-primary ' . ( ($elements_mailpoet_connect !='') ? 'btn-connected' : '' ). '">
									'. ( ($elements_mailpoet_connect !='') ? 'Connected' : 'Connect to MailPoet' ) .'</button><input type="hidden" name="mailpoet_connect" value="1" /></p>';
								
								$admin_message .= '</form >';
								
								if ( $elements_mailpoet_connect != '' ) {
									$admin_message .= '<form method="post" action="" id="elements-mc-form">';
									$admin_message .='<p><button class="integrate-element-form button-primary ">Disconnect</button></p>';
									$admin_message .= '<input type="hidden" name="disconnect_mailpoet" value ="1" /></form >';
								}
							}
							echo $admin_message;
							?>			
						</div>
						<?php if(!$is_pro_active) : ?>
						<div class="mystickyelement-integration-button">
							<a href="<?php echo esc_url(admin_url("admin.php?page=my-sticky-license-key")); ?>" class="new-upgrade-button" target="blank">ACTIVATE YOUR KEY</a>
						</div>
						<?php endif; ?>
					</div>
				</li>
			</ul>
			<div class="clear clearfix"></div>
		</div>
	</div>	
</div>

<style>
*, ::after, ::before {
    box-sizing: border-box;
}
/*New Widget Page css*/
.mystickyelement-new-widget-wrap {
    border-radius: 10px;
    padding: 10px;
    margin: 40px auto 0 auto;
    background-size: auto 100%;
    width: 100%;
    max-width: 776px;
    background: #fff url("../images/bg.png") right bottom no-repeat;
    font-family: 'Poppins';
    line-height: 20px;
}

.mystickyelement-new-widget-wrap h2 {
    font-style: normal;
    font-weight: 600;
    font-size: 20px;
    line-height: 30px;
    color: #1E1E1E;
    margin: 15px 0;
}
.mystickyelement-features ul {
    margin: 0;
    padding: 0;
}
.mystickyelement-features ul li {
    margin: 0;
    width: 50%;
    float: left;
    padding: 10px;
}
.mystickyelement-feature {
    margin: 30px 0 0 0;
    background: #FFFFFF;
    border: 1px solid #605DEC;
    box-sizing: border-box;
    border-radius: 4px;
    padding: 30px 15px 10px 15px;
    min-height: 186px;
    position: relative;
}
.mystickyelement-feature.mystickyelement-free {
	min-height: 140px;
}
.mystickyelement-feature.second {
    min-height: 155px;
}
.feature-title {
    font-family: Poppins;
    font-style: normal;
    font-weight: bold;
    font-size: 13px;
    line-height: 18px;
    color: #1E1E1E;
}
.feature-description {
    font-family: Poppins;
    font-style: normal;
    font-weight: normal;
    font-size: 13px;
    line-height: 18px;
    color: #1E1E1E;
}
a.new-upgrade-button {
    height: 40px;
    background: #605DEC;
    border-radius: 100px;
    border: solid 1px #605DEC;
    display: inline-block;
    text-align: center;
    color: #fff;
    line-height: 40px;
    margin: 0px 0 10px 10px;
    padding: 0 21px;
    text-decoration: none;
    text-transform: uppercase;
}
a.new-demo-button {
    height: 40px;
    color: #605DEC;
    border: solid 1px #605DEC;
    border-radius: 100px;
    display: inline-block;
    text-align: center;
    background: #fff;
    line-height: 40px;
    margin: 10px 0 10px 10px;
    padding: 0 25px;
    text-decoration: none;
    width: 165px;
}
.mystickyelement-feature.analytics {
    min-height: 115px;
}
.mystickyelement-feature-top {
    width: 50px;
    height: 50px;
    border: solid 1px #605dec;
    border-radius: 50%;
    position: absolute;
    left: 0;
    right: 0;
    margin: 0 auto;
    top: -25px;
    background: #fff;
    z-index: 11;
    padding: 10px;
}
.mystickyelement-feature-top img {
    width: 100%;
    height: auto;
}
.integrate-element-form.button-primary.btn-connected{
	background-color: #008000;
}

.mystickyelement-features ul li:hover .mystickyelement-integration-button{
	display: block;
}
.mystickyelement-integration-button {
	display: none;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    z-index: 9;
}

</style>

<?php 
if ( !empty($contact_form) && $show_mailchimp_integration_popup == '' && $mailchimp_flg == 1 ) {
	
	update_option( 'mystickyelements_show_mailchimp_integration_popup', 1 );
	?>
	<div id="myStickyelements-connect-mailchimp" style="display:none;" title="<?php esc_attr_e( 'Connect existing forms', 'mystickyelement-submit-delete' ); ?>">
		<p><?php esc_html_e('Seems like you have existing forms. In order to connect your existing forms to MailChimp, please go to the widget\'s settings and select "Sends leads to MailChimp"' ); ?></p>
	</div>
	<style>
	.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset{
		float: none;
		text-align: center;
	}
	.ui-dialog .ui-dialog-titlebar{
		padding: 0px !important;
	}
	#myStickyelements-connect-mailchimp{
		width: 400px !important;
	}
	</style>
	<script>
		( function( $ ) {
			"use strict";
			$(document).ready(function(){
				jQuery( "#myStickyelements-connect-mailchimp" ).dialog({
					resizable: false,
					modal: true,
					draggable: false,
					height: 'auto',
					width: 400,
					buttons: {
						"Got it": {
							click: function () {
								$(this).dialog('close');
							},
							text: 'Got it',
							class: 'purple-btn'
						},												
					}
				});				
			});
		})( jQuery );
	</script>
	<?php
}

if ( !empty($contact_form) && $show_mailpoet_integration_popup == '' && $mailpoet_flg == 1 ) {
	update_option( 'mystickyelements_show_mailpoet_integration_popup', 1 );
	?>
	<div id="myStickyelements-connect-mailpoet" style="display:none;" title="<?php esc_attr_e( 'Connect existing forms', 'mystickyelement-submit-delete' ); ?>">
		<p><?php _e('Seems like you have existing forms. In order to connect your existing forms to MailPoet, please go to the widget\'s settings and select "Sends leads to MailPoet"' ); ?></p>
	</div>
	<style>
	.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset{
		float: none;
		text-align: center;
	}
	.ui-dialog .ui-dialog-titlebar{
		padding: 0px !important;
	}
	#myStickyelements-connect-mailpoet{
		width: 400px !important;
	}
	</style>
	<script>
		( function( $ ) {
			"use strict";
			$(document).ready(function(){
				jQuery( "#myStickyelements-connect-mailpoet" ).dialog({
					resizable: false,
					modal: true,
					draggable: false,
					height: 'auto',
					width: 400,
					buttons: {
						"Got it": {
							click: function () {
								$(this).dialog('close');
							},
							text: 'Got it',
							class: 'purple-btn'
						},												
					}
				});				
			});
		})( jQuery );
	</script>
	<?php
}
