<?php
class imgApp {
	function _UPLOADPIC($upfile, $maxsize, $updir, $newname = 'date') {
		if ($newname == 'date') {
			$newname = date ( "Ymdhis" ); // 浣跨敤鏃ユ湡鍋氭枃浠跺悕
		}
		$name = $upfile ["name"];
		$type = $upfile ["type"];
		$size = $upfile ["size"];
		$tmp_name = $upfile ["tmp_name"];
		
		switch ($type) {
			case 'image/pjpeg' :
			case 'image/jpeg' :
				$extend = ".jpg";
				break;
			case 'image/gif' :
				$extend = ".gif";
				break;
			case 'image/png' :
				$extend = ".png";
				break;
		}
		if (emptyempty ( $extend )) {
			echo ("璀﹀憡锛佸彧鑳戒笂浼犲浘鐗囩被鍨嬶細GIF JPG PNG");
			exit ();
		}
		if ($size > $maxsize) {
			$maxpr = $maxsize / 1000;
			echo ("璀﹀憡锛佷笂浼犲浘鐗囧ぇ灏忎笉鑳借秴杩�" . $maxpr . "K!");
			exit ();
		}
		if (move_uploaded_file ( $tmp_name, $updir . $newname . $extend )) {
			return $updir . $newname . $extend;
		}
	}
	function show_pic_scal($width, $height, $picpath) {
		$imginfo = GetImageSize ( $picpath );
		$imgw = $imginfo [0];
		$imgh = $imginfo [1];
		
		$ra = number_format ( ($imgw / $imgh), 1 ); // 瀹介珮姣�
		$ra2 = number_format ( ($imgh / $imgw), 1 ); // 楂樺姣�
		
		if ($imgw > $width or $imgh > $height) {
			if ($imgw > $imgh) {
				$newWidth = $width;
				$newHeight = round ( $newWidth / $ra );
			} elseif ($imgw < $imgh) {
				$newHeight = $height;
				$newWidth = round ( $newHeight / $ra2 );
			} else {
				$newWidth = $width;
				$newHeight = round ( $newWidth / $ra );
			}
		} else {
			$newHeight = $imgh;
			$newWidth = $imgw;
		}
		$newsize [0] = $newWidth;
		$newsize [1] = $newHeight;
		
		return $newsize;
	}
	function getImageInfo($src) {
		return getimagesize ( $src );
	}
	/**
	 * 鍒涘缓鍥剧墖锛岃繑鍥炶祫婧愮被鍨�
	 *
	 * @param string $src
	 *        	鍥剧墖璺緞
	 * @return resource $im 杩斿洖璧勬簮绫诲瀷
	 *         *
	 */
	function create($src) {
		$info = $this->getImageInfo ( $src );
		switch ($info [2]) {
			case 1 :
				$im = imagecreatefromgif ( $src );
				break;
			case 2 :
				$im = imagecreatefromjpeg ( $src );
				break;
			case 3 :
				$im = imagecreatefrompng ( $src );
				break;
		}
		return $im;
	}
	/**
	 * 缂╃暐鍥句富鍑芥暟
	 *
	 * @param string $src
	 *        	鍥剧墖璺緞
	 * @param int $w
	 *        	缂╃暐鍥惧搴�
	 * @param int $h
	 *        	缂╃暐鍥鹃珮搴�
	 * @return mixed 杩斿洖缂╃暐鍥捐矾寰�
	 *         *
	 */
	function resize($src, $w, $h) {
		$temp = pathinfo ( $src );
		$name = $temp ["basename"]; // 鏂囦欢鍚�
		$dir = $temp ["dirname"]; // 鏂囦欢鎵�鍦ㄧ殑鏂囦欢澶�
		$extension = $temp ["extension"]; // 鏂囦欢鎵╁睍鍚�
		$savepath = "{$dir}/{$name}"; // 缂╃暐鍥句繚瀛樿矾寰�,鏂扮殑鏂囦欢鍚嶄负*.thumb.jpg
		                              
		// 鑾峰彇鍥剧墖鐨勫熀鏈俊鎭�
		$info = $this->getImageInfo ( $src );
		$width = $info [0]; // 鑾峰彇鍥剧墖瀹藉害
		$height = $info [1]; // 鑾峰彇鍥剧墖楂樺害
		$per1 = round ( $width / $height, 2 ); // 璁＄畻鍘熷浘闀垮姣�
		$per2 = round ( $w / $h, 2 ); // 璁＄畻缂╃暐鍥鹃暱瀹芥瘮
		                              
		// 璁＄畻缂╂斁姣斾緥
		if ($per1 > $per2 || $per1 == $per2) {
			// 鍘熷浘闀垮姣斿ぇ浜庢垨鑰呯瓑浜庣缉鐣ュ浘闀垮姣旓紝鍒欐寜鐓у搴︿紭鍏�
			$per = $w / $width;
		}
		if ($per1 < $per2) {
			// 鍘熷浘闀垮姣斿皬浜庣缉鐣ュ浘闀垮姣旓紝鍒欐寜鐓ч珮搴︿紭鍏�
			$per = $h / $height;
		}
		$temp_w = intval ( $width * $per ); // 璁＄畻鍘熷浘缂╂斁鍚庣殑瀹藉害
		$temp_h = intval ( $height * $per ); // 璁＄畻鍘熷浘缂╂斁鍚庣殑楂樺害
		$temp_img = imagecreatetruecolor ( $temp_w, $temp_h ); // 鍒涘缓鐢诲竷
		$im = $this->create ( $src );
		imagecopyresampled ( $temp_img, $im, 0, 0, 0, 0, $temp_w, $temp_h, $width, $height );
		if ($per1 > $per2) {
			imagejpeg ( $temp_img, $savepath, 100 );
			imagedestroy ( $im );
			return $this->addBg ( $savepath, $w, $h, "w" );
			// 瀹藉害浼樺厛锛屽湪缂╂斁涔嬪悗楂樺害涓嶈冻鐨勬儏鍐典笅琛ヤ笂鑳屾櫙
		}
		if ($per1 == $per2) {
			imagejpeg ( $temp_img, $savepath, 100 );
			imagedestroy ( $im );
			return $savepath;
			// 绛夋瘮缂╂斁
		}
		if ($per1 < $per2) {
			imagejpeg ( $temp_img, $savepath, 100 );
			imagedestroy ( $im );
			return $this->addBg ( $savepath, $w, $h, "h" );
			// 楂樺害浼樺厛锛屽湪缂╂斁涔嬪悗瀹藉害涓嶈冻鐨勬儏鍐典笅琛ヤ笂鑳屾櫙
		}
	}
	/**
	 * 娣诲姞鑳屾櫙
	 *
	 * @param string $src
	 *        	鍥剧墖璺緞
	 * @param int $w
	 *        	鑳屾櫙鍥惧儚瀹藉害
	 * @param int $h
	 *        	鑳屾櫙鍥惧儚楂樺害
	 * @param String $first
	 *        	鍐冲畾鍥惧儚鏈�缁堜綅缃殑锛寃 瀹藉害浼樺厛 h 楂樺害浼樺厛 wh:绛夋瘮
	 * @return 杩斿洖鍔犱笂鑳屾櫙鐨勫浘鐗� *
	 */
	function addBg($src, $w, $h, $fisrt = "w") {
		$bg = imagecreatetruecolor ( $w, $h );
		$white = imagecolorallocate ( $bg, 255, 255, 255 );
		imagefill ( $bg, 0, 0, $white ); // 濉厖鑳屾櫙
		                                 
		// 鑾峰彇鐩爣鍥剧墖淇℃伅
		$info = $this->getImageInfo ( $src );
		$width = $info [0]; // 鐩爣鍥剧墖瀹藉害
		$height = $info [1]; // 鐩爣鍥剧墖楂樺害
		$img = $this->create ( $src );
		if ($fisrt == "wh") {
			// 绛夋瘮缂╂斁
			return $src;
		} else {
			if ($fisrt == "w") {
				$x = 0;
				$y = ($h - $height) / 2; // 鍨傜洿灞呬腑
			}
			if ($fisrt == "h") {
				$x = ($w - $width) / 2; // 姘村钩灞呬腑
				$y = 0;
			}
			imagecopymerge ( $bg, $img, $x, $y, 0, 0, $width, $height, 100 );
			imagejpeg ( $bg, $src, 100 );
			imagedestroy ( $bg );
			imagedestroy ( $img );
			return $src;
		}
	}
}
