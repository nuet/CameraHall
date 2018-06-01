<?php
class OrderAction extends CommonAction {
	
	
	function index(){
		 //echo "<pre>";var_dump($_SESSION['nickname']);exit;
		 //echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
		 //iconv("UTF-8", "UTF-8",$_GET);
		//$get = iconv("UTF-8","utf-8",$_GET['keyword']);
		 //echo "<pre>";var_dump($_POST,$_GET,$get);die;
		 
		import('ORG.Util.Page');
		$orderobj = new OrdersViewModel();
		if($_POST['ftype']==null&&$_POST['keyword']==""){ 
			$keyword= trim($_GET['keyword']);
			//var_dump($_GET['keyword']);die;
			$ftype=$_GET['ftype'];
		}else{
			$keyword=trim($_POST['keyword']);
			$ftype=$_POST['ftype'];
		}
		
		if($_POST['startdate']==null&&$_POST['enddate']==null){
			$startdate=$_GET['startdate'];
			$enddate = $_GET['enddate'];
		}else{
			$startdate=$_POST['startdate'];
			$enddate = $_POST['enddate'];
		}
		//$k = $keyword =  iconv("gb2312","gb2312",$keyword);
 //var_dump($k,$ftype);die;
		if($_SESSION['nickname']!="有一佳总店"){
			$store = new StoreModel();
			$store_ids = $store->where(array('name'=>$_SESSION['nickname']))->find();
			// $where['YuyueOrders.store_id'] = $store_ids['id'];
			if($store_ids){
                $where['YuyueOrders.place_id']=array('like','%'.$store_ids['id'].'%');
			}else{
                $this->error('为未查询到门店的订单，请联系客服核对昵称或权限',U('Store/index'));
			}
			
		}
//var_dump($keyword,$ftype);die;
		if(!empty($keyword) && !empty($ftype)){
			$where[$ftype]=array('like','%'.$keyword.'%');
			$this->assign('ftype',$ftype);
			$this->assign('keyword',$keyword);
		}
		if($startdate ||$enddate){
			//dump(strtotime($startdate));die;
			if($startdate&&$enddate){
				$where['YuyueDate.yuyue_timestamp'] =  array('between',array(strtotime($startdate),strtotime($enddate)));	
			}
			if($startdate&&!enddate){
				$where['YuyueDate.yuyue_timestamp'] = array('egt',strtotime($startdate));
				
			}
			
			if(!$startdate&&enddate){
				$where['YuyueDate.yuyue_timestamp'] = array('elt',strtotime($enddate));
				
			}
			$this->assign('startdate',$startdate);
			$this->assign('enddate',$enddate);
			
		}
		//echo "<pre>";var_dump($where);die;
		//$where[$ftype]=array('like','%'.$keyword.'%');
		$count=$orderobj->where($where)->count();	
		$page=new Page($count,C('PAGESIZE'));
		$show=$page->show();
		$this->assign("show",$show);
		//echo "<pre>";var_dump($where);die;
		$list=$orderobj->order('YuyueOrders.createtime desc')->where($where)
		->limit($page->firstRow.','.$page->listRows)->select();
		
		if($list){
			foreach ($list as &$value) {
				$value['createtime'] = date('Y-m-d H:i:s',$value['createtime']);
				$value['gender'] = $value['gender']=='0'? "男":'女'; 
				//$value['status'] = $value['status']=='0' ? "待支付":'已支付'; 
			}
		}
		
		
		$this->assign('alist',$list);
		//dump($ftype);die;
		$this->display('');
	}	
	
	function insert(){
		
		$art=new YuyueDateModel();
		$yyt=new YuyueTimeModel();
		
		if(!$_POST['yuyue_date']||empty($_POST['yuyue_date'])){
			
			$this->error('日期为空，请重新输入！！',U('Datetime/add'));
		}
		$data = $art->where("yuyue_date ='".$_POST['yuyue_date']."'")->select();
		
		if(!empty($data)){
			
			$this->error('日期已存在，请重新输入！！',U('Datetime/add'));	
		}
		$arr['yuyue_date'] = $_POST['yuyue_date'];
		$arr['yuyue_timestamp'] = strtotime($_POST['yuyue_date']);
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
			
			$this->error('日期为空，请重新输入！！',U('Datetime/date'));
		}
	
		
		$data['id'] = $_POST['id'];
		$data['yuyue_date'] = $_POST['yuyue_date'];
		$data['yuyue_timestamp'] = strtotime($_POST['yuyue_date']);;
		$res = $art->save($data);
		//dump($_POST);die;
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



	function updateorder(){
		$cat=new YuyueOrdersModel();
		if($data=$cat->create()){
			if(!empty($data['id'])){
				if(false!==$cat->save()){
					$this->assign('jumpUrl',__URL__.'/index');
					$this->success('操作成功');
				}else{
					$this->error('操作失败：'.$cat->getDbError());
				}
			}else{
				$this->error('请选择编辑用户');
			}
		}else{
			$this->error('操作失败：数据验证( '.$cat->getError().' )');
		}
	}

