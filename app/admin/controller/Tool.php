<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/18
 * Time: 14:29
 */

namespace app\admin\controller;


use app\admin\model\AdminDepartment;
use think\Controller;

class Tool extends Admin
{
    protected function _initialize()
    {
        parent::_initialize();
    }

    public function getTreeUser(){
        if ($this->request->isPost()){
            if (1 != session('admin_user.role_id')){
                $cid = session('admin_user.cid');
            }else{
                $cid = session('admin_user.cid');
            }
            $result = AdminDepartment::getDepUser($cid);
            return $result;
        }
        return $this->fetch();
    }

    public function getTreeUser1(){
        return $this->fetch();
    }

    public function getTreeDep(){
        if (1 != session('admin_user.role_id')){
            $cid = session('admin_user.cid');
        }else{
            $cid = 3;
        }
        $result = AdminDepartment::index($cid);
        return $result;
    }
}