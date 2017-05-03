<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace Common\Api;
class UserApi {
    /**
     * 检测用户是否登录
     * @return integer 0-未登录，大于0-当前登录用户ID
     */
    public static function is_login(){
        $user = session('user_auth');
        if (empty($user)) {
            return 0;
        } else {
            return session('user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
        }
    }

    /**
     * 检测当前用户是否为管理员
     * @return boolean true-管理员，false-非管理员
     */
    public static function is_administrator($uid = null){
        $uid = is_null($uid) ? is_login() : $uid;
        return $uid && (intval($uid) === C('USER_ADMINISTRATOR'));
    }

    /**
     * 根据用户ID获取用户信息
     * @param  integer $uid 用户ID
     * @param  string $field
     * @return array       用户信息
     */
    public static function get_user_info($uid = 0, $field){
        static $list;

        /* 获取缓存数据 */
        if(empty($list)){
            $list = S('sys_user_info_list');
        }

        /* 查找用户信息 */
        $key = "u{$uid}";
        if(isset($list[$key])){ //已缓存，直接使用
            $user_info = $list[$key];
        } else { //调用接口获取用户信息
            $user_info = M('Member')->find($uid);
            if($info !== false){
                $user_info = $list[$key] = $user_info;
                /* 缓存用户 */
                $count = count($list);
                $max   = C('USER_MAX_CACHE');
                while ($count-- > $max) {
                    array_shift($list);
                }
                S('sys_user_info_list', $list);
            } else {
                $user_info = array();
            }
        }
        if($field){
            return $user_info[$field];
        }
        return $user_info;
    }
}
