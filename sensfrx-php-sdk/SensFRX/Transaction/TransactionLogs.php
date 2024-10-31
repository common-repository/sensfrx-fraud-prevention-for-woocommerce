<?php
/**
 * SensFRX Main Class of App
 *
 */
namespace SensFRX\Transaction;

use SensFRX\Common\ApiCalls;
/**
 * Class Transaction
 *
 * @package Transaction
 */
class TransactionLogs {
	private $objAPI;
	public function __construct($objAPI) {
		$this->objAPI = $objAPI;
	}
	public function transactionLog($transactionEvent, $deviceId = null, $transactionFields = array()) {
		$method = 'POST';
		$url = '/transaction';
		$data = array(
			'ev'  => $transactionEvent,
			'dID' => $deviceId,
			'tfs' => $transactionFields
		);
		return $this->objAPI->callAPI($method, $url, $data);
	}
}
