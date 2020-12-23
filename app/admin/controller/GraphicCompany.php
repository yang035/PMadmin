<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/11
 * Time: 10:39
 */

namespace app\admin\controller;


use app\admin\model\GraphicCompany as GraphicCompanyModel;

class GraphicCompany extends Admin
{
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
            $where['cid'] = session('admin_user.cid');
            $data['data'] = GraphicCompanyModel::where($where)->page($page)->limit($limit)->select();
            $data['count'] = GraphicCompanyModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }
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
            $result = $this->validate($data, 'GraphicCompany');
            if($result !== true) {
                return $this->error($result);
            }
            if (!GraphicCompanyModel::create($data)) {
                return $this->error('添加失败');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        return $this->fetch('form');
    }

    public function edit($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'GraphicCompany');
            if($result !== true) {
                return $this->error($result);
            }
            if (!GraphicCompanyModel::update($data)) {
                return $this->error('修改失败');
            }
            return $this->success('修改成功',url('index'));
        }

        $row = GraphicCompanyModel::where('id', $id)->find()->toArray();
        $this->assign('data_info', $row);
        return $this->fetch('form');
    }
}