	function add(){
		
		//$art=new YuyueDateModel();
		//$yyt=new YuyueTimeModel();
		
		$this->assign('curtime',date('Y-m-d',time()-3600*24));
		$this->assign('timepoint',self::$defaulttimearr);
		$this->display();
	}

	function edit(){

		$id=intval($_GET['id']);
		if(empty($id)){
			$this->error('ID为空，请重新选择！！',U('Datetime/date'));
		}
		$order = new YuyueOrdersModel();
		$store = new StoreModel();
		$goods = new ArticleModel();
		$time = new YuyueTimeModel();
		$date = new YuyueDateModel();
		$orderdata = $order->where('id='.$id)->select();
		$time = $time ->where('id='.$orderdata[0]['tp_id'])->select();
		$date = $date ->where('id='.$time[0]['date_id'])->select();
		$list=$store->order('id')->select();
		$gooddata = $goods->order('id')->select();

		$createtime = date('Y-m-d H:i:s',$orderdata[0]['createtime']);


		$this->assign('date',$date);
		$this->assign('uptime',$time);
		$this->assign('slist',$list);
		$this->assign('update',$orderdata);
		$this->assign('glist',$gooddata);
		$this->assign('createtime',$createtime);

		//dump($list);die;
		$this->display();
	}
	
	function delete(){
		$did=$_POST['did'];
		if(!empty($did) && is_array($did)){

			$order = new YuyueOrdersModel();

			$id=implode(',',$did);
			if(false!==$order->where('id in('.$id.')')->delete()){
				
				$this->assign('jumpUrl',__URL__.'/index');
				$this->success('操作成功');
			}else{
				$this->error('操作失败：'.$order->getDbError());
			}
		}else{
			$this->error('请选择删除用户');
		}
	}
	
	
	


	  //导出表格
    function exportExcel($expTitle,$expCellName,$expTableData){
              $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
              $fileName = $_SESSION['account'].date('_YmdHis');//文件输出的文件名
              $cellNum = count($expCellName);
              $dataNum = count($expTableData);

                import("ORG.PHPExcel.PHPExcel");
                $objPHPExcel = new PHPExcel();//ThinkPHP3.1的写法
                $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

                $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
              for($i=0;$i<$cellNum;$i++){
                     $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
              }
            // Miscellaneous glyphs, UTF-8
             for($i=0;$i<$dataNum;$i++){
                         for($j=0;$j<$cellNum;$j++){
                                   $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
                           }
              }

              header('pragma:public');
              header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
              header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');//ThinkPHP3.1的写法            

              $objWriter->save('php://output');
              exit;
    }

            function export(){//导出Excel
             
             $id = array_filter(explode(',',$_GET['id']));
              $xlsName  = "Orders";
              $xlsCell  = array( //设置字段名和列名的映射
                  array('order_id','订单ID'),
                  array('title','产品'),
                  array('price','支付金额'),
                  array('store_name','门店'),
                  array('yuyue_date','预约日期'),
                  array('time_point','预约时间'),
                  array('name','名字'),
                  array('tel','手机'),
                  array('gender','性别'),
                  array('email','邮箱'),
                  array('note','备注信息'),
                  array('createtime','创建时间'),
                  array('status','订单状态')
          );
          $xlsModel = new OrdersViewModel();      

          $where['id']=array('in',$id);
          $xlsData  = $xlsModel->Field('order_id,title,price,store_name,yuyue_date,time_point,name,tel,gender,email,note,createtime,status')->where($where)->select();        


        //将sex字段中1和0分贝装换为“男”和“女”          

        foreach ($xlsData as $k => $v)
          {
          	 $weeks =array('日','一','二','三','四','五','六');
             $w = date('w',strtotime($myorder[0]['yuyue_date']));
               $xlsData[$k]['gender']=$v['gender']==0?'男':'女';
               $xlsData[$k]['createtime']=date('Y-m-d H:i:s',$v['createtime'])."(周$weeks[$w])";
               if ($v['status']=='-1') {
               	 $xlsData[$k]['status']="已关闭";
               }elseif($v['status']==0){
               	 $xlsData[$k]['status']="待支付";
               }else{
               	 $xlsData[$k]['status']="已完成";
               }
             
          }

           // echo "<pre>";var_dump($xlsData);exit;
          $this->exportExcel($xlsName,$xlsCell,$xlsData);
           
         }







    public function upstate(){
    	$orders = new YuyueOrdersModel();
    	$orderdata  = $orders ->where(array('id='.$_POST['id']))->find();

    if($orderdata['status']==1){
    	//已付款
    	if($orderdata['state']==1){
                 $this->ajaxReturn($res,"已核实",-1);
    	}else{
    	$orderdata['state'] = 1;
		$res = $orders->save($orderdata);
		if ($res) {
		    	 $this->ajaxReturn($res,"核实成功",1);
		    }else{
		    	 $this->ajaxReturn($res,"核实失败",0);
		    }   

    	} 

    }elseif($orderdata['status']==0){
    	//待付款
 			$this->ajaxReturn($res,"待付款",-2);
    }else{
    	//已取消
 			$this->ajaxReturn($res,"已取消",-3);
    }

    }
	
}
?>