<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------

namespace Addons\Weather\Controller;
use Home\Controller\AddonsController;

class WeatherController extends AddonsController{

    //获取天气列表
    public function getList(){
        $lists = S('Weather_content');
        if(!$lists){
            $config = get_addon_config('Weather');
            $url = "http://api.map.baidu.com/telematics/v2/weather?location=".$config['city']."&ak=".$config['ak']."";
            $result = file_get_contents($url);
            $content = simplexml_load_string($result);
            $lists['city'] = (string)$content->currentCity;
            $lists['showday'] = $config['showday'];
            foreach($content->results->result as $result){
                $lists['date'][] = (string)$result->date;
                $lists['weather'][] = (string)$result->weather;
                $lists['wind'][] = (string)$result->wind;
                $lists['temperature'][] = (string)$result->temperature;
                $lists['pictureUrl'][] = (string)$result->dayPictureUrl;
            }
        }
        if($lists){
            $this->success('成功', '', array('data'=>$lists));
        }else{
            $this->error('天气列表失败');
        }
    }
}
