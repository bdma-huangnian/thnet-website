<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
use Think\Page;
/**
 * 评论控制器
 * @author ijry <skipperprivater@gmail.com>
 */
class CommentController extends HomeController {
    /**
     * 更新评论
     * @author ijry <skipperprivater@gmail.com>
     */
    public function save(){
        $this->login();
        preg_match_all("/([\一-\龥]){1}/u", $_POST['content'], $num);
        if(2 > count($num[0])){
            $this->error('评论至少包含2个中文字符！');
        }
        $res = D('Comment')->update();
        if(!$res){
            $this->error(D('Comment')->getError());
        }else{
            $this->success($res['id']?'更新成功！':'评论成功！', Cookie('__forward__'));
        }
    }
}
