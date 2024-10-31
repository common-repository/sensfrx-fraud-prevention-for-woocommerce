<?php
/**
 * Provide a admin area view for the plugin
 * This file is used to markup the admin-facing aspects of the plugin.
 * 
 * @link       https://sensfrx.ai
 * @since      1.0.0
 * @package    SensFRX
 * @subpackage SensFRX/admin/partials
*/
// This file should primarily consist of HTML with a little bit of PHP.
// Google Analytics - Settings Display
if (!function_exists('add_action')) {
	die(); 
} 
if (!defined('ABSPATH')) {
	die('ASPATH is required - POLICY PAGE');
}

$options = get_option('sensfrx_options', SensFRX::default_options());
require_once(SENSFRX_DIR . 'sensfrx-php-sdk/SensFRX/autoload.php');
$obj = new SensFRX\SensFRX([
	'property_id' => $options['sensfrx_property_id'],
	'property_secret' => $options['sensfrx_property_secret']
]);
$secretKey = $options['sensfrx_property_secret']; // Property ID
$propertyId = $options['sensfrx_property_id'];    // Secret Key
$URL = $site_title . '/wp-admin/admin.php?page=sensfrx_integration';
$notification_shadow = false;
$notification_shadow1 = false;
$shodow_display = false;
$shodow_display1 = false;
if ( isset($_POST['webhook_update_button']) && isset($_POST['webhook_update_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['webhook_update_nonce']), 'webhook_update_action') ) {
	if (!empty($propertyId) && !empty($secretKey)) {
		$nonce = wp_create_nonce('sensfrx_webhook_nonce');
		$webHookUrl = home_url() . '/wp-json/sensfrx-fraud-prevention-for-woocommerce/sensfrx_webhook';
		// $postData = json_encode(["url"=>$webHookUrl]);
		$webhook_update =  $obj->addWebHook($webHookUrl);
		if (!empty($webhook_update) && is_string($webhook_update)) {
			echo '<div id="myAlert" class="sensfrx_myAlert_notification_success">
						Webhook Updated Successfully
					</div>';
		} else if (!empty($webhook_update) && is_array($webhook_update)) {
			echo '<div id="myAlert" class="sensfrx_myAlert_notification_success">
						Webhook Updated Successfully
					</div>';
		} else {
			echo '<div id="myAlert" class="sensfrx_myAlert_notification_failed">
						Webhook Not Updated Successfully
					</div>';
		}
	}
}
if ( isset($_POST['shodow_mode_button']) && isset($_POST['sensfrx_shadow_mode_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_shadow_mode_nonce']), 'sensfrx_shadow_mode_action') ) {
	$shadow_value = isset($_POST['shadow']) ? sanitize_text_field($_POST['shadow']) : sanitize_text_field('');
	$postDataArray = array(
		'shadow_mode' => $shadow_value, 
	); 
	if (!empty($propertyId) && !empty($secretKey)) {
		$res_shadow_post = $obj->postshadowinfo($postDataArray);
		// $type = gettype($res_shadow_post);
		if (is_array($res_shadow_post)) {
			$res_shadow_post = json_decode($res_shadow_post[0]);
			$notification_shadow = false;
			$notification_shadow1 = false;
			if ('success' === $res_shadow_post->status) {
				$notification_shadow = true;
			}
			if ('success' != $res_shadow_post->status) {
				$notification_shadow1 = true;
			}
		} elseif (is_string($res_shadow_post)) {
			$res_shadow_post = json_decode($res_shadow_post);
			$notification_shadow = false;
			$notification_shadow1 = false;
			if ('success' === $res_shadow_post->status) {
				$notification_shadow = true;
			}
			if ('success' != $res_shadow_post->status) {
				$notification_shadow1 = true;
			}
		} else {
			$notification_shadow = false;
			$notification_shadow1 = true;
		}  
	}
}
?>

<form id="form-3" action="#policies" method="post">
	<?php wp_nonce_field('sensfrx_shadow_mode_action', 'sensfrx_shadow_mode_nonce'); ?>
	<input type="hidden" name="active_tab" value="tab-3">
	<input type="hidden" name="input_1_tab3" placeholder="Input 1">
	<input type="hidden" name="input_2_tab3" placeholder="Input 2">
	<input type="hidden" name="shadow" id="shadow" placeholder="Input 2">
	<input type="submit" id="Policy" value="Submit" name="shodow_mode_button" class="sensfrx_hide_button">
</form> 
<form id="form-3" action="#policies" method="post">
	<?php wp_nonce_field('webhook_update_action', 'webhook_update_nonce'); ?>
	<input type="submit" id="webhook_update" value="Submit" name="webhook_update_button" class="sensfrx_hide_button">
</form>
<?php 
$propertyId = $options['sensfrx_property_id'];
$secretKey = $options['sensfrx_property_secret'];
if (!empty($propertyId) && !empty($secretKey)) {
	$res_shadow = $obj->getshadowinfo();
	if (is_array($res_shadow)) {
		$shodow_display = false;
		$shodow_display1 = false;
		if ('success' === $res_shadow['status']) {
			$shodow_display = true;
		} else {
			$shodow_display1 = true;
		}
	} else {
		$shodow_display = false;
		$shodow_display1 = true;
	}  
} else {
	$shodow_display1 = true;
}
?>
<div class="wrap">
	<!-- <h1><?php echo esc_html(SENSFRX_NAME); ?> <small><?php echo esc_html('v' . SENSFRX_VERSION); ?></small></h1> -->
	<!-- <div class="ats-toggle-all"><a href="<?php echo esc_html(SENSFRX_PATH); ?>"><?php esc_html_e('Toggle all panels', 'sensfrx_fpwoo'); ?></a></div> -->
<?php 
if (true === $notification_shadow) {
	?>
	<div id="myAlert" class="sensfrx_myAlert_notification_success">
		Policy Data Updated Successfully...
	</div>
	<?php 
}
if (true === $notification_shadow1) {
	?>
	<div id="myAlert" class="sensfrx_myAlert_notification_failed">
		Policy Data Not Updated Successfully...
	</div>
	<?php 
}
if (true === $shodow_display1) {
	?>
	<div class="sensfrx_form-container">
		<div class="sensfrx_form-container-auth">
			<h4><span class="sensfrx_form-container-auth-font">⚠️</span> Sorry, there's an issue with the <b>Authentication</b>.Please check <b><a href="<?php echo esc_url($URL); ?>">Property ID</a></b> & <b><a href="<?php echo esc_url($URL); ?>">Secret Key</a></b>.</h4>
			<p>If the problem persists, please check the property ID and ensure that all required information is correctly provided. For further assistance, contact our support team at <a href="mailto:info@sensfrx.ai">info@Sensfrx.ai</a>.</p>
		</div>
	</div>
	<?php
}
if (true === $shodow_display) {
	?>
	<div class="sensfrx_policy_settings-container">
		<h2 class="sensfrx_policy_tab_heading">Policy Setting :</h2>
		<div class="sensfrx-button-container">
			<button id="button1" class="sensfrx_policy_tab_btn active">Account Security Policies</button>
			<button id="button2" class="sensfrx_policy_tab_btn">Transaction Security Policies</button>
			<button id="button4" class="sensfrx_policy_tab_btn active">New Account Security Policies</button>
			<button id="button3" class="sensfrx_policy_tab_btn">Webhook Security Policies</button>
		</div>
		<form id="myForm" method="post" action="options.php">
			<?php settings_fields('sensfrx_plugin_policy_options'); ?>
			<div class="metabox-holder">
				<div id="sensfrxPortletSettings" class="postbox">	
					<div class="ats-portlet toggle<?php echo !isset($_GET['settings-updated']) ? ' default-hidden' : ''; ?>">
						<div id="div1">
							<h3 class="ats-portlet-title" id="sensfrx_policy_ato_heading"><?php esc_html_e('Account Security Policies Settings', 'sensfrx_fpwoo'); ?></h3>
							<table id="table1" class="">
								<tr>
									<th id="sensfrx_policy_th_apply" class="sensfrx_policy_ato_allow">Below you can set the sensfrx incident policies. These policies are defined to help the application understand on how to handle sensfrx responses.</th>
								</tr>
								<tr>
									<th colspan="2" class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply">1. Allow</th>
								</tr>
								<tr>
									<td id="sensfrx_policy_th_apply" class="sensfrx_policy_ato_allow_heading"><em>sensfrx responds with Allow when the risk score is medium (Low/<b>Medium</b>/High/Critical). Below you can set whether you want sensfrx to send device approval email when the risk score is observed to be medium.</em></td>
								</tr>
								<tr>
									<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><input id="sensfrx_policy_options[sensfrx_medium_email]" name="sensfrx_policy_options[sensfrx_medium_email]" type="checkbox" value="1" <?php echo ( isset($sensfrx_policy_options['sensfrx_medium_email']) && 1 == esc_attr($sensfrx_policy_options['sensfrx_medium_email']) ) ? 'checked="checked"' : ''; ?>>
										<label for="sensfrx_policy_options[sensfrx_medium_email]"><?php esc_html_e('Send Device Approval E-mail', 'sensfrx_fpwoo'); ?></label>
									</td>
								</tr>
								<tr>
									<th colspan="2" class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply">2. Challenge</th>
								</tr>
								<tr>
									<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><em>sensfrx responds with Challenge when the risk score is high (Low/Medium/<b>High</b>/Critical). Below you can set whether you want sensfrx to send password reset email when the risk score is observed to be high.</em></td>
								</tr>
								<tr>
									<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><input id="sensfrx_policy_options[sensfrx_challenge_email]" name="sensfrx_policy_options[sensfrx_challenge_email]" type="checkbox" value="1" <?php echo ( isset($sensfrx_policy_options['sensfrx_challenge_email']) && 1 == esc_attr($sensfrx_policy_options['sensfrx_challenge_email']) ) ? 'checked="checked"' : ''; ?>>
										<label for="sensfrx_policy_options[sensfrx_challenge_email]"><?php esc_html_e('Send Password Reset E-mail', 'sensfrx_fpwoo'); ?></label>
									</td>
								</tr>
								<tr>
									<th colspan="2" class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply">3. Deny</th>
								</tr>
								<tr>
									<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><em>sensfrx responds with Deny when the risk score is high (Low/Medium/High/<b>Critical</b>). Below you can set whether you want sensfrx to send password reset email when the risk score is observed to be high.</em></td>
								</tr>
								<tr>
									<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><input id="sensfrx_policy_options[sensfrx_deny_email]" name="sensfrx_policy_options[sensfrx_deny_email]" type="checkbox" value="1" <?php echo ( isset($sensfrx_policy_options['sensfrx_deny_email']) && 1 == esc_attr($sensfrx_policy_options['sensfrx_deny_email']) ) ? 'checked="checked"' : ''; ?>>
										<label for="sensfrx_policy_options[sensfrx_deny_email]"><?php esc_html_e('Send Password Reset E-mail', 'sensfrx_fpwoo'); ?></label>
									</td>
								</tr>
							</table>
						</div>
						<!-- --------------------------------- -->
						<div id="div2" class="sensfrx_hide_button">
							<h3 class="ats-portlet-title" id="sensfrx_policy_ato_heading"><?php esc_html_e('Transaction Policies Settings', 'sensfrx_fpwoo'); ?></h3>
							<table id="table2" class="">
								<tr>
									<th class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply">Below you can set the sensfrx incident policies. These policies are defined to help the application understand on how to handle sensfrx responses.</th>
								</tr>
								<tr>
									<th colspan="2" class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply">1. Transaction Allow</th>
								</tr>
								<tr>
									<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><em>sensfrx responds with Allow when the risk score is medium (Low/<b>Medium</b>/High/Critical). Below you can set whether you want sensfrx to send device approval email when the risk score is observed to be medium.</em></td>
								</tr>
								<tr>
									<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><input id="sensfrx_policy_options[sensfrx_allow_payment_email]" name="sensfrx_policy_options[sensfrx_allow_payment_email]" type="checkbox" value="1" <?php echo ( isset($sensfrx_policy_options['sensfrx_allow_payment_email']) && 1 == esc_attr($sensfrx_policy_options['sensfrx_allow_payment_email']) ) ? 'checked="checked"' : ''; ?>>
										<label for="sensfrx_policy_options[sensfrx_allow_payment_email]"><?php esc_html_e('Send Transaction Unusual Activity E-mail', 'sensfrx_fpwoo'); ?></label>
									</td>
								</tr>
								<!-- --------------------------------  -->
								<tr>
									<th colspan="2" class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply">2. Transaction Challenge</th>
								</tr>
								<tr>
									<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><em>sensfrx responds with Challenge when the risk score is (Low/Medium/<b>High</b>/Critical.) Below you can set whether you want sensfrx to send device approval email when the risk score is observed to be medium.</em></td>
								</tr>
								<tr>
									<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><input id="sensfrx_policy_options[sensfrx_challenge_payment_email]" name="sensfrx_policy_options[sensfrx_challenge_payment_email]" type="checkbox" value="1" <?php echo ( isset($sensfrx_policy_options['sensfrx_challenge_payment_email']) && 1 == esc_attr($sensfrx_policy_options['sensfrx_challenge_payment_email']) ) ? 'checked="checked"' : ''; ?>>
										<label for="sensfrx_policy_options[sensfrx_challenge_payment_email]"><?php esc_html_e('Send Transaction Challenge Activity E-mail', 'sensfrx_fpwoo'); ?></label>
									</td>
								</tr>
								<!-- ----------------------------- -->
								<tr>
									<th colspan="2" class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply">3. Transaction Deny</th>
								</tr>
								<tr>
									<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><em>sensfrx responds with Deny when the risk score is Critical (Low/Medium/High/<b>Critical</b>). Below you can set whether you want sensfrx to send password reset email when the risk score is observed to be high.</em></td>
								</tr>
								<tr>
									<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><input id="sensfrx_policy_options[sensfrx_deny_payment_email]" name="sensfrx_policy_options[sensfrx_deny_payment_email]" type="checkbox" value="1" <?php echo ( isset($sensfrx_policy_options['sensfrx_deny_payment_email']) && 1 == esc_attr($sensfrx_policy_options['sensfrx_deny_payment_email']) ) ? 'checked="checked"' : ''; ?>>
										<label for="sensfrx_policy_options['sensfrx_deny_payment_email]"><?php esc_html_e('Send Transaction Suspicious Activity E-mail', 'sensfrx_fpwoo'); ?></label>
									</td>
								</tr>
							</table>
						</div>
						<!-- ------------------------------ -->
						<div id="div4" class="sensfrx_hide_button">
							<h3 class="ats-portlet-title" id="sensfrx_policy_ato_heading"><?php esc_html_e('New Account Policies Settings', 'sensfrx_fpwoo'); ?></h3>
							<table id="table2" class="">
								<tr>
									<th class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply">Below you can set the sensfrx incident policies. These policies are defined to help the application understand on how to handle sensfrx responses.</th>
								</tr>
								<tr>
									<th colspan="2" class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply">1. New Account Allow</th>
								</tr>
								<tr>
									<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><em>sensfrx responds with Allow when the risk score is medium (Low/<b>Medium</b>/High/Critical). Below you can set whether you want sensfrx to send device approval email when the risk score is observed to be medium.</em></td>
								</tr>
								<tr>
									<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><input id="sensfrx_policy_options[sensfrx_allow_register_email]" name="sensfrx_policy_options[sensfrx_allow_register_email]" type="checkbox" value="1" <?php echo ( isset($sensfrx_policy_options['sensfrx_allow_register_email']) && 1 == esc_attr($sensfrx_policy_options['sensfrx_allow_register_email']) ) ? 'checked="checked"' : ''; ?>>
										<label for="sensfrx_policy_options[sensfrx_allow_register_email]"><?php esc_html_e('Send New Account Unusual Activity E-mail', 'sensfrx_fpwoo'); ?></label>
									</td>
								</tr>
								<!-- White List Checkbox -->
								<input type="hidden" id="sensfrx_policy_options[sensfrx_whitle_list_email]" name="sensfrx_policy_options[sensfrx_whitle_list_email]" type="checkbox" value="1" <?php echo ( isset($sensfrx_policy_options['sensfrx_whitle_list_email']) && 1 == esc_attr($sensfrx_policy_options['sensfrx_whitle_list_email']) ) ? 'checked="checked"' : ''; ?>>
								<!-- --------------------------------  -->
								<tr>
									<th colspan="2" class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply">2. New Account Challenge</th>
								</tr>
								<tr>
									<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><em>sensfrx responds with Challenge when the risk score is (Low/Medium/<b>High</b>/Critical.) Below you can set whether you want sensfrx to send device approval email when the risk score is observed to be medium.</em></td>
								</tr>
								<tr>
									<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><input id="sensfrx_policy_options[sensfrx_challenge_register_email]" name="sensfrx_policy_options[sensfrx_challenge_register_email]" type="checkbox" value="1" <?php echo ( isset($sensfrx_policy_options['sensfrx_challenge_register_email']) && 1 == esc_attr($sensfrx_policy_options['sensfrx_challenge_register_email']) ) ? 'checked="checked"' : ''; ?>>
										<label for="sensfrx_policy_options[sensfrx_challenge_register_email]"><?php esc_html_e('Send New Account Challenge Activity E-mail', 'sensfrx_fpwoo'); ?></label>
									</td>
								</tr>
								<!-- ----------------------------- -->
								<tr>
									<th colspan="2" class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply">3. New Account Deny</th>
								</tr>
								<tr>
									<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><em>sensfrx responds with Deny when the risk score is Critical (Low/Medium/High/<b>Critical</b>). Below you can set whether you want sensfrx to send password reset email when the risk score is observed to be high.</em></td>
								</tr>
								<tr>
									<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><input id="sensfrx_policy_options[sensfrx_deny_register_email]" name="sensfrx_policy_options[sensfrx_deny_register_email]" type="checkbox" value="1" <?php echo ( isset($sensfrx_policy_options['sensfrx_deny_register_email']) && 1 == esc_attr($sensfrx_policy_options['sensfrx_deny_register_email']) ) ? 'checked="checked"' : ''; ?>>
										<label for="sensfrx_policy_options['sensfrx_deny_register_email]"><?php esc_html_e('Send New Account Suspicious Activity E-mail', 'sensfrx_fpwoo'); ?></label>
									</td>
								</tr>
							</table>
						</div>
						<!-- ------------------------------ -->
						<div id="div3" class="sensfrx_hide_button">
							<!-- Div to display the statement -->
							<form method="post" action="#policies">
								<div id="webhookStatement">
									<h3 class="ats-portlet-title" id="sensfrx_policy_ato_heading"><?php esc_html_e('Webhook Security Policies', 'sensfrx_fpwoo'); ?></h3>
									<p class="sensfrx_webhook_information">A webhook is a communication method allowing applications to share real-time data by triggering events and instantly notifying other systems. For instance, imagine a security information and event management (SIEM) system deployed by SensFRX. When the SIEM system detects unusual network activity or a potential security breach, it can trigger a webhook to promptly notify the designated security personnel or an incident response platform. This immediate communication enables swift investigation and mitigation efforts, allowing sensfrx teams to respond in real-time to emerging threats and enhance the overall resilience of the organization's digital infrastructure.</p>
								</div>
								<!-- <button name="webhookupdate" onclick="webhook_rules()" class="sensfrx_policy_save-button" id="sensfrx_policy_webhook_update">Update</button>                                                                                            -->
								<br>
								<div id="sensfrx_policy_th_apply_webhook">
									<td class="" id=""><input id="sensfrx_policy_options[sensfrx_webhook_allow]" name="sensfrx_policy_options[sensfrx_webhook_allow]" type="checkbox" value="1" <?php echo ( isset($sensfrx_policy_options['sensfrx_webhook_allow']) && 1 == esc_attr($sensfrx_policy_options['sensfrx_webhook_allow']) ) ? 'checked="checked"' : ''; ?>>
										<label for="sensfrx_policy_options[sensfrx_webhook_allow]"><?php esc_html_e('Webhook Consent', 'sensfrx_fpwoo'); ?></label>
									</td>
								</div>
							</form>
						</div>
						<!-- ------------------------------- -->
						<table  class="" id="webhookupdatehide">
							<tr>
								<td class="sensfrx_policy_ato_allow_heading"><hr></td>
							</tr>
							<tr>
								<th colspan="2" class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><strong>Shadow Mode</strong></th>
							</tr>
							<tr>
								<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply"><em>sensfrx will not take any action against users regardless of their risk.</em></td>
							</tr>
							<tr>
								<td class="sensfrx_policy_ato_allow_heading" id="sensfrx_policy_th_apply">
									<?php 
									if (true == $shodow_display) {
										?>
										<input id="sensfrx_policy_options[sensfrx_shadow_mode]" name="sensfrx_policy_options[sensfrx_shadow_mode]" type="checkbox" value="1" <?php echo ( is_array($res_shadow) && isset($res_shadow['shadow_mode']) && 1 == $res_shadow['shadow_mode'] ) ? 'checked' : ''; ?>>
										<label for="sensfrx_policy_options[sensfrx_shadow_mode]"><?php esc_html_e('Enable Shadow Mode', 'sensfrx_fpwoo'); ?></label>
										<?php 
									} else {
										?>
										<input id="sensfrx_policy_options[sensfrx_shadow_mode]" name="sensfrx_policy_options[sensfrx_shadow_mode]" type="checkbox" value="1" >
										<label for="sensfrx_policy_options[sensfrx_shadow_mode]"><?php esc_html_e('Shadow Mode API is not working please connect with Sensfrx.ai', 'sensfrx_fpwoo'); ?></label>
										<?php 
									}
									?>
								</td>
							</tr>
							<tr>
								<td class="sensfrx_policy_ato_allow_heading">
									<input type="submit" name="shodow_mode_button" class="sensfrx_policy_save-button" value="<?php esc_attr_e('Save Changes', 'sensfrx_fpwoo'); ?>" onclick="getClickedValue(this)" />
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</form>
	</div>
	<?php 
}
?>
</div>
