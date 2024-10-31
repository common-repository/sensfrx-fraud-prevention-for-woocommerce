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
	die('ASPATH is required - Notification PAGE');
}
$options = get_option('sensfrx_options', SensFRX::default_options());
require_once(SENSFRX_DIR . 'sensfrx-php-sdk/SensFRX/autoload.php');
$obj = new SensFRX\SensFRX([
	'property_id' => $options['sensfrx_property_id'],
	'property_secret' => $options['sensfrx_property_secret']
]);
$secretKey = $options['sensfrx_property_secret']; // Property ID
$propertyId = $options['sensfrx_property_id'];    // Secret Key
$site_title = home_url();
$URL = $site_title . '/wp-admin/admin.php?page=sensfrx_integration';
$notification_Notification = false;
$notification_Notification1 = false;
if ( isset($_POST['sumbitalert']) && isset($_POST['sensfrx_notify_sumbitalert_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_notify_sumbitalert_nonce']), 'sensfrx_notify_sumbitalert_action') ) {
	$emailAlert1 = isset($_POST['emailalert']) ? sanitize_text_field($_POST['emailalert']) : null;
	$emailAlert = sanitize_email($emailAlert1);
	$checkAlert = isset($_POST['checkboxalert']) ? sanitize_text_field($_POST['checkboxalert']) : null;
	$dropAlert = isset($_POST['dropdalert']) ? sanitize_text_field($_POST['dropdalert']) : null;
	if ( !empty($propertyId) && !empty($secretKey) ) {
		$postDataArray = [
			'enabled' => $checkAlert,
			'risk_threshold' => $dropAlert,
			'email' => $emailAlert,
		];
		$resultAlert = $obj->postalertsinfo($postDataArray);
		if (is_array($resultAlert)) {
			if ('success' == $resultAlert['status']) {
				$notification_Notification = true;
			}
			if ('success' != $resultAlert['status']) {
				$notification_Notification1 = true;
			}
		} else {
			$notification_Notification = false;
			$notification_Notification1 = true;
		}
	}
}
$resAlert = $obj-> getalertsinfo();
$resAlert = (array) $resAlert;
if (is_array($resAlert)) {
	$AlertException = false;
	$AlertException1 = false;
	$AlertException2 = false;
	if ('success' === $resAlert['status']) {
		$AlertException = true;
		$AlerttArray = $resAlert['data'];
		$AlerttArray = (array) $AlerttArray;
		if ('1' == $AlerttArray['enabled']) {
			$AlerttArray['enabled'] = 'on';
		} else {
			$AlerttArray['enabled'] = 'off';
		}
	}
	if ('success' != $resAlert['status']) {
		$AlertException1 = true;
	}  
} elseif (is_string($resAlert)) {
	$AlertException = false;
	$AlertException1 = false;
	$AlertException2 = true;
} else {
	$AlertException = false;
	$AlertException1 = true;
	$AlertException2 = false;
} 
if (true === $notification_Notification) {
	?>
	<div class="sensfrx_myAlert_notification_success" id="myAlert">
		Notification & Alert Data Updated Successfully...
	</div>
	<?php 
}
if (true === $notification_Notification1) {
	?>
	<div class="sensfrx_myAlert_notification_failed" id="myAlert">
		Notification & Alert Data Not Updated Successfully...
	</div>
	<?php 
}
if (true === $AlertException2) {
	?>
	<div class="sensfrx_form-container">
		<div class="sensfrx_form-container-auth">
			<h4><span class="sensfrx_form-container-auth-font">⚠️</span> Sorry, there's an issue with the <b>Authentication</b>.Please check <b><a href="<?php echo esc_url($URL); ?>">Property ID</a></b> & <b><a href="<?php echo esc_url($URL); ?>">Secret Key</a></b>.</h4>
			<p>If the problem persists, please check the property ID and ensure that all required information is correctly provided. For further assistance, contact our support team at <a href="mailto:info@sensfrx.ai">info@Sensfrx.ai</a>.</p>
		</div>
	</div>
	<?php
}
if (true === $AlertException1) {
	?>
	<div class="sensfrx_form-container">
		<div class="sensfrx_form-container-auth">
			<h4><span class="sensfrx_form-container-auth-font">⚠️</span> Sorry, there's an issue with the API. Please try again later.</h4>
			<p>If the problem persists, please check the <b><a href="<?php echo esc_url($URL); ?>">Property ID</a></b> & <b><a href="<?php echo esc_url($URL); ?>">Secret Key</a> and ensure that all required information is correctly provided. For further assistance, contact our support team at <a href="mailto:info@sensfrx.ai">info@Sensfrx.ai</a>.</p>
		</div>
	</div>
	<?php
}
if (true === $AlertException) {
	?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<div class="sensfrx_notification_settings-container">
		<form id="form-4" action="#notification-alert" method="post">
			<?php wp_nonce_field('sensfrx_notify_sumbitalert_action', 'sensfrx_notify_sumbitalert_nonce'); ?>
			<input type="hidden" name="active_tab" value="tab-4">
			<input type="hidden" name="input_1_tab3" placeholder="Input 1">
			<input type="hidden" name="input_2_tab3" placeholder="Input 2">
			<input type="hidden" name="emailalert" id="hiddeneamil" >
			<input type="hidden" name="checkboxalert" id="hiddencheckbox" >
			<input type="hidden" name="dropdalert" id="hiddendropdown" >
			<input type="submit" name="sumbitalert" id="Notification" value="Submit" class="sensfrx_hide_button">
		</form id="mainForm" action="" method="post">
		<h2 class="sensfrx_tab_heading">Notification Alert :</h2>
		<form>
			<label for="statement1"></label>
			<p>Configure how to get notified when a login exceeds the specified risk threshold. You can also let your users resolve these alerts by activating Security Notifications.</p>
			<br>
			<label for="emailCheckboxx">Check this box &nbsp;
			<input class="sensfrx_notification_input" type="checkbox" id="emailCheckboxx" name="emailCheckbox" <?php echo ( 'on' == $AlerttArray['enabled'] ) ? 'checked' : ''; ?>>
			</label>
			<label for="statement2"></label>
			<p>Emails will be triggered when the device score reaches the selected threshold.</p>
			<select class="sensfrx_notification_select" id="threshold" name="threshold">
				<option value="30" <?php echo ( '30' == $AlerttArray['risk_threshold'] ) ? 'selected' : ''; ?>>
					Suspicious ( Risk Score : 30 )
				</option>
				<option value="40" <?php echo ( '40' == $AlerttArray['risk_threshold'] ) ? 'selected' : ''; ?>>
					Suspicious ( Risk Score : 40 )
				</option>
				<option value="50" <?php echo ( '50' == $AlerttArray['risk_threshold'] ) ? 'selected' : ''; ?>>
					Suspicious ( Risk Score : 50 )
				</option>
				<option value="60" <?php echo ( '60' == $AlerttArray['risk_threshold'] ) ? 'selected' : ''; ?>>
					Suspicious ( Risk Score : 60 )
				</option>
				<option value="70" <?php echo ( '70' == $AlerttArray['risk_threshold'] ) ? 'selected' : ''; ?>>
					Suspicious ( Risk Score : 70 )
				</option>
				<option value="80" <?php echo ( '80' == $AlerttArray['risk_threshold'] ) ? 'selected' : ''; ?>>
					Suspicious ( Risk Score : 80 )
				</option>
				<option value="90" <?php echo ( '90' == $AlerttArray['risk_threshold'] ) ? 'selected' : ''; ?>>
					Compromised ( Risk Score : 90 )
				</option>
				<option value="100" <?php echo ( '100' == $AlerttArray['risk_threshold'] ) ? 'selected' : ''; ?>>
					Compromised ( Risk Score : 100 )
				</option>
			</select>
			<br>
			<input class="sensfrx_notification_email_input_width" type="email" id="emailInput22" name="emailInput" placeholder="Email Address" value="<?php echo esc_html($AlerttArray['email']); ?>" required>
			<br>
			<button id="sensfrx_notification_save_button" type="submit" onclick="getClickedValueNotification(this)" class="save-button">Save Chenge</button>
		</form>
	</div>
	<?php 
} 
