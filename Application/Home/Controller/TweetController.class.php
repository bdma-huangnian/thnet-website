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
 * 微博控制器
 * @author ijry <skipperprivater@gmail.com>
 */
class TweetController extends HomeController {
    /**
     * 微博
     * @author jry <skipperprivater@gmail.com>
     */
    public function index($order = "create_time desc"){
        $tweets = M('tweet')->page(!empty($_GET["p"])?$_GET["p"]:1, 20)->where(array('status' => 1))->order($order)->select();
        $count = M('Tweet')->where(array('status' => 1))->count();
        $Page = new Page($count,20);
        $show = $Page->show();
        $this->assign('uid', is_login());
        $this->assign('page',$show);
        $this->assign('tweets',$tweets);
        $this->meta_title = '微博';
        $this->assign('meta_keywords', C('WEB_SITE_KEYWORD'));
        $this->assign('meta_description', C('WEB_SITE_DESCRIPTION'));
        $this->display();
    }

    /**
     * 用户空间
     * @author jry <skipperprivater@gmail.com>
     */
    public function space($uid = 0){
        if(!$uid){
           $uid = is_login();
           if(!$uid){
               $this->login();
           }
        }
        $user_info = M('Member')->find($uid);
        $tweets = M('tweet')->page(!empty($_GET["p"])?$_GET["p"]:1, 20)->where(array('uid' => $uid, 'status' => 1))->order('create_time desc')->select();
        $count = M('Tweet')->where(array('uid' => $uid, 'status' => 1))->count();
        $Page = new Page($count,20);
        $show = $Page->show();
        $this->assign('user_id',$uid);
        $this->assign('page',$show);
        $this->assign('tweets',$tweets);
        $this->meta_title = get_user_info($uid, 'nickname').'的空间';
        $this->assign('meta_keywords', C('WEB_SITE_KEYWORD'));
        $this->assign('meta_description', C('WEB_SITE_DESCRIPTION'));
        $this->display();
    }

    /**
     * 更新消息
     * @author ijry <skipperprivater@gmail.com>
     */
    public function save(){
        $res = D('Tweet')->update();
        if(!$res){
            $this->error(D('Tweet')->getError());
        }else{
            $this->success($res['id']?'更新成功！':'发布成功！');
        }
    }

    /**
     * 阅读
     * @param $id ID
     * @return array
     * @author jry <skipperprivater@gmail.com>
     */
    public function detail($id){
        $Tweet = D('Tweet');
        $tweet_info = D('Tweet')->find((int)$id);

        /* 更新浏览数 */
        $map = array('id' => $id);
        $Tweet->where($map)->setInc('view');

        /* 获取评论 */
        $map = array();
        $map['app'] = 1;
        $map['cid'] = (int)$id;
        $map['status'] = 1;
        $comments = D('Comment')->where($map)->order('create_time asc')->select();

        $this->assign('comments',$comments);
        $this->assign('info', $tweet_info);
        $this->assign('comment_app', 1);
        $this->assign('meta_title', '微博');
        $this->assign('meta_keywords', C('WEB_SITE_KEYWORD'));
        $this->assign('meta_description', $tweet['content']);
        $this->display();
    }
}
