<?php
/**
 * SensFRX Main Class of App
 *
 */
namespace SensFRX\Plugin;

use SensFRX\Common\ApiCalls;
/**
 * Class Plugin
 *
 * @package Plugin
 */
class Plugin {
	private $objAPI;
	public function __construct($objAPI) {
		$this->objAPI = $objAPI;
	}
	public function integratePlugin($pluginInfo) {
		$method = 'POST';
		$url = '/plugin-integrate';
		return $this->objAPI->callAPI($method, $url, $pluginInfo);
	}
	public function uninstallPlugin($pluginInfo) {
		$method = 'POST';
		$url = '/plugin-uninstall';
		return $this->objAPI->callAPI($method, $url, $pluginInfo);
	}
}
