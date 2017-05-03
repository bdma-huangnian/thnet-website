<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Addons\EditorForAdmin\Controller;
use Home\Controller\AddonsController;
use Think\Upload;
/**
 * 编辑器上传控制器
 */
class UploadController extends AddonsController {
    public $uploader = null;

    /* 上传图片 */
    public function upload(){
        /* 上传配置 */
        $setting = C('EDITOR_UPLOAD');

        /* 调用文件上传组件上传文件 */
        $Picture = D('Picture');
        $pic_driver = C('PICTURE_UPLOAD_DRIVER');
        $info = $Picture->upload(
            $_FILES,
            C('EDITOR_UPLOAD'),
            C('PICTURE_UPLOAD_DRIVER'),
            C("UPLOAD_{$pic_driver}_CONFIG")
        );

        return $info['imgFile'];
    }

    //编辑器下载远程图片
    public function download_remote_img(){
        /* 返回标准数据 */
        $return  = array('status' => 1);
        if((isset($_POST['html']))&&(!empty($_POST['html']))){
            $body = $_POST['html'];
            $body = preg_replace("/<a.*?>(<img.*?>)<\/a>/","$1",$body); //去除包裹img标签的a标签
            $img_array = array();
            preg_match_all("/<img.*?src=[\',\"](.*?\.(jpg|jpeg|gif|bmp|png)).*?>/i",$body,$img_array);
            $img_array = array_unique($img_array[1]);
            $realpath = realpath(C('EDITOR_UPLOAD.rootPath')).'/'.date('Y-m-d');
            if(!is_dir($realpath)) @mkdir($realpath,0755);
            set_time_limit(0);
            //遍历远程图片下载到本地并替换远程图片地址
            foreach($img_array as $key =>$value){
                $value = trim($value);
                $get_file = file_get_contents($value);
                $rename = '/'.uniqid().'.'.substr($value,-3,3);
                $realname = $realpath.$rename;
                $url = __ROOT__.C('EDITOR_UPLOAD.rootPath').date('Y-m-d').$rename;
                $url = str_replace('./', '/', $url);
                if($get_file){
                    $fp = fopen($realname,'w');
                    $ret = fwrite($fp,$get_file);
                    fclose($fp);
                }
                $body = ereg_replace($value,$url,$body);
            }
            $return['info'] = $body;
        }else{
            $return['status'] = 0;
        }
        /* 返回JSON数据 */
        exit(json_encode($return));
    }

    //keditor编辑器上传图片处理
    public function ke_upimg(){
        /* 返回标准数据 */
        $return  = array('error' => 0, 'info' => '上传成功', 'data' => '');
        $img = $this->upload();
        /* 记录附件信息 */
        if($img){
            if($img['url']){
                $return['url'] = $img['url'];
            }else{
                $return['url'] = __ROOT__.$img['path'];
            }
            unset($return['info'], $return['data']);
        } else {
            $return['error'] = 1;
            $return['message'] = "上传错误";
        }

        /* 返回JSON数据 */
        exit(json_encode($return));
    }

    //ueditor编辑器上传图片处理
    public function ue_upimg(){
        $img = $this->upload();
        $return = array();
        if($img['url']){
            $return['url'] = $img['url'];
        }else{
            $return['url'] = __ROOT__.$img['path'];
        }
        $title = htmlspecialchars($_POST['pictitle'], ENT_QUOTES);
        $return['title'] = $title;
        $return['original'] = $img['name'];
        $return['state'] = ($img)? 'SUCCESS' : "上传错误";
        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }

    //keditor编辑器文件管理
    public function ke_file_manager(){
        //根目录路径，可以指定绝对路径，比如 /var/www/attached/
        $root_path = __ROOT__.C('EDITOR_UPLOAD.rootPath');
        //根目录URL，可以指定绝对路径，比如 http://www.yoursite.com/attached/
        $root_url = C('EDITOR_UPLOAD.rootPath');
        //图片扩展名
        $ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');

        if ($dir_name !== '') {
            $root_path .= $dir_name . "/";
            $root_url .= $dir_name . "/";
            if (!file_exists($root_path)) {
                mkdir($root_path);
            }
        }

        //根据path参数，设置各路径和URL
        if (empty($_GET['path'])) {
            $current_path = realpath($root_path) . '/';
            $current_url = $root_url;
            $current_dir_path = '';
            $moveup_dir_path = '';
        } else {
            $current_path = realpath($root_path) . '/' . $_GET['path'];
            $current_url = $root_url . $_GET['path'];
            $current_dir_path = $_GET['path'];
            $moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
        }
        //echo realpath($root_path);
        //排序形式，name or size or type
        $order = empty($_GET['order']) ? 'name' : strtolower($_GET['order']);

        //不允许使用..移动到上一级目录
        if (preg_match('/\.\./', $current_path)) {
            echo 'Access is not allowed.';
            exit;
        }
        //最后一个字符不是/
        if (!preg_match('/\/$/', $current_path)) {
            echo 'Parameter is not valid.';
            exit;
        }
        //目录不存在或不是目录
        if (!file_exists($current_path) || !is_dir($current_path)) {
            echo 'Directory does not exist.';
            exit;
        }

        //遍历目录取得文件信息
        $file_list = array();
        if ($handle = opendir($current_path)) {
            $i = 0;
            while (false !== ($filename = readdir($handle))) {
                if ($filename{0} == '.') continue;
                $file = $current_path . $filename;
                if (is_dir($file)) {
                    $file_list[$i]['is_dir'] = true; //是否文件夹
                    $file_list[$i]['has_file'] = (count(scandir($file)) > 2); //文件夹是否包含文件
                    $file_list[$i]['filesize'] = 0; //文件大小
                    $file_list[$i]['is_photo'] = false; //是否图片
                    $file_list[$i]['filetype'] = ''; //文件类别，用扩展名判断
                } else {
                    $file_list[$i]['is_dir'] = false;
                    $file_list[$i]['has_file'] = false;
                    $file_list[$i]['filesize'] = filesize($file);
                    $file_list[$i]['dir_path'] = '';
                    $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
                    $file_list[$i]['filetype'] = $file_ext;
                }
                $file_list[$i]['filename'] = $filename; //文件名，包含扩展名
                $file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file)); //文件最后修改时间
                $i++;
            }
            closedir($handle);
        }

        usort($file_list, 'cmp_func');

        $result = array();
        //相对于根目录的上一级目录
        $result['moveup_dir_path'] = $moveup_dir_path;
        //相对于根目录的当前目录
        $result['current_dir_path'] = $current_dir_path;
        //当前目录的URL
        $result['current_url'] = $current_url;
        //文件数
        $result['total_count'] = count($file_list);
        //文件列表数组
        $result['file_list'] = $file_list;

        /* 返回JSON数据 */
        exit(json_encode($result));
    }

    //排序
    function cmp_func($a, $b) {
        global $order;
        if ($a['is_dir'] && !$b['is_dir']) {
            return -1;
        } else if (!$a['is_dir'] && $b['is_dir']) {
            return 1;
        } else {
            if ($order == 'size') {
                if ($a['filesize'] > $b['filesize']) {
                    return 1;
                } else if ($a['filesize'] < $b['filesize']) {
                    return -1;
                } else {
                    return 0;
                }
            } else if ($order == 'type') {
                return strcmp($a['filetype'], $b['filetype']);
            } else {
                return strcmp($a['filename'], $b['filename']);
            }
        }
    }
}
