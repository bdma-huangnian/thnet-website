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
 * 前台标签控制器
 * @author ijry <skipperprivater@gmail.com>
 */
class TagsController extends HomeController {
    /**
     * 搜索相关标签
     * @author ijry <skipperprivater@gmail.com>
     */
    public function tags(){
        $tags =  D('Tags')->searchTags($_GET['q']);
        $data = array();
        foreach($tags as $value){
            $data[] = array('id' => $value['title'], 'title'=> $value['title']);
        }
        echo json_encode($data);
    }
}
