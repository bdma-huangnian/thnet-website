# ************************************************************
# CoreThink SQL dump
# Version 1.0Beta
# Host: 127.0.0.1 (MySQL 5.6.21-enterprise-commercial-advanced)
# Database: corethink
# Generation Time: 2015-01-18 07:04:06 +0000
# ************************************************************



# Dump of table core_action
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_action`;

CREATE TABLE `ct_action` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '行为说明',
  `remark` char(140) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text COMMENT '行为规则',
  `log` text COMMENT '日志规则',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统行为表';

LOCK TABLES `ct_action` WRITE;
/*!40000 ALTER TABLE `ct_action` DISABLE KEYS */;

INSERT INTO `ct_action` (`id`, `name`, `title`, `remark`, `rule`, `log`, `type`, `status`, `update_time`)
VALUES
    (1,'user_login','用户登录','积分+10，每天一次','table:member|field:score|condition:uid={$self} AND status>-1|rule:score+10|cycle:24|max:1;','[user]在[time|time_format]登录了系统',1,1,1419315470),
    (2,'add_document','发布文章','积分+5，每天上限5次','table:member|field:score|condition:uid={$self} AND status>-1|rule:score+5|cycle:24|max:5;','[user]在[time|time_format]发表了一篇文档。\r\n表[model]，记录编号[record]。',1,1,1419316082),
    (3,'submit_comment','发表评论','评论积分+1，每天上限20','table:member|field:score|condition:uid={$self} AND status>-1|rule:score+1|cycle:24|max:20;','[user]在[time|time_format]发表了一条评论。\r\n表[model]，记录编号[record]。',1,1,1419316697);

/*!40000 ALTER TABLE `ct_action` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table core_action_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_action_log`;

CREATE TABLE `ct_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `action_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '行为id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '触发行为的数据id',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '日志备注',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  PRIMARY KEY (`id`),
  KEY `action_ip_ix` (`action_ip`),
  KEY `action_id_ix` (`action_id`),
  KEY `user_id_ix` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';



# Dump of table core_addons
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_addons`;

CREATE TABLE `ct_addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `has_adminlist` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台列表',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件表';

LOCK TABLES `ct_addons` WRITE;
/*!40000 ALTER TABLE `ct_addons` DISABLE KEYS */;

INSERT INTO `ct_addons` (`id`, `name`, `title`, `description`, `status`, `config`, `author`, `version`, `create_time`, `has_adminlist`)
VALUES
    (1,'SiteStat','站点统计信息','统计站点的基础信息',1,'{\"title\":\"\\u7cfb\\u7edf\\u4fe1\\u606f\",\"width\":\"4\",\"display\":\"1\"}','CoreThink','0.1',1407825388,0),
    (2,'DevTeam','开发团队信息','开发团队成员信息',1,'{\"title\":\"\\u5f00\\u53d1\\u56e2\\u961f\\u4fe1\\u606f\",\"width\":\"2\",\"display\":\"1\",\"producer\":\"\\u6c5f\\u5982\\u610f\",\"codeteam\":\"\\u6c5f\\u5982\\u610f\",\"uiteam\":\"\\u6c5f\\u5982\\u610f\",\"website\":\"http:\\/\\/www.corethink.cn\",\"qqgroup\":\"130747567\",\"contact\":\"skipperprivater@gmail.com\"}','CoreThink','0.1',1383126253,0),
    (3,'SystemInfo','系统环境信息','用于显示一些服务器的信息',1,'{\"title\":\"\\u7cfb\\u7edf\\u4fe1\\u606f\",\"width\":\"2\",\"display\":\"1\"}','CoreThink','0.1',1379512036,0),
    (4,'Editor','前台编辑器','用于增强整站长文本的输入和显示',1,'{\"editor_type\":\"2\",\"editor_wysiwyg\":\"1\",\"editor_height\":\"500px\",\"editor_resize_type\":\"1\"}','CoreThink','0.1',1379830910,0),
    (5,'EditorForAdmin','后台编辑器','用于增强整站长文本的输入和显示',1,'{\"editor_type\":\"2\",\"editor_wysiwyg\":\"1\",\"editor_markdownpreview\":\"0\",\"editor_height\":\"500px\",\"editor_resize_type\":\"1\"}','CoreThink','0.1',1383126253,0),
    (6,'SocialComment','通用社交化评论','集成了各种社交化评论插件，轻松集成到系统中。',1,'{\"status\":\"0\",\"comment_type\":\"2\",\"comment_uid_youyan\":\"\",\"comment_short_name_duoshuo\":\"\",\"comment_form_pos_duoshuo\":\"top\",\"comment_data_list_duoshuo\":\"10\",\"comment_data_order_duoshuo\":\"asc\"}','CoreThink','0.1',1380273962,0),
    (7,'QiuBai','糗事百科','读别人的糗事，娱乐自己',1,'{\"title\":\"\\u7cd7\\u4e8b\\u767e\\u79d1\",\"width\":\"2\",\"display\":\"1\",\"cache_time\":\"60\"}','CoreThink','0.1',1411721369,0),
    (8,'Weather','天气预报','天气预报',1,'{\"status\":\"1\",\"title\":\"\\u5929\\u6c14\\u9884\\u62a5\",\"city\":\"\\u5357\\u4eac\",\"showplace\":[\"0\",\"1\"],\"showday\":\"4\",\"ak\":\"51da9f03c9731fa65316f2c93c13cb26\",\"width\":\"2\"}','CoreThink','0.1',1413345937,0),
    (9,'Fancybox','图片弹出播放','让文章内容页的图片有弹出图片播放的效果',1,'{\"group\":\"1\",\"transitionIn\":\"fade\",\"transitionOut\":\"fade\",\"padding\":\"10\",\"hideOnContentClick\":\"false\",\"easingIn\":\"easeOutCubic\"}','CoreThink','0.1',1407681961,0),
    (10, 'ReturnTop', '返回顶部', '返回顶部', 1, '{\"status\":\"1\",\"theme\":\"rocket\",\"customer\":\"\",\"case\":\"\",\"qq\":\"\",\"weibo\":\"\"}', 'corethink', '0.1', 1407681961, 0),
    (11,'UploadImages','多图上传','多图上传',1,'null','CoreThink','0.1',1411705309,0),
    (12,'BaiduShare','百度分享','用户将网站内容分享到第三方网站',1,'{\"openbutton\":\"1\",\"buttonlist\":[\"mshare\",\"qzone\",\"tsina\",\"renren\",\"tieba\",\"weixin\"],\"button_size\":\"24\",\"openslide\":\"0\",\"slide_position\":\"right\",\"slide_color\":\"0\",\"openimg\":\"0\",\"imglist\":[\"mshare\",\"qzone\",\"tsina\",\"renren\",\"tqq\",\"tieba\"],\"openselect\":\"0\",\"selectlist\":[\"mshare\",\"qzone\",\"tsina\",\"renren\",\"tqq\",\"tieba\"]}','CoreThink','0.1',1407666348,0),
    (13,'SyncLogin','第三方账号登陆','第三方账号登陆',1,'{\"type\":[\"Qq\",\"Sina\"],\"meta\":\"\",\"QqKEY\":\"\",\"QqSecret\":\"\",\"SinaKEY\":\"\",\"SinaSecret\":\"\"}','CoreThink','0.1',1408122773,0),
    (14,'AdFloat','图片漂浮广告','图片漂浮广告',1,'{\"status\":\"0\",\"url\":\"http:\\/\\/www.corethink.cn\",\"image\":\"\",\"width\":\"100\",\"height\":\"100\",\"speed\":\"10\",\"target\":\"1\"}','CoreThink','0.1',1408602081,0);

/*!40000 ALTER TABLE `ct_addons` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table core_attachment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_attachment`;

CREATE TABLE `ct_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '附件显示名',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '附件类型',
  `source` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '资源ID',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联记录ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '附件大小',
  `dir` int(12) unsigned NOT NULL DEFAULT '0' COMMENT '上级目录ID',
  `sort` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `idx_record_status` (`record_id`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表';



# Dump of table core_attribute
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_attribute`;

CREATE TABLE `ct_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '字段注释',
  `field` varchar(100) NOT NULL DEFAULT '' COMMENT '字段定义',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '数据类型',
  `value` varchar(100) NOT NULL DEFAULT '' COMMENT '字段默认值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `model_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '模型id',
  `is_must` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `validate_rule` varchar(255) NOT NULL DEFAULT '',
  `validate_time` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `error_info` varchar(100) NOT NULL DEFAULT '',
  `validate_type` varchar(25) NOT NULL DEFAULT '',
  `auto_rule` varchar(100) NOT NULL DEFAULT '',
  `auto_time` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `auto_type` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `model_id` (`model_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模型属性字段表';

LOCK TABLES `ct_attribute` WRITE;
/*!40000 ALTER TABLE `ct_attribute` DISABLE KEYS */;

