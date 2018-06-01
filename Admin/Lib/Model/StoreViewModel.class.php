<?php
class StoreViewModel extends ViewModel {
	public $viewFields=array(
		'Store'=>array('id'=>'cid','name'=>'cname','area'=>'carea','published'=>'cpublished','tel'=>'ctel','order'=>'corder','email'=>'cemail','bus'=>'cbus','access'=>'caccess','lid'=>'clid','cityid'),
		'City'=>array('name'=>'tname','_on'=>'Store.cityid=City.id'),
	);
}
?>