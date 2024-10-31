<?php
/**
 * SensFRX API Calls Class of App
 *
 */
namespace SensFRX\Common;

/**
 * Class ApiCalls
 *
 * @package ApiCalls
 */
class ApiCalls {
	private $property_id;
	private $property_secret;
	private $apiURL;
	public function __construct($property_id,$property_secret,$app_url) {
		$this->property_id = $property_id;
		$this->property_secret = $property_secret;
		$this->apiURL = $app_url;
	}
	private function getAPIUrl($path) {
		return $this->apiURL . $path;
		
	}
	public function callAPI ($method, $url, $data=[]) {
		try {
			$headers = array();
			$headers['ip'] = $this->get_client_ip();
			if (isset($_SERVER['HTTP_USER_AGENT'])) {
				$headers['ua'] = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
			} else {
				$headers['ua'] = "";
			}
			if (isset($_SERVER['HTTP_HOST'])) {
				$headers['ho'] = sanitize_text_field($_SERVER['HTTP_HOST']);
			} else {
				$headers['ho'] = "";
			}
			// $headers['ua'] = $_SERVER['HTTP_USER_AGENT'];
			// $headers['ho'] = $_SERVER['HTTP_HOST'];
			if (isset($_SERVER['HTTP_REFERER'])) {
				$headers['rf'] = sanitize_text_field($_SERVER['HTTP_REFERER']);
			} else {
				$headers['rf'] = "";
			}
			$headers['ac'] = isset($_SERVER['HTTP_ACCEPT_CHARSET'])?sanitize_text_field($_SERVER['HTTP_ACCEPT_CHARSET']):''; // $this->getAcceptString($_SERVER);
			$headers['url'] = sanitize_url($this->getPageUrl($_SERVER));
			//$headers['head'] = getallheaders();
			// echo '<pre>';
			// print_r($headers);
			// die();
			$data['h'] = $headers;
			$url = $this->getAPIUrl($url);
			$apiKey = base64_encode($this->property_id . ':' . $this->property_secret);
			$headers = [
				'authorization' => 'Basic ' . $apiKey,
				'content-type' => 'application/json'
			];
			// echo 'URL = '. $url;
			// echo '<br>';
			// echo 'Header : ';
			// echo '<pre>';
			// print_r($headers);
			// echo '<br>';
			// echo 'Data :';
			// print_r($data);
			// die();
			if ( 'POST' == $method ) {
				$jsonData = wp_json_encode($data);
				$args = [
					'method'  => $method,
					'timeout'     => 20,
					'headers' => $headers,
					'body'    => $jsonData,
				];
				$response = wp_remote_post($url, $args);
			} else if ( 'GET' == $method ) {
				$jsonData = wp_json_encode($data);
				$args = [
					'method'  => $method,
					'timeout'     => 20,
					'headers' => $headers,
				];
				$url = $url . '?' . $jsonData;
				$response = wp_remote_get($url, $args);
			} 
			if (is_wp_error($response)) {
				$error_message = $response->get_error_message();
				echo 'Something went wrong: ' . esc_html($error_message);
			} else {
				$http_code = wp_remote_retrieve_response_code($response);
				if (200 === $http_code) {
					$return_res = array();
					$return_res = $response['body'];
					$return_res = json_decode($return_res);
					$return_res = (array) $return_res;
					return $return_res;
				} else {
					return 'HTTP CODE ERROR ' . $http_code;
				}
			}
		} catch (Exception $e) {
			echo 'Message: ' . esc_html($e->getMessage());
		}
	}
	// Function to get the client IP address
	public function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP')) {
			$ipaddress = getenv('HTTP_CLIENT_IP');
		} else if (getenv('HTTP_X_FORWARDED_FOR')) {
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		} else if (getenv('HTTP_X_FORWARDED')) {
			$ipaddress = getenv('HTTP_X_FORWARDED');
		} else if (getenv('HTTP_FORWARDED_FOR')) {
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		} else if (getenv('HTTP_FORWARDED')) {
			$ipaddress = getenv('HTTP_FORWARDED');
		} else if (getenv('REMOTE_ADDR')) {
			$ipaddress = getenv('REMOTE_ADDR');
		} else {
			$ipaddress = 'UNKNOWN';
		}
		return $ipaddress;
	}
	private function getPageOrigin( $srv, $use_forwarded_host = false ) {
		// check if SSL
		$ssl      = ( !empty($srv['HTTPS']) && 'on' == $srv['HTTPS'] );
		$srvp     = strtolower( $srv['SERVER_PROTOCOL'] );
		$srvpro   = substr( $srvp, 0, strpos( $srvp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
		if (isset($_SERVER['SERVER_PORT'])) {
			$port = sanitize_text_field($_SERVER['SERVER_PORT']);
		} else {
			$port = ''; 
		}
		$serverName = isset($_SERVER['SERVER_NAME']) ? sanitize_text_field($_SERVER['SERVER_NAME']) : ''; // Replace 'default_server_name' with a relevant default value
		$port     = ( ( !$ssl && '80' == $port ) || ( $ssl && '443' == $port ) ) ? '' : ':' . $port;
		$host     = ( $use_forwarded_host && isset( $srv['HTTP_X_FORWARDED_HOST'] ) ) ? $srv['HTTP_X_FORWARDED_HOST'] : ( isset( $srv['HTTP_HOST'] ) ? $srv['HTTP_HOST'] : '' );
		$host     = isset( $host ) ? $host : $serverName . $port;
		return $srvpro . '://' . $host;
	}
	private function getPageUrl( $srv, $use_forwarded_host = false ) {
		return $this->getPageOrigin( $srv, $use_forwarded_host ) . $srv['REQUEST_URI'];
	}
	public function getAcceptString($srv) {
		$retData = array();
		if (isset($srv['HTTP_ACCEPT'])) {
			$retData['a'] = isset($_SERVER['HTTP_ACCEPT']) ? sanitize_text_field($_SERVER['HTTP_ACCEPT']) : '';
		} else {
			$retData['a'] = "Index 'HTTP_ACCEPT' is not set in \$_SERVER.";
		}
		if (isset($srv['HTTP_ACCEPT_CHARSET'])) {
			$retData['ac'] = isset($_SERVER['HTTP_ACCEPT_CHARSET']) ? sanitize_text_field($_SERVER['HTTP_ACCEPT_CHARSET']) : '';
		} else {
			$retData['ac'] = "Index 'HTTP_ACCEPT_CHARSET' is not set \$_SERVER.";
		}
		if (isset($srv['HTTP_ACCEPT_ENCODING'])) {
			$retData['ae'] = isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? sanitize_text_field($_SERVER['HTTP_ACCEPT_ENCODING']) : '';
		} else {
			$retData['ae'] = "Index 'HTTP_ACCEPT_ENCODING' is not set \$_SERVER.";
		}
		if (isset($srv['HTTP_ACCEPT_LANGUAGE'])) {
			$retData['al'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? sanitize_text_field($_SERVER['HTTP_ACCEPT_LANGUAGE']) : '';
		} else {
			$retData['al'] = "Index 'HTTP_ACCEPT_LANGUAGE' is not set \$_SERVER.";
		}
		return $retData;
	}
}
