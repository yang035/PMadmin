<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/11
 * Time: 10:39
 */

namespace app\admin\controller;


use app\admin\model\Partnership as PartnershipModel;

class Partnership extends Admin
{
    public function index($q = '')
    {
        $map = [];
        if (1 != session('admin_user.role_id')) {
            $map['cid'] = session('admin_user.cid');
        }
        if ($q) {
            if (preg_match("/^1\d{10}$/", $q)) {// 手机号
                $map['cellphone'] = $q;
            } else {// 用户名、昵称
                $map['name'] = ['like', '%' . $q . '%'];
            }
        }
        $grade_type = config('other.partnership_grade');
        $data_list = PartnershipModel::where($map)->paginate(20, false, ['query' => input('get.')]);
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
            // 验证
            $result = $this->validate($data, 'Partnership');
            if ($result !== true) {
                return $this->error($result);
            }

            unset($data['id']);
            $result = PartnershipModel::create($data);
            $result_arr = $result->toArray();
            $cid = $result_arr['id'];
            if (!$result) {
                return $this->error('添加失败！');
            }
            return $this->success('添加成功。', url('index'));
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
            $result = $this->validate($data, 'Partnership');
            if ($result !== true) {
                return $this->error($result);
            }

            if (!PartnershipModel::update($data)) {
                return $this->error('修改失败！');
            }
            return $this->success('修改成功。', url('index'));
        }

        $row = PartnershipModel::where('id', $id)->find()->toArray();
        $this->assign('data_info', $row);
        return $this->fetch('form');
    }
}