INSERT INTO `ct_attribute` (`id`, `name`, `title`, `field`, `type`, `value`, `remark`, `is_show`, `extra`, `model_id`, `is_must`, `status`, `update_time`, `create_time`, `validate_rule`, `validate_time`, `error_info`, `validate_type`, `auto_rule`, `auto_time`, `auto_type`)
VALUES
    (1,'uid','用户ID','int(10) unsigned NOT NULL ','num','0','',0,'',1,0,1,1384508362,1383891233,'',0,'','','',0,''),
    (2,'model_id','内容模型ID','tinyint(1) unsigned NOT NULL ','num','0','该文档所对应的模型',0,'',1,0,1,1384508350,1383891233,'',0,'','','',0,''),
    (3,'category_id','所属分类','int(10) unsigned NOT NULL ','num','','',0,'',1,0,1,1384508336,1383891233,'',0,'','','',0,''),
    (4,'group_id','所属分组','SMALLINT(3) unsigned NOT NULL ','num','','',0,'',1,0,1,1384508336,1383891233,'',0,'','','',0,''),
    (5,'root','根节点','int(10) unsigned NOT NULL ','num','0','该文档的顶级文档编号',0,'',1,0,1,1384508323,1383891233,'',0,'','','',0,''),
    (6,'pid','所属ID','int(10) unsigned NOT NULL ','num','0','父文档编号',0,'',1,0,1,1384508543,1383891233,'',0,'','','',0,''),
    (7,'name','标识','char(40) NOT NULL ','string','','同一根节点下标识不重复',1,'',1,0,1,1383894743,1383891233,'',0,'','','',0,''),
    (8,'title','标题','char(80) NOT NULL ','string','','文档标题',1,'',1,0,1,1383894778,1383891233,'',0,'','','',0,''),
    (9,'color','标题颜色','char(7) NOT NULL','color','','标题颜色，默认为空',1,'',1,0,1,1413303715,1413303715,'',0,'','','',0,''),
    (10,'tags','标签','varchar(255) NOT NULL','tag','','标签',1,'',1,0,1,1413303715,1413303715,'',0,'','','',0,''),
    (11,'description','描述','char(255) NOT NULL ','textarea','','如不填写系统将自动截取正文',1,'',1,0,1,1383894927,1383891233,'',0,'','','',0,''),
    (12,'cover_id','封面','int(10) unsigned NOT NULL ','picture','0','0-无封面，大于0-封面图片ID，需要函数处理',1,'',1,0,1,1384147827,1383891233,'',0,'','','',0,''),
    (13,'pictures','多图上传','char(10) NOT NULL','pictures','','多团上传，可用于相册、商品展示等',1,'',1,0,1,1407646362,1407646362,'',0,'','','',0,''),
    (14,'position','推荐位','smallint(5) unsigned NOT NULL ','checkbox','0','多个推荐则将其推荐值相加',1,'[DOCUMENT_POSITION]',1,0,1,1383895640,1383891233,'',0,'','','',0,''),
    (15,'create_time','创建时间','int(11) unsigned NOT NULL ','datetime','0','',1,'',1,0,1,1383895903,1383891233,'',0,'','','',0,''),
    (16,'update_time','更新时间','int(11) unsigned NOT NULL ','datetime','0','',0,'',1,0,1,1384508277,1383891233,'',0,'','','',0,''),
    (17,'link_id','外链','int(10) unsigned NOT NULL ','url','0','0-非外链，大于0-外链ID,需要函数进行链接与编号的转换',1,'',1,0,1,1383895757,1383891233,'',0,'','','',0,''),
    (18,'type','内容类型','tinyint(1) unsigned NOT NULL ','select','2','',1,'1:目录\r\n2:主题\r\n3:段落',1,0,1,1384511157,1383891233,'',0,'','','',0,''),
    (19,'display','前台可见性','tinyint(1) unsigned NOT NULL ','radio','1','',1,'0:前台不可见\r\n1:前台可见',1,0,1,1386662271,1383891233,'',0,'','','',0,''),
    (20,'view','浏览量','int(10) unsigned NOT NULL ','num','0','',1,'',1,0,1,1383895835,1383891233,'',0,'','','',0,''),
    (21,'comment','评论数','int(10) unsigned NOT NULL ','num','0','',1,'',1,0,1,1383895846,1383891233,'',0,'','','',0,''),
    (22,'bookmark','收藏数','int(10) unsigned NOT NULL ','num','0','',1,'',1,0,1,1383896103,1383891243,'',0,'','','',0,''),
    (23,'good','赞数','int(10) unsigned NOT NULL ','num','0','',1,'',1,0,1,1383895846,1383891233,'',0,'','','',0,''),
    (24,'bad','踩数','int(10) unsigned NOT NULL ','num','0','',1,'',1,0,1,1383895846,1383891233,'',0,'','','',0,''),
    (25,'deadline','截至时间','int(10) unsigned NOT NULL ','datetime','0','0-永久有效',1,'',1,0,1,1387163248,1383891233,'',0,'','','',0,''),
    (26,'level','优先级','int(10) unsigned NOT NULL ','num','0','越高排序越靠前',1,'',1,0,1,1383895894,1383891233,'',0,'','','',0,''),
    (27,'status','数据状态','tinyint(1) NOT NULL ','radio','0','',0,'-1:删除\r\n0:禁用\r\n1:正常\r\n2:待审核\r\n3:草稿',1,0,1,1384508496,1383891233,'',0,'','','',0,''),
    (28,'attach','附件数量','tinyint(1) unsigned NOT NULL ','num','0','',0,'',1,0,1,1387260355,1383891233,'',0,'','','',0,''),
    (29,'parse','内容解析类型','tinyint(1) unsigned NOT NULL ','select','0','',0,'0:html\r\n1:ubb\r\n2:markdown',2,0,1,1384511049,1383891243,'',0,'','','',0,''),
    (30,'content','详细内容','text NOT NULL ','editor','','',1,'',2,0,1,1383896225,1383891243,'',0,'','','',0,''),
    (31,'template','详情页显示模板','varchar(100) NOT NULL ','string','','参照display方法参数的定义',1,'',2,0,1,1383896190,1383891243,'',0,'','','',0,''),
    (32,'parse','内容解析类型','tinyint(1) unsigned NOT NULL ','select','0','',0,'0:html\r\n1:ubb\r\n2:markdown',3,0,1,1387260461,1383891252,'',0,'','','',0,''),
    (33,'content','下载详细描述','text NOT NULL ','editor','','',1,'',3,0,1,1383896438,1383891252,'',0,'','','',0,''),
    (34,'template','详情页显示模板','varchar(100) NOT NULL ','string','','',1,'',3,0,1,1383896429,1383891252,'',0,'','','',0,''),
    (35,'file_id','文件ID','int(10) unsigned NOT NULL ','file','0','需要函数处理',1,'',3,0,1,1383896415,1383891252,'',0,'','','',0,''),
    (36,'download','下载次数','int(10) unsigned NOT NULL ','num','0','',1,'',3,0,1,1383896380,1383891252,'',0,'','','',0,''),
    (37,'size','文件大小','bigint(20) unsigned NOT NULL ','num','0','单位bit',1,'',3,0,1,1383896371,1383891252,'',0,'','','',0,'');

/*!40000 ALTER TABLE `ct_attribute` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table core_auth_extend
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_auth_extend`;

CREATE TABLE `ct_auth_extend` (
  `group_id` mediumint(10) unsigned NOT NULL COMMENT '用户id',
  `extend_id` mediumint(8) unsigned NOT NULL COMMENT '扩展表中数据的id',
  `type` tinyint(1) unsigned NOT NULL COMMENT '扩展类型标识 1:栏目分类权限;2:模型权限',
  UNIQUE KEY `group_extend_type` (`group_id`,`extend_id`,`type`),
  KEY `uid` (`group_id`),
  KEY `group_id` (`extend_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组与分类的对应关系表';


# Dump of table core_auth_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_auth_group`;

CREATE TABLE `ct_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组所属模块',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组表';


# Dump of table core_auth_group_access
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_auth_group_access`;

CREATE TABLE `ct_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户所属的用户组列表';



# Dump of table core_auth_rule
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_auth_rule`;

CREATE TABLE `ct_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限规则表：admin模块对应后台菜单';

LOCK TABLES `ct_auth_rule` WRITE;
/*!40000 ALTER TABLE `ct_auth_rule` DISABLE KEYS */;

