<?php
class YuyueOrdersModel extends Model {

	protected $_auto=array(
		array('createtime','getCreatetime',3,'callback'),
		
	);
	
	
	public function getCreatetime(){
		if(empty($_POST['createtime'])){
			return time();
		}else{
			//需要判断是否是中文
			return $_POST['createtime'];
		}
	}
	
}
?>