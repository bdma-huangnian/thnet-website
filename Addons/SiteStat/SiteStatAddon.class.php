<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Addons\SiteStat;
use Common\Controller\Addon;
/**
 * 系统环境信息插件
 * @author thinkphp
 */
class SiteStatAddon extends Addon {
    public $info = array(
        'name'=>'SiteStat',
        'title'=>'站点统计信息',
        'description'=>'统计站点的基础信息',
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

    //实现的adminIndex钩子方法
    public function adminIndex($param){
        $config = $this->getConfig();
        $this->assign('addons_config', $config);
        if($config['display']){
            $info['user']        =    M('Member')->count();
            $info['action']        =    M('ActionLog')->count();
            $info['document']    =    M('Document')->count();
            $info['category']    =    M('Category')->count();
            $info['model']        =    M('Model')->count();
            $this->assign('info',$info);
            $this->display('info');
        }
    }
}
