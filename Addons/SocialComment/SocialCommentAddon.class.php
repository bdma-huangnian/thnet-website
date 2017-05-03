<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Addons\SocialComment;
use Common\Controller\Addon;
/**
 * 通用社交化评论插件
 * @author thinkphp
 */
class SocialCommentAddon extends Addon {
    public $info = array(
        'name'=>'SocialComment',
        'title'=>'通用社交化评论',
        'description'=>'集成了各种社交化评论插件，轻松集成到系统中。',
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
    public function documentDetailAfter($param){
        //检查插件是否开启
        $config = $this->getConfig();
        if($config['status']){
            $this->assign('addons_config', $config);
            $this->display('comment');
        }
    }
}
