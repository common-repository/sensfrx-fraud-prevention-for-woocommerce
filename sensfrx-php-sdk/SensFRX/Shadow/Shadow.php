<?php
/**
 * SensFRX Main Class of App
 *
 */
namespace SensFRX\Shadow;

use SensFRX\Common\ApiCalls;
/**
 * Class Shadow
 *
 * @package Shadow
 */
class Shadow {
	private $objAPI;
	public function __construct($objAPI) {
		$this->objAPI = $objAPI;
	}
	public function getShadow() {
		$method = 'GET';
		$url = '/shadow';
		return $this->objAPI->callAPI($method, $url);
	}
	public function postShadow($shadowInfo) {
		$method = 'POST';
		$url = '/shadow';
		return $this->objAPI->callAPI($method, $url, $shadowInfo);
	}
}
