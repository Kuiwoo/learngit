<?php
/*
 * 绫诲悕锛欰lipaySubmit
 * 鍔熻兘锛氭敮浠樺疂鍚勬帴鍙ｈ姹傛彁浜ょ被
 * 璇︾粏锛氭瀯閫犳敮浠樺疂鍚勬帴鍙ｈ〃鍗旽TML鏂囨湰锛岃幏鍙栬繙绋婬TTP鏁版嵁
 * 鐗堟湰锛�3.3
 * 鏃ユ湡锛�2012-07-23
 * 璇存槑锛�
 * 浠ヤ笅浠ｇ爜鍙槸涓轰簡鏂逛究鍟嗘埛娴嬭瘯鑰屾彁渚涚殑鏍蜂緥浠ｇ爜锛屽晢鎴峰彲浠ユ牴鎹嚜宸辩綉绔欑殑闇�瑕侊紝鎸夌収鎶�鏈枃妗ｇ紪鍐�,骞堕潪涓�瀹氳浣跨敤璇ヤ唬鐮併��
 * 璇ヤ唬鐮佷粎渚涘涔犲拰鐮旂┒鏀粯瀹濇帴鍙ｄ娇鐢紝鍙槸鎻愪緵涓�涓弬鑰冦��
 */
require_once ("alipay_core.function.php");
require_once ("alipay_rsa.function.php");
class AlipaySubmit {
	var $alipay_config;
	/**
	 * 鏀粯瀹濈綉鍏冲湴鍧�锛堟柊锛�
	 */
	var $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?';
	function __construct($alipay_config) {
		$this->alipay_config = $alipay_config;
	}
	function AlipaySubmit($alipay_config) {
		$this->__construct ( $alipay_config );
	}
	
	/**
	 * 鐢熸垚绛惧悕缁撴灉
	 *
	 * @param $para_sort 宸叉帓搴忚绛惧悕鐨勬暟缁�
	 *        	return 绛惧悕缁撴灉瀛楃涓�
	 */
	function buildRequestMysign($para_sort) {
		// 鎶婃暟缁勬墍鏈夊厓绱狅紝鎸夌収鈥滃弬鏁�=鍙傛暟鍊尖�濈殑妯″紡鐢ㄢ��&鈥濆瓧绗︽嫾鎺ユ垚瀛楃涓�
		$prestr = createLinkstring ( $para_sort );
		
		$mysign = "";
		switch (strtoupper ( trim ( $this->alipay_config ['sign_type'] ) )) {
			case "RSA" :
				$mysign = rsaSign ( $prestr, $this->alipay_config ['private_key_path'] );
				break;
			default :
				$mysign = "";
		}
		
		return $mysign;
	}
	
	/**
	 * 鐢熸垚瑕佽姹傜粰鏀粯瀹濈殑鍙傛暟鏁扮粍
	 *
	 * @param $para_temp 璇锋眰鍓嶇殑鍙傛暟鏁扮粍        	
	 * @return 瑕佽姹傜殑鍙傛暟鏁扮粍
	 */
	function buildRequestPara($para_temp) {
		// 闄ゅ幓寰呯鍚嶅弬鏁版暟缁勪腑鐨勭┖鍊煎拰绛惧悕鍙傛暟
		$para_filter = paraFilter ( $para_temp );
		
		// 瀵瑰緟绛惧悕鍙傛暟鏁扮粍鎺掑簭
		$para_sort = argSort ( $para_filter );
		
		// 鐢熸垚绛惧悕缁撴灉
		$mysign = $this->buildRequestMysign ( $para_sort );
		
		// 绛惧悕缁撴灉涓庣鍚嶆柟寮忓姞鍏ヨ姹傛彁浜ゅ弬鏁扮粍涓�
		$para_sort ['sign'] = $mysign;
		$para_sort ['sign_type'] = strtoupper ( trim ( $this->alipay_config ['sign_type'] ) );
		
		return $para_sort;
	}
	
	/**
	 * 鐢熸垚瑕佽姹傜粰鏀粯瀹濈殑鍙傛暟鏁扮粍
	 *
	 * @param $para_temp 璇锋眰鍓嶇殑鍙傛暟鏁扮粍        	
	 * @return 瑕佽姹傜殑鍙傛暟鏁扮粍瀛楃涓�
	 */
	function buildRequestParaToString($para_temp) {
		// 寰呰姹傚弬鏁版暟缁�
		$para = $this->buildRequestPara ( $para_temp );
		
		// 鎶婂弬鏁扮粍涓墍鏈夊厓绱狅紝鎸夌収鈥滃弬鏁�=鍙傛暟鍊尖�濈殑妯″紡鐢ㄢ��&鈥濆瓧绗︽嫾鎺ユ垚瀛楃涓诧紝骞跺瀛楃涓插仛urlencode缂栫爜
		$request_data = createLinkstringUrlencode ( $para );
		
		return $request_data;
	}
	
	/**
	 * 寤虹珛璇锋眰锛屼互琛ㄥ崟HTML褰㈠紡鏋勯�狅紙榛樿锛�
	 *
	 * @param $para_temp 璇锋眰鍙傛暟鏁扮粍        	
	 * @param $method 鎻愪氦鏂瑰紡銆備袱涓�煎彲閫夛細post銆乬et        	
	 * @param $button_name 纭鎸夐挳鏄剧ず鏂囧瓧        	
	 * @return 鎻愪氦琛ㄥ崟HTML鏂囨湰
	 */
	function buildRequestForm($para_temp, $method, $button_name) {
		// 寰呰姹傚弬鏁版暟缁�
		$para = $this->buildRequestPara ( $para_temp );
		$sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='" . $this->alipay_gateway_new . "_input_charset=" . trim ( strtolower ( $this->alipay_config ['input_charset'] ) ) . "' method='" . $method . "'>";
		while ( list ( $key, $val ) = each ( $para ) ) {
			$sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
		}
		
		// submit鎸夐挳鎺т欢璇蜂笉瑕佸惈鏈塶ame灞炴��
		$sHtml = $sHtml . "<input type='submit'  value='" . $button_name . "' style='display:none;'></form>";
		
		$sHtml = $sHtml . "<script>document.forms['alipaysubmit'].submit();</script>";
		
		return $sHtml;
	}
	
	/**
	 * 鐢ㄤ簬闃查挀楸硷紝璋冪敤鎺ュ彛query_timestamp鏉ヨ幏鍙栨椂闂存埑鐨勫鐞嗗嚱鏁�
	 * 娉ㄦ剰锛氳鍔熻兘PHP5鐜鍙婁互涓婃敮鎸侊紝鍥犳蹇呴』鏈嶅姟鍣ㄣ�佹湰鍦扮數鑴戜腑瑁呮湁鏀寔DOMDocument銆丼SL鐨凱HP閰嶇疆鐜銆傚缓璁湰鍦拌皟璇曟椂浣跨敤PHP寮�鍙戣蒋浠�
	 * return 鏃堕棿鎴冲瓧绗︿覆
	 */
	function query_timestamp() {
		$url = $this->alipay_gateway_new . "service=query_timestamp&partner=" . trim ( strtolower ( $this->alipay_config ['partner'] ) ) . "&_input_charset=" . trim ( strtolower ( $this->alipay_config ['input_charset'] ) );
		$encrypt_key = "";
		
		$doc = new DOMDocument ();
		$doc->load ( $url );
		$itemEncrypt_key = $doc->getElementsByTagName ( "encrypt_key" );
		$encrypt_key = $itemEncrypt_key->item ( 0 )->nodeValue;
		
		return $encrypt_key;
	}
}
?>