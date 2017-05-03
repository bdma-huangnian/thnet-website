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
/**
 * 消息管理控制器
 * @author ijry <skipperprivater@gmail.com>
 */
class MessageController extends AdminController {
    /**
     * 消息列表
     * @author ijry <skipperprivater@gmail.com>
     */
    public function index(){
        $title   =   I('title');
        $map['status']  =   array('egt',0);
        if(is_numeric($title)){
            $map['to_uid']       =   array(intval($title),array('like','%'.$title.'%'));
        }else{
            $map['title']    =   array('like', '%'.(string)$title.'%');
        }

        $list   = $this->lists('Message', $map);
        int_to_string($list);
        $this->assign('_list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->meta_title = '用户信息';
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
            case 'forbidmessage':
                $this->forbid('Message', $map );
                break;
            case 'resumemessage':
                $this->resume('Message', $map );
                break;
            case 'deletemessage':
                $this->delete('Message', $map );
                break;
            default:
                $this->error('参数非法');
        }
    }

    /**
     * 新增消息
     * @author ijry <skipperprivater@gmail.com>
     */
    public function add(){
        $this->meta_title = '发送新消息';
        $this->display();
    }

    /**
     * 编辑消息
     * @author ijry <skipperprivater@gmail.com>
     */
    public function edit(){
        $id = I('get.id');
        empty($id) && $this->error('参数不能为空！');
        $data = M('Message')->field(true)->find($id);

        $this->assign('data',$data);
        $this->meta_title = '编辑消息';
        $this->display();
    }

    /**
     * 更新消息
     * @author ijry <skipperprivater@gmail.com>
     */
    public function save(){
        $res = D('Message')->update();
        if(!$res){
            $this->error(D('Message')->getError());
        }else{
            $this->success($res['id']?'更新成功！':'新增成功！', Cookie('__forward__'));
        }
    }
}
