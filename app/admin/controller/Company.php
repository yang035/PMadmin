<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/11
 * Time: 10:39
 */

namespace app\admin\controller;


use app\admin\model\AdminCompany;
use app\admin\model\AdminDepartment;
use app\admin\model\AdminMenu as MenuModel;
use app\admin\model\Category;
use think\Db;

class Company extends Admin
{
    public function index($q = '')
    {
        $map = [];
        $cid = session('admin_user.cid');
        if (!in_array($cid,[1,6])){
            $map['id'] = session('admin_user.cid');
        }
        $limit = input('param.limit/d', 20);

        $sys_type = input('param.sys_type/d');
        if ($sys_type) {
            $map['sys_type'] = $sys_type;
        }
        $name = input('param.name');
        if ($name) {
            $map['name'] = ['like', "%{$name}%"];
        }
        $cellphone = input('param.cellphone');
        if ($cellphone) {
            $map['register_mobile'] = trim($cellphone);
        }

        $data_list = AdminCompany::where($map)->paginate($limit, false, ['query' => input('get.')]);
        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
        $this->assign('systype_option', AdminCompany::getSysType1());
        return $this->fetch();
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            // 验证
            $result = $this->validate($data, 'AdminCompany');
            if($result !== true) {
                return $this->error($result);
            }
            if ($data['piao_name'] && $data['identity_number']){
                $content = <<<EOF
开票名称：{$data['piao_name']}<br>
开票税号：{$data['identity_number']}<br>
地址电话：{$data['piao_address']}<br>
开户银行：{$data['bank']}<br>
银行账号：{$data['card_num']}<br>
EOF;
                $data['code_path'] = scerweima1($content);
            }
            $result = AdminCompany::create($data);
            $result_arr =$result->toArray();
            $cid = $result_arr['id'];
            if (!$result) {
                return $this->error('添加失败！');
            }else{
                $dep_data = [
                    'code'=>$cid.'d',
                    'cid'=>$cid,
                    'user_id' => session('admin_user.uid'),
                    'name'=>$result_arr['name'],
                    'remark'=>$result_arr['name']
                ];
                if (!AdminDepartment::create($dep_data)){
                    return $this->error('添加失败！');
                }

                $categoty_data = [
                    'code'=>$cid.'g',
                    'cid'=>$cid,
                    'user_id' => session('admin_user.uid'),
                    'name'=>$result_arr['name'],
                    'remark'=>$result_arr['name']
                ];
                if (!Category::create($categoty_data)){
                    return $this->error('添加失败！');
                }

            }
            return $this->success('添加成功。',url('index'));
        }

        return $this->fetch('form');
    }

    public function edit($id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ($data['cellphone'] == 0) {
                unset($data['cellphone']);
            }

            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'AdminCompany');
            if($result !== true) {
                return $this->error($result);
            }
            if ($data['piao_name'] && $data['identity_number']){
                $content = <<<EOF
开票名称：{$data['piao_name']}
开票税号：{$data['identity_number']}
地址电话：{$data['piao_address']}
开户银行：{$data['bank']}
银行账号：{$data['card_num']}
EOF;
                $data['code_path'] = scerweima1($content);
            }
            $flag = false;
            Db::startTrans();
            try{
                $w = [
                    'pid' => 0,
                    'cid' => $data['id'],
                ];
                AdminDepartment::where($w)->update(['name'=>$data['name']]);
                \app\admin\model\ScoreRule::where($w)->update(['name'=>$data['name']]);
                $flag = AdminCompany::update($data);
                //事务提交
                Db::commit();
            } catch (\Exception $e) {
//                  事务回滚
                Db::rollback();
            }
            if ($flag){
                return $this->success('修改成功',url('index'));
            }else{
                return $this->error('修改失败');
            }
        }

        $row = AdminCompany::where('id', $id)->find()->toArray();
        $this->assign('data_info', $row);
        return $this->fetch('form');
    }

    public function comAuth($id = 0)
    {
        $params = $this->request->param();
        if ($id <= 1) {
            return $this->error('禁止编辑');
        }

        if ($this->request->isPost()) {
            $data = $this->request->post();

            // 当前登陆用户不可更改自己的分组角色
            if (ADMIN_ROLE > 4) {
                return $this->error('禁止修改当前角色(原因：您不是超级管理员或公司管理员)');
            }
            $data['user_id'] = session('admin_user.uid');
            if (isset($data['auth'])){
                $data['auth'] = json_encode($data['auth']);
            }else{
                $data['auth'] = null;
            }
            if (!AdminCompany::update($data)) {
                return $this->error('修改失败');
            }
            $dep_w = [
                'pid' => 0,
                'cid' => $id,
            ];
            $dep_auth['auth']= $data['auth'];
            if (!AdminDepartment::where($dep_w)->update($dep_auth)) {
                return $this->error('修改失败');
            }

            // 更新权限缓存
            cache('role_auth_'.ADMIN_ROLE, $data['auth']);

            return $this->success('修改成功');
        }
        $tab_data = [];
        $tab_data['menu'] = [
            ['title' => '设置权限'],
        ];
        $row = AdminCompany::where('id', $params['id'])->field('id,auth')->find()->toArray();
        $row['auth'] = json_decode($row['auth']);
        $this->assign('data_info', $row);
        $this->assign('menu_list', MenuModel::getAllChild());
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 2);
        return $this->fetch();
    }

    public function read($id = 0)
    {
        $row = AdminCompany::where('id', $id)->find()->toArray();
        $this->assign('data_list', $row);
        return $this->fetch();
    }
}