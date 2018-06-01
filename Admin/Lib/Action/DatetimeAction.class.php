<?php

class DatetimeAction extends CommonAction {
	
	public static $defaulttimearr =array('09:30','09:55','10:20','10:45','11:10','11:35','12:00','12:25','12:50','13:15','13:40','14:05','14:30','14:55','15:20','15:45','16:10','16:35','17:00','17:25','17:50','18:15');
	//public static $defaulttimearr =array('09:30','09:55','10:20');
	public static $defaultdatecount = 1;


	function insert(){
		
		$art=new YuyueDateModel();
		$yyt=new YuyueTimeModel();
		
		if(!$_POST['yuyue_date']||empty($_POST['yuyue_date'])){
			
			$this->error('日期为空，请重新输入！！',U('Datetime/add'));
		}
		
		if(empty($_POST['place']) || $_POST['place']=='0'){
			
			$this->error('门店为空，请选择门店！！',U('Datetime/add'));	
		}
		$where =array('place_id'=>$_POST['place'],'yuyue_date'=>$_POST['yuyue_date']);
		$data = $art->where($where)->select();
		
		if(!empty($data)){
			
			$this->error('门店日期已存在，请重新输入！！',U('Datetime/add'));	
		}
	
		
		$arr['yuyue_date'] = $_POST['yuyue_date'];
		$arr['yuyue_timestamp'] = strtotime($_POST['yuyue_date']);
		$arr['place_id'] = $_POST['place'];
		$res = $art->add($arr);
		if($res){
			foreach ($_POST['time_point'] as $key=>$value) {
				$arr1[$key]['date_id'] = $res;
				$arr1[$key]['time_point'] = $value;
				$arr1[$key]['time_store'] = $_POST['time_store'][$key];
			}
			
			$re = $yyt->addAll($arr1);
			if($re){
				$this->success('操作成功！',U('Datetime/date'));		
			}
			
		}else{
			$this->error('操作失败，请重新输入！！',U('Datetime/add'));	
		}
		
	}

	function update(){
		//echo "<pre>";var_dump($_FILES);die;
		$art=new YuyueDateModel();
		$yyt=new YuyueTimeModel();

		if(!$_POST['yuyue_date']||empty($_POST['yuyue_date'])){
			
			$this->error('日期为空，请重新编辑！！',U('Datetime/date'));
		}
		if(empty($_POST['place'])|| $_POST['place']=='0'){
			
			$this->error('门店为空，请重新编辑！！',U('Datetime/date'));
		}
	
		
		//$times = $yyt->where('date_id = '.$_POST['id'])->select();
		//if($times){
			//$ids = array();  
			//$ids = array_map('array_shift', $times);
			
			//$om	=new YuyueOrdersModel();
		//	$where['tp_id']= array('in',$ids);
			//$where['status'] = array('in',array('0','1'));
	//		$where['state'] = '0';
			
		//	$orders = $om->where($where)->select();
			//if(!empty($orders)){
				//$order_id = $orders[0]['order_id'];
				//$this->error("此日期存在未完成订单".$order_id."，请勿修改",U('Datetime/date'));	
			//}
			
		//}
	
		$data['id'] = $_POST['id'];
		$data['yuyue_date'] = $_POST['yuyue_date'];
		$data['yuyue_timestamp'] = strtotime($_POST['yuyue_date']);
		$data['place_id'] = $_POST['place'];
		$res = $art->save($data);
		//dump($res);die;
		
		if(1){
			foreach ($_POST['time_point'] as $key=>$value) {
				$arr1=array();
				$arr1['date_id'] = $data['id'];
				$arr1['id'] = $_POST['tid'][$key];
				$arr1['time_point'] = $value;
				$arr1['time_store'] = $_POST['time_store'][$key];
				
				$re = $yyt->save($arr1);
			}
			
			$this->success('操作成功！',U('Datetime/date'));		
		
			
		}else{
			$this->error('操作失败，请重新输入！！',U('Datetime/date'));	
		}
	}

	function add(){
		
		//$art=new YuyueDateModel();
		$yyt=new StoreModel();
		$city = $yyt->where('published =1')->select();
		//dump($city);die;
		$data = F('websetting');
		$time_points = $data['time_point']? $data['time_point'] :self::$defaulttimearr;
		$time_store = $data['time_store']? $data['time_store'] :self::$defaultdatecount;
			
		$this->assign('curtime',date('Y-m-d',time()-3600*24));
		$this->assign('time_store',$time_store);
		$this->assign('timepoint',$time_points);
		$this->assign('citys',$city);
		
		$this->display();
	}

