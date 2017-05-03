<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
/**
 * 后台标签控制器
 * @author ijry <skipperprivater@gmail.com>
 */
class TagsController extends AdminController {
    /**
     * 标签列表
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

        $list   = $this->lists('Tags', $map, $order='sort asc,id desc');
        int_to_string($list);
        $this->assign('_list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->meta_title = '标签列表';
        $this->display();
    }

    /**
     * 标签状态修改
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
            case 'forbidtags':
                $this->forbid('Tags', $map );
                break;
            case 'resumetags':
                $this->resume('Tags', $map );
                break;
            case 'deletetags':
                $this->delete('Tags', $map );
                break;
            default:
                $this->error('参数非法');
        }
    }

    /**
     * 新增标签
     * @author ijry <skipperprivater@gmail.com>
     */
    public function add(){
        $this->meta_title = '新增标签';
        $this->display();
    }

    /**
     * 编辑标签
     * @author ijry <skipperprivater@gmail.com>
     */
    public function edit(){
        $id = I('get.id');
        empty($id) && $this->error('参数不能为空！');
        $data = M('Tags')->field(true)->find($id);

        $this->assign('data',$data);
        $this->meta_title = '编辑标签';
        $this->display();
    }

    /**
     * 更新标签
     * @author ijry <skipperprivater@gmail.com>
     */
    public function save(){
        $res = D('Tags')->update();
        if(!$res){
            $this->error(D('Tags')->getError());
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
            $list = M('Tags')->where($map)->field('id,title')->order('sort asc,id desc')->select();

            $this->assign('list', $list);
            $this->meta_title = '标签排序';
            $this->display();
        }elseif(IS_POST){
            $ids = I('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = M('Tags')->where(array('id'=>$value))->setField('sort', $key+1);
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

    /**
     * 搜索相关标签
     * @author ijry <skipperprivater@gmail.com>
     */
    public function tags(){
        $tags =  D('Tags')->searchTags($_GET['q']);
        $data = array();
        foreach($tags as $value){
            $data[] = array('id' => $value['title'], 'title'=> $value['title']);
        }
        echo json_encode($data);
    }
}
