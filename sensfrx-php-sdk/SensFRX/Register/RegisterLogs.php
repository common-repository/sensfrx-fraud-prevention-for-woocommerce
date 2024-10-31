<?php
/**
 * SensFRX Main Class of App
 *
 */
namespace SensFRX\Register;

use SensFRX\Common\ApiCalls;
/**
 * Class Register
 *
 * @package Register
 */
class RegisterLogs {
	private $objAPI;
	public function __construct($objAPI) {
		$this->objAPI = $objAPI;
	}
	public function registerLog($registerEvent, $deviceId = null, $registerFields = array()) {
		$method = 'POST';
		$url = '/register';
		$data = array(
			'ev'  => $registerEvent,
			'dID' => $deviceId,
			'rfs' => $registerFields
		);
		return $this->objAPI->callAPI($method, $url, $data);
	}
}
