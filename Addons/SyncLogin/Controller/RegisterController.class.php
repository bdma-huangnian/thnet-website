<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------

/**
 *@第三方帐号集成  注册绑定 模块
 *@author: jry
 */
namespace Addons\SyncLogin\Controller;
use Home\Controller\AddonsController;
use User\Api\UserApi as UserApi;

class RegisterController extends AddonsController {

    /**
     * 第三方帐号集成 - 绑定本地帐号
     * @return void
     */
    public function dobind(){
        $email = $_POST['email'];
        $password = trim($_POST['password']);
        //根据邮箱地址和密码判断是否存在该用户
        $user = new UserApi;
        if(preg_match("/^[0-9a-zA-Z]+@(([0-9a-zA-Z]+)[.])+[a-z]{2,4}$/i",$email )) {
               $uid = $user->login($email, $password, 2);
        }

        if($uid > 0 ) {
            //注册来源-第三方帐号绑定
            if(isset($_POST)){
                //查找曾经绑定过后来取消绑定的记录
                $other['uid'] = $uid;
                $other['type'] = $_POST['other_type'];
                $other['openid'] = $_POST['other_openid'];
                $other['status'] = 0;
                $user_info_sync_login = D('sync_login')->where($other)->find(); // 根据openid等参数查找同步登录表中的用户信息
                if($user_info_sync_login){
                    $syncdata['access_token'] = $token['access_token'];
                    $syncdata['refresh_token'] = $token['refresh_token'];
                    $syncdata['status'] = 1;
                    $ret = D('sync_login')->where($other)->save($syncdata);
                }else{
                    $other['access_token'] = $_POST['other_access_token'];
                    $other['refresh_token'] = $_POST['other_refresh_token'];
                    $other['status'] = 1;
                    $ret = D('sync_login')->add($other);
                }
                if(!$ret){
                    $this->error('绑定失败');
                }
            }else{
                $this->error('绑定失败，第三方信息不正确');
            }
            /* 登录用户 */
            $Member = D('Member');
            if($Member->login($uid)){
                $this->assign('jumpUrl', U('Home/Index/index'));
                $this->success('恭喜您，绑定成功');
            }else{
                $this->error($Member->getError());
            }
            return ;
        }else{
            $this->error('绑定失败，请确认帐号密码正确'); // 注册失败
        }
    }

    /**
     * 第三方帐号集成 - 取消绑定本地帐号
     * @return void
     */
    public function cancelbind(){
        $condition['uid'] = 1;
        $condition['type'] = $_GET['type'];
        $ret = D('sync_login')->where($condition)->delete();
        if($ret){
            $this->success('取消绑定成功');
        }else{
            $this->error('取消绑定失败');
        }
    }

    /**
     * 第三方帐号集成 - 注册新账号
     * @return void
     */
    public function doregister(){
        $email = $_POST['email'];
        $username = $_POST['uname'];
        $password = $_POST['password'];
        $repassword = $_POST['repassword'];
        $mobile = $_POST['mobile'];
        //验证昵称
        if(empty($username)){
            $this->error('请输入昵称！');
        }
        if(strlen($username) < 3){
            $this->error('昵称长度不能小于3个字符');
        }

        $User = new UserApi;

        //验证邮箱格式
        if(!preg_match("/^[0-9a-zA-Z]+@(([0-9a-zA-Z]+)[.])+[a-z]{2,4}$/i", $email)){
            $this->error('您输入的邮箱地址格式不对！');
        }

        /* 检测密码 */
        if($password != $repassword){
            $this->error('密码和重复密码不一致！');
        }

        $uid   =   $User->register($username, $password, $email, $mobile);
        if(0 < $uid){
            $user = array('uid' => $uid, 'nickname' => $username, 'status' => 1);
            if(!M('Member')->add($user)){
                $this->error('用户添加失败！');
            } else {
                // 注册来源-第三方帐号绑定
                if(isset($_POST['other_type'])){
                    $other['uid'] = $uid;
                    $other['type'] = $_POST['other_type'];
                    $other['openid'] = $_POST['other_openid'];
                    $other['access_token'] = $_POST['other_access_token'];
                    $other['refresh_token'] = $_POST['other_refresh_token'];
                    $other['status'] = 1;
                    D('sync_login')->add($other);
                }
                /* 登录用户 */
                $Member = D('Member');
                if($Member->login($uid)){
                    $this->assign('jumpUrl', U('Home/Index/index'));
                    $this->success('恭喜您，注册成功');
                }else{
                    $this->error($Member->getError());
                }
            }
        }else{
            $this->error($this->showRegError($uid)); //注册失败
        }
    }

    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     */
    private function showRegError($code = 0){
        switch ($code) {
            case -1:  $error = '用户名长度必须在16个字符以内！'; break;
            case -2:  $error = '用户名被禁止注册！'; break;
            case -3:  $error = '用户名被占用！'; break;
            case -4:  $error = '注册太频繁请稍后再试'; break;
            case -5:  $error = '用户名只可含有汉字、数字、字母、下划线且不以下划线开头结尾，不以数字开头！'; break;
            case -6:  $error = '密码长度必须在6-30个字符之间！'; break;
            case -7:  $error = '密码至少由数字、字符、特殊字符三种中的两种组成！'; break;
            case -8:  $error = '邮箱格式不正确！'; break;
            case -9:  $error = '邮箱长度必须在1-32个字符之间！'; break;
            case -10: $error = '邮箱被占用！'; break;
            case -11: $error = '手机号格式不正确！'; break;
            case -12: $error = '手机号被占用！'; break;
            default:  $error = '未知错误';
        }
        return $error;
    }

}
