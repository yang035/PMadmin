<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;

use app\admin\model\AdminCompany;
use app\admin\model\AdminUser;
use app\admin\model\UserLogin;

class DataReport extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();
    }

    public function index($q = '')
    {
        return $this->fetch();
    }

    public function getUserCount(){
        $company_count = AdminCompany::count('id');
        $person_count = AdminUser::count('id');
        $names = ['公司数','人数',];
        $rule = [
            0=>[
                'name'=>$names[0],
                'value'=>$company_count,
            ],
            1=>[
                'name'=>$names[1],
                'value'=>$person_count,
            ],
        ];
        return [
            'leg'=>$names,
            'ser'=>$rule
        ];
    }

    public function getCompanyCount(){
        $fields = "COUNT(id) c,FROM_UNIXTIME(create_time,'%Y%m') m";
        $data = AdminCompany::field($fields)->group('m')->select();
        $tmp = [];
        if ($data){
            foreach ($data as $v) {
                $tmp[$v['m']] = $v['c'];
            }
        }
        return [
            'leg'=>array_keys($tmp),
            'ser'=>array_values($tmp),
        ];
    }

    public function getPersonCount(){
        $fields = "COUNT(id) c,FROM_UNIXTIME(create_time,'%Y%m') m";
        $data = AdminUser::field($fields)->group('m')->select();
        $tmp = [];
        if ($data){
            foreach ($data as $v) {
                $tmp[$v['m']] = $v['c'];
            }
        }
        return [
            'leg'=>array_keys($tmp),
            'ser'=>array_values($tmp),
        ];
    }

    public function getLoginCount(){
        $fields = "COUNT(user_id) c,DATE_FORMAT(login_time,'%Y%m') m";
        $data = UserLogin::field($fields)->group('m')->select();
        $tmp = [];
        if ($data){
            foreach ($data as $v) {
                $tmp[$v['m']] = $v['c'];
            }
        }
        return [
            'leg'=>array_keys($tmp),
            'ser'=>array_values($tmp),
        ];
    }

}