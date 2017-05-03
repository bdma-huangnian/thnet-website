<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Addons\AdFloat;
use Common\Controller\Addon;
/**
 * 两侧浮动广告插件
 * @author birdy
 */
class AdFloatAddon extends Addon {
    public $info = array(
        'name'=>'AdFloat',
        'title'=>'图片漂浮广告',
        'description'=>'图片漂浮广告',
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

    //实现的pageFooter钩子方法
    public function pageFooter($param){
        $config = $this->getConfig();
        if($config['status']){
            $this->assign('config', $config);
            $this->display('content');
        }
    }
}
