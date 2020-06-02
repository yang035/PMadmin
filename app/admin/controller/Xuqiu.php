<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;
use app\admin\model\Xuqiu as XuqiuModel;


class Xuqiu extends Admin
{
    public function index($q = '')
    {
        $map = [];
        if (2 != session('admin_user.cid')) {
            $map['cid'] = session('admin_user.cid');
        }
        if (session('admin_user.role_id') > 3) {
            $map['user_id'] = session('admin_user.uid');
        }
        if ($q) {
            $map['name'] = ['like', '%' . $q . '%'];
        }
        $data_list = XuqiuModel::where($map)->order('id desc')->paginate(20, false, ['query' => input('get.')]);
        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
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
            $result = $this->validate($data, 'Xuqiu');
            if ($result !== true) {
                return $this->error($result);
            }
            if (!XuqiuModel::create($data)) {
                return $this->error('添加失败！');
            }
            return $this->success("操作成功{$this->score_value}", url('index'));
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
            $result = $this->validate($data, 'Xuqiu');
            if ($result !== true) {
                return $this->error($result);
            }
            if (!XuqiuModel::update($data)) {
                return $this->error('修改失败！');
            }
            return $this->success('修改成功。', url('index'));
        }

        $row = XuqiuModel::where('id', $id)->find()->toArray();
        $row['remark'] = htmlspecialchars_decode($row['remark']);
        $this->assign('data_info', $row);
        return $this->fetch('form');
    }

    public function read($id = 0)
    {
        $row = XuqiuModel::where('id', $id)->find()->toArray();
        $row['remark'] = htmlspecialchars_decode($row['remark']);
        $this->assign('data_list', $row);
        return $this->fetch();
    }
}