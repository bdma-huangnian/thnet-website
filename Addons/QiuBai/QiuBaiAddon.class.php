<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Addons\QiuBai;
use Common\Controller\Addon;
/**
 * 系统环境信息插件
 * @author thinkphp
 */
class QiuBaiAddon extends Addon {
    public $info = array(
        'name' => 'QiuBai',
        'title' => '糗事百科',
        'description' => '读别人的糗事，娱乐自己',
        'status' => 1,
        'author' => 'CoreThink',
        'version' => '0.1'
    );

    public function install() {
        if(!extension_loaded('curl')){
            session('addons_install_error', 'PHP的CURL扩展未开启');
            return false;
        }
        return true;
    }

    public function uninstall() {
        return true;
    }

    //实现的adminIndex钩子方法
    public function adminIndex($param) {
        $config = $this->getConfig();
        $this->assign('addons_config', $config);
        if ($config['display'])
            $this->display('widget');
    }
}
