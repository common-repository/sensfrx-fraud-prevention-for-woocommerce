<?php
/**
 * SensFRX Main Class of App
 *
 */
namespace SensFRX\TransReview;

use SensFRX\Common\ApiCalls;
/**
 * Class License
 *
 * @package License
 */
class TransReview {
	private $objAPI;
	public function __construct($objAPI) {
		$this->objAPI = $objAPI;
	}

	public function getTransReview() {
		$method = 'GET';
		$url = '/trans-review';
		return $this->objAPI->callAPI($method, $url);
	}

	public function postTransReview($transReviewInfo) {
		$method = 'POST';
		$url = '/trans-review';
		return $this->objAPI->callAPI($method, $url, $transReviewInfo);
	}
}
