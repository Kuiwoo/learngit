<?php
/*
 * 鍔熻兘锛氭敮浠樺疂椤甸潰璺宠浆鍚屾閫氱煡椤甸潰
 * 鐗堟湰锛�3.3
 * 鏃ユ湡锛�2012-07-23
 * 璇存槑锛�
 * 浠ヤ笅浠ｇ爜鍙槸涓轰簡鏂逛究鍟嗘埛娴嬭瘯鑰屾彁渚涚殑鏍蜂緥浠ｇ爜锛屽晢鎴峰彲浠ユ牴鎹嚜宸辩綉绔欑殑闇�瑕侊紝鎸夌収鎶�鏈枃妗ｇ紪鍐�,骞堕潪涓�瀹氳浣跨敤璇ヤ唬鐮併��
 * 璇ヤ唬鐮佷粎渚涘涔犲拰鐮旂┒鏀粯瀹濇帴鍙ｄ娇鐢紝鍙槸鎻愪緵涓�涓弬鑰冦��
 *
 * ************************椤甸潰鍔熻兘璇存槑*************************
 * 璇ラ〉闈㈠彲鍦ㄦ湰鏈虹數鑴戞祴璇�
 * 鍙斁鍏TML绛夌編鍖栭〉闈㈢殑浠ｇ爜銆佸晢鎴蜂笟鍔￠�昏緫绋嬪簭浠ｇ爜
 * 璇ラ〉闈㈠彲浠ヤ娇鐢≒HP寮�鍙戝伐鍏疯皟璇曪紝涔熷彲浠ヤ娇鐢ㄥ啓鏂囨湰鍑芥暟logResult锛岃鍑芥暟宸茶榛樿鍏抽棴锛岃alipay_notify_class.php涓殑鍑芥暟verifyReturn
 */
require_once ("alipay.config.php");
require_once ("lib/alipay_notify.class.php");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
// 璁＄畻寰楀嚭閫氱煡楠岃瘉缁撴灉
$alipayNotify = new AlipayNotify ( $alipay_config );
$verify_result = $alipayNotify->verifyReturn ();
if ($verify_result) { // 楠岃瘉鎴愬姛
                      // ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                      // 璇峰湪杩欓噷鍔犱笂鍟嗘埛鐨勪笟鍔￠�昏緫绋嬪簭浠ｇ爜
                      
	// 鈥斺�旇鏍规嵁鎮ㄧ殑涓氬姟閫昏緫鏉ョ紪鍐欑▼搴忥紙浠ヤ笅浠ｇ爜浠呬綔鍙傝�冿級鈥斺��
                      // 鑾峰彇鏀粯瀹濈殑閫氱煡杩斿洖鍙傛暟锛屽彲鍙傝�冩妧鏈枃妗ｄ腑椤甸潰璺宠浆鍚屾閫氱煡鍙傛暟鍒楄〃
                      
	// 鍟嗘埛璁㈠崟鍙�
	
	$out_trade_no = $_GET ['out_trade_no'];
	
	// 鏀粯瀹濅氦鏄撳彿
	
	$trade_no = $_GET ['trade_no'];
	
	// 浜ゆ槗鐘舵��
	$trade_status = $_GET ['trade_status'];
	
	if ($_GET ['trade_status'] == 'TRADE_FINISHED' || $_GET ['trade_status'] == 'TRADE_SUCCESS') {
		// 鍒ゆ柇璇ョ瑪璁㈠崟鏄惁鍦ㄥ晢鎴风綉绔欎腑宸茬粡鍋氳繃澶勭悊
		// 濡傛灉娌℃湁鍋氳繃澶勭悊锛屾牴鎹鍗曞彿锛坥ut_trade_no锛夊湪鍟嗘埛缃戠珯鐨勮鍗曠郴缁熶腑鏌ュ埌璇ョ瑪璁㈠崟鐨勮缁嗭紝骞舵墽琛屽晢鎴风殑涓氬姟绋嬪簭
		// 濡傛灉鏈夊仛杩囧鐞嗭紝涓嶆墽琛屽晢鎴风殑涓氬姟绋嬪簭
	} else {
		echo "trade_status=" . $_GET ['trade_status'];
	}
	
	echo "楠岃瘉鎴愬姛<br />";
	
	// 鈥斺�旇鏍规嵁鎮ㄧ殑涓氬姟閫昏緫鏉ョ紪鍐欑▼搴忥紙浠ヤ笂浠ｇ爜浠呬綔鍙傝�冿級鈥斺��
	
	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
} else {
	// 楠岃瘉澶辫触
	// 濡傝璋冭瘯锛岃鐪媋lipay_notify.php椤甸潰鐨剉erifyReturn鍑芥暟
	echo "楠岃瘉澶辫触";
}
?>
        <title>鏀粯瀹濇墜鏈虹綉绔欐敮浠樻帴鍙�</title>
</head>
<body>
</body>
</html>