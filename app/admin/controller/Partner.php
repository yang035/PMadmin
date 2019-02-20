<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/11
 * Time: 10:39
 */

namespace app\admin\controller;


use app\admin\model\Partner as PartnerModel;

class Partner extends Admin
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
        $data_list = PartnerModel::where($map)->paginate(20, false, ['query' => input('get.')]);
        if ($data_list) {
            foreach ($data_list as $k => $v) {
                $data_list[$k]['year_money'] = $v['min_target'] * $v['year_per'] / 100;
                $data_list[$k]['month_money'] = round($data_list[$k]['year_money'] * $v['month_per'] / 100 / 12, 2);
                $data_list[$k]['annual_bonus'] = ($data_list[$k]['year_money'] * (100 - $v['month_per'])) / 100;
            }
        }
        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('partnership_grade', $grade_type);
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
            $result = $this->validate($data, 'Partner');
            if ($result !== true) {
                return $this->error($result);
            }

            unset($data['id']);
            $result = PartnerModel::create($data);
            $result_arr = $result->toArray();
            $cid = $result_arr['id'];
            if (!$result) {
                return $this->error('添加失败！');
            }
            return $this->success('添加成功。', url('index'));
        }
        $this->assign('partnership_grade', PartnerModel::getPartnershipGrade());
        return $this->fetch('form');
    }

    public function edit($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'Partner');
            if ($result !== true) {
                return $this->error($result);
            }

            if (!PartnerModel::update($data)) {
                return $this->error('修改失败！');
            }
            return $this->success('修改成功。', url('index'));
        }

        $row = PartnerModel::where('id', $id)->find()->toArray();
        $this->assign('data_info', $row);
        $this->assign('partnership_grade', PartnerModel::getPartnershipGrade());
        return $this->fetch('form');
    }
}