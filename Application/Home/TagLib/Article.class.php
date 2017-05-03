<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Home\TagLib;
use Think\Template\TagLib;
/**
 * 文档模型标签库
 */
class Article extends TagLib {
    /**
     * 定义标签列表
     * @var array
     */
    protected $tags   =  array(
        'partlist' => array('attr' => 'id,field,page,name', 'close' => 1), //段落列表
        'partpage' => array('attr' => 'id,listrow', 'close' => 0), //段落分页
        'prev'     => array('attr' => 'name,info', 'close' => 1), //获取上一篇文章信息
        'next'     => array('attr' => 'name,info', 'close' => 1), //获取下一篇文章信息
        'page'     => array('attr' => 'cate,row,child,status,uid,pos', 'close' => 0), //列表分页
        'list'     => array('attr' => 'name,cate,child,page,row,field,order,status,uid,pos', 'close' => 1), //获取文档列表
        'cate'     => array('attr' => 'name,id,field', 'close' => 1), //获取分类
        'recommend' => array('attr' => 'name,cate_id,row,order', 'close' => 1), //获取推荐内容
    );

    /*右侧推荐内容*/
    public function _recommend($tag, $content) {
        $name = $tag['name'];
        $cate_id = empty($tag['cate_id']) ? 'null' : $tag['cate_id'];
        $row = empty($tag['row']) ? '10' : $tag['row'];
        $order  = empty($tag['order']) ? '\'`create_time` DESC\'' : $tag['order'];
        $parse = '<?php ';
        $parse .= '$__RESULT__ = M(\'Document\')->where(array(\'category_id\' => '. $cate_id .'))->limit('.$row.')->order(';
        $parse .= $order.')->select();';
        $parse .= '?>';
        $parse .= '<volist name="__RESULT__" id="'. $name .'">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    }

   /* 分类数据列表 */
    public function _cate($tag, $content){
        $name   = $tag['name'];
        $cate   = empty($tag['id']) ? 'null' : $tag['id'];
        $field  = empty($tag['field']) ? 'true' : $tag['field'];
        $parse  = '<?php ';
        $parse .= '$__CATE__='.$cate.';';
        $parse .= '$__LIST__ = D(\'Category\')->getSameLevel($__CATE__,';
        $parse .= $field . ', true);';
        $parse .= ' ?>';
        $parse .= '<volist name="__LIST__" id="'. $name .'">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    }

    /* 文档数据列表 */
    public function _list($tag, $content){
        $name   = $tag['name'];
        $cate   = empty($tag['cate']) ? 'null' : $tag['cate'];
        $child  = empty($tag['child']) ? 'false' : $tag['child'];
        $row    = empty($tag['row'])   ? '10' : $tag['row'];
        $field  = empty($tag['field']) ? 'true' : $tag['field'];
        $order  = empty($tag['order']) ? '\'`level` DESC,`create_time` DESC\'' : $tag['order'];
        $status = empty($tag['status']) ? '1' : $tag['status'];
        $uid    = empty($tag['uid']) ? 'false' : $tag['uid'];
        $pos    = empty($tag['pos']) ? 'false' : $tag['pos'];

        $parse  = '<?php ';
        if('true' === $child){
            $parse .= '$__CATE__=D(\'Category\')->getChildrenId('.$cate.');';
        }else{
            $parse .= '$__CATE__='.$cate.';';
        }
        $parse .= '$__LIST__ = D(\'Document\')->page(!empty($_GET["p"])?$_GET["p"]:1,'.$row.')->lists($__CATE__,';
        $parse .= $field . ',';
        $parse .= $order . ',';
        $parse .= $status . ',';
        $parse .= $uid . ',';
        $parse .= $pos . ');';
        $parse .= ' ?>';
        $parse .= '<volist name="__LIST__" id="'. $name .'">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    }

    /* 列表数据分页 */
    public function _page($tag){
        $cate   = empty($tag['cate']) ? 'null' : $tag['cate'];
        $row    = empty($tag['row'])   ? '10' : $tag['row'];
        $child  = empty($tag['child']) ? 'false' : $tag['child'];
        $status = empty($tag['status']) ? '1' : $tag['status'];
        $uid    = empty($tag['uid']) ? 'true' : $tag['uid'];
        $pos    = empty($tag['pos']) ? 'false' : $tag['pos'];
        $parse   = '<?php ';
        if('true' === $child){
            $parse .= '$__CATE__=D(\'Category\')->getChildrenId('.$cate.');';
        }else{
            $parse .= '$__CATE__='.$cate.';';
        }
        $parse  .= '$__PAGE__ = new \Think\Page(get_list_count($__CATE__,';
        $parse  .= $status . ',';
        $parse  .= $uid . ',';
        $parse  .= $pos .'),';
        $parse  .= $row . ');';
        $parse  .= 'echo $__PAGE__->show();';
        $parse  .= ' ?>';
        return $parse;
    }

    /* 获取下一篇文章信息 */
    public function _next($tag, $content){
        $name   = $tag['name'];
        $info   = $tag['info'];
        $parse  = '<?php ';
        $parse .= '$' . $name . ' = D(\'Document\')->next($' . $info . ');';
        $parse .= ' ?>';
        $parse .= '<notempty name="' . $name . '">';
        $parse .= $content;
        $parse .= '</notempty>';
        return $parse;
    }

    /* 获取上一篇文章信息 */
    public function _prev($tag, $content){
        $name   = $tag['name'];
        $info   = $tag['info'];
        $parse  = '<?php ';
        $parse .= '$' . $name . ' = D(\'Document\')->prev($' . $info . ');';
        $parse .= ' ?>';
        $parse .= '<notempty name="' . $name . '">';
        $parse .= $content;
        $parse .= '</notempty>';
        return $parse;
    }

    /* 段落数据分页 */
    public function _partpage($tag){
        $id      = $tag['id'];
        if ( isset($tag['listrow']) ) {
            $listrow = $tag['listrow'];
        }else{
            $listrow = 10;
        }
        $parse   = '<?php ';
        $parse  .= '$__PAGE__ = new \Think\Page(get_part_count(' . $id . '), ' . $listrow . ');';
        $parse  .= 'echo $__PAGE__->show();';
        $parse  .= ' ?>';
        return $parse;
    }

    /* 段落列表 */
    public function _partlist($tag, $content){
        $id     = $tag['id'];
        $field  = $tag['field'];
        $name   = $tag['name'];
        if ( isset($tag['listrow']) ) {
            $listrow = $tag['listrow'];
        }else{
            $listrow = 10;
        }
        $parse  = '<?php ';
        $parse .= '$__PARTLIST__ = D(\'Document\')->part(' . $id . ',  !empty($_GET["p"])?$_GET["p"]:1, \'' . $field . '\','. $listrow .');';
        $parse .= ' ?>';
        $parse .= '<?php $page=(!empty($_GET["p"])?$_GET["p"]:1)-1; ?>';
        $parse .= '<volist name="__PARTLIST__" id="'. $name .'">';
        $parse .= $content;
        $parse .= '</volist>';
        return $parse;
    }
}
