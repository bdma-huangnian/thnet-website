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
use Think\Pay;
/**
 * 支付控制器
 */
class PayController extends HomeController {
    //初始化方法
    protected function _initialize(){
        parent::_initialize();
        $this->login();
    }

    //账户余额充值
    public function recharge(){
        if (IS_POST) {
            //订单数据
            $pay_data['paytype'] = I('post.paytype');
            $pay_config = C('PAY_' . $pay_data['paytype']);
            $pay_config['notify_url'] = U("Home/Pay/notify", array('apitype' => $apitype, 'method' => 'notify'), false, true);
            $pay_config['return_url'] = U("Home/Pay/notify", array('apitype' => $apitype, 'method' => 'return'), false, true);
            $pay = new Pay($pay_data['paytype'], $pay_config);
            $pay_data['out_trade_no'] = $pay->createOrderNo();
            $pay_data['title'] = "账户余额充值";
            $pay_data['type'] = 1; //余额充值
            $pay_data['money'] = I('post.money');
            $pay_data['uid'] = is_login();
            $pay_data['paytype'] = I('post.paytype');
            $pay_data['callback'] = "Home/Pay/rechargeSuccess";
            $pay_data['create_time'] = time();
            $pay_data['update_time'] = time();
            M('Pay')->add($pay_data); //创建订单记录
            //支付页面
            $pay_data['body'] = "账户余额充值";
            echo $pay->buildRequestForm($pay_data);
        }else{
            $this->assign('meta_title', '余额充值');
            $this->assign('user_info', M('member')->find(is_login()));//用户信息
            $this->display();
        }
    }

    /**
     * 余额充值成功回调接口
     * @param type $money
     * @param type $param
     */
    public function rechargeSuccess($money, $param){
        if (session("pay_verify") == true) {
            session("pay_verify", null);
            M("Member")->where(array('uid' => $param['uid']))->setInc('money', $money);
        } else {
            E("Access Denied");
        }
    }

    /**
     * 支付结果返回
     */
    public function notify(){
        $apitype = I('get.apitype');
        $pay = Pay($apitype, C('PAY_.' . $apitype));
        if (IS_POST && !empty($_POST)) {
            $notify = $_POST;
        } elseif (IS_GET && !empty($_GET)) {
            $notify = $_GET;
            unset($notify['method']);
            unset($notify['apitype']);
        } else {
            exit('Access Denied');
        }
        //验证
        if($pay->verifyNotify($notify)){
            //获取订单信息
            $info = $pay->getInfo();
            if($info['status']){
                $payinfo = M("Pay")->field(true)->where(array('out_trade_no' => $info['out_trade_no']))->find();
                if ($payinfo['status'] == 0 && $payinfo['callback']) {
                    session("pay_verify", true);
                    $check = R($payinfo['callback'], array('money' => $payinfo['money'], 'param' => unserialize($payinfo['param'])));
                    if ($check !== false) {
                        M("Pay")->where(array('out_trade_no' => $info['out_trade_no']))->setField(array('paytype'=>$apitype, 'update_time' => time(), 'status' => 1));
                    }
                }
                if(I('get.method') == "return"){
                    redirect("Home/User/order");
                }else{
                    $pay->notifySuccess();
                }
            }else{
                $this->error("支付失败！");
            }
        }else{
            E("Access Denied");
        }
    }
}
