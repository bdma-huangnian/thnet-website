<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Home\Controller;
/**
 * 前台投票控制器
 * @author ijry <skipperprivater@gmail.com>
 */
class DiggController extends HomeController {
    /**投票
     * @param $app   Digg模型标识ID
     * @param $type  Digg类别 good bad bookmark
     * @param $id    文档内容ID
     * @author ijry <skipperprivater@gmail.com>
     */
    public function digg($app = 0, $type, $cid){
        $this->login();
        $uid = is_login();
        $map['app'] = $app;
        $map['cid'] = $cid;
        $digg_info = D('Digg')->where($map)->find();
        if(!$digg_info){ //创建Digg记录
            $data['app'] = $app;
            $data['cid'] = $cid;
            $data[$type] = $uid;
            $status = "yes";
            $count = sizeof($digg);
            $ret = D('Digg')->add($data);
        }else{
            if('' == $digg_info[$type]){
                $count = 1;
                M(C('CONTENT_APP.'.(int)$app))->where(array('id'=> (int)$cid))->setField($type, $count);
                $map['app'] = $app;
                $map['cid'] = $cid;
                $data[$type] = $uid;
                $ret = D('Digg')->where($map)->save($data);
                $status = "yes";
            }else{
                $digg = explode(',', $digg_info[$type]);
                $key  = array_search($uid, $digg); //是否已经Digg过，返回key
                if($key !== NULL && $key !== false){ //取消Digg
                    unset($digg[$key]);
                    $status = "no";
                }else{
                    $digg[] = (string)$uid;
                    $status = "yes";
                }
                $count = sizeof($digg);
                M(C('CONTENT_APP.'.(int)$app))->where(array('id'=> (int)$cid))->setField($type, $count);
                $map['app'] = $app;
                $map['cid'] = $cid;
                $data[$type] = trim(implode(',', array_values(array_unique($digg))), ',');
                $ret = D('Digg')->where($map)->save($data);
            }
        }
        if($ret){
            $this->success($status . "." . $count);
        }
        $this->error("投票出错");
    }
}
