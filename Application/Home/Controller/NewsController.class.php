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
* 
*/
class NewsController extends HomeController{
	
	public function index(){
		# code...
	}

	public function lists() {
		$cate_id = intval(I('cate_id'));
		$cate_id = empty($cate_id) ? 1 : $cate_id;

		$this->assign('cate_id', $cate_id);
		$this->assign('meta_keywords', C('WEB_SITE_KEYWORD'));
        $this->assign('meta_description', C('WEB_SITE_DESCRIPTION'));

        if ($cate_id === 6) {
        	$this->assign('current_cateid', 11);
        	$this->assign('meta_title', '公司产品');
			$this->display('News/products');
        }else{
        	$this->assign('current_cateid', 14);
        	$this->assign('meta_title', '新闻资讯');
			$this->display();
        }
	}

	public function detail(){
		$id = intval(I('id'));
		$type = intval(I('type'));

		if ($type === 1) {
			$cate_id = 11;
		}elseif ($type === 2) {
			$cate_id = 14;
		}
		$documents = D('Document');
		$result = $documents->detail($id);
		if ($result) {
			$prev = $documents->prev($result);
			$next = $documents->next($result);
		}

		$this->assign('current_cateid', $cate_id);
		$this->assign('document_detail', $result);
		$this->assign('prev', $prev);
		$this->assign('next', $next);
		$this->assign('meta_title', $result['title']);
        $this->assign('meta_keywords', C('WEB_SITE_KEYWORD'));
        $this->assign('meta_description', C('WEB_SITE_DESCRIPTION'));
		$this->display();
	}
}


?>