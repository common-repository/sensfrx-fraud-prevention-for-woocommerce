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
class ResetPasswordLogs {
	private $objAPI;
	public function __construct($objAPI) {
		$this->objAPI = $objAPI;
	}
	public function resetPasswordLog($resetPasswordEvent, $userId = null, $deviceId = null, $userExtras = array()) {
		$method = 'POST';
		$url = '/reset-password';
		$data = array(
			'ev'  => $resetPasswordEvent,
			'uID' => $userId,
			'dID' => $deviceId,
			'uex' => $userExtras
		);
		return $this->objAPI->callAPI($method, $url, $data);
	}
}
