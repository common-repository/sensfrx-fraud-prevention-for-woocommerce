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

class SensFRX_table_exist {

	public static function sensfrx_table_check($tb) {

		global $wpdb;
		$tb_cache = wp_cache_get('sensfrx_admin_data', 'sensfrx_group');

		if ($tb_cache !== false) {
			$table_name_exist = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $tb));
			wp_cache_replace('sensfrx_admin_data', $table_name_exist, 'sensfrx_group', 86400);
			return $table_name_exist;
		} else {
			$table_name_exist = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $tb));
			wp_cache_set('sensfrx_admin_data', $table_name_exist, 'sensfrx_group', 86400);
			return $table_name_exist;
		}
	
	}

}