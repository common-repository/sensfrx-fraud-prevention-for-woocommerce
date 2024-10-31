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
 * a.sensfrx.ai
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    SensFRX
 * @subpackage SensFRX/includes
 */

class SensFRX_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$options = get_option('sensfrx_options', SensFRX::default_options());
		require_once(SENSFRX_DIR . 'sensfrx-php-sdk/SensFRX/autoload.php');
		$obj = new SensFRX\SensFRX([
			'property_id' => $options['sensfrx_property_id'],
			'property_secret' => $options['sensfrx_property_secret']
		]);
		global $wp_version;
		$version = $wp_version;
		$site_url = site_url();
		$site_url_without_https = str_replace('https://', '', $site_url);
		$postDataArray = [
			'app_type' => 'Woocommerce Extension',
			'app_version' => $version,
			'domain' => $site_url_without_https,
		];
		$Activation = $obj->integrateplugininfo($postDataArray);
		// Webhook URL 
		$webHookUrl = home_url() . '/wp-json/sensfrx-fraud-prevention-for-woocommerce/sensfrx_webhook';
		$webhook_update =  $obj->addWebHook($webHookUrl);
	}
}
