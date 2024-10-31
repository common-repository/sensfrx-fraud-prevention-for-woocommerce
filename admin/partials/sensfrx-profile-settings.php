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
	die('ASPATH is required - Profile PAGE');
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
$ProfileException = false;
$ProfileException1 = false;
$ProfileException2 = false;
$notification_Profile = false;
$notification_Profile1 = false;
if ( isset($_POST['Submit_Profile_Form']) && isset($_POST['sensfrx_profile_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_profile_nonce']), 'sensfrx_profile_action') ) {
	$name1 = isset($_POST['namename']) ? sanitize_text_field($_POST['namename']) : null;
	$email1 = isset($_POST['emailemail']) ? sanitize_email($_POST['emailemail']) : null;
	$gender = isset($_POST['sexsex']) ? sanitize_text_field($_POST['sexsex']) : null;
	$brand_name1 = isset($_POST['bnamename']) ? sanitize_text_field($_POST['bnamename']) : null;
	$Org_name1 = isset($_POST['onamename']) ? sanitize_text_field($_POST['onamename']) : null;
	$brand_url1 = isset($_POST['burlurl']) ? esc_url_raw($_POST['burlurl']) : null;
	$phone1 = isset($_POST['phonephone']) ? sanitize_text_field($_POST['phonephone']) : null;
	$timezone = isset($_POST['timezone1']) ? sanitize_text_field($_POST['timezone1']) : null;
	// Sanitize all the input 
	$name = sanitize_text_field($name1);
	$email = sanitize_email($email1, FILTER_SANITIZE_EMAIL);
	$brand_name = sanitize_text_field($brand_name1);
	$Org_name = sanitize_text_field($Org_name1);
	$brand_url = sanitize_url($brand_url1);
	$phone = preg_replace('/[^0-9]/', '', $phone1);
	$parts = explode(' ', $name);
	$fname = $parts[0];
	$lname = $parts[1];
	if (!empty($propertyId) && !empty($secretKey)) {
		$postDataArray = array(
			'fname' => $fname,
			'lname' => $lname,
			'email' => $email,
			'phone' => $phone,
			'sex' => $gender,
			'timezone' => $timezone,
			'brand_name' => $brand_name,
			'brand_url' => $brand_url,
			'org_name' => $Org_name,
		);
		$resultProfile = $obj->postprofileinfo($postDataArray);
		if (is_array($resultProfile)) {
			if ('success' == $resultProfile['status']) {
				$notification_Profile = true;
			}
			if ('success' != $resultProfile['status']) {
				$notification_Profile1 = true;
			}
		} else {
			$notification_Profile = false;
			$notification_Profile1 = true;
		}
	}
}
$res_Profie = $obj->getprofileinfo();
if (is_array($res_Profie)) {
	if ('success' === $res_Profie['status']) {
		$ProfileException = true;
		$General_data = $res_Profie['data'];
		$General_data = (array) $General_data;
	}
	if ('success' != $res_Profie['status']) {
		$ProfileException1 = true;
	} 
} else if (is_string($res_Profie)) {
	$ProfileException = false;
	$ProfileException1 = false;
	$ProfileException2 = true;
} else {
	$ProfileException = false;
	$ProfileException1 = true;
	$ProfileException2 = false;
}
if (true === $notification_Profile) {
	?>
	<div class="sensfrx_myAlert_notification_success" id="myAlert">
		Profile Data Updated Successfully...
	</div>
	<?php 
} 
if (true === $notification_Profile1) {
	?>
	<div class="sensfrx_myAlert_notification_failed" id="myAlert">
		Profile Data Not Updated Successfully...
	</div>
	<?php 
}
if (true === $ProfileException1) {
	?>
	<div class="sensfrx_form-container">
		<div class="sensfrx_form-container-auth">
			<h4><span class="sensfrx_form-container-auth-font">⚠️</span> Sorry, there's an issue with the API. Please try again later.</h4>
			<p>If the problem persists, please check the <b><a href="<?php echo esc_url($URL); ?>">Property ID</a></b> & <b><a href="<?php echo esc_url($URL); ?>">Secret Key</a> and ensure that all required information is correctly provided. For further assistance, contact our support team at <a href="mailto:info@sensfrx.ai">info@Sensfrx.ai</a>.</p>
		</div>
	</div>
	<?php
}
if (true === $ProfileException2) {
	?>
	<div class="sensfrx_form-container">
		<div class="sensfrx_form-container-auth">
			<h4><span class="sensfrx_form-container-auth-font">⚠️</span> Sorry, there's an issue with the <b>Authentication</b>.Please check <b><a href="<?php echo esc_url($URL); ?>">Property ID</a></b> & <b><a href="<?php echo esc_url($URL); ?>">Secret Key</a></b>.</h4>
			<p>If the problem persists, please check the property ID and ensure that all required information is correctly provided. For further assistance, contact our support team at <a href="mailto:info@sensfrx.ai">info@Sensfrx.ai</a>.</p>
		</div>
	</div>
	<?php
}
if (true === $ProfileException) {
	?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<div class="sensfrx_profile_container">
		<h2 class="sensfrx_tab_heading">Profile Information :</h2>
		<table class="sensfrx_profile_table">
			<tr>
				<th>Name :</th>
				<td class="sensfrx_profile_table_input_bc"><b><?php echo esc_html($General_data['fname'] . ' ' . $General_data['lname']); ?></b></td>
			</tr>
			<tr>
				<th>Gender :</th>
				<td class="sensfrx_profile_table_input_bc"><b><?php echo esc_html( ( 'm' == $General_data['sex'] ) ) ? 'Male' : 'Female'; ?></b></td>
			</tr>
			<tr>
				<th>Email :</th>
				<td class="sensfrx_profile_table_input_bc"><b><?php echo esc_html($General_data['email']); ?></b></td>
			</tr>
			<tr>
				<th>Phone Number :</th>
				<td class="sensfrx_profile_table_input_bc"><b><?php echo esc_html($General_data['phone']); ?></b></td>
			</tr>
			<tr>
				<th>Timezone :</th>
				<td class="sensfrx_profile_table_input_bc"><b><?php echo esc_html($General_data['timezone']); ?></b></td>
			</tr>
			<tr>
				<th>Brand Name :</th>
				<td class="sensfrx_profile_table_input_bc"><b><?php echo esc_html($General_data['brand_name']); ?></b></td>
			</tr>
			<tr>
				<th>Brand URL :</th>
				<td class="sensfrx_profile_table_input_bc"><b><?php echo esc_html($General_data['brand_url']); ?></b></td>
			</tr>
			<tr>
				<th>Organization Name :</th>
				<td class="sensfrx_profile_table_input_bc"><b><?php echo esc_html($General_data['org_name']); ?></b></td>
			</tr>
		</table>
		<button class="sensfrx_profile_edit-button" id="editButton" onclick="openEditForm()">Update</button>
		<!-- Edit form (Initially hidden) -->
		<div class="edit-sensfrx_form-container">
			<br>
			<hr class="bold-line">
			<br>
			<form class="sensfrx_profile_edit-form" id="editForm" onsubmit="return Profile()">
				<h2 class="sensfrx_profile_update_info_heading">Update Information</h2>
				<div class="info-item">
					<label for="name">Name:</label>
					<input class="sensfrx_profile_edit_form_inputs" type="text" name="name" id="name" value="<?php echo esc_html($General_data['fname'] . ' ' . $General_data['lname']); ?>">
				</div>
				<div id="sensfrx_profile_gender_timezone" class="info-item">
					<label for="gender">Gender:</label>
					<input type="radio" name="sex" value="m" <?php echo ( 'm' == $General_data['sex'] ) ? 'checked' : ''; ?>> Male
					<input type="radio" name="sex" value="f" <?php echo ( 'f' == $General_data['sex'] ) ? 'checked' : ''; ?>> Female
				</div>
				<div class="info-item">
					<label for="email">Email:</label>
					<input class="sensfrx_profile_edit_form_inputs" type="email" name="email" id="emaile" value="<?php echo esc_html($General_data['email']); ?>">
				</div>
				<div class="info-item">
					<label for="name">Phone Number:</label>
					<input class="sensfrx_profile_edit_form_inputs" type="number" name="phone" id="phone" value="<?php echo esc_html($General_data['phone']); ?>">
				</div>
				<div class="info-item">
					<label for="name">Brand Name:</label>
					<input type="text" name="b_name" id="brand_name" value="<?php echo esc_html($General_data['brand_name']); ?>" class="sensfrx_profile_edit_form_inputs">
				</div>
				<div class="info-item">
					<label for="name">Brand URL:</label>
					<input type="text" name="b_url" id="brand_url" value="<?php echo esc_html($General_data['brand_url']); ?>" class="sensfrx_profile_edit_form_inputs">
				</div>
				<div id="sensfrx_profile_gender_timezone" class="info-item">
					<label for="timezone">Choose a Timezone:</label>
					<select id="timezone" name="timezone">
					<?php
					$timezones = timezone_identifiers_list();
					$selectedTimezone = ''; 
					if (isset($General_data['timezone'])) {
						$selectedTimezone = $General_data['timezone'];
					}
					foreach ($timezones as $tz) {
						$selected = ( $tz == $selectedTimezone ) ? 'selected' : '';
						echo '<option value="' . esc_attr($tz) . '" ' . esc_attr($selected) . '>' . esc_html($tz) . '</option>';
					}
					?>
					</select>
				</div>
				<div class="info-item">
					<label for="name">Oraganization Name:</label>
					<input type="text" name="o_name" id="org_name" value="<?php echo esc_html($General_data['org_name']); ?>" class="sensfrx_profile_edit_form_inputs">
				</div>
				<div class="sensfrx_profile_error-message" id="errorMessage">Please fill all the fields.</div>
				<button type="submit" id="submitform" class="sensfrx_profile_submit-button">Save Changes</button>
				<button type="button" onclick="closeForm()" class="sensfrx_profile_cancel-button">Cancel</button>
			</form>
			<form id="form-9" action="#profile" method="post">
				<?php wp_nonce_field('sensfrx_profile_action', 'sensfrx_profile_nonce'); ?>
				<input type="hidden" name="active_tab" value="tab-9">
				<input type="hidden" name="input_9_tab9" placeholder="Input 9">
				<input type="hidden" name="input_9_tab9" placeholder="Input 9">
				<input type="hidden" name="namename" id="myInputName">
				<input type="hidden" name="emailemail" id="myInputEmail">
				<input type="hidden" name="sexsex" id="myInputSex">
				<input type="hidden" name="bnamename" id="myInputBrandName">
				<input type="hidden" name="onamename" id="myInputOrgName">
				<input type="hidden" name="timezone1" id="timezone1">
				<input type="hidden" name="burlurl" id="myInputBrandUrl">
				<input type="hidden" name="phonephone" id="myInputPhone">
				<input type="submit" name="Submit_Profile_Form" id="Profile_same_tab" value="Submit" class="sensfrx_hide_button">
			</form>
		</div>
	</div>
	<?php
}
?>
