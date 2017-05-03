<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Home\Controller;
use User\Api\UserApi;
/**
 * 用户控制器
 * 包括用户中心，用户登录及注册
 */
class UserController extends HomeController {
    /**
     * 用户中心首页
     * @author jry <skipperprivater@gmail.com>
     */
    public function index(){
        $this->redirect('profile');
    }

    /**
     * 注册页面
     * @author jry <skipperprivater@gmail.com>
     */
    public function register($username = '', $password = '', $repassword = '', $email = '', $mobile = '', $verify = ''){
        if(!C('USER_ALLOW_REGISTER')){
            $this->error('注册已关闭');
        }
        if(is_login()){
            $this->error("您已登陆系统");
        }
        if(IS_POST){ //注册用户
            /* 检测验证码 */
            if(C('WEB_SITE_VERIFY_HOME') && !check_verify($verify)){
                $this->error('验证码输入错误！');
            }

            /* 检测敏感用户名 */
            $deny = explode ( ',', C('SENSITIVE_WORDS'));
            foreach ($deny as $k=>$v) {
                if(stristr($username, $v)){
                    $this->error('用户名禁止注册！');
                }
            }

            /* 检测密码 */
            if($password != $repassword){
                $this->error('密码和重复密码不一致！');
            }

            /* 调用注册接口注册用户 */
            $User = new UserApi;
            $uid = $User->register($username, $password, $email, $mobile);
            if(0 < $uid){ //注册成功
                //发送注册成功提醒邮件
                $mail_body = '尊敬的用户，欢迎您注册成为《'.C('WEB_SITE_TITLE').'》用户。
                             在您开始浏览或者发布信息时请您遵守如下服务条款：<br>
                             不得在《'.C('WEB_SITE_TITLE').'》上上传和发布下列违法文件及信息：<br>
                             (1) 反对宪法所确定的基本原则的；<br>
                             (2) 危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；<br>
                             (3) 损害国家荣誉和利益的；<br>
                             (4) 煽动民族仇恨、民族歧视，破坏民族团结的；<br>
                             (5) 破坏国家宗教政策，宣扬邪教和封建迷信的；<br>
                             (6) 散布谣言，扰乱社会秩序，破坏社会稳定的；<br>
                             (7) 散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；<br>
                             (8) 侮辱或者诽谤他人，侵害他人合法权益的；<br>
                             (9) 含有法律、行政法规禁止的其他内容的。<hr>
                             请牢记以下信息：<br>
                             您的用户名是：  '.$username.'<br>
                             您的注册邮箱为：'.$email.'<br>
                             您的注册密码为：'.$password;
                $status = think_send_mail($email, '注册提醒邮件', $mail_body);
                $this->success('注册成功！', U('login'));
            } else { //注册失败，显示错误信息
                $this->error($this->showRegError($uid));
            }
        } else { //显示注册表单
            $this->meta_title = '用户注册';
            $this->display();
        }
    }

    /**
     * 登录页面
     * @author jry <skipperprivater@gmail.com>
     */
    public function login($username = '', $password = '', $verify = ''){
        if(IS_POST){ //登录验证
            /* 检测验证码 */
            if(C('WEB_SITE_VERIFY_HOME') && !check_verify($verify)){
                $this->error('验证码输入错误！');
            }

            /* 调用UC登录接口登录 */
            $user = new UserApi;
            if(preg_match("/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/", $username)){
                $uid = $user->login($username, $password, 2);
            }elseif(preg_match("/1\d{10}$/", $username)){
                $uid = $user->login($username, $password, 3);
            }else{
                $uid = $user->login($username, $password, 1);
            }
            if(0 < $uid){ //UC登录成功
                /* 登录用户 */
                $Member = D('Member');
                if($Member->login($uid)){ //登录用户
                    A('Message')->updateMessageSystemToMessage();
                    $this->success('登录成功！', Cookie('__forward__') ? : U('Home/Index/index'));
                } else {
                    $this->error($Member->getError());
                }

            }else{ //登录失败
                switch($uid) {
                    case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
                    case -2: $error = '密码错误！'; break;
                    default: $error = '未知错误！'; break; // 0-接口参数错误（调试阶段使用）
                }
                $this->error($error);
            }

        }else{ //显示登录表单
            // 记录前一页的cookie
            if(is_login()){
                $this->error("您已登陆系统", U('Home/Index/index'));
            }
            Cookie('__forward__', $_SERVER['HTTP_REFERER']);
            $this->meta_title = '用户登录';
            $this->display();
        }
    }

    /**
     * 退出登录
     * @author jry <skipperprivater@gmail.com>
     */
    public function logout(){
        if(is_login()){
            D('Member')->logout();
            $this->success('退出成功！', U('User/login'));
        } else {
            $this->redirect('User/login');
        }
    }

    /**
     * 验证码，用于登录和注册
     * @author jry <skipperprivater@gmail.com>
     */
    public function verify(){
        $verify = new \Think\Verify();
        $verify->entry(1);
    }

