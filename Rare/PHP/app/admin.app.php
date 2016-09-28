<?php
// include ('userbase.app.php');
class AdminApp {
	var $userApp;
	var $mine;
	var $login;
	var $data;
	function __construct() {
		$this->login = false;
		$this->userApp = new userApp ();
		parse_str ( file_get_contents ( 'php://input' ), $data );
		$data = array_merge ( $_GET, $_POST, $data );
		unset ( $data ['app'] );
		unset ( $data ['act'] );
		$this->data = $data;
		// var_dump($_SESSION);
		if (! empty ( $_SESSION ['admin_id'] ) && ! empty ( $_SESSION ['admin'] ) && ! empty ( $_SESSION ['admin_level'] )) {
			// $this->mine=array('username'=>$_SESSION['admin'],'id'=>$_SESSION['admin_id'],'level'=>$_SESSION['level']);
			$this->login = true;
		}
		
		if ($this->login === false && $_GET ['act'] != 'login') {
			header ( 'Location: index.php?app=admin&act=login' );
		}
	}
	function login() {
		if (isPost ()) {
			$username = $_POST ['username'];
			$password = $_POST ['password'];
			
			$mode = &m ( 'BASE' );
			$relpass = $mode->findone ( 'password', 'webadmin', array (
					'username' => $username 
			) );
			if (md5 ( $password ) !== $relpass) {
				apiOutPut ( false );
			}
			$res = $mode->findrow ( '', 'webadmin', array (
					'username' => $username 
			) );
			$_SESSION ['admin_id'] = $res ['Id'];
			$_SESSION ['admin'] = $res ['username'];
			$_SESSION ['admin_level'] = $res ['level'];
			// var_dump($_SESSION);
			apiOutPut ( true );
			// header('location:index.php?app=admin&act=index');
		} else {
			display ( 'admin_login.html' );
		}
		apiOutPut ( false );
	}
	function index() {
		display ( 'admin_Exhibition.html' );
	}
	function userlisthtml() {
		display ( "admin_insert.html" );
	}
	function html() {
		$html = $this->data ['html'];
		display ( "$html.html" );
	}
	
