<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

	die('ASPATH is required - General PAGE');

}

$options = get_option('sensfrx_options', SensFRX::default_options());

require_once(SENSFRX_DIR . 'sensfrx-php-sdk/SensFRX/autoload.php');

$obj = new SensFRX\SensFRX([

	'property_id' => $options['sensfrx_property_id'],

	'property_secret' => $options['sensfrx_property_secret']

]);

$secretKey = $options['sensfrx_property_secret']; // Property ID

$propertyId = $options['sensfrx_property_id'];    // Secret Key



global $wpdb; 

$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity';

$sensfrx_ato_logs = $wpdb->get_results("SELECT * FROM $sensfrx_ato_table_name ORDER BY id DESC", ARRAY_A);

// $sensfrx_reg_table_name = $wpdb->prefix . 'sensfrx_real_activity_registration';

// $sensfrx_reg_logs = $wpdb->get_results("SELECT * FROM $sensfrx_reg_table_name ORDER BY id DESC", ARRAY_A);

// Test to see if WooCommerce is active (including network activated).



// Admin Email Whitelisting 

$sensfrx_policy_options = get_option('sensfrx_policy_options', SensFRX::default_policy_options());

// echo '<pre>';

// print_r($sensfrx_policy_options);

$sensfrx_white_list_admin_users = get_users(array('role' => 'administrator'));

$sensfrx_white_list_admin_info_array = array();

foreach ($sensfrx_white_list_admin_users as $user) {

    $sensfrx_white_list_admin_info_array[] = $user->user_email;

}

$sensfrx_white_list_admin_emails_string = implode(', ', $sensfrx_white_list_admin_info_array);

$sensfrx_whitelisting_update = null;

$sensfrx_whitelisting_not_update = null;

