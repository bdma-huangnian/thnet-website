<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Addons\BaiduShare;
use Common\Controller\Addon;
/**
 * 百度分享插件
 * @author 啊不名字
 */
class BaiduShareAddon extends Addon {
    public $custom_config = 'config.html';

    public $info = array(
        'name'=>'BaiduShare',
        'title'=>'百度分享',
        'description'=>'用户将网站内容分享到第三方网站',
        'status'=>1,
        'author'=>'CoreThink',
        'version'=>'0.1'
    );

    public function install(){
        return true;
    }

    public function uninstall(){
        return true;
    }

    //实现的BaiduShare钩子方法
    public function BaiduShare($param){
        $this->assign('info', $param);
        $this->assign('addons_config', $this->getConfig());
        $this->display('share');
    }
}
