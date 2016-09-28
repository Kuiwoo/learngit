<?php
/**
 * Created by PhpStorm.
 * User: 41023
 * Date: 2015/12/20
 * Time: 21:46
 */

/*
 * app act check
 *
 */
function app_act() {
	global $LOG;
	global $APP;
	global $ACT;
	if (isset ( $_GET ['app'] ) and preg_match ( '/^[\w]*$/', $_GET ['app'] )) {
		$APP = $_GET ['app'];
	} else {
		$LOG->write ( "app is error" );
	}
	
	if (isset ( $_GET ['act'] ) and preg_match ( '/^[\w]*$/', $_GET ['act'] )) {
		$ACT = $_GET ['act'];
	} else {
		$LOG->write ( "act is error" );
	}
}
function display($filename) 

{
	header ( "Content-type: text/html" );
	$file = "html/" . $filename;
	$html = file_get_contents ( $file );
	echo $html;
	die ();
}
function apiOutPut($rest, $code = 200, $mess = 'ok') {
	$code = (! $rest and $code == 200) ? 404 : $code;
	$res = array (
			'retval' => $rest,
			'code' => $code,
			'message' => $mess 
	);
	header ( "Content-type: application/json" );
	// var_dump($res);
	echo json_encode ( $res );
	die ();
}
function checkclass($class) {
	switch ($class) {
		case 'party_new' :
			return 1;
			break;
		case 'activity_new' :
			return 1;
			break;
		case 'job_new' :
			return 1;
			break;
		case 'activity_article' :
			return 3;
			break;
		case 'study' :
			return 2;
			break;
		default :
			return false;
	}
}
function random_str($length = 8) {
	// 瀵嗙爜瀛楃闆嗭紝鍙换鎰忔坊鍔犱綘闇�瑕佺殑瀛楃
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$password = '';
	for($i = 0; $i < $length; $i ++) {
		$password .= $chars [mt_rand ( 0, strlen ( $chars ) - 1 )];
	}
	return $password;
}
function verification() {
	return time () . rabdom_str ( 16 );
}
function &m($modelname) {
	global $CLASSTYPE;
	$CLASSTYPE = "MODEL";
	// include ('model/'.$modelname.'.model.php');
	$modelname = $modelname . 'Model';
	$newobj = new $modelname ();
	return $newobj;
}
function monthAdd($timed, $months = 1) {
	$months = intval ( $months );
	if ($months <= 0) {
		return false;
	} else {
		/*
		 * $times=getdate($timed);
		 * //var_dump($times);
		 * $year=$times['year'];
		 * $month=$times['mon'];
		 * $days=0;
		 * for($i=1;$i<=$months;$i++){
		 * $month=($month)%12;
		 * if($month==1){
		 * $year=$year+1;
		 * }
		 *
		 * if($month!=2)
		 * {
		 * $mon_days=abs(intval($month-7.5))%2==0?31:30;
		 * }else{
		 * $mon_days=($year%400==0 or ($year%100!=0 and $year%4==0))?29:28;
		 * }
		 * $days+=$mon_days;
		 * //echo $days."\n";
		 * }
		 */
		
		return $timed + $months * 31 * 24 * 60 * 60;
	}
}
function objectToArray($obj) {
	$arr = is_object ( $obj ) ? get_object_vars ( $obj ) : $obj;
	if (is_array ( $arr )) {
		return array_map ( __FUNCTION__, $arr );
	} else {
		return $arr;
	}
}
function had($k) {
	if (isset ( $k ) && ! empty ( $k )) {
		return $k;
	} else {
		return false;
	}
}

/**
 * 鏄惁鏄疉JAx鎻愪氦鐨�
 *
 * @return bool
 */
function isAjax() {
	if (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest') {
		return true;
	} else {
		return false;
	}
}

/**
 * 鏄惁鏄疓ET鎻愪氦鐨�
 */
function isGet() {
	return $_SERVER ['REQUEST_METHOD'] == 'GET' ? true : false;
}

/**
 * 鏄惁鏄疨OST鎻愪氦
 *
 * @return int
 */
