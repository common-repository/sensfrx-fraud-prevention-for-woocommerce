<?php
/**
 * SensFRX Main Class of App
 *
 */
namespace SensFRX\Login;

use SensFRX\Common\ApiCalls;
/**
 * Class Login
 *
 * @package Login
 */
class LoginLogs {
	private $objAPI;
	public function __construct($objAPI) {
		$this->objAPI = $objAPI;
	}
	public function loginLog($loginEvent, $userId = null, $deviceId = null, $userExtras = array()) {
		$method = 'POST';
		$url = '/login';
		$data = array(
			'ev'  => $loginEvent,
			'uID' => $userId,
			'dID' => $deviceId,
			'uex' => $userExtras
		);
		return $this->objAPI->callAPI($method, $url, $data);
	}
}
