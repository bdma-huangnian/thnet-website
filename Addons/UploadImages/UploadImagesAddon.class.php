<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Addons\UploadImages;
use Common\Controller\Addon;
/**
 * 图片批量上传插件
 * @author yidian
 */
class UploadImagesAddon extends Addon {
    public $info = array(
        'name' => 'UploadImages',
        'title' => '多图上传',
        'description' => '多图上传',
        'status' => 1,
        'author' => 'CoreThink',
        'version' => '1.1'
    );

    public function install(){
        /* 先判断插件需要的钩子是否存在 */
        $this->existHook($this->info['name'], $this->info['name'], $this->info['description']);
        return true;
    }

    public function uninstall(){
        //删除钩子
        $this->deleteHook($this->info['name']);
        return true;
    }

    //实现的UploadImages钩子方法
    public function UploadImages($param){
        $name = $param['name'] ?: 'pics';
        $valArr = $param['value'] ? explode(',', $param['value']) : array();
        $this->assign('name',$name);
        $this->assign('valStr',$param['value']);
        $this->assign('valArr',$valArr);
        $this->display('upload');
    }
}