INSERT INTO `ct_auth_rule` (`id`, `module`, `type`, `name`, `title`, `status`, `condition`)
VALUES
    (1, 'admin', 2, 'Admin/Index/index', '首页', 1, ''),
    (2, 'admin', 2, 'Admin/Config/group', '系统', 1, ''),
    (3, 'admin', 2, 'Admin/Article/index', '内容', 1, ''),
    (4, 'admin', 2, 'Admin/User/index', '用户', 1, ''),
    (5, 'admin', 2, 'Admin/Addons/index', '扩展', 1, ''),
    (6, 'admin', 2, 'Admin/Other', '其他', 1, ''),
    (7, 'admin', 1, 'Admin/Config/group', '网站设置', 1, ''),
    (8, 'admin', 1, 'Admin/Config/index', '配置管理', 1, ''),
    (9, 'admin', 1, 'Admin/Config/add', '新增', 1, ''),
    (10, 'admin', 1, 'Admin/Config/edit', '编辑', 1, ''),
    (11, 'admin', 1, 'Admin/Config/del', '删除', 1, ''),
    (12, 'admin', 1, 'Admin/Config/save', '保存', 1, ''),
    (13, 'admin', 1, 'Admin/Config/sort', '排序', 1, ''),
    (14, 'admin', 1, 'Admin/Category/index', '分类管理', 1, ''),
    (15, 'admin', 1, 'Admin/Category/add', '新增', 1, ''),
    (16, 'admin', 1, 'Admin/Category/edit', '编辑', 1, ''),
    (17, 'admin', 1, 'Admin/Category/del', '删除', 1, ''),
    (18, 'admin', 1, 'Admin/Category/setStatus', '改变状态', 1, ''),
    (19, 'admin', 1, 'Admin/Category/operate/type/move', '移动', 1, ''),
    (20, 'admin', 1, 'Admin/Category/operate/type/merge', '合并', 1, ''),
    (21, 'admin', 1, 'Admin/Channel/index', '导航管理', 1, ''),
    (22, 'admin', 1, 'Admin/Channel/add', '新增', 1, ''),
    (23, 'admin', 1, 'Admin/Channel/edit', '编辑', 1, ''),
    (24, 'admin', 1, 'Admin/Channel/del', '删除', 1, ''),
    (25, 'admin', 1, 'Admin/Channel/setStatus', '改变状态', 1, ''),
    (26, 'admin', 1, 'Admin/Channel/sort', '排序', 1, ''),
    (27, 'admin', 1, 'Admin/Model/index', '模型管理', 1, ''),
    (28, 'admin', 1, 'Admin/Model/add', '新增', 1, ''),
    (29, 'admin', 1, 'Admin/Model/edit', '编辑', 1, ''),
    (30, 'admin', 1, 'Admin/Model/del', '删除', 1, ''),
    (31, 'admin', 1, 'Admin/Model/update', '保存', 1, ''),
    (32, 'admin', 1, 'Admin/Model/setStatus', '改变状态', 1, ''),
    (33, 'admin', 1, 'Admin/Model/generate', '生成', 1, ''),
    (34, 'admin', 1, 'Admin/Menu/index', '菜单管理', 1, ''),
    (35, 'admin', 1, 'Admin/Menu/add', '新增', 1, ''),
    (36, 'admin', 1, 'Admin/Menu/edit', '编辑', 1, ''),
    (37, 'admin', 1, 'Admin/Menu/del', '删除', 1, ''),
    (38, 'admin', 1, 'Admin/Menu/sort', '排序', 1, ''),
    (39, 'admin', 1, 'Admin/Menu/import', '导入', 1, ''),
    (40, 'admin', 1, 'Admin/Database/index?type=export', '备份数据库', 1, ''),
    (41, 'admin', 1, 'Admin/Database/export', '备份', 1, ''),
    (42, 'admin', 1, 'Admin/Database/optimize', '优化表', 1, ''),
    (43, 'admin', 1, 'Admin/Database/repair', '修复表', 1, ''),
    (44, 'admin', 1, 'Admin/Database/index?type=import', '还原数据库', 1, ''),
    (45, 'admin', 1, 'Admin/Database/import', '恢复', 1, ''),
    (46, 'admin', 1, 'Admin/Database/del', '删除', 1, ''),
    (47, 'admin', 1, 'Admin/Attribute/index', '属性管理', 1, ''),
    (48, 'admin', 1, 'Admin/Attribute/add', '新增', 1, ''),
    (49, 'admin', 1, 'Admin/Attribute/edit', '编辑', 1, ''),
    (50, 'admin', 1, 'Admin/Attribute/del', '删除', 1, ''),
    (51, 'admin', 1, 'Admin/Attribute/update', '保存', 1, ''),
    (52, 'admin', 1, 'Admin/Attribute/setStatus', '改变状态', 1, ''),
    (53, 'admin', 1, 'Admin/Think/lists', '数据列表', 1, ''),
    (54, 'admin', 1, 'Admin/Think/add', '新增', 1, ''),
    (55, 'admin', 1, 'Admin/Think/edit', '编辑', 1, ''),
    (56, 'admin', 1, 'Admin/Think/setStatus', '改变状态', 1, ''),
    (57, 'admin', 1, 'Admin/Article/index', '所有文档', 1, ''),
    (58, 'admin', 1, 'Admin/Article/mydocument', '我的文档', 1, ''),
    (59, 'admin', 1, 'Admin/Article/add', '新增', 1, ''),
    (60, 'admin', 1, 'Admin/Article/edit', '编辑', 1, ''),
    (61, 'admin', 1, 'Admin/Article/update', '保存', 1, ''),
    (62, 'admin', 1, 'Admin/Article/setStatus', '改变状态', 1, ''),
    (63, 'admin', 1, 'Admin/Article/move', '移动', 1, ''),
    (64, 'admin', 1, 'Admin/Article/copy', '复制', 1, ''),
    (65, 'admin', 1, 'Admin/Article/paste', '粘贴', 1, ''),
    (66, 'admin', 1, 'Admin/Article/sort', '文档排序', 1, ''),
    (67, 'admin', 1, 'Admin/Article/autoSave', '保存草稿', 1, ''),
    (68, 'admin', 1, 'Admin/Article/examine', '待审核', 1, ''),
    (69, 'admin', 1, 'Admin/Article/draftbox', '草稿箱', 1, ''),
    (70, 'admin', 1, 'Admin/Article/recycle', '回收站', 1, ''),
    (71, 'admin', 1, 'Admin/Article/permit', '还原', 1, ''),
    (72, 'admin', 1, 'Admin/Article/clear', '清空', 1, ''),
    (73, 'admin', 1, 'Admin/Tweet/index', '微博列表', 1, ''),
    (74, 'admin', 1, 'Admin/Tweet/add', '新增', 1, ''),
    (75, 'admin', 1, 'Admin/Tweet/edit', '编辑', 1, ''),
    (76, 'admin', 1, 'Admin/Tweet/save', '保存', 1, ''),
    (77, 'admin', 1, 'Admin/Tweet/changeStatus', '改变状态', 1, ''),
    (78, 'admin', 1, 'Admin/Comment/index', '评论列表', 1, ''),
    (79, 'admin', 1, 'Admin/Comment/add', '新增', 1, ''),
    (80, 'admin', 1, 'Admin/Comment/edit', '编辑', 1, ''),
    (81, 'admin', 1, 'Admin/Comment/save', '保存', 1, ''),
    (82, 'admin', 1, 'Admin/Comment/changeStatus', '改变状态', 1, ''),
    (83, 'admin', 1, 'Admin/User/index', '用户列表', 1, ''),
    (84, 'admin', 1, 'Admin/User/add', '新增', 1, ''),
    (85, 'admin', 1, 'Admin/User/edit', '编辑', 1, ''),
    (86, 'admin', 1, 'Admin/User/changeStatus', '改变状态', 1, ''),
    (87, 'admin', 1, 'Admin/AuthManager/group', '授权', 1, ''),
    (88, 'admin', 1, 'Admin/User/updateNickname', '修改昵称', 1, ''),
    (89, 'admin', 1, 'Admin/User/updatePassword', '修改密码', 1, ''),
    (90, 'admin', 1, 'Admin/AuthManager/index', '权限管理', 1, ''),
    (91, 'admin', 1, 'Admin/AuthManager/createGroup', '新增', 1, ''),
    (92, 'admin', 1, 'Admin/AuthManager/editGroup', '编辑', 1, ''),
    (93, 'admin', 1, 'Admin/AuthManager/writeGroup', '保存', 1, ''),
    (94, 'admin', 1, 'Admin/AuthManager/changeStatus', '改变状态', 1, ''),
    (95, 'admin', 1, 'Admin/AuthManager/access', '访问授权', 1, ''),
    (96, 'admin', 1, 'Admin/AuthManager/category', '分类授权', 1, ''),
    (97, 'admin', 1, 'Admin/AuthManager/addToCategory', '保存分类授权', 1, ''),
    (98, 'admin', 1, 'Admin/AuthManager/user', '成员授权', 1, ''),
    (99, 'admin', 1, 'Admin/AuthManager/addToGroup', '保存成员授权', 1, ''),
    (100, 'admin', 1, 'Admin/AuthManager/removeFromGroup', '解除授权', 1, ''),
    (101, 'admin', 1, 'Admin/User/action', '用户行为', 1, ''),
    (102, 'admin', 1, 'Admin/User/addaction', '新增', 1, ''),
    (103, 'admin', 1, 'Admin/User/editaction', '编辑', 1, ''),
    (104, 'admin', 1, 'Admin/User/saveAction', '保存', 1, ''),
    (105, 'admin', 1, 'Admin/User/setStatus', '改变状态', 1, ''),
    (106, 'admin', 1, 'Admin/Action/actionlog', '行为日志', 1, ''),
    (107, 'admin', 1, 'Admin/Action/edit', '查看行为日志', 1, ''),
    (108, 'admin', 1, 'Admin/MessageSystem/index', '系统消息', 1, ''),
    (109, 'admin', 1, 'Admin/MessageSystem/add', '新增', 1, ''),
    (110, 'admin', 1, 'Admin/MessageSystem/edit', '编辑', 1, ''),
    (111, 'admin', 1, 'Admin/MessageSystem/save', '保存', 1, ''),
    (112, 'admin', 1, 'Admin/MessageSystem/changeStatus', '改变状态', 1, ''),
    (113, 'admin', 1, 'Admin/Message/index/', '用户消息', 1, ''),
    (114, 'admin', 1, 'Admin/Message/add', '新增', 1, ''),
    (115, 'admin', 1, 'Admin/Message/edit', '编辑', 1, ''),
    (116, 'admin', 1, 'Admin/Message/save', '保存', 1, ''),
    (117, 'admin', 1, 'Admin/Message/changeStatus', '改变状态', 1, ''),
    (118, 'admin', 1, 'Admin/Mail/index', '邮件列表', 1, ''),
    (119, 'admin', 1, 'Admin/Mail/add', '新增', 1, ''),
    (120, 'admin', 1, 'Admin/Mail/edit', '编辑', 1, ''),
    (121, 'admin', 1, 'Admin/Mail/save', '保存', 1, ''),
    (122, 'admin', 1, 'Admin/Mail/changeStatus', '改变状态', 1, ''),
    (123, 'admin', 1, 'Admin/Addons/index', '插件管理', 1, ''),
    (124, 'admin', 1, 'Admin/Addons/create', '创建', 1, ''),
    (125, 'admin', 1, 'Admin/Addons/checkForm', '检测创建', 1, ''),
    (126, 'admin', 1, 'Admin/Addons/preview', '预览', 1, ''),
    (127, 'admin', 1, 'Admin/Addons/build', '快速生成插件', 1, ''),
    (128, 'admin', 1, 'Admin/Addons/config', '设置', 1, ''),
    (129, 'admin', 1, 'Admin/Addons/disable', '禁用', 1, ''),
    (130, 'admin', 1, 'Admin/Addons/enable', '启用', 1, ''),
    (131, 'admin', 1, 'Admin/Addons/install', '安装', 1, ''),
    (132, 'admin', 1, 'Admin/Addons/uninstall', '卸载', 1, ''),
    (133, 'admin', 1, 'Admin/Addons/saveconfig', '更新配置', 1, ''),
    (134, 'admin', 1, 'Admin/Addons/adminList', '插件后台列表', 1, ''),
    (135, 'admin', 1, 'Admin/Addons/execute', 'URL方式访问插件', 1, ''),
    (136, 'admin', 1, 'Admin/Addons/hooks', '钩子管理', 1, ''),
    (137, 'admin', 1, 'Admin/Addons/addHook', '新增', 1, ''),
    (138, 'admin', 1, 'Admin/Addons/edithook', '编辑', 1, ''),
    (139, 'admin', 1, 'Admin/Slider/index', '幻灯列表', 1, ''),
    (140, 'admin', 1, 'Admin/Slider/add', '新增', 1, ''),
    (141, 'admin', 1, 'Admin/Slider/edit', '编辑', 1, ''),
    (142, 'admin', 1, 'Admin/Slider/del', '删除', 1, ''),
    (143, 'admin', 1, 'Admin/Slider/setStatus', '改变状态', 1, ''),
    (144, 'admin', 1, 'Admin/Slider/sort', '排序', 1, ''),
    (145, 'admin', 1, 'Admin/Tags/index', '标签列表', 1, ''),
    (146, 'admin', 1, 'Admin/Tags/add', '新增', 1, ''),
    (147, 'admin', 1, 'Admin/Tags/edit', '编辑', 1, ''),
    (148, 'admin', 1, 'Admin/Tags/del', '删除', 1, ''),
    (149, 'admin', 1, 'Admin/Tags/setStatus', '改变状态', 1, ''),
    (150, 'admin', 1, 'Admin/Tags/sort', '排序', 1, '');

/*!40000 ALTER TABLE `ct_auth_rule` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table core_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_category`;

CREATE TABLE `ct_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(30) NOT NULL COMMENT '标志',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `list_row` tinyint(1) unsigned NOT NULL DEFAULT '10' COMMENT '列表每页行数',
  `meta_title` varchar(50) NOT NULL DEFAULT '' COMMENT 'SEO的网页标题',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `template_index` varchar(100) NOT NULL DEFAULT '' COMMENT '频道页模板',
  `template_lists` varchar(100) NOT NULL DEFAULT '' COMMENT '列表页模板',
  `template_detail` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页模板',
  `template_edit` varchar(100) NOT NULL DEFAULT '' COMMENT '编辑页模板',
  `model` varchar(100) NOT NULL DEFAULT '' COMMENT '列表绑定模型',
  `model_sub` varchar(100) NOT NULL DEFAULT '' COMMENT '子文档绑定模型',
  `type` varchar(100) NOT NULL DEFAULT '' COMMENT '允许发布的内容类型',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链',
  `allow_publish` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许发布内容',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '可见性',
  `reply` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许回复',
  `check` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '发布的文章是否需要审核',
  `reply_model` varchar(100) NOT NULL DEFAULT '',
  `extend` text COMMENT '扩展设置',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '数据状态',
  `icon` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类图标',
  `groups` varchar(255) NOT NULL DEFAULT '' COMMENT '分组定义',
  `fonticon` varchar(255) NOT NULL DEFAULT '' COMMENT '字体图标',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分类表';

LOCK TABLES `ct_category` WRITE;
/*!40000 ALTER TABLE `ct_category` DISABLE KEYS */;

