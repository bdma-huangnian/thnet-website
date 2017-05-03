<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 https://wx.zjliving.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <skipperprivater@gmail.com> <https://wx.zjliving.com>
// +----------------------------------------------------------------------
namespace User\Model;
use Think\Model;
/**
 * 会员模型
 */
class UcenterMemberModel extends Model {
    /**
     * 数据表前缀
     * @var string
     */
    protected $tablePrefix = UC_TABLE_PREFIX;

    /**
     * 数据库连接
     * @var string
     */
    protected $connection = UC_DB_DSN;

    /* 用户模型自动验证 */
    protected $_validate = array(
        /* 验证用户名 */
        array('username', '1,30', -1, self::EXISTS_VALIDATE, 'length'), //用户名长度不合法
        array('username', 'checkDenyMember', -2, self::EXISTS_VALIDATE, 'callback'), //用户名禁止注册
        array('username', '', -3, self::EXISTS_VALIDATE, 'unique'), //用户名被占用
        array('username', 'checkIP', -4, self::EXISTS_VALIDATE, 'callback'), //IP限制
        array('username', '/^(?!_)(?!\d)(?!.*?_$)[\w\一-\龥]+$/', -5, self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH), //用户名验证

        /* 验证密码 */
        array('password', '6,30', -6, self::EXISTS_VALIDATE, 'length'), //密码长度不合法
        array('password', '/(?!^(\d+|[a-zA-Z]+|[~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+)$)^[\w~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+$/', -7, self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH), //密码复杂度验证

        /* 验证邮箱 */
        array('email', 'email', -8, self::EXISTS_VALIDATE), //邮箱格式不正确
        array('email', '1,32', -9, self::EXISTS_VALIDATE, 'length'), //邮箱长度不合法
        array('email', '', -10, self::EXISTS_VALIDATE, 'unique'), //邮箱被占用

        /* 验证手机号码 */
        array('mobile', '/^1\d{10}$/', -11, self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH), //手机号码格式不正确
        array('mobile', '', -12, self::EXISTS_VALIDATE, 'unique'), //手机号被占用
    );

