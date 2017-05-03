<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Addons\Editor;
use Common\Controller\Addon;
/**
 * 编辑器插件
 * @author yangweijie <yangweijiester@gmail.com>
 */
class EditorAddon extends Addon {
    public $info = array(
            'name'=>'Editor',
            'title'=>'前台编辑器',
            'description'=>'用于增强整站长文本的输入和显示',
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

    /**
     * 编辑器挂载的文章内容钩子
     * @param array('name'=>'表单name','value'=>'表单对应的值')
     */
    public function homeArticleEdit($data){
        $this->assign('addons_data', $data);
        $this->assign('addons_config', $this->getConfig());
        $this->display('content');
    }
}
