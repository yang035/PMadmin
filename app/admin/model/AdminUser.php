<?php
namespace app\admin\model;

use think\Db;
use think\Model;
use app\admin\model\AdminMenu as MenuModel;
use app\admin\model\AdminRole as RoleModel;

class AdminUser extends Model
{
    public static function userList(){
        $where = [];
        $user = self::where($where)->select();
        $data = [];
        foreach ($user as $k=>$v){
            $data[$k]['pid'] = $v['department_id'];
            $data[$k]['name'] = "<font='red'>".$v['realname']."</font>";
        }
        return $data;
    }

    // 对密码进行加密
    public function setPasswordAttr($value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    // 写入时，将权限ID转成JSON格式
    public function setAuthAttr($value)
    {
        if (empty($value)) return '';
        return json_encode($value);
    }

    // 获取最后登陆ip
    public function setLastLoginIpAttr()
    {
        return get_client_ip();
    }

    // 最后登录时间
    public function getLastLoginTimeAttr($value)
    {
        if (!$value) return '';
        return date('Y-m-d H:i', $value);
    }

    // 权限
    public function role()
    {
        return $this->hasOne('AdminRole', 'id', 'role_id');
    }

    //关联部门
    public function dep()
    {
        return $this->hasOne('AdminDepartment', 'id', 'department_id');
    }

    // 部门
    public static function department($did=0)
    {
        $map['company_id'] = session('admin_user.cid');
        $map['department_id'] = $did;
        $data = self::where($map)->find()->toArray();
        return $data;
    }

    public function del($id = 0)
    {
        $menu_model = new MenuModel();
        if (is_array($id)) {
            $error = '';
            foreach ($id as $k => $v) {
                if ($v == ADMIN_ID) {
                    $error .= '不能删除当前登陆的用户['.$v.']！<br>';
                    continue;
                }

                if ($v == 1) {
                    $error .= '不能删除超级管理员['.$v.']！<br>';
                    continue;
                }

                if ($v <= 0) {
                    $error .= '参数传递错误['.$v.']！<br>';
                    continue;
                }

                $map = [];
                $map['id'] = $v;
                // 删除用户
                self::where($map)->delete();
                // 删除关联表;
                $menu_model->delUser($v);
            }

            if ($error) {
                $this->error = $error;
                return false;
            }
        } else {
            $id = (int)$id;
            if ($id <= 0) {
                $this->error = '参数传递错误！';
                return false;
            }

            if ($id == ADMIN_ID) {
                $this->error = '不能删除当前登陆的用户！';
                return false;
            }

            if ($id == 1) {
                $this->error = '不能删除超级管理员！';
                return false;
            }

            $map = [];
            $map['id'] = $id;
            // 删除用户
            self::where($map)->delete();
            // 删除关联表
            $menu_model->delUser($id);
        }

        return true;
    }

    public function login($username = '', $password = '', $remember = false)
    {
        $username = trim($username);
        $password = trim($password);
        $map = [];
        $map['status'] = 1;

        if ($this->validateData(input('post.'), 'AdminUser.login') != true) {
            $this->error = $this->getError();
            return false;
        }
        if (preg_match("/^1\d{10}$/", $username)) {
            $map['mobile'] = $username;
            $user = self::where($map)->find();
            if (!$user) {
                $this->error = '手机号码不存在或被禁用！';
                return false;
            }
        }else{
            $map['username'] = $username;
            $user = self::where($map)->find();
            if (!$user) {
                $this->error = '用户不存在或被禁用！';
                return false;
            }
        }

        // 密码校验
        if (!password_verify($password, $user->password)) {
            $this->error = '登陆密码错误！';
            return false;
        }

        // 检查是否分配角色
        if ($user->role_id == 0) {
            $this->error = '禁止访问(原因：未分配角色)！';
            return false;
        }

        // 角色信息
        $role = RoleModel::where('id', $user->role_id)->find()->toArray();
        if (!$role || $role['status'] == 0) {
            $this->error = '禁止访问(原因：角色分组可能被禁用)！';
            return false;
        }

        $dep_role = AdminDepartment::where('id', $user->department_id)->find()->toArray();
        $company = AdminCompany::where('id', $user->company_id)->find()->toArray();

        // 更新登录信息
        $user->last_login_time = time();
        $user->last_login_ip   = get_client_ip();
        if ($user->save()) {
            // 执行登陆
            $login = [];
            $login['uid'] = $user->id;
            $login['cid'] = $user->company_id;
            $login['depid'] = $user->department_id;
            $login['path'] = $dep_role['path'];
            $login['role_id'] = $user->role_id;
            $login['work_cat'] = $user->work_cat;
            $login['role_name'] = $role['name'];
            $login['nick'] = $user->nick;
            $login['job_item'] = $user->job_item;
            $login['username'] = $user->username;
            $login['realname'] = $user->realname;
            $login['signature'] = $user->signature;
            $login['company'] = $company['name'];
            cookie('hisi_iframe', $user->iframe);
            // 主题设置
            self::setTheme(isset($user->theme) ? $user->theme : 0);
            self::getThemes(true);
            // 缓存角色权限
            session('role_auth_'.$user->role_id, $user->auth ? json_decode($user->auth, true) : ($dep_role['auth'] ? json_decode($dep_role['auth'], true) : json_decode($role['auth'], true)));
            // 缓存登录信息
            session('admin_user', $login);
            session('admin_user_sign', $this->dataSign($login));

            $url = config('other.bbs_url');
            $d = [
                'email'=>$username,
                'password'=>$password,
                'other'=>json_encode($user),
            ];
            $bbs_token = curlInfo($url,$d);
            setcookie("bbs_token", $bbs_token, time() + 8640000, "/", $url);
            $user->setInc('times');

            $u_login = [
                'user_id' => $user->id,
                'cid' => $user->company_id,
                'login_ip' => get_client_ip(),
                'login_time' => date('Y-m-d H:i:s'),
            ];
            Db::table('tb_user_login')->insert($u_login);

            return $user->id;
        }
        return false;
    }

    public static function getThemes($cache = false)
    {
        $themeFile = '.'.config('view_replace_str.__ADMIN_CSS__').'/theme.css';
        $themes = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        if (is_file($themeFile)) {
            $content = file_get_contents($themeFile);
            preg_match_all("/\/\*{6}(.+?)\*{6}\//", $content, $diyTheme);
            if (isset($diyTheme[1]) && count($diyTheme[1]) > 0) {
                foreach ($diyTheme[1] as $v) {
                    if (preg_match("/^[A-Za-z0-9\-\_]+$/", trim($v))) {
                        array_push($themes, trim($v));
                    }
                }
                $themes = array_unique($themes);
            }
        }
        if ($cache) {
            session('hisi_admin_themes', $themes);
        }
        return $themes;
    }

    public static function setTheme($name = 'default', $update = false)
    {
        cookie('hisi_admin_theme', $name);
        $result = true;
        if ($update && defined('ADMIN_ID')) {
            $result = self::where('id', ADMIN_ID)->setField('theme', $name);
        }
        return $result;
    } 

    public function isLogin()
    {
        $user = session('admin_user');
        if (isset($user['uid'])) {
            if (!self::where('id', $user['uid'])->find()) {
                return false;
            }
            return session('admin_user_sign') == $this->dataSign($user) ? $user : false;
        }
        return false;
    }

    public function logout()
    {
        session('admin_user', null);
        session('admin_user_sign', null);
    }

    public function dataSign($data = [])
    {
        if (!is_array($data)) {
            $data = (array) $data;
        }
        ksort($data);
        $code = http_build_query($data);
        $sign = sha1($code);
        return $sign;
    }

    // /**
    //  * 用户状态设置
    //  * @param string $id 用户ID
    //  * @return bool
    //  */
    // public function status($id = '', $val = 0) {
    //     if (is_array($id)) {
    //         $error = '';
    //         foreach ($id as $k => $v) {
    //             $v = (int)$v;
    //             if ($v == 1) {
    //                 $error .= '禁止更改超级管理员状态['.$v.']<br>';
    //                 continue;
    //             }

    //             $map = [];
    //             $map['id'] = $v;
    //             // 删除用户
    //             self::where($map)->setField('status', $val);
    //         }

    //         if ($error) {
    //             $this->error = $error;
    //             return false;
    //         }
    //     } else {
    //         $id = (int)$id;
    //         if ($id <= 0) {
    //             $this->error = '参数传递错误';
    //             return false;
    //         }

    //         if ($id == 1) {
    //             $this->error = '禁止更改超级管理员状态';
    //             return false;
    //         }

    //         $map = [];
    //         $map['id'] = $id;
    //         // 删除用户
    //         self::where($map)->setField('status', $val);
    //     }

    //     return true;
    // }

    public static function getUserById($id=1){
        $where = [
            'id'=>$id,
        ];
        $res = self::where($where)->find()->toArray();
        if ($res){
            return $res;
        }
        return [];
    }

    public static function getUserById1($id=1){
        $where = [
            'id'=>$id,
            'status'=>1,
        ];
        $res = self::where($where)->find();
        if ($res){
            return $res;
        }
        return [];
    }

    public static function selectUser($type = 0){
        $where = [
            'status' => 1,
            'is_show' => 0,
            'company_id' => session('admin_user.cid'),
        ];
        $data = self::where($where)->field('id,realname')->select();
        $str = '';
        if ($data){
            foreach ($data as $k => $v) {
                if ($type == $k) {
                    $str .= "<option value='".$v['id']."' selected>".$v['realname']."</option>";
                } else {
                    $str .= "<option value='".$v['id']."'>".$v['realname']."</option>";
                }
            }
        }
        return $str;
    }

    public static function inputSearchUser(){
        $where = [
            'status' => 1,
            'is_show' => 0,
            'company_id' => session('admin_user.cid'),
        ];
        $data = self::where($where)->field('id,realname')->select();
        return json_encode($data);
    }

    public static function inputSearchUser1(){
        $where = [
            'is_show' => 0,
            'company_id' => session('admin_user.cid'),
        ];
        $data = self::where($where)->field('id,realname')->select();
        return json_encode($data);
    }

    public static function getSexOption($type = 0)
    {
        $leaveType = config('other.sex_type');
        $str = '';
        foreach ($leaveType as $k => $v) {
            if ($type == $k) {
                $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
            } else {
                $str .= '<option value="'.$k.'">'.$v.'</option>';
            }
        }
        return $str;
    }

    public static function checkUser($username,$type=0){
        if (preg_match("/^1\d{10}$/", $username)) {
            $map['u.mobile'] = $username;
        }else{
            $map['u.username'] = $username;
        }
        $map['u.status'] = 1;
        $fields = 'c.id,c.name';
        $data = \db('admin_user')->alias('u')->field($fields)
            ->where($map)->join('tb_admin_company c','u.company_id = c.id','left')
            ->select();
        $str = '';
        if ($data){
            foreach ($data as $k => $v) {
                if ($type == $k) {
                    $str .= "<option value='".$v['id']."' selected>".$v['name']."</option>";
                } else {
                    $str .= "<option value='".$v['id']."'>".$v['name']."</option>";
                }
            }
        }
        return $str;
    }
}
