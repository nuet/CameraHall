<?php
class OrdersModel extends Model {
	protected  $_validate = array(
		array('name','require','分类标题必须填写！',1),
		array('published',array(0,1),'启用：1 ; 停用：0',0,'in'),
	);
}
?>