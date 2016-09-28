<?php
include ('BASE.model.php');
class ARTICLEModel extends BASEModel {
	function getArticleList($class = 'party_new', $activaty_id = 0, $min_id = 0, $length = 10) {
		$query = "select user.Id as user_id,user.headimg as user_headimg,user.type as user_type,user.name as user_name,article.Id,article.title,article.small_title,article.class,article.time,article.click_number,GROUP_CONCAT(resouce.small_img) as small_img,GROUP_CONCAT(resouce.name) as small_name,GROUP_CONCAT(resouce.Id) as small_id 
from article left join user on user.Id=article.author_id left join resouce on resouce.father_id=article.Id and resouce.father_type='$class'and resouce.status='1' and resouce.data_status='1' where article.class='$class' and article.activaty_id='$activaty_id' and article.status='1' and article.data_status='1' group by article.Id  order by article.Id limit $min_id,$length";
		$res = $this->getall ( $query );
		// echo $query;
		// var_dump($res);
		if ($res) {
			foreach ( $res as &$v ) {
				if (! empty ( $v ['small_id'] )) {
					$v ['small_id'] = explode ( ",", $v ['small_id'] );
				}
				if (! empty ( $v ['small_img'] )) {
					$v ['small_img'] = explode ( ",", $v ['small_img'] );
				}
				if (! empty ( $v ['small_img'] )) {
					$v ['small_name'] = explode ( ",", $v ['small_name'] );
				}
			}
		}
		return $res;
	}
	function getArticle($article_id) {
		$query = "select  user.Id as user_id,user.headimg as user_headimg,user.type as user_type,user.name as user_name,article.Id,article.title,article.small_title,article.class,article.time,article.click_number,GROUP_CONCAT(resouce.small_img) as small_img,GROUP_CONCAT(resouce.name) as small_name 
from article left join user on user.Id=article.author_id left join resouce on resouce.father_id=article.Id and resouce.father_type=article.class and resouce.status='1' 
		and resouce.data_status='1' where article.Id='$article_id' and article.status='1' and article.data_status='1' group by article.Id";
		$res = $this->getrow ( $query );
		// var_dump($res);
		$class = $res ['class'];
		$query = "select type,data from resouce where father_type='$class' and father_id='$article_id' and status='1' and data_status='1' order by Id";
		$datas = $this->getall ( $query );
		$res ['data'] = $datas ? $datas : array (
				'small_img' => '',
				'small_name' => '' 
		);
		if (! empty ( $res ['small_img'] )) {
			$res ['small_img'] = explode ( ",", $res ['small_img'] );
		}
		if (! empty ( $res ['small_img'] )) {
			$res ['small_name'] = explode ( ",", $res ['small_name'] );
		}
		return $res;
	}
	function getActivatyList($min_id = 0, $length = 10) {
		$query = "select  user.Id as user_id,user.headimg as user_headimg,user.type as user_type,user.name as user_name,activaty.Id,activaty.title,activaty.small_title,activaty.time,activaty.click_number,activaty.author_id,activaty.start_time,activaty.end_time,GROUP_CONCAT(resouce.small_img) as small_img,GROUP_CONCAT(resouce.name) as small_name
		from activaty left join user on activaty.author_id=user.Id left join resouce on resouce.father_id=activaty.Id and resouce.father_type='activaty'and resouce.status= '1' and resouce.data_status='1' where activaty.status='1' and activaty.data_status='1' group by activaty.Id  order by activaty.Id limit $min_id,$length";
		
		$res = $this->getall( $query);
		 //var_dump($res);
		if ($res) {
			foreach ( $res as &$v ) {
				if (! empty ( $v ['small_img'] )) {
					$v ['small_img'] = explode ( ",", $v ['small_img'] );
				}
				if (! empty ( $v ['small_img'] )) {
					$v ['small_name'] = explode ( ",", $v ['small_name'] );
				}
			}
		}
		
		return $res;
	}
	function getActivaty($activaty_id) {
		$query = " select  user.Id as user_id,user.headimg as user_headimg,user.type as user_type,user.name as user_name,activaty.Id,activaty.title,activaty.small_title,activaty.time,activaty.click_number,activaty.author_id,activaty.start_time,activaty.end_time,GROUP_CONCAT(resouce.small_img) as small_img,GROUP_CONCAT(resouce.name) as small_name
		from activaty left join user on user.Id=activaty.author_id left join resouce on resouce.father_id=activaty.Id and resouce.father_type='activaty'and resouce.status='1' and resouce.data_status='1' where activaty.Id='$activaty_id' and activaty.status='1' and activaty.data_status='1'
     group by activaty.Id";
		$res = $this->getrow ( $query );
		// var_dump($res);
		// $class=$res['class'];
		$query = "select type,data from resouce where father_type='activaty' and father_id='$activaty_id' and status='1' and data_status='1' order by Id";
		$datas = $this->getall ( $query );
		$res ['data'] = $datas ? $datas : array ();
		if (! empty ( $res ['small_img'] )) {
			$res ['small_img'] = explode ( ",", $res ['small_img'] );
		}
		if (! empty ( $res ['small_img'] )) {
			$res ['small_name'] = explode ( ",", $res ['small_name'] );
		}
		// $res['small_name']=explode(",",$res['small_name']) ;
		return $res;
	}
	function getCommentList($article_id, $min_id, $length) {
		$query = "select user.Id as user_id,user.headimg as user_headimg,user.type as user_type,user.name as user_name,comment.Id,comment.user_id,comment.comment,comment.article_id,comment.time from comment left join user on user.Id=comment.user_id where comment.article_id='$article_id' and comment.status='1' and comment.data_status='1' order by comment.Id limit $min_id,$length";
		$res = $this->getall ( $query );
		return $res;
	}
	function getArticleClass($article_id) {
		$query = "select class where Id='$article_id' and status='1' and data_status='1'";
		$class = $this->getone ( $query );
		return $class;
	}
	function getResouce($id) {
		$query = "select name,author,create_time,small_img,data,type,remark from resouce where Id='$id' and status='1' and data_status='1'";
		$res = $this->getrow ( $query );
		return $res;
	}
}