function isPost() {
	return ($_SERVER ['REQUEST_METHOD'] == 'POST' && (empty ( $_SERVER ['HTTP_REFERER'] ) || preg_replace ( "~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER ['HTTP_REFERER'] ) == preg_replace ( "~([^\:]+).*~", "\\1", $_SERVER ['HTTP_HOST'] ))) ? true : false;
}

/**
 * 鏄惁鏄疨UT鎻愪氦
 *
 * @return int
 */
function isPut() {
	return ($_SERVER ['REQUEST_METHOD'] == 'PUT' && (empty ( $_SERVER ['HTTP_REFERER'] ) || preg_replace ( "~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER ['HTTP_REFERER'] ) == preg_replace ( "~([^\:]+).*~", "\\1", $_SERVER ['HTTP_HOST'] ))) ? true : false;
}

/**
 * 鏄惁鏄疍ELETE鎻愪氦
 *
 * @return int
 */
function isDelete() {
	return ($_SERVER ['REQUEST_METHOD'] == 'DELETE' && (empty ( $_SERVER ['HTTP_REFERER'] ) || preg_replace ( "~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER ['HTTP_REFERER'] ) == preg_replace ( "~([^\:]+).*~", "\\1", $_SERVER ['HTTP_HOST'] ))) ? true : false;
}

/**
 * 鏄惁鏄疨ATCH鎻愪氦
 *
 * @return int
 */
function isPatch() {
	return ($_SERVER ['REQUEST_METHOD'] == 'PATCH' && (empty ( $_SERVER ['HTTP_REFERER'] ) || preg_replace ( "~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER ['HTTP_REFERER'] ) == preg_replace ( "~([^\:]+).*~", "\\1", $_SERVER ['HTTP_HOST'] ))) ? true : false;
}
// 鏁扮粍杞紪鐮�
function mult_iconv($in_charset, $out_charset, $data) {
	if (substr ( $out_charset, - 8 ) == '//IGNORE') {
		$out_charset = substr ( $out_charset, 0, - 8 );
	}
	if (is_array ( $data )) {
		foreach ( $data as $key => $value ) {
			if (is_array ( $value )) {
				$key = iconv ( $in_charset, $out_charset . '//IGNORE', $key );
				$rtn [$key] = mult_iconv ( $in_charset, $out_charset, $value );
			} elseif (is_string ( $key ) || is_string ( $value )) {
				if (is_string ( $key )) {
					$key = iconv ( $in_charset, $out_charset . '//IGNORE', $key );
				}
				if (is_string ( $value )) {
					$value = iconv ( $in_charset, $out_charset . '//IGNORE', $value );
				}
				$rtn [$key] = $value;
			} else {
				$rtn [$key] = $value;
			}
		}
	} elseif (is_string ( $data )) {
		$rtn = iconv ( $in_charset, $out_charset . '//IGNORE', $data );
	} else {
		$rtn = $data;
	}
	return $rtn;
}
function fileext($file) {
	return substr ( strrchr ( $file, '.' ), 1 );
}
function img2thumb($src_img, $dst_img, $width = 75, $height = 75, $cut = 0, $proportion = 0) {
	if (! is_file ( $src_img )) {
		return false;
	}
	$ot = fileext ( $dst_img );
	$otfunc = 'image' . ($ot == 'jpg' ? 'jpeg' : $ot);
	if (! $ot)
		return false;
	$srcinfo = getimagesize ( $src_img );
	$src_w = $srcinfo [0];
	$src_h = $srcinfo [1];
	$type = strtolower ( substr ( image_type_to_extension ( $srcinfo [2] ), 1 ) );
	if (! $type)
		return false;
	$createfun = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);
	
	$dst_h = $height;
	$dst_w = $width;
	$x = $y = 0;
	
	/**
	 * 缂╃暐鍥句笉瓒呰繃婧愬浘灏哄锛堝墠鎻愭槸瀹芥垨楂樺彧鏈変竴涓級
	 */
	if (($width > $src_w && $height > $src_h) || ($height > $src_h && $width == 0) || ($width > $src_w && $height == 0)) {
		$proportion = 1;
	}
	if ($width > $src_w) {
		$dst_w = $width = $src_w;
	}
	if ($height > $src_h) {
		$dst_h = $height = $src_h;
	}
	
	if (! $width && ! $height && ! $proportion) {
		return false;
	}
	if (! $proportion) {
		if ($cut == 0) {
			if ($dst_w && $dst_h) {
				if ($dst_w / $src_w > $dst_h / $src_h) {
					$dst_w = $src_w * ($dst_h / $src_h);
					$x = 0 - ($dst_w - $width) / 2;
				} else {
					$dst_h = $src_h * ($dst_w / $src_w);
					$y = 0 - ($dst_h - $height) / 2;
				}
			} else if ($dst_w xor $dst_h) {
				if ($dst_w && ! $dst_h) // 鏈夊鏃犻珮
{
					$propor = $dst_w / $src_w;
					$height = $dst_h = $src_h * $propor;
				} else if (! $dst_w && $dst_h) // 鏈夐珮鏃犲
{
					$propor = $dst_h / $src_h;
					$width = $dst_w = $src_w * $propor;
				}
			}
		} else {
			if (! $dst_h) // 瑁佸壀鏃舵棤楂�
{
				$height = $dst_h = $dst_w;
			}
			if (! $dst_w) // 瑁佸壀鏃舵棤瀹�
{
				$width = $dst_w = $dst_h;
			}
			$propor = min ( max ( $dst_w / $src_w, $dst_h / $src_h ), 1 );
			$dst_w = ( int ) round ( $src_w * $propor );
			$dst_h = ( int ) round ( $src_h * $propor );
			$x = ($width - $dst_w) / 2;
			$y = ($height - $dst_h) / 2;
		}
	} else {
		$proportion = min ( $proportion, 1 );
		$height = $dst_h = $src_h * $proportion;
		$width = $dst_w = $src_w * $proportion;
	}
	
	$src = $createfun ( $src_img );
	$dst = imagecreatetruecolor ( $width ? $width : $dst_w, $height ? $height : $dst_h );
	$white = imagecolorallocate ( $dst, 255, 255, 255 );
	imagefill ( $dst, 0, 0, $white );
	
	if (function_exists ( 'imagecopyresampled' )) {
		imagecopyresampled ( $dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h );
	} else {
		imagecopyresized ( $dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h );
	}
	$otfunc ( $dst, $dst_img );
	imagedestroy ( $dst );
	imagedestroy ( $src );
	return true;
}
	