if ( isset($_POST['sensfrx_save_whitelist_settings']) && isset($_POST['sensfrx_save_whitelist_settings']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_whitelist_form_nonce_field']), 'sensfrx_whitelist_form_nonce') ) {

	$sensfrx_whitelist_checkbox_value = isset($_POST['sensfrx_whitelist_checkbox']) ? sanitize_text_field($_POST['sensfrx_whitelist_checkbox']) : null;

	if (isset($sensfrx_whitelist_checkbox_value) && null !== $sensfrx_whitelist_checkbox_value) {

		// $sensfrx_policy_options['sensfrx_whitle_list_email'] = '1';

		$sensfrx_policy_options['sensfrx_whitle_list_email'] = '1'; 

		update_option('sensfrx_policy_options', $sensfrx_policy_options);

		$sensfrx_whitelisting_update = true;

	} else if (!isset($sensfrx_whitelist_checkbox_value) && null == $sensfrx_whitelist_checkbox_value) {

		$sensfrx_policy_options['sensfrx_whitle_list_email'] = ''; 

		update_option('sensfrx_policy_options', $sensfrx_policy_options);

		$sensfrx_whitelisting_update = true;

	} else {

		$sensfrx_whitelisting_not_update = true;

	}

}

function is_user_whitelisted(){

	$sensfrx_policy_options = get_option('sensfrx_policy_options', SensFRX::default_policy_options());

	if ( '1' == $sensfrx_policy_options['sensfrx_whitle_list_email'] ) {

		return true;

	} else  {

		return false;

	} 

}

// Trans Review Approve

$trans_review_approve_success = false;

$trans_review_approve_failed = false;

$trans_review_reject_success = false;

$trans_review_reject_failed = false;

$trans_review_order_id = null;

if ( isset($_POST['sensfrx_trans_review_order_id_approve']) && isset($_POST['sensfrx_trans_review_order_id_approve']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_whitelist_form_nonce_trans_review_field']), 'sensfrx_whitelist_form_nonce_trans_review') ) {

	$sensfrx_trans_review_order_id_value = isset($_POST['sensfrx_trans_review_order_id_approve']) ? sanitize_text_field($_POST['sensfrx_trans_review_order_id_approve']) : null;

	if (isset($sensfrx_trans_review_order_id_value) && null !== $sensfrx_trans_review_order_id_value) {

		$trans_review_order_id_got = false;

		$trans_review_data = array();

		$postTransReview = array();

		$getTransReview = $obj->getTrans_Review();

		$getTransReview = $getTransReview['data'];

		$getTransReview = (array) $getTransReview;

		if (!empty($getTransReview)) {

			foreach ($getTransReview as $order) {

				if ($order->order_id == $sensfrx_trans_review_order_id_value) {

					$trans_review_order_id_got = true;

					$trans_review_data['trans_id'] = $order->transaction_id;

					$trans_review_data['action'] = 'approve';

					$postTransReview = $obj->postTrans_Review($trans_review_data);

					$trans_review_order_id = $order->order_id;

					$sensfrx_order = wc_get_order($trans_review_order_id);

					if (isset($postTransReview['status']) && $postTransReview['status'] === 'success') {

						if ($sensfrx_order) {

							$sensfrx_order->update_status('processing', 'Sensfrx - Order status updated to Processing.');
						
							$current_status = $sensfrx_order->get_status();

							
							if ($current_status === 'processing') {

								if (isset($postTransReview['status']) && $postTransReview['status'] === 'success') {

									$trans_review_approve_success = true;
						
									$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
						
									$current_date_ato = date('Y-m-d H:i:s'); 
						
									$insert_data = "The transaction was approved successfully on " . $current_date_ato . " for Order ID " . $trans_review_order_id . ".";
						
									$data = array(
						
										'sensfrx_log_type' =>  'Transaction Review',
						
										'sensfrx_log1' => $insert_data,
						
									);
						
									$results = wp_cache_get('sensfrx_admin_244_insert_data', 'sensfrx_group');
						
									if ($results !== false) {
						
										$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
						
										wp_cache_replace('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);
						
									} else {
						
										$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
						
										wp_cache_set('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);
						
									}
						
								} else {

									$trans_review_approve_failed = true;

									$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 

									$current_date_ato = date('Y-m-d H:i:s'); 

									$insert_data = "The transaction approved request was unsuccessful. on " . $current_date_ato . " for Order ID " . $trans_review_order_id . ".";



									$data = array(

										'sensfrx_log_type' =>  'Transaction Review',

										'sensfrx_log1' => $insert_data,

									);

									$results = wp_cache_get('sensfrx_admin_244_insert_data', 'sensfrx_group');

									if ($results !== false) {

										$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);

										wp_cache_replace('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);

									} else {

										$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);

										wp_cache_set('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);

									}

								}
							
							} else {
								
								$trans_review_approve_failed = true;

								$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 

								$current_date_ato = date('Y-m-d H:i:s'); 

								$insert_data = "The transaction approved request was unsuccessful. on " . $current_date_ato . " for Order ID " . $trans_review_order_id . " with status: " . $current_status . ".";



								$data = array(

									'sensfrx_log_type' =>  'Transaction Review',

									'sensfrx_log1' => $insert_data,

								);

								$results = wp_cache_get('sensfrx_admin_244_insert_data', 'sensfrx_group');

								if ($results !== false) {

									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);

									wp_cache_replace('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);

								} else {

									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);

									wp_cache_set('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);

								}

							}

						}
					} else {

						$trans_review_approve_failed = true;

						$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 

						$current_date_ato = date('Y-m-d H:i:s'); 

						$insert_data = "The transaction approved request was unsuccessful. on " . $current_date_ato . " for Order ID " . $trans_review_order_id . ".";



						$data = array(

							'sensfrx_log_type' =>  'Transaction Review',

							'sensfrx_log1' => $insert_data,

						);

						$results = wp_cache_get('sensfrx_admin_244_insert_data', 'sensfrx_group');

						if ($results !== false) {

							$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);

							wp_cache_replace('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);

						} else {

							$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);

							wp_cache_set('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);

						}

					}

					break;

				}

			}

		}

	}

}

if ( isset($_POST['sensfrx_trans_review_order_id_reject']) && isset($_POST['sensfrx_trans_review_order_id_reject']) && wp_verify_nonce(sanitize_text_field($_POST['sensfrx_whitelist_form_nonce_trans_review_field']), 'sensfrx_whitelist_form_nonce_trans_review') ) {

	$sensfrx_trans_review_order_id_value = isset($_POST['sensfrx_trans_review_order_id_reject']) ? sanitize_text_field($_POST['sensfrx_trans_review_order_id_reject']) : null;

	if (isset($sensfrx_trans_review_order_id_value) && null !== $sensfrx_trans_review_order_id_value) {

		$trans_review_order_id_got = false;

		$trans_review_data = array();

		$postTransReview = array();

		$getTransReview = $obj->getTrans_Review();

		$getTransReview = $getTransReview['data'];

		$getTransReview = (array) $getTransReview;

		if (!empty($getTransReview)) {

			foreach ($getTransReview as $order) {

				if ($order->order_id == $sensfrx_trans_review_order_id_value) {

					$trans_review_order_id_got = true;

					$trans_review_data['trans_id'] = $order->transaction_id;

					$trans_review_data['action'] = 'reject';

					$postTransReview = $obj->postTrans_Review($trans_review_data);

					$trans_review_order_id = $order->order_id;

					if (isset($postTransReview['status']) && $postTransReview['status'] === 'success') {

						$sensfrx_order = wc_get_order($trans_review_order_id);

						$sensfrx_order->update_status('cancelled', 'Sensfrx - Order marked as cancelled due to fraudulent activity.');

						$current_status = $sensfrx_order->get_status();
					
						if ($current_status === 'cancelled') {

							if (isset($postTransReview['status']) && $postTransReview['status'] === 'success') {
								
								$trans_review_reject_success = true;
					
								$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
					
								$current_date_ato = date('Y-m-d H:i:s'); 
					
								$insert_data = "The transaction was rejected successfully on " . $current_date_ato . " for Order ID " . $trans_review_order_id . ".";
					
								$data = array(
					
									'sensfrx_log_type' =>  'Transaction Review',
					
									'sensfrx_log1' => $insert_data,
					
								);
					
								$results = wp_cache_get('sensfrx_admin_244_insert_data', 'sensfrx_group');
					
								if ($results !== false) {
					
									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
					
									wp_cache_replace('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);
					
								} else {
					
									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
					
									wp_cache_set('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);
					
								}
					
							} else {

								$trans_review_reject_failed = true;
					
								$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
					
								$current_date_ato = date('Y-m-d H:i:s'); 
					
								$insert_data = "The transaction reject request was unsuccessful. on " . $current_date_ato . " for Order ID " . $trans_review_order_id . ".";
					
								$data = array(
					
									'sensfrx_log_type' =>  'Transaction Review',
					
									'sensfrx_log1' => $insert_data,
					
								);
					
								$results = wp_cache_get('sensfrx_admin_244_insert_data', 'sensfrx_group');
					
								if ($results !== false) {
					
									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
					
									wp_cache_replace('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);
					
								} else {
					
									$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
					
									wp_cache_set('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);
					
								}
					
							}

						} else {

							$trans_review_reject_failed = true;
				
							$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
				
							$current_date_ato = date('Y-m-d H:i:s'); 
				
							$insert_data = "The transaction reject request was unsuccessful. on " . $current_date_ato . " for Order ID " . $trans_review_order_id . " with status: " . $current_status . ".";
				
							$data = array(
				
								'sensfrx_log_type' =>  'Transaction Review',
				
								'sensfrx_log1' => $insert_data,
				
							);
				
							$results = wp_cache_get('sensfrx_admin_244_insert_data', 'sensfrx_group');
				
							if ($results !== false) {
				
								$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
				
								wp_cache_replace('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);
				
							} else {
				
								$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
				
								wp_cache_set('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);
				
							}
					
						}

						$refund_args = array(

							'amount'         => $sensfrx_order->get_total(),

							'order_id'       => $sensfrx_order,

							'reason'         => 'Refund due to detected fraudulent activity.',

							'refund_payment' => true, 

						);

						try {

							$refund = wc_create_refund($refund_args);

					

							if (is_wp_error($refund)) {

								$sensfrx_order->add_order_note('Refund failed: ' . $refund->get_error_message());

							} else {

								$sensfrx_order->add_order_note('Refund processed successfully for the cancelled order.');

							}

						} catch (Exception $e) {

							$sensfrx_order->add_order_note('Error during refund: ' . $e->getMessage());

						}
					} else {

						$trans_review_reject_failed = true;
			
						$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 
			
						$current_date_ato = date('Y-m-d H:i:s'); 
			
						$insert_data = "The transaction reject request was unsuccessful. on " . $current_date_ato . " for Order ID " . $trans_review_order_id . ".";
			
						$data = array(
			
							'sensfrx_log_type' =>  'Transaction Review',
			
							'sensfrx_log1' => $insert_data,
			
						);
			
						$results = wp_cache_get('sensfrx_admin_244_insert_data', 'sensfrx_group');
			
						if ($results !== false) {
			
							$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
			
							wp_cache_replace('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);
			
						} else {
			
							$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);
			
							wp_cache_set('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);
			
						}
			
					}
	
					break;

				}

			}

		}

	}

}

$trans_review_approve_success_bulk = false;

$trans_review_approve_failed_bulk = false;

$trans_review_reject_success_bulk = false;

$trans_review_reject_failed_bulk = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $trans_review_bulk_orderIds = $_POST['trans_review_order_id']; 

	$trans_review_bulk_action = $_POST['trans_review_bulk_action']; 

    if ( $trans_review_bulk_orderIds && $trans_review_bulk_action ) {

		$transReviewBulkOrderIds = explode(',', $trans_review_bulk_orderIds);

		$trans_review_transactions = [];

		$data = array();

		$getTransReview = $obj->getTrans_Review();

		$getTransReview = $getTransReview['data'];

		$getTransReview = (array) $getTransReview;

		if (!empty($getTransReview)) {

			foreach ($getTransReview as $order) {

				foreach ($transReviewBulkOrderIds as $bulk_action) {

					if ($order->order_id == $bulk_action) {

						$trans_review_transactions[] = [

							"trans_id" => $order->transaction_id, // Use => to define the key-value pair

							"action" => $trans_review_bulk_action // Define the action

						];

					}

				}

			}

		}

		$data1 = [

			'data' => $trans_review_transactions

		];

		$postTransReview = $obj->postTrans_Review($data1);

		if (isset($postTransReview['status']) && $postTransReview['status'] === 'success') {

			$insert_data = null;

			$current_date_ato = date('Y-m-d H:i:s'); 

			if ( 'approve' === $trans_review_bulk_action) {

				foreach ($transReviewBulkOrderIds as $order_id) {

					$order = wc_get_order($order_id);
					
					if (is_a($order, 'WC_Order')) {
	
						$order->update_status('processing', 'Sensfrx - Order status updated to Processing in bulk action.');
					
					} else {
	
						error_log("Order with ID " . $order . " not found.");
						
					}
				}

				$trans_review_approve_success_bulk = true;

				$insert_data = "The transaction was approved successfully on " . $current_date_ato . " for Order ID " . $trans_review_bulk_orderIds . ".";

			} else if ( 'reject' === $trans_review_bulk_action ) {

				foreach ($transReviewBulkOrderIds as $order_id) {

					$order = wc_get_order($order_id);
					
					if (is_a($order, 'WC_Order')) {
	
						$order->update_status('cancelled', 'Sensfrx - Order status updated to Cancelled in bulk action.');

						$refund_args = array(

							'amount'         => $order->get_total(),

							'order_id'       => $order,

							'reason'         => 'Refund due to detected fraudulent activity.',

							'refund_payment' => true, 

						);

						try {

							$refund = wc_create_refund($refund_args);

							if (is_wp_error($refund)) {

								$order->add_order_note('Refund failed: ' . $refund->get_error_message());

							} else {

								$order->add_order_note('Refund processed successfully for the cancelled order.');

							}

						} catch (Exception $e) {

							$order_id->add_order_note('Error during refund: ' . $e->getMessage());

						}
					
					} else {
	
						error_log("Order with ID " . $order_id . " not found.");
						
					}
				}

				$trans_review_reject_success_bulk = true;

				$insert_data = "The transaction was rejected successfully on " . $current_date_ato . " for Order ID " . $trans_review_bulk_orderIds . ".";

			}

			$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 

			$data = array(

				'sensfrx_log_type' =>  'Transaction Review',

				'sensfrx_log1' => $insert_data,

			);

			$results = wp_cache_get('sensfrx_admin_244_insert_data', 'sensfrx_group');

			if ($results !== false) {

				$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);

				wp_cache_replace('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);

			} else {

				$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);

				wp_cache_set('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);

			}

		} else {

			$insert_data = null;

			$current_date_ato = date('Y-m-d H:i:s'); 

			if ( 'approve' === $trans_review_bulk_action) {

				$trans_review_approve_failed_bulk = true;

				$insert_data = "The transaction approve request was unsuccessful. on " . $current_date_ato . " for Order ID " . $trans_review_bulk_orderIds . ".";

			} else if ( 'reject' === $trans_review_bulk_action ) {

				$trans_review_reject_failed_bulk = true;

				$insert_data = "The transaction reject request was unsuccessful. on " . $current_date_ato . " for Order ID " . $trans_review_bulk_orderIds . ".";

			}

			$sensfrx_ato_table_name = $wpdb->prefix . 'sensfrx_real_activity'; 

			$data = array(

				'sensfrx_log_type' =>  'Transaction Review',

				'sensfrx_log1' => $insert_data,

			);

			$results = wp_cache_get('sensfrx_admin_244_insert_data', 'sensfrx_group');

			if ($results !== false) {

				$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);

				wp_cache_replace('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);

			} else {

				$insert_result = $wpdb->insert($sensfrx_ato_table_name, $data);

				wp_cache_set('sensfrx_admin_244_insert_data', $results, 'sensfrx_group', 86400);

			}

		}

		

	}


}

// Woo Mandetory is active

$woocommerce_check = trailingslashit( WP_PLUGIN_DIR ) . 'woocommerce/woocommerce.php';

if ( file_exists($woocommerce_check) && in_array($woocommerce_check, wp_get_active_and_valid_plugins()) ) {  

	// Trans Review Get API Call

	$getTransReview = array();

	if (null != $propertyId && null != $secretKey) {

		$getTransReview = $obj->getTrans_Review();

		$getTransReview = $getTransReview['data'];

		$getTransReview = (array) $getTransReview;

	}

	// echo '<pre>';

	// print_r($getTransReview);

	?>

	<div id="sensfrxPluginDashboard">

		<div class="sensfrx-logo"><?php

			$logo_path = plugins_url() . '/sensfrx-fraud-prevention-for-woocommerce/admin/image/sensfrx-nf.png';

			echo '<img  class="sensfrx-logo-image" src="' . esc_html($logo_path) . '" alt="Sensfrx Logo" /> '; ?>

		</div>

		<div class="widefat">

			<!-- Tab Navigation -->

			<div class="sensfrx-general-css-tab-container">

				<div class="sensfrx-css-123-tab active" sensfrx-general-data-tab="1">Dashboard</div>

				<div class="sensfrx-css-123-tab" sensfrx-general-data-tab="4">Order Review</div>

				<div class="sensfrx-css-123-tab" sensfrx-general-data-tab="2">Activity Logs</div>

				<!-- <div class="sensfrx-css-123-tab" sensfrx-general-data-tab="4">Registration</div> -->

				<div class="sensfrx-css-123-tab" sensfrx-general-data-tab="3">Settings</div>

			</div>



			<div id="sensfrx-whitelist-tab-content-1" class="sensfrx-css-123-tabtab-content active">

				<?php 

				$propertyId = $options['sensfrx_property_id'];

				$secretKey = $options['sensfrx_property_secret'];

				$apiKey = base64_encode($propertyId . ':' . $secretKey);

				$site_title = home_url();

				$URL = $site_title . '/wp-admin/admin.php?page=sensfrx_integration';

				?>

				<input type="text" id="sensfrx_apiKey" class="sensfrx_hide_button" value="<?php echo esc_html($apiKey); ?>">

				<?php 

				if ( isset($propertyId) && null != $propertyId && isset($secretKey) && null != $secretKey ) {

					?>

					<div class="sensfrx_form-container">

						<div class="sensfrx-tabs-head">

							<h2 class="sensfrx-tabs-title">Dashboard :</h2>

							<select name="example-dropdown" id="sensfrx_date_dashboard">

								<option value="filter-7" selected>Last 7 Day's</option>

								<option value="filter-30">Last 30 Day's</option>

								<option value="filter-365">Last Year</option>

							</select>

						</div>

						<div class="sensfrx_form-container-1">

							<h4 class="sensfrx_form-tab-title">Account Security Fraud Analytics</h4>

							<div class="sensfrx_box-container">

								<div class="sensfrx_as1-stat-wrap sensfrx_as1-stat-wrap-total">

									<div class="sensfrx_as1-stat-no sensfrx_as1-stat-total" id="Total_ATO_c">00</div>

										<div class="sensfrx_as1-stat-txt">

											<strong>Total Logins</strong>

										</div>

										<div class="asensfrx_s1-stat-gs as1-stat-up" id="vCT-dir">

											<div class="sensfrx_as1-stat-graph">

												<i class="as-icon as1-icon-stat-graph"></i>

											</div>

											<div class="sensfrx_as1-stat-gnum text-nowrap">

												<i class="as1-icon-point"></i>

											<span id="ATO_total_change">Property or Secret Key is not correct, <a href="<?php echo esc_url($URL); ?>">please check</a></span>

										</div>

									</div>

								</div>

								<div class="sensfrx_as1-stat-wrap sensfrx_as1-stat-wrap-denied">

									<div class="sensfrx_as1-stat-no sensfrx_as1-stat-total" id="Total_ATO_d">00</div>

									<div class="sensfrx_as1-stat-txt">

										<strong>Denied Logins</strong>

									</div>

									<div class="asensfrx_s1-stat-gs as1-stat-up" id="vCT-dir">

										<div class="sensfrx_as1-stat-graph">

											<i class="as-icon as1-icon-stat-graph"></i>

										</div>

										<div class="sensfrx_as1-stat-gnum text-nowrap">

											<i class="as1-icon-point"></i>

											<span id="ATO_total_d_change">Property or Secret Key is not correct, <a href="<?php echo esc_url($URL); ?>">please check</a></span>

										</div>

									</div>

								</div>  

								<div class="sensfrx_as1-stat-wrap sensfrx_as1-stat-wrap-reviewed">

									<div class="sensfrx_as1-stat-no sensfrx_as1-stat-total" id="Total_ATO_chall">00</div>

									<div class="sensfrx_as1-stat-txt">

										<strong>Challenged Logins</strong>

									</div>

									<div class="asensfrx_s1-stat-gs as1-stat-up" id="vCT-dir">

										<div class="sensfrx_as1-stat-graph">

											<i class="as-icon as1-icon-stat-graph"></i>

										</div>

										<div class="sensfrx_as1-stat-gnum text-nowrap">

											<i class="as1-icon-point"></i>

											<span id="Total_ATO_chall_change">Property or Secret Key is not correct, <a href="<?php echo esc_url($URL); ?>">please check</a></span>

										</div>

									</div>

								</div>

								<div class="sensfrx_as1-stat-wrap sensfrx_as1-stat-wrap-allowed">

									<div class="sensfrx_as1-stat-no sensfrx_as1-stat-total" id="Total_ATO_a">00</div>

									<div class="sensfrx_as1-stat-txt">

										<strong>Allowed Logins</strong>

									</div>

									<div class="asensfrx_s1-stat-gs as1-stat-up" id="vCT-dir">

										<div class="sensfrx_as1-stat-graph">

											<i class="as-icon as1-icon-stat-graph"></i>

										</div>

										<div class="sensfrx_as1-stat-gnum text-nowrap">

											<i class="as1-icon-point"></i>

											<span id="Total_ATO_a_change">Property or Secret Key is not correct, <a href="<?php echo esc_url($URL); ?>">please check</a></span>

										</div>

									</div>

								</div>

							</div>

						</div>

						<br>

						<div class="sensfrx_form-container-1">

							<h4 class="sensfrx_form-tab-title">Transaction Fraud Analytics</h4>

							<div class="sensfrx_box-container">

								<div class="sensfrx_as1-stat-wrap sensfrx_as1-stat-wrap-total">

									<div class="sensfrx_as1-stat-no sensfrx_as1-stat-total" id="Total_c">00</div>

									<div class="sensfrx_as1-stat-txt">

										<strong>Total Transactions</strong>

									</div>

									<div class="asensfrx_s1-stat-gs as1-stat-up" id="vCT-dir">

										<div class="sensfrx_as1-stat-graph">

											<i class="as-icon as1-icon-stat-graph"></i>

										</div>

										<div class="sensfrx_as1-stat-gnum text-nowrap">

											<i class="as1-icon-point"></i>

											<span id="Total_c_change">Property or Secret Key is not correct, <a href="<?php echo esc_url($URL); ?>">please check</a></span>

										</div>

									</div>

								</div>

								<div class="sensfrx_as1-stat-wrap sensfrx_as1-stat-wrap-denied">

									<div class="sensfrx_as1-stat-no sensfrx_as1-stat-total" id="Total_d">00</div>

									<div class="sensfrx_as1-stat-txt">

										<strong>Denied Transaction</strong>

									</div>

									<div class="asensfrx_s1-stat-gs as1-stat-up" id="vCT-dir">

										<div class="sensfrx_as1-stat-graph">

											<i class="as-icon as1-icon-stat-graph"></i>

										</div>

										<div class="sensfrx_as1-stat-gnum text-nowrap">

											<i class="as1-icon-point"></i>

											<span id="Total_d_change">Property or Secret Key is not correct, <a href="<?php echo esc_url($URL); ?>">please check</a></span>

										</div>

									</div>

								</div>

								<div class="sensfrx_as1-stat-wrap sensfrx_as1-stat-wrap-reviewed">

									<div class="sensfrx_as1-stat-no sensfrx_as1-stat-total" id="Total_chall">00</div>

									<div class="sensfrx_as1-stat-txt">

										<strong>Review Transactions</strong>

									</div>

									<div class="asensfrx_s1-stat-gs as1-stat-up" id="vCT-dir">

										<div class="sensfrx_as1-stat-graph">

											<i class="as-icon as1-icon-stat-graph"></i>

										</div>

										<div class="sensfrx_as1-stat-gnum text-nowrap">

											<i class="as1-icon-point"></i>

											<span id="Total_chall_change">Property or Secret Key is not correct, <a href="<?php echo esc_url($URL); ?>">please check</a></span>    

										</div>

									</div>

								</div>

								<div class="sensfrx_as1-stat-wrap sensfrx_as1-stat-wrap-allowed">

									<div class="sensfrx_as1-stat-no sensfrx_as1-stat-total" id="Total_a">00</div>

									<div class="sensfrx_as1-stat-txt">

										<strong>Allowed Transaction</strong>

									</div>

									<div class="asensfrx_s1-stat-gs as1-stat-up" id="vCT-dir">

										<div class="sensfrx_as1-stat-graph">

											<i class="as-icon as1-icon-stat-graph"></i>

										</div>

										<div class="sensfrx_as1-stat-gnum text-nowrap">

											<i class="as1-icon-point"></i>

											<span id="Total_a_change">Property or Secret Key is not correct, <a href="<?php echo esc_url($URL); ?>">please check</a></span>

										</div>

									</div>

								</div>

							</div>

						</div>

						<br>

						<div class="sensfrx_form-container-1">

							<h4 class="sensfrx_form-tab-title">New Account Fraud Analytics</h4>

							<div class="sensfrx_box-container">

								<div class="sensfrx_as1-stat-wrap sensfrx_as1-stat-wrap-total">

									<div class="sensfrx_as1-stat-no sensfrx_as1-stat-total" id="Total_c_reg">00</div>

									<div class="sensfrx_as1-stat-txt">

										<strong>Total New Accounts</strong>

									</div>

									<div class="asensfrx_s1-stat-gs as1-stat-up" id="vCT-dir">

										<div class="sensfrx_as1-stat-graph">

											<i class="as-icon as1-icon-stat-graph"></i>

										</div>

										<div class="sensfrx_as1-stat-gnum text-nowrap">

											<i class="as1-icon-point"></i>

											<span id="Total_c_change_reg">Property or Secret Key is not correct, <a href="<?php echo esc_url($URL); ?>">please check</a></span>

										</div>

									</div>

								</div>

								<div class="sensfrx_as1-stat-wrap sensfrx_as1-stat-wrap-denied">

									<div class="sensfrx_as1-stat-no sensfrx_as1-stat-total" id="Total_d_reg">00</div>

									<div class="sensfrx_as1-stat-txt">

										<strong>Denied New Accounts</strong>

									</div>

									<div class="asensfrx_s1-stat-gs as1-stat-up" id="vCT-dir">

										<div class="sensfrx_as1-stat-graph">

											<i class="as-icon as1-icon-stat-graph"></i>

										</div>

										<div class="sensfrx_as1-stat-gnum text-nowrap">

											<i class="as1-icon-point"></i>

											<span id="Total_d_change_reg">Property or Secret Key is not correct, <a href="<?php echo esc_url($URL); ?>">please check</a></span>

										</div>

									</div>

								</div>

								<div class="sensfrx_as1-stat-wrap sensfrx_as1-stat-wrap-reviewed">

									<div class="sensfrx_as1-stat-no sensfrx_as1-stat-total" id="Total_chall_reg">00</div>

									<div class="sensfrx_as1-stat-txt">

										<strong>Review New Accounts</strong>

									</div>

									<div class="asensfrx_s1-stat-gs as1-stat-up" id="vCT-dir">

										<div class="sensfrx_as1-stat-graph">

											<i class="as-icon as1-icon-stat-graph"></i>

										</div>

										<div class="sensfrx_as1-stat-gnum text-nowrap">

											<i class="as1-icon-point"></i>

											<span id="Total_chall_change_reg">Property or Secret Key is not correct, <a href="<?php echo esc_url($URL); ?>">please check</a></span>    

										</div>

									</div>

								</div>

								<div class="sensfrx_as1-stat-wrap sensfrx_as1-stat-wrap-allowed">

									<div class="sensfrx_as1-stat-no sensfrx_as1-stat-total" id="Total_a_reg">00</div>

									<div class="sensfrx_as1-stat-txt">

										<strong>Allowed New Accounts</strong>

									</div>

									<div class="asensfrx_s1-stat-gs as1-stat-up" id="vCT-dir">

										<div class="sensfrx_as1-stat-graph">

											<i class="as-icon as1-icon-stat-graph"></i>

										</div>

										<div class="sensfrx_as1-stat-gnum text-nowrap">

											<i class="as1-icon-point"></i>

											<span id="Total_a_change_reg">Property or Secret Key is not correct, <a href="<?php echo esc_url($URL); ?>">please check</a></span>

										</div>

									</div>

								</div>

							</div>

						</div>

						<p>

							<h3><a href="https://client.sensfrx.ai" target="_blank"> <strong>Click</strong> here</a> to access the dashboard for more data-driven insights and captivating graphical representation.<span class="sensfrx_comparisonResult" id="comparisonResult"></span></h3>

						</p>

					</div>

					<?php 

				} else {

					?>

					<div class="sensfrx_form-container">

						<div class="sensfrx_form-container-auth">

							<h4><span class="sensfrx_form-container-auth-font">⚠️</span> Sorry, there's an issue with the <b>Authentication</b>.Please check <b><a href="<?php echo esc_url($URL); ?>">Property ID</a></b> & <b><a href="<?php echo esc_url($URL); ?>">Secret Key</a></b>.</h4>

							<p>If the problem persists, please check the property ID and ensure that all required information is correctly provided. For further assistance, contact our support team at <a href="mailto:info@sensfrx.ai">info@Sensfrx.ai</a>.</p>

						</div>

					</div>

					<?php 

				}

				?>

			</div>

			<div id="sensfrx-whitelist-tab-content-4" class="sensfrx-css-123-tabtab-content">

				<form id="form-8" action="" method="post">

					<?php wp_nonce_field('sensfrx_privacy_action', 'sensfrx_privacy_nonce'); ?>

					<input type="hidden" name="active_tab" value="tab-8">

					<input type="hidden" name="input_1_tab3" placeholder="Input 1">

					<input type="hidden" name="input_2_tab3" placeholder="Input 2">

					<input type="hidden" name="trans_review_order_id" id="trans_review_order_id" >

					<input type="hidden" name="trans_review_bulk_action" id="trans_review_bulk_action" >

					<input type="submit" name="privacy_submit" id="trans_review_bulk_submit" value="Submit" class="sensfrx_hide_button" onclick='sensfrx_tab_open_which_save_transaction_review_bulk(event)';>

				</form>

				<?php

				if (true === $trans_review_approve_success) {

					?>

					<div class="sensfrx_myAlert_notification_whitelist_success_trans_review" id="myAlert">

						Order Approve Successfully...

					</div>

					<?php 

				} 

				if (true === $trans_review_approve_failed) {

					?>

					<div class="sensfrx_myAlert_notification_whitelist_failed_trans_review" id="myAlert">

						Order Not Approve Successfully...

					</div>

					<?php 

				}

				if (true === $trans_review_reject_success) {

					?>

					<div class="sensfrx_myAlert_notification_whitelist_success_trans_review" id="myAlert">

						Order Reject Successfully...

					</div>

					<?php 

				} 

				if (true === $trans_review_reject_failed) {

					?>

					<div class="sensfrx_myAlert_notification_whitelist_failed_trans_review" id="myAlert">

						Order Not Reject Successfully...

					</div>

					<?php 

				}

				if (true === $trans_review_approve_success_bulk) {

					?>

					<div class="sensfrx_myAlert_notification_whitelist_success_trans_review" id="myAlert">

						Order Approve Successfully...

					</div>

					<?php 

				} 

				if (true === $trans_review_reject_success_bulk) {

					?>

					<div class="sensfrx_myAlert_notification_whitelist_success_trans_review" id="myAlert">

						Order Reject Successfully...

					</div>

					<?php 

				}

				if (true === $trans_review_approve_failed_bulk) {

					?>

					<div class="sensfrx_myAlert_notification_whitelist_failed_trans_review" id="myAlert">

						Order Not Approve Successfully...

					</div>

					<?php 

				} 

				if (true === $trans_review_reject_failed_bulk) {

					?>

					<div class="sensfrx_myAlert_notification_whitelist_failed_trans_review" id="myAlert">

						Order Not Reject Successfully...

					</div>

					<?php 

				}

				?>

				<div class="sensfrx-tabs-head">

					<h2 class="sensfrx-tabs-title">Order Review :</h2>

					<form method='post' action='' class='trans_review_bulk_action_form'>

						<div class="trans_review_bulk-action-container">

							<input type="text" id="trans_review_search_filter" placeholder="Search (Order ID, Email, Date)" onkeyup="filterTransactions()">

							<select id="trans_review_bulk_action_123">

								<option value="">Select Action</option>

								<option value="approve">Approve</option>

								<option value="reject">Reject</option>

							</select>

							<button id="trans_review_apply_bulk_action" onclick="applyBulkAction(event)">Apply</button>

						</div>

					</form>

				</div>



				<!-- Transaction Table -->

				<div class="sensfrx_transaction_review_table_container">

					<table class="sensfrx_transaction_review_table" id="transaction_table">

						<thead>

							<tr>

								<th class="sensfrx_transaction_review_th"><input type="checkbox" id="select_all" onclick="toggleSelectAll(this)"></th> <!-- Checkbox for all -->

								<th class="sensfrx_transaction_review_th">#</th>

								<th class="sensfrx_transaction_review_th">Order ID</th>

								<th class="sensfrx_transaction_review_th">Email</th>

								<th class="sensfrx_transaction_review_th">Risk Score</th>

								<th class="sensfrx_transaction_review_th">Date Created</th>

								<th class="sensfrx_transaction_review_th">Details</th>

								<th class="sensfrx_transaction_review_th">Action</th>

							</tr>

						</thead>

						<tbody id="transaction_rows">

							<?php

							// Loop through each order and create a row in the table

							if (!empty($getTransReview)) {

								$count_trans_review = 0;

								foreach ($getTransReview as $order) {

									$count_trans_review++;

									echo "<tr data-order-id='" . htmlspecialchars($order->order_id) . "' data-email='" . htmlspecialchars($order->email) . "' data-date='" . htmlspecialchars(date('d-M-Y h:i A', strtotime($order->event_time))) . "'>";

									echo "<td class='sensfrx_transaction_review_td'><input type='checkbox' class='transaction_checkbox' data-order-id='" . htmlspecialchars($order->order_id) . "'></td>"; // Checkbox for each row

									echo "<td class='sensfrx_transaction_review_td'>" . htmlspecialchars($count_trans_review) . "</td>";

									echo "<td class='sensfrx_transaction_review_td'>" . htmlspecialchars($order->order_id) . "</td>";

									echo "<td class='sensfrx_transaction_review_td'>" . htmlspecialchars($order->email) . "</td>";

									echo "<td class='sensfrx_transaction_review_td'>" . htmlspecialchars($order->transaction_score) . "</td>";

									echo "<td class='sensfrx_transaction_review_td'>" . htmlspecialchars(date('d-M-Y h:i A', strtotime($order->event_time))) . "</td>";

									echo "<td class='sensfrx_transaction_review_td'><a href='" . htmlspecialchars($order->link) . "' target='_blank'>View Details</a></td>";

									echo "<td class='sensfrx_transaction_review_td'>";

									

									// Form for approve/reject buttons

									echo "<form method='post' action='' style='display:inline;'>";

									wp_nonce_field('sensfrx_whitelist_form_nonce_trans_review', 'sensfrx_whitelist_form_nonce_trans_review_field'); 

									echo "<input type='hidden' name='sensfrx_trans_review_order_id_approve' value='" . htmlspecialchars($order->order_id) . "'>";

									echo "<input type='hidden' name='action_type' value='approve'>";

									echo "<button type='submit' class='sensfrx_transaction_review_button sensfrx_transaction_review_approve_button' onclick='sensfrx_tab_open_which_save_transaction_review(event);'>Approve</button>";

									echo "</form>"; 

									echo "&nbsp;";

									echo "<form method='post' action='' style='display:inline;'>";

									wp_nonce_field('sensfrx_whitelist_form_nonce_trans_review', 'sensfrx_whitelist_form_nonce_trans_review_field'); 

									echo "<input type='hidden' name='sensfrx_trans_review_order_id_reject' value='" . htmlspecialchars($order->order_id) . "'>";

									echo "<input type='hidden' name='action_type' value='reject'>";

									echo "<button type='submit' class='sensfrx_transaction_review_button sensfrx_transaction_review_reject_button' onclick='sensfrx_tab_open_which_save_transaction_review_reject(event);'>Reject</button>";

									echo "</form>";

						

									echo "</td>";

									echo "</tr>";

								}

							} else {

								echo "<tr><td colspan='8' class='sensfrx_transaction_review_td'>No orders found</td></tr>";

							}

							?>

						</tbody>

					</table>

					<div id="no_results_message" style="display:none; text-align: center; margin-top: 10px; color: black; padding: 10px 10px 10px 10px;">No matching order found.</div>

				</div>



			</div>

			<div id="sensfrx-whitelist-tab-content-2" class="sensfrx-css-123-tabtab-content">

				<div class="sensfrx-tabs-head">

					<h2 class="sensfrx-tabs-title">Activity Logs :</h2>

				</div>

				<div class="sensfrx_ato_logs_table-container">

					<?php if (!empty($sensfrx_ato_logs)) : ?>

						<table class="sensfrx_ato_logs_custom-table sensfrx-tables-1">

							<thead>

								<tr>

									<th>#</th>

									<th>Activity</th>

									<th>Text</th>

									<th>Time</th>

								</tr>

							</thead>

							<tbody>

								<?php 

									$counter = 1;

									foreach ($sensfrx_ato_logs as $row) : 

								?>

									<tr>

										<td><?php echo $counter++; ?></td>

										<td><?php echo esc_html($row['sensfrx_log_type']); ?></td>

										<td><?php echo esc_html($row['sensfrx_log1']); ?></td>

										<td><?php echo esc_html($row['created_at']); ?></td>

									</tr>

								<?php endforeach; ?>

							</tbody>

						</table>

					<?php else : ?>

						<p class="sensfrx_ato_logs_empty-message">No data available in the table.</p>

					<?php endif; ?>

				</div>

			</div>

			<div id="sensfrx-whitelist-tab-content-3" class="sensfrx-css-123-tabtab-content">

				<?php

				if (true === $sensfrx_whitelisting_update) {

					?>

					<div class="sensfrx_myAlert_notification_whitelist_success" id="myAlert">

						Whitelist Feature Updated Successfully...

					</div>

					<?php 

				} 

				if (true === $sensfrx_whitelisting_not_update) {

					?>

					<div class="sensfrx_myAlert_notification_whitelist_failed" id="myAlert">

						VWhitelist Feature Not Updated Successfully...

					</div>

					<?php 

				}

				?>

				<div class="sensfrx-tabs-head">

					<h2 class="sensfrx-tabs-title">Settings :</h2>

				</div>

				<form method="post" action="">

					<?php wp_nonce_field('sensfrx_whitelist_form_nonce', 'sensfrx_whitelist_form_nonce_field'); ?>

					<p>Configure Sensfrx's fraud prevention settings, including admin user whitelisting and analysis parameters.</p>

					<label for="sensfrx_whitelist_checkbox">

						<input type="checkbox" id="sensfrx_whitelist_checkbox" name="sensfrx_whitelist_checkbox" value="1" <?php echo ( isset($sensfrx_policy_options['sensfrx_whitle_list_email']) && 1 == esc_attr($sensfrx_policy_options['sensfrx_whitle_list_email']) ) ? 'checked="checked"' : ''; ?>>

						Whitelist admin users in fraud analysis

					</label>

					<br>

					<label for="whitelisted_email">Currently Whitelisted Admin Users :</label>

					<table class="sensfrx-tables-1">

						<thead>

							<tr>

								<th>#</th>

								<th>Username</th>

								<th>Email</th>

							</tr>

						</thead>

						<tbody>

							<?php

							$count_num = 0;

							foreach ($sensfrx_white_list_admin_users as $user_data) {

								$count_num++;

								if (is_user_whitelisted()) {

									echo "<tr>";

									echo "<td>" . esc_html($count_num) . "</td>";

									echo "<td>" . esc_html($user_data->user_login) . "</td>";

									echo "<td>" . esc_html($user_data->user_email) . "</td>";

									echo "</tr>";

								}

							}

							?>

						</tbody>

					</table>

					<!-- <div id="sensfrx-whitelisted-emails"><?php echo $sensfrx_white_list_admin_emails_string; ?></div> -->

					<br><br>

					<input type="submit" name="sensfrx_save_whitelist_settings" value="Save" class="button button-primary" onclick="sensfrx_tab_open_which_save();">

				</form>

			</div>

		</div>

	</div>

	<?php

} else {

	?>

	

	<div class="sensfrx_woocommerce-mandatory-message">

		<p>WooCommerce activation is mandatory for this functionality.</p>

	</div>

	<?php    

}

?>





