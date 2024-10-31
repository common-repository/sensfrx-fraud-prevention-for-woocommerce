<?php
/**
 * SensFRX Main Class of App
 *
 */
namespace SensFRX\Bot;

use SensFRX\Common\ApiCalls;
/**
 * Class Bot
 *
 * @package Bot
 */
class Bot {
	private $objAPI;
	public function __construct($objAPI) {
		$this->objAPI = $objAPI;
	}
	public function botLog($deviceId = null, $userId = null) {
		$method = 'POST';
		$url = '/bot';
		$data = array(
			'uID' => $userId,
			'dID' => $deviceId,
		);
		return $this->objAPI->callAPI($method, $url, $data);
	}
}
