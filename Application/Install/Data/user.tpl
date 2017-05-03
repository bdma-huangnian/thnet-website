<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
/**
 * UCenter客户端配置文件
 * 注意：该配置文件请使用常量方式定义
 */
$db = array(
    'DB_TYPE'    => $_SERVER[ENV_PRE.'DB_TYPE'] ? : '[DB_TYPE]', // 数据库类型
    'DB_HOST'    => $_SERVER[ENV_PRE.'DB_HOST'] ? : '[DB_HOST]', // 服务器地址
    'DB_NAME'    => $_SERVER[ENV_PRE.'DB_NAME'] ? : '[DB_NAME]', // 数据库名
    'DB_USER'    => $_SERVER[ENV_PRE.'DB_USER'] ? : '[DB_USER]', // 用户名
    'DB_PWD'     => $_SERVER[ENV_PRE.'DB_PWD']  ? : '[DB_PWD]', // 密码
    'DB_PORT'    => $_SERVER[ENV_PRE.'DB_PORT'] ? : '[DB_PORT]', // 端口
    'DB_PREFIX'  => $_SERVER[ENV_PRE.'DB_PREFIX'] ? : '[DB_PREFIX]', // 数据库表前缀
);
$UC_DB_DSN = $db['DB_TYPE'].'://'.$db['DB_USER'].':'.$db['DB_PWD'].'@'.$db['DB_HOST'].':'.$db['DB_PORT'].'/'.$db['DB_NAME'];
define('UC_APP_ID', 1); //应用ID
define('UC_API_TYPE', 'Model'); //可选值 Model / Service
define('UC_AUTH_KEY', '[AUTH_KEY]'); //加密KEY
define('UC_DB_DSN', $UC_DB_DSN); // 数据库连接，使用Model方式调用API必须配置此项
define('UC_TABLE_PREFIX', $db['DB_PREFIX']); // 数据表前缀，使用Model方式调用API必须配置此项
