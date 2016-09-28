<?php
include ('userbase.app.php');
class UserApp extends userbaseApp {
	function index() {
		if ($this->user->login === 0) {
			header ( 'Location: index.php?app=login&act=index' );
			exit ();
		}
		
		display ( 'exhibition.html' );
	}
	
	function typeList(){
		if(isGet()){
			$mode=&m('YUNGU');
			$allType=$mode->getTypeList();
			var_dump($allType);
		}
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
	
	
	
	
	
	
	
	
	
	
	function alipay() {
		$basemode_alipay = &m ( 'BASE' );
		if (! isset ( $_GET ['paytoken'] ) or empty ( $_GET ['paytoken'] ) or ! isset ( $_GET ['alipaydata'] ) or empty ( $_GET ['alipaydata'] )) {
			die ( '娌℃湁token 鎴栨病鏈夋敮浠樹俊鎭�' );
		}
		$paytoken = $_GET ['paytoken'];
		$alipaydata = json_decode ( base64_decode ( $_GET ['alipaydata'] ) );
		$alipaydata = objectToArray ( $alipaydata );
		// var_dump($alipaydata) ;
		// echo "\r\n 瀵绘壘鐨勪氦鏄撳彿涓� ".$alipaydata['trade_no']." 鎵惧埌缁撴灉涓� ".$basemode_alipay->findone('id','webalipaylist',array('trade_no'=>$alipaydata['trade_no']));
		if ($basemode_alipay->findone ( 'id', 'webalipaylist', array (
				'trade_no' => $alipaydata ['trade_no'] 
		) )) {
			die ( 'false 浜ゆ槗鍙烽噸鍙�' );
		}
		// die('2222222222');
		$basemode_alipay->insert ( array (
				'trade_no' => $alipaydata ['trade_no'] 
		), 'webalipaylist' );
		
		// 楠岃瘉瀹夊叏鎬т笌鍚堟硶鎬�
		
		// $query="select name,username,password,vpnname from webvpn where type='free' limit 1";
		$dbpayid = $basemode_alipay->findone ( 'pay_id', 'webpaytoken', array (
				'paytoken' => $paytoken,
				'status' => 0 
		) );
		// var_dump($alipaydata);
		// var_dump("\r\n 璁㈠崟鍙蜂负 $dbpayid 锛屾敮浠樺疂璁㈠崟鍙蜂负 ".$alipaydata['out_trade_no']);
		// die('123213');
		if ($dbpayid !== false && isset ( $alipaydata ['out_trade_no'] ) && $dbpayid == $alipaydata ['out_trade_no']) {
			$basemode_alipay->updates ( array (
					'status' => 1 
			), 'webpaytoken', array (
					'paytoken' => $paytoken 
			) );
			// echo "楠岃瘉璁㈠崟鍙锋垚鍔�";
			// 纭鎺ユ敹鐨勫悎娉曟��
			$dbdata = array (
					'time' => date ( "Y-m-d H:i:s", time () ),
					'trade_no' => $alipaydata ['trade_no'],
					'payid' => $alipaydata ['out_trade_no'],
					'trade_type' => 'alipay',
					'description' => json_encode ( $alipaydata ),
					'money' => $alipaydata ['total_fee'],
					'product_name' => $alipaydata ['subject'],
					'buyer_alipay_id' => $alipaydata ['seller_id'],
					'buyer_email' => $alipaydata ['buyer_email'],
					'alipay_status' => $alipay_status ['trade_status '] 
			);
			
			// 楠岃瘉浜ゆ槗鍙锋槸鍚﹀凡缁忓鐞嗚繃
			if ($basemode_alipay->findone ( 'id', 'webstadelog', array (
					'trade_no' => $alipaydata ['out_trade_no'] 
			) ) !== false) {
				$basemode_alipay->update ( array (
						'status' => - 1 
				), 'webpay', array (
						'id' => $alipaydata ['out_trade_no'] 
				) );
				$dbdata ['time'] = date ( "Y-m-d H:i:s", time () );
				$dbdata ['status'] = - 1;
				$basemode_alipay->insert ( $dbdata, 'webtradelog' );
				echo ('false');
				$basemod_alipay->update ( array (
						'status' => 1 
				), 'webpaytoken', array (
						'paytoken' => $paytoken 
				) );
				die ();
			}
			$basemode_alipay->insert ( $dbdata, 'webtradelog' );
			$user_id = $basemode_alipay->findone ( 'userid', 'webpay', array (
					'id' => $alipaydata ['out_trade_no'] 
			) );
			$username = $basemode_alipay->findone ( 'username', 'radcheck', array (
					'id' => $user_id 
			) );
			$money = $basemode_alipay->findone ( 'money', 'webpay', array (
					'id' => $alipaydata ['out_trade_no'] 
			) );
			$product_id = $basemode_alipay->findone ( 'product', 'webpay', array (
					'id' => $alipaydata ['out_trade_no'] 
			) );
			$product_num = $basemode_alipay->findone ( 'product_number', 'webpay', array (
					'id' => $alipaydata ['out_trade_no'] 
			) );
			$product_months = $basemode_alipay->findone ( 'months', 'webvpnproduct', array (
					'id' => $product_id 
			) );
			$money = $basemode_alipay->findone ( 'money', 'webpay', array (
					'id' => $alipaydata ['out_trade_no'] 
			) );
			if ($money != $$alipaydata ['total_fee']) {
				$addtime = $this->setUserExpiration ( $username, $product_num * $product_months );
				// var_dump("\r\n 瀹夊崜鏈堜唤涓� ".$product_num*$product_months);
				$basemod1 = &m ( 'BASE' );
				if ($addtime === false) {
					$basemode_alipay->updates ( array (
							'status' => '-1',
							'trade_no' => $alipaydata ['trade_no'],
							'trade_type' => 'alipay' 
					), 'webpay', array (
							'id' => $alipaydata ['out_trade_no'] 
					) );
					$dbdata ['time'] = date ( "Y-m-d H:i:s", time () );
					$dbdata ['status'] = - 1;
					$basemode_alipay->insert ( $dbdata, 'webtradelog' );
					echo ('false');
				} else {
					$basemode_alipay->updates ( array (
							'status' => '1',
							'trade_no' => $alipaydata ['trade_no'],
							'trade_type' => 'alipay',
							'finish_time' => date ( "Y-m-d H:i:s", time () ) 
					), 'webpay', array (
							'id' => $alipaydata ['out_trade_no'] 
					) );
					$dbdata ['time'] = date ( "Y-m-d H:i:s", time () );
					$dbdata ['status'] = 1;
					$basemode_alipay->insert ( $dbdata, 'webtradelog' );
					echo ('true');
				}
			} else {
				echo "money is error";
			}
		} else {
			echo ('false');
		}
		// var_dump($paytoken);
		// var_dump('浠ヤ笂涓簍oken');
		// $basenew=&m('BASE');
	}
	function createPayForAlipay() {
		file_put_contents ( 'IAPpayLog.txt', "缃戝潃 \r\n", FILE_APPEND );
		file_put_contents ( 'IAPpayLog.txt', json_encode ( $_GET ), FILE_APPEND );
		file_put_contents ( 'IAPpayLog.txt', "缃戝潃缁撴潫 \r\n", FILE_APPEND );
		if ($_GET ['username'] == $this->user->username and ! empty ( $_GET ['product_id'] ) and ! empty ( $_GET ['product_num'] ) and ! empty ( $_GET ['money'] )) {
			
			$username = $this->user->username;
			// echo($_GET['product_id']);
			apiOutPut ( $this->createpay ( $username, $_GET ['product_id'], $_GET ['product_num'], $_GET ['money'], 'alipay' ) );
		} else {
			apiOutPut ( false );
		}
	}
	function createpay($username, $product_id, $product_num, $money, $paytype) {
		$basemode1 = &m ( 'BASE' );
		// 鍟嗗搧锛屾暟閲忥紝浠锋牸鍚堟硶鎬ц瘑鍒�
		// die($basemode->findone('id','webvpnproduct',array('IAP_id'=>$info['product_id'])));
		// die($product_id);
		switch ($paytype) {
			case 'alipay' :
				$product_price = $basemode1->findone ( 'alipayprice', 'webvpnproduct', array (
						'id' => $product_id 
				) );
				break;
			case 'IAP' :
				$product_price = $basemode1->findone ( 'price', 'webvpnproduct', array (
						'id' => $product_id 
				) );
				break;
			default :
				return false;
		}
		// if($money!=$product_price*$product_num){ return false;}
		// die($product_price);
		
		do {
			$prov = mt_rand ( 100000000, 999999999 );
			$prov1 = mt_rand ( 100000000, 999999999 );
			$prov2 = mt_rand ( 100000000, 999999999 );
			$prov3 = mt_rand ( 100000000, 999999999 );
			$token = base64_encode ( $prov ) . base64_encode ( $prov1 ) . base64_encode ( $prov2 ) . base64_encode ( $prov3 );
		} while ( $basemode1->findone ( 'id', 'webpay', array (
				'checkid' => $token 
		) ) !== false );
		
		// var_dump($token);
		$payinfo = array (
				'userid' => $basemode1->findone ( 'id', 'radcheck', array (
						'username' => $username,
						'attribute' => 'Cleartext-Password' 
				) ),
				'money' => $money,
				'product' => $product_id,
				'product_number' => $product_num,
				'checkid' => $token 
		);
		if (! $basemode1->insert ( $payinfo, 'webpay' )) {
			return false;
		}
		
		return ($basemode1->findone ( 'id', 'webpay', array (
				'checkid' => $token 
		) ));
	}
	function payFromIos() {
		$this->logining ();
		$username = $_GET ['user'];
		// 鐢ㄦ埛缁熶竴鏆傛椂鏈仛
		// 鏈嶅姟鍣ㄤ簩娆￠獙璇佷唬鐮�
		// die('123213');
		$basemode = &m ( 'BASE' );
		// 鑾峰彇 App 鍙戦�佽繃鏉ョ殑鏁版嵁,璁剧疆鏃跺�欐槸娌欑洅鐘舵��
		$receipt = $_GET ['data'];
		$isSandbox = true;
		// 寮�濮嬫墽琛岄獙璇�
		try {
			
			$info = $this->getReceiptData ( $receipt, $isSandbox );
			file_put_contents ( 'IAPpayLog.txt', "缃戝潃 \r\n", FILE_APPEND );
			file_put_contents ( 'IAPpayLog.txt', json_encode ( $info ), FILE_APPEND );
			file_put_contents ( 'IAPpayLog.txt', "缃戝潃缁撴潫 \r\n", FILE_APPEND );
			
			// 楠岃瘉浜ゆ槗鍙锋槸鍚﹀凡澶勭悊
			// var_dump($basemode->findone('id','webtradelog',array('trade_no'=>$info['transaction_id'])));
			// var_dump($info['transaction_id']);
			if ($basemode->findone ( 'id', 'webtradelog', array (
					'trade_no' => $info ['transaction_id'] 
			) ) !== false) {
				// die('1111');
				apiOutPut ( false );
			}
			
			// die('123213');
			// 鍒涘缓璁㈠崟
			//
			// var_dump($basemode->findone('id','webtradelog',array('trade_no'=>intval($info['transaction_id'])))!==false);
			// var_dump($basemode->findone('id','webvpnproduct',array('IAP_id'=>$info['product_id'])));
			$payid = $this->createpay ( $username, $basemode->findone ( 'id', 'webvpnproduct', array (
					'IAP_id' => $info ['product_id'] 
			) ), $info ['quantity'], 0, 'IAP' );
			// die($payid);
			// die('123213');
			// die($payid);
			if ($payid !== false) {
				// 鍐欏叆鏃ュ織
				$dbdata = array (
						'time' => date ( "Y-m-d H:i:s", time () ),
						'trade_no' => $info ['transaction_id'],
						'payid' => $payid,
						'trade_type' => 'IAP',
						'description' => json_encode ( $info ),
						'money' => 0,
						'product_name' => $basemode->findone ( 'product', 'webvpnproduct', array (
								'IAP_id' => $info ['product_id'] 
						) ) 
				);
				// 'buyer_alipay_id'=>$alipaydata['seller_id'],
				// 'buyer_email'=>$alipaydata['buyer_email'],
				// 'alipay_status'=>$alipay_status['trade_status ']
				
				$dbdata ['time'] = date ( "Y-m-d H:i:s", time () );
				$dbdata ['status'] = 0;
				$basemode->insert ( $dbdata, 'webtradelog' );
				
				$product_months = $basemode->findone ( 'months', 'webvpnproduct', array (
						'IAP_id' => $info ['product_id'] 
				) );
				file_put_contents ( 'IAPpayLog.txt', "\r\n 鏈堟暟涓� $product_months \r\n", FILE_APPEND );
				
				$allmonths = intval ( $info ['quantity'] ) * intval ( $product_months );
				file_put_contents ( 'IAPpayLog.txt', $allmonths, FILE_APPEND );
				$addtime = $this->setUserExpiration ( $username, $allmonths );
				if ($addtime === false) {
					$basemode->update ( array (
							'status' => - 1,
							'trade_no' => $info ['transaction_id'],
							'trade_type' => 'IAP' 
					), 'webpay', array (
							'id' => $payid 
					) );
					$dbdata ['time'] = date ( "Y-m-d H:i:s", time () );
					$dbdata ['status'] = - 1;
					$basemode->insert ( $dbdata, 'webtradelog' );
					apiOutPut ( false );
				} else {
					$basemode->update ( array (
							'status' => 1,
							'trade_no' => $info ['transaction_id'],
							'trade_type' => 'IAP',
							'finish_time' => date ( "Y-m-d H:i:s", time () ) 
					), 'webpay', array (
							'id' => $payid 
					) );
					$dbdata ['time'] = date ( "Y-m-d H:i:s", time () );
					$dbdata ['status'] = 1;
					$basemode->insert ( $dbdata, 'webtradelog' );
					// apiOutPut(true);
				}
			} else {
				apiOutPut ( false );
			}
			
			// 娣诲姞鏃堕棿
			
			// 瀹屾垚璁㈠崟
			
			// 瀹屾垚鏃ュ織
			
			/*
			 * $dbdata=array(
			 * 'time'=>date("Y-m-d H:i:s" ,time()),
			 * 'trade_no'=>$info['transaction_id'],
			 * 'payid'=>$_GET['out_trade_no'],
			 * 'trade_type'=>'IAP',
			 * 'description'=>json_encode($info),
			 * //'money'=>$alipaydata['total_fee'],
			 * 'product_name'=>$basemode->findone('product','webvpnproduct',array('IAP_id'=>$info['product_id'])),
			 * //'buyer_alipay_id'=>$alipaydata['seller_id'],
			 * //'buyer_email'=>$alipaydata['buyer_email'],
			 * //'alipay_status'=>$alipay_status['trade_status ']
			 * );
			 */
			// 閫氳繃product_id 鏉ュ垽鏂槸涓嬭浇鍝釜璧勬簮
			
			// switch($info['product_id']){
			
			// case 'com.application.xxxxx.xxxx':
			// Header("Location:xxxx.zip");
			// break;
			// }
			apiOutPut ( $this->getExpiration ( $this->user->username ) );
		} // 鎹曡幏寮傚父
catch ( Exception $e ) {
			file_put_contents ( 'IAPpayLog.txt', "start \r\n", FILE_APPEND );
			file_put_contents ( 'IAPpayLog.txt', $e->getMessage (), FILE_APPEND );
			// die('123123');
			file_put_contents ( 'IAPpayLog.txt', "end \r\n", FILE_APPEND );
			apiOutPut ( false );
		}
	}
	function getReceiptData($receipt, $isSandbox = false) {
		if ($isSandbox) {
			$endpoint = 'https://sandbox.itunes.apple.com/verifyReceipt';
		} else {
			$endpoint = 'https://buy.itunes.apple.com/verifyReceipt';
		}
		
		$postData = json_encode ( array (
				'receipt-data' => $receipt 
		) );
		
		$ch = curl_init ( $endpoint );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postData );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 ); // 杩欎袱琛屼竴瀹氳鍔狅紝涓嶅姞浼氭姤SSL 閿欒
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		
		$response = curl_exec ( $ch );
		$errno = curl_errno ( $ch );
		$errmsg = curl_error ( $ch );
		curl_close ( $ch );
		// 鍒ゆ柇鏃跺�欏嚭閿欙紝鎶涘嚭寮傚父
		if ($errno != 0) {
			throw new Exception ( $errmsg, $errno );
		}
		
		$data = json_decode ( $response );
		// 鍒ゆ柇杩斿洖鐨勬暟鎹槸鍚︽槸瀵硅薄
		if (! is_object ( $data )) {
			throw new Exception ( 'Invalid response data' );
		}
		// 鍒ゆ柇璐拱鏃跺�欐垚鍔�
		if (! isset ( $data->status ) || $data->status != 0) {
			throw new Exception ( 'Invalid receipt' );
		}
		
		// 杩斿洖浜у搧鐨勪俊鎭�
		return array (
				'quantity' => $data->receipt->quantity,
				'product_id' => $data->receipt->product_id,
				'transaction_id' => $data->receipt->transaction_id,
				'purchase_date' => $data->receipt->purchase_date,
				'app_item_id' => $data->receipt->app_item_id,
				'bid' => $data->receipt->bid,
				'bvrs' => $data->receipt->bvrs 
		);
	}
}