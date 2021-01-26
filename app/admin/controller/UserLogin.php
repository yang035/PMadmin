<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;

use app\admin\model\AdminUser;
use app\admin\model\AdminCompany;
use app\admin\model\UserLogin as UserLoginModel;

class UserLogin extends Admin
{
    public $tab_data = [];

    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '登录日志',
                'url' => 'admin/UserLogin/index',
            ],
        ];
        $this->tab_data = $tab_data;
        $this->assign('user_select', AdminUser::inputSearchUser1());
    }

    public function index($q = '')
    {
        $real_name = '';
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 20);
            $params = $this->request->param();

            if (isset($params['user_id']) && !empty($params['user_id'])) {
                $where['user_id'] = $params['user_id'];
            }
            $cid = session('admin_user.cid');
            if (6 != $cid){
                $where['cid'] = session('admin_user.cid');
            }
            $order = 'login_time desc';
            $data['data'] = UserLoginModel::where($where)->page($page)->order($order)->limit($limit)->select();
            if ($data['data']) {
                foreach ($data['data'] as $k => $v) {
                    $v['real_name'] = !empty($v['user_id']) ? AdminUser::getUserById($v['user_id'])['realname'] : '无';
                    $v['com_name'] = AdminCompany::getCompanyById($v['cid'])['name'];
                }
            }
            $data['count'] = UserLoginModel::where($where)->count('*');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        // 分页
        $tab_data = $this->tab_data;
        $tab_data['current'] = url('');

        $this->assign('real_name', $real_name);
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        return $this->fetch();
    }
}