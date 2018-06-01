<?php
class ArticleViewModel extends ViewModel {
	public $viewFields=array(
		'Article'=>array('id'=>'aid','price'=>'price','pay_price'=>'pay_price','length_of_service'=>'length_of_service','look_like_time'=>'look_like_time','access_method'=>'access_method','photo'=>'photo','title'=>'atitle','introtext','published'=>'apublished','sectionid'=>'sid','created','created_by','order'=>'aorder',
			'img1'=>'img1','img2'=>'img2','img3'=>'img3','img4'=>'img4',
			'img1_name'=>'img1_name','img2_name'=>'img2_name','img3_name'=>'img3_name','img4_name'=>'img4_name',
			'img1_title'=>'img1_title','img2_title'=>'img2_title','img3_title'=>'img3_title','img4_title'=>'img4_title',
			'access'=>'aaccess','store_id','hits'),
		'Section'=>array('title'=>'stitle','_on'=>'Section.id=Article.sectionid'),
		//'Category'=>array('title'=>'ctitle','_on'=>'Category.id=Article.catid'),
		'Store'=>array('name'=>'store_name','_on'=>'Store.id=Article.store_id'),
		'User'=>array('username','name','_on'=>'User.id=Article.created_by'),
	);
}
?>