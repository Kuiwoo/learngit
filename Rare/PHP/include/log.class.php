<?php

/**
 * Created by PhpStorm.
 * User: 41023
 * Date: 2015/12/20
 * Time: 19:56
 */
class Log {
	var $logroot = './data/log/';
	var $date;
	var $filename;
	var $sign;
	function __construct() {
		$this->sign = $this->sign ();
		$this->date = date ( "Y-m-d" );
		$this->filename = $this->logroot . $this->date . '.txt';
	}
	public function write($text) {
		$time = date ( 'Y-m-d h:i:s', time () );
		$text = htmlspecialchars ( $text );
		file_put_contents ( $this->filename, $text . " time : " . $time . " sign : " . $this->sign . "\r\n", FILE_APPEND | LOCK_EX );
	}
	public function sign() {
		$sign = '';
		$sign .= ! empty ( $_SESSION ['user'] ['user_id'] ) ? $_SESSION ['user'] ['user_id'] : '';
		$sign .= " url: " . $_SERVER ['REQUEST_URI'];
		if (isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] )) {
			$sign .= " ip :" . $_SERVER ['HTTP_X_FORWARDED_FOR'];
		} else {
			$sign .= " ip :" . $_SERVER ['REMOTE_ADDR'];
		}
		
		return $sign;
	}
}
?>