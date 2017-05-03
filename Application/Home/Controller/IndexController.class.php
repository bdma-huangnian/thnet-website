<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Page;
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController {
    //系统首页
    private $pageSize =10;

    public function index(){
        if(C('WEB_SITE_HOME_PAGE') !== 'Index/index'){
            $this->redirect(C('WEB_SITE_HOME_PAGE'));
        }

        $page = intval(I('page'))?intval(I('page')):0;
        $is_end = 0;

        $conditions = array();
        $conditions['category_id'] = 6;
        $documents = M('Document')->where($conditions)->order('create_time desc')->limit($page*$this->pageSize, $this->pageSize)->select();
        if (count($documents) < $this->pageSize) {
            $is_end = 1;
        }
        $this->assign('current_cateid', 10);

        if (!IS_AJAX){
            $this->assign('documents', $documents);
            $this->assign('is_end', $is_end);
            $this->assign('meta_title', '首页');
            $this->assign('meta_keywords', C('WEB_SITE_KEYWORD'));
            $this->assign('meta_description', C('WEB_SITE_DESCRIPTION'));
            $this->display();
        }else{
            $status = 1;
            if (empty($documents)) {
                $status = 0;
            }

            foreach ($documents as $key => $value) {
                $pics = get_cover($value['cover_id']);
                $documents[$key]['imgurl'] = __ROOT__.$pics['path'];
                $documents[$key]['link'] = U('News/detail').'?id='.$value['id'];
            }
            $this->ajaxReturn(array('status' => $status, 'data' => $documents, 'is_end' => $is_end));
        }
    }

    public function aboutus(){
        $this->assign('current_cateid', 13);
        $this->assign('meta_title', '关于同好');
        $this->assign('meta_keywords', C('WEB_SITE_KEYWORD'));
        $this->assign('meta_description', C('WEB_SITE_DESCRIPTION'));
        $this->display();
    }

    public function cooperation(){
	if(!IS_AJAX){
            $this->assign('current_cateid', 15);
            $this->assign('meta_title', '投资合作');
            $this->assign('meta_keywords', C('WEB_SITE_KEYWORD'));
            $this->assign('meta_description', C('WEB_SITE_DESCRIPTION'));
            $this->display();
	}else{
	    $name = trim(I('name'));
	    $phone = trim(I('phone'));
	    $contact = trim(I('contact'));
	    $message = trim(I('message'));

	    if($name && $phone && $contact && $message){
		$this->ajaxReturn(array('status' => 1));
	    }else{
		$this->ajaxReturn(array('status' => 0));
	    }
	}
    }

    public function offer(){
        $this->assign('current_cateid', 15);
        $this->assign('meta_title', '快速报价');
        $this->assign('meta_keywords', C('WEB_SITE_KEYWORD'));
        $this->assign('meta_description', C('WEB_SITE_DESCRIPTION'));
        $this->display();
    }

    public function index1(){
        $page = intval(I('page'))?intval(I('page')):0;
        $is_end = 0;

        $conditions = array();
        $conditions['category_id'] = 6;
        $documents = M('Document')->where($conditions)->order('create_time desc')->limit($page*$this->pageSize, $this->pageSize)->select();
        if (count($documents) < $this->pageSize) {
            $is_end = 1;
        }

        if (!IS_AJAX){
            $this->assign('documents', $documents);
            $this->assign('is_end', $is_end);
            $this->assign('meta_title', '首页');
            $this->assign('meta_keywords', C('WEB_SITE_KEYWORD'));
            $this->assign('meta_description', C('WEB_SITE_DESCRIPTION'));
            $this->display();
        }else{

            $status = 1;
            if (empty($documents)) {
                $status = 0;
            }

            foreach ($documents as $key => $value) {
                $pics = get_cover($value['cover_id']);
                $documents[$key]['imgurl'] = __ROOT__.$pics['path'];
                $documents[$key]['link'] = U('News/detail').'?id='.$value['id'];
            }
            $this->ajaxReturn(array('status' => $status, 'data' => $documents, 'is_end' => $is_end));
        }
    }

    public function wechatTest(){
        $account = array();
        $account['token'] = "fdasf213213dfasfa";
        $account['key'] = "fdafear321";
        $account['secret'] = "09u3213ifdaf";
        $WechatAccount = new \WechatAccount($account);
        $access_token = $WechatAccount->fetch_token();
        print_r($access_token);
    }

    //搜索
    public function search(){
        if($_GET['key']){
            $key  = (string)I('key');
            $list = D('Document')->page(!empty($_GET["p"])?$_GET["p"]:1, 10)->lists($category = null, $field = true, $order = "`create_time` desc", $status = 1, $uid = false, $pos = false , $key);
            $count = D('Document')->listCount($category, $status = 1, $uid = false, $pos = false, $key);
            $Page = new Page($count,10);
            $show = $Page->show();
            $this->assign('list', $list);
            $this->assign('page',$show);
            $this->assign('meta_title', '搜索');
            $this->assign('meta_keywords', C('WEB_SITE_KEYWORD'));
            $this->assign('meta_description', C('WEB_SITE_DESCRIPTION'));
        }else{
            $this->error("请您输入关键字后搜索");
        }
        $this->display();
    }
}
