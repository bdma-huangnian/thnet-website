<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Install\Controller;
use Think\Controller;
use Think\Storage;
/**
 * 默认控制器
 */
class IndexController extends Controller {
    //安装首页
    public function index(){
        $this->display();
    }

    //安装完成
    public function complete(){
        $step = session('step');
        if(!$step){
            $this->error('请正确安装系统', U('index'));
        } elseif($step != 3) {
            $this->error('请正确安装系统', U('Install/step'.$step));
        }
        // 写入安装锁定文件
        Storage::put(MODULE_PATH . 'Data/install.lock', 'lock');
        if(!session('update')){
            //创建配置文件
            $this->assign('info',session('config_file'));
        }
        session('step', null);
        session('error', null);
        $this->display();
    }
}