INSERT INTO `ct_category` (`id`, `name`, `title`, `pid`, `sort`, `list_row`, `meta_title`, `keywords`, `description`, `template_index`, `template_lists`, `template_detail`, `template_edit`, `model`, `model_sub`, `type`, `link_id`, `allow_publish`, `display`, `reply`, `check`, `reply_model`, `extend`, `create_time`, `update_time`, `status`, `icon`, `groups`, `fonticon`)
VALUES
    (1,'article','文章',0,2,10,'','','','','','','','2,3','2,3','1,2,3',0,2,1,0,1,'',NULL,1412783679,1419406808,1,0,'','fa fa-paper-plane'),
    (2,'group','群组',0,4,10,'','','','','','','','2,3','2,3','1,2,3',0,2,1,0,1,'',NULL,1412783884,1419406582,1,0,'','fa fa-users'),
    (3,'event','活动',0,6,10,'','','','','','','','2,3','2,3','1,2,3',0,2,1,0,1,'',NULL,1412783978,1415289920,1,0,'','fa fa-cube'),
    (4,'shop','商城',0,8,10,'','','','','','','','2,3','2,3','1,2,3',0,2,1,0,1,'',NULL,1412783918,1415289809,1,0,'','fa fa-shopping-cart'),
    (5,'forum','论坛',0,10,10,'','','','','','','','2,3','2,3','1,2,3',0,2,1,0,1,'',NULL,1412784024,1415290076,1,0,'','fa fa-comment');

/*!40000 ALTER TABLE `ct_category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table core_channel
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_channel`;

CREATE TABLE `ct_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '导航ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级导航ID',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '导航标题',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '导航链接类型',
  `url` char(127) NOT NULL DEFAULT '' COMMENT '导航链接',
  `content` text NOT NULL COMMENT '单页内容',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `fonticon` varchar(127) NOT NULL DEFAULT '' COMMENT '字体图标',
  `target` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '新窗口打开',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '导航排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='导航表';

LOCK TABLES `ct_channel` WRITE;
/*!40000 ALTER TABLE `ct_channel` DISABLE KEYS */;

INSERT INTO `ct_channel` (`id`, `pid`, `title`, `type`, `url`, `content`, `create_time`, `update_time`, `fonticon`, `target`, `sort`, `status`)
VALUES
    (1, 0, '顶部导航', 0, '', '', 1423492650, 1423492650, '', 0, 0, 1),
    (2, 0, '底部导航', 1, '', '', 1423492650, 1423492650, '', 0, 0, 1),
    (3, 2, '关于我们', 1, '', '', 1423492651, 1423492651, '', 0, 0, 1),
    (4, 2, '加入我们', 1, '', '', 1423492652, 1423492652, '', 0, 0, 1),
    (5, 2, '联系我们', 1, '', '', 1423492653, 1423492653, '', 0, 0, 1),
    (6, 2, '常见问题', 1, '', '', 1423492654, 1423492654, '', 0, 0, 1),
    (7, 2, '用户协议', 1, '', '', 1423492655, 1423492655, '', 0, 0, 1),
    (8, 2, '意见反馈', 1, '', '', 1423492656, 1423492656, '', 0, 0, 1),
    (9, 2, '友情链接', 1, '', '', 1423492657, 1423492657, '', 0, 0, 1),
    (10, 1, '微博', 0, 'Tweet/index', '', 1417359799, 1423491518, 'fa fa-edit', 0, 0, 1),
    (11, 1, '文章', 0, 'Article/lists/category/article', '', 1417745409, 1422784421, ' fa fa fa-paper-plane', 0, 0, 1),
    (12, 1, '群组', 0, 'Article/lists/category/group', '', 1417745596, 1422783810, 'fa fa-group', 0, 0, 1),
    (13, 1, '活动', 0, 'Article/lists/category/event', '', 1417745638, 1417745638, 'fa fa-cube', 0, 0, 1),
    (14, 1, '商城', 0, 'Article/lists/category/shop', '', 1417745688, 1417745688, 'fa fa-shopping-cart', 0, 0, 1),
    (15, 1, '论坛', 0, 'Article/lists/category/forum', '', 1417745719, 1422784433, 'fa fa-comment', 0, 0, 1);

/*!40000 ALTER TABLE `ct_channel` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table core_comment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_comment`;

CREATE TABLE `ct_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '留言ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `app` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '评论模型ID',
  `cid` int(10) unsigned NOT NULL COMMENT '评论文档ID',
  `content` varchar(1280) NOT NULL DEFAULT '' COMMENT '评论内容',
  `create_time` int(11) unsigned NOT NULL COMMENT '评论时间',
  `update_time` int(11) unsigned NOT NULL COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `ip` varchar(15) NOT NULL COMMENT '来源IP',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论数据表';



# Dump of table core_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_config`;

CREATE TABLE `ct_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '配置说明',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `type` (`type`),
  KEY `group` (`group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统配置表';

LOCK TABLES `ct_config` WRITE;
/*!40000 ALTER TABLE `ct_config` DISABLE KEYS */;

