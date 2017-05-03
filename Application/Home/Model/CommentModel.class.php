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
use User\Api\UserApi;
/**
 * 评论模型
 * @author ijry <skipperprivater@gmail.com>
 */
class CommentModel extends Model {
    /* 自动验证规则 */
    protected $_validate = array(
        array('content', '1,1280', '评论内容长度不多于1280个字符', self::EXISTS_VALIDATE, 'length'),
        array('content', 'require', '评论内容不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('uid', 'is_login', self::MODEL_INSERT, 'function'),
        array('content', 'html2text', self::MODEL_BOTH, 'function'),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', 1, self::MODEL_INSERT, 'string'),
        array('ip', 'get_client_ip', self::MODEL_INSERT, 'function'),
    );

    /**
     * 新增或更新一条评论
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
            $id = $this->add(); //添加行为
            if(!$id){
                $this->error = '新增出错！';
                return false;
            }

            M(C('CONTENT_APP.'.(int)$data['app']))->where(array('id'=> (int)$data['cid']))->setInc('comment'); // 更新评论数

            /* 给作者发送消息 */
            $post_info = M(C('CONTENT_APP.'.(int)$data['app']))->find($data['cid']);
            if((int)is_login() !== (int)$post_info['uid']){
                $new_message['to_uid'] = $post_info['uid'];
                $new_message['title'] = "您发布的内容有新评论啦！";
                $new_message['content'] = '您发布的内容有新评论啦&nbsp;<a href="http://' .C('WEB_SITE_DOMAIN'). '/' .C('CONTENT_APP.'.(int)$data['app']). '/detail/id/'.$data['cid'].'.html">点击查看</a>';
                D('message')->add(D('message')->create($new_message));
            }

            /* 如果是回复发消息通知用户 */
            $pid = (int)$_POST['pid'];
            if( $pid > 0){
                $parent_comment_info = M('comment')->find($pid);
                if((int)$parent_comment_info['uid'] !== (int)$post_info['uid']){ //是否内容作者与评论作者为同一人
                    $new_message['to_uid'] = $parent_comment_info['uid'];
                    $username = get_user_info(is_login(), 'nickname');
                    $new_message['title'] = $username . '回复您啦！';
                    $new_message['content'] = $username . '回复您啦！<br>' . $_POST['content'] . '<br><a href="http://' .C('WEB_SITE_DOMAIN'). '/' .C('CONTENT_APP.'.(int)$data['app']). '/detail/id/'.$data['cid'].'.html">点击查看</a>';
                    D('message')->add(D('message')->create($new_message));
                }
            }

            action_log('submit_comment', 'comment', $id);

        } else { //更新数据
            $status = $this->save(); //更新基础内容
            if(false === $status){
                $this->error = '更新出错！';
                return false;
            }
        }

        //内容添加或更新完成
        return $data;
    }
}
