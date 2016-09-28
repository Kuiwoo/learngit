<?php
/**
 * Created by PhpStorm.
 * User: 41023
 * Date: 2015/12/20
 * Time: 18:16
 */

/*
 * log class use to log do something
 */
error_reporting(3);

header ( "Access-Control-Allow-Origin:*");
header ( 'Access-Control-Allow-Headers: X-Requested-With' );
header ( 'Content-Type: application/x-www-form-urlencoded' );
try {
	include ('config.php');
	include ('include/index.fun.php');
	include ('include/log.class.php');
} catch ( Exception $e ) {
	print $e;
	die ();
}
/*
 * url app act
 */
$APP = 'defaults';
$ACT = 'index';
$LOG = new Log ();
$CLASSTYPE = 'APP';

header ( "Content-type:text/html;charset=utf-8" );
// die(123);
session_start ();
app_act ();
/**
 * user post get data safe
 */
/*
 * foreach($_GET as &$getone)
 * {
 * $getone=urldecode($getone);
 * $getone=base64_encode($getone);
 * }
 *
 * foreach($_POST as &$postone)
 * {
 * $postone=urldecode($postone);
 * $postone=base64_encode($getone);
 * }
 */
include ('app/' . $APP . '.app.php');
function __autoload($class_name) {
	// $path = str_replace('_', '/', $class_name);
	global $CLASSTYPE;
	
	if ($CLASSTYPE == 'MODEL') {
		$model_path = 'model/';
		$name = explode ( 'Model', $class_name );
		$type = 'model';
	} elseif ($CLASSTYPE == "APP") {
		$model_path = 'app/';
		$name = explode ( 'App', $class_name );
		$type = 'app';
	}
	
	$CLASSTYPE = "APP";
	/*
	 * switch ($CLASSTYPE){
	 * case "MODEL":
	 * $model_path='model/';
	 * $name=explode('Model',$class_name);
	 * $type='model';
	 * echo 1;
	 * case "APP":
	 * $model_path='app/';
	 * $name=explode('App',$class_name);
	 * $type='app';
	 * echo 2;
	 * default:
	 *
	 * die('no such file $class_name');
	 * }
	 */
	
	require_once $model_path . $name [0] . '.' . $type . '.php';
}
$CLASSTYPE = "APP";
$APPNAME = $APP . 'App';
$APPobj = new $APPNAME ();
$APPobj->$ACT ();
die ();








