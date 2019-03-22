<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;

use app\admin\model\AdminUser;
use app\admin\model\UserInfo as UserInfoModel;
use app\admin\model\Nation as NationModel;
use think\Db;


class UserInfo extends Admin
{
    public $tab_data = [];

    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '洽商情况',
                'url' => 'admin/UserInfo/index',
            ],
        ];
        $this->tab_data = $tab_data;
        $this->assign('user_select', AdminUser::inputSearchUser());
    }

    public function index($q = '')
    {
        $real_name = '';
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 20);
            $params = $this->request->param();

            if (isset($params['user_id'])) {
                $where['user_id'] = $params['user_id'];
                $real_name = $params['real_name'];
            }
            $where['cid'] = session('admin_user.cid');
//            print_r($where);exit();
            $data['data'] = UserInfoModel::where($where)->page($page)->order('id desc')->limit($limit)->select();
            if ($data['data']) {
                foreach ($data['data'] as $k => $v) {
                    $v['real_name'] = AdminUser::getUserById($v['user_id'])['realname'];
//                    $v['report'] = htmlspecialchars_decode($v['report']);
                }
            }
            $data['count'] = UserInfoModel::where($where)->count('id');
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
        return $this->fetch('item');
    }

    public function addItem()
    {
        $params= $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['operator_id'] = session('admin_user.uid');
            $user_id = (int)$data['user_id'];

            if (empty($user_id)){
                return $this->error('请选择人员');
            }

            // 验证
            $result = $this->validate($data, 'UserInfo');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['real_name'],$data['id']);
//            print_r($data);exit();

            if (!UserInfoModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");

        }

        $this->assign('sex_type', UserInfoModel::getSexOption());
        $this->assign('education_type', UserInfoModel::getEducationOption());
        $this->assign('marital_type', UserInfoModel::getMaritalOption());
        $this->assign('nation_type', NationModel::getOption());
        $this->assign('real_name', $params['real_name']);
        return $this->fetch('itemform');
    }

    public function editItem($id = 0)
    {
        $params= $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['operator_id'] = session('admin_user.uid');

            if (empty($data['user_id'])){
                return $this->error('请选择人员');
            }
            // 验证
            $result = $this->validate($data, 'UserInfo');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['real_name']);
            if (!UserInfoModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功');
        }

        if ($id) {
            $row = UserInfoModel::where('id', $id)->find()->toArray();
            $row['real_name'] = AdminUser::getUserById($row['user_id'])['realname'];
            $this->assign('data_info', $row);
        }
        $this->assign('sex_type', UserInfoModel::getSexOption());
        $this->assign('education_type', UserInfoModel::getEducationOption());
        $this->assign('marital_type', UserInfoModel::getMaritalOption());
        $this->assign('nation_type', NationModel::getOption());
        $this->assign('real_name', $row['real_name']);
        return $this->fetch('itemform');
    }

    public function read($id = 0)
    {
        $row = UserInfoModel::where('id', $id)->find()->toArray();
        $row['real_name'] = AdminUser::getUserById($row['user_id'])['realname'];
        $this->assign('sex_type', UserInfoModel::getSexOption());
        $this->assign('education_type', UserInfoModel::getEducationOption());
        $this->assign('marital_type', UserInfoModel::getMaritalOption());
        $this->assign('nation_type', NationModel::getOption());
        $this->assign('data_list', $row);
        return $this->fetch();
    }

    public function delItem()
    {
        $id = input('param.id/a');
        $model = new UserInfoModel();
        if (!$model->del($id)) {
            return $this->error($model->getError());
        }
        return $this->success('删除成功');
    }

    public function cat()
    {
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 15);
            $keyword = input('param.keyword');
            if ($keyword) {
                $where['name'] = ['like', "%{$keyword}%"];
            }
            $where['cid'] = session('admin_user.cid');
            $data['data'] = CatModel::where($where)->page($page)->limit($limit)->select();
            $data['count'] = CatModel::where($where)->count('id');
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

    public function addCat()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'ContactsCat');
            if ($result !== true) {
                return $this->error($result);
            }
            if (!CatModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        return $this->fetch('catform');
    }

    public function editCat($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'ContactsCat');
            if ($result !== true) {
                return $this->error($result);
            }
            if (!CatModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功', url('cat'));
        }

        $row = CatModel::where('id', $id)->find()->toArray();
        $this->assign('data_info', $row);
        return $this->fetch('catform');
    }

    public function delCat()
    {
        $id = input('param.id/a');
        $model = new CatModel();
        if (!$model->del($id)) {
            return $this->error('此类别下有检查项，不能删除');
        }
        return $this->success('删除成功');
    }

}