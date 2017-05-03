<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        if(IS_GET){
            $Code = strval($_GET['Code']);
            $this->assign('list',array('Code'=>$Code));
            $this->display();die;
        }
        if(IS_POST){
            $phone = strval($_POST['Phone']);
            $code = intval($_POST['Code']);
            $pwd = strval($_POST['Password']);
            $QRCode = strval($_POST['QRCode']);//二维码后的邀请码  $_GET['Code'];
            $session_code = session('phone' . $phone);
            if ($session_code != $code) {
                $this->assign('list',array('Code'=>$QRCode));
                $this->assign('error','验证码错误');
                $this->display();die;
            }
            if (empty($phone)) {
                $this->assign('list',array('Code'=>$QRCode));
                $this->assign('error','手机号码不能为空');
                $this->display();die;
            }
            if (strlen($pwd) < 6) {
                $this->assign('list',array('Code'=>$QRCode));
                $this->assign('error','密码长度不能小于6位');
                $this->display();die;
            } else {
                $password = md5($pwd);
            }
            $db_user = M('User');
            if ($db_user->where("Phone = $phone")->getField('ID')) {
                $this->assign('list',array('Code'=>$QRCode));
                $this->assign('error','该账号已存在');
                $this->display();die;
            }
            $UserCode = $this->UserCode();//邀请码
            $UserQRcode = $this->QRCode('http://114.55.176.237', $UserCode);//邀请二维码
            $db_user->Password = $password;
            $db_user->Phone = $phone;
            $db_user->Role = 0;
            $db_user->QRCode = $UserQRcode;
            $db_user->Code = $UserCode;//用户二维邀请码
            $db_user->AddTime = time();
            $res = $db_user->add();
            if ($res) {
                //注册成功消息通知
                $this->mation($res, 1, 0, 0, 0);
                $UserPID = $db_user->where("Code = $QRCode")->getField('ID');//查询二维码所属用户id
                M('agent')->add(array('UserID' => $res, 'UserPID' => $UserPID, 'AddTime' => time()));//分销关系添加
                redirect('http://114.55.176.237/download');
            } else {
                $this->assign('list',array('Code'=>$QRCode));
                $this->assign('error','网络错误');
                $this->display();die;
            }
        }
    }

    //生成用户邀请码
    public function UserCode()
    {
        return md5(time() . uniqid());
    }

    /**二维码生成器
     * @$Url            二维码链接地址
     * @$UserCode       用户邀请码
     */
    public function QRCode($Url, $UserCode)
    {
        //import('Vendor.phpqrcode.phpqrcode');
        Vendor('phpqrcode.phpqrcode');
        $data = $Url . '?Code=' . $UserCode;
        // 纠错级别：L、M、Q、H
        $level = 'H';
        // 点的大小：1到10,用于手机端4就可以了
        $size = 4;
        // 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
        $path = '/Files/image/' . date('Y') . '-' . date('m') . '-' . date('d');
        if (!is_dir('.' . $path)) {
            mkdir('.' . $path, 0777, true);
        }
        // 生成的文件名
        $fileName = $path . '/' . uniqid() . '.png';
        $QRcode = new \QRcode();
        //$QRcode->png($data, false, $level, $size);
        $QRcode->png($data, '.' . $fileName, $level, $size);
        return $fileName;
    }

    /**消息保存
     * @$user_id    消息所属用户id
     * @$type       消息类型1注册欢迎语，2发货通知，3付款提醒，4收货提醒，5评价提醒
     * @$title      消息标题   由模版获得
     * @$icon       消息图标   由模版获得
     * @$content    消息内容   由模版获得
     * @$order_id   订单id
     * @$comment_id 评论id
     * $waybill     物流信息
     * */
    public function mation($user_id = '', $type = '', $order_id = '', $comment_id = '', $waybill = '')
    {
        $db_user = M('user');
        $nick_name = $db_user->where("id= $user_id")->getField('nick_name');
        $db_order = M('order');
        $order_number = $db_order->where("id = $order_id")->getField('order_number');
        $list = $this->template($type);
        $mation_title = str_replace('$order_number', "$order_number", str_replace('$nick_name', "$nick_name", $list['title']));
        $mation_content = str_replace('$order_number', "$order_number", str_replace('$nick_name', "$nick_name", $list['content']));
        $db_mation = M('mation');
        $db_mation->user_id = $user_id;
        $db_mation->title = $mation_title;
        $db_mation->icon = $list['icon'];
        $db_mation->content = $mation_content;
        $db_mation->type = $type;
        $db_mation->order_id = $order_id;
        $db_mation->comment_id = $comment_id;
        $db_mation->waybill = $waybill;
        $db_mation->see = 0;
        $db_mation->created_time = time();
        $res = $db_mation->add();
        if ($res) {
            return true;
        } else {
            return false;
        }
    }
}