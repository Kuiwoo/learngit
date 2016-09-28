<?php
/*
 * 閰嶇疆鏂囦欢
 * 鐗堟湰锛�3.4
 * 淇敼鏃ユ湡锛�2016-03-08
 * 璇存槑锛�
 * 浠ヤ笅浠ｇ爜鍙槸涓轰簡鏂逛究鍟嗘埛娴嬭瘯鑰屾彁渚涚殑鏍蜂緥浠ｇ爜锛屽晢鎴峰彲浠ユ牴鎹嚜宸辩綉绔欑殑闇�瑕侊紝鎸夌収鎶�鏈枃妗ｇ紪鍐�,骞堕潪涓�瀹氳浣跨敤璇ヤ唬鐮併��
 * 璇ヤ唬鐮佷粎渚涘涔犲拰鐮旂┒鏀粯瀹濇帴鍙ｄ娇鐢紝鍙槸鎻愪緵涓�涓弬鑰冦��
 *
 * 瀹夊叏鏍￠獙鐮佹煡鐪嬫椂锛岃緭鍏ユ敮浠樺瘑鐮佸悗锛岄〉闈㈠憟鐏拌壊鐨勭幇璞★紝鎬庝箞鍔烇紵
 * 瑙ｅ喅鏂规硶锛�
 * 1銆佹鏌ユ祻瑙堝櫒閰嶇疆锛屼笉璁╂祻瑙堝櫒鍋氬脊妗嗗睆钄借缃�
 * 2銆佹洿鎹㈡祻瑙堝櫒鎴栫數鑴戯紝閲嶆柊鐧诲綍鏌ヨ銆�
 */

// 鈫撯啌鈫撯啌鈫撯啌鈫撯啌鈫撯啌璇峰湪杩欓噷閰嶇疆鎮ㄧ殑鍩烘湰淇℃伅鈫撯啌鈫撯啌鈫撯啌鈫撯啌鈫撯啌鈫撯啌鈫撯啌鈫�
// 鍚堜綔韬唤鑰匢D锛岀绾﹁处鍙凤紝浠�2088寮�澶寸敱16浣嶇函鏁板瓧缁勬垚鐨勫瓧绗︿覆锛屾煡鐪嬪湴鍧�锛歨ttps://b.alipay.com/order/pidAndKey.htm
$alipay_config ['partner'] = '2088911789850583';

// 鏀舵鏀粯瀹濊处鍙凤紝浠�2088寮�澶寸敱16浣嶇函鏁板瓧缁勬垚鐨勫瓧绗︿覆锛屼竴鑸儏鍐典笅鏀舵璐﹀彿灏辨槸绛剧害璐﹀彿
$alipay_config ['seller_id'] = $alipay_config ['partner'];

// 鍟嗘埛鐨勭閽�,姝ゅ濉啓鍘熷绉侀挜锛孯SA鍏閽ョ敓鎴愶細https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.nBDxfy&treeId=58&articleId=103242&docType=1
$alipay_config ['private_key_path'] = 'key/rsa_private_key.pem';

// 鏀粯瀹濈殑鍏挜锛屾煡鐪嬪湴鍧�锛歨ttps://b.alipay.com/order/pidAndKey.htm
$alipay_config ['ali_public_key_path'] = 'key/alipay_public_key.pem';
// 鏈嶅姟鍣ㄥ紓姝ラ�氱煡椤甸潰璺緞 闇�http://鏍煎紡鐨勫畬鏁磋矾寰勶紝涓嶈兘鍔�?id=123杩欑被鑷畾涔夊弬鏁帮紝蹇呴』澶栫綉鍙互姝ｅ父璁块棶
// $alipay_config['notify_url'] = "http://鍟嗘埛缃戝叧缃戝潃/alipay.wap.create.direct.pay.by.user-PHPUTF-8/notify_url.php";
$alipay_config ['notify_url'] = "http://123.56.183.145/Alipay/notify_url.php";

// 椤甸潰璺宠浆鍚屾閫氱煡椤甸潰璺緞 闇�http://鏍煎紡鐨勫畬鏁磋矾寰勶紝涓嶈兘鍔�?id=123杩欑被鑷畾涔夊弬鏁帮紝蹇呴』澶栫綉鍙互姝ｅ父璁块棶
// $alipay_config['return_url'] = "http://鍟嗘埛缃戝潃/alipay.wap.create.direct.pay.by.user-PHP-UTF-8/return_url.php";
$alipay_config ['return_url'] = "http://123.56.183.145/Alipay/return_url.php";
// 绛惧悕鏂瑰紡
$alipay_config ['sign_type'] = strtoupper ( 'RSA' );

// 瀛楃缂栫爜鏍煎紡 鐩墠鏀寔utf-8
$alipay_config ['input_charset'] = strtolower ( 'utf-8' );

// ca璇佷功璺緞鍦板潃锛岀敤浜巆url涓璼sl鏍￠獙
// 璇蜂繚璇乧acert.pem鏂囦欢鍦ㄥ綋鍓嶆枃浠跺す鐩綍涓�
$alipay_config ['cacert'] = getcwd () . '\\cacert.pem';

// 璁块棶妯″紡,鏍规嵁鑷繁鐨勬湇鍔″櫒鏄惁鏀寔ssl璁块棶锛岃嫢鏀寔璇烽�夋嫨https锛涜嫢涓嶆敮鎸佽閫夋嫨http
$alipay_config ['transport'] = 'http';

// 鏀粯绫诲瀷 锛屾棤闇�淇敼
$alipay_config ['payment_type'] = "1";

// 浜у搧绫诲瀷锛屾棤闇�淇敼
$alipay_config ['service'] = "alipay.wap.create.direct.pay.by.user";

// 鈫戔啈鈫戔啈鈫戔啈鈫戔啈鈫戔啈璇峰湪杩欓噷閰嶇疆鎮ㄧ殑鍩烘湰淇℃伅鈫戔啈鈫戔啈鈫戔啈鈫戔啈鈫戔啈鈫戔啈鈫戔啈鈫�

?>