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
 * 文档模型控制器
 * 文档模型列表和详情
 */
class ArticleController extends HomeController {
    /* 文档模型频道页 */
    public function index(){
        /* 分类信息 */
        $category = $this->category();

        //频道页只显示模板，默认不读取任何内容
        //内容可以通过模板标签自行定制

        /* 模板赋值并渲染模板 */
        $this->assign('meta_title',$category['title']);
        $this->assign('rightNav', get_parent_category($category['id']));
        $this->assign('current_category', $category);
        $this->display($category['template_index']);
    }

    /* 文档模型列表页 */
    public function lists($p = 1){
        /* 分类信息 */
        $category = $this->category();
        /* 获取当前分类列表 */
        $Document = D('Document');
        /* 如果当前分类仅有一篇文章且标题与分类标题相同 */
        $list = $Document->lists($category['id']);
        if(1 === sizeof($list) && $category['title'] === $list[0]['title']){
            $this->redirect('detail', array('id' => $list[0]['id']));
        }

        /* 模板赋值并渲染模板 */
        $this->assign('meta_title',$category['title']);
        $this->assign('meta_keywords',$category['keywords']);
        $this->assign('meta_description',$category['description']);
        $this->assign('rightNav', get_parent_category($category['id']));
        $this->assign('current_category', $category);
        $this->display($category['template_lists']);
    }

    /* 文档模型详情页 */
    public function detail($id = 0, $p = 1){
        /* 标识正确性检测 */
        if(!($id && is_numeric($id))){
            $this->error('文档ID错误！');
        }

        /* 页码检测 */
        $p = intval($p);
        $p = empty($p) ? 1 : $p;

        /* 获取详细信息 */
        $Document = D('Document');
        $info = $Document->detail($id);
        if(!$info){
            $this->error($Document->getError());
        }
        if(0 < $info['link_id']){
            redirect(get_link($info['link_id']));
        }

        /* 分类信息 */
        $category = $this->category($info['category_id']);

        /* 获取模板 */
        if(!empty($info['template'])){//已定制模板
            $tmpl = $info['template'];
        } elseif (!empty($category['template_detail'])){ //分类已定制模板
            $tmpl = $category['template_detail'];
        } else { //使用默认模板
            $tmpl = 'Article/'. get_document_model($info['model_id'],'name') .'/detail';
        }

        /* 更新浏览数 */
        $map = array('id' => $id);
        $Document->where($map)->setInc('view');

        /* 获取评论 */
        $map = array();
        $map['app'] = 0;
        $map['cid'] = (int)$id;
        $map['status'] = 1;
        $comments = D('Comment')->where($map)->order('create_time asc')->select();
        $this->assign('comments',$comments);

        /* 获取投票 */
        $map['app'] = 0;
        $map['cid'] = $id;
        $digg_info = D('Digg')->where($map)->find();
        $digg_good = explode(',', $digg_info['good']);
        $digg_bad = explode(',', $digg_info['bad']);
        $digg_bookmark = explode(',', $digg_info['bookmark']);
        if(in_array(session('user_auth.uid'), $digg_good)){
            $this->assign('digg_good', true);
        }
        if(in_array(session('user_auth.uid'), $digg_bad)){
            $this->assign('digg_bad', true);
        }
        if(in_array(session('user_auth.uid'), $digg_bookmark)){
            $this->assign('digg_bookmark', true);
        }

        if($info['tags']){
            $info['tags'] = explode(',', $info['tags']);
        }

        /* 模板赋值并渲染模板 */
        $this->assign('meta_title',$info['title']);
        $this->assign('meta_keywords',$info['tags']);
        $this->assign('meta_description',$info['description']);
        $this->assign('rightNav', get_parent_category($category['id']));
        $this->assign('current_category', $category);
        $this->assign('info', $info);
        $this->assign('page', $p); //页码
        $this->assign('comment_app', 0);
        $this->display($tmpl);
    }

    /* 文档分类检测 */
    private function category($id = 0){
        /* 标识正确性检测 */
        $id = $id ? $id : I('get.category', 0);
        if(empty($id)){
            $this->error('没有指定文档分类！');
        }

        /* 获取分类信息 */
        $category = D('Category')->info($id);
        if($category && 1 == $category['status']){
            switch ($category['display']) {
                case 0:
                    $this->error('该分类禁止显示！');
                    break;
                default:
                    return $category;
            }
        } else {
            $this->error('分类不存在或被禁用！');
        }
    }

    /* 发布文档 */
    public function update(){
        if(IS_POST){
            //标签信息更新
            $tags = D('Tags');
            if($_POST['id']){
                $orignal_tags = D('Document')->getFieldById($_POST['id'], 'tags');
                if($orignal_tags && !$tags->setDecByTags($orignal_tags)){ // 更新时候先将原有标签统计减去一
                    $this->error("标签Count更新错误");
                }
            }
            if($_POST["tags"]){
                $tags->updateTags(explode(',', $_POST["tags"])); // 更新标签信息
            }
            //文档信息更新
            $document   =   D('Document');
            $res = $document->update();
            if(!$res){
                $this->error($document->getError());
            }else{
                $this->success($res['id']?'更新成功':'新增成功', U('/'));
            }
        }else{
            $this->login();
            /* 获取所有分类 */
            $map  = array('status' => 1, 'display' => 1);
            $list = D('Category')->field('id,title,sort,pid,allow_publish,status')->where($map)->order('sort')->select();
            $list = $this->tree($list);
            $this->meta_title = '发布文档';
            $this->assign('tree', $list);
            $this->assign('user_info', M('member')->find(is_login()));//用户信息
            $this->display('Article/article/add');
        }
    }

    /* 递归解析分类 */
    public function tree($list,$pid=0,$level=0,$html='—'){
        static $tree = array();
        foreach($list as $v){
            if($v['pid'] == $pid){
                $v['sort'] = $level;
                $v['title'] = str_repeat($html,$level).$v['title'];
                $tree[] = $v;
                $this->tree($list,$v['id'],$level+1);
            }
        }
        return $tree;
    }

    /* 我的文档 */
    public function mydocument(){
        $this->meta_title = '我的文档';
        $this->assign('user_info', M('member')->find(is_login()));//用户信息
        $this->display('User/mydocument');
    }

    /* 我的收藏 */
    public function mybookmark(){
        $condition['app'] = 0;
        $digg = M('Digg')->where($condition)->select();
        foreach($digg as $v){
            if( in_array(is_login(),json_decode($v['bookmark'], true))){
                $bookmark[] = $v['cid'];
            }
        }
        if($bookmark){
            $map['id'] = array('in', array_values($bookmark));
            $map['status'] = 1;
            $document_list = D('document')->page(!empty($_GET["p"])?$_GET["p"]:1, 10)->where($map)->order('`create_time` desc')->select();
            $count = D('document')->where($map)->count();
            $Page = new Page($count,10);
            $show = $Page->show();
            $this->meta_title = '我的收藏';
            $this->assign('page',$show);
            $this->assign('document_list', $document_list);
            $this->assign('user_info', M('member')->find(is_login()));//用户信息
        }
        $this->display('User/mybookmark');
    }
}
