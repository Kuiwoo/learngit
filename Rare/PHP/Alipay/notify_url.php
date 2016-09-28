<?php
/*
 * 鍔熻兘锛氭敮浠樺疂鏈嶅姟鍣ㄥ紓姝ラ�氱煡椤甸潰
 * 鐗堟湰锛�3.3
 * 鏃ユ湡锛�2012-07-23
 * 璇存槑锛�
 * 浠ヤ笅浠ｇ爜鍙槸涓轰簡鏂逛究鍟嗘埛娴嬭瘯鑰屾彁渚涚殑鏍蜂緥浠ｇ爜锛屽晢鎴峰彲浠ユ牴鎹嚜宸辩綉绔欑殑闇�瑕侊紝鎸夌収鎶�鏈枃妗ｇ紪鍐�,骞堕潪涓�瀹氳浣跨敤璇ヤ唬鐮併��
 * 璇ヤ唬鐮佷粎渚涘涔犲拰鐮旂┒鏀粯瀹濇帴鍙ｄ娇鐢紝鍙槸鎻愪緵涓�涓弬鑰冦��
 *
 *
 * ************************椤甸潰鍔熻兘璇存槑*************************
 * 鍒涘缓璇ラ〉闈㈡枃浠舵椂锛岃鐣欏績璇ラ〉闈㈡枃浠朵腑鏃犱换浣旽TML浠ｇ爜鍙婄┖鏍笺��
 * 璇ラ〉闈笉鑳藉湪鏈満鐢佃剳娴嬭瘯锛岃鍒版湇鍔″櫒涓婂仛娴嬭瘯銆傝纭繚澶栭儴鍙互璁块棶璇ラ〉闈€��
 * 璇ラ〉闈㈣皟璇曞伐鍏疯浣跨敤鍐欐枃鏈嚱鏁發ogResult锛岃鍑芥暟宸茶榛樿鍏抽棴锛岃alipay_notify_class.php涓殑鍑芥暟verifyNotify
 * 濡傛灉娌℃湁鏀跺埌璇ラ〉闈㈣繑鍥炵殑 success 淇℃伅锛屾敮浠樺疂浼氬湪24灏忔椂鍐呮寜涓�瀹氱殑鏃堕棿绛栫暐閲嶅彂閫氱煡
 */
ini_set ( 'display_errors', 1 ); // 璁剧疆寮�鍚敊璇彁绀�
error_reporting ( 'E_ALL & ~E_NOTICE ' ); // 閿欒绛夌骇鎻愮ず
file_put_contents ( 'AliPayLog.txt', "\r\n 鎺ユ敹鍒版敮浠橀�氱煡 \r\n", FILE_APPEND );
file_put_contents ( 'AliPayLog.txt', json_encode ( $_POST ), FILE_APPEND );
file_put_contents ( 'AliPayLog.txt', "\r\n POST鎺ユ敹瀹屾瘯 \r\n", FILE_APPEND );
// die('12321321');
require_once ("alipay.config.php");
require_once ("lib/alipay_notify.class.php");
// require_once("sql.php");
// 璁＄畻寰楀嚭閫氱煡楠岃瘉缁撴灉
$alipayNotify = new AlipayNotify ( $alipay_config );
$verify_result = $alipayNotify->verifyNotify ();

