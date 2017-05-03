<?php
namespace Api\Controller;

use Think\Controller;

class ApiController extends Controller
{
    public function get_data()
    {
      if (!IS_POST) {
            $result['ErrorCode'] = 1;
            $result['ErrorMsg'] = 'error data type,please checked and again';
            $this->print_result($result);
        }

       /* $data = stripslashes($_POST);
        $json_data = json_decode($data, true);
        var_dump($json_data);die;
        //var_dump($_POST['data']['Phone']);die;
        return $json_data;*/
    }

    public function ApiMsg()
    {
        $data = $this->get_data();
        $result = array('ErrorCode' => 1, 'ErrorMsg' => '', 'Data' => array());
        $Phone = strval($_POST['Phone']);
        $Msg = '您的验证码为:'.intval($_POST['Msg']);
        if(empty($Phone)){
            $result['ErrorMsg'] = '手机号码不能为空';
            $this->print_result($result);
        }
        $res = $this->sendsms($Phone,$Msg,0);
        if($res == 0){
            $result['ErrorCode'] = 0;
            $this->print_result($result);
        }else{
            $result['ErrorMsg'] = $res;
            $this->print_result($result);
        }
    }

    //打印输出结果
    public function print_result($result = array())
    {
        $json = json_encode($result);
        print_r($json);
        die;
    }

    //短信发送
    /**短信发送调用
     * @$mobile            手机号码
     * @$msg               短信内容
     */
    private function sendsms($mobile, $msg,$from="0", $sign = "同好宝") {
        vendor('YiMeiSMS/nusoaplib.nusoap');
        //import('', APP_PATH . "Common/YiMeiSMS/nusoaplib/", 'nusoap.php');
        //导入nusoap
        vendor('YiMeiSMS.include.Client');
        //import('', APP_PATH . "Common/YiMeiSMS/include/", 'Client.php');
        //导入Client

        //D("News")->add(array("cid"=>30,"naddtime"=>time(),"ntitle"=>"No2.".$out_trade_no."Client".date("s",time())."；止于L275","ncontent"=>$msg)); //不能正常返回干净的success，调试用

        //return false;

        /**
         * 网关地址
         */
        $gwUrl = 'http://sdkhttp.eucp.b2m.cn/sdk/SDKService'; //适合3sdk
        //$gwUrl = 'http://sdk4report.eucp.b2m.cn:8080/sdk/SDKService'; //适合6sdk

        /**
         * 序列号,请通过亿美销售人员获取
         */
        $serialNumber = C("YiMei_xlh");

        /**
         * 密码,请通过亿美销售人员获取
         */
        $password = C("YiMei_pwd");

        /**
         * 登录后所持有的SESSION KEY，即可通过login方法时创建
         */
        $sessionKey = C("YiMei_key");

        /**
         * 连接超时时间，单位为秒
         */
        $connectTimeOut = 2;

        /**
         * 远程信息读取超时时间，单位为秒
         */
        $readTimeOut = 10;

        /**
        $proxyhost    可选，代理服务器地址，默认为 false ,则不使用代理服务器
        $proxyport    可选，代理服务器端口，默认为 false
        $proxyusername  可选，代理服务器用户名，默认为 false
        $proxypassword  可选，代理服务器密码，默认为 false
         */
        $proxyhost = false;
        $proxyport = false;
        $proxyusername = false;
        $proxypassword = false;

        $client = new \Client($gwUrl, $serialNumber, $password, $sessionKey, $proxyhost, $proxyport, $proxyusername, $proxypassword, $connectTimeOut, $readTimeOut);

        /**
         * 发送向服务端的编码，如果本页面的编码为utf-8，请使用utf-8
         */
        $client -> setOutgoingEncoding("utf-8");
        $contentsms = $msg;
        if ($sign != "")
            $contentsms = "【" . $sign . "】" . $contentsms;
        $statusCode = $client -> sendSMS(array($mobile), $contentsms);
        //$statusCode = $client->sendSMS(array('159xxxxxxxx','159xxxxxxxx'),"test2测试");
        //echo "处理状态码:".$statusCode;
        //echo "处理状态码:".$statusCode;

        if ($statusCode == 0) {
            $statusCodeinfo = "成功";
        } else {
            $statusCodeinfo = "失败(" . $statusCode . ")";
        }

        //保存在数据库
        $data["suidfrom"]=$from;
        $data["smobile"]=$mobile;
        $data["saddtime"]=time();
        $data["sstatus"]=$statusCodeinfo;
        $data["scontent"]=$contentsms;
        M("msg")->add($data);
        //__保存在数据库

        return $statusCode;
    }
}
