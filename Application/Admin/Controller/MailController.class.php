<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
use User\Api\UserApi;
/**
 * 邮件管理控制器
 * @author ijry <skipperprivater@gmail.com>
 */
class MailController extends AdminController {
    /**
     * 邮件列表
     * @author ijry <skipperprivater@gmail.com>
     */
    public function index(){
        $title       =   I('title');
        $map['status']  =   array('egt',0);
        $map['mail_to|title']   =   array(array('like','%'.$title.'%'),array('like','%'.$title.'%'),'_multi'=>true);
        $list   = $this->lists('Mail', $map);
        int_to_string($list);
        $this->assign('_list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->meta_title = '邮件列表';
        $this->display();
    }

    /**
     * 消息状态修改
     * @author ijry <skipperprivater@gmail.com>
     */
    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['id'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'forbidmail':
                $this->forbid('Mail', $map );
                break;
            case 'resumemail':
                $this->resume('Mail', $map );
                break;
            case 'deletemail':
                $this->delete('Mail', $map );
                break;
            default:
                $this->error('参数非法'.$method);
        }
    }

    /**
     * 新增邮件
     * @author ijry <skipperprivater@gmail.com>
     */
    public function add(){
        $this->meta_title = '发送新邮件';
        $this->display();
    }

    /**
     * 编辑邮件
     * @author ijry <skipperprivater@gmail.com>
     */
    public function edit(){
        $id = I('get.id');
        empty($id) && $this->error('参数不能为空！');
        $data = M('Mail')->field(true)->find($id);

        $this->assign('data',$data);
        $this->meta_title = '编辑邮件';
        $this->display();
    }

    /**
     * 更新邮件
     * @author ijry <skipperprivater@gmail.com>
     */
    public function save(){
        if('' == $_POST['mail_to']){
            $_POST['mail_to'] = 0;
        }
        $res = D('Mail')->update();
        if(!$res){
            $this->error(D('Mail')->getError());
        }else{
            $this->success($res['id']?'更新成功！':'新增成功！', Cookie('__forward__'));
        }
    }

    /**
     * 发送测试邮件
     * @author ijry <skipperprivater@gmail.com>
     */
    public function sendTestMail(){
        //发送邮件
        $res=  think_send_mail(C('MAIL_SMTP_TEST'), '测试邮件', '测试邮件正文');
        if (true === $res) {
            $this->success('发送测试邮件成功');
        } else {
            $this->error('发送失败');
        }
    }

    /**
     * 执行发送邮件操作
     * @param $id 邮件ID
     * @author ijry <skipperprivater@gmail.com>
     */
    public function doSendMail($id = 0){
        //获取要发送邮件的信息
        $mail = D('Mail')->find($id);
        $address[0] = $mail['mail_to'];
        if (0 == $address[0]) {
            //获取所有注册用户的邮箱
            $User    =   new UserApi();
            $address = $User->getColumnByfield('email');
        }
        //遍历邮箱发送邮件
        foreach ($address as $k => $v) {
            //发送邮件
            $status=think_send_mail($v, $mail['title'], $mail['body']);
        }
        if($status){
            $this->success('邮件发送成功。');
        }
    }
}
