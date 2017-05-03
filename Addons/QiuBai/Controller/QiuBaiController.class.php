<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Addons\QiuBai\Controller;
use Home\Controller\AddonsController;
class QiuBaiController extends AddonsController {
    //获取糗事百科列表
    public function getList(){
        if(!extension_loaded('curl')){
            $this->error('糗事百科插件需要开启PHP的CURL扩展');
        }
        $lists = S('QiuBai_content');
        if(!$lists){
            $config = get_addon_config('QiuBai');
            $HTTP_Server = "www.qiushibaike.com/8hr";
            $HTTP_URL = "/";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://" . $HTTP_Server . $HTTP_URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
            $content = curl_exec($ch);
            curl_close($ch);
            if($content){
                preg_match_all('/<div class="content" title="(.*?)">\s*(.*?)\s*<\/div>/is', $content, $match);
                unset($match[0]);
                $lists = array_map(function($a, $b) {
                    return array('time' => $a, 'content' => $b);
                }, $match[1], $match[2]);
                S('QiuBai_content',$lists,$config['cache_time']);
            }
        }

        if($lists){
            $this->success('成功', '', array('data'=>$lists));
        }else{
            $this->error('获取糗事百科列表失败');
        }
        $this->assign('qiubai_list', $lists);
    }
}
