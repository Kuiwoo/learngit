<?php
include ('userbase.app.php');
class ApiApp extends userbaseApp {
	function articleList() {
		if (isget ()) {
			$page_num = 10;
			$this->data ['class'] = ! empty ( $this->data ['class'] ) ? $this->data ['class'] : 'party_new';
			$min_id = ! empty ( $this->data ['page'] ) ? ($this->data ['page'] - 1) * $page_num : 0;
			$father_id = ! empty ( $this->data ['father_id'] ) ? $this->data ['father_id'] : 0;
			$classlevel = checkclass ( $this->data ['class'] );
			
			$login = $this->user->login;
			if ($father_id == 0 and $this->data ['class'] == 'activaty_aticle')
				apiOutPut ( array (), 301, "缂哄皯蹇呰鍙傛暟" );
				/*
			 * if($login===0 && $classlevel>=2)
			 * {
			 * apiOutPut(false,403,'娌℃湁瓒冲鐨勬潈闄�');
			 * }
			 */
			$artmodel = &m ( 'ARTICLE' );
			$list = $artmodel->getArticleList ( $this->data ['class'], $father_id, $min_id, $page_num );
			$list ? apiOutPut ( $list ) : apiOutPut ( array (), 404, '鏈壘鍒版暟鎹�' );
			// apiOutPut($list);
		}
		
		if (ispost ()) {
		}
		
		if (isput ()) {
		}
		
		if (isdelete ()) {
		}
	}
	function article() {
		if (isget ()) {
			$artmodel = &m ( 'ARTICLE' );
			if (empty ( $this->data ['id'] )) {
				apiOutPut ( ( object ) null, 301, "缂哄皯蹇呰鍙傛暟" );
			}
			$class = $artmodel->getArticleClass ( $this->data ['id'] );
			$classlevel = checkclass ( $class );
			$login = $this->user->login;
			
			/*
			 * if($login===0 && $classlevel>=2)
			 * {
			 * apiOutPut(false,403,'娌℃湁瓒冲鐨勬潈闄�');
			 * }
			 */
			
			$list = $artmodel->getArticle ( $this->data ['id'] );
			$list ? apiOutPut ( $list ) : apiOutPut ( ( object ) null, 404, '鏈壘鍒版暟鎹�' );
			// apiOutPut($list);
		}
		
		if (ispost ()) {
		}
		
		if (isput ()) {
		}
		
		if (isdelete ()) {
		}
	}
	function activatyList() {
		if (isget ()) {
			$page_num = 10;
			// $this->data['class']=!empty($this->data['class'])?$this->data['class']:'party_new';
			$min_id = ! empty ( $this->data ['page'] ) ? (intval($this->data ['page']) - 1) * $page_num : 0;
			// $father_id=!empty($this->data['father_id'])?$this->data['father_id']:0;
			// $classlevel=checkclass($this->data['class']);
			
			$login = $this->user->login;
			/*
			 * if($login===0 )
			 * {
			 * apiOutPut(false,403,'娌℃湁瓒冲鐨勬潈闄�');
			 * }
			 */
			$artmodel = &m ( 'ARTICLE' );
			$list = $artmodel->getActivatyList ( $min_id, $page_num );
			$list ? apiOutPut ( $list ) : apiOutPut ( array (), 404, '鏈壘鍒版暟鎹�' );
			// apiOutPut($list);
		}
		
		if (ispost ()) {
		}
		
		if (isput ()) {
		}
		
		if (isdelete ()) {
		}
	}
	function activaty() {
		if (isget ()) {
			$artmodel = &m ( 'ARTICLE' );
			if (empty ( $this->data ['id'] ))
				apiOutPut ( ( object ) null, 301, "缂哄皯蹇呰鍙傛暟" );
			$class = $artmodel->getArticleClass ( $this->data ['id'] );
			$classlevel = checkclass ( $class );
			$login = $this->user->login;
			
			/*
			 * if($login===0 && $classlevel>=2)
			 * {
			 * apiOutPut(false,403,'娌℃湁瓒冲鐨勬潈闄�');
			 * }
			 */
			
			$list = $artmodel->getActivaty ( $this->data ['id'] );
			$list ? apiOutPut ( $list ) : apiOutPut ( ( object ) null, 404, '鏈壘鍒版暟鎹�' );
			// apiOutPut($list);
		}
		
		if (ispost ()) {
		}
		
		if (isput ()) {
		}
		
		if (isdelete ()) {
		}
	}
	function commentList() {
		if (isget ()) {
			$page_num = 10;
			if (empty ( $this->data ['father_id'] ))
				apiOutPut ( array (), 301, "缂哄皯蹇呰鍙傛暟" );
			$min_id = ! empty ( $this->data ['page'] ) ? ($this->data ['page'] - 1) * $page_num : 0;
			$father_id = ! empty ( $this->data ['father_id'] ) ? $this->data ['father_id'] : 0;
			
			$login = $this->user->login;
			
			/*
			 * if($login===0 )
			 * {
			 * apiOutPut(false,403,'娌℃湁瓒冲鐨勬潈闄�');
			 * }
			 */
			$artmodel = &m ( 'ARTICLE' );
			$list = $artmodel->getCommentList ( $father_id, $min_id, $page_num );
			$list ? apiOutPut ( $list ) : apiOutPut ( array (), 404, '鏈壘鍒版暟鎹�' );
			// apiOutPut($list);
		}
		
		if (ispost ()) {
		}
		
		if (isput ()) {
		}
		
		if (isdelete ()) {
		}
	}
	function comment() {
		if (isget ()) {
		}
		
		if (ispost ()) {
		}
		
		if (isput ()) {
		}
		
		if (isdelete ()) {
		}
	}
	function resouce() {
		if (isget ()) {
			if (empty ( $this->data ['id'] ))
				apiOutPut ( array (), 301, "缂哄皯蹇呰鍙傛暟" );
				/*
			 * if($login===0 )
			 * {
			 * apiOutPut(false,403,'娌℃湁瓒冲鐨勬潈闄�');
			 * }
			 */
			$artmodel = &m ( 'ARTICLE' );
			$list = $artmodel->getResouce ( $this->data ['id'] );
			$list ? apiOutPut ( $list ) : apiOutPut ( array (), 404, '鏈壘鍒版暟鎹�' );
			// apiOutPut($list);
		}
		
		if (ispost ()) {
		}
		
		if (isput ()) {
		}
		
		if (isdelete ()) {
		}
	}
	function userInfo() {
		if ($this->user->login === 0) {
			apiOutPut ( ( object ) null, 403, '灏氭湭鐧诲綍' );
		}
		if (isGet ()) {
			$id = $this->user->userid;
			$info = $this->getuserinfo ( $id );
			$info ? apiOutPut ( $info ) : apiOutPut ( ( object ) null, 404, '鏈壘鍒版暟鎹�' );
		}
	}
	
	function test(){
		$mode=&m('BASE');
		apiOutPut($mode->test());
	}
}