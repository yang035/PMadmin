<?php
namespace app\admin\controller;

use app\admin\model\Goods as GoodsModel;
use think\Validate;

class Goods extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '商品明细',
                'url' => 'admin/Goods/index',
            ],
        ];
        $this->tab_data = $tab_data;
    }

    public function index($q = '')
    {
        $params = $this->request->param();
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 15);

            if (1 != session('admin_user.role_id')){
                $where['cid'] = session('admin_user.cid');
            }
            if (isset($params['cat_id']) && !empty($params['cat_id'])){
                $where['cat_id'] = $params['cat_id'];
            }
            if (isset($params['name']) && !empty($params['name'])){
                $where['title'] = ['like',"%{$params['name']}%"];
            }

            $data['data'] = GoodsModel::with('category')->where($where)->page($page)->limit($limit)->select();
            $data['count'] = GoodsModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }

        // 分页
        $tab_data = $this->tab_data;
        $tab_data['current'] = url('');

        $this->assign('unit_option', config('other.unit'));
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        return $this->fetch();
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'Goods');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['id'], $data['cat_name']);
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
//            print_r($data);exit();
            if (!GoodsModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success('添加成功',url('index'));
        }

        $this->assign('menu_list', '');
        $this->assign('unit_option', GoodsModel::getOption());

        return $this->fetch();
    }

    public function edit($id = 0)
    {
        $params = $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'Goods');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['cat_name']);
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
//            print_r($data);exit();
            if (!GoodsModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功',url('index'));
        }
        $where = [
            'id'=>$params['id'],
        ];
        $data_info = GoodsModel::with('category')->where($where)->find();
        $this->assign('unit_option', GoodsModel::getOption());
        $this->assign('data_info', $data_info);
        return $this->fetch('add');
    }


    public function del()
    {
        $id = input('param.id/a');
        $model = new GoodsModel();
        if (!$model->del($id)) {
            return $this->error($model->getError());
        }
        return $this->success('操作成功');
    }

    public function read($id = 0)
    {
        $params = $this->request->param();
        $where = [
            'id'=>$params['id'],
        ];
        $data_info = GoodsModel::with('category')->where($where)->find();
        $data_info['content'] = htmlspecialchars_decode($data_info['content']);
        $this->assign('unit_option', GoodsModel::getOption());
        $this->assign('data_info', $data_info);
        return $this->fetch();
    }
}
