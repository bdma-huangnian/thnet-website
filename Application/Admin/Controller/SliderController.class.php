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
 * 幻灯管理控制器
 * @author ijry <skipperprivater@gmail.com>
 */
class SliderController extends AdminController {
    /**
     * 幻灯列表
     * @author ijry <skipperprivater@gmail.com>
     */
    public function index(){
        $title   =   I('title');
        $map['status']  =   array('egt',0);
        if(is_numeric($title)){
            $map['id']       =   array(intval($title),array('like','%'.$title.'%'));
        }else{
            $map['title']    =   array('like', '%'.(string)$title.'%');
        }

        $list   = $this->lists('Slider', $map, $order='sort desc,id desc');
        int_to_string($list);
        $this->assign('_list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->meta_title = '幻灯列表';
        $this->display();
    }

    /**
     * 幻灯状态修改
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
            case 'forbidslider':
                $this->forbid('Slider', $map );
                break;
            case 'resumeslider':
                $this->resume('Slider', $map );
                break;
            case 'deleteslider':
                $this->delete('Slider', $map );
                break;
            default:
                $this->error('参数非法');
        }
    }

    /**
     * 新增幻灯
     * @author ijry <skipperprivater@gmail.com>
     */
    public function add(){
        $this->meta_title = '新增幻灯';
        $this->display();
    }

    /**
     * 编辑幻灯
     * @author ijry <skipperprivater@gmail.com>
     */
    public function edit(){
        $id = I('get.id');
        empty($id) && $this->error('参数不能为空！');
        $data = M('Slider')->field(true)->find($id);

        $this->assign('data',$data);
        $this->meta_title = '编辑幻灯';
        $this->display();
    }

    /**
     * 更新幻灯
     * @author ijry <skipperprivater@gmail.com>
     */
    public function save(){
        $res = D('Slider')->update();
        if(!$res){
            $this->error(D('Slider')->getError());
        }else{
            $this->success($res['id']?'更新成功！':'新增成功！', Cookie('__forward__'));
        }
    }

    /**
     * 排序
     * @author ijry <skipperprivater@gmail.com>
     */
    public function sort(){
        if(IS_GET){
            $ids = I('get.ids');
            $pid = I('get.pid');

            //获取排序的数据
            $map = array('status'=>array('gt',-1));
            if(!empty($ids)){
                $map['id'] = array('in',$ids);
            }else{
                if($pid !== ''){
                    $map['pid'] = $pid;
                }
            }
            $list = M('Slider')->where($map)->field('id,title')->order('sort desc,id desc')->select();

            $this->assign('list', $list);
            $this->meta_title = '幻灯排序';
            $this->display();
        }elseif(IS_POST){
            $ids = I('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = M('Slider')->where(array('id'=>$value))->setField('sort', $key+1);
            }
            if($res !== false){
                $this->success('排序成功！');
            }else{
                $this->error('排序失败！');
            }
        }else{
            $this->error('非法请求！');
        }
    }
}
