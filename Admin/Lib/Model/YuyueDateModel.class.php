<?php
class YuyueDateModel extends Model {

	protected $_auto=array(
		array('yuyue_date','getYuyueDate',3,'callback'),
		
	);
	
	
	public function getYuyueDate(){
		if(empty($_POST['yuyue_date'])){
			return date("Y-m-d-H-i-s");
		}else{
			//需要判断是否是中文
			return $_POST['yuyue_date'];
		}
	}
	
}
?>