<?php
include ('userbase.app.php');
class HtmlApp extends userbaseApp {
	function index() {
		if ($this->user->login === 0) {
			header ( 'Location: index.php?app=login&act=index' );
			exit ();
		}
		
		display ( 'exhibition.html' );
	}
	function userInfo() {
		if ($this->user->login === 0) {
			apiOutPut ( false );
		}
		if (isGet ()) {
			$id = $this->user->userid;
			$info = $this->getuserinfo ( $id );
			apiOutPut ( $info );
		}
	}
}