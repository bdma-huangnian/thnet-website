<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Home\Model;
use Think\Model;
/**
 * 标签模型
 */
class TagsModel extends Model {
    /**
     * 搜索相关标签
     *@ param string 搜索关键字
     * @return array 相关标签
     * @author ijry <skipperprivater@gmail.com>
     */
    public function searchTags($keyword){
        $map["title"] = array("like", "%".$keyword."%");
        $tags = $this->field('id,title')->where($map)->select();
        return $tags;
    }

    /**
     * 根据ID将某文档拥有的原标签计数减一
     * @param  string  $orignal_tags   原标签
     * @author ijry <skipperprivater@gmail.com>
     */
    public function setDecByTags($orignal_tags = array()){
        if($orignal_tags){
            $this->where(array('title'=>array('in', $orignal_tags)))->setDec('count');
            return true;
        }else{
            return false;
        }
    }

    /**
     * 更新文档标签后更新标签表
     * @param  array  $tags   标签数据
     * @return array 标签数据
     * @author ijry <skipperprivater@gmail.com>
     */
    public function updateTags($tags = array()){
        foreach ($tags as $value) {
            $map['title'] = $value;
            if($this->where($map)->find()){
                $this->where($map)->setInc('count');
            }else{
               $this->add(array('title'=>$value, 'description'=>$value, 'create_time'=>NOW_TIME,'count'=>1));
            }
        }
        return $tags;
    }
}