INSERT INTO `ct_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`)
VALUES
    (1,'WEB_SITE_CLOSE',4,'站点开关',1,'0:关闭,1:开启','站点关闭后其他用户不能访问，管理员可以正常访问',1378898976,1406992386,1,'1',1),
    (2,'WEB_SITE_TITLE',1,'网站标题',1,'','网站标题前台显示标题',1378898976,1379235274,1,'CoreThink',2),
    (3,'WEB_SITE_DOMAIN',1,'网站域名',1,'','网站的域名，如 \"www.baidu.cn\"',1415983027,1415983202,1,'www.corethink.cn',3),
    (4,'WEB_SITE_SLOGAN',1,'网站宣传标语',1,'','网站宣传标语',1407003397,1407004692,1,'简单高效卓越的轻量级社交解决方案',4),
    (5,'WEB_SITE_HOME_PAGE',1,'网站首页地址',1,'','网站首页地址',1419407725,1419407806,1,'Index/index',5),
    (6,'WEB_SITE_LOGO',5,'网站LOGO',1,'','网站LOGO',1407003397,1407004692,1,'',6),
    (7,'WEB_SITE_FAVICON',5,'收藏夹图标',1,'','网页地址栏和收藏夹显示图标',1416672588,1416672626,1,'',7),
    (8,'WEB_SITE_DESCRIPTION',2,'网站描述',1,'','网站搜索引擎描述',1378898976,1379235841,1,'',8),
    (9,'WEB_SITE_KEYWORD',2,'网站关键字',1,'','网站搜索引擎关键字',1378898976,1381390100,1,'',9),
    (10,'WEB_SITE_COPYRIGHT',1,'版权信息',1,'','设置在网站底部显示的版权信息，如“版权所有 © 2014-2015 公司名”',1406991855,1406992583,1,'版权所有 © 2014-2015',10),
    (11,'WEB_SITE_ICP',1,'网站备案号',1,'','设置在网站底部显示的备案号，如“苏ICP备14000000号\"',1378900335,1415983236,1,'苏ICP备14000000号',11),
    (12,'WEB_SITE_CONTACT',3,'联系方式',1,'','网站联系方式，包含电话、邮件以及社交账号等',1410877075,1410877075,1,'QQ:598821125\r\nTEL:15005173785\r\nEMAIL:skipperprivater@gmail.com',12),
    (13,'WEB_SITE_STATISTICS',2,'站点统计',1,'','支持百度、Google、cnzz等所有Javascript的统计代码',1407824190,1407824303,1,'',13),
    (14,'COLOR_STYLE',4,'后台色系',1,'default_color:默认\namaze_color:怀想天空\nthinkox_color: ThinkOX','后台颜色风格',1379122533,1420644200,1,'default_color',14),
    (15,'DEFAULT_THEME', 4, '前台主题', 1, 'default:默认', '前台模版主题，不影响后台', 1425215616, 1425215616, 1, 'default', 15),
    (16,'DOCUMENT_POSITION',3,'文档推荐位',2,'','文档推荐位，推荐到多个位置KEY值相加即可',1379053380,1379235329,1,'1:首页推荐\r\n2:热门推荐',1),
    (17,'OPEN_DRAFTBOX',4,'是否开启草稿功能',2,'0:关闭草稿功能\r\n1:开启草稿功能\r\n','新增文章时的草稿功能配置',1379484332,1379484591,1,'0',3),
    (18,'DRAFT_AOTOSAVE_INTERVAL',0,'自动保存草稿时间',2,'','自动保存草稿的时间间隔，单位：秒',1379484574,1386143323,1,'600',4),
    (19,'LIST_ROWS',0,'后台每页记录数',2,'','后台数据每页显示记录数',1379503896,1380427745,1,'10',5),
    (20,'REPLY_LIST_ROWS',0,'评论列表每页条数',2,'','回复列表每页条数',1386645376,1408068026,1,'10',6),
    (21,'USER_ALLOW_REGISTER',4,'是否允许用户注册',3,'0:关闭注册\r\n1:允许注册','是否开放用户注册',1379504487,1379504580,1,'1',1),
    (22,'USER_COMMENT_CLOSE',4,'是否允许用户评论',3,'0:关闭评论,1:允许评论','评论关闭后用户不能进行评论',1418715779,1418716106,1,'1',2),
    (23,'USER_ACTION_LOG',4,'是否记录行为日志',3,'0:关闭记录\r\n1:开启记录\r\n','是否开启用户行为日志记录',1411723590,1411743500,1,'0',3),
    (24,'WEB_SITE_VERIFY_HOME',4,'前台登录验证码',3,'0:关闭验证,1:开启验证','前台登录时是否需要验证码',1409482093,1409482093,1,'0',4),
    (25,'WEB_SITE_VERIFY',4,'后台登录验证码',3,'0:关闭验证,1:开启验证','后台登录时是否需要验证码',1409482093,1409482093,1,'0',5),
    (26,'SENSITIVE_WORDS',2,'敏感字词',3,'','用户注册及内容显示敏感字词',1420385145,1420387079,1,'傻逼,垃圾',6),
    (27,'CONFIG_TYPE_LIST',3,'配置类型列表',4,'','主要用于数据解析和页面表单的生成',1378898976,1379235348,1,'0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举\r\n5:图片',1),
    (28,'CONFIG_GROUP_LIST',3,'配置分组',4,'','配置分组',1379228036,1384418383,1,'1:基本\r\n2:内容\r\n3:用户\r\n4:系统\r\n5:上传\r\n6:邮件\r\n7:支付',2),
    (29,'HOOKS_TYPE',3,'钩子的类型',4,'','类型 1-用于扩展显示内容，2-用于扩展业务处理',1379313397,1379313407,1,'1:视图\r\n2:控制器',3),
    (30,'DOCUMENT_MODEL_TYPE',3,'文档模型配置',4,'','文档模型核心配置，请勿更改',1416638950,1416638950,1,'1:目录\r\n2:主题\r\n3:段落',4),
    (31,'AUTH_CONFIG',3,'Auth配置',4,'','自定义Auth.class.php类配置',1379409310,1379409564,1,'AUTH_ON:1\r\nAUTH_TYPE:2',5),
    (32,'CONTENT_APP',3,'评论投票模型',4,'','核心配置，请勿更改',1418713621,1418713621,1,'0:document\r\n1:tweet\r\n',6),
    (33,'CODEMIRROR_THEME',4,'CodeMirror主题',4,'3024-day:3024 day\r\n3024-night:3024 night\r\nambiance:ambiance\r\nbase16-dark:base16 dark\r\nbase16-light:base16 light\r\nblackboard:blackboard\r\ncobalt:cobalt\r\neclipse:eclipse\r\nelegant:elegant\r\nerlang-dark:erlang-dark\r\nlesser-dark:lesser-dark\r\nmidnight:midnight','详情见CodeMirror官网',1379814385,1384740813,1,'ambiance',7),
    (34,'SHOW_PAGE_TRACE',4,'是否显示页面Trace',4,'0:关闭\r\n1:开启','是否显示页面Trace信息',1387165685,1387165685,1,'0',8),
    (35,'DATA_BACKUP_PATH',1,'数据库备份根路径',4,'','路径必须以 / 结尾',1381482411,1381482411,1,'./Data/',9),
    (36,'DATA_BACKUP_PART_SIZE',0,'数据库备份卷大小',4,'','该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M',1381482488,1381729564,1,'20971520',10),
    (37,'DATA_BACKUP_COMPRESS',4,'数据库备份文件是否启用压缩',4,'0:不压缩\r\n1:启用压缩','压缩备份文件需要PHP环境支持gzopen,gzwrite函数',1381713345,1381729544,1,'1',11),
    (38,'DATA_BACKUP_COMPRESS_LEVEL',4,'数据库备份文件压缩级别',4,'1:普通\r\n4:一般\r\n9:最高','数据库备份文件的压缩级别，该配置在开启压缩时生效',1381713408,1381713408,1,'9',12),
    (39,'DEVELOP_MODE',4,'开启开发者模式',4,'0:关闭\r\n1:开启','是否开启开发者模式',1383105995,1383291877,1,'1',13),
    (40,'ADMIN_ALLOW_IP',2,'后台允许访问IP',4,'','多个用逗号分隔，如果不配置表示不限制IP访问',1387165454,1387165553,1,'',14),
    (41,'ALLOW_VISIT',3,'不受限控制器方法',0,'','',1386644047,1386644741,1,'0:article/draftbox\r\n1:article/mydocument\r\n2:Category/tree\r\n3:Index/verify\r\n4:file/upload\r\n5:file/download\r\n6:user/updatePassword\r\n7:user/updateNickname\r\n8:user/submitPassword\r\n9:user/submitNickname\r\n10:file/uploadpicture',0),
    (42,'DENY_VISIT',3,'超管专限控制器方法',0,'','仅超级管理员可访问的控制器方法',1386644141,1386644659,1,'0:Addons/addhook\r\n1:Addons/edithook\r\n2:Addons/delhook\r\n3:Addons/updateHook\r\n4:Admin/getMenus\r\n5:Admin/recordList\r\n6:AuthManager/updateRules\r\n7:AuthManager/tree',0),
    (43,'PICTURE_UPLOAD_DRIVER',4,'图片上传驱动类型',5,'Local:Local-本地\r\nFtp:FTP空间\r\nSae:Sae-Storage\r\nBcs:Bcs云存储\r\nUpyun:又拍云\r\nQiniu:七牛云存储','需要配置相应的UPLOAD_{driver}_CONFIG 配置方可使用，不然默认Local本地',1393073505,1393073505,1,'Local',1),
    (44,'UPLOAD_FTP_CONFIG', 3, 'FTP上传配置', 5, '', 'FTP上传配置', 1393073559, 1393073559, 1, 'host:\r\nusername:\r\npassword:', 2),
    (45,'UPLOAD_SAE_CONFIG', 3, 'Sae上传配置', 5, '', 'Sae上传配置', 1393073998, 1393073998, 1, 'domain:', 3),
    (46,'UPLOAD_BCS_CONFIG', 3, 'Bcs上传配置', 5, '', 'Bcs上传配置', 1393073559, 1393073559, 1, 'AccessKey:\r\nSecretKey:\r\nbucket:', 4),
    (47,'UPLOAD_UPYUN_CONFIG', 3, '又拍云上传配置', 5, '', '又拍云上传配置', 1393073559, 1393073559, 1, 'host:\r\nusername:\r\npassword:\r\nbucket:', 5),
    (48,'UPLOAD_QINIU_CONFIG', 3, '七牛云存储上传配置', 5, '', '七牛云存储上传配置', 1393074989, 1416637334, 1, 'secrectKey:\r\naccessKey:\r\ndomain:\r\nbucket:', 6),
    (49,'MAIL_TYPE',4,'邮件类型',6,'1:内置函数发送,2:SMTP模块发送','邮件类型',1410491198,1417664552,1,'1',1),
    (50,'MAIL_SMTP_SECURE',4,'安全协议类型',6,'0:不使用,ssl:SSL','安全协议类型',1410491198,1417664552,1,'0',2),
    (51,'MAIL_SMTP_HOST',1,'SMTP服务器',6,'','邮箱服务器名称[如：smtp.qq.com]',1410491317,1410937703,1,'smtp.qq.com',3),
    (52,'MAIL_SMTP_PORT',0,'SMTP服务器端口',6,'','普通端口一般为25，SSL端口为465',1410491384,1410491384,1,'25',4),
    (53,'MAIL_SMTP_USER',1,'SMTP服务器用户名',6,'','邮箱用户名',1410491508,1410941682,1,'',5),
    (54,'MAIL_SMTP_PASS',1,'SMTP服务器密码',6,'邮箱密码','密码',1410491656,1410941695,1,'',6),
    (55,'MAIL_SMTP_TEST',1,'测试邮件接收地址',6,'','测试邮件接收地址',1410491698,1410937656,1,'',7),
    (56,'MIAL_SMTP_SIGNATURE',2,'邮件签名',6,'','邮件签名',1417675463,1417675527,1,'',8),
    (57,'PAY_ALIPAY', 3, '支付宝配置', 7, '', '支付宝配置', 1393074989, 1416637334, 1, 'email:\r\nkey:\r\npartner:', 1),
    (58,'PAY_TENPAY', 3, '财付通配置', 7, '', '财付通配置', 1393074989, 1416637334, 1, 'key:\r\npartner:', 2),
    (59,'PAY_PAYPAL', 3, 'PayPal配置', 7, '', 'PayPal配置', 1393074989, 1416637334, 1, 'business:', 3),
    (60,'PAY_YEEPAY', 3, '易宝配置', 7, '', '易宝配置', 1393074989, 1416637334, 1, 'key:\r\npartner:', 4),
    (61,'PAY_KUAIQIAN', 3, '快钱配置', 7, '', '快钱配置', 1393074989, 1416637334, 1, 'key:\r\npartner:', 5),
    (62,'PAY_UNIONPAY', 3, '银联配置', 7, '', '银联配置', 1393074989, 1416637334, 1, 'key:\r\npartner:', 6),
    (63,'PAY_ALIWAPPAY', 3, '支付宝WAP配置', 7, '', '支付宝WAP配置', 1393074989, 1416637334, 1, 'email:\r\nkey:\r\npartner:', 7);

/*!40000 ALTER TABLE `ct_config` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table core_digg
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_digg`;

CREATE TABLE `ct_digg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '投票ID',
  `app` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '投票模型ID',
  `cid` int(10) unsigned NOT NULL COMMENT '投票文档ID',
  `good` longtext COMMENT '赞',
  `bad` longtext COMMENT '踩',
  `bookmark` longtext COMMENT '收藏',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Digg插件表';


# Dump of table core_document
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_document`;

CREATE TABLE `ct_document` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `model_id` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '内容模型ID',
  `category_id` int(10) unsigned NOT NULL COMMENT '所属分类',
  `group_id` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '所属分组',
  `root` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '根节点',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属ID',
  `name` char(40) NOT NULL DEFAULT '' COMMENT '标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '标题',
  `color` char(7) NOT NULL DEFAULT '' COMMENT '标题颜色',
  `tags` char(70) NOT NULL DEFAULT '' COMMENT '标签',
  `description` char(255) NOT NULL DEFAULT '' COMMENT '描述',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '封面',
  `pictures` char(10) NOT NULL DEFAULT '' COMMENT '多图上传',
  `position` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '内容类型',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '可见性',
  `view` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `comment` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `bookmark` int(10) NOT NULL DEFAULT '0' COMMENT '收藏数',
  `good` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '赞数',
  `bad` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '踩数',
  `deadline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '截至时间',
  `level` int(10) NOT NULL DEFAULT '0' COMMENT '优先级',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '数据状态',
  `attach` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '附件数量',
  PRIMARY KEY (`id`),
  KEY `idx_category_status` (`category_id`,`status`),
  KEY `idx_status_type_pid` (`status`,`uid`,`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型基础表';

LOCK TABLES `ct_document` WRITE;
/*!40000 ALTER TABLE `ct_document` DISABLE KEYS */;

INSERT INTO `ct_document` (`id`, `uid`, `model_id`, `category_id`, `group_id`, `root`, `pid`, `name`, `title`, `color`, `tags`, `description`, `cover_id`, `pictures`, `position`, `create_time`, `update_time`, `link_id`, `type`, `display`, `view`, `comment`, `bookmark`, `good`, `bad`, `deadline`, `level`, `status`, `attach`)
VALUES
    (1,1,2,1,0,0,0,'','简单高效卓越的轻量级产品开发框架-CoreThink','','','CoreThink是一套轻量级产品开发框架，追求简单、高效、卓越。可轻松实现移动互联网时代支持多终端的轻量级产品快速开发。',0,'',3,1418047620,1420699557,0,2,1,0,0,0,0,0,0,0,1,0);

/*!40000 ALTER TABLE `ct_document` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table core_document_article
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_document_article`;

CREATE TABLE `ct_document_article` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型',
  `content` longtext NOT NULL COMMENT '文章内容',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型文章表';

LOCK TABLES `ct_document_article` WRITE;
/*!40000 ALTER TABLE `ct_document_article` DISABLE KEYS */;

