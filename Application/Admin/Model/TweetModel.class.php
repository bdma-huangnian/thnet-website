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
 * 微博模型
 * @author ijry <skipperprivater@gmail.com>
 */
class TweetModel extends Model {
    /* 自动验证规则 */
    protected $_validate = array(
        array('content', 'require', '微博内容不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('content', '1,255', '微博内容最多255个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('uid', 'is_login', self::MODEL_BOTH, 'function'),
        array('content', 'html2text', self::MODEL_BOTH, 'function'),
        array('create_time', 'time', self::MODEL_BOTH, 'function'),
    );

    /**
     * 新增或更新
     * @return boolean fasle 失败 ， int  成功 返回完整的数据
     * @author ijry <skipperprivater@gmail.com>
     */
    public function update(){
        /* 获取数据对象 */
        $data = $this->create($_POST);
        if(empty($data)){
            return false;
        }

        /* 添加或新增消息 */
        if(empty($data['id'])){ //新增数据
            $id = $this->add(); //添加
            if(!$id){
                $this->error = '新增消息出错！';
                return false;
            }
        } else { //更新数据
            $status = $this->save(); //更新基础内容
            if(false === $status){
                $this->error = '更新消息出错！';
                return false;
            }
        }

        //内容添加或更新完成
        return $data;
    }
}
