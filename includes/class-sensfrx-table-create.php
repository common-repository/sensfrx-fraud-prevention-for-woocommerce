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

class SensFRX_Table_Create {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	public static function table_create() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'sensfrx_sleep'; // Replace 'your_custom_table' with your desired table name
		$charset_collate = $wpdb->get_charset_collate();
		$results = wp_cache_get('sensfrx_table_create_1', 'sensfrx_group');
		if ($results !== false) {
			// Define the SQL query to create the table
			$sql = "CREATE TABLE $table_name (
				id INT NOT NULL AUTO_INCREMENT,
				cid INT NOT NULL,
				c_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (id)
			) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$results = dbDelta( $sql );
			wp_cache_replace('sensfrx_table_create_1', $results, 'sensfrx_group', 86400);
		} else {
			// Define the SQL query to create the table
			$sql = "CREATE TABLE $table_name (
				id INT NOT NULL AUTO_INCREMENT,
				cid INT NOT NULL,
				c_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (id)
			) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$results = dbDelta( $sql );
			wp_cache_set('sensfrx_table_create_1', $results, 'sensfrx_group', 86400);
		}
		// // Define the SQL query to create the table
		// $sql = "CREATE TABLE $table_name (
		// 	id INT NOT NULL AUTO_INCREMENT,
		// 	cid INT NOT NULL,
		// 	c_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		// 	PRIMARY KEY (id)
		// ) $charset_collate;";
		// require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		// dbDelta( $sql );
		$table_name = $wpdb->prefix . 'sensfrx_webhook_logout';
		$charset_collate = $wpdb->get_charset_collate();
		$results = wp_cache_get('sensfrx_table_create_2', 'sensfrx_group');
		if ($results !== false) {
			// Define the SQL query to create the table
			$sql = "CREATE TABLE $table_name (
				id INT NOT NULL AUTO_INCREMENT,
				client_id INTEGER,
				PRIMARY KEY (id)
			) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$results = dbDelta( $sql );
			wp_cache_replace('sensfrx_table_create_2', $results, 'sensfrx_group', 86400);
		} else {
			// Define the SQL query to create the table
			$sql = "CREATE TABLE $table_name (
				id INT NOT NULL AUTO_INCREMENT,
				client_id INTEGER,
				PRIMARY KEY (id)
			) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$results = dbDelta( $sql );
			wp_cache_set('sensfrx_table_create_2', $results, 'sensfrx_group', 86400);
		}
		// // Define the SQL query to create the table
		// $sql = "CREATE TABLE $table_name (
		// 	id INT NOT NULL AUTO_INCREMENT,
		// 	client_id INTEGER,
		// 	PRIMARY KEY (id)
		// ) $charset_collate;";
		// require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		// dbDelta( $sql ); 

		$table_name = $wpdb->prefix . 'sensfrx_api_active_and_webhook';
		$charset_collate = $wpdb->get_charset_collate();
		$results = wp_cache_get('sensfrx_table_create_3', 'sensfrx_group');
		if ($results !== false) {
			$sql = "CREATE TABLE $table_name (
				id INT NOT NULL AUTO_INCREMENT,
				p_id VARCHAR(255) NOT NULL,
				s_key VARCHAR(255) NOT NULL,
				sensfrx_activate VARCHAR(255) NOT NULL,
				sensfrx_webhook VARCHAR(255) NOT NULL,
				PRIMARY KEY (id)
			) $charset_collate;";
		
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		} else {
			// Define the SQL query to create the table
			$sql = "CREATE TABLE $table_name (
				id INT NOT NULL AUTO_INCREMENT,
				p_id VARCHAR(255) NOT NULL,
				s_key VARCHAR(255) NOT NULL,
				sensfrx_activate VARCHAR(255) NOT NULL,
				sensfrx_webhook VARCHAR(255) NOT NULL,
				PRIMARY KEY (id)
			) $charset_collate;";
		
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}

		// --------------------------------------------------------------

		$table_name_1 = $wpdb->prefix . 'sensfrx_tab_data';
		$charset_collate = $wpdb->get_charset_collate();
		$results_1 = wp_cache_get('sensfrx_table_create_1', 'sensfrx_group');

		if ($results_1 !== false) {
			$sql_1 = "CREATE TABLE $table_name_1 (
				id INT NOT NULL AUTO_INCREMENT,
				sensfrx_tab VARCHAR(255) NOT NULL,
				PRIMARY KEY (id)
			) $charset_collate;";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql_1);
		} else {
			$sql_1 = "CREATE TABLE $table_name_1 (
				id INT NOT NULL AUTO_INCREMENT,
				sensfrx_tab VARCHAR(255) NOT NULL,
				PRIMARY KEY (id)
			) $charset_collate;";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql_1);
		}

		$table_name_2 = $wpdb->prefix . 'sensfrx_custome_filed';
		$results_2 = wp_cache_get('sensfrx_table_create_2', 'sensfrx_group');

		if ($results_2 !== false) {
			$sql_2 = "CREATE TABLE $table_name_2 (
				id INT NOT NULL AUTO_INCREMENT,
				sensfrx_custom_field VARCHAR(255) NOT NULL,
				PRIMARY KEY (id)
			) $charset_collate;";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql_2);
		} else {
			$sql_2 = "CREATE TABLE $table_name_2 (
				id INT NOT NULL AUTO_INCREMENT,
				sensfrx_custom_field VARCHAR(255) NOT NULL,
				PRIMARY KEY (id)
			) $charset_collate;";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql_2);
		}

		$table_name = $wpdb->prefix . 'sensfrx_real_activity';
		$charset_collate = $wpdb->get_charset_collate();
		$results = wp_cache_get('sensfrx_table_create_333', 'sensfrx_group');
		if ($results !== false) {
			$sql = "CREATE TABLE $table_name (
				id INT NOT NULL AUTO_INCREMENT,
				sensfrx_log_type VARCHAR(255) NOT NULL,
				sensfrx_log1 VARCHAR(255) NOT NULL,
				created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (id)
			) $charset_collate;";
		
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		} else {
			// Define the SQL query to create the table
			$sql = "CREATE TABLE $table_name (
				id INT NOT NULL AUTO_INCREMENT,
				sensfrx_log_type VARCHAR(255) NOT NULL,
				sensfrx_log1 VARCHAR(255) NOT NULL,
				created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (id)
			) $charset_collate;";
		
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}

		$table_name = $wpdb->prefix . 'sensfrx_shadow_activity';
		$charset_collate = $wpdb->get_charset_collate();
		$results = wp_cache_get('sensfrx_table_create_3334', 'sensfrx_group');
		if ($results !== false) {
			$sql = "CREATE TABLE $table_name (
				id INT NOT NULL AUTO_INCREMENT,
				sensfrx_shadow_status VARCHAR(255) NOT NULL,
				PRIMARY KEY (id)
			) $charset_collate;";
		
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		} else {
			// Define the SQL query to create the table
			$sql = "CREATE TABLE $table_name (
				id INT NOT NULL AUTO_INCREMENT,
				sensfrx_shadow_status VARCHAR(255) NOT NULL,
				PRIMARY KEY (id)
			) $charset_collate;";
		
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}
}