    /* 用户模型自动完成 */
    protected $_auto = array(
        array('password', 'think_ucenter_md5', self::MODEL_BOTH, 'function', UC_AUTH_KEY),
        array('reg_time', NOW_TIME, self::MODEL_INSERT),
        array('reg_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
        array('update_time', NOW_TIME),
        array('status', 'getStatus', self::MODEL_BOTH, 'callback'),
    );

    /**
     * 检测用户名是不是被禁止注册
     * @param  string $username 用户名
     * @return boolean ture 未禁用，false 禁止注册
     */
    protected function checkDenyMember($username){
        $deny = M('UcenterSetting')->where(array('name'=>'DENY_USERNAME'))->getField('value');
        $deny = explode ( ',', $deny);
        foreach ($deny as $k=>$v) {
            if(stristr($username, $v)){
                return false;
            }
        }
        return true;
    }

    /**
     * 根据配置指定用户状态
     * @return integer 用户状态
     */
    protected function getStatus(){
        return true; //TODO: 暂不限制，下一个版本完善
    }

    /**
     * 注册一个新用户
     * @param  string $username 用户名
     * @param  string $password 用户密码
     * @param  string $email    用户邮箱
     * @param  string $mobile   用户手机号码
     * @return integer          注册成功-用户信息，注册失败-错误编号
     */
    public function register($username, $password, $email, $mobile){
        $data = array(
            'username' => $username,
            'password' => $password,
            'email'    => $email,
            'mobile'   => $mobile,
        );

        //验证手机
        if(empty($data['mobile'])) unset($data['mobile']);

        /* 添加用户 */
        if($this->create($data)){
            $uid = $this->add();
            return $uid ? $uid : 0; //0-未知错误，大于0-注册成功
        } else {
            return $this->getError(); //错误详情见自动验证注释
        }
    }

    /**
     * 用户登录认证
     * @param  string  $username 用户名
     * @param  string  $password 用户密码
     * @param  integer $type     用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
     * @return integer           登录成功-用户ID，登录失败-错误编号
     */
    public function login($username, $password, $type = 1){
        $map = array();
        switch ($type) {
            case 1:
                $map['username'] = $username;
                break;
            case 2:
                $map['email'] = $username;
                break;
            case 3:
                $map['mobile'] = $username;
                break;
            case 4:
                $map['id'] = $username;
                break;
            default:
                return 0; //参数错误
        }

        /* 获取用户数据 */
        $user = $this->where($map)->find();
        if(is_array($user) && $user['status']){
            /* 验证用户密码 */
            if(think_ucenter_md5($password, UC_AUTH_KEY) === $user['password']){
                $this->updateLogin($user['id']); //更新用户登录信息
                return $user['id']; //登录成功，返回用户ID
            } else {
                return -2; //密码错误
            }
        } else {
            return -1; //用户不存在或被禁用
        }
    }

    /**
     * 获取用户信息
     * @param  string  $uid         用户ID或用户名
     * @param  boolean $is_username 是否使用用户名查询
     * @return array                用户信息
     */
    public function info($uid, $is_username = false){
        $map = array();
        if($is_username){ //通过用户名获取
            $map['username'] = $uid;
        } else {
            $map['id'] = $uid;
        }

        $user = $this->where($map)->field('id,username,password,email,mobile,status')->find();
        if(is_array($user) && $user['status'] == 1){
            return $user;
        } else {
            return -1; //用户不存在或被禁用
        }
    }

    /**
     * 检测用户信息
     * @param  string  $field  用户名
     * @param  integer $type   用户名类型 1-用户名，2-用户邮箱，3-用户电话
     * @return integer         错误编号
     */
    public function checkField($field, $type = 1){
        $data = array();
        switch ($type) {
            case 1:
                $data['username'] = $field;
                break;
            case 2:
                $data['email'] = $field;
                break;
            case 3:
                $data['mobile'] = $field;
                break;
            default:
                return 0; //参数错误
        }

        return $this->create($data) ? 1 : $this->getError();
    }

    /**
     * 更新用户登录信息
     * @param  integer $uid 用户ID
     */
    protected function updateLogin($uid){
        $data = array(
            'id'              => $uid,
            'last_login_time' => NOW_TIME,
            'last_login_ip'   => get_client_ip(1),
        );
        $this->save($data);
    }

    /**
     * 更新用户信息
     * @param int $uid 用户id
     * @param string $password 密码，用来验证
     * @param array $data 修改的字段数组
     * @param bool $encrypt_verify 是否加密原文验证密码
     * @return true 修改成功，false 修改失败
     * @author huajie <banhuajie@163.com>
     */
    public function updateUserFields($uid, $password, $data, $encrypt_verify = true){
        if(empty($uid) || empty($password) || empty($data)){
            $this->error = '参数错误！';
            return false;
        }

        //更新前检查用户密码
        if(!$this->verifyUser($uid, $password, $encrypt_verify = true)){
            $this->error = '验证出错：密码不正确！';
            return false;
        }

        //更新用户信息
        $data = $this->create($data);
        if($data){
            return $this->where(array('id'=>$uid))->save($data);
        }
        return false;
    }

    /**
     * 验证用户密码
     * @param int $uid 用户id
     * @param string $password_in 密码
     * @param bool $$encrypt_verify 是否加密原文验证密码
     * @return true 验证成功，false 验证失败
     * @author huajie <banhuajie@163.com>
     */
    protected function verifyUser($uid, $password_in, $encrypt_verify = true){
        $password = $this->getFieldById($uid, 'password');
        switch($encrypt_verify){
            case true : $password_in = think_ucenter_md5($password_in, UC_AUTH_KEY); break;
            default : break;
        }
        if($password_in === $password){
            return true;
        }
        return false;
    }

    /**
     * 获取所有所有用户指定字段值
     * @param string $field 字段
     * @return array
     * @author ijry <skipperprivater@gmail.com>
     */
    public function getColumnByfield($field = 'email', $map){
        $map['status'] = 1;
        return $this->where($map)->getField($field,true);
    }

    /**
     * 检测同一IP注册是否频繁
     * @return boolean ture 正常，false 频繁注册
     */
    protected function checkIP(){
        $limit_time = M('UcenterSetting')->where(array('name'=>'LIMIT_TIME_BY_IP'))->getField('value');
        $map['reg_time'] = array('GT', time()-(int)$limit_time);
        $reg_ip = $this->getColumnByfield('reg_ip', $map);
        $key = array_search(get_client_ip(1), $reg_ip);
        if($reg_ip && $key !== false){
            return false;
        }
        return true;
    }
}
