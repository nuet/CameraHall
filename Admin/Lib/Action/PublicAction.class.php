<?php
// +----------------------------------------------------------------------
// | 17Joys [ 让我们一起开发内容管理系统 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.17joys.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 马明 <alex0018@126.com>
// +----------------------------------------------------------------------
//
class PublicAction extends Action {
	function left(){
		$menu=new MenuModel();
		$list=$menu->select();
		$this->assign('mlist',$list);
		$this->display();
	}
	function login(){
		if(empty($_SESSION[C('USER_AUTH_KEY')])){
			$this->display();
		}else{
			$this->redirect('Index/index');
		}
	}
	
	function verify(){
		
		import("ORG.Util.Image");
		Image::buildImageVerify();		
	}
	
	function checklogin(){
		// echo "<pre>";var_dump(md5($_POST['verify']),$_SESSION['verify']);exit();
		if(empty($_POST['username'])) {
			$this->error('帐号错误！');
		}elseif (empty($_POST['password'])){
			$this->error('密码必须！');
		}elseif (empty($_POST['verify'])){
			$this->error('验证码必须！');
		}
		$map=array();
		$map['username']=$_POST['username'];
		$map['active']=array('gt',0);
		if($_SESSION['verify'] != md5($_POST['verify'])) {
			$this->error('验证码错误！');
		}
		
		import('ORG.Util.RBAC');
		$authInfo=RBAC::authenticate($map);
		
		if(empty($authInfo)){
			$this->error('账号不存在或者被禁用!');
		}else{
			if($authInfo['password']!=md5($_POST['password'])){
				$this->error('账号密码错误!');
			}
			$_SESSION[C('USER_AUTH_KEY')]=$authInfo['id'];
			$_SESSION['email']=$authInfo['email'];
			$_SESSION['nickname']=$authInfo['name'];
            $_SESSION['lastLoginDate']=$authInfo['last_login_date'];
			
			if($authInfo['username']=='admin'){
				$_SESSION[C('ADMIN_AUTH_KEY')]=true;
			}
			
			$user=M('User');
			$lastdate=date('Y-m-d H:i:s');
            $data=array();
			$data['id']=$authInfo['id'];
			$data['last_login_date']=$lastdate;
			$user->save($data);
			
			RBAC::saveAccessList();
			$this->assign('jumpUrl',__APP__.'/Index/index');
			$this->success('登录成功!');
		}
	}
	
	function logout(){
		if(!empty($_SESSION[C('USER_AUTH_KEY')])){
			unset($_SESSION[C('USER_AUTH_KEY')]);
			$_SESSION=array();
			session_destroy();
			$this->assign('jumpUrl',__URL__.'/login');
			$this->success('登出成功');
		}else{
			$this->error('已经登出了');
		}
	}
	
	function main(){
		
		$orderobj = new OrdersViewModel();
		

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
		//dump($_POST);die;
		$startdate=$_POST['startdate'];
		$enddate = $_POST['enddate'];
		if($startdate ||$enddate){
			//dump(strtotime($startdate));die;
			if($startdate&&$enddate){
				$where['YuyueOrders.createtime'] =  array('between',array(strtotime($startdate),strtotime($enddate)));	
			}
			if($startdate&&!enddate){
				$where['YuyueOrders.createtime'] = array('egt',strtotime($startdate));
				
			}
			
			if(!$startdate&&enddate){
				$where['YuyueOrders.createtime'] = array('elt',strtotime($enddate));
				
			}
			$this->assign('startdate',$startdate);
			$this->assign('enddate',$enddate);
			
		}else{
			
			$startdate =date('Y-1-1',time());
			$enddate =date('Y-m-1',time()+3600*24*30);	
		}
		
		$this->assign('startdate',$startdate);
		$this->assign('enddate',$enddate);
		
		
		$where['YuyueOrders.createtime'] =  array('between',array(strtotime($startdate),strtotime($enddate)));	
		//$where['YuyueOrders.status']='1';
		$data  = $orderobj->order('YuyueOrders.createtime')->where($where)->select();
		
		$notes = array();
		$tips = array();
		foreach ($data as $value) {
			
			if($value['status']=='1'){	
				//按时间（月）获取金额	
				$m = date('m',$value['createtime']);
				$notes[$m]['income'] += $value['price'];
				$notes[$m]['mouth'] = $m."月";
				
				//按门店统计比例与金额
				$tips[$value['store_name']]['price'] +=$value['price'];
				$tips[$value['store_name']]['store_name'] =$value['store_name'];
				
			}else if($value['status']=='-2'){
				$m = date('m',$value['createtime']);
				$notes[$m]['expenses'] +=$value['return_fee']/100;	
				$notes[$m]['mouth'] = $m."月";
			}	
		
		}
		//dump($data);die;
		$this->assign('tips',json_encode(array_values($tips)));
		$this->assign('notes',json_encode(array_values($notes)));
		$this->display();
	}
}
?>