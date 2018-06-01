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
class StoreAction extends CommonAction {

	function index(){
		import('ORG.Util.Page');

		$cat=new StoreViewModel();
		$keyword=$_POST['keyword'];
		$ftype=$_POST['ftype'];
		if(!empty($keyword) && !empty($ftype)){
			$where['Store.'.$ftype]=array('like','%'.$keyword.'%');
			$_SESSION['keyword']=$where;
		}else{
			if(empty($keyword) && !empty($ftype)){
				unset($_SESSION['keyword']);
			}else if(!empty($_SESSION['keyword'])){
				$where=$_SESSION['keyword'];	
			}
		}
		// echo "<pre>";var_dump($keyword,$ftype,$_SESSION['keyword']);exit;
		$count=$cat->where($where)->count();
		$page=new Page($count,C('PAGESIZE'));
		$show=$page->show();
		$this->assign("show",$show);
		$list=$cat->order('Store.id')->where($where)->order('lid asc')->limit($page->firstRow.','.$page->listRows)->select();
		// echo "<pre>";var_dump($keyword,$ftype,$_SESSION['keyword'],$list);exit;
		$this->assign('clist',$list);		
		$role=new Model('Role');
		$list=$role->getField('id,name');
		$this->assign('rlist',$list);
	
		$this->display();
	}

	function insert(){
		
		if(!$_POST['cityid']){
			$this->error('请选择门店对应的城市');	
		}
		$cat=new StoreModel();
		if($data=$cat->create()){
			if(false!==$cat->add()){
				$this->assign('jumpUrl',__URL__.'/index');
				$this->success('操作成功，插入数据编号为：'.$cat->getLastInsID());
			}else{
				$this->error('操作失败：addsection'.$cat->getDbError());
			}
		}else{
			$this->error('操作失败：数据验证( '.$cat->getError().' )');
		}
	}

	function update(){
		
		$om	=new YuyueOrdersModel();
		$where['place_id']=$_POST['id'];
		$where['status'] = array('in',array('0','1'));
		$where['state'] = '0';
		if(!$_POST['cityid']){
			$this->error('请选择门店对应的城市');	
		}
		
		$orders = $om->where($where)->select();
		//if(!empty($orders)){
		//	$order_id = $orders[0]['order_id'];
		//	$this->error("此门店存在未完成订单".$order_id."，请勿修改",U('Store/index'));	
		//}
		$cat=new StoreModel();
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
		$sec=new CityModel();
		$list=$sec->order('id')->select();
		$this->assign('slist',$list);
		
		$role=new Model('Role');
		$list=$role->select();
		$this->assign('rlist',$list);
		$this->display();
	}

	function edit(){
		$id=$_GET['id'];
		if(!empty($id)){
			$cat=new StoreModel();
			$date=$cat->getById($id);
			$this->assign('udate',$date);
			
			$sec=new CityModel();
			$list=$sec->order('id')->select();
			$this->assign('slist',$list);
			
			$role=new Model('Role');
			$list=$role->select();
			$this->assign('rlist',$list);
		}
		$this->display();
	}
	
	function delete(){
		
		$did=$_POST['did'];
		
		$om	=new YuyueOrdersModel();
		$where['place_id']=array('in',$did);
		$where['status'] = array('in',array('0','1'));
		$where['state'] = '0';
		
		$orders = $om->where($where)->select();
		if(!empty($orders)){
			$order_id = $orders[0]['order_id'];
			$this->error("此门店存在未完成订单".$order_id."，请勿删除",U('Store/index'));	
		}
		
		if(!empty($did) && is_array($did)){
			$cat=new StoreModel();
			$id=implode(',',$did);
			if(false!==$cat->delete($id)){
				$this->assign('jumpUrl',__URL__.'/index');
				$this->success('操作成功');
			}else{
				$this->error('操作失败：'.$cat->getDbError());
			}
		}else{
			$this->error('请选择删除用户');
		}
	}
	
	function helper(){
		import('ORG.Util.Page');
		$cat=new StoreViewModel();
		$keyword=$_POST['keyword'];
		$ftype=$_POST['ftype'];
		if(!empty($keyword) && !empty($ftype)){
			$where['Category.'.$ftype]=array('like','%'.$keyword.'%');
			$_SESSION['keyword']=$where;
		}else{
			if(empty($keyword) && !empty($ftype)){
				unset($_SESSION['keyword']);
			}else if(!empty($_SESSION['keyword'])){
				$where=$_SESSION['keyword'];	
			}
		}
		$where['Category.published']=array('gt',0);
		$count=$cat->where($where)->count();
		$page=new Page($count,C('PAGESIZE'));
		$show=$page->show();
		$this->assign("show",$show);
		$list=$cat->order('Category.id')->where($where)
		->limit($page->firstRow.','.$page->listRows)->select();
		
		$this->assign('clist',$list);		
		
		$role=new Model('Role');
		$list=$role->getField('id,name');
		$this->assign('rlist',$list);
		
		$this->display();
	}
}
?>