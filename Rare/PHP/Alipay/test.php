<?php
file_put_contents ( 'AliPayLog.txt', "\r\n ��ʼ���ӵ����ݿ� \r\n", FILE_APPEND );
$mysqli = new mysqli ( "123.56.183.145", "radius", "radius", "radius" );
var_dump ( $mysqli );
$mysqli->set_charset ( "utf8" );
file_put_contents ( 'AliPayLog.txt', "\r\n ���ӵ����ݿ�ɹ� \r\n", FILE_APPEND );
file_put_contents ( 'AliPayLog.txt', $mysqli->query ( "select pay_id from webpaytoken where paytoken='aaaaa'" ), FILE_APPEND );
var_dump ( $mysqli->query ( "select id from webpaytoken where paytoken='aaaaa'" )->fetch_one () );