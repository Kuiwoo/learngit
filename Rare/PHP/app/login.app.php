<?php
include "userbase.app.php";
class LoginApp extends userbaseApp {
	function index()
	{
		display('login.html');
	}
	function checkusername() {
		if (empty ( $_GET ['username'] )) {
			apiOutPut ( false );
		}
		$mode = &m ( 'BASE' );
		$had = $mode->findone ( 'id', 'user', array (
				'username' => $_GET ['username'] 
		) ) ? true : false;
		apiOutPut ( $had );
	}
	function telphone() {
		if (empty ( $_SESSION ['forget_username'] )) {
			apiOutPut ( false );
		}
		$mode = &m ( 'BASE' );
		$tel = $mode->findone ( 'telphone', 'user', array (
				'username' => $_SESSION ['forget_username'] 
		) );
		$cent = substr ( $tel, - 8, 4 );
		$tel = str_replace ( $cent, '****', $tel );
		apiOutPut ( $tel );
	}
	function forgettelcode() {
		if (empty ( $_SESSION ['forget_username'] )) {
			apiOutPut ( false );
		}
		$mode = &m ( 'BASE' );
		$tel = $mode->findone ( 'telphone', 'user', array (
				'username' => $_SESSION ['forget_username'] 
		) );
		// $code='1234';
		$code = rand ( 100000, 999999 );
		$duanxin = new chuanglanApp ( 'N7368641', '7f311096' );
		$ret = $duanxin->send ( $tel, "銆愮鐢绘．鏋楅噸缃瘑鐮佹湇鍔°�戞偍鐨勯獙璇佺爜鏄�:$code" );
		$_SESSION ['forget_code'] = $code;
		apiOutPut ( true );
	}
	function putforgetcode() {
		if (empty ( $_GET ['code'] )) {
			apiOutPut ( false );
		}
		if ($_GET ['code'] == $_SESSION ['forget_code']) {
			$_SESSION ['forget_code_right'] = true;
			$_SESSION ['forget_code'] = null;
			apiOutPut ( true );
		} else {
			$_SESSION ['forget_code'] = null;
			apiOutPut ( false );
		}
	}
	function newpass() {
		if (empty ( $_SESSION ['forget_username'] ) or ! isset ( $_SESSION ['forget_code_right'] ) or $_SESSION ['forget_code_right'] == false) {
			header ( 'Location: index.php?app=login&act=forgetpassword2' );
			exit ();
		}
		if (empty ( $_POST ['password'] )) {
			apiOutPut ( false );
		}
		
		if (! ctype_alnum ( $_POST ['password'] )) {
			// echo 'error2';
			apiOutPut ( false );
		}
		
		if (strlen ( $_POST ['password'] ) > 16 or strlen ( $_POST ['password'] ) < 6) {
			apiOutPut ( false );
		}
		$this->setpassword ( $_SESSION ['forget_username'], $_POST ['password'] );
		$_SESSION ['forget_username'] = null;
		$_SESSION ['forget_code'] = null;
		$_SESSION ['forget_code_right'] = false;
		apiOutPut ( true );
	}
	function register() {
		if (empty ( $_GET ['username'] ) or empty ( $_GET ['password'] )) {
			// echo 'error1';
			apiOutPut ( false );
		}
		$username = $_GET ['username'];
		$password = $_GET ['password'];
		
		if (! ctype_alnum ( $username ) or ! ctype_alnum ( $password )) {
			// echo 'error2';
			apiOutPut ( false );
		}
		
		if (strlen ( $username ) > 16 or strlen ( $username ) < 6 or strlen ( $password ) > 16 or strlen ( $password ) < 6) {
			apiOutPut ( false );
		}
		if ($this->getPassWord ( $username ) === false) {
			if ($this->userAdd ( $username, $password ) == 1) {
				if ($this->userGroupSet ( $username ) === false or $this->setUserExpiration ( $username, 1 ) === false) // 锟斤拷锟斤拷默锟斤拷注锟斤拷剩锟斤拷时锟斤拷
{
					// echo "error4";
					apiOutPut ( false );
				} else {
					$token = $this->login ( $username );
					apiOutPut ( $token );
				}
			} else {
				// echo "error5";
				apiOutPut ( false );
			}
		} else {
			// echo "error6";
			apiOutPut ( false );
		}
	}
	function userLogin() {
		// setcookie('aa','bb');
		// $this->settoken('admin');
		// $a=$this->login('admin');
		
		if (empty ( $_POST ['username'] ) or empty ( $_POST ['password'] )) {
			apiOutPut ( false, 301, '参数错误' );
		}
		$username = $_POST ['username'];
		$password = $_POST ['password'];
		// echo $username;
	
		// die();
		if ($this->getPassWord ( $username ) == md5 ( $password )) {
			$this->settoken ( $username );
			
			$a = $this->login ( $username );
			apiOutPut ( $a );
		} else {
			apiOutPut ( false, 201, '登陆失败' );
		}
		// apiOutPut($this->getPassWord($username)===$password?true:false);
	}
	function hadUser() {
		if (empty ( $_GET ['username'] )) {
			apiOutPut ( false );
		}
		if (isset ( $_GET ['username'] )) {
			$username = $_GET ['username'];
			apiOutPut ( $this->getPassWord ( $username ) === false ? true : false );
		} else {
			apiOutPut ( false );
		}
	}
	function test() {
		// echo '123123';
		apiOutPut ( $_POST );
	}
}
