<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020-8-18
 * Time: 17:27
 */

namespace app\admin\controller;
use app\admin\model\HezuoCompany as HezuoCompanyModel;
use app\admin\model\HezuoPerson as HezuoPersonModel;
use app\admin\model\AdminCompany;
use app\admin\model\AdminUser;
use think\Db;

class HezuoCompany extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '合作公司',
                'url' => 'admin/HezuoCompany/index',
            ],
        ];
        $this->tab_data = $tab_data;
        $this->assign('company_select', AdminCompany::getOption1());
    }

    public function index($q = '')
    {
        $params = $this->request->param();
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 30);

            $where['cid'] = session('admin_user.cid');
            $data['data'] = HezuoCompanyModel::where($where)->page($page)->limit($limit)->select();
            $company = AdminCompany::getOption2();
            foreach ($data['data'] as $k => $v) {
                $data['data'][$k]['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];
                $data['data'][$k]['company_name'] = $company[$v['company_id']];
            }
            $data['count'] = HezuoCompanyModel::where($where)->count('id');
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

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (!$data['company_id']) {
                return $this->error('请选择公司');
            }

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'HezuoCompany');
            if($result !== true) {
                return $this->error($result);
            }

            $u_data = AdminUser::where(['company_id'=>$data['company_id']])->limit(1)->column('id');
            $person_id = $u_data ? $u_data[0] : 0;
            // 启动事务
            Db::startTrans();
            try {
                HezuoCompanyModel::create($data);
                $data['person_id'] = $person_id;
                $flag = HezuoPersonModel::create($data);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($flag) {
                return $this->success("操作成功{$this->score_value}", 'index');
            } else {
                return $this->error('添加失败！');
            }
        }
        return $this->fetch('itemform');
    }

    public function edit($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (!$data['company_id']) {
                return $this->error('请选择公司');
            }
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'HezuoCompany');
            if($result !== true) {
                return $this->error($result);
            }
            if (!HezuoCompanyModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功');
        }

        $row = HezuoCompanyModel::where('id', $id)->find();
        $this->assign('data_info', $row);
        return $this->fetch('itemform');
    }

}