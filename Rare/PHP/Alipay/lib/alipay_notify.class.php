<?php
/*
 * 绫诲悕锛欰lipayNotify
 * 鍔熻兘锛氭敮浠樺疂閫氱煡澶勭悊绫�
 * 璇︾粏锛氬鐞嗘敮浠樺疂鍚勬帴鍙ｉ�氱煡杩斿洖
 * 鐗堟湰锛�3.2
 * 鏃ユ湡锛�2011-03-25
 * 璇存槑锛�
 * 浠ヤ笅浠ｇ爜鍙槸涓轰簡鏂逛究鍟嗘埛娴嬭瘯鑰屾彁渚涚殑鏍蜂緥浠ｇ爜锛屽晢鎴峰彲浠ユ牴鎹嚜宸辩綉绔欑殑闇�瑕侊紝鎸夌収鎶�鏈枃妗ｇ紪鍐�,骞堕潪涓�瀹氳浣跨敤璇ヤ唬鐮併��
 * 璇ヤ唬鐮佷粎渚涘涔犲拰鐮旂┒鏀粯瀹濇帴鍙ｄ娇鐢紝鍙槸鎻愪緵涓�涓弬鑰�
 *
 * ************************娉ㄦ剰*************************
 * 璋冭瘯閫氱煡杩斿洖鏃讹紝鍙煡鐪嬫垨鏀瑰啓log鏃ュ織鐨勫啓鍏XT閲岀殑鏁版嵁锛屾潵妫�鏌ラ�氱煡杩斿洖鏄惁姝ｅ父
 */
