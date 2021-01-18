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
use app\admin\model\Score as ScoreModel;
use app\admin\model\AdminCompany;
use app\admin\model\AdminUser;

class HezuoPerson extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '合作公司',
                'url' => 'admin/HezuoPerson/index',
            ],
        ];
        $this->tab_data = $tab_data;
        $this->assign('company_select', HezuoCompanyModel::getOption());
    }

    public function index($q = '')
    {
        $params = $this->request->param();
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 30);

            if (isset($params['company_id'])){
                $where['company_id'] = $params['company_id'];
            }

            $where['cid'] = session('admin_user.cid');
            $data['data'] = HezuoPersonModel::where($where)->page($page)->limit($limit)->select();
            $company = AdminCompany::getOption2();
            foreach ($data['data'] as $k => $v) {
                $data['data'][$k]['person_name'] = $v['person_id'] ? AdminUser::getUserById($v['person_id'])['username'] : '无';
                $data['data'][$k]['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];
                $data['data'][$k]['company_name'] = $company[$v['company_id']];
            }
            $data['count'] = HezuoPersonModel::where($where)->count('id');
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
            if (!$data['person_id']) {
                return $this->error('请选择人员');
            }

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'HezuoPerson');
            if($result !== true) {
                return $this->error($result);
            }
            $row = HezuoPersonModel::getRow($data['company_id'],$data['person_id']);
            if (!$row){
                if (!HezuoPersonModel::create($data)) {
                    return $this->error('添加失败');
                }
                return $this->success("操作成功{$this->score_value}");
            }else{
                return $this->error('此公司这个用户已存在');
            }

        }
        return $this->fetch('itemform');
    }

    public function personSelect($company_id)
    {
        return AdminUser::selectUser1($company_id);
    }

    public function edit($id = 0)
    {
        $row = HezuoPersonModel::where('id', $id)->find();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['company_id'] = $row['company_id'];

            if (!$data['company_id']) {
                return $this->error('请选择公司');
            }
            if (!$data['person_id']) {
                return $this->error('请选择人员');
            }

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'HezuoPerson');
            if($result !== true) {
                return $this->error($result);
            }
            if (!HezuoPersonModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功');
        }


        $this->assign('data_info', $row);
        $this->assign('person_select', AdminUser::selectUser1($row['company_id']));
        return $this->fetch('editform');
    }

}