if ($verify_result) { // 楠岃瘉鎴愬姛
                      // ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                      // 璇峰湪杩欓噷鍔犱笂鍟嗘埛鐨勪笟鍔￠�昏緫绋嬪簭浠�
                      
	// 鈥斺�旇鏍规嵁鎮ㄧ殑涓氬姟閫昏緫鏉ョ紪鍐欑▼搴忥紙浠ヤ笅浠ｇ爜浠呬綔鍙傝�冿級鈥斺��
                      
	// 鑾峰彇鏀粯瀹濈殑閫氱煡杩斿洖鍙傛暟锛屽彲鍙傝�冩妧鏈枃妗ｄ腑鏈嶅姟鍣ㄥ紓姝ラ�氱煡鍙傛暟鍒楄〃
                      
	// 鍟嗘埛璁㈠崟鍙�
	file_put_contents ( 'AliPayLog.txt', "\r\n 鎺ユ敹楠岃瘉鎴愬姛 \r\n", FILE_APPEND );
	// file_put_contents('AliPayLog.txt',json_encode($_POST),FILE_APPEND);
	// file_put_contents('AliPayLog.txt',"\r\n POST鎺ユ敹瀹屾瘯 \r\n",FILE_APPEND);
	$out_trade_no = $_POST ['out_trade_no'];
	
	// 鏀粯瀹濅氦鏄撳彿
	
	$trade_no = $_POST ['trade_no'];
	
	file_put_contents ( 'AliPayLog.txt', "\r\n 璁板綍鎺ュ彈娆℃暟 \r\n", FILE_APPEND );
	
	// 浜ゆ槗鐘舵��
	$trade_status = $_POST ['trade_status'];
	file_put_contents ( 'AliPayLog.txt', "\r\n 寮�濮嬭繛鎺ュ埌鏁版嵁搴� \r\n", FILE_APPEND );
	$mysqli = new mysqli ( "123.56.183.145", "radius", "radius", "radius" );
	$mysqli->set_charset ( "utf8" );
	file_put_contents ( 'AliPayLog.txt', "\r\n 杩炴帴鍒版暟鎹簱鎴愬姛 \r\n", FILE_APPEND );
	// file_put_contents('AliPayLog.txt',$mysqli->query("select id from webpaytoken where paytoken='".$token."'"),FILE_APPEND);
	do {
		$prov = mt_rand ( 100000000, 999999999 );
		$prov1 = mt_rand ( 100000000, 999999999 );
		$prov2 = mt_rand ( 100000000, 999999999 );
		$prov3 = mt_rand ( 100000000, 999999999 );
		$token = base64_encode ( $prov ) . base64_encode ( $prov1 ) . base64_encode ( $prov2 ) . base64_encode ( $prov3 );
	} while ( $mysqli->query ( "select id from webpaytoken where paytoken='" . $token . "'" )->num_rows > 0 );
	file_put_contents ( 'AliPayLog.txt', "\r\n 鍒涘缓token鎴愬姛 \r\n", FILE_APPEND );
	if ($mysqli->query ( "insert into webpaytoken (paytoken,pay_id) values ('" . $token . "'," . $out_trade_no . ")" )) {
		file_put_contents ( 'AliPayLog.txt', "\r\n 澧炲姞token鎴愬姛 \r\n", FILE_APPEND );
	} else {
		// file_put_contents('AliPayLog.txt',json_encode($_POST),FILE_APPEND);
		file_put_contents ( 'AliPayLog.txt', "\r\n 鏉ヨ嚜浜庡鍔爐oken澶辫触" . date ( "Y-m-d H:i:s", time () ), FILE_APPEND );
	}
	// token瀹归敊鍑犵巼
	$jsondata = base64_encode ( json_encode ( $_POST ) );
	$apiurl = "http://123.56.183.145/index.php?app=user&act=alipay&paytoken=$token&alipaydata=$jsondata";
	$rest = file_get_contents ( $apiurl );
	file_put_contents ( 'AliPayLog.txt', "\r\n  寮�濮嬫帴鍙楀鐞嗙粨鏋�", FILE_APPEND );
	file_put_contents ( 'AliPayLog.txt', "\r\n  寮�鍚帴鍙�:$apiurl", FILE_APPEND );
	file_put_contents ( 'AliPayLog.txt', "\r\n 澶勭悊缁撴灉 :" . $rest . ' &&in time :' . date ( "Y-m-d H:i:s", time () ), FILE_APPEND );
	file_put_contents ( 'AliPayLog.txt', "\r\n 鏉ヨ嚜浜庢敮浠樻垚鍔�" . date ( "Y-m-d H:i:s", time () ), FILE_APPEND );
	file_put_contents ( 'AliPayLog.txt', "\r\n", FILE_APPEND );
	
	// if($_POST['trade_status'] == 'TRADE_FINISHED') {
	// 鍒ゆ柇璇ョ瑪璁㈠崟鏄惁鍦ㄥ晢鎴风綉绔欎腑宸茬粡鍋氳繃澶勭悊
	// 濡傛灉娌℃湁鍋氳繃澶勭悊锛屾牴鎹鍗曞彿锛坥ut_trade_no锛夊湪鍟嗘埛缃戠珯鐨勮鍗曠郴缁熶腑鏌ュ埌璇ョ瑪璁㈠崟鐨勮缁嗭紝骞舵墽琛屽晢鎴风殑涓氬姟绋嬪簭
	// 璇峰姟蹇呭垽鏂姹傛椂鐨則otal_fee銆乻eller_id涓庨�氱煡鏃惰幏鍙栫殑total_fee銆乻eller_id涓轰竴鑷寸殑
	// 濡傛灉鏈夊仛杩囧鐞嗭紝涓嶆墽琛屽晢鎴风殑涓氬姟绋嬪簭
	// file_put_contents('AliPayLog.txt',$_POST,FILE_APPEND);
	// file_put_contents('AliPayLog.txt','\r\n',FILE_APPEND);
	
	// 娉ㄦ剰锛�
	// 閫�娆炬棩鏈熻秴杩囧彲閫�娆炬湡闄愬悗锛堝涓変釜鏈堝彲閫�娆撅級锛屾敮浠樺疂绯荤粺鍙戦�佽浜ゆ槗鐘舵�侀�氱煡
	
	// 璋冭瘯鐢紝鍐欐枃鏈嚱鏁拌褰曠▼搴忚繍琛屾儏鍐垫槸鍚︽甯�
	// logResult("杩欓噷鍐欏叆鎯宠璋冭瘯鐨勪唬鐮佸彉閲忓�硷紝鎴栧叾浠栬繍琛岀殑缁撴灉璁板綍");
	// }
	// else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
	// 鍒ゆ柇璇ョ瑪璁㈠崟鏄惁鍦ㄥ晢鎴风綉绔欎腑宸茬粡鍋氳繃澶勭悊
	// 濡傛灉娌℃湁鍋氳繃澶勭悊锛屾牴鎹鍗曞彿锛坥ut_trade_no锛夊湪鍟嗘埛缃戠珯鐨勮鍗曠郴缁熶腑鏌ュ埌璇ョ瑪璁㈠崟鐨勮缁嗭紝骞舵墽琛屽晢鎴风殑涓氬姟绋嬪簭
	// 璇峰姟蹇呭垽鏂姹傛椂鐨則otal_fee銆乻eller_id涓庨�氱煡鏃惰幏鍙栫殑total_fee銆乻eller_id涓轰竴鑷寸殑
	// 濡傛灉鏈夊仛杩囧鐞嗭紝涓嶆墽琛屽晢鎴风殑涓氬姟绋嬪簭
	// file_put_contents('AliPayLog.txt',$_POST,FILE_APPEND);
	// file_put_contents('AliPayLog.txt','\r\n',FILE_APPEND);
	// 娉ㄦ剰锛�
	// 浠樻瀹屾垚鍚庯紝鏀粯瀹濈郴缁熷彂閫佽浜ゆ槗鐘舵�侀�氱煡
	
	// 璋冭瘯鐢紝鍐欐枃鏈嚱鏁拌褰曠▼搴忚繍琛屾儏鍐垫槸鍚︽甯�
	// logResult("杩欓噷鍐欏叆鎯宠璋冭瘯鐨勪唬鐮佸彉閲忓�硷紝鎴栧叾浠栬繍琛岀殑缁撴灉璁板綍");
	// }
	
	// 鈥斺�旇鏍规嵁鎮ㄧ殑涓氬姟閫昏緫鏉ョ紪鍐欑▼搴忥紙浠ヤ笂浠ｇ爜浠呬綔鍙傝�冿級鈥斺��
	
	echo "success"; // 璇蜂笉瑕佷慨鏀规垨鍒犻櫎
		                
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
} else {
	// 楠岃瘉澶辫触
	echo "fail";
	
	// 璋冭瘯鐢紝鍐欐枃鏈嚱鏁拌褰曠▼搴忚繍琛屾儏鍐垫槸鍚︽甯�
	// logResult("杩欓噷鍐欏叆鎯宠璋冭瘯鐨勪唬鐮佸彉閲忓�硷紝鎴栧叾浠栬繍琛岀殑缁撴灉璁板綍");
}
file_put_contents ( 'AliPayLog.txt', "end  \r\n", FILE_APPEND );
?>