	function edit(){

		$id=intval($_GET['id']);
		if(empty($id)){
			$this->error('ID为空，请重新选择！！',U('Datetime/date'));
		}
		$yyt=new StoreModel();
		$city = $yyt->where('published =1')->select();
		
		$art=new YuyueDateModel();
		
		$list = $art->table('joys_yuyue_date yyd,joys_store js,joys_yuyue_time yyt')->where('yyd.id=yyt.date_id and js.id=yyd.place_id and yyd.id='.$id)->order('yyt.id asc')->select();
		//echo "<pre>";var_dump($list);die;
		
		$this->assign('curtime',date('Y-m-d',time()-3600*24));
		$this->assign('list',$list);
		$this->assign('id',$id);
		$this->assign('place_id',$list[0]['place_id']);
		$this->assign('date',$list[0]['yuyue_date']);
		$this->assign('citys',$city);
		//dump($list);die;
		$this->display();
	}
	
	function delete(){
		
		$did=$_POST['did'];
		if(!empty($did) && is_array($did)){
			
			$art=new YuyueDateModel();
			$yyt=new YuyueTimeModel();
			$id=implode(',',$did);
			
			$where['date_id']=array('in',$id);
			
			//删除之前看有无订单
			$times = $yyt->where($where)->select();
			if($times){
				$ids = array();  
				$ids = array_map('array_shift', $times);
				
				$om	=new YuyueOrdersModel();
				$where['tp_id']=array('in',$ids);
				$where['status'] = array('in',array('0','1'));
				$where['state'] = '0';
				
				$orders = $om->where($where)->select();
				if(!empty($orders)){
					$order_id = $orders[0]['order_id'];
					$this->error("此日期存在未完成订单".$order_id."，请勿删除",U('Datetime/date'));	
				}
				
			}
		
			if(false!==$art->where('id in('.$id.')')->delete()){
				
				$yyt->where($where)->delete();
				
				$this->assign('jumpUrl',__URL__.'/date');
				$this->success('操作成功');
			}else{
				$this->error('操作失败：'.$art->getDbError());
			}
		}else{
			$this->error('请选择删除用户');
		}
	}
	
	
	public function date(){
		
		$keyword=$_POST['keyword'];
		$ftype=$_POST['ftype'];		
		
		$sm=new StoreModel();
		if(!empty($keyword) && !empty($ftype)){
			$place_id= $sm->field('id')->where('name like "%'.$keyword.'%"')->select();
			$arr =array();
			foreach ($place_id as $value) {
				$arr[] = $value['id'];	
			}
			
			$where ="place_id in (".implode(',',$arr).")";
			
			$this->assign('keyword',$keyword);
		}
		
		$art=new YuyueDateModel();
		$yyt=new YuyueTimeModel();
		
		
		$date = date('Y-m-d',time());
		$t = strtotime($date);
		
		//$where ='yuyue_timestamp >= '.$t ;
		$list=$art->where($where)->order('id desc')->select();
		
		$count = count($list);
		foreach ($list as &$v){
			$store =$sm->where('id= '.$v['place_id'])->find();	
			$v['place'] = $store['name'];
			
		}
		
//		$data = F('websetting');
		//dump($list);die;
//		if($data&&$data['offline']=='1'){
//			
//			$l = $data['date_length'] ? ($data['date_length']):self::$defaultdatecount;
//			$time_store = $data['time_store'] ? $data['time_store'] :1;
//			$time_points = $data['time_point']? $data['time_point'] :self::$defaulttimearr;
//			
//			if(!$list||empty($list)||$count<$l){
//			
//				$curtime = !empty($list) ? $list[$count-1]['yuyue_timestamp'] : time();
//				$length = !empty($list) ? ($l -$count) : $l;
//			 	
//				//dump(date("Y-m-d",$curtime));die;
//			 	for ($i=1;$i<=$length;$i++){ 
//					$data['yuyue_date']=date('Y-m-d',$curtime+$i*24*60*60); 
//					$data['yuyue_timestamp'] = strtotime($data['yuyue_date']); 
//					$res =  $art->add($data);
//					if($res){
//						
//						foreach ($time_points as $key=> $val){
//							$list1[$key]['date_id'] = $res;
//							$list1[$key]['time_point'] = $val;
//							$list1[$key]['time_store'] = $time_store;
//						}
//						
//						$yyt->addAll($list1);	
//						
//					}
//				}
//			}
//		}	
			$this->assign('list',$list);
			$this->display();
			
		
	}
	
	public function setting(){
		
		$data = F('webSetting');
		
		//dump($data);die;
		$this->assign('data',$data);
		
		$this->display('');
	}
	
	public function to_setting(){
		
		//dump($_POST);die;
		if($_POST['time_point']){
			
			$_POST['time_point'] = array_filter ($_POST['time_point']);	
			
		}
		F('webSetting',$_POST);
		
		$this->success('操作成功',U('Datetime/setting'));
		
	}
	
	Public function upload(){
	 	import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath =  './Public/Uploads/';// 设置附件上传目录
		 if(!$upload->upload()) {// 上传错误提示错误信息
		  $this->error($upload->getErrorMsg());
		 //echo $upload->getErrorMsg();
		 }else{// 上传成功 获取上传文件信息
		$info =  $upload->getUploadFileInfo();
		 }
		 // 保存表单数据 包括附件数据
		//var_dump($info);die;
		return $info;
	 }
	
}
?>