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
function parseParams($params){
	$p=explode("\n",$params);
	$r=array();
	foreach ($p as $v){
		$tmp=explode('=',$v);
		$r[$tmp[0]]=$tmp[1];
	}
	return $r;
}
?>