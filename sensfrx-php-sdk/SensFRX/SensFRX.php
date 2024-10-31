<?php
/**
 *  SensFRX Main Class of App
 *
 */
namespace SensFRX;

use SensFRX\Login\LoginLogs;
use SensFRX\Transaction\TransactionLogs;
use SensFRX\Login\ResetPasswordLogs;
use SensFRX\Update\UpdateLogs;
use SensFRX\Register\RegisterLogs;
use SensFRX\Common\ApiCalls;
use SensFRX\Common\ErrorMessage;
use SensFRX\Device\DeviceManage;
use SensFRX\Profile\Profile;
use SensFRX\Privacy\Privacy;
use SensFRX\Plugin\Plugin;
use SensFRX\Shadow\Shadow;
use SensFRX\Itemslug\Itemslug;
use SensFRX\TransReview\TransReview;
use SensFRX\Stats\Stats;
use SensFRX\Alerts\Alerts;
use SensFRX\License\License;
use SensFRX\Rules\Rules;
use SensFRX\Bot\Bot;
use SensFRX\WebHook\WebHook;
/**
 * Class SensFRX
 *
 * @package SensFRX
 */
class SensFRX {
	/**
	 * Version number of the SensFRX PHP SDK.
	 * 
	 * @const string Version number of the SensFRX PHP SDK.
	 */
	const VERSION = '1.2.0';
	/**
	 * App ID of the SensFRX PHP SDK.
	 * 
	 * @const string The name of the environment variable that contains the app ID.
	 */
	const APP_ID_ENV_NAME = 'AUTHSAFE_APP_ID';
	/**
	 * App Secret of the SensFRX PHP SDK.
	 * 
	 * @const string The name of the environment variable that contains the app secret.
	 */
	const APP_SECRET_ENV_NAME = 'AUTHSAFE_APP_SECRET';
	/**
	 * API Url of the SensFRX PHP SDK.
	 * 
	 * @const string The name of the environment variable that contains the app secret.
	 */
	//const APP_API_URL = 'https://pixel.sensfrx.ai';
	/**
	 * SensFRXApp of the SensFRX PHP SDK.
	 * 
	 * @var SensFRXApp The SensFRXApp entity.
	 */
	protected $app;
	/**
	 * Instantiates a new SensFRX super-class object.
	 *
	 * @param array $config
	 *
	 * @throws SensFRXSDKException
	 */
	public $loginLog;
	public $transactionLog;
	public $resetPasswordLog;
	public $updateLog;
	public $registerLog;
	public $ProfileObj;
	public $PrivacyObj;
	public $PluginObj;
	public $ShadowObj;
	public $ItemslugObj;
	public $TransReviewObj;
	public $StatsObj;
	public $AlertsObj;
	public $LicenseObj;
	public $RulesObj;
	public $botLog;
	public $deviceManage;
	public $webHook;
	public $objAPI;
	public $apiUrl;
	public function __construct(array $config = []) {
		$msg = array();
		$a = new ErrorMessage();
		$this->apiUrl = 'https://a.sensfrx.ai/v1';
		$config = array_merge([
			'property_id' => getenv(static::APP_ID_ENV_NAME),
			'property_secret' => getenv(static::APP_SECRET_ENV_NAME),
			'api_url' => $this->apiUrl
		], $config);
		$this->objAPI = new ApiCalls($config['property_id'], $config['property_secret'], $config['api_url']);
		$this->loginLog = new LoginLogs($this->objAPI);
		$this->transactionLog = new TransactionLogs($this->objAPI);
		$this->resetPasswordLog = new ResetPasswordLogs($this->objAPI);
		$this->updateLog = new UpdateLogs($this->objAPI);
		$this->registerLog = new RegisterLogs($this->objAPI);
		$this->ProfileObj = new Profile($this->objAPI);
		$this->PrivacyObj = new Privacy($this->objAPI);
		$this->PluginObj = new Plugin($this->objAPI);
		$this->ShadowObj = new Shadow($this->objAPI);
		$this->ItemslugObj = new Itemslug($this->objAPI);
		$this->TransReviewObj = new TransReview($this->objAPI);
		$this->StatsObj = new Stats($this->objAPI);
		$this->AlertsObj = new Alerts($this->objAPI);
		$this->LicenseObj = new License($this->objAPI);
		$this->RulesObj = new Rules($this->objAPI);
		$this->botLog = new Bot($this->objAPI);
		$this->deviceManage = new DeviceManage($this->objAPI);
		$this->webHook = new WebHook($this->objAPI);
		if (empty($config['property_id']) || !isset($config['property_id'])) {
			$msg['status'] = 'error';
			$erno = 0;
			$msg['message'] = $a->errormsg($erno);
			return $msg;
		}
		if (empty($config['property_secret']) || !isset($config['property_secret'])) {
			$msg['status'] = 'error';
			$erno = 0;
			$msg['message'] = $a->errormsg($erno);
			return $msg;
		}
	}
	public function loginAttempt($loginEvent,$userId = null,$deviceId = null,$userExtras = array()) {
		$b = new ErrorMessage();
		if (empty($loginEvent) || !isset($loginEvent)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		if ( ( 'login_success' == $loginEvent ) && ( empty($userId) || !isset($userId) ) ) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->loginLog->loginLog($loginEvent, $userId, $deviceId, $userExtras);
	}
	public function transactionAttempt($transactionEvent,$deviceId = null,$transactionExtras = array()) {
		$b = new ErrorMessage();
		if (empty($transactionEvent) || !isset($transactionEvent)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->transactionLog->transactionLog($transactionEvent, $deviceId, $transactionExtras);
	}
	public function registerAttempt($registerEvent,$deviceId = null,$registerFields = array()) {
		$b = new ErrorMessage();
		if (empty($registerEvent) || !isset($registerEvent)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		if (count($registerFields) == 0) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->registerLog->registerLog($registerEvent, $deviceId, $registerFields);
	}
	public function passwordResetAttempt($resetPasswordEvent,$userId = null,$deviceId = null,$userExtras = array()) {
		$b = new ErrorMessage();
		if (empty($resetPasswordEvent) || !isset($resetPasswordEvent)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		if ( ( 'reset_password_success' == $resetPasswordEvent ) && ( empty($userId) || !isset($userId) ) ) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->resetPasswordLog->resetPasswordLog($resetPasswordEvent, $userId, $deviceId, $userExtras);
	}
	public function updateAttempt($updateEvent,$userId = null,$deviceId = null,$userExtras = array()) {
		$b = new ErrorMessage();
		if (empty($updateEvent) || !isset($updateEvent)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->updateLog->updateLog($updateEvent, $userId, $deviceId, $userExtras);
	}
	public function approveDevice($deviceId) {
		$b = new ErrorMessage();
		if (empty($deviceId) || !isset($deviceId) ) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->deviceManage->deviceManage($deviceId, 'approve');
	}
	public function denyDevice($deviceId) {
		$b = new ErrorMessage();
		if (empty($deviceId) || !isset($deviceId)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->deviceManage->deviceManage($deviceId, 'deny');
	}
	public function getUserDevices($userId) {
		$b = new ErrorMessage();
		if (empty($userId) || !isset($userId)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->deviceManage->getUserDevices($userId);
	}
	public function addWebHook($url) {
		$b = new ErrorMessage();
		if (empty($url) || !isset($url)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			$msg['status'] = 'error';
			$erno = 3;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->webHook->addWebHook($url);
	}

	public function getprofileinfo() {
		return $this->ProfileObj->getProfile();
	}

	public function postprofileinfo($EditFields = array()) {
		$b = new ErrorMessage();
		if (empty($EditFields) || !isset($EditFields)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		if (count($EditFields) == 0) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->ProfileObj->postProfile($EditFields);
	}
	public function getalertsinfo() {
		return $this->AlertsObj->getAlerts();
	}
	public function postalertsinfo($EditFields = array()) {
		$b = new ErrorMessage();
		if (empty($EditFields) || !isset($EditFields)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		if (count($EditFields) == 0) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->AlertsObj->postAlerts($EditFields);
	}
	public function getprivacyinfo() {
		return $this->PrivacyObj->getPrivacy();
	}
	public function postprivacyinfo($EditFields = array()) {
		$b = new ErrorMessage();
		if (empty($EditFields) || !isset($EditFields)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		if (count($EditFields) == 0) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->PrivacyObj->postPrivacy($EditFields);
	}
	public function integrateplugininfo($Fields = array()) {
		$b = new ErrorMessage();
		if (empty($Fields) || !isset($Fields)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		if (count($Fields) == 0) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->PluginObj->integratePlugin($Fields);
	}
	public function uninstallplugininfo($Fields = array()) {
		$b = new ErrorMessage();
		if (empty($Fields) || !isset($Fields)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		if (count($Fields) == 0) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->PluginObj->uninstallPlugin($Fields);
	}
	public function getItem_sluginfo() {
		return $this->ItemslugObj->getItem_slug();
	}
	public function getTrans_Review() {
		return $this->TransReviewObj->getTransReview();
	}
	public function postTrans_Review($trans_review_fields) {
		$b = new ErrorMessage();
		if (empty($trans_review_fields) || !isset($trans_review_fields)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		if (count($trans_review_fields) == 0) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		} 
		return $this->TransReviewObj->postTransReview($trans_review_fields);
	}
	public function getshadowinfo() {
		return $this->ShadowObj->getShadow();
	}
	public function postshadowinfo($EditFields = array()) {
		$b = new ErrorMessage();
		if (empty($EditFields) || !isset($EditFields)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		if (count($EditFields) == 0) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->ShadowObj->postShadow($EditFields);
	}
	public function getlicenseinfo() {
		return $this->LicenseObj->getLicense();
	}
	public function getrulesinfo() {
		return $this->RulesObj->getRules();
	}
	public function postrulesinfo($EditFields = array()) {
		$b = new ErrorMessage();
		if (empty($EditFields) || !isset($EditFields)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		if (count($EditFields) == 0) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->RulesObj->postRules($EditFields);
	}
	public function isBot($deviceId = null, $userId = null) {
		$b = new ErrorMessage();
		if (empty($deviceId) || !isset($deviceId)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		} 
		return $this->botLog->botLog($deviceId, $userId);
	}
	public function getAtoStatsinfo($DateFilter = array()) {
		$b = new ErrorMessage();
		if (empty($DateFilter) || !isset($DateFilter)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		if (count($DateFilter) == 0) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->StatsObj->getAtoStats($DateFilter);
	}
	public function getTransStatsinfo($DateFilter = array()) {
		$b = new ErrorMessage();
		if (empty($DateFilter) || !isset($DateFilter)) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		if (count($DateFilter) == 0) {
			$msg['status'] = 'error';
			$erno = 1;
			$msg['message'] = $b->errormsg($erno);
			return $msg;
		}
		return $this->StatsObj->getTransStats($DateFilter);
	}
}
