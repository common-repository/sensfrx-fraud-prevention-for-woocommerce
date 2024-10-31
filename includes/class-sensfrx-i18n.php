<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://sensfrx.ai
 * @since      1.0.0
 *
 * @package    SensFRX
 * @subpackage SensFRX/includes
 */
/**

 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    SensFRX
 * @subpackage SensFRX/includes
 */

class SensFRXi18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */

	public function load_plugin_textdomain() {

		load_plugin_textdomain(

			'sensfrx_fpwoo',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'

		);
	}
}
