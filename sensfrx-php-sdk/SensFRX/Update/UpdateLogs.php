<?php
/**
 * SensFRX Main Class of App
 *
 */
namespace SensFRX\Update;

use SensFRX\Common\ApiCalls;
/**
 * Class Update
 *
 * @package Update
 */
class UpdateLogs {
	private $objAPI;
	public function __construct($objAPI) {
		$this->objAPI = $objAPI;
	}
	public function updateLog($updateEvent, $userId = null, $deviceId = null, $userExtras = array()) {
		$method = 'POST';
		$url = '/update-profile';
		$data = array(
			'ev'  => $updateEvent,
			'uID' => $userId,
			'dID' => $deviceId,
			'uex' => $userExtras
		);
		return $this->objAPI->callAPI($method, $url, $data);
	}
}
