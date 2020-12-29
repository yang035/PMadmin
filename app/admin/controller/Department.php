<?php
namespace app\admin\controller;

use app\admin\model\AdminDepartment;
use app\admin\model\AdminMenu as MenuModel;
use app\admin\model\AdminUser;
use app\admin\model\AdminRole as RoleModel;

class Department extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '机构管理',
                'url' => 'admin/department/index',
            ],
        ];
        $this->tab_data = $tab_data;
    }
    public function index()
    {
        $cid = session('admin_user.cid');
        $list = AdminDepartment::getDepUser1($cid);
        if ($this->request->isAjax()) {
            $data = [];
            $data['code'] = 0;
            $data['msg'] = 'ok';
            $data['data'] = $list;
            return json($data);
        }
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 2);
        return $this->fetch();
    }
    public function userList()
    {
        $list = AdminUser::userList();
        if ($this->request->isAjax()) {
            $data = [];
            $data['code'] = 0;
            $data['msg'] = 'ok';
            $data['data'] = $list;
            return json($data);
        }
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 2);
        return $this->fetch();
    }
    public function index1()
    {
        $department_list = AdminDepartment::getAllChild(0, 0);
        $this->assign('department_list', $department_list);
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 2);
        return $this->fetch();
    }

    public function add($pid = '')
    {
        $params = $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['cid'] = session('admin_user.cid');
            $data['pid'] = $data['id'];
            $data['code'] = $this->getCode($data['code'],$data['pid']);
            $code_arr = array_slice(explode('d','0d'.$data['code']),0,-2);
            $data['path'] = implode(',',array_unique($code_arr));
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'AdminDepartment');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
            if (!AdminDepartment::create($data)) {
                return $this->error('添加失败！');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $this->assign('menu_option', self::departmentOption($pid));
        $this->view->engine->layout(true);
        return $this->fetch();
    }

    public function getCode($pcode='',$pid=0){
        $result = AdminDepartment::getRowById($pid);
        if ($result['code'].$pid.'d' == $pcode){
            return $pcode;
        }else{
            return $result['code'].$pid.'d';
        }
    }
    public function edit($id = 0)
    {
        $params = $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['cid'] = session('admin_user.cid');
            $data['code'] = $this->getCode($data['code'],$data['pid']);
            $code_arr = array_slice(explode('d','0d'.$data['code']),0,-2);
            $data['path'] = implode(',',array_unique($code_arr));
            // 验证
            $result = $this->validate($data, 'AdminDepartment');
            if($result !== true) {
                return $this->error($result);
            }

            if (!AdminDepartment::update($data)) {
                return $this->error('修改失败！');
            }
            return $this->success('修改成功。',url('index'));
        }
        if ($params['pid'] == 0){
            return $this->error('顶级公司禁止修改','index');
        }
        $row = AdminDepartment::getRowById($id);
        $prow = AdminDepartment::getRowById($params['pid']);
        $row['pname'] = $prow['name'];
        $this->assign('data_info', $row);
