-- 
-- 导出表中的数据 `joys_role`
-- 

INSERT INTO `joys_role` VALUES (1, '超级管理员', 0, 1, '超级管理员分组');
INSERT INTO `joys_role` VALUES (2, '普通管理员', 0, 1, '普通管理员分组');

-- 
-- 导出表中的数据 `joys_user`
-- 

INSERT INTO `joys_user` VALUES (1, '~`~ADMINNAME~`~', '~`~ADMINPWD~`~', 'administrator', 'admin@admin.com', '2010-03-01 09:23:44', '2010-03-23 21:39:15', 1, '');

-- 
-- 导出表中的数据 `joys_role_user`
-- 

INSERT INTO `joys_role_user` VALUES (1, 1);

-- ----------------------------
-- Records of joys_node
-- ----------------------------
INSERT INTO `joys_node` VALUES ('1', 'Admin', '后台管理', '1', '后台项目', '0', '0', '1');
INSERT INTO `joys_node` VALUES ('2', 'Section', '单元管理', '1', '控制器', '1', '1', '2');
INSERT INTO `joys_node` VALUES ('4', 'Article', '产品管理', '1', '控制器', '3', '1', '2');
INSERT INTO `joys_node` VALUES ('5', 'Index', '后台默认', '1', '控制器', '0', '1', '2');
INSERT INTO `joys_node` VALUES ('6', 'Public', '公共管理', '1', '控制器', '0', '1', '2');
INSERT INTO `joys_node` VALUES ('7', 'index', '单元列表', '1', '动作', '0', '2', '3');
INSERT INTO `joys_node` VALUES ('8', 'add', '添加单元', '1', '动作', '9', '2', '3');
INSERT INTO `joys_node` VALUES ('9', 'edit', '编辑单元', '1', '动作', '0', '2', '3');
INSERT INTO `joys_node` VALUES ('10', 'delete', '删除单元', '1', '动作', '0', '2', '3');
INSERT INTO `joys_node` VALUES ('11', 'index', '默认动作', '1', '动作', '0', '5', '3');
INSERT INTO `joys_node` VALUES ('12', 'index', '分类列表', '1', '动作', '0', '3', '3');
INSERT INTO `joys_node` VALUES ('13', 'User', '用户管理', '1', '控制器', '0', '1', '2');
INSERT INTO `joys_node` VALUES ('14', 'index', '用户列表', '1', '动作', '0', '13', '3');
INSERT INTO `joys_node` VALUES ('15', 'add', '添加用户', '1', '动作', '0', '13', '3');
INSERT INTO `joys_node` VALUES ('16', 'edit', '编辑用户', '1', '动作', '0', '13', '3');
INSERT INTO `joys_node` VALUES ('17', 'delete', '删除用户', '1', '动作', '0', '13', '3');
INSERT INTO `joys_node` VALUES ('18', 'Role', '用户分组管理', '1', '控制器', '0', '1', '2');
INSERT INTO `joys_node` VALUES ('19', 'index', '用户分组列表', '1', '动作', '0', '18', '3');
INSERT INTO `joys_node` VALUES ('20', 'add', '用户分组添加', '1', '动作', '0', '18', '3');
INSERT INTO `joys_node` VALUES ('21', 'edit', '用户分组编辑', '1', '动作', '0', '18', '3');
INSERT INTO `joys_node` VALUES ('22', 'delete', '用户分组删除', '1', '动作', '0', '18', '3');
INSERT INTO `joys_node` VALUES ('23', 'add', '添加分类', '1', '动作', '0', '3', '3');
INSERT INTO `joys_node` VALUES ('49', 'Datetime', '时间管理', '1', '控制器', '0', '1', '2');
INSERT INTO `joys_node` VALUES ('25', 'delete', '删除分类', '1', '动作', '0', '3', '3');
INSERT INTO `joys_node` VALUES ('26', 'index', '产品列表', '1', '动作', '0', '4', '3');
INSERT INTO `joys_node` VALUES ('27', 'add', '添加产品', '1', '动作', '0', '4', '3');
INSERT INTO `joys_node` VALUES ('28', 'edit', '编辑产品', '1', '动作', '0', '4', '3');
INSERT INTO `joys_node` VALUES ('29', 'delete', '删除产品', '1', '动作', '0', '4', '3');
INSERT INTO `joys_node` VALUES ('59', 'add', '增加城市', '1', '动作', '0', '47', '3');
INSERT INTO `joys_node` VALUES ('60', 'edit', '编辑城市', '1', '动作', '0', '47', '3');
INSERT INTO `joys_node` VALUES ('61', 'delete', '删除城市', '1', '动作', '0', '47', '3');
INSERT INTO `joys_node` VALUES ('62', 'Store', '门店列表', '1', '控制器', '0', '1', '2');
INSERT INTO `joys_node` VALUES ('58', 'setting', '日期时间设置', '1', '动作', '0', '49', '3');
INSERT INTO `joys_node` VALUES ('50', 'Order', '订单管理', '1', '控制器', '0', '1', '2');
INSERT INTO `joys_node` VALUES ('51', 'index', '订单列表', '1', '动作', '0', '50', '3');
INSERT INTO `joys_node` VALUES ('52', 'explore', '导出订单', '1', '动作', '0', '50', '3');
INSERT INTO `joys_node` VALUES ('53', 'edit', '编辑订单', '1', '动作', '0', '50', '3');
INSERT INTO `joys_node` VALUES ('54', 'delete', '删除订单', '1', '动作', '0', '50', '3');
INSERT INTO `joys_node` VALUES ('55', 'date', '日期管理', '1', '动作', '0', '49', '3');
INSERT INTO `joys_node` VALUES ('56', 'edit', '编辑日期', '1', '动作', '0', '49', '3');
INSERT INTO `joys_node` VALUES ('57', 'delete', '删除日期', '1', '动作', '0', '49', '3');
INSERT INTO `joys_node` VALUES ('47', 'City', '城市管理', '1', '控制器', '0', '1', '2');
INSERT INTO `joys_node` VALUES ('48', 'index', '城市列表', '1', '动作', '0', '47', '3');
INSERT INTO `joys_node` VALUES ('63', 'add', '增加门店', '1', '动作', '0', '62', '3');
INSERT INTO `joys_node` VALUES ('64', 'edit', '编辑门店', '1', '动作', '0', '62', '3');
INSERT INTO `joys_node` VALUES ('65', 'delete', '删除门店', '1', '动作', '0', '62', '3');
INSERT INTO `joys_node` VALUES ('66', 'index', '门店列表', '1', '动作', '0', '62', '3');
INSERT INTO `joys_node` VALUES ('67', 'Anote', '首页配置', '1', '控制器', '0', '1', '2');
INSERT INTO `joys_node` VALUES ('68', 'index', '首页配置列表', '1', '动作', '0', '67', '3');
INSERT INTO `joys_node` VALUES ('69', 'edit', '编辑首页配置', '1', '动作', '0', '67', '3');
INSERT INTO `joys_node` VALUES ('70', 'Cnote', '说明配置', '1', '控制器', '0', '1', '2');
INSERT INTO `joys_node` VALUES ('71', 'index', '说明列表', '1', '动作', '0', '70', '3');
INSERT INTO `joys_node` VALUES ('72', 'edit', '编辑说明', '1', '动作', '0', '70', '3');
INSERT INTO `joys_node` VALUES ('73', 'delete', '删除说明', '1', '动作', '0', '70', '3');
INSERT INTO `joys_node` VALUES ('74', 'add', '增加说明', '1', '动作', '0', '70', '3');
INSERT INTO `joys_node` VALUES ('75', 'add', '增加日期', '1', '动作', '0', '49', '3');



