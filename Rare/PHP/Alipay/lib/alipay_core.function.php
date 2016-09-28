<?php
/*
 * 鏀粯瀹濇帴鍙ｅ叕鐢ㄥ嚱鏁�
 * 璇︾粏锛氳绫绘槸璇锋眰銆侀�氱煡杩斿洖涓や釜鏂囦欢鎵�璋冪敤鐨勫叕鐢ㄥ嚱鏁版牳蹇冨鐞嗘枃浠�
 * 鐗堟湰锛�3.3
 * 鏃ユ湡锛�2012-07-19
 * 璇存槑锛�
 * 浠ヤ笅浠ｇ爜鍙槸涓轰簡鏂逛究鍟嗘埛娴嬭瘯鑰屾彁渚涚殑鏍蜂緥浠ｇ爜锛屽晢鎴峰彲浠ユ牴鎹嚜宸辩綉绔欑殑闇�瑕侊紝鎸夌収鎶�鏈枃妗ｇ紪鍐�,骞堕潪涓�瀹氳浣跨敤璇ヤ唬鐮併��
 * 璇ヤ唬鐮佷粎渚涘涔犲拰鐮旂┒鏀粯瀹濇帴鍙ｄ娇鐢紝鍙槸鎻愪緵涓�涓弬鑰冦��
 */

/**
 * 鎶婃暟缁勬墍鏈夊厓绱狅紝鎸夌収鈥滃弬鏁�=鍙傛暟鍊尖�濈殑妯″紡鐢ㄢ��&鈥濆瓧绗︽嫾鎺ユ垚瀛楃涓�
 *
 * @param $para 闇�瑕佹嫾鎺ョ殑鏁扮粍
 *        	return 鎷兼帴瀹屾垚浠ュ悗鐨勫瓧绗︿覆
 */
function createLinkstring($para) {
	$arg = "";
	while ( list ( $key, $val ) = each ( $para ) ) {
		$arg .= $key . "=" . $val . "&";
	}
	// 鍘绘帀鏈�鍚庝竴涓�&瀛楃
	$arg = substr ( $arg, 0, count ( $arg ) - 2 );
	
	// 濡傛灉瀛樺湪杞箟瀛楃锛岄偅涔堝幓鎺夎浆涔�
	if (get_magic_quotes_gpc ()) {
		$arg = stripslashes ( $arg );
	}
	
	return $arg;
}
/**
 * 鎶婃暟缁勬墍鏈夊厓绱狅紝鎸夌収鈥滃弬鏁�=鍙傛暟鍊尖�濈殑妯″紡鐢ㄢ��&鈥濆瓧绗︽嫾鎺ユ垚瀛楃涓诧紝骞跺瀛楃涓插仛urlencode缂栫爜
 *
 * @param $para 闇�瑕佹嫾鎺ョ殑鏁扮粍
 *        	return 鎷兼帴瀹屾垚浠ュ悗鐨勫瓧绗︿覆
 */
function createLinkstringUrlencode($para) {
	$arg = "";
	while ( list ( $key, $val ) = each ( $para ) ) {
		$arg .= $key . "=" . urlencode ( $val ) . "&";
	}
	// 鍘绘帀鏈�鍚庝竴涓�&瀛楃
	$arg = substr ( $arg, 0, count ( $arg ) - 2 );
	
	// 濡傛灉瀛樺湪杞箟瀛楃锛岄偅涔堝幓鎺夎浆涔�
	if (get_magic_quotes_gpc ()) {
		$arg = stripslashes ( $arg );
	}
	
	return $arg;
}
/**
 * 闄ゅ幓鏁扮粍涓殑绌哄�煎拰绛惧悕鍙傛暟
 *
 * @param $para 绛惧悕鍙傛暟缁�
 *        	return 鍘绘帀绌哄�间笌绛惧悕鍙傛暟鍚庣殑鏂扮鍚嶅弬鏁扮粍
 */
