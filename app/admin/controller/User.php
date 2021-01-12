<?php
namespace app\admin\controller;

use app\admin\model\AdminCompany;
use app\admin\model\AdminDepartment;
use app\admin\model\AdminUser as UserModel;
use app\admin\model\AdminRole as RoleModel;
use app\admin\model\AdminMenu as MenuModel;
use app\admin\model\AdminUserDefault;
use app\admin\model\WorkCat;
use app\admin\model\WorkItem;
use app\common\service\Service;
use think\Db;
use think\Exception;
use think\Validate;
use app\admin\model\JobCat as JobCatModel;
use app\admin\model\JobItem as JobItemModel;
use app\admin\model\UserLogin as UserLoginModel;

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

            $role_id = session('admin_user.role_id');
            $cid = session('admin_user.cid');
            $uid = session('admin_user.uid');
            if (1 != $role_id){
                $where['company_id'] = $cid;
            }
            if (4 == $cid && 89 != $uid){
                $where['id'] = session('admin_user.uid');
            }
            if ($role_id > 4){
                $where['id'] = session('admin_user.uid');
            }

            $order = 'status desc,id desc';

            $data['data'] = UserModel::with('role')->with('dep')->where($where)->order($order)->page($page)->limit($limit)->select();
            if ($data['data']){
                foreach ($data['data'] as $k=>$v){
                    $data['data'][$k]['job_item'] = !empty($v['job_item']) ? JobItemModel::getItem()[$v['job_item']] : '无';
                    $data['data'][$k]['work_cat'] = !empty($v['work_cat']) ? WorkCat::getItem()[$v['work_cat']] : '无';
                }
            }
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

    public function other($q = '')
    {
        if ($this->request->isAjax()) {
            if (!in_array(session('admin_user.uid'),[21,31])){
                return $this->error('暂无相关数据');
            }
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
//            $where['is_show'] = ['eq', 0];
            $where['company_id'] = ['neq', 2];

            $order = 'times desc,status desc,id desc';

            $data['data'] = UserModel::with('role')->with('dep')->where($where)->order($order)->page($page)->limit($limit)->select();
            if ($data['data']){
                foreach ($data['data'] as $k=>$v){
                    $data['data'][$k]['job_item'] = !empty($v['job_item']) ? JobItemModel::getItem()[$v['job_item']] : '无';
                    $data['data'][$k]['work_cat'] = !empty($v['work_cat']) ? WorkCat::getItem()[$v['work_cat']] : '无';
                }
            }
            $data['count'] = UserModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        // 分页
        $tab_data['menu'] = [
            [
                'title' => '其他账户',
                'url' => 'admin/user/other',
            ],
        ];
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

            if (empty($data['department_id'])){
                return $this->error('请选择部门');
            }else{
                $dep_id = implode(',',array_filter(explode(',',$data['department_id'])));
            }
            if (!is_numeric($dep_id)){
                return $this->error('请重新选择部门');
            }

            $data['last_login_ip'] = '';
            $data['auth'] = '';
            $data['company_id'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $data['department_id'] = $dep_id;
            // 验证
            $result = $this->validate($data, 'AdminUser');
            unset($data['id'], $data['password_confirm']);
            if($result !== true) {
                return $this->error($result);
            }
            $u = UserModel::create($data);
            if (!$u) {
                return $this->error('添加失败');
            }
            $tmp = [
                'id' => $u['id'],
                'id_card' => date('Y').$u['id'],
            ];
            UserModel::update($tmp);
            $score = [
                'subject_id' => 0,
                'project_id' => 0,
                'cid' => $data['company_id'],
                'project_code' => '',
                'user' => $u['id'],
                'gl_add_score' => config('other.gl_give'),
                'remark' => "新入职用户所得GL",
                'user_id' => 0,
                'is_lock' => 0,
                'create_time' => time(),
                'update_time' => time(),
            ];
            db('score')->insert($score);
            return $this->success("操作成功{$this->score_value}",url('index'));
        }

        $tab_data = [];
        $tab_data['menu'] = [
            ['title' => '添加用户'],
        ];
        $this->assign('menu_list', '');
        $this->assign('role_option', RoleModel::getOption());
        $this->assign('rule_option',JobCatModel::getOption1());
        $this->assign('work_option',WorkItem::getOption());
        $this->assign('company_option', AdminCompany::getOption());
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 2);
        $this->assign('sex_type', UserModel::getSexOption());
        return $this->fetch('userform');
    }

    public function getJobItem($id=0,$gid=0){
        $child_option = JobItemModel::getChilds($id,$gid);
        echo json_encode($child_option);
    }

    public function editUser($id = 0)
    {
        if ($id == 1 && ADMIN_ID != $id) {
            return $this->error('禁止修改超级管理员');
        }
        $cid = session('admin_user.cid');
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (empty($data['department_id'])){
                return $this->error('请选择部门');
            }else{
                $dep_id = implode(',',array_filter(explode(',',$data['department_id'])));
            }
            if (!is_numeric($dep_id)){
                return $this->error('请重新选择部门');
            }
            $data['department_id'] = $dep_id;
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

            if ($data['password'] == '') {
                unset($data['password']);
            }
            $data['company_id'] = $cid;
            // 验证
            $result = $this->validate($data, 'AdminUser.update');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['password_confirm'],$data['dep_name'],$data['is_auth']);
            if (!UserModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功',url('index'));
        }

        $row = UserModel::where('id', $id)->find()->toArray();
        $dep_role = AdminDepartment::where('id', $row['department_id'])->find()->toArray();
        $auth = RoleModel::where('id', $row['role_id'])->find()->toArray();
        if (!in_array($cid,[1,6])){
            if ($row['role_id'] >= 6){
                $role_other = $auth;
            }else{
                $role_other = RoleModel::where('id', 6)->find()->toArray();
            }
            $row['auth'] = $row['auth'] ? json_decode($row['auth']) : ($dep_role['auth'] ? json_decode($dep_role['auth']) : json_decode($role_other['auth']));
        }else{
            $row['auth'] = $row['auth'] ? json_decode($row['auth']) : ($dep_role['auth'] ? json_decode($dep_role['auth']) : json_decode($auth['auth']));
        }
