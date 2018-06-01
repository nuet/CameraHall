<?php
class YuyueTimeModel extends Model {
	
	protected $_auto=array(
		array('time_point','getTimePoint',3,'callback'),
		array('time_store','getTimeStore',3,'callback'),
	);
	
	
	public function getTimePoint(){
		if(empty($_POST['time_point'])){
			return date("10:00");
		}else{
			//需要判断是否是中文
			return $_POST['time_point'];
		}
	}
	public function getTimeStore(){
		if(empty($_POST['time_store'])||!is_numeric($_POST['time_store'])){
			return 1;
		}else{
			//需要判断是否是中文
			return $_POST['time_store'];
		}
	}	
	
}
?>