INSERT INTO `ct_document_article` (`id`, `parse`, `content`, `template`)
VALUES
    (1,0,'<h1>\r\n    CoreThink\r\n</h1>\r\n<h2>\r\n    项目介绍\r\n</h2>\r\n<p>\r\n    CoreThink是一套轻量级产品开发框架，追求简单、高效、卓越。可轻松实现移动互联网时代支持多终端的轻量级社区快速开发。系统功能采用模块化开发，内置权限控制、文档模型、模板标签、用户支付等模块，便于用户灵活扩展和二次开发。内建数据备份、云存储、邮件管理、静态化、插件扩展、敏感词过滤、用户行为记录、广告管理等功能。\r\n</p>\r\n<h2>\r\n    目录结构\r\n</h2>\r\n<pre class=\"prettyprint lang-js\">├─index.php     CoreThink入口文件\r\n├─Addons 插件目录\r\n│  \r\n├─Application 应用模块目录\r\n│  ├─Admin 后台模块\r\n│  │  ├─Conf 后台配置文件目录\r\n│  │  ├─Common 后台函数公共目录\r\n│  │  ├─Controller 后台控制器目录\r\n│  │  ├─Model 后台模型目录\r\n│  │  ├─Logic 后台模型逻辑目录\r\n│  │  └─View 后台视图文件目录\r\n│  │  \r\n│  ├─Common 公共模块目录（不能直接访问）\r\n│  │  ├─Conf 公共配置文件目录\r\n│  │  ├─Common 公共函数文件目录\r\n│  │  ├─Controller 模块访问控制器目录\r\n│  │  └─Model 公共模型目录\r\n│  │  \r\n│  ├─Home Home 前台模块\r\n│  │  ├─Conf 前台配置文件目录\r\n│  │  ├─Common 前台函数公共目录\r\n│  │  ├─Controller 前台控制器目录\r\n│  │  ├─Model 前台模型目录\r\n│  │  └─View 模块视图文件目录\r\n│  │\r\n│  └─User 用户模块（不能直接访问）\r\n│     ├─Api 用户接口文件目录\r\n│     ├─Conf 用户配置目录\r\n│     ├─Common 后台函数公共目录\r\n│     ├─Model 用户模型目录\r\n│     └─Service 用户Service文件目录\r\n│\r\n├─Public 应用资源文件目录 \r\n├─Runtime 应用运行时目录\r\n├─ThinkPHP 框架目录\r\n└─Uploads 上传根目录\r\n  ├─Download 文件上传目录\r\n  ├─Picture 图片上传目录\r\n  └─Editor 编辑器图片上传目录</pre>\r\n<h2>\r\n    问题反馈\r\n</h2>\r\n<p>\r\n    在使用中有任何问题，欢迎反馈给我们，可以用以下联系方式跟我们交流\r\n</p>\r\n<ul>\r\n    <li>\r\n        邮件: skipperprivater@gmail.com\r\n    </li>\r\n    <li>\r\n        QQ: 598821125\r\n    </li>\r\n    <li>\r\n        电话: 15005173785\r\n    </li>\r\n</ul>\r\n<h2>\r\n    感激\r\n</h2>\r\n<p>\r\n    感谢以下的项目,排名不分先后\r\n</p>\r\n<ul>\r\n    <li>\r\n        <a href=\"http://thinkphp.cn/\">ThinkPHP</a> \r\n    </li>\r\n    <li>\r\n        <a href=\"http://onethink.cn/\">OneThink</a> \r\n    </li>\r\n    <li>\r\n        <a href=\"http://getbootstrap.com\">Bootstrap</a> \r\n    </li>\r\n    <li>\r\n        <a href=\"http://jquery.com\">jQuery</a> \r\n    </li>\r\n</ul>\r\n<h2>\r\n    关于我们\r\n</h2>\r\n<p>\r\n    南京同好网络科技有限公司\r\n</p>\r\n<p>\r\n    <strong>利用本系统现有的后台功能和标签库机制，你可以轻松的定制或者开发基于本系统的社交产品、网站和应用。</strong> \r\n</p>','');

/*!40000 ALTER TABLE `ct_document_article` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table core_document_download
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_document_download`;

CREATE TABLE `ct_document_download` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型',
  `content` text NOT NULL COMMENT '下载详细描述',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型下载表';



# Dump of table core_file
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_file`;

CREATE TABLE `ct_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '原始文件名',
  `savename` char(20) NOT NULL DEFAULT '' COMMENT '保存名称',
  `savepath` char(30) NOT NULL DEFAULT '' COMMENT '文件保存路径',
  `ext` char(5) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `mime` char(40) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `location` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '文件保存位置',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '远程地址',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_md5` (`md5`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文件表';



# Dump of table core_hooks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_hooks`;

CREATE TABLE `ct_hooks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text NOT NULL COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统钩子表';

LOCK TABLES `ct_hooks` WRITE;
/*!40000 ALTER TABLE `ct_hooks` DISABLE KEYS */;

INSERT INTO `ct_hooks` (`id`, `name`, `description`, `type`, `update_time`, `addons`, `status`)
VALUES
    (1,'pageHeader','页面header钩子，一般用于加载插件CSS文件和代码',1,0,'',0),
    (2,'pageFooter','页面footer钩子，一般用于加载插件JS文件和JS代码',1,0,'ReturnTop,AdFloat',0),
    (3,'documentEditForm','添加编辑表单的扩展内容钩子',1,0,'',0),
    (4,'documentDetailAfter','文档末尾显示',1,1412615352,'Fancybox,SocialComment',0),
    (5,'documentSaveComplete','保存文档数据后的扩展钩子',2,0,'',0),
    (6,'homeArticleEdit','添加编辑表单的内容显示钩子',1,0,'Editor',0),
    (7,'adminArticleEdit','后台内容编辑页编辑器',1,1378982734,'EditorForAdmin',0),
    (8,'adminIndex','首页小格子个性化显示',1,1407827181,'SiteStat,SystemInfo,DevTeam,QiuBai,Weather',0),
    (9,'app_begin','应用开始',2,1384481614,'',0),
    (10,'UploadImages','多图上传',1,1411705309,'UploadImages',0),
    (11,'BaiduShare','百度分享',1,1408122773,'BaiduShare',0),
    (12,'SyncLogin','第三方账号同步登陆',1,1408122773,'SyncLogin',0);

/*!40000 ALTER TABLE `ct_hooks` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table core_mail
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_mail`;

CREATE TABLE `ct_mail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '邮件ID',
  `mail_to` varchar(255) NOT NULL DEFAULT '0' COMMENT '收件邮箱',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '邮件标题',
  `body` text NOT NULL COMMENT '邮件正文',
  `create_time` int(11) unsigned NOT NULL COMMENT '发送时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邮件列表';



# Dump of table core_member
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_member`;

CREATE TABLE `ct_member` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `nickname` char(16) NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
  `qq` varchar(20) NOT NULL DEFAULT '' COMMENT 'qq号',
  `score` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '账户余额',
  `avatar` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员头像',
  `login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '会员状态',
  PRIMARY KEY (`uid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员表';



# Dump of table core_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_menu`;

CREATE TABLE `ct_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

LOCK TABLES `ct_menu` WRITE;
/*!40000 ALTER TABLE `ct_menu` DISABLE KEYS */;

