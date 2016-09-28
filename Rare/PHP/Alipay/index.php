<?php
/*
 * 鍔熻兘锛氭敮浠樺疂鎵嬫満缃戠珯鏀粯鎺ュ彛鎺ュ彛璋冭瘯鍏ュ彛椤甸潰
 * 鐗堟湰锛�3.4
 * 淇敼鏃ユ湡锛�2016-03-08
 * 璇存槑锛�
 * 浠ヤ笅浠ｇ爜鍙槸涓轰簡鏂逛究鍟嗘埛娴嬭瘯鑰屾彁渚涚殑鏍蜂緥浠ｇ爜锛屽晢鎴峰彲浠ユ牴鎹嚜宸辩綉绔欑殑闇�瑕侊紝鎸夌収鎶�鏈枃妗ｇ紪鍐�,骞堕潪涓�瀹氳浣跨敤璇ヤ唬鐮併��
 */
?>
<!DOCTYPE html>
<html>
<head>
<title>鏀粯瀹濇墜鏈虹綉绔欐敮浠樻帴鍙ｆ帴鍙�</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
* {
	margin: 0;
	padding: 0;
}

ul, ol {
	list-style: none;
}

body {
	font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande",
		sans-serif;
}

.hidden {
	display: none;
}

.new-btn-login-sp {
	padding: 1px;
	display: inline-block;
	width: 75%;
}

.new-btn-login {
	background-color: #02aaf1;
	color: #FFFFFF;
	font-weight: bold;
	border: none;
	width: 100%;
	height: 30px;
	border-radius: 5px;
	font-size: 16px;
}

#main {
	width: 100%;
	margin: 0 auto;
	font-size: 14px;
}

.red-star {
	color: #f00;
	width: 10px;
	display: inline-block;
}

.null-star {
	color: #fff;
}

.content {
	margin-top: 5px;
}

.content dt {
	width: 100px;
	display: inline-block;
	float: left;
	margin-left: 20px;
	color: #666;
	font-size: 13px;
	margin-top: 8px;
}

.content dd {
	margin-left: 120px;
	margin-bottom: 5px;
}

.content dd input {
	width: 85%;
	height: 28px;
	border: 0;
	-webkit-border-radius: 0;
	-webkit-appearance: none;
}

#foot {
	margin-top: 10px;
	position: absolute;
	bottom: 15px;
	width: 100%;
}

.foot-ul {
	width: 100%;
}

.foot-ul li {
	width: 100%;
	text-align: center;
	color: #666;
}

.note-help {
	color: #999999;
	font-size: 12px;
	line-height: 130%;
	margin-top: 5px;
	width: 100%;
	display: block;
}

#btn-dd {
	margin: 20px;
	text-align: center;
}

.foot-ul {
	width: 100%;
}

.one_line {
	display: block;
	height: 1px;
	border: 0;
	border-top: 1px solid #eeeeee;
	width: 100%;
	margin-left: 20px;
}

.am-header {
	display: -webkit-box;
	display: -ms-flexbox;
	display: box;
	width: 100%;
	position: relative;
	padding: 7px 0;
	-webkit-box-sizing: border-box;
	-ms-box-sizing: border-box;
	box-sizing: border-box;
	background: #1D222D;
	height: 50px;
	text-align: center;
	-webkit-box-pack: center;
	-ms-flex-pack: center;
	box-pack: center;
	-webkit-box-align: center;
	-ms-flex-align: center;
	box-align: center;
}

.am-header h1 {
	-webkit-box-flex: 1;
	-ms-flex: 1;
	box-flex: 1;
	line-height: 18px;
	text-align: center;
	font-size: 18px;
	font-weight: 300;
	color: #fff;
}
</style>
</head>
<body text=#000000 bgColor="#ffffff" leftMargin=0 topMargin=4>
	<header class="am-header">
		<h1>鏀粯瀹濇墜鏈虹綉绔欐敮浠樻帴鍙ｅ揩閫熼�氶亾</h1>
	</header>
	<div id="main">
		<form name=alipayment action=alipayapi.php method=post target="_blank">
			<div id="body" style="clear: left">
				<dl class="content">
					<dt>鍟嗘埛璁㈠崟鍙� 锛�</dt>
					<dd>
						<input id="WIDout_trade_no" name="WIDout_trade_no" />
					</dd>
					<hr class="one_line">
					<dt>璁㈠崟鍚嶇О 锛�</dt>
					<dd>
						<input id="WIDsubject" name="WIDsubject" />
					</dd>
					<hr class="one_line">
					<dt>浠樻閲戦 锛�</dt>
					<dd>
						<input id="WIDtotal_fee" name="WIDtotal_fee" />
					</dd>
					<hr class="one_line">
					<dt>鍟嗗搧灞曠ず缃戝潃 锛�</dt>
					<dd>
						<input id="WIDshow_url" name="WIDshow_url" />
					</dd>
					<hr class="one_line">
					<dt>鍟嗗搧鎻忚堪锛�</dt>
					<dd>
						<input id="WIDbody" name="WIDbody" />
					</dd>
					<hr class="one_line">
					<dt></dt>
					<dd id="btn-dd">
						<span class="new-btn-login-sp">
							<button class="new-btn-login" type="submit"
								style="text-align: center;">纭� 璁�</button>
						</span> <span class="note-help">濡傛灉鎮ㄧ偣鍑烩�滅‘璁も�濇寜閽紝鍗宠〃绀烘偍鍚屾剰璇ユ鐨勬墽琛屾搷浣溿��</span>
					</dd>
				</dl>
			</div>
		</form>
		<div id="foot">
			<ul class="foot-ul">
				<li>鏀粯瀹濈増鏉冩墍鏈� 2015-2018 ALIPAY.COM</li>
			</ul>
		</div>
	</div>
</body>
<script language="javascript">
	function GetDateNow() {
		var vNow = new Date();
		var sNow = "";
		sNow += String(vNow.getFullYear());
		sNow += String(vNow.getMonth() + 1);
		sNow += String(vNow.getDate());
		sNow += String(vNow.getHours());
		sNow += String(vNow.getMinutes());
		sNow += String(vNow.getSeconds());
		sNow += String(vNow.getMilliseconds());
		document.getElementById("WIDout_trade_no").value =  sNow;
		document.getElementById("WIDsubject").value = "娴嬭瘯";
		document.getElementById("WIDtotal_fee").value = "0.01";
	}
	GetDateNow();
</script>
</html>