function paraFilter($para) {
	$para_filter = array ();
	while ( list ( $key, $val ) = each ( $para ) ) {
		if ($key == "sign" || $key == "sign_type" || $val == "")
			continue;
		else
			$para_filter [$key] = $para [$key];
	}
	return $para_filter;
}
/**
 * 瀵规暟缁勬帓搴�
 *
 * @param $para 鎺掑簭鍓嶇殑鏁扮粍
 *        	return 鎺掑簭鍚庣殑鏁扮粍
 */
function argSort($para) {
	ksort ( $para );
	reset ( $para );
	return $para;
}
/**
 * 鍐欐棩蹇楋紝鏂逛究娴嬭瘯锛堢湅缃戠珯闇�姹傦紝涔熷彲浠ユ敼鎴愭妸璁板綍瀛樺叆鏁版嵁搴擄級
 * 娉ㄦ剰锛氭湇鍔″櫒闇�瑕佸紑閫歠open閰嶇疆
 *
 * @param $word 瑕佸啓鍏ユ棩蹇楅噷鐨勬枃鏈唴瀹�
 *        	榛樿鍊硷細绌哄��
 */
function logResult($word = '') {
	$fp = fopen ( "log.txt", "a" );
	flock ( $fp, LOCK_EX );
	fwrite ( $fp, "鎵ц鏃ユ湡锛�" . strftime ( "%Y%m%d%H%M%S", time () ) . "\n" . $word . "\n" );
	flock ( $fp, LOCK_UN );
	fclose ( $fp );
}

/**
 * 杩滅▼鑾峰彇鏁版嵁锛孭OST妯″紡
 * 娉ㄦ剰锛�
 * 1.浣跨敤Crul闇�瑕佷慨鏀规湇鍔″櫒涓璸hp.ini鏂囦欢鐨勮缃紝鎵惧埌php_curl.dll鍘绘帀鍓嶉潰鐨�";"灏辫浜�
 * 2.鏂囦欢澶逛腑cacert.pem鏄疭SL璇佷功璇蜂繚璇佸叾璺緞鏈夋晥锛岀洰鍓嶉粯璁よ矾寰勬槸锛歡etcwd().'\\cacert.pem'
 *
 * @param $url 鎸囧畾URL瀹屾暣璺緞鍦板潃        	
 * @param $cacert_url 鎸囧畾褰撳墠宸ヤ綔鐩綍缁濆璺緞        	
 * @param $para 璇锋眰鐨勬暟鎹�        	
 * @param $input_charset 缂栫爜鏍煎紡銆傞粯璁ゅ�硷細绌哄��
 *        	return 杩滅▼杈撳嚭鐨勬暟鎹�
 */
function getHttpResponsePOST($url, $cacert_url, $para, $input_charset = '') {
	if (trim ( $input_charset ) != '') {
		$url = $url . "_input_charset=" . $input_charset;
	}
	$curl = curl_init ( $url );
	curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, true ); // SSL璇佷功璁よ瘉
	curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 2 ); // 涓ユ牸璁よ瘉
	curl_setopt ( $curl, CURLOPT_CAINFO, $cacert_url ); // 璇佷功鍦板潃
	curl_setopt ( $curl, CURLOPT_HEADER, 0 ); // 杩囨护HTTP澶�
	curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 鏄剧ず杈撳嚭缁撴灉
	curl_setopt ( $curl, CURLOPT_POST, true ); // post浼犺緭鏁版嵁
	curl_setopt ( $curl, CURLOPT_POSTFIELDS, $para ); // post浼犺緭鏁版嵁
	$responseText = curl_exec ( $curl );
	// var_dump( curl_error($curl) );//濡傛灉鎵цcurl杩囩▼涓嚭鐜板紓甯革紝鍙墦寮�姝ゅ紑鍏筹紝浠ヤ究鏌ョ湅寮傚父鍐呭
	curl_close ( $curl );
	
	return $responseText;
}

