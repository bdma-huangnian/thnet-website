<?php
namespace Addons\ReturnTop;
use Common\Controller\Addon;
/**
 * 返回顶部插件
 * @author corethink
 */
class ReturnTopAddon extends Addon {
    public $info = array(
        'name'=>'ReturnTop',
        'title'=>'返回顶部',
        'description'=>'返回顶部',
        'status'=>1,
        'author'=>'corethink',
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
        $addons_config = $this->getConfig();
        if($addons_config['status']){
            $this->assign('addons_config', $addons_config);
            $this->display($addons_config['theme']);
        }
    }
}
