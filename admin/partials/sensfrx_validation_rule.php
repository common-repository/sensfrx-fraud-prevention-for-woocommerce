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
	die('ASPATH is required - Validation PAGE');
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
$notification_validation = false;
$notification_validation1 = false;
$validation = false;
$validation1 = false;
$validation2 = false;
if ( isset($_POST['validator']) && isset($_POST['sensfrx_validator_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_validator_nonce']), 'sensfrx_validator_action') ) {
	$score_value = isset($_POST['scoreValues']) ? sanitize_text_field($_POST['scoreValues']) : null;
	$active_value = isset($_POST['activeValues']) ? sanitize_text_field($_POST['activeValues']) : null;
	$score_string = explode(',', $score_value);
	$score_array = array();
	foreach ($score_string as $piece) {
		$score_array[] = $piece;
	}
	$active_string = explode(',', $active_value);
	$active_array = array();
	foreach ($active_string as $piece) {
		$active_array[] = $piece;
	}
	$postData = array();
	$res = $obj->getrulesinfo();

	if (is_array($res)) {
		$detector_date = $res['data'];
		$detector_date = array_map(function($item) {
			return (array) $item;
		}, $detector_date);
		usort($detector_date, function ($a, $b) {
			return strcmp($a['title'], $b['title']);
		});
		foreach ($detector_date as &$item) {
			// Assign values from the score_array and active_array using array_shift
			$item['score_value'] = array_shift($score_array);
			$item['active'] = array_shift($active_array);
		}
		if (!empty($propertyId) && !empty($secretKey)) {
			$res_Validaton = $obj->postrulesinfo($detector_date);
			if (is_array($res_Validaton)) {
				if ('success' == $res_Validaton['status']) {
					$notification_validation = true;
				}
				if ('success' != $res_Validaton['status']) {
					$notification_validation1 = true;
				}
			} else {
				$notification_validation = false;
				$notification_validation1 = true;
			}
		}
	}
}
$res = $obj->getrulesinfo();
$validation = false;
$validation1 = false;
$validation2 = false;
if (is_array($res)) {
	if ('success' === $res['status']) {
		$validation = true;
		$detector_date = $res['data'];
		$detector_date = array_map(function($item) {
			return (array) $item;
		}, $detector_date);
		$sr_no = 1;
	}
	if ('success' != $res['status']) {
		$validation1 = true;
	}  
} elseif (is_string($res)) {
	$validation = false;
	$validation1 = false;
	$validation2 = true;
} else {
	$validation = false;
	$validation1 = true;
	$validation2 = false;
}
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SensFRX Validation</title>

<?php 
if (true === $notification_validation) {
	?>
	<div class="sensfrx_myAlert_notification_success" id="myAlert">
		Validate Data Updated Successfully...
	</div>
	<?php 
} 
if (true === $notification_validation1) {
	?>
	<div class="sensfrx_myAlert_notification_failed" id="myAlert">
		Validate Data Not Updated Successfully...
	</div>
	<?php 
}
if (true === $validation1) {
	?>
	<div class="sensfrx_form-container">
		<div class="sensfrx_form-container-auth">
			<h4><span class="sensfrx_form-container-auth-font">⚠️</span> Sorry, there's an issue with the API. Please try again later.</h4>
			<p>If the problem persists, please check the <b><a href="<?php echo esc_url($URL); ?>">Property ID</a></b> & <b><a href="<?php echo esc_url($URL); ?>">Secret Key</a> and ensure that all required information is correctly provided. For further assistance, contact our support team at <a href="mailto:info@sensfrx.ai">info@Sensfrx.ai</a>.</p>
		</div>
	</div>
	<?php 
}
if (true === $validation2) {
	?>
	<div class="sensfrx_form-container">
		<div class="sensfrx_form-container-auth">
			<h4><span class="sensfrx_form-container-auth-font">⚠️</span> Sorry, there's an issue with the <b>Authentication</b>.Please check <b><a href="<?php echo esc_url($URL); ?>">Property ID</a></b> & <b><a href="<?php echo esc_url($URL); ?>">Secret Key</a></b>.</h4>
			<p>If the problem persists, please check the property ID and ensure that all required information is correctly provided. For further assistance, contact our support team at <a href="mailto:info@sensfrx.ai">info@Sensfrx.ai</a>.</p>
		</div>
	</div>
	<?php
}
if (true === $validation) {
	?>
	<form id="form-2" action="#validation-rules" method="post">
		<?php wp_nonce_field('sensfrx_validator_action', 'sensfrx_validator_nonce'); ?>
		<input type="hidden" name="active_tab" value="tab-2">
		<input type="hidden" name="input_1_tab2" placeholder="Input 1">
		<input type="hidden" name="input_2_tab2" placeholder="Input 2">
		<input type="hidden" id="activeValues" name="activeValues" value="">
		<input type="hidden" id="scoreValues" name="scoreValues" value="">
		<input class="sensfrx_hide_button" type="submit" name="validator" id="validator" value="Submit">
	</form>
	<form id="mainForm" action="" method="post">
		<div class="sensfrx_settings-container">
			<h2 class="sensfrx_tab_heading">Validation Rules :</h2>
			<table class="sensfrx_validation_rules_table">
				<thead>
					<tr>
						<th>Count</th>
						<th>Detector</th>
						<th>Title</th>
						<th>Description</th>
						<th>Score Value</th>
						<th>Active</th>
						<th>Module</th> 
					</tr>
				</thead>
				<?php
				usort($detector_date, function ($a, $b) {
					return strcmp($a['title'], $b['title']);
				});
				foreach ($detector_date as $index => $item) :
					?>
				<tbody>
					<tr>
						<td><?php echo esc_html($sr_no++); ?></td>
						<td><?php echo esc_html($item['code']); ?></td>
						<td><?php echo esc_html($item['title']); ?></td>
						<td><?php echo esc_html($item['desc']); ?></td>
						<td><input type="number" name="score[]" value="<?php echo esc_html($item['score_value']); ?>"></td>
						<td><input type="checkbox" name="active[]" <?php echo 1 == $item['active'] ? 'checked' : ''; ?>></td>
						<td><?php echo esc_html(implode(', ', $item['tag'])); ?></td> 
					</tr>
				</tbody>
				<?php endforeach; ?>
			</table>
			<button type="submit" onclick="validation_rules(event)" class="sensfrx-validation-rules-save-button">Save Changes</button>
		</div>
	</form>
	<?php 
}
