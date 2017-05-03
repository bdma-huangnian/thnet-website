<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Addons\DevTeam;
use Common\Controller\Addon;
/**
 * 开发团队信息插件
 * @author thinkphp
 */
class DevTeamAddon extends Addon {
    public $info = array(
        'name'=>'DevTeam',
        'title'=>'开发团队信息',
        'description'=>'开发团队成员信息',
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
        if($config['display'])
            $this->display('widget');
    }
}