    /**
     * 个人中心
     * @author jry <skipperprivater@gmail.com>
     */
    public function profile(){
        if ( !is_login() ) {
            $this->error( '您还没有登陆',U('User/login') );
        }
        if ( IS_POST ) {
            //获取参数
            $uid              =   is_login();
            $data['sex']      = I('post.sex');
            $data['birthday'] = I('post.birthday');
            $data['qq']       = I('post.qq');
            $data['sex']      === "" && $this->error('请输入性别');
            $data['birthday'] === "" && $this->error('请输入生日');
            $data['qq']       === "" && $this->error('请输入QQ号');
            $member = D('member');
            $res = $member->updateMemberFields($uid, $data);
            if($res){
                $this->success('修改成功！');
            }else{
                $this->error('修改失败！');
            }
        }else{
            $user_info = M('member')->find(is_login());
            $condition['uid'] = is_login();
            $condition['status'] = 1;
            $sync_login = D('sync_login')->where($condition)->getField("type", true);
            /* 调用UC登录接口 */
            $user = new UserApi;
            $ucenter_info = $user->info(is_login());
            $this->meta_title = '个人中心';
            $this->assign('user_info', $user_info);//用户信息
            $this->assign('ucenter_info', $ucenter_info); //UCenter用户信息
            $this->assign('sync_login', $sync_login); //第三方账号绑定信息
            $this->display();
        }
    }

    /**
     * 修改头像
     * @author jry <skipperprivater@gmail.com>
     */
    public function resetAvatar(){
        if ( IS_POST ) {
            //获取参数
            $uid              =   is_login();
            $data['avatar']      = I('post.avatar');
            $data['avatar']       === "" && $this->error('请上传头像');
            $member = D('member');
            $res = $member->updateMemberFields($uid, $data);
            if($res){
                update_user_info_cache($uid);
                $this->success('头像修改成功！');
            }else{
                $this->error('修改失败！');
            }
        }
    }

    /**
     * 修改密码
     * @author jry <skipperprivater@gmail.com>
     */
    public function resetPassword(){
        if ( IS_POST ) {
            //获取参数
            $uid        =   is_login();
            $password   =   I('post.old');
            $repassword = I('post.repassword');
            $data['password'] = I('post.password');
            empty($password) && $this->error('请输入原密码');
            empty($data['password']) && $this->error('请输入新密码');
            empty($repassword) && $this->error('请输入确认密码');
            if($data['password'] !== $repassword){
                $this->error('您输入的新密码与确认密码不一致');
            }
            $Api = new UserApi();
            $res = $Api->updateInfo($uid, $password, $data);
            if($res['status']){
                $this->success('修改密码成功！');
            }else{
                $this->error($this->showRegError($res['info']));
            }
        }else{
            $this->display();
        }
    }

    /**
     * 忘记密码申请重置页面
     * $email[用户注册时的邮箱]
     * $url[修改密码地址]
     * @author jry <skipperprivater@gmail.com>
     */
    public function forgetPassword(){
        if (IS_POST) {
            /* 检测验证码 */
            if(C('WEB_SITE_VERIFY_HOME') && !check_verify(I('post.verify'))){
                $this->error('验证码输入错误！');
            }
            $username = I('post.username');
            $email = I('post.email');
            if(empty($username)){
                $this->error('请输入注册时填写的用户名！');
            }
            if(empty($email)){
                $this->error('请输入注册时填写的邮箱！');
            }
            $User = new UserApi;
            $user_info = $User->info($username, true);
            if($user_info['username'] == $username && $user_info['email'] == $email){
                //重置密码页面地址
                $url = "http://" . $_SERVER ['HTTP_HOST'] . U('User/resetPasswordWithToken',array('id'=>$user_info['id'], 'token'=>$user_info['password']));
                //重置密码链接的邮件
                $mail_body='亲爱的'.$email.'：<br>
                            欢迎使用《'.C('WEB_SITE_TITLE').'》重置密码功能。请您点击此链接来修改您的密码：<a href="'.$url.'">重置密码</a>。<br>
                            如果您并未发过此请求，则可能是因为其他用户在尝试重设密码时误输入了您的电子邮件地址而使您收到这封邮件，
                            那么您可以放心的忽略此邮件，无需进一步采取任何操作。<br>';
                $status = think_send_mail($email, '重置密码邮件', $mail_body);
                if($status){
                    $this->success('重置密码邮件发送成功！');
                }
            }else{
                $this->error('信息填写错误！');
            }
        } else {
            $this->meta_title = '忘记密码';
            $this->display();
        }
    }

    /**
     * 忘记密码申请重置接受
     * @author jry <skipperprivater@gmail.com>
     */
    public function resetPasswordWithToken($id, $token){
        if ( IS_POST ) {
            /* 检测验证码 */
            if(C('WEB_SITE_VERIFY_HOME') && !check_verify(I('post.verify'))){
                $this->error('验证码输入错误！');
            }
            //获取参数
            $repassword = I('post.repassword');
            $data['password'] = I('post.password');
            empty($data['password']) && $this->error('请输入新密码');
            empty($repassword) && $this->error('请输入确认密码');
            if($data['password'] !== $repassword){
                $this->error('您输入的新密码与确认密码不一致');
            }
            $User = new UserApi();
            $res = $User->updateInfo(I('post.id'), I('post.token'), $data, $encrypt_verify = false);
            if($res['status']){
                $this->success('密码重置成功！',U('User/login'));
            }else{
                $this->error($this->showRegError($res['info']));
            }
        }else{
            $this->assign('id',$id);
            $this->assign('token',$token);
            $this->meta_title = '忘记密码';
            $this->display();
        }
    }

    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     * @author jry <skipperprivater@gmail.com>
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