//        if (!$row['auth']) {
//            $auth = RoleModel::where('id', $row['role_id'])->value('auth');
//            $row['auth'] = json_decode($auth);
//        } else {
//            $row['auth'] = json_decode($row['auth']);
//        }
//        $row['auth'] = json_decode($row['auth']);

        $tab_data = [];
        $tab_data['menu'] = [
            ['title' => '修改用户'],
            ['title' => '设置权限'],
        ];
        if ($row['department_id']){
            $row['department_select_id'] = AdminDepartment::getRowById($row['department_id'])['name'];
        }
//        $row['job_item'] = !empty($row['job_item']) ? JobItemModel::getItem()[$row['job_item']] : '无';

        $this->assign('menu_list', MenuModel::getAllChild());
        $this->assign('role_option', RoleModel::getOption());
        $this->assign('work_option',WorkItem::getOption($row['work_cat']));
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 2);
        $this->assign('role_option', RoleModel::getOption($row['role_id']));
        $this->assign('company_option', AdminCompany::getOption());
        $this->assign('rule_option',JobCatModel::getOption1());
        $this->assign('data_info', $row);
        $this->assign('sex_type', UserModel::getSexOption());
        return $this->fetch('editform');
    }

    public function info()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['id'] = ADMIN_ID;
            // 防止伪造
            unset($data['role_id'], $data['status'],$data['cname'], $data['dep_name'], $data['last_login_time']);

            if ($data['password'] == '') {
                unset($data['password']);
            }

            // 验证
            $result = $this->validate($data, 'AdminUser.info');
            if($result !== true) {
                return $this->error($result);
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
        $row['job_item'] = !empty($row['job_item']) ? JobItemModel::getItem()[$row['job_item']] : '无';
        $this->assign('sex_type', UserModel::getSexOption());
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

            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'AdminRole');
            if($result !== true) {
                return $this->error($result);
            }
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
        $this->assign('sys_type', RoleModel::getSysType());
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

            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'AdminRole');
            if($result !== true) {
                return $this->error($result);
            }
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
        $row = RoleModel::where('id', $id)->field('id,name,intro,auth,status,sys_type')->find()->toArray();
        $row['auth'] = json_decode($row['auth']);
        $this->assign('data_info', $row);
        $this->assign('menu_list', MenuModel::getAllChild());
        $this->assign('sys_type', RoleModel::getSysType($row['sys_type']));
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
            $data['accountant_user'] = trim($data['accountant_user'],',');
            $data['hr_user'] = trim($data['hr_user'],',');
            $data['hr_finance_user'] = trim($data['hr_finance_user'],',');
            $data['own_user'] = trim($data['own_user'],',');
            $data['assignment_user'] = trim($data['assignment_user'],',');
            $data['finance1_user'] = trim($data['finance1_user'],',');
            $data['finance2_user'] = trim($data['finance2_user'],',');
            $data['finance3_user'] = trim($data['finance3_user'],',');
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

        $fields = 'cid,manager_user,send_user,deal_user,copy_user,finance_user,accountant_user,hr_user,hr_finance_user,own_user,assignment_user,finance1_user,finance2_user,finance3_user';
        $row = AdminUserDefault::field($fields)->where('cid',$cid)->find();
        if ($row){
            $row['manager_user_id'] = $this->deal_user($row['manager_user']);
            $row['send_user_id'] = $this->deal_user($row['send_user']);
            $row['deal_user_id'] = $this->deal_user($row['deal_user']);
            $row['copy_user_id'] = $this->deal_user($row['copy_user']);
            $row['finance_user_id'] = $this->deal_user($row['finance_user']);
            $row['accountant_user_id'] = $this->deal_user($row['accountant_user']);
            $row['hr_user_id'] = $this->deal_user($row['hr_user']);
            $row['hr_finance_user_id'] = $this->deal_user($row['hr_finance_user']);
            $row['own_user_id'] = $this->deal_user($row['own_user']);
            $row['assignment_user_id'] = $this->deal_user($row['assignment_user']);
            $row['finance1_user_id'] = $this->deal_user($row['finance1_user']);
            $row['finance2_user_id'] = $this->deal_user($row['finance2_user']);
            $row['finance3_user_id'] = $this->deal_user($row['finance3_user']);
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

    public function spreadStatistics()
    {
        $params = $this->request->param();
        $uid = session('admin_user.uid');
        $cid = session('admin_user.cid');
        $role_id = session('admin_user.role_id');
//        $d = date('Y-m-d',strtotime('-30 day')).' - '.date('Y-m-d');
        $d = '';
        $where = [];
        if (isset($params['search_date']) && !empty($params['search_date'])) {
            $d = $params['search_date'];
            $d_arr = explode(' - ', $d);
            $d0 = strtotime($d_arr[0] . ' 00:00:00');
            $d1 = strtotime($d_arr[1] . ' 23:59:59');
            $where = [
                'create_time' => ['between', [$d0, $d1]]
            ];
        }
//        if (!in_array($uid,[21,31])){
//            $mobile = UserModel::field('mobile')->where(['id'=>$uid])->find();
//            $where['tuijianren'] = $mobile['mobile'];
//        }
        if ($role_id > 4 || $cid != 2){
            $mobile = UserModel::field('mobile')->where(['id'=>$uid])->find();
            $where['tuijianren'] = $mobile['mobile'];
        }
        $p = isset($params['page']) ? $params['page'] : 1;
        $data_list = UserModel::field('tuijianren,COUNT(id) as num')->where($where)->where('tuijianren IS NOT NULL OR tuijianren != NULL')->group('tuijianren')->paginate(30, false, ['query' => input('get.')]);
        if ($data_list) {
            $user_name = UserModel::column('realname', 'mobile');
            foreach ($data_list as $k => $v) {
                $v['xuhao'] = ($p - 1) * 30 + $k + 1;
                $v['name'] = isset($user_name[$v['tuijianren']]) ? $user_name[$v['tuijianren']] : '无';
            }
        }
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
        $this->assign('d', $d);
        return $this->fetch();
    }

    public function spread()
    {
        $params = $this->request->param();
        $where = [
            'tuijianren' => $params['tuijianren'],
        ];

        if (isset($params['search_date']) && !empty($params['search_date'])) {
            $search_date = explode(' - ', urldecode($params['search_date']));
            $where['create_time'] = ['between', [strtotime($search_date[0] . ' 00:00:00'), strtotime($search_date[1] . ' 23:59:59')]];
        }else{
            $params['search_date'] = '';
        }
        $data_list = UserModel::where($where)->paginate(30, false, ['query' => input('get.')]);
        $p = isset($params['page']) ? $params['page'] : 1;
        if ($data_list) {
            $login_count = UserLoginModel::group('user_id')->column('count(user_id)', 'user_id');
            foreach ($data_list as $k => $v) {
                $v['xuhao'] = ($p - 1) * 30 + $k + 1;
                $v['num'] = isset($login_count[$v['id']]) ? $login_count[$v['id']] : '0';
                $v['com_name'] = AdminCompany::getCompanyById($v['company_id'])['name'];
            }
        }
        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
        $this->assign('d', urldecode($params['search_date']));
        return $this->fetch();
    }

    public function status() {
        $val   = input('param.val');
        $ids   = input('param.ids/a') ? input('param.ids/a') : input('param.id/a');
        $table = input('param.table');
        $f = input('param.f');
        $f = empty($f) ? 'status' : $f;
        $field = input('param.field', $f);

        if (empty($ids)) {
            return $this->error('参数传递错误[1]！');
        }
        if (empty($table)) {
            return $this->error('参数传递错误[2]！');
        }
        // 以下表操作需排除值为1的数据
        if ($table == 'admin_menu' || $table == 'admin_user' || $table == 'admin_role' || $table == 'admin_module') {
            if (in_array('1', $ids) || ($table == 'admin_menu' && in_array('2', $ids))) {
                return $this->error('系统限制操作');
            }
        }
        // 获取主键
        $pk = Db::name($table)->getPk();
        $map = [];
        $map[$pk] = ['in', $ids];
        $map1['user_id'] = ['in', $ids];

        //事务开始
        Db::startTrans();
        try{
            $res = Db::name($table)->where($map)->setField($field, $val);
            Db::name('user_info')->where($map1)->setField($field, $val);
            Db::name('computer')->where($map1)->setField($field, $val);
            //提交事务
            Db::commit();
        }catch (Exception $e){
            //回滚事务
            Db::rollback();
        }

        if ($res === false) {
            return $this->error('状态设置失败');
        }
        return $this->success('状态设置成功');
    }
}
