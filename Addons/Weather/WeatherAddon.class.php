<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Addons\Weather;
use Common\Controller\Addon;
/**
 * 天气插件
 * @author cepljxiongjun
 */
class WeatherAddon extends Addon {
    public $info = array(
        'name' => 'Weather',
        'title' => '天气预报',
        'description' => '天气预报',
        'status' => 1,
        'author' => 'CoreThink',
        'version' => '0.1'
    );

    public function install(){
        return true;
    }

    public function uninstall(){
        return true;
    }

    //实现的adminIndex钩子方法
    public function adminIndex(){
        $config = $this->getConfig();
        $this->assign('addons_config', $config);
        foreach($config['showplace'] as $k=>$v){
            if($v == '0' && $config['status'])
                $this->display('widget');
        }
    }
}
