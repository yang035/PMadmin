<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/18
 * Time: 14:29
 */

namespace app\admin\controller;


use app\admin\model\AdminDepartment;
use app\admin\model\Category;
use think\Controller;
use think\Db;

class Tool extends Admin
{
    protected function _initialize()
    {
        parent::_initialize();
    }

    public function getTreeUser(){
        $params = $this->request->param();
        if ($this->request->isPost()){
            $data = $this->request->post();
            if (1 != session('admin_user.role_id')){
                $cid = session('admin_user.cid');
            }else{
                $cid = session('admin_user.cid');
            }
            if ($data['path']){
                $result = AdminDepartment::getDepUser($cid,1);
            }else{
                $result = AdminDepartment::getDepUser($cid);
            }
            return $result;
        }
        if (!isset($params['f'])){
            return $this->fetch();
        }else{
            return $this->fetch('get_tree_user2');
        }

    }

    public function getThirdUser(){
        $params = $this->request->param();
        if ($this->request->isPost()){
            $data = $this->request->post();
            $map = [
                'p.cid' => session('admin_user.cid'),
                'p.status' => 1,
                'u.id'=>['<>','']
            ];
            $fields = "u.id,u.username,c.name";
            $user = Db('hezuo_person p')
                ->join('admin_user u','p.person_id = u.id','left')
                ->join('admin_company c','p.company_id = c.id','left')
                ->field($fields)
                ->where($map)
                ->select();
            $result = [];
            if ($user){
                foreach ($user as $k => $v) {
                    if ($v['id']){
                        $result[$k] = $v;
                        $result[$k]['uid'] = '10000' . $v['id'];
                    }
                }
            }
            return json($result);
        }
        return $this->fetch();
    }

    public function getTreeDepartment(){
        if ($this->request->isPost()){
            $data = $this->request->post();
            if (1 != session('admin_user.role_id')){
                $cid = session('admin_user.cid');
            }else{
                $cid = session('admin_user.cid');
            }
            if ($data['path']){
                $result = AdminDepartment::getDepUser($cid,1);
            }else{
                $result = AdminDepartment::getDepUser($cid);
            }
            return $result;
        }
        return $this->fetch();
    }

    public function getTreeDep(){
        if ($this->request->isPost()){
            $data = $this->request->post();
            if (1 != session('admin_user.role_id')){
                $cid = session('admin_user.cid');
            }else{
                $cid = session('admin_user.cid');
            }
            if ($data['path']){
                $result = AdminDepartment::getDepUser($cid,1);
            }else{
                $result = AdminDepartment::getDepUser($cid);
            }
            return $result;
        }
        return $this->fetch();
    }

    public function getTreeGood(){
        if ($this->request->isPost()){
            if (1 != session('admin_user.role_id')){
                $cid = session('admin_user.cid');
            }else{
                $cid = session('admin_user.cid');
            }
            $result = Category::getDepGood($cid);
            return $result;
        }
        return $this->fetch();
    }

    public function getTreeUser1(){
        return $this->fetch();
    }

    public function getTreeDep1(){
        if (1 != session('admin_user.role_id')){
            $cid = session('admin_user.cid');
        }else{
            $cid = session('admin_user.cid');
        }
        $result = AdminDepartment::index($cid);
        return $result;
    }

    public function getTreeCat(){
        if (1 != session('admin_user.role_id')){
            $cid = session('admin_user.cid');
        }else{
            $cid = session('admin_user.cid');
        }
        $result = Category::index($cid);
        return $result;
    }
}