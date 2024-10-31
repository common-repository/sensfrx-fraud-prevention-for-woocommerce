<?php
/**
 * This is a short description of the package.
 * 
 * @package           Sensfrx
 *
 * Plugin Name:       Sensfrx - Fraud Prevention for WooCommerce
 * Plugin URI:        https://sensfrx.ai
 * Description:       Prevent your WooCommerce store from fraudulent activities with Sensfrx. Seamlessly integrate advanced fraud detection and prevention capabilities into your online store to secure your business and customers.
 * Version:           1.0.10
 * Author:            Sensfrx
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html  
 * Text Domain:       
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Requires Plugins:  woocommerce  
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'SENSFRX_VERSION', '1.0.10' );
// Activation and deactivation hooks
register_activation_hook(__FILE__, 'SensFRX_activate');
register_deactivation_hook(__FILE__, 'SensFRX_deactivate');
register_activation_hook( __FILE__, 'sensfrx_table_create' );
register_deactivation_hook( __FILE__, 'sensfrx_table_delete' );
function SensFRX_activate() {     					// The code that runs during plugin activation.
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sensfrx-activator.php';
	SensFRX_Activator::activate();
	sensfrx_plugin_activation_callback();
}
function SensFRX_deactivate() {   					// The code that runs during plugin deactivation.
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sensfrx-deactivator.php';
	SensFRX_Deactivator::deactivate();
	wp_clear_scheduled_hook('sensfrx_interval_cron_event');
}
function sensfrx_table_create() {        			// Table Insertion which sensfrx require for custom functionality.
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sensfrx-table-create.php';
	SensFRX_Table_Create::table_create();
}
function sensfrx_table_delete() {					// Table deletion which sensfrx utilized.
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sensfrx-table-delete.php';
	SensFRX_Table_Delete::table_delete();
}
require plugin_dir_path( __FILE__ ) . 'includes/class-sensfrx.php';
/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function SensFRX_run() {
	$plugin = new SensFRX();
	$plugin->run();
	if (!defined('SENSFRX_URL')) { 
		define('SENSFRX_URL', plugin_dir_url(__FILE__));
	}
	if (!defined('SENSFRX_DIR')) {
		define('SENSFRX_DIR', plugin_dir_path(__FILE__));
	}
	if (!defined('SENSFRX_FILE')) {
		define('SENSFRX_FILE', plugin_basename(__FILE__));
	}
	if (!defined('SENSFRX_SLUG')) { 
		define('SENSFRX_SLUG', basename(dirname(__FILE__)));
	}
	if (!defined('SENSFRX_PIXEL_URL')) { 
		define('SENSFRX_PIXEL_URL', 'https://p.sensfrx.ai/as.js?p=');
	}
}

SensFRX_run();

function sensfrx_plugin_activation_callback() {                             
	if (get_option('SensFRX_active', true)) {                        
		update_option('SensFRX_active', false);               
		set_transient('Sensfrx_redirect_to_integration_page', true, 30);
	}
}

function sensfrx_plugin_check_redirection() {    
	function my_custom_plugin_woocommerce_notice() {
		?>
		<div class="notice notice-error">
			<p><?php echo '<b style="color: blue;">Sensfrx - Fraud Prevention for WooCommerce</b><b style="color: red;"> requires WooCommerce to be installed and active.</b>'; ?></p>
		</div>
		<?php
	}	
	if (!is_plugin_active('woocommerce/woocommerce.php')) {
			add_action('admin_notices', 'my_custom_plugin_woocommerce_notice');
	}
	if (get_transient('Sensfrx_redirect_to_integration_page')) {
		delete_transient('Sensfrx_redirect_to_integration_page');                            // Delete the transient to prevent further redirections
		wp_safe_redirect(admin_url('admin.php?page=sensfrx_integration'));        // Redirect to the specified URL after plugin activation
	}
	
}

add_action('admin_init', 'sensfrx_plugin_check_redirection');

// Cronjob setup 
// Step 1: Add a custom schedule for every minute
add_filter('cron_schedules', 'sensfrx_add_every_minute_schedule');
function sensfrx_add_every_minute_schedule($schedules) {
    $schedules['every_minute'] = array(
        'interval' => 60, // 60 seconds = 1 minute
        'display'  => __('Every Minute'),
    );
    return $schedules;
}
// Step 2: Schedule the cron event if it's not already scheduled
add_action('wp', 'sensfrx_interval_cron_event');
function sensfrx_interval_cron_event() {
    if (!wp_next_scheduled('sensfrx_cron_task')) {
        wp_schedule_event(time(), 'every_minute', 'sensfrx_cron_task'); // Hook the event to our custom interval
    }
}
// Step 3: Define what happens when the event is triggered
add_action('sensfrx_cron_task', 'run_sensfrx_cron_task');
function run_sensfrx_cron_task() {
	$current_time = time();
	$last_run_time = get_option('sensfrx_interval_last_run_time', 0);
	if (($current_time - $last_run_time) >= 5) {
		update_option('sensfrx_interval_last_run_time', $current_time);
		$log_message = date('Y-m-d H:i:s');
		$logs = get_option('sensfrx_cron_logs', []);
		$logs[] = $log_message;
		if (count($logs) > 20) {
			array_shift($logs);  
		}
		update_option('sensfrx_cron_logs', $logs);
	}
}
// Step 3: update the plugin that time it inserted 
// add_filter( 'upgrader_process_complete', 'sensfrx_interval_update' );
// function sensfrx_interval_update() {
// 	sensfrx_interval_cron_event();
// }  

