<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 12:15
 */

namespace app\admin\controller;

use app\admin\model\AdminUser;
use app\admin\model\Task as TaskModel;

class Task extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '任务审批',
                'url' => 'admin/task/index',
            ],
        ];
        $this->tab_data = $tab_data;
    }
    public function index($q = '')
    {
        $map = [];
        if ($q) {
            if (preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $q)) {// 邮箱
                $map['email'] = $q;
            } elseif (preg_match("/^1\d{10}$/", $q)) {// 手机号
                $map['cellphone'] = $q;
            } else {// 用户名、昵称
                $map['name'] = ['like', '%'.$q.'%'];
            }
        }

        $data_list = TaskModel::where($map)->paginate(10, false, ['query' => input('get.')]);
        foreach ($data_list as $k=>$v){
            $user_arr = explode(',',rtrim($v['send_user'], ','));
            $user =[];
            foreach ($user_arr as $val){
                $user[] = AdminUser::getUserById($val)['realname'];
            }
            if ($user){
                $data_list[$k]['send_user'] = implode(',',$user);
            }
        }
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
            // 验证
            $result = $this->validate($data, 'Plan');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
            $data['user_id'] = session('admin_user.uid');
            if (!TaskModel::create($data)) {
                return $this->error('添加失败！');
            }
            return $this->success('添加成功。','index');
        }
        $this->assign('plan_option', \app\admin\model\Plan::getOption());
        return $this->fetch('form');
    }

}