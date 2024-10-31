<?php
/**
 * Fired during plugin activation
 *
 * @link       https://sensfrx.ai
 * @since      1.0.0
 *
 * @package    SensFRX
 * @subpackage SensFRX/includes
 */
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    SensFRX
 * @subpackage SensFRX/includes
 */
class SensFRX_Table_Delete {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function table_delete() {
		global $wpdb;
		// $table_name = $wpdb->prefix . 'sensfrx_sleep';
		// $results = wp_cache_get('sensfrx_admin_table_data', 'sensfrx_group');
		// if ($results !== false) {

		// } else {
		// 	$drop_tb = $wpdb->query($wpdb->prepare('DROP TABLE IF EXISTS `%s`', $table_name));
		// 	wp_cache_set('sensfrx_admin_table_data', $drop_tb, 'sensfrx_group', 86400);
		// }
		// // $drop_tb = $wpdb->query($wpdb->prepare('DROP TABLE IF EXISTS `%s`', $table_name));
		// $table_name = $wpdb->prefix . 'sensfrx_webhook_logout';
		// $results = wp_cache_get('sensfrx_admin_table1_data', 'sensfrx_group');
		// if ($results !== false) {

		// } else {
		// 	$drop_tb = $wpdb->query($wpdb->prepare('DROP TABLE IF EXISTS `%s`', $table_name));
		// 	wp_cache_set('sensfrx_admin_table1_data', $drop_tb, 'sensfrx_group', 86400);
		// }
		// // $drop_tb = $wpdb->query($wpdb->prepare('DROP TABLE IF EXISTS `%s`', $table_name));

		
		$table_name = $wpdb->prefix . 'sensfrx_sleep';
		$sanitized_table_name = esc_sql($table_name);
		if ($results !== false) {
			$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_sleep');
			wp_cache_replace('sensfrx_admin_table1_data', $drop_tb, 'sensfrx_group', 86400);
		} else {
			$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_sleep');
			wp_cache_set('sensfrx_admin_table1_data', $drop_tb, 'sensfrx_group', 86400);
		}
		// $wpdb->query("DROP TABLE IF EXISTS `$sanitized_table_name`");

		$table_name = $wpdb->prefix . 'sensfrx_webhook_logout';
		$sanitized_table_name = esc_sql($table_name);
		$results = wp_cache_get('sensfrx_admin_table1_data', 'sensfrx_group');
		if ($results !== false) {
			$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_webhook_logout');
			wp_cache_replace('sensfrx_admin_table1_data', $drop_tb, 'sensfrx_group', 86400);
		} else {
			$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_webhook_logout');
			wp_cache_set('sensfrx_admin_table1_data', $drop_tb, 'sensfrx_group', 86400);
		}
		// $wpdb->query("DROP TABLE IF EXISTS `$sanitized_table_name`");

		$table_name = $wpdb->prefix . 'sensfrx_api_active_and_webhook';
		$sanitized_table_name = esc_sql($table_name);
		$results = wp_cache_get('sensfrx_admin_table1_data', 'sensfrx_group');
		if ($results !== false) {
			$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_api_active_and_webhook');
			wp_cache_replace('sensfrx_admin_table1_data', $drop_tb, 'sensfrx_group', 86400);
		} else {
			$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_api_active_and_webhook');
			wp_cache_set('sensfrx_admin_table1_data', $drop_tb, 'sensfrx_group', 86400);
		}

		$table_name = $wpdb->prefix . 'sensfrx_tab_data';
		$sanitized_table_name = esc_sql($table_name);
		$results = wp_cache_get('sensfrx_admin_table1_data', 'sensfrx_group');
		if ($results !== false) {
			$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_tab_data');
			wp_cache_replace('sensfrx_admin_table1_data', $drop_tb, 'sensfrx_group', 86400);
		} else {
			$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_tab_data');
			wp_cache_set('sensfrx_admin_table1_data', $drop_tb, 'sensfrx_group', 86400);
		}

		$table_name = $wpdb->prefix . 'sensfrx_custome_filed';
		$sanitized_table_name = esc_sql($table_name);
		$results = wp_cache_get('sensfrx_admin_table1_data', 'sensfrx_group');
		if ($results !== false) {
			$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_custome_filed');
			wp_cache_replace('sensfrx_admin_table1_data', $drop_tb, 'sensfrx_group', 86400);
		} else {
			$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_custome_filed');
			wp_cache_set('sensfrx_admin_table1_data', $drop_tb, 'sensfrx_group', 86400);
		}

		// $table_name = $wpdb->prefix . 'sensfrx_real_activity_ato';
		// $sanitized_table_name = esc_sql($table_name);
		// $results = wp_cache_get('sensfrx_admin_table11_data', 'sensfrx_group');
		// if ($results !== false) {
		// 	$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_real_activity_ato');
		// 	wp_cache_replace('sensfrx_admin_table11_data', $drop_tb, 'sensfrx_group', 86400);
		// } else {
		// 	$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_real_activity_ato');
		// 	wp_cache_set('sensfrx_admin_table11_data', $drop_tb, 'sensfrx_group', 86400);
		// }

		// $table_name = $wpdb->prefix . 'sensfrx_real_activity_transaction';
		// $sanitized_table_name = esc_sql($table_name);
		// $results = wp_cache_get('sensfrx_admin_table111_data', 'sensfrx_group');
		// if ($results !== false) {
		// 	$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_real_activity_transaction');
		// 	wp_cache_replace('sensfrx_admin_table111_data', $drop_tb, 'sensfrx_group', 86400);
		// } else {
		// 	$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_real_activity_transaction');
		// 	wp_cache_set('sensfrx_admin_table111_data', $drop_tb, 'sensfrx_group', 86400);
		// }

		// $table_name = $wpdb->prefix . 'sensfrx_real_activity_registration';
		// $sanitized_table_name = esc_sql($table_name);
		// $results = wp_cache_get('sensfrx_admin_table1111_data', 'sensfrx_group');
		// if ($results !== false) {
		// 	$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_real_activity_registration');
		// 	wp_cache_replace('sensfrx_admin_table1111_data', $drop_tb, 'sensfrx_group', 86400);
		// } else {
		// 	$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_real_activity_registration');
		// 	wp_cache_set('sensfrx_admin_table1111_data', $drop_tb, 'sensfrx_group', 86400);
		// }

		$table_name = $wpdb->prefix . 'sensfrx_real_activity';
		$sanitized_table_name = esc_sql($table_name);
		$results = wp_cache_get('sensfrx_admin_table11_data', 'sensfrx_group');
		if ($results !== false) {
			$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_real_activity');
			wp_cache_replace('sensfrx_admin_table11_data', $drop_tb, 'sensfrx_group', 86400);
		} else {
			$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_real_activity');
			wp_cache_set('sensfrx_admin_table11_data', $drop_tb, 'sensfrx_group', 86400);
		}

		$table_name = $wpdb->prefix . 'sensfrx_shadow_activity';
		$sanitized_table_name = esc_sql($table_name);
		$results = wp_cache_get('sensfrx_admin_table11_data', 'sensfrx_group');
		if ($results !== false) {
			$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_shadow_activity');
			wp_cache_replace('sensfrx_admin_table11_data', $drop_tb, 'sensfrx_group', 86400);
		} else {
			$wpdb->query('DROP TABLE IF EXISTS wp_sensfrx_shadow_activity');
			wp_cache_set('sensfrx_admin_table11_data', $drop_tb, 'sensfrx_group', 86400);
		}
	}
}
