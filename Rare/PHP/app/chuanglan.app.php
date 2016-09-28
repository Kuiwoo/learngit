<?php

/**
 * 鍒涜摑鐭俊鎺ュ彛
 */
class ChuanglanApp {
	const SENDURL = 'http://222.73.117.138:7891/mt';
	const QUERYURL = 'http://222.73.117.138:7891/bi';
	const ISENDURL = 'http://222.73.117.140:8044/mt';
	const IQUERYURL = 'http://222.73.117.140:8044/bi';
	private $_sendUrl = ''; // 鍙戦�佺煭淇℃帴鍙rl
	private $_queryBalanceUrl = ''; // 鏌ヨ浣欓鎺ュ彛url
	private $_un; // 璐﹀彿
	private $_pw; // 瀵嗙爜
	
	/**
	 * 鏋勯�犳柟娉�
	 *
	 * @param string $account
	 *        	鎺ュ彛璐﹀彿
	 * @param string $password
	 *        	鎺ュ彛瀵嗙爜
	 */
	public function __construct($account, $password) {
		$this->_un = $account;
		$this->_pw = $password;
	}
	
	/* ========== 涓氬姟妯″潡 ========== */
	/**
	 * 鐭俊鍙戦��
	 *
	 * @param string $phone
	 *        	鎵嬫満鍙风爜
	 * @param string $content
	 *        	鐭俊鍐呭
	 * @param integer $isreport        	
	 * @return void
	 */
	public function send($phone, $content, $isreport = 0) {
		$requestData = array (
				'un' => $this->_un,
				'pw' => $this->_pw,
				'sm' => $content,
				'da' => $phone,
				'rd' => $isreport,
				'dc' => 15,
				'rf' => 2,
				'tf' => 3 
		);
		
		$url = ChuanglanApp::SENDURL . '?' . http_build_query ( $requestData );
		return $this->_request ( $url );
	}
	
	/**
	 * 鍥介檯鐭俊鍙戦��
	 *
	 * @param string $phone
	 *        	鎵嬫満鍙风爜
	 * @param string $content
	 *        	鐭俊鍐呭
	 * @param integer $isreport        	
	 * @return void
	 */
	public function sendInternational($phone, $content, $isreport = 0) {
		$requestData = array (
				'un' => $this->_un,
				'pw' => $this->_pw,
				'sm' => $content,
				'da' => $phone,
				'rd' => $isreport,
				'rf' => 2,
				'tf' => 3 
		);
		
		$url = ChuanglanApp::ISENDURL . '?' . http_build_query ( $requestData );
		return $this->_request ( $url );
	}
	
	/**
	 * 鏌ヨ浣欓
	 *
	 * @return String 浣欓杩斿洖
	 */
	public function queryBalance() {
		$requestData = array (
				'un' => $this->_un,
				'pw' => $this->_pw,
				'rf' => 2 
		);
		
		$url = ChuanglanApp::QUERYURL . '?' . http_build_query ( $requestData );
		return $this->_request ( $url );
	}
	
	/**
	 * 鏌ヨ浣欓
	 *
	 * @return String 浣欓杩斿洖
	 */
	public function queryBalanceInternational() {
		$requestData = array (
				'un' => $this->_un,
				'pw' => $this->_pw,
				'rf' => 2 
		);
		
		$url = ChuanglanApp::IQUERYURL . '?' . http_build_query ( $requestData );
		return $this->_request ( $url );
	}
	
	/* ========== 涓氬姟妯″潡 ========== */
	
	/* ========== 鍔熻兘妯″潡 ========== */
	/**
	 * 璇锋眰鍙戦��
	 *
	 * @return string 杩斿洖鐘舵�佹姤鍛�
	 */
	private function _request($url) {
		// echo $url;
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_URL, $url );
		$result = curl_exec ( $ch );
		curl_close ( $ch );
		return $result;
	}
	/* ========== 鍔熻兘妯″潡 ========== */
}