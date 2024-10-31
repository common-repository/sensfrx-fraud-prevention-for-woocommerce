<?php
/**
 * SensFRX Error Messages Class of App
 *
 */
namespace SensFRX\Common;

use SensFRX\Login\LoginLogs;
/**
 * Class ErrorMessage
 *
 * @package ErrorMessage
 */
class ErrorMessage {
	// error handling class
	public function errormsg($erno) {
		$errorMSG[0] = 'Authorization Error';
		$errorMSG[1] = 'Missing Paramaters';
		$errorMSG[2] = 'Cookies are not supported';
		$errorMSG[3] = 'The URL is invalid';
		if (isset($errorMSG[$erno])) {
			return $errorMSG[$erno];
		} else {
			return 'There was some error';
		}
	}
}
