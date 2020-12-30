<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;
use app\admin\model\FondPool as FondPoolModel;
use app\admin\model\SubjectItem;
use app\admin\model\AdminUser;

class FondPool extends Admin{
    public function _initialize()
    {
        return parent::_initialize(); // TODO: Change the autogenerated stub
    }

    public function index($q = '')
    {
        $subject_name = '';
        $real_name = '';
        $params = $this->request->param();
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 20);

            if (!empty($params['user_id'])) {
                $where['user'] = $params['user_id'];
                $real_name = $params['real_name'];
            }
            $role_id = session('admin_user.role_id');
            if ($role_id > 4) {
                $where['user'] = session('admin_user.uid');
            }
            $where['cid'] = session('admin_user.cid');
//            $where['is_fafang'] = 1;
            $order = 'status desc,id desc';
            $field = 'user,sum(add_fond) as add_fond,sum(sub_fond) as sub_fond';
            $group = 'user';
//            print_r($where);exit();
            $data['data'] = FondPoolModel::field($field)->where($where)->group($group)->page($page)->order($order)->limit($limit)->select();
            $map = [
                'company_id'=>$where['cid'],
            ];
            $user = AdminUser::where($map)->column('realname','id');
            if ($data['data']){
                foreach ($data['data'] as $k=>$v){
                    $data['data'][$k]['user_name'] = $user[$v['user']];
                    $v['add_fond'] = round($v['add_fond'],2);
                    $v['sub_fond'] = round($v['sub_fond'],2);
                    $data['data'][$k]['no_tixian'] = round($v['add_fond'] - $v['sub_fond'],2);
                }
            }
            $data['count'] = FondPoolModel::where($where)->group($group)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }
        $this->assign('user_select', AdminUser::inputSearchUser());
        $this->assign('real_name', $real_name);
        return $this->fetch();
    }

    public function detail($q = '')
    {
        $subject_name = '';
        $real_name = '';
        $params = $this->request->param();
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 20);

            if ($params['user_id']) {
                $where['user'] = $params['user_id'];
                $real_name = $params['real_name'];
            }
            $role_id = session('admin_user.role_id');
            if ($role_id > 4) {
                $where['user'] = session('admin_user.uid');
            }
            $where['cid'] = session('admin_user.cid');
            $order = 'status desc,id desc';
//            print_r($where);
            $data['data'] = FondPoolModel::where($where)->page($page)->order($order)->limit($limit)->select();
            $map = [
                'company_id'=>$where['cid'],
            ];
            $user = AdminUser::where($map)->column('realname','id');
            if ($data['data']){
                foreach ($data['data'] as $k=>$v){
                    $data['data'][$k]['user_name'] = $user[$v['user']];
                    $data['data'][$k]['user_id'] = (1!=$v['user_id']) ? $user[$v['user_id']] : '系统';
                    $data['data'][$k]['remark'] = $v['subject_id'] ? SubjectItem::getItem()[$v['subject_id']] : $v['remark'];
                }
            }
            $data['count'] = FondPoolModel::where($where)->count('id');
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }
        $this->assign('user_select', AdminUser::inputSearchUser1());
        $this->assign('real_name', $real_name);
        return $this->fetch();
    }
}