//        $this->assign('menu_option', self::departmentOption($row['pid']));
        return $this->fetch();
    }

    public function depAuth($id = 0)
    {
        $params = $this->request->param();
        if ($id <= 1) {
            return $this->error('禁止编辑');
        }

        $cid = session('admin_user.uid');
        if ($this->request->isPost()) {
            $data = $this->request->post();

            // 当前登陆用户不可更改自己的分组角色
            if (ADMIN_ROLE > 3) {
                return $this->error('禁止修改当前角色(原因：您不是超级管理员或公司管理员)');
            }
            $data['user_id'] = $cid;
            if (isset($data['auth'])){
                $data['auth'] = json_encode($data['auth']);
            }else{
                $data['auth'] = null;
            }
            if (!AdminDepartment::update($data)) {
                return $this->error('修改失败');
            }

            // 更新权限缓存
            cache('role_auth_'.ADMIN_ROLE, $data['auth']);

            return $this->success('修改成功');
        }
        $tab_data = [];
        $tab_data['menu'] = [
            ['title' => '设置权限'],
        ];
        $row = AdminDepartment::where('id', $params['id'])->field('id,auth')->find()->toArray();
        $auth = RoleModel::where('id', 6)->find()->toArray();
        $row['auth'] = $row['auth'] ? json_decode($row['auth']) : json_decode($auth['auth']);
//        if ($row['auth']){
//            $auth = json_decode($row['auth']);
//        }else{
//            $auth = AdminUser::getdepAuth($id);
//        }
//        $row['auth'] = json_decode($auth);

        $this->assign('data_info', $row);
        $this->assign('menu_list', MenuModel::getAllChild());
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 2);
        return $this->fetch();
    }

    private function departmentOption($id = '', $str = '')
    {
        $menus = AdminDepartment::getAllChild();
        foreach ($menus as $v) {
            if ($id == $v['id']) {
                $str .= '<option level="1" value="'.$v['id'].'" selected>'.$v['name'].'</option>';
            } else {
                $str .= '<option level="1" value="'.$v['id'].'">'.$v['name'].'</option>';
            }
            if ($v['child']) {
                foreach ($v['child'] as $vv) {
                    if ($id == $vv['id']) {
                        $str .= '<option level="2" value="'.$vv['id'].'" selected>&nbsp;&nbsp;'.$vv['name'].'</option>';
                    } else {
                        $str .= '<option level="2" value="'.$vv['id'].'">&nbsp;&nbsp;'.$vv['name'].'</option>';
                    }
                    if ($vv['child']) {
                        foreach ($vv['child'] as $vvv) {
                            if ($id == $vvv['id']) {
                                $str .= '<option level="3" value="'.$vvv['id'].'" selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$vvv['name'].'</option>';
                            } else {
                                $str .= '<option level="3" value="'.$vvv['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$vvv['name'].'</option>';
                            }
                        }
                    }
                }
            }
        }
        return $str;
    }

    public function del()
    {
        $id = input('param.ids/a');
        $model = new AdminDepartment();
        if ($model->del($id)) {
            return $this->success('删除成功。');
        }
        return $this->error($model->getError());
    }

    public function export()
    {
        $id = input('param.id/d');
        $map = [];
        $map['id'] = $id;
        $menu = MenuModel::where($map)->field('pid,title,icon,module,url,param,target,debug,system,nav,sort')->find()->toArray();
        if (!$menu) {
            return $this->error('模块不存在！');
        }
        if ($menu['pid'] > 0 && $menu['url'] != 'admin/plugins/run') {
            return $this->error('只能通过顶级菜单导出！');
        }
        if ($menu['url'] == 'admin/plugins/run' && MenuModel::where('id', $menu['pid'])->value('url') == 'admin/plugins/run') {
            return $this->error('只能通过顶级菜单导出！');
        }
        unset($menu['pid'], $menu['id']);
        $menus = [];
        $menus[0] = $menu;
        $menus[0]['childs'] = MenuModel::getAllChild($id, 0, 'id,pid,title,icon,module,url,param,target,debug,system,nav,sort');
        $menus = self::menuReor($menus);
        $menus = json_decode(json_encode($menus, 1), 1);
        // 美化数组格式
        $menus = var_export($menus, true);
        $menus = preg_replace("/(\d+|'id') =>(.*)/", '', $menus);
        $menus = preg_replace("/'childs' => (.*)(\r\n|\r|\n)\s*array/", "'childs' => $1array", $menus);
        $menus = str_replace(['array (', ')'], ['[', ']'], $menus);
        $menus = preg_replace("/(\s*?\r?\n\s*?)+/", "\n", $menus);
        $str = json_indent(json_encode($menus, 1));
        
        $str = "<?php\nreturn ".$menus.";\n";
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="menu.php"');
        header('Content-Length:'.strLen($str));
        echo $str;
    }

    public function quick()
    {
        $id = input('param.id/d');
        if (!$id) {
            return $this->error('参数传递错误！');
        }
        $map = [];
        $map['id'] = $id;
        
        $row = MenuModel::where($map)->find()->toArray();
        if (!$row) {
            return $this->error('您添加的菜单不存在！');
        }
        
        unset($row['id'], $map['id']);
        $map['url'] = $row['url'];
        $map['param'] = $row['param'];
        $map['uid'] = ADMIN_ID;
        $row['pid'] = $map['pid'] = 4;
        if (MenuModel::where($map)->find()) {
            return $this->error('您已添加过此快捷菜单！');
        }
        $row['uid'] = ADMIN_ID;
        $row['debug'] = 0;
        $row['system'] = 0;
        $row['ctime'] = time();
        $model = new MenuModel();
        $res = $model->storage($row);
        if ($res === false) {
            return $this->error('快捷菜单添加失败！');
        }
        return $this->success('快捷菜单添加成功。');
    }

    private static function menuReor($data = [])
    {
        $menus = [];
        foreach ($data as $k => $v) {
            if (isset($v['pid'])) {
                unset($v['pid']);
            }
            if (isset($v['childs']) && !empty($v['childs'])) {
                $v['childs'] = self::menuReor($v['childs']);
            } else if (isset($v['childs'])) {
                unset($v['childs']);
            }
            $menus[] = $v;
        }
        return $menus;
    }
}