require_once ("alipay_core.function.php");
require_once ("alipay_rsa.function.php");
class AlipayNotify {
	/**
	 * HTTPS褰㈠紡娑堟伅楠岃瘉鍦板潃
	 */
	var $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
	/**
	 * HTTP褰㈠紡娑堟伅楠岃瘉鍦板潃
	 */
	var $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';
	var $alipay_config;
	function __construct($alipay_config) {
		$this->alipay_config = $alipay_config;
	}
	function AlipayNotify($alipay_config) {
		$this->__construct ( $alipay_config );
	}
	/**
	 * 閽堝notify_url楠岃瘉娑堟伅鏄惁鏄敮浠樺疂鍙戝嚭鐨勫悎娉曟秷鎭�
	 *
	 * @return 楠岃瘉缁撴灉
	 */
	function verifyNotify() {
		if (empty ( $_POST )) { // 鍒ゆ柇POST鏉ョ殑鏁扮粍鏄惁涓虹┖
			return false;
		} else {
			// 鐢熸垚绛惧悕缁撴灉
			$isSign = $this->getSignVeryfy ( $_POST, $_POST ["sign"] );
			// 鑾峰彇鏀粯瀹濊繙绋嬫湇鍔″櫒ATN缁撴灉锛堥獙璇佹槸鍚︽槸鏀粯瀹濆彂鏉ョ殑娑堟伅锛�
			$responseTxt = 'false';
			if (! empty ( $_POST ["notify_id"] )) {
				$responseTxt = $this->getResponse ( $_POST ["notify_id"] );
			}
			
			// 鍐欐棩蹇楄褰�
			// if ($isSign) {
			// $isSignStr = 'true';
			// }
			// else {
			// $isSignStr = 'false';
			// }
			// $log_text = "responseTxt=".$responseTxt."\n notify_url_log:isSign=".$isSignStr.",";
			// $log_text = $log_text.createLinkString($_POST);
			// logResult($log_text);
			
			// 楠岃瘉
			// $responsetTxt鐨勭粨鏋滀笉鏄痶rue锛屼笌鏈嶅姟鍣ㄨ缃棶棰樸�佸悎浣滆韩浠借�匢D銆乶otify_id涓�鍒嗛挓澶辨晥鏈夊叧
			// isSign鐨勭粨鏋滀笉鏄痶rue锛屼笌瀹夊叏鏍￠獙鐮併�佽姹傛椂鐨勫弬鏁版牸寮忥紙濡傦細甯﹁嚜瀹氫箟鍙傛暟绛夛級銆佺紪鐮佹牸寮忔湁鍏�
			if (preg_match ( "/true$/i", $responseTxt ) && $isSign) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	/**
	 * 閽堝return_url楠岃瘉娑堟伅鏄惁鏄敮浠樺疂鍙戝嚭鐨勫悎娉曟秷鎭�
	 *
	 * @return 楠岃瘉缁撴灉
	 */
	function verifyReturn() {
		if (empty ( $_GET )) { // 鍒ゆ柇POST鏉ョ殑鏁扮粍鏄惁涓虹┖
			return false;
		} else {
			// 鐢熸垚绛惧悕缁撴灉
			$isSign = $this->getSignVeryfy ( $_GET, $_GET ["sign"] );
			// 鑾峰彇鏀粯瀹濊繙绋嬫湇鍔″櫒ATN缁撴灉锛堥獙璇佹槸鍚︽槸鏀粯瀹濆彂鏉ョ殑娑堟伅锛�
			$responseTxt = 'false';
			if (! empty ( $_GET ["notify_id"] )) {
				$responseTxt = $this->getResponse ( $_GET ["notify_id"] );
			}
			
			// 鍐欐棩蹇楄褰�
			// if ($isSign) {
			// $isSignStr = 'true';
			// }
			// else {
			// $isSignStr = 'false';
			// }
			// $log_text = "responseTxt=".$responseTxt."\n return_url_log:isSign=".$isSignStr.",";
			// $log_text = $log_text.createLinkString($_GET);
			// logResult($log_text);
			
			// 楠岃瘉
			// $responsetTxt鐨勭粨鏋滀笉鏄痶rue锛屼笌鏈嶅姟鍣ㄨ缃棶棰樸�佸悎浣滆韩浠借�匢D銆乶otify_id涓�鍒嗛挓澶辨晥鏈夊叧
			// isSign鐨勭粨鏋滀笉鏄痶rue锛屼笌瀹夊叏鏍￠獙鐮併�佽姹傛椂鐨勫弬鏁版牸寮忥紙濡傦細甯﹁嚜瀹氫箟鍙傛暟绛夛級銆佺紪鐮佹牸寮忔湁鍏�
			if (preg_match ( "/true$/i", $responseTxt ) && $isSign) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	/**
	 * 鑾峰彇杩斿洖鏃剁殑绛惧悕楠岃瘉缁撴灉
	 *
	 * @param $para_temp 閫氱煡杩斿洖鏉ョ殑鍙傛暟鏁扮粍        	
	 * @param $sign 杩斿洖鐨勭鍚嶇粨鏋�        	
	 * @return 绛惧悕楠岃瘉缁撴灉
	 */
	function getSignVeryfy($para_temp, $sign) {
		// 闄ゅ幓寰呯鍚嶅弬鏁版暟缁勪腑鐨勭┖鍊煎拰绛惧悕鍙傛暟
		$para_filter = paraFilter ( $para_temp );
		
		// 瀵瑰緟绛惧悕鍙傛暟鏁扮粍鎺掑簭
		$para_sort = argSort ( $para_filter );
		
		// 鎶婃暟缁勬墍鏈夊厓绱狅紝鎸夌収鈥滃弬鏁�=鍙傛暟鍊尖�濈殑妯″紡鐢ㄢ��&鈥濆瓧绗︽嫾鎺ユ垚瀛楃涓�
		$prestr = createLinkstring ( $para_sort );
		
		$isSgin = false;
		switch (strtoupper ( trim ( $this->alipay_config ['sign_type'] ) )) {
			case "RSA" :
				$isSgin = rsaVerify ( $prestr, trim ( $this->alipay_config ['ali_public_key_path'] ), $sign );
				break;
			default :
				$isSgin = false;
		}
		
		return $isSgin;
	}
	
	/**
	 * 鑾峰彇杩滅▼鏈嶅姟鍣ˋTN缁撴灉,楠岃瘉杩斿洖URL
	 *
	 * @param $notify_id 閫氱煡鏍￠獙ID        	
	 * @return 鏈嶅姟鍣ˋTN缁撴灉 楠岃瘉缁撴灉闆嗭細
	 *         invalid鍛戒护鍙傛暟涓嶅 鍑虹幇杩欎釜閿欒锛岃妫�娴嬭繑鍥炲鐞嗕腑partner鍜宬ey鏄惁涓虹┖
	 *         true 杩斿洖姝ｇ‘淇℃伅
	 *         false 璇锋鏌ラ槻鐏鎴栬�呮槸鏈嶅姟鍣ㄩ樆姝㈢鍙ｉ棶棰樹互鍙婇獙璇佹椂闂存槸鍚﹁秴杩囦竴鍒嗛挓
	 */
	function getResponse($notify_id) {
		$transport = strtolower ( trim ( $this->alipay_config ['transport'] ) );
		$partner = trim ( $this->alipay_config ['partner'] );
		$veryfy_url = '';
		if ($transport == 'https') {
			$veryfy_url = $this->https_verify_url;
		} else {
			$veryfy_url = $this->http_verify_url;
		}
		$veryfy_url = $veryfy_url . "partner=" . $partner . "&notify_id=" . $notify_id;
		$responseTxt = getHttpResponseGET ( $veryfy_url, $this->alipay_config ['cacert'] );
		
		return $responseTxt;
	}
}
?>
