<?php

namespace app\admin\model;

use think\Model;
use think\Loader;
use app\admin\model\AdminRole as RoleModel;
use think\Db;

class AdminDepartment extends Model
{
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public static function index($cid = 1)
    {
        $where = ['cid' => $cid];
        $result = self::where($where)->select();
        return $result;
    }

    public static function getDepUser($cid = 1)
    {
        $where = ['cid' => $cid];
        $result = self::where($where)->select();
        $map = [
            'company_id' => $cid,
            'department_id'=>['>',0],
            'status'=>1,
            'is_show'=>0,
        ];
        $user = AdminUser::where($map)->select();
        foreach ($user as $k => $v) {
            $user[$k]['uid'] = '10000' . $v->id;
        }
        $data = array_merge($user, $result);
        return $data;
    }

    public function storage($data = [])
    {
        if (empty($data)) {
            $data = request()->post();
            $res = $this->create($data);
        }
    }

    public static function getRowById($id = 1)
    {
        $map['cid'] = session('admin_user.cid');
        $map['id'] = $id;
        $data = self::where($map)->find()->toArray();
        return $data;
    }

    public static function getAllChild($pid = 0, $status = 1, $field = 'id,pid,cid,code,name,status')
    {
        $map = [];
        $map['cid'] = 1;
        $map['pid'] = $pid;
        if ($status == 1) {
            $map['status'] = 1;
        }
        $data = self::where($map)->order('id asc')->column($field);
        if ($data) {
            foreach ($data as $k => $v) {
                $data[$k]['child'] = self::getAllChild($v['id'], $status, $field, $data);
            }
        }
        return $data;
    }

    public static function getMainMenu($update = false, $pid = 0, $level = 0, $data = [])
    {
        $cache_tag = '_admin_menu' . ADMIN_ID . dblang('admin');
        $trees = [];
        if (config('develop.app_debug') == 0 && $level == 0 && $update == false) {
            $trees = cache($cache_tag);
        }
        if (empty($trees) || $update === true) {
            if (empty($data)) {
                $map = [];
                $map['status'] = 1;
                $map['nav'] = 1;
                $map['uid'] = ['in', '0,' . ADMIN_ID];
                $data = self::where($map)->order('sort asc')->column('id,pid,module,title,url,param,target,icon');
                $data = array_values($data);
            }

            foreach ($data as $k => $v) {
                if ($v['pid'] == $pid) {
                    if ($level == 3) {
                        return $trees;
                    }
                    // 过滤没访问权限的节点
                    if (!RoleModel::checkAuth($v['id'])) {
                        unset($data[$k]);
                        continue;
                    }
                    // 多语言支持
                    if (config('sys.multi_language') == 1) {
                        $title = Db::name('admin_menu_lang')->where(['menu_id' => $v['id'], 'lang' => dblang('admin')])->value('title');
                        if ($title) {
                            $v['title'] = $title;
                        }
                    }
                    unset($data[$k]);
                    $v['childs'] = self::getMainMenu($update, $v['id'], $level + 1, $data);
                    $trees[] = $v;
                }
            }
            // 非开发模式，缓存菜单
            if (config('develop.app_debug') == 0) {
                cache($cache_tag, $trees);
            }
        }

        return $trees;
    }

    public static function getBrandCrumbs($id)
    {
        if (!$id) {
            return false;
        }
        $map = $menu = [];
        $map['id'] = $id;
        $row = self::where($map)->field('id,pid,title,url,param')->find();
        if ($row->pid > 0) {
            if (isset($row->lang->title)) {
                $row->title = $row->lang->title;
            }
            $menu[] = $row;
            $childs = self::getBrandCrumbs($row->pid);
            if ($childs) {
                $menu = array_merge($childs, $menu);
            }
        }
        return $menu;
    }

