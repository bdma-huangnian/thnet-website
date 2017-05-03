<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Addons\SystemInfo;
use Common\Controller\Addon;
/**
 * 系统环境信息插件
 * @author thinkphp
 */
class SystemInfoAddon extends Addon {
    public $info = array(
        'name'=>'SystemInfo',
        'title'=>'系统环境信息',
        'description'=>'用于显示一些服务器的信息',
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

        if(extension_loaded('curl')){
            $url = 'https://wx.zjliving.com/version/check';
            $params = array(
                'version' => CORETHINK_VERSION,
                'domain'  => $_SERVER['HTTP_HOST'],
                'auth'    => sha1(C('DATA_AUTH_KEY')),
            );

            $vars = http_build_query($params);
            $opts = array(
                CURLOPT_TIMEOUT        => 5,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL            => $url,
                CURLOPT_POST           => 1,
                CURLOPT_POSTFIELDS     => $vars,
                CURLOPT_USERAGENT      => $_SERVER['HTTP_USER_AGENT'],
            );

            /* 初始化并执行curl请求 */
            $ch = curl_init();
            curl_setopt_array($ch, $opts);
            $data  = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);
        }

        $data = json_decode($data, true);
        if($data['status']){
            $config['new_version'] = $data['version'];
        }

        $this->assign('addons_config', $config);
        if($config['display']){
            $this->display('widget');
        }
    }
}
