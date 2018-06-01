set names utf8;

-- 
-- 表的结构 `joys_user`
-- 

CREATE TABLE `joys_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(150) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `reg_date` datetime NOT NULL,
  `last_login_date` datetime NOT NULL,
  `active` tinyint(1) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- 表的结构 `joys_role`
-- 

CREATE TABLE `joys_role` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `remark` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`,`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


-- --------------------------------------------------------

-- 
-- 表的结构 `joys_role_user`
-- 

CREATE TABLE `joys_role_user` (
  `role_id` mediumint(8) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  KEY `role_id` (`role_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- 表的结构 `joys_node`
-- 

CREATE TABLE `joys_node` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `remark` varchar(255) NOT NULL,
  `sort` smallint(5) unsigned NOT NULL,
  `pid` smallint(5) unsigned NOT NULL,
  `level` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`,`status`,`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


-- --------------------------------------------------------

-- 
-- 表的结构 `joys_access`
-- 

CREATE TABLE `joys_access` (
  `role_id` smallint(5) unsigned NOT NULL,
  `node_id` smallint(5) unsigned NOT NULL,
  `level` tinyint(4) NOT NULL,
  `pid` smallint(6) NOT NULL,
  KEY `role_id` (`role_id`,`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

-- 
-- 表的结构 `joys_section`
-- 

CREATE TABLE `joys_section` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `published` tinyint(1) NOT NULL default '0',
  `order` int(11) NOT NULL default '0',
  `access` tinyint(3) unsigned NOT NULL default '0',
  `params` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `joys_article`
-- ----------------------------
DROP TABLE IF EXISTS `joys_article`;
CREATE TABLE `joys_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `title_alias` varchar(255) NOT NULL,
  `introtext` mediumtext NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `sectionid` int(10) unsigned NOT NULL,
  `created` date NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(10) unsigned NOT NULL,
  `publish_up` datetime NOT NULL,
  `publish_down` datetime NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `hits` int(10) unsigned NOT NULL,
  `metadata` text NOT NULL,
  `params` text NOT NULL,
  `photo` varchar(255) NOT NULL DEFAULT '',
  `price` decimal(20,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `sectionid` (`sectionid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- 
-- 表的结构 `joys_component`
-- 

CREATE TABLE `joys_component` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `link` varchar(255) NOT NULL,
  `option` varchar(50) NOT NULL,
  `order` int(11) NOT NULL default '0',
  `params` text NOT NULL,
  `enabled` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


-- ----------------------------
-- Table structure for `joys_city`
-- ----------------------------
DROP TABLE IF EXISTS `joys_city`;
CREATE TABLE `joys_city` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `joys_cnote`
-- ----------------------------
DROP TABLE IF EXISTS `joys_cnote`;
CREATE TABLE `joys_cnote` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `joys_store`
-- ----------------------------
DROP TABLE IF EXISTS `joys_store`;
CREATE TABLE `joys_store` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `access` tinyint(3) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `tel` varchar(50) NOT NULL,
  `bus` varchar(255) DEFAULT NULL,
  `cityid` int(10) DEFAULT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
CREATE TABLE `joys_yuyue_date` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `yuyue_date` date NOT NULL,
  `yuyue_timestamp` int(10) NOT NULL,
  `place_id` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_yy_t` (`yuyue_timestamp`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

CREATE TABLE `joys_yuyue_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(20) CHARACTER SET latin1 NOT NULL,
  `goods_id` int(10) unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `place_id` int(5) unsigned NOT NULL,
  `tp_id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `gender` enum('0','1') CHARACTER SET latin1 DEFAULT '0',
  `tel` varchar(11) CHARACTER SET latin1 NOT NULL,
  `email` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `note` text,
  `createtime` int(10) unsigned DEFAULT NULL,
  `status` enum('1','-1','-2','0') DEFAULT '0',
  `open_id` varchar(32) NOT NULL,
  `payment_id` varchar(32) NOT NULL,
  `return_fee` decimal(10,2) NOT NULL,
  `state` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ind_order_id` (`order_id`),
  KEY `ind_name` (`name`),
  KEY `ind_tel` (`tel`),
  KEY `ind_open_id` (`open_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
CREATE TABLE `joys_yuyue_time` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_id` int(10) NOT NULL,
  `time_point` time NOT NULL,
  `time_store` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '当前时间点库存量',
  PRIMARY KEY (`id`),
  KEY `index_date_id` (`date_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-----------------------------------------------------------

CREATE TABLE `joys_anote` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `tip1` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `tip2` varchar(255) NOT NULL,
  `tip3` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
