-- 
-- 导出表中的数据 `joys_access`
-- 

-- ----------------------------
-- Records of joys_access
-- ----------------------------
INSERT INTO `joys_access` VALUES ('1', '1', '1', '0');
INSERT INTO `joys_access` VALUES ('1', '2', '2', '1');
INSERT INTO `joys_access` VALUES ('1', '9', '3', '2');
INSERT INTO `joys_access` VALUES ('1', '7', '3', '2');
INSERT INTO `joys_access` VALUES ('2', '7', '3', '2');
INSERT INTO `joys_access` VALUES ('1', '5', '2', '1');
INSERT INTO `joys_access` VALUES ('1', '11', '3', '5');
INSERT INTO `joys_access` VALUES ('2', '1', '1', '0');
INSERT INTO `joys_access` VALUES ('1', '8', '3', '2');
INSERT INTO `joys_access` VALUES ('2', '2', '2', '1');
INSERT INTO `joys_access` VALUES ('2', '3', '2', '1');
INSERT INTO `joys_access` VALUES ('2', '4', '2', '1');
INSERT INTO `joys_access` VALUES ('2', '5', '2', '1');
INSERT INTO `joys_access` VALUES ('2', '6', '2', '1');
INSERT INTO `joys_access` VALUES ('2', '62', '2', '1');
INSERT INTO `joys_access` VALUES ('2', '47', '2', '1');
INSERT INTO `joys_access` VALUES ('2', '30', '2', '1');
INSERT INTO `joys_access` VALUES ('2', '35', '2', '1');
INSERT INTO `joys_access` VALUES ('2', '50', '2', '1');
INSERT INTO `joys_access` VALUES ('2', '8', '3', '2');
INSERT INTO `joys_access` VALUES ('2', '9', '3', '2');
INSERT INTO `joys_access` VALUES ('2', '10', '3', '2');
INSERT INTO `joys_access` VALUES ('2', '11', '3', '5');
INSERT INTO `joys_access` VALUES ('2', '12', '3', '3');
INSERT INTO `joys_access` VALUES ('2', '23', '3', '3');
INSERT INTO `joys_access` VALUES ('2', '24', '3', '3');
INSERT INTO `joys_access` VALUES ('2', '25', '3', '3');
INSERT INTO `joys_access` VALUES ('2', '26', '3', '4');
INSERT INTO `joys_access` VALUES ('2', '27', '3', '4');
INSERT INTO `joys_access` VALUES ('2', '28', '3', '4');
INSERT INTO `joys_access` VALUES ('2', '29', '3', '4');
INSERT INTO `joys_access` VALUES ('2', '14', '3', '13');
INSERT INTO `joys_access` VALUES ('2', '15', '3', '13');
INSERT INTO `joys_access` VALUES ('2', '16', '3', '13');
INSERT INTO `joys_access` VALUES ('2', '17', '3', '13');
INSERT INTO `joys_access` VALUES ('2', '19', '3', '18');
INSERT INTO `joys_access` VALUES ('2', '20', '3', '18');
INSERT INTO `joys_access` VALUES ('2', '21', '3', '18');
INSERT INTO `joys_access` VALUES ('2', '22', '3', '18');
INSERT INTO `joys_access` VALUES ('2', '31', '3', '30');
INSERT INTO `joys_access` VALUES ('2', '32', '3', '30');
INSERT INTO `joys_access` VALUES ('2', '33', '3', '30');
INSERT INTO `joys_access` VALUES ('2', '34', '3', '30');
INSERT INTO `joys_access` VALUES ('2', '41', '3', '40');
INSERT INTO `joys_access` VALUES ('2', '42', '3', '40');
INSERT INTO `joys_access` VALUES ('2', '43', '3', '40');
INSERT INTO `joys_access` VALUES ('2', '44', '3', '40');
INSERT INTO `joys_access` VALUES ('2', '45', '3', '40');
INSERT INTO `joys_access` VALUES ('2', '36', '3', '35');
INSERT INTO `joys_access` VALUES ('2', '37', '3', '35');
INSERT INTO `joys_access` VALUES ('2', '39', '3', '35');
INSERT INTO `joys_access` VALUES ('2', '46', '3', '35');
INSERT INTO `joys_access` VALUES ('2', '51', '3', '50');
INSERT INTO `joys_access` VALUES ('2', '52', '3', '50');
INSERT INTO `joys_access` VALUES ('2', '53', '3', '50');
INSERT INTO `joys_access` VALUES ('2', '54', '3', '50');
INSERT INTO `joys_access` VALUES ('2', '49', '2', '1');
INSERT INTO `joys_access` VALUES ('2', '55', '3', '49');
INSERT INTO `joys_access` VALUES ('2', '56', '3', '49');
INSERT INTO `joys_access` VALUES ('2', '57', '3', '49');
INSERT INTO `joys_access` VALUES ('2', '58', '3', '49');
INSERT INTO `joys_access` VALUES ('1', '4', '2', '1');
INSERT INTO `joys_access` VALUES ('1', '6', '2', '1');
INSERT INTO `joys_access` VALUES ('1', '13', '2', '1');
INSERT INTO `joys_access` VALUES ('1', '18', '2', '1');
INSERT INTO `joys_access` VALUES ('1', '49', '2', '1');
INSERT INTO `joys_access` VALUES ('1', '62', '2', '1');
INSERT INTO `joys_access` VALUES ('1', '50', '2', '1');
INSERT INTO `joys_access` VALUES ('1', '47', '2', '1');
INSERT INTO `joys_access` VALUES ('1', '67', '2', '1');
INSERT INTO `joys_access` VALUES ('1', '70', '2', '1');
INSERT INTO `joys_access` VALUES ('1', '10', '3', '2');
INSERT INTO `joys_access` VALUES ('1', '26', '3', '4');
INSERT INTO `joys_access` VALUES ('1', '27', '3', '4');
INSERT INTO `joys_access` VALUES ('1', '28', '3', '4');
INSERT INTO `joys_access` VALUES ('1', '29', '3', '4');
INSERT INTO `joys_access` VALUES ('1', '14', '3', '13');
INSERT INTO `joys_access` VALUES ('1', '15', '3', '13');
INSERT INTO `joys_access` VALUES ('1', '16', '3', '13');
INSERT INTO `joys_access` VALUES ('1', '17', '3', '13');
INSERT INTO `joys_access` VALUES ('1', '19', '3', '18');
INSERT INTO `joys_access` VALUES ('1', '20', '3', '18');
INSERT INTO `joys_access` VALUES ('1', '21', '3', '18');
INSERT INTO `joys_access` VALUES ('1', '22', '3', '18');
INSERT INTO `joys_access` VALUES ('1', '55', '3', '49');
INSERT INTO `joys_access` VALUES ('1', '56', '3', '49');
INSERT INTO `joys_access` VALUES ('1', '57', '3', '49');
INSERT INTO `joys_access` VALUES ('1', '58', '3', '49');
INSERT INTO `joys_access` VALUES ('1', '75', '3', '49');
INSERT INTO `joys_access` VALUES ('1', '63', '3', '62');
INSERT INTO `joys_access` VALUES ('1', '64', '3', '62');
INSERT INTO `joys_access` VALUES ('1', '65', '3', '62');
INSERT INTO `joys_access` VALUES ('1', '66', '3', '62');
INSERT INTO `joys_access` VALUES ('1', '51', '3', '50');
INSERT INTO `joys_access` VALUES ('1', '52', '3', '50');
INSERT INTO `joys_access` VALUES ('1', '53', '3', '50');
INSERT INTO `joys_access` VALUES ('1', '54', '3', '50');
INSERT INTO `joys_access` VALUES ('1', '48', '3', '47');
INSERT INTO `joys_access` VALUES ('1', '59', '3', '47');
INSERT INTO `joys_access` VALUES ('1', '60', '3', '47');
INSERT INTO `joys_access` VALUES ('1', '61', '3', '47');
INSERT INTO `joys_access` VALUES ('1', '68', '3', '67');
INSERT INTO `joys_access` VALUES ('1', '69', '3', '67');
INSERT INTO `joys_access` VALUES ('1', '71', '3', '70');
INSERT INTO `joys_access` VALUES ('1', '72', '3', '70');
INSERT INTO `joys_access` VALUES ('1', '73', '3', '70');
INSERT INTO `joys_access` VALUES ('1', '74', '3', '70');
INSERT INTO `joys_access` VALUES ('2', '67', '2', '1');
INSERT INTO `joys_access` VALUES ('2', '70', '2', '1');
INSERT INTO `joys_access` VALUES ('2', '75', '3', '49');
INSERT INTO `joys_access` VALUES ('2', '63', '3', '62');
INSERT INTO `joys_access` VALUES ('2', '64', '3', '62');
INSERT INTO `joys_access` VALUES ('2', '65', '3', '62');
INSERT INTO `joys_access` VALUES ('2', '66', '3', '62');
INSERT INTO `joys_access` VALUES ('2', '48', '3', '47');
INSERT INTO `joys_access` VALUES ('2', '59', '3', '47');
INSERT INTO `joys_access` VALUES ('2', '60', '3', '47');
INSERT INTO `joys_access` VALUES ('2', '61', '3', '47');
INSERT INTO `joys_access` VALUES ('2', '68', '3', '67');
INSERT INTO `joys_access` VALUES ('2', '69', '3', '67');
INSERT INTO `joys_access` VALUES ('2', '71', '3', '70');
INSERT INTO `joys_access` VALUES ('2', '72', '3', '70');
INSERT INTO `joys_access` VALUES ('2', '73', '3', '70');
INSERT INTO `joys_access` VALUES ('2', '74', '3', '70');



-- 
-- 导出表中的数据 `joys_section`
-- 



-- 
-- 导出表中的数据 `joys_category`
-- 



-- 
-- 导出表中的数据 `joys_article`
-- 


-- 
-- 导出表中的数据 `joys_component`
-- 

INSERT INTO `joys_component` VALUES (1, '文章内容', 'Article', 'view', 0, '', 1);
INSERT INTO `joys_component` VALUES (2, '单元内容', 'Section', 'view', 0, '', 1);






