<?php
class ArticleAction extends CommonAction {
	function index(){
		import('ORG.Util.Page');
		$art=new ArticleViewModel();
		$keyword=trim($_POST['keyword']);
		$ftype=$_POST['ftype'];
		if(!empty($keyword) && !empty($ftype)){
			$where[$ftype]=array('like','%'.$keyword.'%');
			$this->assign('ftype', $ftype) ;
			$this->assign('keyword',$keyword);
		}
		
		$count=$art->where($where)->count();
		$page=new Page($count,C('PAGESIZE'));
		$show=$page->show();
		$this->assign("show",$show);
		//$list=$art->order('Article.id')->where($where)
		$list=$art->order('Article.order desc')->where($where)
		->limit($page->firstRow.','.$page->listRows)->select();
		
		$this->assign('alist',$list);
		 //echo "<pre>";var_dump($list);die;  //$list=$art->order('aorder desc')->where($where)
		$role=new Model('Role');
		$list=$role->getField('id,name');
		$this->assign('rlist',$list);

		$yyt=new StoreModel();
		$city = $yyt->where('published =1')->select();
		$this->assign('scity',$city);
		
		$this->display();
	}

	function insert(){
		//echo "<pre>";var_dump($_FILES);die;
		$pinfo = $this->upload();	
	    
		$art=new ArticleModel();
		if($data=$art->create()){
			if($pinfo[0]['savename']){
				// $art->photo = $pinfo[0]['savename'];
				foreach ($pinfo as $key => $value) {
				  	if ($value['key'] == 'photo') {
				  		$art->photo = $pinfo[$key]['savename'];
				  	}
				  	if ($value['key'] == 'imga') {
				  		$art->img1 = $pinfo[$key]['savename'];
				  	}
				  	if ($value['key'] == 'imgb') {
				  		$art->img2 = $pinfo[$key]['savename'];
				  	}
				  	if ($value['key'] == 'imgc') {
				  		$art->img3 = $pinfo[$key]['savename'];
				  	}
				  	if ($value['key'] == 'imgd') {
				  		$art->img4 = $pinfo[$key]['savename'];
				  	}
				  }
			} 
			if(false!==$art->add()){
				$this->assign('jumpUrl',__URL__.'/index');
				$this->success('操作成功，插入数据编号为：'.$art->getLastInsID());
			}else{
				$this->error('操作失败：addsection'.$art->getDbError());
			}
		}else{
			$this->error('操作失败：数据验证( '.$art->getError().' )');
		}
	}

	function update(){
		//echo "<pre>";var_dump($_FILES);die;
		$om	=new YuyueOrdersModel();
		$where['goods_id']=$_POST['id'];
		$where['status'] = array('in',array('0','1'));
		$where['state'] = '0';
		
		$orders = $om->where($where)->select();
		//if(!empty($orders)){
		//	$order_id = $orders[0]['order_id'];
		//	$this->error("此产品存在未完成订单".$order_id."，请勿修改",U('Article/index'));	
		//}
		
		if(!empty($_FILES['photo']['size'])|| !$_POST['photo']||!empty($_FILES['imga']['size'])|| !$_POST['imga']||!empty($_FILES['imgb']['size'])|| !$_POST['imgb']||!empty($_FILES['imgc']['size'])|| !$_POST['imgc']||!empty($_FILES['imgd']['size'])|| !$_POST['imgd']){
			$pinfo = $this->upload();	
		}
		
		
		$art=new ArticleModel();
		if($data=$art->create()){
			if(!empty($data['id'])){

				if($pinfo[0]['savename']){
				  foreach ($pinfo as $key => $value) {
				  	if ($value['key'] == 'photo') {
				  		$art->photo = $pinfo[$key]['savename'];
				  	}
				  	if ($value['key'] == 'imga') {
				  		$art->img1 = $pinfo[$key]['savename'];
				  	}
				  	if ($value['key'] == 'imgb') {
				  		$art->img2 = $pinfo[$key]['savename'];
				  	}
				  	if ($value['key'] == 'imgc') {
				  		$art->img3 = $pinfo[$key]['savename'];
				  	}
				  	if ($value['key'] == 'imgd') {
				  		$art->img4 = $pinfo[$key]['savename'];
				  	}
				  }
				} 

				if(false!==$art->save()){
					$this->assign('jumpUrl',__URL__.'/index');
					$this->success('操作成功');
				}else{
					$this->error('操作失败：'.$art->getDbError());
				}
			}else{
				$this->error('请选择编辑用户');
			}
		}else{
			$this->error('操作失败：数据验证( '.$art->getError().' )');
		}
	}