	// 鐢ㄦ埛绠＄悊绯荤粺
	function UserList() {
		if (isGet ()) {
			$search = empty ( $_GET ['search'] ) ? '' : " where username like '%" . $_GET ['search'] . "%'";
			$limit = 20;
			$page = empty ( $_GET ['page'] ) ? 1 : intval ( $_GET ['page'] );
			$minpage = ($page - 1) * 20;
			
			$mode = &m ( 'BASE' );
			// $userinfo=$mode->getall("select u.id,u.username,u.value as password,b.value as expiration,ug.groupname from radcheck u left join radcheck b on (b.username=u.username and b.attribute='Expiration') left join radusergroup as ug on ug.username=u.username where u.attribute='Cleartext-Password' $search limit $minpage,$limit");
			$query = "select * from user $search limit $minpage,$limit";
			$userinfo = $mode->getall ( $query );
			$infonumber = $mode->getone ( "select count(*) from user  $search" );
			apiOutPut ( array (
					'number' => $infonumber,
					'data' => $userinfo 
			) );
		}
		apiOutPut ( false );
	}
	function addpiclist() {
		if (isGet ()) {
			$search = empty ( $_GET ['maxid'] ) ? '' : " and img.id > '" . $_GET ['maxid'] . "'";
			$limit = 20;
			$page = empty ( $_GET ['page'] ) ? 1 : intval ( $_GET ['page'] );
			$minpage = ($page - 1) * 20;
			
			$mode = &m ( 'BASE' );
			// $userinfo=$mode->getall("select u.id,u.username,u.value as password,b.value as expiration,ug.groupname from radcheck u left join radcheck b on (b.username=u.username and b.attribute='Expiration') left join radusergroup as ug on ug.username=u.username where u.attribute='Cleartext-Password' $search limit $minpage,$limit");
			$query = "select img.*,group_concat(cast(imgtag.tag as char)) as tag from img left join imgtag on imgtag.imgid=img.id  where img.status=0 $search group by img.id order by img.id limit $minpage,$limit";
			$userinfo = $mode->getall ( $query );
			$infonumber = $mode->getone ( "select count(*) from img where status=0  $search" );
			apiOutPut ( array (
					'number' => $infonumber,
					'data' => $userinfo 
			) );
		}
		apiOutPut ( false );
	}
	function picList() {
		if (isGet ()) {
			$search = empty ( $_GET ['search'] ) ? '' : " and img.filename like '%" . $_GET ['search'] . "%'";
			$limit = 20;
			$page = empty ( $_GET ['page'] ) ? 1 : intval ( $_GET ['page'] );
			$minpage = ($page - 1) * 20;
			
			$mode = &m ( 'BASE' );
			// $userinfo=$mode->getall("select u.id,u.username,u.value as password,b.value as expiration,ug.groupname from radcheck u left join radcheck b on (b.username=u.username and b.attribute='Expiration') left join radusergroup as ug on ug.username=u.username where u.attribute='Cleartext-Password' $search limit $minpage,$limit");
			$query = "select img.*,group_concat(cast(imgtag.tag as char)) as tag from img left join imgtag on imgtag.imgid=img.id  where img.status=0 $search group by img.id order by img.id limit $minpage,$limit";
			$userinfo = $mode->getall ( $query );
			$infonumber = $mode->getone ( "select count(*) from img where status=0  $search" );
			apiOutPut ( array (
					'number' => $infonumber,
					'data' => $userinfo 
			) );
		}
		apiOutPut ( false );
	}
	function picInfo() {
		if (isPut ()) {
			$mode = &m ( 'BASE' );
			$doing = false;
			// var_dump($this->data);
			if (empty ( $this->data ['filename'] )) {
				apiOutPut ( false );
			}
			if (! empty ( $this->data ['title'] )) {
				try {
					// $mode->update(array('telphone'=>$this->data['telphone']),'user',array('username'=>$this->data['username']));
					$mode->exec ( "update img set title='" . $this->data ['title'] . "' where filename='" . $this->data ['filename'] . "'" );
				} catch ( Exception $e ) {
					apiOutPut ( false );
				}
				// apiOutPut(true);
				$doing = true;
			}
			
			if (! empty ( $this->data ['smallfilename'] )) {
				try {
					$mode->update ( array (
							'smallfilename' => $this->data ['smallfilename'] 
					), 'img', array (
							'filename' => $this->data ['filename'] 
					) );
				} catch ( Exception $e ) {
					apiOutPut ( false );
				}
				$doing = true;
			}
			
			if (! empty ( $this->data ['body'] )) {
				try {
					$mode->update ( array (
							'body' => $this->data ['body'] 
					), 'img', array (
							'filename' => $this->data ['filename'] 
					) );
				} catch ( Exception $e ) {
					apiOutPut ( false );
				}
				$doing = true;
			}
			
			if (! empty ( $this->data ['status'] )) {
				try {
					$mode->update ( array (
							'status' => $this->data ['status'] 
					), 'img', array (
							'filename' => $this->data ['filename'] 
					) );
				} catch ( Exception $e ) {
					apiOutPut ( false );
				}
				$doing = true;
			}
			
			apiOutPut ( $doing );
			// var_dump($this->data);
		}
		apiOutPut ( false );
	}
	function UserInfo() {
		if (isGet ()) {
			$username = ! empty ( $_GET ['username'] ) ? $_GET ['username'] : false;
			if (! $username) {
				apiOutPut ( false );
			}
			$mode = &m ( 'BASE' );
			$userinfo = $mode->getall ( "select * from user where username='$username'" );
			$userdata = array (
					'id' => $userinfo ['id'],
					'username' => $userinfo ['username'],
					'password' => $userinfo ['password'],
					'expiration' => $userinfo ['expiration'],
					'group' => $userinfo ['groupname'] 
			);
			apiOutPut ( $userdata );
		}
		
		if (isPost ()) {
			// 鏁版嵁妫�娴�
			if (empty ( $_POST ['username'] ) || empty ( $_POST ['password'] )) {
				apiOutPut ( false );
			}
			$username = $_POST ['username'];
			$password = empty ( $_POST ['password'] ) ? 'e10adc3949ba59abbe56e057f20f883e' : $_POST ['password'];
			$headimg = empty ( $_POST ['headimg'] ) ? 'head1.jpg' : $_POST ['headimg'];
			$telphone = empty ( $_POST ['telphone'] ) ? '' : $_POST ['telphone'];
			$name = empty ( $_POST ['name'] ) ? '' : $_POST ['name'];
			$sign = empty ( $_POST ['sign'] ) ? '' : $_POST ['sign'];
			// $expiration=empty($_POST['expiration'])?0:intval($_POST['expiration']);
			if ($this->userApp->getPassWord ( $username ) !== false) {
				apiOutPut ( false );
			}
			try {
				$mode = &m ( 'BASE' );
				$mode->exec ( "insert into user (username,password,telphone,name,headimg,sign) values ('$username','md5($password)','$telphone','$name','$headimg','$sign')" );
			} catch ( Exception $e ) {
				// echo $e;
				apiOutPut ( false );
			}
			apiOutPut ( true );
		}
		
		if (isDelete ()) {
		}
		
		if (isPut ()) {
			$mode = &m ( 'BASE' );
			$doing = false;
			// var_dump($this->data);
			if (empty ( $this->data ['username'] ) || ! $this->userApp->getPassWord ( $this->data ['username'] )) {
				apiOutPut ( false );
			}
			if (! empty ( $this->data ['telphone'] )) {
				try {
					// $mode->update(array('telphone'=>$this->data['telphone']),'user',array('username'=>$this->data['username']));
					$mode->exec ( "update user set telphone='" . $this->data ['telphone'] . "' where username='" . $this->data ['username'] . "'" );
				} catch ( Exception $e ) {
					apiOutPut ( false );
				}
				// apiOutPut(true);
				$doing = true;
			}
			
			if (! empty ( $this->data ['password'] )) {
				try {
					$mode->update ( array (
							'password' => md5 ( $this->data ['password'] ) 
					), 'user', array (
							'username' => $this->data ['username'] 
					) );
				} catch ( Exception $e ) {
					apiOutPut ( false );
				}
				$doing = true;
			}
			
			if (! empty ( $this->data ['name'] )) {
				try {
					$mode->update ( array (
							'name' => $this->data ['name'] 
					), 'user', array (
							'username' => $this->data ['username'] 
					) );
				} catch ( Exception $e ) {
					apiOutPut ( false );
				}
				$doing = true;
			}
			
			if (! empty ( $this->data ['headimg'] )) {
				try {
					$mode->update ( array (
							'headimg' => $this->data ['headimg'] 
					), 'user', array (
							'username' => $this->data ['username'] 
					) );
				} catch ( Exception $e ) {
					apiOutPut ( false );
				}
				$doing = true;
			}
			
			if (! empty ( $this->data ['sign'] )) {
				try {
					$mode->update ( array (
							'sign' => $this->data ['sign'] 
					), 'user', array (
							'username' => $this->data ['username'] 
					) );
				} catch ( Exception $e ) {
					apiOutPut ( false );
				}
				$doing = true;
			}
			
			apiOutPut ( $doing );
			// var_dump($this->data);
		}
		apiOutPut ( false );
	}
	function userGroup() {
		if (isGet ()) {
			$mode = &m ( 'BASE' );
			$grouplist = $mode->findall ( array (
					'groupname' 
			), 'radgroupreply', array (
					'attribute' => 'Auth-Type' 
			) );
			apiOutPut ( $grouplist );
		}
		apiOutPut ( false );
	}
	function datestring() {
		if (isGet ()) {
			$expiration = $this->data ['date'];
			apiOutPut ( strftime ( '%d %b %Y %H:%M:%S', intval ( $expiration ) ) );
		}
		apiOutPut ( false );
	}
	function taglist() {
		if (isget ()) {
			$limit = 20;
			$page = empty ( $_GET ['page'] ) ? 1 : intval ( $_GET ['page'] );
			$minpage = ($page - 1) * 20;
			$mode = &m ( 'BASE' );
			$infos = $mode->getall ( "select * from showtags limit $minpage,$limit" );
			// echo "select * from showtags limit $minpage,$limit";
			$infonumber = $mode->getone ( "select count(*) from showtags" );
			apiOutPut ( array (
					'number' => $infonumber,
					'data' => $infos 
			) );
		}
	}
	function tag() {
		if (ispost ()) {
			$mode = &m ( 'BASE' );
			$res = $mode->exec ( "insert into showtags (tag,status) value ('" . $this->data ['tag'] . "','" . $this->data ['status'] . "')" );
			apiOutPut ( $res ? true : false );
		}
	}
	function settagstatus() {
		$status = $_GET ['status'] ? 1 : 0;
		$tag = $_GET ['tag'];
		$mode = &m ( 'BASE' );
		$res = $mode->exec ( "update showtags set status=$status where tag='$tag'" );
		apiOutPut ( $res ? true : false );
	}
	function userLoginList() {
		if (isGet ()) {
			$search = empty ( $_GET ['search'] ) ? '' : " and username like '%" . $_GET ['search'] . "%'";
			$limit = 20;
			$page = empty ( $_GET ['page'] ) ? 1 : intval ( $_GET ['page'] );
			$minpage = ($page - 1) * 20;
			
			$mode = &m ( 'BASE' );
			// $userinfo=$mode->getall("select u.id,u.username,u.value as password,b.value as expiration,ug.groupname from radcheck u left join radcheck b on (b.username=u.username and b.attribute='Expiration') left join radusergroup as ug on ug.username=u.username where u.attribute='Cleartext-Password' $search limit $minpage,$limit");
			$query = "select radacctid as id,username,nasipaddress as vpn,acctstarttime as logintime,acctinputoctets as upnet,acctoutputoctets as downnet,callingstationid as ipfrom,framedipaddress as ipnat from radacct where acctstoptime is null $search order by radacctid  limit $minpage,$limit";
			$userinfo = $mode->getall ( $query );
			foreach ( $userinfo as &$v ) {
				$v ['ipfrom'] = explode ( '=', $v ['ipfrom'] );
				// var_dump($v['ipnat']);
				$v ['ipfrom'] = $v ['ipfrom'] [0];
			}
			$infonumber = $mode->getone ( "select count(*) from radacct  where acctstoptime is null $search" );
			apiOutPut ( array (
					'number' => $infonumber,
					'data' => $userinfo 
			) );
		}
		apiOutPut ( false );
	}
	function onemonthNetData() {
		if (isGet ()) {
			$username = empty ( $this->data ['username'] ) ? false : $this->data ['username'];
			$month = empty ( $this->data ['month'] ) ? date ( 'm', time () ) : $this->data ['month'];
			$year = empty ( $this->data ['year'] ) ? date ( 'y', time () ) : $this->data ['year'];
			$days = 31;
			
			$titles = array ();
			$updatas = array ();
			$downdatas = array ();
			$search = $username == false ? '' : " and username='$username'";
			$mode = &m ( 'BASE' );
			for($i = 1; $i <= $days; $i ++) {
				$updatas [] = $mode->getone ( "select sum(acctinputoctets) from radacct where  DATE_FORMAT( acctstoptime, '%Y%m%d' )=DATE_FORMAT( '" . "$year-$month-$i" . "' , '%Y%m%d' ) $search" ) / (1024 * 1024);
				$downdatas [] = $mode->getone ( "select sum(acctoutputoctets) from radacct where  DATE_FORMAT( acctstoptime, '%Y%m%d' )=DATE_FORMAT( '" . "$year-$month-$i" . "' , '%Y%m%d' ) $search" ) / (1024 * 1024);
				$titles [] = $i;
			}
			apiOutPut ( array (
					'titles' => $titles,
					'data' => array (
							$updatas,
							$downdatas 
					) 
			) );
		}
		apiOutPut ( false );
	}
	function onedayNetData() {
		if (isGet ()) {
			$username = empty ( $this->data ['username'] ) ? false : $this->data ['username'];
			$month = empty ( $this->data ['month'] ) ? date ( 'm', time () ) : $this->data ['month'];
			$year = empty ( $this->data ['year'] ) ? date ( 'y', time () ) : $this->data ['year'];
			$day = empty ( $this->data ['day'] ) ? date ( 'd', time () ) : $this->data ['day'];
			$hours = 24;
			
			$titles = array ();
			$updatas = array ();
			$downdatas = array ();
			$search = $username == false ? '' : " and username='$username'";
			$mode = &m ( 'BASE' );
			for($i = 0; $i < $hours; $i ++) {
				$updatas [] = $mode->getone ( "select sum(acctinputoctets) from radacct where  DATE_FORMAT( acctstoptime, '%Y%m%d%h' )=DATE_FORMAT( '" . "$year-$month-$day $i" . "' , '%Y%m%d%h' ) $search" ) / (1024 * 1024);
				$downdatas [] = $mode->getone ( "select sum(acctoutputoctets) from radacct where  DATE_FORMAT( acctstoptime, '%Y%m%d%h' )=DATE_FORMAT( '" . "$year-$month-$day $i" . "' , '%Y%m%d%h' ) $search" ) / (1024 * 1024);
				$titles [] = $i;
			}
			apiOutPut ( array (
					'titles' => $titles,
					'data' => array (
							$updatas,
							$downdatas 
					) 
			) );
		}
		apiOutPut ( false );
	}
	function onemonthOnlineData() {
		if (isGet ()) {
			$username = empty ( $this->data ['username'] ) ? false : $this->data ['username'];
			$month = empty ( $this->data ['month'] ) ? date ( 'm', time () ) : $this->data ['month'];
			$year = empty ( $this->data ['year'] ) ? date ( 'y', time () ) : $this->data ['year'];
			$days = 31;
			
			$titles = array ();
			$updatas = array ();
			$downdatas = array ();
			$search = $username == false ? '' : " and username='$username'";
			$mode = &m ( 'BASE' );
			for($i = 1; $i <= $days; $i ++) {
				$updatas [] = $mode->getone ( "select count(*) from (select username from radacct where  DATE_FORMAT( acctstoptime, '%Y%m%d' )>=DATE_FORMAT( '" . "$year-$month-$i" . "' , '%Y%m%d' ) and  DATE_FORMAT( acctstarttime, '%Y%m%d' )<=DATE_FORMAT( '" . "$year-$month-$i" . "' , '%Y%m%d' ) $search group by username ) as big" );
				// $downdatas[]=$mode->getone("select sum(acctoutputoctets) from radacct where DATE_FORMAT( acctstoptime, '%Y%m%d' )=DATE_FORMAT( '"."$year-$month-$i"."' , '%Y%m%d' ) $search")/(1024*1024);
				$titles [] = $i;
			}
			apiOutPut ( array (
					'titles' => $titles,
					'data' => array (
							$updatas 
					) 
			) );
		}
		apiOutPut ( false );
	}
	function onedayOnlineData() {
		if (isGet ()) {
			$username = empty ( $this->data ['username'] ) ? false : $this->data ['username'];
			$month = empty ( $this->data ['month'] ) ? date ( 'm', time () ) : $this->data ['month'];
			$year = empty ( $this->data ['year'] ) ? date ( 'y', time () ) : $this->data ['year'];
			$day = empty ( $this->data ['day'] ) ? date ( 'd', time () ) : $this->data ['day'];
			$hours = 24;
			
			$titles = array ();
			$updatas = array ();
			$downdatas = array ();
			$search = $username == false ? '' : " and username='$username'";
			$mode = &m ( 'BASE' );
			for($i = 0; $i < $hours; $i ++) {
				$updatas [] = $mode->getone ( "select count(*) from (select username from radacct where  DATE_FORMAT( acctstoptime, '%Y%m%d%h' )>=DATE_FORMAT( '" . "$year-$month-$day $i" . "' , '%Y%m%d%h' ) and  DATE_FORMAT( acctstarttime, '%Y%m%d%h' )<=DATE_FORMAT( '" . "$year-$month-$day $i" . "' , '%Y%m%d%h' ) $search group by username ) as big" );
				// $downdatas[]=$mode->getone("select sum(acctoutputoctets) from radacct where DATE_FORMAT( acctstoptime, '%Y%m%d%h' )=DATE_FORMAT( '"."$year-$month-$day $i"."' , '%Y%m%d%h' ) $search")/(1024*1024);
				$titles [] = $i;
			}
			apiOutPut ( array (
					'titles' => $titles,
					'data' => array (
							$updatas 
					) 
			) );
		}
		apiOutPut ( false );
	}
	function ipData() {
		if (isGet ()) {
			$username = empty ( $this->data ['username'] ) ? '' : $this->data ['username'];
			// $month=empty($this->data['month'])?date('m',time()):$this->data['month'];
			// $year=empty($this->data['year'])?date('y',time()):$this->data['year'];
			// $days=31;
			
			$titles = $username . ' IP鏉ユ簮';
			$ips = array ();
			$numbers = array ();
			$search = $username == '' ? '' : " where username='$username'";
			$mode = &m ( 'BASE' );
			
			$datas = $mode->getall ( "select count(*) as number,substring_index (callingstationid,'=',1) as fromip from radacct $search group by fromip " );
			// $downdatas[]=$mode->getone("select sum(acctoutputoctets) from radacct where DATE_FORMAT( acctstoptime, '%Y%m%d' )=DATE_FORMAT( '"."$year-$month-$i"."' , '%Y%m%d' ) $search")/(1024*1024);
			foreach ( $datas as $k => $v ) {
				$ips [] = $v ['fromip'];
				$numbers [] = $v ['number'];
			}
			// apiOutPut($datas);
			apiOutPut ( array (
					'titles' => $titles,
					'data' => array (
							$ips,
							$numbers 
					) 
			) );
		}
		apiOutPut ( false );
	}
	function usetime() {
		if (isGet ()) {
			
			$usernames = array ();
			$numbers = array ();
			$mode = &m ( 'BASE' );
			$datas = $mode->getall ( "select sum(TIMESTAMPDIFF(MINUTE,acctstarttime,acctstoptime)) as number,username from radacct where acctstoptime is not null  group by username" );
			foreach ( $datas as $k => $v ) {
				$usernames [] = $v ['username'];
				$numbers [] = $v ['number'];
			}
			apiOutPut ( array (
					'titles' => '鐢ㄦ埛浣跨敤鏃堕暱',
					'data' => array (
							$usernames,
							$numbers 
					) 
			) );
		}
		apiOutPut ( false );
	}
	function usemonthtime() {
		if (isGet ()) {
			$month = empty ( $this->data ['month'] ) ? date ( 'm', time () ) : $this->data ['month'];
			$year = empty ( $this->data ['year'] ) ? date ( 'Y', time () ) : $this->data ['year'];
			$day = empty ( $this->data ['day'] ) ? date ( 'd', time () ) : $this->data ['day'];
			$usernames = array ();
			$numbers = array ();
			$mode = &m ( 'BASE' );
			$datas = $mode->getall ( "select sum(TIMESTAMPDIFF(MINUTE,acctstarttime,acctstoptime)) as number,username from radacct where acctstoptime is not null and DATE_FORMAT( acctstoptime, '%Y%m' )>=DATE_FORMAT( '" . "$year-$month-$day" . "' , '%Y%m' ) and  DATE_FORMAT( acctstarttime, '%Y%m' )<=DATE_FORMAT( '" . "$year-$month-$day" . "' , '%Y%m' ) group by username" );
			// var_dump("$year-$month");
			foreach ( $datas as $k => $v ) {
				$usernames [] = $v ['username'];
				$numbers [] = $v ['number'];
			}
			apiOutPut ( array (
					'titles' => '鐢ㄦ埛浣跨敤鏃堕暱',
					'data' => array (
							$usernames,
							$numbers 
					) 
			) );
		}
		apiOutPut ( false );
	}
	function usedaytime() {
		if (isGet ()) {
			$month = empty ( $this->data ['month'] ) ? date ( 'm', time () ) : $this->data ['month'];
			$year = empty ( $this->data ['year'] ) ? date ( 'y', time () ) : $this->data ['year'];
			$day = empty ( $this->data ['day'] ) ? date ( 'd', time () ) : $this->data ['day'];
			$usernames = array ();
			$numbers = array ();
			$mode = &m ( 'BASE' );
			$datas = $mode->getall ( "select sum(TIMESTAMPDIFF(MINUTE,acctstarttime,acctstoptime)) as number,username from radacct where acctstoptime is not null and DATE_FORMAT( acctstoptime, '%Y%m%d' )>=DATE_FORMAT( '" . "$year-$month-$day" . "' , '%Y%m%d' ) and  DATE_FORMAT( acctstarttime, '%Y%m%d' )<=DATE_FORMAT( '" . "$year-$month-$day" . "' , '%Y%m%d' ) group by username" );
			// var_dump();
			if ($datas !== false) {
				foreach ( $datas as $k => $v ) {
					$usernames [] = $v ['username'];
					$numbers [] = $v ['number'];
				}
			}
			
			apiOutPut ( array (
					'titles' => '鐢ㄦ埛浣跨敤鏃堕暱',
					'data' => array (
							$usernames,
							$numbers 
					) 
			) );
		}
		apiOutPut ( false );
	}
	function usenet() {
		if (isGet ()) {
			$usernames = array ();
			$numbers = array ();
			$mode = &m ( 'BASE' );
			$datas = $mode->getall ( "select sum(acctinputoctets+acctoutputoctets)/1024/1024 as number,username from radacct group by username" );
			foreach ( $datas as $k => $v ) {
				$usernames [] = $v ['username'];
				$numbers [] = intval ( $v ['number'] );
			}
			apiOutPut ( array (
					'titles' => '鐢ㄦ埛浣跨敤娴侀噺',
					'data' => array (
							$usernames,
							$numbers 
					) 
			) );
		}
		apiOutPut ( false );
	}
	function usemonthnet() {
		if (isGet ()) {
			$month = empty ( $this->data ['month'] ) ? date ( 'm', time () ) : $this->data ['month'];
			$year = empty ( $this->data ['year'] ) ? date ( 'y', time () ) : $this->data ['year'];
			$day = empty ( $this->data ['day'] ) ? date ( 'd', time () ) : $this->data ['day'];
			$usernames = array ();
			$numbers = array ();
			$mode = &m ( 'BASE' );
			$datas = $mode->getall ( "select sum(acctinputoctets+acctoutputoctets)/1024/1024 as number,username from radacct where DATE_FORMAT( acctstoptime, '%Y%m' )>=DATE_FORMAT( '" . "$year-$month-$day" . "' , '%Y%m' ) and  DATE_FORMAT( acctstarttime, '%Y%m' )<=DATE_FORMAT( '" . "$year-$month-$day" . "' , '%Y%m' ) group by username" );
			foreach ( $datas as $k => $v ) {
				$usernames [] = $v ['username'];
				$numbers [] = intval ( $v ['number'] );
			}
			apiOutPut ( array (
					'titles' => '鐢ㄦ埛浣跨敤娴侀噺',
					'data' => array (
							$usernames,
							$numbers 
					) 
			) );
		}
		apiOutPut ( false );
	}
	function usedaynet() {
		if (isGet ()) {
			$month = empty ( $this->data ['month'] ) ? date ( 'm', time () ) : $this->data ['month'];
			$year = empty ( $this->data ['year'] ) ? date ( 'y', time () ) : $this->data ['year'];
			$day = empty ( $this->data ['day'] ) ? date ( 'd', time () ) : $this->data ['day'];
			$usernames = array ();
			$numbers = array ();
			$mode = &m ( 'BASE' );
			$datas = $mode->getall ( "select sum(acctinputoctets+acctoutputoctets)/1024/1024 as number,username from radacct where DATE_FORMAT( acctstoptime, '%Y%m%d' )>=DATE_FORMAT( '" . "$year-$month-$day" . "' , '%Y%m%d' ) and  DATE_FORMAT( acctstarttime, '%Y%m%d' )<=DATE_FORMAT( '" . "$year-$month-$day" . "' , '%Y%m%d' ) group by username" );
			// var_dump("$year-$month-$day");
			foreach ( $datas as $v ) {
				$usernames [] = $v ['username'];
				$numbers [] = $v ['number'];
			}
			apiOutPut ( array (
					'titles' => '鐢ㄦ埛浣跨敤娴侀噺',
					'data' => array (
							$usernames,
							$numbers 
					) 
			) );
		}
		apiOutPut ( false );
	}
	function my_scandir($dir) {
		$files = array ();
		if (is_dir ( $dir )) {
			if ($handle = opendir ( $dir )) {
				while ( ($file = readdir ( $handle )) !== false ) {
					if ($file != "." && $file != "..") {
						if (is_dir ( $dir . "/" . $file )) {
							$files [$file] = $this->my_scandir ( $dir . "/" . $file );
						} else {
							$files [] = $dir . "/" . $file;
							// $files[]=$file;
						}
					}
				}
				closedir ( $handle );
				return $files;
			}
		}
	}
	function gettag($files, $tags = array()) {
		if (is_array ( $files )) {
			foreach ( $files as $k => $v ) {
				$nowtag = $tags;
				if (is_array ( $v )) {
					$nowtag [] = $k;
					$this->gettag ( $v, $nowtag );
				} else {
					$mode = &m ( 'BASE' );
					$id = $mode->findone ( 'id', 'img', array (
							'filename' => $v 
					) );
					if ($id == false) {
						$src_img = $v;
						$end = strrchr ( $src_img, '.' );
						
						// $head=chop($src_img,$end);
						$end = md5_file ( iconv ( "UTF-8", "GBK", $v ) ) . time () . '-small' . $end;
						$dst_img = 'data/smallpic/' . $end;
						$stat = img2thumb ( iconv ( "UTF-8", "GBK", $v ), $dst_img, $width = 318, $height = 469, $cut = 1, $proportion = 0 );
						// echo $stat."\r\n";
						$small = $stat ? $dst_img : '';
						// echo "寮�濮嬫柊澧炴枃浠舵暟鎹�:$v \r\n";
						$mode->exec ( "insert into img (filename,smallfilename) values ('" . $v . "','$small')" );
						$id = $mode->findone ( 'id', 'img', array (
								'filename' => $v 
						) );
						// echo $id;
						$tag_str = '';
						foreach ( $tags as $value ) {
							$tag_str .= "('$id','$value'),";
						}
						$tag_str = trim ( $tag_str, ',' );
						$mode->exec ( 'insert into imgtag (imgid,tag) values ' . $tag_str );
					}
				}
			}
		}
	}
	function addpic() {
		$files = mult_iconv ( 'gbk', 'utf-8', $this->my_scandir ( "data/pic" ) );
		// var_dump($files);
		
		$this->gettag ( $files );
		apiOutPut ( true );
	}
	function maxpic() {
		$mode = &m ( 'BASE' );
		$big = $mode->getone ( 'select id from img order by id desc limit 1' );
		apiOutPut ( $big );
	}
	function testdata() {
		$data = array (
				'titles' => array (
						1,
						2,
						3,
						4,
						5,
						6 
				),
				'data' => array (
						array (
								1,
								123,
								32,
								34,
								21,
								2 
						),
						array (
								12,
								23,
								123,
								254,
								132 
						) 
				) 
		);
		
		apiOutPut ( $data );
	}
}