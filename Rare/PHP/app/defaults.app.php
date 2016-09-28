<?php

/**
 * Created by PhpStorm.
 * User: 41023
 * Date: 2015/12/20
 * Time: 20:51
 */
include ('userbase.app.php');
class DefaultsApp extends userbaseApp {
	function index() {
		if ($this->user->login === 1) {
			header ( 'Location: index.php?app=user&act=index' );
		} else {
			header ( 'Location: index.php?app=login&act=index' );
		}
	}
}