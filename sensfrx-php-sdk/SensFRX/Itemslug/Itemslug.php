<?php
/**
 * SensFRX Main Class of App
 *
 */
namespace SensFRX\Itemslug;

use SensFRX\Common\ApiCalls;
/**
 * Class Item_slug
 *
 * @package Item_slug
 */
class Itemslug {
	private $objAPI;
	public function __construct($objAPI) {
		$this->objAPI = $objAPI;
	}
	public function getItem_slug() {
		$method = 'GET';
		$url = '/item-slugs';
		return $this->objAPI->callAPI($method, $url);
	}
}