INSERT INTO `ct_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`, `status`)
VALUES
    (1, '首页', 0, 1, 'Index/index', 0, '首页', '', 0, 1),
    (2, '系统', 0, 2, 'Config/group', 0, '系统', '', 0, 1),
    (3, '内容', 0, 3, 'Article/index', 0, '内容', '', 0, 1),
    (4, '用户', 0, 4, 'User/index', 0, '用户', '', 0, 1),
    (5, '扩展', 0, 5, 'Addons/index', 0, '扩展', '', 1, 1),
    (6, '其他', 0, 6, 'Other', 1, '其他', '', 0, 1),
    (7, '网站设置', 2, 1, 'Config/group', 0, '网站设置', '系统设置', 0, 1),
    (8, '配置管理', 2, 5, 'Config/index', 0, '配置管理', '系统设置', 1, 1),
    (9, '新增', 8, 0, 'Config/add', 0, '新增配置', '', 0, 1),
    (10, '编辑', 8, 0, 'Config/edit', 0, '编辑配置', '', 0, 1),
    (11, '删除', 8, 0, 'Config/del', 0, '删除配置', '', 0, 1),
    (12, '保存', 8, 0, 'Config/save', 0, '保存配置', '', 0, 1),
    (13, '排序', 8, 0, 'Config/sort', 0, '配置排序', '', 0, 1),
    (14, '分类管理', 2, 2, 'Category/index', 0, '分类管理', '系统设置', 0, 1),
    (15, '新增', 14, 0, 'Category/add', 0, '新增栏目分类', '', 0, 1),
    (16, '编辑', 14, 0, 'Category/edit', 0, '编辑栏目分类', '', 0, 1),
    (17, '删除', 14, 0, 'Category/del', 0, '删除栏目分类', '', 0, 1),
    (18, '改变状态', 14, 0, 'Category/setStatus', 0, '改变栏目状态', '', 0, 1),
    (19, '移动', 14, 0, 'Category/operate/type/move', 0, '移动栏目分类', '', 0, 1),
    (20, '合并', 14, 0, 'Category/operate/type/merge', 0, '合并栏目分类', '', 0, 1),
    (21, '导航管理', 2, 3, 'Channel/index', 0, '导航管理', '系统设置', 0, 1),
    (22, '新增', 21, 0, 'Channel/add', 0, '新增导航', '', 0, 1),
    (23, '编辑', 21, 0, 'Channel/edit', 0, '编辑导航', '', 0, 1),
    (24, '删除', 21, 0, 'Channel/del', 0, '删除导航', '', 0, 1),
    (25, '改变状态', 21, 0, 'Channel/setStatus', 0, '改变导航状态', '', 0, 1),
    (26, '排序', 21, 0, 'Channel/sort', 0, '导航排序', '', 0, 1),
    (27, '模型管理', 2, 4, 'Model/index', 0, '模型管理', '系统设置', 1, 1),
    (28, '新增', 27, 0, 'Model/add', 0, '新增模型', '', 0, 1),
    (29, '编辑', 27, 0, 'Model/edit', 0, '编辑模型', '', 0, 1),
    (30, '删除', 27, 0, 'Model/del', 0, '删除模型', '', 0, 1),
    (31, '保存', 27, 0, 'Model/update', 0, '保存模型', '', 0, 1),
    (32, '改变状态', 27, 0, 'Model/setStatus', 0, '改变模型状态', '', 0, 1),
    (33, '生成', 27, 0, 'Model/generate', 0, '生成模型', '', 0, 1),
    (34, '菜单管理', 2, 6, 'Menu/index', 0, '菜单管理', '系统设置', 1, 1),
    (35, '新增', 34, 0, 'Menu/add', 0, '新增菜单', '', 0, 1),
    (36, '编辑', 34, 0, 'Menu/edit', 0, '编辑菜单', '', 0, 1),
    (37, '删除', 34, 0, 'Menu/del', 0, '删除菜单', '', 0, 1),
    (38, '排序', 34, 0, 'Menu/sort', 0, '菜单排序', '', 0, 1),
    (39, '导入', 34, 0, 'Menu/import', 0, '导入菜单', '', 0, 1),
    (40, '备份数据库', 2, 9, 'Database/index?type=export', 0, '备份数据库', '数据备份', 0, 1),
    (41, '备份', 40, 0, 'Database/export', 0, '备份数据库', '', 0, 1),
    (42, '优化表', 40, 0, 'Database/optimize', 0, '优化数据表', '', 0, 1),
    (43, '修复表', 40, 0, 'Database/repair', 0, '修复数据表', '', 0, 1),
    (44, '还原数据库', 2, 10, 'Database/index?type=import', 0, '还原数据库', '数据备份', 0, 1),
    (45, '恢复', 44, 0, 'Database/import', 0, '数据库恢复', '', 0, 1),
    (46, '删除', 44, 0, 'Database/del', 0, '删除备份文件', '', 0, 1),
    (47, '属性管理', 2, 7, 'Attribute/index', 1, '网站属性配置', '', 0, 1),
    (48, '新增', 47, 0, 'Attribute/add', 0, '新增属性', '', 0, 1),
    (49, '编辑', 47, 0, 'Attribute/edit', 0, '编辑属性', '', 0, 1),
    (50, '删除', 47, 0, 'Attribute/del', 0, '删除属性', '', 0, 1),
    (51, '保存', 47, 0, 'Attribute/update', 0, '保存属性', '', 0, 1),
    (52, '改变状态', 47, 0, 'Attribute/setStatus', 0, '改变属性状态', '', 0, 1),
    (53, '数据列表', 2, 8, 'Think/lists', 1, '数据列表', '', 0, 1),
    (54, '新增', 53, 0, 'Think/add', 1, '新增数据', '', 0, 1),
    (55, '编辑', 53, 0, 'Think/edit', 1, '编辑数据', '', 0, 1),
    (56, '改变状态', 53, 0, 'Think/setStatus', 1, '改变数据状态', '', 0, 1),
    (57, '所有文档', 3, 2, 'Article/index', 0, '所有文档列表', '文档管理', 0, 1),
    (58, '我的文档', 3, 3, 'Article/mydocument', 0, '我的文档', '文档管理', 0, 1),
    (59, '新增', 57, 0, 'Article/add', 0, '新增文档', '', 0, 1),
    (60, '编辑', 57, 0, 'Article/edit', 0, '编辑文档', '', 0, 1),
    (61, '保存', 57, 0, 'Article/update', 0, '保存文档', '', 0, 1),
    (62, '改变状态', 57, 0, 'Article/setStatus', 0, '改变文档状态', '', 0, 1),
    (63, '移动', 57, 0, 'Article/move', 0, '移动文档', '', 0, 1),
    (64, '复制', 57, 0, 'Article/copy', 0, '复制文档', '', 0, 1),
    (65, '粘贴', 57, 0, 'Article/paste', 0, '粘贴文档', '', 0, 1),
    (66, '文档排序', 57, 0, 'Article/sort', 0, '文档排序', '', 0, 1),
    (67, '保存草稿', 57, 0, 'Article/autoSave', 0, '保存文档草稿', '', 0, 1),
    (68, '待审核', 3, 4, 'Article/examine', 0, '待审核列表', '文档管理', 0, 1),
    (69, '草稿箱', 3, 5, 'Article/draftbox', 0, '草稿箱', '文档管理', 0, 1),
    (70, '回收站', 3, 6, 'Article/recycle', 0, '文档回收站', '文档管理', 0, 1),
    (71, '还原', 70, 0, 'Article/permit', 0, '还原回收站文档', '', 0, 1),
    (72, '清空', 70, 0, 'Article/clear', 0, '清空回收站', '', 0, 1),
    (73, '微博列表', 3, 7, 'Tweet/index', 0, '微博列表', '微博管理', 0, 1),
    (74, '新增', 73, 0, 'Tweet/add', 0, '新增微博', '', 0, 1),
    (75, '编辑', 73, 0, 'Tweet/edit', 0, '编辑微博', '', 0, 1),
    (76, '保存', 73, 0, 'Tweet/save', 0, '保存微博', '', 0, 1),
    (77, '改变状态', 73, 0, 'Tweet/changeStatus', 0, '改变微博状态', '', 0, 1),
    (78, '评论列表', 3, 8, 'Comment/index', 0, '评论列表', '评论管理', 0, 1),
    (79, '新增', 78, 0, 'Comment/add', 0, '新增评论', '', 0, 1),
    (80, '编辑', 78, 0, 'Comment/edit', 0, '编辑评论', '', 0, 1),
    (81, '保存', 78, 0, 'Comment/save', 0, '保存评论', '', 0, 1),
    (82, '改变状态', 78, 0, 'Comment/changeStatus', 0, '改变评论状态', '', 0, 1),
    (83, '用户列表', 4, 1, 'User/index', 0, '用户列表', '用户管理', 0, 1),
    (84, '新增', 83, 0, 'User/add', 0, '添加新用户', '', 0, 1),
    (85, '编辑', 83, 0, 'User/edit', 0, '编辑用户', '', 0, 1),
    (86, '改变状态', 83, 0, 'User/changeStatus', 0, '改变用户状态', '', 0, 1),
    (87, '授权', 83, 0, 'AuthManager/group', 0, '设置用户所属用户组', '', 0, 1),
    (88, '修改昵称', 83, 0, 'User/updateNickname', 1, '修改昵称', '', 0, 1),
    (89, '修改密码', 83, 0, 'User/updatePassword', 1, '修改密码', '', 0, 1),
    (90, '权限管理', 4, 2, 'AuthManager/index', 0, '权限管理', '用户管理', 0, 1),
    (91, '新增', 90, 0, 'AuthManager/createGroup', 0, '新增用户组', '', 0, 1),
    (92, '编辑', 90, 0, 'AuthManager/editGroup', 0, '编辑用户组', '', 0, 1),
    (93, '保存', 90, 0, 'AuthManager/writeGroup', 0, '保存用户组', '', 0, 1),
    (94, '改变状态', 90, 0, 'AuthManager/changeStatus', 0, '改变用户组状态', '', 0, 1),
    (95, '访问授权', 90, 0, 'AuthManager/access', 0, '用户组访问授权', '', 0, 1),
    (96, '分类授权', 90, 0, 'AuthManager/category', 0, '用户组分类授权', '', 0, 1),
    (97, '保存分类授权', 90, 0, 'AuthManager/addToCategory', 0, '保存用户组分类授权', '', 0, 1),
    (98, '成员授权', 90, 0, 'AuthManager/user', 0, '用户组成员授权', '', 0, 1),
    (99, '保存成员授权', 90, 0, 'AuthManager/addToGroup', 0, '保存成员授权', '', 0, 1),
    (100, '解除授权', 90, 0, 'AuthManager/removeFromGroup', 0, '用户组成员解除授权', '', 0, 1),
    (101, '用户行为', 4, 3, 'User/action', 0, '用户行为', '行为管理', 1, 1),
    (102, '新增', 101, 0, 'User/addaction', 0, '新增用户行为', '', 0, 1),
    (103, '编辑', 101, 0, 'User/editaction', 0, '编辑用户行为', '', 0, 1),
    (104, '保存', 101, 0, 'User/saveAction', 0, '保存用户行为', '', 0, 1),
    (105, '改变状态', 101, 0, 'User/setStatus', 0, '改变行为状态', '', 0, 1),
    (106, '行为日志', 4, 4, 'Action/actionlog', 0, '行为日志', '行为管理', 0, 1),
    (107, '查看行为日志', 106, 0, 'Action/edit', 0, '查看行为日志', '', 0, 1),
    (108, '系统消息', 4, 5, 'MessageSystem/index', 0, '系统消息', '消息管理', 0, 1),
    (109, '新增', 108, 0, 'MessageSystem/add', 0, '发送新系统消息', '', 0, 1),
    (110, '编辑', 108, 0, 'MessageSystem/edit', 0, '编辑系统消息', '', 0, 1),
    (111, '保存', 108, 0, 'MessageSystem/save', 0, '保存系统消息', '', 0, 1),
    (112, '改变状态', 108, 0, 'MessageSystem/changeStatus', 0, '改变系统消息状态', '', 0, 1),
    (113, '用户消息', 4, 6, 'Message/index/', 0, '用户消息', '消息管理', 0, 1),
    (114, '新增', 113, 0, 'Message/add', 0, '发送新消息', '', 0, 1),
    (115, '编辑', 113, 0, 'Message/edit', 0, '编辑用户消息', '', 0, 1),
    (116, '保存', 113, 0, 'Message/save', 0, '保存用户消息', '', 0, 1),
    (117, '改变状态', 113, 0, 'Message/changeStatus', 0, '改变用户消息状态', '', 0, 1),
    (118, '邮件列表', 4, 7, 'Mail/index', 0, '邮件列表', '邮件管理', 0, 1),
    (119, '新增', 118, 0, 'Mail/add', 0, '新增邮件', '', 0, 1),
    (120, '编辑', 118, 0, 'Mail/edit', 0, '编辑邮件', '', 0, 1),
    (121, '保存', 118, 0, 'Mail/save', 0, '保存邮件', '', 0, 1),
    (122, '改变状态', 118, 0, 'Mail/changeStatus', 0, '改变邮件状态', '', 0, 1),
    (123, '插件管理', 5, 1, 'Addons/index', 0, '插件管理', '系统扩展', 0, 1),
    (124, '创建', 123, 0, 'Addons/create', 0, '服务器上创建插件结构向导', '', 0, 1),
    (125, '检测创建', 123, 0, 'Addons/checkForm', 0, '检测插件是否可以创建', '', 0, 1),
    (126, '预览', 123, 0, 'Addons/preview', 0, '预览插件定义类文件', '', 0, 1),
    (127, '快速生成插件', 123, 0, 'Addons/build', 0, '开始生成插件结构', '', 0, 1),
    (128, '设置', 123, 0, 'Addons/config', 0, '设置插件配置', '', 0, 1),
    (129, '禁用', 123, 0, 'Addons/disable', 0, '禁用插件', '', 0, 1),
    (130, '启用', 123, 0, 'Addons/enable', 0, '启用插件', '', 0, 1),
    (131, '安装', 123, 0, 'Addons/install', 0, '安装插件', '', 0, 1),
    (132, '卸载', 123, 0, 'Addons/uninstall', 0, '卸载插件', '', 0, 1),
    (133, '更新配置', 123, 0, 'Addons/saveconfig', 0, '更新插件配置处理', '', 0, 1),
    (134, '插件后台列表', 123, 0, 'Addons/adminList', 0, '插件后台列表', '', 0, 1),
    (135, 'URL方式访问插件', 123, 0, 'Addons/execute', 0, '控制是否有权限通过url访问插件控制器方法', '', 0, 1),
    (136, '钩子管理', 5, 2, 'Addons/hooks', 0, '钩子管理', '系统扩展', 0, 1),
    (137, '新增', 136, 0, 'Addons/addHook', 0, '新增钩子', '', 0, 1),
    (138, '编辑', 136, 0, 'Addons/edithook', 0, '编辑钩子', '', 0, 1),
    (139, '幻灯列表', 3, 1, 'Slider/index', 0, '幻灯列表', '幻灯管理', 0, 1),
    (140, '新增', 139, 0, 'Slider/add', 0, '新增幻灯', '', 0, 1),
    (141, '编辑', 139, 0, 'Slider/edit', 0, '编辑幻灯', '', 0, 1),
    (142, '删除', 139, 0, 'Slider/del', 0, '删除幻灯', '', 0, 1),
    (143, '改变状态', 139, 0, 'Slider/setStatus', 0, '改变幻灯状态', '', 0, 1),
    (144, '排序', 139, 0, 'Slider/sort', 0, '幻灯排序', '', 0, 1),
    (145, '标签列表', 3, 9, 'Tags/index', 0, '标签管理', '标签管理', 0, 1),
    (146, '新增', 145, 0, 'Tags/add', 0, '新增标签', '', 0, 1),
    (147, '编辑', 145, 0, 'Tags/edit', 0, '编辑标签', '', 0, 1),
    (148, '删除', 145, 0, 'Tags/del', 0, '删除标签', '', 0, 1),
    (149, '改变状态', 145, 0, 'Tags/setStatus', 0, '改变标签状态', '', 0, 1),
    (150, '排序', 145, 0, 'Tags/sort', 0, '标签排序', '', 0, 1);

