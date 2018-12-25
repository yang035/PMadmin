<?php
namespace app\admin\controller;

use app\admin\model\AdminCompany;
use app\admin\model\AdminDepartment;
use app\admin\model\AdminUser as UserModel;
use app\admin\model\AdminRole as RoleModel;
use app\admin\model\AdminMenu as MenuModel;
use app\admin\model\AdminUserDefault;
use app\common\service\Service;
use think\Validate;

class User extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '用户角色',
                'url' => 'admin/user/role',
            ],
            [
                'title' => '用户列表',
                'url' => 'admin/user/index',
            ],
            [
                'title' => '默认人员配置',
                'url' => 'admin/user/userDefault',
            ],
        ];
        $this->tab_data = $tab_data;
    }



    public function index($q = '')
    {
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 15);
            if ($q) {
                if (preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $q)) {// 邮箱
                    $where['email'] = $q;
                } elseif (preg_match("/^1\d{10}$/", $q)) {// 手机号
                    $where['mobile'] = $q;
                } else {// 用户名、昵称
                    $where['username'] = ['like', '%'.$q.'%'];
                }
            }
            $where['id'] = ['neq', 1];
            $where['is_show'] = ['eq', 0];

            if (1 != session('admin_user.role_id')){
                $where['company_id'] = session('admin_user.cid');
            }

            $data['data'] = UserModel::with('role')->with('dep')->where($where)->page($page)->limit($limit)->select();
            $data['count'] = UserModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        // 分页
        $tab_data = $this->tab_data;
        $tab_data['current'] = url('');

        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        return $this->fetch();
    }

    public function iframe()
    {
        $val = UserModel::where('id', ADMIN_ID)->value('iframe');
        if ($val == 1) {
            $val = 0;
        } else {
            $val = 1;
        }
        if (!UserModel::where('id', ADMIN_ID)->setField('iframe', $val)) {
            return $this->error('切换失败');
        }
        cookie('hisi_iframe', $val);
        return $this->success('请稍等，页面切换中...', url('admin/index/index'));
    }

    public function setTheme()
    {
        $theme = input('param.theme', 0);
        if (UserModel::setTheme($theme, true) === false) {
            return $this->error('设置失败');
        }
        return $this->success('设置成功');
    }

    public function addUser()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'AdminUser');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['id'], $data['password_confirm'],$data['dep_name'],$data['is_auth']);
            $data['last_login_ip'] = '';
            $data['auth'] = '';
            $data['company_id'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            if (!UserModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}",url('index'));
        }

        $tab_data = [];
        $tab_data['menu'] = [
            ['title' => '添加用户'],
        ];
        $this->assign('menu_list', '');
        $this->assign('role_option', RoleModel::getOption());
        $this->assign('company_option', AdminCompany::getOption());
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 2);
        return $this->fetch('userform');
    }

    public function editUser($id = 0)
    {
        if ($id == 1 && ADMIN_ID != $id) {
            return $this->error('禁止修改超级管理员');
        }

        if ($this->request->isPost()) {
            $data = $this->request->post();

            if (!isset($data['auth'])) {
                $data['auth'] = '';
            }
            //为0不变更权限
            if (0 == $data['is_auth']){
                $data['auth'] = '';
            }
            $row = UserModel::where('id', $id)->field('role_id,auth')->find();
            if ($data['id'] == 1 || ADMIN_ID == $id) {// 禁止更改超管角色，当前登陆用户不可更改自己的角色和自定义权限
                unset($data['role_id'], $data['auth']);
                if (!$row['auth']) {
                    $data['auth'] = '';
                }
            } else if ($row['role_id'] != $data['role_id']) {// 如果分组不同，自定义权限无效
                $data['auth'] = '';
            }

            if (isset($data['role_id']) && RoleModel::where('id', $data['role_id'])->value('auth') == json_encode($data['auth'])) {// 如果自定义权限与角色权限一致，则设置自定义权限为空
                $data['auth'] = '';
            }
            // 验证
            $result = $this->validate($data, 'AdminUser.update');
            if($result !== true) {
                return $this->error($result);
            }

            if ($data['password'] == '') {
                unset($data['password']);
            }
            unset($data['password_confirm'],$data['dep_name'],$data['is_auth']);
            $data['company_id'] = session('admin_user.cid');
            if (!UserModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功',url('index'));
        }

        $row = UserModel::where('id', $id)->find()->toArray();
        if (!$row['auth']) {
            $auth = RoleModel::where('id', $row['role_id'])->value('auth');
            $row['auth'] = json_decode($auth);
        } else {
            $row['auth'] = json_decode($row['auth']);
        }
        $tab_data = [];
        $tab_data['menu'] = [
            ['title' => '修改用户'],
            ['title' => '设置权限'],
        ];
        if ($row['department_id']){
            $row['dep_name'] = AdminDepartment::getRowById($row['department_id'])['name'];
        }

        $this->assign('menu_list', MenuModel::getAllChild());
        $this->assign('role_option', RoleModel::getOption());
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 2);
        $this->assign('role_option', RoleModel::getOption($row['role_id']));
        $this->assign('company_option', AdminCompany::getOption());
        $this->assign('data_info', $row);
        return $this->fetch('userform');
    }

    public function info()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['id'] = ADMIN_ID;
            // 防止伪造
            unset($data['role_id'], $data['status'],$data['cname'], $data['dep_name'], $data['last_login_time']);

            // 验证
            $result = $this->validate($data, 'AdminUser.info');
            if($result !== true) {
                return $this->error($result);
            }

            if ($data['password'] == '') {
                unset($data['password']);
            }
            unset($data['password_confirm']);

            if (!UserModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功');
        }

        $row = UserModel::where('id', ADMIN_ID)->find()->toArray();
        $row['cname'] = AdminCompany::getCompanyById(session('admin_user.cid'))['name'];
        $row['dep_name'] = AdminDepartment::getRowById(session('admin_user.depid'))['name'];
        $this->assign('data_info', $row);
        return $this->fetch();
    }

    public function delUser()
    {
        $id = input('param.id/a');
        $model = new UserModel();
        if (!$model->del($id)) {
            return $this->error($model->getError());
        }
        return $this->success('操作成功');
    }

    public function role()
    {
        if (1 != session('admin_user.role_id')){
            return $this->error('没有权限','index');
        }
        if ($this->request->isAjax()) {
            $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 15);

            $data['data'] = RoleModel::where('id', '<>', 1)->select();
            $data['count'] = RoleModel::where('id', '<>', 1)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        $tab_data = $this->tab_data;
        $tab_data['current'] = url('');
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        return $this->fetch();
    }

    public function addRole()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'AdminRole');
            if($result !== true) {
                return $this->error($result);
            }
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            if (!RoleModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $tab_data = [];
        $tab_data['menu'] = [
            ['title' => '添加角色'],
            ['title' => '设置权限'],
        ];
        $this->assign('menu_list', MenuModel::getAllChild());
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 2);
        return $this->fetch('roleform');
    }

    public function editRole($id = 0)
    {
        if ($id <= 1) {
            return $this->error('禁止编辑');
        }

        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 当前登陆用户不可更改自己的分组角色
            if (ADMIN_ROLE == $data['id']) {
                return $this->error('禁止修改当前角色(原因：您不是超级管理员)');
            }

            // 验证
            $result = $this->validate($data, 'AdminRole');
            if($result !== true) {
                return $this->error($result);
            }
            $data['user_id'] = session('admin_user.uid');
            if (!RoleModel::update($data)) {
                return $this->error('修改失败');
            }

            // 更新权限缓存
            cache('role_auth_'.$data['id'], $data['auth']);

            return $this->success('修改成功');
        }
        $tab_data = [];
        $tab_data['menu'] = [
            ['title' => '修改角色'],
            ['title' => '设置权限'],
        ];
        $row = RoleModel::where('id', $id)->field('id,name,intro,auth,status')->find()->toArray();
        $row['auth'] = json_decode($row['auth']);
        $this->assign('data_info', $row);
        $this->assign('menu_list', MenuModel::getAllChild());
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 2);
        return $this->fetch('roleform');
    }
    public function delRole()
    {
        $id   = input('param.id/a');
        $model = new RoleModel();
        if (!$model->del($id)) {
            return $this->error($model->getError());
        }
        return $this->success('删除成功');
    }

    public function userDefault()
    {
        $cid = session('admin_user.cid');
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $data['manager_user'] = trim($data['manager_user'],',');
            $data['send_user'] = trim($data['send_user'],',');
            $data['deal_user'] = trim($data['deal_user'],',');
            $data['copy_user'] = trim($data['copy_user'],',');
            $data['finance_user'] = trim($data['finance_user'],',');
            $data['hr_user'] = trim($data['hr_user'],',');
            $data['hr_finance_user'] = trim($data['hr_finance_user'],',');
            $data['own_user'] = trim($data['own_user'],',');
            $row = AdminUserDefault::where('cid',$cid)->find();
            if ($row){
                $data['id'] = $row['id'];
                if (!AdminUserDefault::update($data)) {
                    return $this->error('操作失败');
                }
            }else{
                if (!AdminUserDefault::create($data)) {
                    return $this->error('操作失败');
                }
            }
            return $this->success('操作成功');
        }

        $fields = 'cid,manager_user,send_user,deal_user,copy_user,finance_user,hr_user,hr_finance_user,own_user';
        $row = AdminUserDefault::field($fields)->where('cid',$cid)->find();
        if ($row){
            $row['manager_user_id'] = $this->deal_user($row['manager_user']);
            $row['send_user_id'] = $this->deal_user($row['send_user']);
            $row['deal_user_id'] = $this->deal_user($row['deal_user']);
            $row['copy_user_id'] = $this->deal_user($row['copy_user']);
            $row['finance_user_id'] = $this->deal_user($row['finance_user']);
            $row['hr_user_id'] = $this->deal_user($row['hr_user']);
            $row['hr_finance_user_id'] = $this->deal_user($row['hr_finance_user']);
            $row['own_user_id'] = $this->deal_user($row['own_user']);
        }

        if ($row){
            $redis = \service('Redis');
            $redis->set("pm:user:{$cid}",json_encode($row));
        }

        $tab_data = $this->tab_data;
        $tab_data['current'] = url('');
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        $this->assign('data_info', $row);
        return $this->fetch();

    }

    public function deal_user($x_user)
    {
        if (!empty($x_user)){
            $x_user_arr = explode(',',$x_user);
            $x_user = [];
            if ($x_user_arr){
                foreach ($x_user_arr as $key=>$val){
                    $x_user[] = UserModel::getUserById($val)['realname'];
                }
                return implode(',',$x_user);
            }
        }else{
            return '';
        }

    }

}
