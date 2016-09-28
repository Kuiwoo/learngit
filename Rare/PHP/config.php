<?php
/**
 * Created by PhpStorm.
 * User: 41023
 * Date: 2015/12/20
 * Time: 22:27
 */
$cookielogin = 0;

$baseport = 3306;
$basems = 'mysql'; // ���ݿ�����
$basehost = '127.0.0.1'; // ���ݿ�������
$basename = 'yungu'; // ʹ�õ����ݿ�
$baseuser = 'root'; // ���ݿ������û���
$basepass = 'root'; // ��Ӧ������
$basedsn = "$basems:host=$basehost;port=$baseport;dbname=$basename";

date_default_timezone_set ( 'PRC' );