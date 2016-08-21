<?php
include ('userbase.app.php');

class ApiApp extends userbaseApp
{
	
	function oneDirct(){
		$key=$_GET['key']?$_GET['key']:'空';
		//$key='孔';
		$url='http://api.avatardata.cn/XinHuaZiDian/LookUp?key=d9dbe824c49748e9a47d08fd8444b636&content='.$key;
		$res=file_get_contents($url);
		put($res);
		return;
	}
	
	function chengDirct(){
		$key=$_GET['key']?$_GET['key']:'龙马精神';
		$url='http://api.avatardata.cn/ChengYu/Search?key=f2d6bf978f7748e98755ab7773ef457b&rows=10&keyWord='.$key;
		$res=$this->post($url,array('rows'=>10));
		echo $res;
		//put($res);
		return;
	}
	
	function chengOne(){
		$key=$_GET['id']?$_GET['id']:'d420b457-4b86-4ab1-b824-cb84440131fc';
		$url='http://api.avatardata.cn/ChengYu/LookUp?key=f2d6bf978f7748e98755ab7773ef457b&id='.$key;
		$res=$this->post($url,array('dtype'=>'JSON'));
		echo $res;
		//put($res);
		return;
	}
	
	function post($url, $data){//file_get_content
 
         
 
        $postdata = http_build_query(
 
            $data
 
        );
 
         
 
        $opts = array('http' =>
 
                      array(
 
                          'method'  => 'POST',
 
                          'header'  => 'Content-type: application/x-www-form-urlencoded',
 
                          'content' => $postdata
 
                      )
 
        );
 
         
 
        $context = stream_context_create($opts);
 
 
        $result = file_get_contents($url, false, $context);
 
        return $result;
 
 
    }
 
 
	
	function articleList()
	{
		if(isget())
		{
			$page_num=10;
			$this->data['class']=!empty($this->data['class'])?$this->data['class']:'party_new';
			$min_id=!empty($this->data['page'])?($this->data['page']-1)*$page_num:0;
			$father_id=!empty($this->data['father_id'])?$this->data['father_id']:0;
			$classlevel=checkclass($this->data['class']);
			
			$login=$this->user->login;
			if($father_id==0 and $this->data['class']=='activaty_aticle')apiOutPut(array(),301,"缺少必要参数");
			/* if($login===0 && $classlevel>=2)
			{
				apiOutPut(false,403,'没有足够的权限');
			} */
			$artmodel=&m('ARTICLE');
			$list=$artmodel->getArticleList($this->data['class'],$father_id,$min_id,$page_num);
			$list?apiOutPut($list):apiOutPut(array(),404,'未找到数据');
			//apiOutPut($list);
		}
		
		if(ispost())
		{
			
		}
		
		if(isput())
		{
				
		}
		
		if(isdelete())
		{
				
		}
	}
	
	
	function article()
	{
		if(isget())
		{
			$artmodel=&m('ARTICLE');
			if(empty($this->data['id']))
			{
				apiOutPut((object)null,301,"缺少必要参数");
			}
			$class=$artmodel->getArticleClass($this->data['id']);
			$classlevel=checkclass($class);
			$login=$this->user->login;
			
		/* 	if($login===0 && $classlevel>=2)
			{
				apiOutPut(false,403,'没有足够的权限');
			}	 */
			
			$list=$artmodel->getArticle($this->data['id']);
			$list?apiOutPut($list):apiOutPut((object)null,404,'未找到数据');
			//apiOutPut($list);
		}
	
		if(ispost())
		{
				
		}
	
		if(isput())
		{
	
		}
	
		if(isdelete())
		{
	
		}
	}
	
	
	function activatyList()
	{
		if(isget())
		{
			$page_num=10;
			//$this->data['class']=!empty($this->data['class'])?$this->data['class']:'party_new';
			$min_id=!empty($this->data['page'])?($this->data['page']-1)*$page_num:0;
			//$father_id=!empty($this->data['father_id'])?$this->data['father_id']:0;
			//$classlevel=checkclass($this->data['class']);
			
			$login=$this->user->login;
		/* 	if($login===0 )
			{
				apiOutPut(false,403,'没有足够的权限');
			}  */
			$artmodel=&m('ARTICLE');
			$list=$artmodel->getActivatyList($min_id,$page_num);
			$list?apiOutPut($list):apiOutPut(array(),404,'未找到数据');
			//apiOutPut($list);
		}
	
		if(ispost())
		{
	
		}
	
		if(isput())
		{
	
		}
	
		if(isdelete())
		{
	
		}
	}
	
	function activaty()
	{
		if(isget())
		{
			$artmodel=&m('ARTICLE');
			if(empty($this->data['id']))apiOutPut((object)null,301,"缺少必要参数");
			$class=$artmodel->getArticleClass($this->data['id']);
			$classlevel=checkclass($class);
			$login=$this->user->login;
			
			/* if($login===0 && $classlevel>=2)
			{
				apiOutPut(false,403,'没有足够的权限');
			} */
				
			$list=$artmodel->getActivaty($this->data['id']);
			$list?apiOutPut($list):apiOutPut((object)null,404,'未找到数据');
			//apiOutPut($list);
		}
	
		if(ispost())
		{
	
		}
	
		if(isput())
		{
	
		}
	
		if(isdelete())
		{
	
		}
	}
	
	function commentList()
	{
		if(isget())
		{
			$page_num=10;
			if(empty($this->data['father_id']))apiOutPut(array(),301,"缺少必要参数");
			$min_id=!empty($this->data['page'])?($this->data['page']-1)*$page_num:0;
			$father_id=!empty($this->data['father_id'])?$this->data['father_id']:0;
				
			$login=$this->user->login;
			
		/* 	if($login===0 )
			{
				apiOutPut(false,403,'没有足够的权限');
			} */
			//echo($father_id);
			$artmodel=&m('ARTICLE');
			$list=$artmodel->getCommentList($father_id,$min_id,$page_num);
			$list?apiOutPut($list):apiOutPut(array(),404,'未找到数据');
			//apiOutPut($list);
		}
		
		if(ispost())
		{
		
		}
		
		if(isput())
		{
		
		}
		
		if(isdelete())
		{
		
		}
	}
	
	function comment()
	{
		if(isget())
		{
		
		}
		
		if(ispost())
		{
		
		}
		
		if(isput())
		{
		
		}
		
		if(isdelete())
		{
		
		}
	}
	
	function resouce()
	{
		if(isget())
		{
			if(empty($this->data['id']))apiOutPut(array(),301,"缺少必要参数");
			/* 	if($login===0 )
			 {
			 apiOutPut(false,403,'没有足够的权限');
			 } */
			$artmodel=&m('ARTICLE');
			$list=$artmodel->getResouce($this->data['id']);
			$list?apiOutPut($list):apiOutPut(array(),404,'未找到数据');
			//apiOutPut($list);
			
		}
		
		if(ispost())
		{
		
		}
		
		if(isput())
		{
		
		}
		
		if(isdelete())
		{
		
		}
	}
	
	function userInfo()
	{
		if($this->user->login===0)
		{
			apiOutPut((object)null,403,'尚未登录');
		}
		if(isGet())
		{
			$id=$this->user->userid;
			$info=$this->getuserinfo($id);
			$info?apiOutPut($info):apiOutPut((object)null,404,'未找到数据');
		}
	}
	
}