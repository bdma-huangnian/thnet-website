<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class HomeController extends Controller {
    /* 空操作，用于输出404页面 */
    public function _empty(){
        $this->redirect('Index/index');
    }

    /* 初始化方法 */
    protected function _initialize(){
        $navs = D("channel")->order('id')->select();
        $navs = node_merge($navs);
        /* 读取站点配置 */
        $config = api('Config/lists');
        /* 模板相关配置 */
        $config['TMPL_PARSE_STRING']['__STATIC__'] =  __ROOT__.'/Public/static';
        $config['TMPL_PARSE_STRING']['__IMG__']    =  __ROOT__.'/Application/'.MODULE_NAME.'/View/'.$config['DEFAULT_THEME'].'/Static/images';
        $config['TMPL_PARSE_STRING']['__CSS__']    =  __ROOT__.'/Application/'.MODULE_NAME.'/View/'.$config['DEFAULT_THEME'].'/Static/css';
        $config['TMPL_PARSE_STRING']['__JS__']     =  __ROOT__.'/Application/'.MODULE_NAME.'/View/'.$config['DEFAULT_THEME'].'/Static/js';
        C($config); //添加配置
        if(!C('WEB_SITE_CLOSE')){
            $this->error('站点已经关闭，请稍后访问~');
        }
        $this->assign('menu', $navs);
        $this->assign('unreadMessageCount', D('Message')->getUnreadCount());
        $this->assign('sliders', D('slider')->where(array('status'=>1))->limit(0, 5)->order('`sort` DESC, `create_time` DESC')->select());
    }

    /* 用户登录检测 */
    protected function login(){
        /* 用户登录检测 */
        is_login() || $this->error('您还没有登录，请先登录！', U('User/login'));
    }
}
