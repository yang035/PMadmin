<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 14:59
 */

namespace app\admin\controller;

use app\admin\model\AdminCompany;
use app\admin\model\AdminLog;
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
        $cid = session('admin_user.cid');
        $role_id = session('admin_user.role_id');
        if (!($cid == 6 && $role_id < 5)){
            return $this->error('禁止访问');
        }
        return $this->fetch();
    }

    /**
     * @return array
     * @throws \think\Exception
     * 公司用户和个人用户比例
     */
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

    /**
     * @return array
     * @throws \think\Exception
     * 注册公司数
     */
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

    /**
     * @return array
     * @throws \think\Exception
     * 注册个人数
     */
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

    /**
     * @return array
     * @throws \think\Exception
     * 登录次数
     */
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

    /**
     * @return array
     * @throws \think\Exception
     * 菜单访问量
     */
    public function getMenuCount(){
        $fields = "COUNT(id) c,title,url,FROM_UNIXTIME(ctime,'%Y%m') m";
        $data = AdminLog::field($fields)->group('title')->order('c desc')->limit(10)->select();
        $tmp = [];
        if ($data){
            foreach ($data as $v) {
                $tmp[$v['title']] = $v['c'];
            }
        }

        return [
            'leg'=>array_keys($tmp),
            'ser'=>array_values($tmp),
        ];
    }

    /**
     * @return array
     * @throws \think\Exception
     * PV
     */
    public function getPvCount(){
        $fields = "COUNT(id) c,FROM_UNIXTIME(ctime,'%Y%m%d') m";
        $data = AdminLog::field($fields)->group('m')->select();
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

    /**
     * @return array
     * @throws \think\Exception
     * UV
     */
    public function getUvCount(){
        $fields = "COUNT(DISTINCT ip) c,FROM_UNIXTIME(ctime,'%Y%m%d') m";
        $data = AdminLog::field($fields)->group('m')->select();
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

    /**
     * @return array
     * @throws \think\Exception
     * VV
     */
    public function getVvCount(){
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

    /**
     * @return array
     * @throws \think\Exception
     * IP
     */
    public function getIpCount(){
        $fields = "COUNT(DISTINCT ip) c,FROM_UNIXTIME(ctime,'%Y%m%d') m";
        $data = AdminLog::field($fields)->group('m')->select();
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

    /**
     * @return array
     * @throws \think\Exception
     * 商品订单量
     */
    public function getShopOrderCount(){
        $fields = "COUNT(id) c,FROM_UNIXTIME(create_time,'%Y%m%d') m";
        $data = \app\admin\model\ShopOrder::field($fields)->group('m')->select();
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

    /**
     * @return array
     * @throws \think\Exception
     * 套餐订单量
     */
    public function getMealOrderCount(){
        $fields = "COUNT(id) c,FROM_UNIXTIME(create_time,'%Y%m%d') m";
        $data = \app\admin\model\MealOrder::field($fields)->group('m')->select();
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