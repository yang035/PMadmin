<?php
namespace app\admin\model;

use think\Model;
use app\admin\model\AdminUser as UserModel;
class AdminRole extends Model
{
    // 写入时，将权限ID转成JSON格式
    public function setAuthAttr($value)
    {
        return json_encode($value);
    }

    public static function getOption($id = 0)
    {
        $rows = self::where('status',1)->column('id,name');
        $str = '';
        foreach ($rows as $k => $v) {
            if ($k == 1) {// 过滤超级管理员角色
                continue;
            }
            if ($id == $k) {
                $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
            } else {
                $str .= '<option value="'.$k.'">'.$v.'</option>';
            }
        }
        return $str;
    }

    public function del($id = 0)
    {
        $user_model = new UserModel();
        if (is_array($id)) {
            $error = '';
            foreach ($id as $k => $v) {
                if ($v == 1) {
                    $error .= '不能删除超级管理员角色['.$v.']！<br>';
                    continue;
                }

                if ($v <= 0) {
                    $error .= '参数传递错误['.$v.']！<br>';
                    continue;
                }

                // 判断是否有用户绑定此角色
                if (UserModel::where('role_id', $v)->find()) {
                    $error .= '删除失败，已有管理员绑定此角色['.$v.']！<br>';
                    continue;
                }
                $map = [];
                $map['id'] = $v;
                self::where($map)->delete();
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

            if ($id == 1) {
                $this->error = '不能删除超级管理员角色！';
                return false;
            }

            // 判断是否有用户绑定此角色
            if (UserModel::where('role_id', $id)->find()) {
                $this->error = '删除失败，已有管理员绑定此角色！<br>';
                return false;
            }

            $map = [];
            $map['id'] = $id;
            self::where($map)->delete();
        }

        return true;
    }

    public static function getAll()
    {
        return self::column('id,name');
    }

    public static function getRole()
    {
        $where = [
            'id' => ['>=',3],
        ];
        return self::field('id,name,discount')->where($where)->select();
    }

    public static function getRole1($role_id)
    {
        $where = [
            'id' => $role_id,
        ];
        return self::where($where)->limit(1)->column('discount');
    }

    public static function checkAuth($id = 0)
    {
        $login = session('admin_user');
        // 超级管理员直接返回true
        if ($login['uid'] == '1' || $login['role_id'] == '1') {
            return true;
        }
        // 获取当前角色的权限明细
        $role_auth = (array)session('role_auth_'.$login['role_id']);
//        print_r($role_auth);exit();
        if (!$role_auth) {
            $map = [];
            $map['id'] = $login['role_id'];
            $auth = self::where($map)->value('auth');
            if (!$auth) {
                return false;
            }
            $role_auth = json_decode($auth, true);
            // 非开发模式，缓存数据
            if (config('develop.app_debug') == 0) {
                session('role_auth_'.$login['role_id'], $role_auth);
            }
        }
        if (!$role_auth) return false;
        return in_array($id, $role_auth);
    }

    public static function getSysType($type = 0)
    {
        $leaveType = config('tb_system.sys_type');
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
}