<?php
/**
 * SensFRX Main Class of App
 *
 */
namespace SensFRX\License;

use SensFRX\Common\ApiCalls;
/**
 * Class License
 *
 * @package License
 */
class License {
	private $objAPI;
	public function __construct($objAPI) {
		$this->objAPI = $objAPI;
	}

	public function getLicense() {
		$method = 'GET';
		$url = '/license';
		return $this->objAPI->callAPI($method, $url);
	}
}
