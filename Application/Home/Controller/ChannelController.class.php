<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Home\Controller;
/**
 * 导航控制器
 */
class ChannelController extends HomeController {
    /* 详情页 */
    public function detail($id = 0){
        /* 标识正确性检测 */
        if(!($id && is_numeric($id))){
            $this->error('导航ID错误！');
        }

        /* 获取详细信息 */
        $Channel = D('Channel');
        $info = $Channel->find($id);
        if(!$info){
            $this->error($Channel->getError());
        }

        /* 模板赋值并渲染模板 */
        $this->assign('meta_title',$info['title']);
        $this->assign('info', $info);
        $this->display();
    }
}
