<?php

/**
 * Created by PhpStorm.
 * User: 41023
 * Date: 2015/12/20
 * Time: 22:01
 */
class userbaseApp {
	var $user;
	var $data;
	/*
	 * user login check
	 * cookie is ok
	 * session is ok
	 * time is ok
	 *
	 */
	function __construct() {
		$this->user = ( object ) null;
		/* session_start(); */
		// echo $_COOKIE['token'];
		if (! empty ( $_SESSION ['LOGIN'] )) {
			
			$this->user->login = 1;
			$this->user->username = $_SESSION ['user'] ['user_name'];
			$this->user->userid = $_SESSION ['user'] ['user_id'];
			$this->user->name = $_SESSION ['user'] ['show_name'];
			$this->user->type = $_SESSION ['user'] ['type'];
		} elseif (! empty ( $_COOKIE ['token'] )) {
			// echo 1;
			$userid = $this->getUseridFromToken ( $_COOKIE ['token'] );
			// echo $userid;
			if ($userid) {
				$this->login ( $this->getUserByid ( $userid ) );
			} else {
				$_SESSION ['user'] = null;
				$_SESSION ['user'] ['login'] = 0;
				$_SESSION ['LOGIN'] = null;
				$_COOKIE ['token'] = null;
				$this->user = ( object ) null;
				$this->user->login = 0;
				$this->logining ();
			}
		} else {
			$_SESSION ['user'] = null;
			$_SESSION ['user'] ['login'] = 0;
			$_SESSION ['LOGIN'] = null;
			$this->user = ( object ) null;
			$this->user->login = 0;
		}
		
		parse_str ( file_get_contents ( 'php://input' ), $data );
		$data = array_merge ( $_GET, $_POST, $data );
		unset ( $data ['app'] );
		unset ( $data ['act'] );
		$this->data = $data;
		
		// $this->user->login=1;
	}
	
	// login fun
	function login($username) {
		$userid = $this->getUserId ( $username );
		$info = $this->getUserInfo ( $userid );
		$_SESSION ['user'] ['login'] = 1;
		$_SESSION ['user'] ['user_id'] = $userid;
		$_SESSION ['user'] ['user_name'] = $username;
		$_SESSION ['user'] ['show_name'] = $info ['name'];
		$_SESSION ['user'] ['type'] = $info ['type']; // quanxiandengji
		                                              // $_SESSION['user']['token']=$token;
		
		$_SESSION ['LOGIN'] = 1;
		$this->user->login = 1;
		$this->user->username = $_SESSION ['user'] ['user_name'];
		$this->user->userid = $_SESSION ['user'] ['user_id'];
		$this->user->name = $_SESSION ['user'] ['show_name'];
		$this->user->type = $_SESSION ['user'] ['type'];
		return true;
	}
	function settoken($username) {
		$userid = $this->getUserId ( $username );
		$info = $this->getUserInfo ( $userid );
		
		if (! $info) {
			apiOutPut ( false, 404, '未找到用户数据' );
		}
		$type = $info ['type'];
		// $name=$this->getUserShowname($username);
		$mode = &m ( 'BASE' );
		do {
			$token = random_str ( 36 );
			$res = $mode->exec ( "insert into token (user_id,type,token,status,data_status,verification_code) values ('" . $userid . "','" . $type . "','" . $token . "','1','1','" . time () . random_str ( 16 ) . "')" );
		} while ( ! $res );
		// $_COOKIE['token']=$token;
		setcookie ( 'token', $token );
	}
	function logout() {
		$_SESSION ['user'] = null;
		$_SESSION ['user'] ['login'] = 0;
		$_SESSION ['LOGIN'] = null;
		$token = $_COOKIE ['token'];
		$basemode = &m ( 'BASE' );
		$query = "update token set status='-1' where token='" . $token . "' and data_status='1'";
		$res = $basemode->exec ( $query );
		$_COOKIE ['token'] = null;
		$this->user = ( object ) null;
		$this->user->login = 0;
		apiOutPut ( true );
		// header('location:index.php');
	}
	function getUseridFromToken($token) {
		$basemode = &m ( 'BASE' );
		$query = "select user_id from token where token='" . $token . "' and status='1' and data_status='1'";
		$res = $basemode->getone ( $query );
		return $res;
	}
	function getPassWord($username) {
		$basemode = &m ( 'BASE' );
		$query = "select password from user where username='" . $username . "' and status='1' and data_status='1'";
		$res = $basemode->getone ( $query );
		return $res;
	}
	function setPassWord($username, $password) {
		$basemode = &m ( 'BASE' );
		$query = "update user set password='" . md5 ( $password ) . "' where username='" . $username . "' and status='1' and data_status='1'";
		$res = $basemode->exec ( $query );
		return $res;
	}
	function getUserInfo($id) {
		$basemode = &m ( 'BASE' );
		$query = "select Id,username,headimg,type,name,sex,telphone from user where id='" . $id . "' and status='1' and data_status='1'";
		$res = $basemode->getrow ( $query );
		// var_dump($res);
		return $res;
	}
	function getUserId($username) {
		$basemode = &m ( 'BASE' );
		$query = "select id from user where username='" . $username . "'";
		$res = $basemode->getone ( $query );
		return $res;
	}
	function getUserShowname($username) {
		$basemode = &m ( 'BASE' );
		$query = "select name from user where username='" . $username . "'";
		$res = $basemode->getone ( $query );
		return $res;
	}
	function getUserById($id) {
		$id = intval ( $id );
		$basemode = &m ( 'BASE' );
		$query = "select username from user where id=" . $id;
		$res = $basemode->getone ( $query );
		return $res;
	}
	function getUserGroup($username) {
		$basemode = &m ( 'BASE' );
		$query = "select type from user where username='" . $username . "'";
		$res = $basemode->getone ( $query );
		return $res;
	}
	function userAdd($username, $password) {
		$basemode = &m ( 'BASE' );
		$query = "insert into radcheck (username,attribute,op,value) values('" . $username . "','Cleartext-Password',':=','" . $password . "')";
		$res = $basemode->exec ( $query );
		return $res;
	}
	function userGroupSet($username, $groupname = 'user') {
		if ($this->hadUserGroup ( $groupname ) == 1) {
			// die(123213);
			$basemode = &m ( 'BASE' );
			if ($this->getUserGroup ( $username ) === false) {
				$query = "INSERT INTO radusergroup (username,groupname,priority) VALUES ('" . $username . "','" . $groupname . "',1)";
			} else {
				$query = "UPDATE radusergroup SET groupname = '" . $groupname . "',priority=1 where username='" . $username . "'";
			}
			$res = $basemode->exec ( $query );
			return $res;
		} else {
			return false;
		}
	}
	function hadUserGroup($userGroup) {
		$basemode = &m ( 'BASE' );
		$query = "select count(*) from radgroupreply where groupname='" . $userGroup . "' and attribute='Auth-Type' and op=':='";
		$res = $basemode->getone ( $query );
		return $res;
	}
	function logining() {
		if ($this->user->login === 0) {
			apiOutPut ( false, 401, '鏈櫥闄嗘垨鐧婚檰杩囨湡' );
		} else {
			apiOutPut ( true );
		}
	}
}