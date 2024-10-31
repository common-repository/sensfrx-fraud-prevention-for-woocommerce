<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://sensfrx.ai
 * @since      1.0.0
 *
 * @package    SensFRX
 * @subpackage SensFRX/admin/partials
 */
// This file should primarily consist of HTML with a little bit of PHP.
// Google Analytics - Settings Display
if (!function_exists('add_action')) {
	die(); 
} 
if (!defined('ABSPATH')) {
	die('ASPATH is required - Privacy PAGE');
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
$PrivaryException = false;
$PrivaryException1 = false;
$PrivaryException2 = false;
$notification_Privacy = false;
$notification_Privacy1 = false;
if ( isset($_POST['privacy_submit']) && isset($_POST['sensfrx_privacy_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_privacy_nonce']), 'sensfrx_privacy_action') ) {
	$emailAlert = isset($_POST['emailprivacy']) ? sanitize_text_field($_POST['emailprivacy']) : null;
	$checkALert = isset($_POST['checkboxprivacy']) ? sanitize_text_field($_POST['checkboxprivacy']) : null;
	if (!empty($propertyId) && !empty($secretKey)) {
		$postDataArray = [
			'privacy_email' => $emailAlert,
			'privacy_consent' => $checkALert,
		];
		$resultPrivacy = $obj->postprivacyinfo($postDataArray);
		if (is_array($resultPrivacy)) {
			if ('success' == $resultPrivacy['status']) {
				$notification_Privacy = true;
			}
			if ('success' != $resultPrivacy['status']) {
				$notification_Privacy1 = true;
			}
		} else {
			$notification_Privacy = false;
			$notification_Privacy1 = true;
		}
	}
}
$resPrivacy = $obj->getprivacyinfo();
if (is_array($resPrivacy)) {
	if ('success' === $resPrivacy['status']) {
		$PrivaryException = true;
		$Privacy_data = $resPrivacy['data'];
		$Privacy_data = (array) $Privacy_data;
		if ('1' == $Privacy_data['privacy_consent']) {
			$Privacy_data['privacy_consent'] = 'on';
		} else {
			$Privacy_data['privacy_consent'] = 'off';
		}
	}
	if ('success' != $resPrivacy['status']) {
		$PrivaryException1 = true;
	}
} else if (is_string($resPrivacy)) {
	$PrivaryException = false;
	$PrivaryException1 = false;
	$PrivaryException2 = true;
} else {
	$PrivaryException = false;
	$PrivaryException1 = true;
	$PrivaryException2 = false;
}
if (true === $notification_Privacy) {
	?>
	<div class="sensfrx_myAlert_notification_success" id="myAlert">
		Privacy Data Updated Successfully...
	</div>
	<?php 
}
if (true === $notification_Privacy1) {
	
	echo '<div class="sensfrx_myAlert_notification_failed" id="myAlert">
		Privacy Data Not Updated Successfully...
	</div>';
	
}
if (true === $PrivaryException2) {
	?>
	<div class="sensfrx_form-container">
		<div class="sensfrx_form-container-auth">
			<h4><span class="sensfrx_form-container-auth-font">⚠️</span> Sorry, there's an issue with the <b>Authentication</b>.Please check <b><a href="<?php echo esc_url($URL); ?>">Property ID</a></b> & <b><a href="<?php echo esc_url($URL); ?>">Secret Key</a></b>.</h4>
			<p>If the problem persists, please check the property ID and ensure that all required information is correctly provided. For further assistance, contact our support team at <a href="mailto:info@sensfrx.ai">info@Sensfrx.ai</a>.</p>
		</div>
	</div>
	<?php
}
if (true === $PrivaryException1) {
	?>
	<div class="sensfrx_form-container">
		<div class="sensfrx_form-container-auth">
			<h4><span class="sensfrx_form-container-auth-font">⚠️</span> Sorry, there's an issue with the API. Please try again later.</h4>
			<p>If the problem persists, please check the <b><a href="<?php echo esc_url($URL); ?>">Property ID</a></b> & <b><a href="<?php echo esc_url($URL); ?>">Secret Key</a> and ensure that all required information is correctly provided. For further assistance, contact our support team at <a href="mailto:info@sensfrx.ai">info@Sensfrx.ai</a>.</p>
		</div>
	</div>
	<?php
}
if (true === $PrivaryException) {
	?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sensfrx Privacy</title>
	<form id="form-8" action="#account-privacy" method="post">
		<?php wp_nonce_field('sensfrx_privacy_action', 'sensfrx_privacy_nonce'); ?>
		<input type="hidden" name="active_tab" value="tab-8">
		<input type="hidden" name="input_1_tab3" placeholder="Input 1">
		<input type="hidden" name="input_2_tab3" placeholder="Input 2">
		<input type="hidden" name="emailprivacy" id="emailprivacy" >
		<input type="hidden" name="checkboxprivacy" id="checkboxprivacy" >
		<input type="submit" name="privacy_submit" id="Privacy" value="Submit" class="sensfrx_hide_button">
	</form>
	<form action="#" method="post">
		<div class="sensfrx-settings-container-account-privacy">
			<h2 class="sensfrx_tab_heading">Account & Privacy :</h2>
			<label class="sensfrx_email_label_privacy_page" for="email">Email:</label>
			<input class="sensfrx_privacy_email_input" type="email" id="privacy_email" name="email" placeholder="Enter your email" value="<?php echo esc_html($Privacy_data['privacy_email']); ?>" required>
			<h3>Data Protection and Sharing :</h3>
			<p>Your data is securely stored and protected from unauthorized access.<br>We do not share your data with any third parties.</p>
			<h3>Compliance :</h3>
			<p>We are committed to complying with all relevant privacy regulations, including IT ACT, 2000 Compliance.</p>
			<h3>User Consent :</h3>
			<p>By using our fraud detection product, you give consent to the data collation. If you want to<br>withdraw your consent and delete your data, please contact us on info.sensfrx.ai</p>
			<label>
				<input type="checkbox" id="declaration" name="declaration" <?php echo ( 'on' == $Privacy_data['privacy_consent'] ) ? 'checked' : ''; ?> required>
				<span class="sensfrx_terms-label">I agree to the Terms and Conditions</span>
			</label>
			<p class="sensfrx_privacy_error-message">Please accept the terms and conditions.</p>
			<p class="sensfrx_privacy_statement">Please enter your email and check the box to agree to the terms and conditions.</p>
			<button class="sensfrx_privacy_save_button" type="submit"  onclick="getClickedValuePrivacy(this)">Save Changes</button>
		</div>
	</form>
	<?php
}