    public static function getInfo($id = 0)
    {
        $map = [];
        if (empty($id)) {
            $model = request()->module();
            $controller = request()->controller();
            $action = request()->action();
            $map['url'] = $model . '/' . $controller . '/' . $action;
        } else {
            $map['id'] = (int)$id;
        }
        $map['status'] = 1;
        $rows = self::where($map)->column('id,title,url,param');

        if (!$rows) {
            return false;
        }
        sort($rows);
        if (count($rows) > 1) {
            $_get = input('param.');
            if (!$_get) {
                foreach ($rows as $k => $v) {
                    if ($v['param'] == '') {
                        return $rows[$k];
                    }
                }
            }
            foreach ($rows as $k => $v) {
                if ($v['param']) {
                    parse_str($v['param'], $param);
                    ksort($param);
                    $param_arr = [];
                    foreach ($param as $kk => $vv) {
                        if (isset($_get[$kk])) {
                            $param_arr[$kk] = $_get[$kk];
                        }
                    }
                    $sqlmap = [];
                    $sqlmap['param'] = http_build_query($param_arr);
                    $sqlmap['url'] = $map['url'];
                    $res = self::where($sqlmap)->field('id,title,url,param')->find();
                    if ($res) {
                        return $res;
                    }
                }
            }
            $map['param'] = '';
            $res = self::where($map)->field('id,title,url,param')->find();
            if ($res) {
                return $res;
            } else {
                return false;
            }
        }
        return $rows[0];
    }

    public static function getParents($id = 0)
    {
        $map = [];
        if (empty($id)) {
            $model = request()->module();
            $controller = request()->controller();
            $action = request()->action();
            $map['url'] = $model . '/' . $controller . '/' . $action;
        } else {
            $map['id'] = (int)$id;
        }
        $res = self::where($map)->find();
        if ($res['pid'] > 0) {
            $id = self::getParents($res['pid']);
        } else {
            $id = $res['id'];
        }
        return $id;
    }

    public function del($ids = '')
    {
        if (is_array($ids)) {
            $error = '';
            foreach ($ids as $k => $v) {
                $map = [];
                $map['id'] = $v;
                $map['cid'] = session('admin_user.cid');
                $row = self::where($map)->find();
                if (self::where('pid', $row['id'])->find()) {
                    $error .= '[' . $row['name'] . ']请先删除下级菜单<br>';
                    continue;
                }
                if (AdminUser::where('department_id', $row['id'])->find()) {
                    $error .= '[' . $row['name'] . ']请先删除部门下用户<br>';
                    continue;
                }
                self::where($map)->delete();
            }
            if ($error) {
                $this->error = $error;
                return false;
            }
            return true;
        }
        $this->error = '参数传递错误';
        return false;
    }

    public function delUser($uid = 0)
    {
        $uid = (int)$uid;
        if ($uid <= 0) {
            $this->error = '参数传递错误';
            return false;
        }
        $rows = self::where('uid', $uid)->column('id,title');
        foreach ($rows as $key => $v) {
            // 删除多语言
            Db::name('admin_menu_lang')->where('menu_id', $v['id'])->delete();
        }
        self::getMainMenu(true);
        return self::where('uid', $uid)->delete();
    }

    public static function importMenu($data = [], $mod = '', $type = 'module', $pid = 0)
    {
        if (empty($data)) {
            return true;
        }

        if ($type == 'module') {// 模型菜单
            foreach ($data as $v) {
                if (!isset($v['pid'])) {
                    $v['pid'] = $pid;
                }

                $childs = '';
                if (isset($v['childs'])) {
                    $childs = $v['childs'];
                    unset($v['childs']);
                }
                $res = model('AdminMenu')->storage($v);
                if (!$res) {
                    return false;
                }
                if (!empty($childs)) {
                    self::importMenu($childs, $mod, $type, $res['id']);
                }
            }
        } else {// 插件菜单
            if ($pid == 0) {
                $pid = 3;
                // if (!empty($data[0]) && !isset($data[0]['childs'])) {
                //     $pid = 5;
                // }
            }
            foreach ($data as $v) {
                if (empty($v['param']) && empty($v['url'])) {
                    return false;
                }
                if (!isset($v['pid'])) {
                    $v['pid'] = $pid;
                }
                $v['module'] = $mod;
                $childs = '';
                if (isset($v['childs'])) {
                    $childs = $v['childs'];
                    unset($v['childs']);
                }
                $res = model('AdminMenu')->storage($v);
                if (!$res) {
                    return false;
                }
                if (!empty($childs)) {
                    self::importMenu($childs, $mod, $type, $res['id']);
                }
            }
        }
        self::getMainMenu(true);
        return true;
    }
}