/**
 * 杩滅▼鑾峰彇鏁版嵁锛孏ET妯″紡
 * 娉ㄦ剰锛�
 * 1.浣跨敤Crul闇�瑕佷慨鏀规湇鍔″櫒涓璸hp.ini鏂囦欢鐨勮缃紝鎵惧埌php_curl.dll鍘绘帀鍓嶉潰鐨�";"灏辫浜�
 * 2.鏂囦欢澶逛腑cacert.pem鏄疭SL璇佷功璇蜂繚璇佸叾璺緞鏈夋晥锛岀洰鍓嶉粯璁よ矾寰勬槸锛歡etcwd().'\\cacert.pem'
 *
 * @param $url 鎸囧畾URL瀹屾暣璺緞鍦板潃        	
 * @param $cacert_url 鎸囧畾褰撳墠宸ヤ綔鐩綍缁濆璺緞
 *        	return 杩滅▼杈撳嚭鐨勬暟鎹�
 */
function getHttpResponseGET($url, $cacert_url) {
	$curl = curl_init ( $url );
	curl_setopt ( $curl, CURLOPT_HEADER, 0 ); // 杩囨护HTTP澶�
	curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 鏄剧ず杈撳嚭缁撴灉
	curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, true ); // SSL璇佷功璁よ瘉
	curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 2 ); // 涓ユ牸璁よ瘉
	curl_setopt ( $curl, CURLOPT_CAINFO, $cacert_url ); // 璇佷功鍦板潃
	$responseText = curl_exec ( $curl );
	// var_dump( curl_error($curl) );//濡傛灉鎵цcurl杩囩▼涓嚭鐜板紓甯革紝鍙墦寮�姝ゅ紑鍏筹紝浠ヤ究鏌ョ湅寮傚父鍐呭
	curl_close ( $curl );
	
	return $responseText;
}

/**
 * 瀹炵幇澶氱瀛楃缂栫爜鏂瑰紡
 *
 * @param $input 闇�瑕佺紪鐮佺殑瀛楃涓�        	
 * @param $_output_charset 杈撳嚭鐨勭紪鐮佹牸寮�        	
 * @param $_input_charset 杈撳叆鐨勭紪鐮佹牸寮�
 *        	return 缂栫爜鍚庣殑瀛楃涓�
 */
function charsetEncode($input, $_output_charset, $_input_charset) {
	$output = "";
	if (! isset ( $_output_charset ))
		$_output_charset = $_input_charset;
	if ($_input_charset == $_output_charset || $input == null) {
		$output = $input;
	} elseif (function_exists ( "mb_convert_encoding" )) {
		$output = mb_convert_encoding ( $input, $_output_charset, $_input_charset );
	} elseif (function_exists ( "iconv" )) {
		$output = iconv ( $_input_charset, $_output_charset, $input );
	} else
		die ( "sorry, you have no libs support for charset change." );
	return $output;
}
/**
 * 瀹炵幇澶氱瀛楃瑙ｇ爜鏂瑰紡
 *
 * @param $input 闇�瑕佽В鐮佺殑瀛楃涓�        	
 * @param $_output_charset 杈撳嚭鐨勮В鐮佹牸寮�        	
 * @param $_input_charset 杈撳叆鐨勮В鐮佹牸寮�
 *        	return 瑙ｇ爜鍚庣殑瀛楃涓�
 */
function charsetDecode($input, $_input_charset, $_output_charset) {
	$output = "";
	if (! isset ( $_input_charset ))
		$_input_charset = $_input_charset;
	if ($_input_charset == $_output_charset || $input == null) {
		$output = $input;
	} elseif (function_exists ( "mb_convert_encoding" )) {
		$output = mb_convert_encoding ( $input, $_output_charset, $_input_charset );
	} elseif (function_exists ( "iconv" )) {
		$output = iconv ( $_input_charset, $_output_charset, $input );
	} else
		die ( "sorry, you have no libs support for charset changes." );
	return $output;
}
?>