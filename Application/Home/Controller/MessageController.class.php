<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
use Think\Page;
/**
 * 消息管理控制器
 * @author ijry <skipperprivater@gmail.com>
 */
class MessageController extends HomeController {
    /**消息页面
     * @param int    $page
     * @param string $tab 当前tab
     */
    public function index($p = 1, $tab = 'unread'){
        $this->login();
        $this->updateMessageSystemToMessage();
        $map = $this->getMapByTab($tab, $map);
        $map['to_uid'] = is_login();
        $map['status'] = 1;
        $nowPage = isset($_GET['p'])?$_GET['p']:1;
        $messages   = D('Message')->where($map)->order('create_time desc')->page($nowPage , 10)->select();
        $totalCount = D('Message')->where($map)->count(); //用于分页
        $Page       = new Page($totalCount,10);// 实例化分页类 传入总记录数
        $show       = $Page->show();// 分页显示输出
        $this->assign('messages', $messages);
        $this->assign('page', $show);// 赋值分页输出
        $this->assign('tab', $tab);
        $this->assign('user_info', M('member')->find(is_login()));//用户信息
        $this->assign('meta_title', '消息中心');
        $this->display();
    }

    /**
     * 阅读一则消息
     * @param $id 消息ID
     * @return array
     */
    public function detail($id){
        $this->login();
        $message = D('message')->find((int)$id);
        $ret = D('message')->where(array('id' => $id))->setField('is_read', 1);
        $this->assign('message', $message);
        $this->assign('meta_title', $message['title']);
        $this->display();
    }

    /**
     * 根据TAB获取查询条件
     * @param $tab
     * @param $map
     * @return mixed
     */
    private function getMapByTab($tab, $map){
        switch ($tab) {
            case 'system':
                $map['type'] = 0;
                break;
            case 'user':
                $map['type'] = 1;
                break;
            case 'app':
                $map['type'] = 2;
                break;
            case 'all':
                break;
            default:
                $map['is_read'] = 0;
                break;
        }
        return $map;
    }

    /**
     * 查询发给所有用户的新系统消息并添加进用户的消息列表
     * @return bool
     */
    public function updateMessageSystemToMessage(){
        $user_info = get_user_info(is_login());
        $map['create_time'] = array('gt', $user_info['reg_time']);
        $map['status'] = 1;
        $message_system = D('MessageSystem')->field("id,title,content,create_time")->where($map)->select();
        if($message_system){
            foreach($message_system as $v){
                $condition['to_uid'] = is_login();
                $condition['ms_id'] = $v['id'];
                if(!D('Message')->where($condition)->select()){
                    $v['to_uid'] = is_login();
                    $v['ms_id'] = $v['id'];
                    unset($v['id']);
                    if(D('Message')->add($v)){
                        D('MessageSystem')->where(array('id'=>$v['ms_id']))->setInc('count');
                    }else{
                        $this->error("系统消息获取错误");
                    }
                }
            }
        }
        return true;
    }
}
