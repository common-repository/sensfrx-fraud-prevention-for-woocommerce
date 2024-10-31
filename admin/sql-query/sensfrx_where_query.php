<?php
/**
 * The admin-specific functionality of the SensFRX plugin.
 *
 * @link       https://sensfrx.ai
 * @since      1.0.0
 *
 * @package    SensFRX
 * @subpackage SensFRX/admin
 * 
 */

class SensFRX_Where_Query {

	public static function sensfrx_where_query($tb) {
		global $wpdb;
		$table_exists = wp_cache_get('sensfrx_admin_data_webhook_check', 'sensfrx_group');
		if ($table_exists !== false) {
			$results = $wpdb->get_results($wpdb->prepare( 'SELECT * FROM wp_sensfrx_webhook_logout WHERE client_id = %d', $tb ));
			wp_cache_replace('sensfrx_admin_data_webhook_check', $table_exists, 'sensfrx_group', 86400);
			return $results;
		} else {
			$results = $wpdb->get_results($wpdb->prepare( 'SELECT * FROM wp_sensfrx_webhook_logout WHERE client_id = %d', $tb ));
			wp_cache_set('sensfrx_admin_data_webhook_check', $table_exists, 'sensfrx_group', 86400);
			return $results;
		}	
	}
}