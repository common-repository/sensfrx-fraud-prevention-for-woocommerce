<?php
/**
 * SensFRX Main Class of App
 *
 */
namespace SensFRX\Privacy;

use SensFRX\Common\ApiCalls;
/**
 * Class Privacy
 *
 * @package Privacy
 */
class Privacy {
	private $objAPI;
	public function __construct($objAPI) {
		$this->objAPI = $objAPI;
	}
	public function getPrivacy() {
		$method = 'GET';
		$url = '/privacy';
		return $this->objAPI->callAPI($method, $url);
	}
	public function postPrivacy($privacyInfo) {
		$method = 'POST';
		$url = '/privacy';
		return $this->objAPI->callAPI($method, $url, $privacyInfo);
	}
}
