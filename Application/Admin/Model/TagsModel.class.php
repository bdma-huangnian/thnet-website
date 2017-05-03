<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Admin\Model;
use Think\Model;
/**
 * 标签模型
 */
class TagsModel extends Model {
    /* 自动验证规则 */
    protected $_validate = array(
        array('title', 'require', '标签不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,32', '标签长度不能超过32个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT, 'string'),
        array('create_time', 'time', self::MODEL_BOTH, 'function'),
    );

    /**
     * 新增或更新一则标签
     * @return boolean fasle 失败 ， int  成功 返回完整的数据
     * @author ijry <skipperprivater@gmail.com>
     */
    public function update(){
        /* 获取数据对象 */
        $data = $this->create($_POST);
        if(empty($data)){
            return false;
        }

        /* 添加或新增标签 */
        if(empty($data['id'])){ //新增数据
            $id = $this->add(); //添加
            if(!$id){
                $this->error = '新增标签出错！';
                return false;
            }
        } else { //更新数据
            $status = $this->save(); //更新基础内容
            if(false === $status){
                $this->error = '更新标签出错！';
                return false;
            }
        }

        //内容添加或更新完成
        return $data;
    }

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