/*!40000 ALTER TABLE `ct_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table core_message
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_message`;

CREATE TABLE `ct_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '消息ID',
  `to_uid` varchar(20) NOT NULL DEFAULT '' COMMENT '接收用户ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0系统消息,1用户消息,2应用消息',
  `from_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发送用户ID',
  `create_time` int(11) unsigned NOT NULL COMMENT '发送时间',
  `is_read` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已读',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `ms_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '系统消息ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户消息表';



# Dump of table core_message_system
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_message_system`;

CREATE TABLE `ct_message_system` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '消息ID',
  `to_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '接收消息用户ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `create_time` int(11) unsigned NOT NULL COMMENT '发送时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户获取计数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统消息表';



# Dump of table core_model
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_model`;

CREATE TABLE `ct_model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '模型标识',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `extend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '继承的模型',
  `relation` varchar(30) NOT NULL DEFAULT '' COMMENT '继承与被继承模型的关联字段',
  `need_pk` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '新建表时是否需要主键字段',
  `field_sort` text NOT NULL COMMENT '表单字段排序',
  `field_group` varchar(255) NOT NULL DEFAULT '1:基础' COMMENT '字段分组',
  `attribute_list` text NOT NULL COMMENT '属性列表（表的字段）',
  `attribute_alias` varchar(255) NOT NULL DEFAULT '' COMMENT '属性别名定义',
  `template_list` varchar(100) NOT NULL DEFAULT '' COMMENT '列表模板',
  `template_add` varchar(100) NOT NULL DEFAULT '' COMMENT '新增模板',
  `template_edit` varchar(100) NOT NULL DEFAULT '' COMMENT '编辑模板',
  `list_grid` text NOT NULL COMMENT '列表定义',
  `list_row` smallint(2) unsigned NOT NULL DEFAULT '10' COMMENT '列表数据长度',
  `search_key` varchar(50) NOT NULL DEFAULT '' COMMENT '默认搜索字段',
  `search_list` varchar(255) NOT NULL DEFAULT '' COMMENT '高级搜索的字段',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `engine_type` varchar(25) NOT NULL DEFAULT 'MyISAM' COMMENT '数据库引擎',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型表';

LOCK TABLES `ct_model` WRITE;
/*!40000 ALTER TABLE `ct_model` DISABLE KEYS */;

INSERT INTO `ct_model` (`id`, `name`, `title`, `extend`, `relation`, `need_pk`, `field_sort`, `field_group`, `attribute_list`, `attribute_alias`, `template_list`, `template_add`, `template_edit`, `list_grid`, `list_row`, `search_key`, `search_list`, `create_time`, `update_time`, `status`, `engine_type`)
VALUES
    (1,'document','基础文档',0,'',1,'{\"1\":[\"8\",\"10\",\"11\",\"12\",\"13\",\"14\",\"15\"],\"2\":[\"7\",\"9\",\"17\",\"18\",\"19\",\"20\",\"21\",\"22\",\"23\",\"24\",\"25\",\"26\",\"27\"]}','1:基础,2:扩展','','','','','','id:编号\r\ntitle:标题:[EDIT]\r\ntype:类型\r\nupdate_time:最后更新\r\nstatus:状态\r\nview:浏览\r\nid:操作:[EDIT]|编辑,[DELETE]|删除',10,'','',1383891233,1384507827,1,'MyISAM'),
    (2,'article','文章',1,'',1,'{\"1\":[\"8\",\"10\",\"11\",\"12\",\"13\",\"30\",\"14\",\"15\"],\"2\":[\"7\",\"9\",\"17\",\"18\",\"19\",\"20\",\"21\",\"22\",\"23\",\"24\",\"25\",\"26\",\"31\"]}','1:基础,2:扩展','','','','','','',10,'','',1383891243,1420232277,1,'MyISAM'),
    (3,'download','下载',1,'',1,'{\"1\":[\"8\",\"10\",\"11\",\"12\",\"13\",\"33\",\"35\",\"37\",\"36\",\"14\",\"15\"],\"2\":[\"7\",\"9\",\"17\",\"18\",\"19\",\"20\",\"21\",\"22\",\"23\",\"24\",\"25\",\"26\",\"34\"]}','1:基础,2:扩展','','','','','','',10,'','',1383891252,1420232543,1,'MyISAM');

/*!40000 ALTER TABLE `ct_model` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table core_pay
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_pay`;

CREATE TABLE `ct_pay` (
  `out_trade_no` varchar(32) NOT NULL DEFAULT '' COMMENT '订单号',
  `title` varchar(80) NOT NULL DEFAULT '' COMMENT '标题',
  `type` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '订单类别',
  `money` decimal(10,2) NOT NULL COMMENT '支付金额',
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `paytype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '付款方式',
  `callback` varchar(255) NOT NULL DEFAULT '' COMMENT '回调接口地址',
  `param` varchar(255) NOT NULL DEFAULT '' COMMENT '附加参数',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`out_trade_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付表';


# Dump of table core_picture
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_picture`;

CREATE TABLE `ct_picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='上传图片信息表';


# Dump of table core_slider
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_slider`;

CREATE TABLE `ct_slider` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '幻灯ID',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '标题',
  `cover_id` int(11) unsigned NOT NULL COMMENT '封面ID',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '点击链接',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='幻灯切换表';



# Dump of table core_sync_login
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_sync_login`;

CREATE TABLE `ct_sync_login` (
  `uid` int(10) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `refresh_token` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='第三方同步登录信息表';



# Dump of table core_tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_tags`;

CREATE TABLE `ct_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL COMMENT '标题',
  `description` varchar(127) DEFAULT '' NULL COMMENT '描述',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='标签表';

LOCK TABLES `ct_tags` WRITE;
/*!40000 ALTER TABLE `ct_tags` DISABLE KEYS */;

INSERT INTO `ct_tags` (`id`, `title`, `description`, `create_time`, `count`, `sort`)
VALUES
    (1,'CoreThink','CoreThink',1418405374,1,0);

/*!40000 ALTER TABLE `ct_tags` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table core_tweet
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_tweet`;

CREATE TABLE `ct_tweet` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `content` char(255) NOT NULL DEFAULT '' COMMENT '内容',
  `picture` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '配图',
  `create_time` int(11) unsigned NOT NULL COMMENT '发表时间',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `view` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `comment` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `good` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '赞数',
  `bad` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '踩数',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微博数据表';



# Dump of table core_ucenter_admin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_ucenter_admin`;

CREATE TABLE `ct_ucenter_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员用户ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '管理员状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='UCenter管理员表';



# Dump of table core_ucenter_app
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_ucenter_app`;

CREATE TABLE `ct_ucenter_app` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '应用ID',
  `title` varchar(30) NOT NULL COMMENT '应用名称',
  `url` varchar(100) NOT NULL COMMENT '应用URL',
  `ip` char(15) NOT NULL DEFAULT '' COMMENT '应用IP',
  `auth_key` varchar(100) NOT NULL DEFAULT '' COMMENT '加密KEY',
  `sys_login` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '同步登陆',
  `allow_ip` varchar(255) NOT NULL DEFAULT '' COMMENT '允许访问的IP',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '应用状态',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='UCenter应用表';



# Dump of table core_ucenter_member
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_ucenter_member`;

CREATE TABLE `ct_ucenter_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(16) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `email` char(32) NOT NULL COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL DEFAULT '0' COMMENT '用户手机',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='UCenter用户表';

LOCK TABLES `ct_ucenter_member` WRITE;
/*!40000 ALTER TABLE `ct_ucenter_member` DISABLE KEYS */;

INSERT INTO `ct_ucenter_member` (`id`, `username`, `password`, `email`, `mobile`, `reg_time`, `reg_ip`, `last_login_time`, `last_login_ip`, `update_time`, `status`)
VALUES
    (1,'admin','54a2e66e2912dba24d9b4db464392337','598821125@qq.com','15005173785',1419611388,0,1420699391,2130706433,1418203524,1);

/*!40000 ALTER TABLE `ct_ucenter_member` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table core_ucenter_setting
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_ucenter_setting`;

CREATE TABLE `ct_ucenter_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '设置ID',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型（1-用户配置）',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `value` text NOT NULL COMMENT '配置数据',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='UCenter设置表';

LOCK TABLES `ct_ucenter_setting` WRITE;
/*!40000 ALTER TABLE `ct_ucenter_setting` DISABLE KEYS */;

INSERT INTO `ct_ucenter_setting` (`id`, `type`, `name`, `value`, `title`)
VALUES
    (1,1,'DENY_USERNAME','管理员,admin','系统保留的用户名'),
    (2,1,'LIMIT_TIME_BY_IP','3600','同一IP注册时间间隔');

/*!40000 ALTER TABLE `ct_ucenter_setting` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table core_url
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_url`;

CREATE TABLE `ct_url` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '链接唯一标识',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `short` char(100) NOT NULL DEFAULT '' COMMENT '短网址',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='链接表';
