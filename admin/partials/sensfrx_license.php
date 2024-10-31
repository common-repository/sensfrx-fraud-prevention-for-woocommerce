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
	die('ASPATH is required - License PAGE');
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
$licenseData = array();
$License_Exception = false;
$License_Exception1 = false;
$License_Exception2 = false;
$res_license = $obj->getlicenseinfo();        // Get Validation Rules Data
if (is_array($res_license)) {
	$License_Exception = false;
	$License_Exception1 = false;
	$License_Exception2 = false;
	if ('success' === $res_license['status']) {
		$License_Exception = true;
		$licenseData = $res_license['data'];
		$licenseData = (array) $licenseData;
		$originalTimestamp = $licenseData['start_date'];
		$modifiedTimestamp = str_replace('T', ' ', $originalTimestamp);
	}
	if ('success' != $res_license['status']) {
		$License_Exception1 = true;
	}   
} elseif (is_string($res_license)) {
	$License_Exception = false;
	$License_Exception1 = false;
	$License_Exception2 = true;
} else {
	$License_Exception = false;
	$License_Exception1 = true;
	$License_Exception2 = false;
}
if (true === $License_Exception2) {
	?>

	<div class="form-container">
		<div class="sensfrx_form-container">
			<div class="sensfrx_form-container-auth">
				<h4><span class="sensfrx_form-container-auth-font">⚠️</span> Sorry, there's an issue with the <b>Authentication</b>.Please check <b><a href="<?php echo esc_url($URL); ?>">Property ID</a></b> & <b><a href="<?php echo esc_url($URL); ?>">Secret Key</a></b>.</h4>
				<p>If the problem persists, please check the property ID and ensure that all required information is correctly provided. For further assistance, contact our support team at <a href="mailto:info@sensfrx.ai">info@Sensfrx.ai</a>.</p>
			</div>
		</div>
	</div>
	<?php
}
if (true === $License_Exception1) {
	?>
	<div class="form-container">
		<div class="sensfrx_form-container">
			<div class="sensfrx_form-container-auth">
				<h4><span class="sensfrx_form-container-auth-font">⚠️</span> Sorry, there's an issue with the API. Please try again later.</h4>
				<p>If the problem persists, please check the <b><a href="<?php echo esc_url($URL); ?>">Property ID</a></b> & <b><a href="<?php echo esc_url($URL); ?>">Secret Key</a> and ensure that all required information is correctly provided. For further assistance, contact our support team at <a href="mailto:info@sensfrx.ai">info@Sensfrx.ai</a>.</p>
			</div>
		</div>
	</div>
	<?php
}
if (true === $License_Exception) {
	?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<div class="sensfrx-subscription-container">
		<h2 class="sensfrx_tab_heading">License Information :</h2>
		<table class="sensfrx_license_table">
			<tr>
				<th>Plan Name</th>
				<td><?php echo esc_html($licenseData['plan']); ?></td>
			</tr>
			<tr>
				<th>Credit Available</th>
				<td><?php echo esc_html($licenseData['available_credit']); ?></td>
			</tr>
			<tr>
				<th>Start Date</th>
				<td><?php echo esc_html($modifiedTimestamp); ?></td>
			</tr>
			<tr>
				<th>Renewal Date</th>
				<td><?php echo esc_html($licenseData['renewal']); ?></td>
			</tr>
		</table>
		<p>Note: Subscription auto-renews unless canceled before the billing period ends. Need support? Contact us at <a href="mailto:info@sensfrx.ai">info@sensfrx.ai</a></p>
	</div>
	<?php
}
