<?php
/**
 * SensFRX Main Class of App
 *
 */
namespace SensFRX\WebHook;

use SensFRX\Common\ApiCalls;
/**
 * Class WebHook
 *
 * @package WebHook
 */
class WebHook {
	private $objAPI;
	public function __construct($objAPI) {
		$this->objAPI = $objAPI;
	}
	public function addWebHook($webhookURL) {
		$method = 'POST';
		$url = '/webhooks';
		$data = array(
			'url'  => $webhookURL
		);
		return $this->objAPI->callAPI($method, $url, $data);
	}
}
