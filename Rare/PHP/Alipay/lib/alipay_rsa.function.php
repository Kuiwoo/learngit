<?php
/*
 * 鏀粯瀹濇帴鍙SA鍑芥暟
 * 璇︾粏锛歊SA绛惧悕銆侀獙绛俱�佽В瀵�
 * 鐗堟湰锛�3.3
 * 鏃ユ湡锛�2012-07-23
 * 璇存槑锛�
 * 浠ヤ笅浠ｇ爜鍙槸涓轰簡鏂逛究鍟嗘埛娴嬭瘯鑰屾彁渚涚殑鏍蜂緥浠ｇ爜锛屽晢鎴峰彲浠ユ牴鎹嚜宸辩綉绔欑殑闇�瑕侊紝鎸夌収鎶�鏈枃妗ｇ紪鍐�,骞堕潪涓�瀹氳浣跨敤璇ヤ唬鐮併��
 * 璇ヤ唬鐮佷粎渚涘涔犲拰鐮旂┒鏀粯瀹濇帴鍙ｄ娇鐢紝鍙槸鎻愪緵涓�涓弬鑰冦��
 */

/**
 * RSA绛惧悕
 *
 * @param $data 寰呯鍚嶆暟鎹�        	
 * @param $private_key_path 鍟嗘埛绉侀挜鏂囦欢璺緞
 *        	return 绛惧悕缁撴灉
 */
function rsaSign($data, $private_key_path) {
	$priKey = file_get_contents ( $private_key_path );
	$res = openssl_get_privatekey ( $priKey );
	openssl_sign ( $data, $sign, $res );
	openssl_free_key ( $res );
	// base64缂栫爜
	$sign = base64_encode ( $sign );
	return $sign;
}

/**
 * RSA楠岀
 *
 * @param $data 寰呯鍚嶆暟鎹�        	
 * @param $ali_public_key_path 鏀粯瀹濈殑鍏挜鏂囦欢璺緞        	
 * @param $sign 瑕佹牎瀵圭殑鐨勭鍚嶇粨鏋�
 *        	return 楠岃瘉缁撴灉
 */
function rsaVerify($data, $ali_public_key_path, $sign) {
	$pubKey = file_get_contents ( $ali_public_key_path );
	$res = openssl_get_publickey ( $pubKey );
	$result = ( bool ) openssl_verify ( $data, base64_decode ( $sign ), $res );
	openssl_free_key ( $res );
	return $result;
}

/**
 * RSA瑙ｅ瘑
 *
 * @param $content 闇�瑕佽В瀵嗙殑鍐呭锛屽瘑鏂�        	
 * @param $private_key_path 鍟嗘埛绉侀挜鏂囦欢璺緞
 *        	return 瑙ｅ瘑鍚庡唴瀹癸紝鏄庢枃
 */
function rsaDecrypt($content, $private_key_path) {
	$priKey = file_get_contents ( $private_key_path );
	$res = openssl_get_privatekey ( $priKey );
	// 鐢╞ase64灏嗗唴瀹硅繕鍘熸垚浜岃繘鍒�
	$content = base64_decode ( $content );
	// 鎶婇渶瑕佽В瀵嗙殑鍐呭锛屾寜128浣嶆媶寮�瑙ｅ瘑
	$result = '';
	for($i = 0; $i < strlen ( $content ) / 128; $i ++) {
		$data = substr ( $content, $i * 128, 128 );
		openssl_private_decrypt ( $data, $decrypt, $res );
		$result .= $decrypt;
	}
	openssl_free_key ( $res );
	return $result;
}
?>