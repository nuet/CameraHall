<?php
class OrderViewModel extends ViewModel {
	public $viewFields=array(
		'Article'=>array('id'=>'gid','price'=>'price','pay_price'=>'pay_price','photo'=>'photo','title'=>'title',
			'img1'=>'img1','img2'=>'img2','img3'=>'img3','img4'=>'img4',
			'img1_name'=>'img1_name','img2_name'=>'img2_name','img3_name'=>'img3_name','img4_name'=>'img4_name',
			'img1_title'=>'img1_title','img2_title'=>'img2_title','img3_title'=>'img3_title','img4_title'=>'img4_title'
			),
		'YuyueOrders'=>array('id'=>'id','order_id'=>'order_id','name','price','email','note','goods_id','tp_id','gender','place_id','tel','status','state','open_id','createtime','_on'=>'Article.id=YuyueOrders.goods_id'),
		'YuyueTime'=>array('time_point','_on'=>'YuyueTime.id=YuyueOrders.tp_id'),
		'YuyueDate'=>array('yuyue_date','_on'=>'YuyueTime.date_id =YuyueDate.id'),
		'Store'=>array('name'=>'store_name','area'=>'store_area','tel'=>'store_tel','email'=>'store_email','bus'=>'store_bus','_on'=>'Store.id=YuyueOrders.place_id'),
		//'Category'=>array('title'=>'ctitle','_on'=>'Category.id=Article.catid'),
		//'place'=>array('username','name','_on'=>'User.id=Article.created_by'),
	);
}
?>