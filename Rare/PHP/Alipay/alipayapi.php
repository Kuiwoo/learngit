
<?php
/*
 * 鍔熻兘锛氭墜鏈虹綉绔欐敮浠樻帴鍙ｆ帴鍏ラ〉
 * 鐗堟湰锛�3.3
 * 淇敼鏃ユ湡锛�2012-07-23
 * 璇存槑锛�
 * 浠ヤ笅浠ｇ爜鍙槸涓轰簡鏂逛究鍟嗘埛娴嬭瘯鑰屾彁渚涚殑鏍蜂緥浠ｇ爜锛屽晢鎴峰彲浠ユ牴鎹嚜宸辩綉绔欑殑闇�瑕侊紝鎸夌収鎶�鏈枃妗ｇ紪鍐�,骞堕潪涓�瀹氳浣跨敤璇ヤ唬鐮併��
 * 璇ヤ唬鐮佷粎渚涘涔犲拰鐮旂┒鏀粯瀹濇帴鍙ｄ娇鐢紝鍙槸鎻愪緵涓�涓弬鑰冦��
 *
 * ************************娉ㄦ剰*************************
 * 濡傛灉鎮ㄥ湪鎺ュ彛闆嗘垚杩囩▼涓亣鍒伴棶棰橈紝鍙互鎸夌収涓嬮潰鐨勯�斿緞鏉ヨВ鍐�
 * 1銆佸紑鍙戞枃妗ｄ腑蹇冿紙https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.2Z6TSk&treeId=60&articleId=103693&docType=1锛�
 * 2銆佸晢鎴峰府鍔╀腑蹇冿紙https://cshall.alipay.com/enterprise/help_detail.htm?help_id=473888锛�
 * 3銆佹敮鎸佷腑蹇冿紙https://support.open.alipay.com/alipay/support/index.htm锛�
 * 濡傛灉涓嶆兂浣跨敤鎵╁睍鍔熻兘璇锋妸鎵╁睍鍔熻兘鍙傛暟璧嬬┖鍊笺��
 */
require_once ("alipay.config.php");
require_once ("lib/alipay_submit.class.php");

/**
 * ************************璇锋眰鍙傛暟*************************
 */

// 鍟嗘埛璁㈠崟鍙凤紝鍟嗘埛缃戠珯璁㈠崟绯荤粺涓敮涓�璁㈠崟鍙凤紝蹇呭～
$out_trade_no = $_POST ['WIDout_trade_no'];

// 璁㈠崟鍚嶇О锛屽繀濉�
$subject = $_POST ['WIDsubject'];

// 浠樻閲戦锛屽繀濉�
$total_fee = $_POST ['WIDtotal_fee'];

// 鏀堕摱鍙伴〉闈笂锛屽晢鍝佸睍绀虹殑瓒呴摼鎺ワ紝蹇呭～
// $show_url = $_POST['WIDshow_url'];

// 鍟嗗搧鎻忚堪锛屽彲绌�
$body = $_POST ['WIDbody'];

/**
 * *********************************************************
 */

// 鏋勯�犺璇锋眰鐨勫弬鏁版暟缁勶紝鏃犻渶鏀瑰姩
$parameter = array (
		"service" => $alipay_config ['service'],
		"partner" => $alipay_config ['partner'],
		"seller_id" => $alipay_config ['seller_id'],
		"payment_type" => $alipay_config ['payment_type'],
		"notify_url" => $alipay_config ['notify_url'],
		
		// "return_url" => $alipay_config['return_url'],
		"_input_charset" => trim ( strtolower ( $alipay_config ['input_charset'] ) ),
		"out_trade_no" => $out_trade_no,
		"subject" => $subject,
		"total_fee" => $total_fee,
		
		// "show_url" => $show_url,
		"body" => $body 
);
// 鍏朵粬涓氬姟鍙傛暟鏍规嵁鍦ㄧ嚎寮�鍙戞枃妗ｏ紝娣诲姞鍙傛暟.鏂囨。鍦板潃:https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.2Z6TSk&treeId=60&articleId=103693&docType=1
// 濡�"鍙傛暟鍚�" => "鍙傛暟鍊�" 娉細涓婁竴涓弬鏁版湯灏鹃渶瑕佲��,鈥濋�楀彿銆�



// 寤虹珛璇锋眰
$alipaySubmit = new AlipaySubmit ( $alipay_config );
$html_text = $alipaySubmit->buildRequestPara ( $_POST );
// $html_text = $alipaySubmit->buildRequestPara($parameter);
file_put_contents ( 'AliPayLog.txt', $_POST, FILE_APPEND );
file_put_contents ( 'AliPayLog.txt', "\r\n rest is :" . $html_text ['sign'] . ' &&in time :' . date ( "Y-m-d H:i:s", time () ), FILE_APPEND );
file_put_contents ( 'AliPayLog.txt', "\r\n", FILE_APPEND );
// echo json_encode($html_text);
echo ($html_text ['sign']);
?>
