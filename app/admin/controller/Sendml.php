<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;
use app\admin\model\Sendml as SendmlModel;


class Sendml extends Admin{
    public function _initialize()
    {
        return parent::_initialize(); // TODO: Change the autogenerated stub
    }

    public function index()
    {
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 20);

            $cat_id = input('param.cat_id/d');
            if ($cat_id) {
                $where['cat_id'] = $cat_id;
            }
            $name = input('param.name');
            if ($name) {
                $where['name'] = ['like', "%{$name}%"];
            }
            $s_status = input('param.s_status/d');
            if ($s_status) {
                $where['s_status'] = $s_status;
            }
            $where['cid'] = session('admin_user.cid');
            $order = 'status desc,id desc';
            $data['data'] = SendmlModel::with('cat')->where($where)->page($page)->order($order)->limit($limit)->select();
            $data['count'] = SendmlModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }
        return $this->fetch();
    }

    public function add($user,$subject_id)
    {
        if (empty($user) || empty($subject_id)){
            return $this->error('项目或员工不存在');
        }
        $cid = session('admin_user.cid');
        $w = [
            'cid' => $cid,
            'subject_id' => $subject_id,
            'user' => $user,
        ];
        $row = SendmlModel::where($w)->order('id desc')->limit(1)->find();
        $last_total_fafang = $row ? $row['total_fafang'] : 0;
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $data['last_total_fafang'] = $last_total_fafang;
            $data['total_fafang'] = $last_total_fafang + $data['benci_fafang'];
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'Sendml');
            if ($result !== true) {
                return $this->error($result);
            }
            if (!SendmlModel::create($data)) {
                return $this->error('操作失败');
            }
            return $this->success('操作成功');
        }
        $this->assign('data_info', $row);
        return $this->fetch('form');
    }

    public function edit($user,$subject_id)
    {
        if (empty($user) || empty($subject_id)){
            return $this->error('项目或员工不存在');
        }
        $cid = session('admin_user.cid');
        $w = [
            'cid' => $cid,
            'subject_id' => $subject_id,
            'user' => $user,
        ];
        $row = SendmlModel::where($w)->order('id desc')->limit(1)->find();
        if (!$row){
            return $this->error("没有可编辑的，请操作[发放]按钮");
        }
        if (1 == $row['status']){
            return $this->error("本次发放{$row['benci_fafang']}上级已确认,不能修改");
        }
        $last_total_fafang = $row ? $row['last_total_fafang'] : 0;
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $data['last_total_fafang'] = $last_total_fafang;
            $data['total_fafang'] = $last_total_fafang + $data['benci_fafang'];
            // 验证
            $result = $this->validate($data, 'Sendml');
            if ($result !== true) {
                return $this->error($result);
            }
            if (!SendmlModel::update($data)) {
                return $this->error('操作失败');
            }
            return $this->success('操作成功');
        }
        $this->assign('data_info', $row);
        return $this->fetch('form');
    }

    public function setStatus($user,$subject_id)
    {
        if (empty($user) || empty($subject_id)){
            return $this->error('项目或员工不存在');
        }
        $cid = session('admin_user.cid');
        if ($this->request->isAjax()) {
            $w = [
                'cid' => $cid,
                'subject_id' => $subject_id,
                'user' => $user,
            ];
            $row = SendmlModel::where($w)->order('id desc')->limit(1)->find();
            if ($row){
                $d = [
                    'id' => $row['id'],
                    'status' => 1,
                ];
                if (!SendmlModel::update($d)) {
                    return $this->error('确认失败');
                }
                return $this->success('确认成功');
            }
        }
    }
}