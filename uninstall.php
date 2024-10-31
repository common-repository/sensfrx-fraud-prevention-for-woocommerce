<?php
/**
 * 
 * Fired when the plugin is uninstalled.
 *
 * @link       https://sensfrx.ai
 * @since      1.0.0
 *
 * @package    SensFRX
 */
// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
// delete options
delete_option('sensfrx_options');
delete_option('sensfrx_policy_options');
