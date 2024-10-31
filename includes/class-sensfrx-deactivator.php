<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://sensfrx.ai
 * @since      1.0.0
 *
 * @package    SensFRX
 * @subpackage SensFRX/includes
 */
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    SensFRX
 * @subpackage SensFRX/includes
 */
class SensFRX_Deactivator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
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
		$Deactivation = $obj->uninstallplugininfo($postDataArray);
	}
}
