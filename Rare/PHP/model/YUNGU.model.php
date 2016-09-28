<?php 
include ('BASE.model.php');
class YUNGUModel extends BASEModel {
	
	function getTypeList(){
		$query="select id,parentId,title from types where status=1";
		$allType=$this->getAll($query);
		$outTypes=array();
		foreach ($allType as $type){
			$outTypes[$type['parentId']][]=$type;
		}
		apiOutPut($outTypes);
	}
}