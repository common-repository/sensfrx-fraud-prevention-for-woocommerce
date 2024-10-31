<?php
/**
 * SensFRX Main Class of App
 *
 */
namespace SensFRX\Alerts;

use SensFRX\Common\ApiCalls;
/**
 * Class Alerts
 *
 * @package Alerts
 */
class Alerts {
	private $objAPI;
	public function __construct($objAPI) {
		$this->objAPI = $objAPI;
	}
	public function getAlerts() {
		$method = 'GET';
		$url = '/alerts';
		return $this->objAPI->callAPI($method, $url);
	}
	public function postAlerts($alertsInfo) {
		$method = 'POST';
		$url = '/alerts';
		return $this->objAPI->callAPI($method, $url, $alertsInfo);
	}
}
