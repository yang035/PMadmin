<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;
use app\admin\model\Taocan as TaocanModel;


class Taocan extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '套餐编辑',
                'url' => 'admin/Taocan/index',
            ],
        ];
        $this->tab_data = $tab_data;
    }

    public function index()
    {
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 15);
            $keyword = input('param.keyword');
            if ($keyword) {
                $where['name'] = ['like', "%{$keyword}%"];
            }
            $ser_level = config('other.ser_level');
            $where['cid'] = session('admin_user.cid');
            $data['data'] = TaocanModel::where($where)->page($page)->limit($limit)->select();
            if ($data['data']){
                foreach ($data['data'] as $k=>$v){
                    $data['data'][$k]['level_name'] = $ser_level[$v['ser_level']];
                }
            }
            $data['count'] = TaocanModel::where($where)->count('id');
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

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'MealCat');
            if($result !== true) {
                return $this->error($result);
            }
            if (!TaocanModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $this->assign('ser_level',TaocanModel::getLevel());
        return $this->fetch('form');
    }

    public function edit($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'MealCat');
            if($result !== true) {
                return $this->error($result);
            }
            if (!TaocanModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功',url('cat'));
        }

        $row = TaocanModel::where('id', $id)->find()->toArray();
        $this->assign('ser_level',TaocanModel::getLevel($row['ser_level']));
        $this->assign('data_info', $row);
        return $this->fetch('form');
    }
    public function del()
    {
        $id = input('param.id/a');
        $model = new TaocanModel();
        if (!$model->del($id)) {
            return $this->error('此类别下有检查项，不能删除');
        }
        return $this->success('删除成功');
    }
}