	function add(){
		$sec=new SectionModel();
		$list=$sec->order('id')->select();
		$this->assign('slist',$list);
		$cat=new CategoryModel();
		$list=$cat->order('sectionid,id')->select();
		$this->assign('clist',$list);
		
		$role=new Model('Role');
		$list=$role->select();
		$this->assign('rlist',$list);

		$yyt=new StoreModel();
		$city = $yyt->where('published =1')->select();
		$this->assign('scity',$city);

		$this->display();
	}

	function edit(){
		$id=$_GET['id'];
		if(!empty($id)){
			$art=new ArticleModel();
			$date=$art->getById($id);
			$this->assign('udate',$date);
			$sec=new SectionModel();
			$list=$sec->order('id')->select();
			$this->assign('slist',$list);
			$cat=new CategoryModel();
			$list=$cat->select();
			$this->assign('clist',$list);
			
			$role=new Model('Role');
			$list=$role->select();
			$this->assign('rlist',$list);

			$yyt=new StoreModel();
		    $city = $yyt->where('published =1')->select();
		    $this->assign('scity',$city);
		}
		$this->display();
	}
	
	function delete(){
		
		$did=$_POST['did'];
		
		if(!empty($did) && is_array($did)){
			
			$om	=new YuyueOrdersModel();
			$where['goods_id']=array('in',$did);
			$where['status'] = array('in',array('0','1'));
			$where['state'] = '0';
			
			$orders = $om->where($where)->select();
			if(!empty($orders)){
				$order_id = $orders[0]['order_id'];
				$this->error("此产品存在未完成订单".$order_id."，请勿删除",U('Article/index'));	
			}
			
			$art=new ArticleModel();
			$id=implode(',',$did);
			if(false!==$art->where('id in('.$id.')')->delete()){
				$this->assign('jumpUrl',__URL__.'/index');
				$this->success('操作成功');
			}else{
				$this->error('操作失败：'.$art->getDbError());
			}
		}else{
			$this->error('请选择删除用户');
		}
	}
	
	
	
	function helper(){
		import('ORG.Util.Page');
		$art=new ArticleViewModel();
		$keyword=$_POST['keyword'];
		$ftype=$_POST['ftype'];
		if(!empty($keyword) && !empty($ftype)){
			$where[$ftype]=array('like','%'.$keyword.'%');
			$_SESSION['keyword']=$where;
		}else{
			if(empty($keyword) && !empty($ftype)){
				unset($_SESSION['keyword']);
			}else if(!empty($_SESSION['keyword'])){
				$where=$_SESSION['keyword'];	
			}
		}
		$where['Article.published']=array('gt',0);
		$count=$art->where($where)->count();
		$page=new Page($count,C('PAGESIZE'));
		$show=$page->show();
		$this->assign("show",$show);
		$list=$art->order('Article.id')->where($where)
		->limit($page->firstRow.','.$page->listRows)->select();
		
		$this->assign('alist',$list);
		$role=new Model('Role');
		$list=$role->getField('id,name');
		$this->assign('rlist',$list);
		
		$this->display();
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
		// dump($info);die;
		return $info;
	 }
	 //产品排序
    public function sort(){
		//echo 11;
		$art=new ArticleModel();
    	if(IS_AJAX){
    	    $id = I('id',0,'intval');
    		$sort = I('sort',0,'intval');
			$where['id'] = $id;
			$data['order'] = $sort;
			//var_dump($data);
    		$result = $art->where($where)->save($data);
			//echo $art->getLastSql(); 
			//var_dump($result);die;
    		if($result){
				$this->ajaxReturn(array('status'=>1,'msg'=>'修改成功！'));
			}else{
				$this->ajaxReturn(array('status'=>0,'msg'=>'修改失败！'));
			}
    	}
    }
}
?>