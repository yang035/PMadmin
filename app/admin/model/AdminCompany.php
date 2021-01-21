<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/11
 * Time: 10:45
 */

namespace app\admin\model;


use think\Model;

class AdminCompany extends Model
{
    protected $autoWriteTimestamp = true;

    public static function getCompanyById($id){
        $result = [];
        $where = [
            'id'=>$id
        ];
        $result = self::where($where)->find()->toArray();
        return $result;
    }
    public static function getOption($id = 0)
    {
        $rows = self::column('id,name');
        $str = '';
        foreach ($rows as $k => $v) {
            if ($k == 1) {// 过滤超级管理员角色
                continue;
            }
            if ($id == $k) {
                $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
            } else {
                $str .= '<option value="'.$k.'">'.$v.'</option>';
            }
        }
        return $str;
    }

    public static function getOption1($id = 0)
    {
        $rows = self::column('id,name');
        $str = '<option value="">选择</option>';
        foreach ($rows as $k => $v) {
            if ($k == 1) {// 过滤超级管理员角色
                continue;
            }
            if ($id == $k) {
                $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
            } else {
                $str .= '<option value="'.$k.'">'.$v.'</option>';
            }
        }
        return $str;
    }

    public static function getOption2($id = 0)
    {
        $rows = self::column('name','id');
        return $rows;
    }

    public static function getOption3($id = 0)
    {
        $rows = self::column('name,sys_type','id');
        return $rows;
    }

    public static function getSysType($type = 0)
    {
        $leaveType = config('tb_system.sys_type');
        $str = '';
        foreach ($leaveType as $k => $v) {
            if ($type == $k) {
                $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
            } else {
                $str .= '<option value="'.$k.'">'.$v.'</option>';
            }
        }
        return $str;
    }

    public static function getSysType1($type = 0)
    {
        $leaveType = config('tb_system.sys_type');
        $str = '<option value="">全部</option>';
        foreach ($leaveType as $k => $v) {
            if ($type == $k) {
                $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
            } else {
                $str .= '<option value="'.$k.'">'.$v.'</option>';
            }
        }
        return $str;
    }

    public static function getGysType($type = 0)
    {
        $leaveType = config('tb_system.gys_type');
        $str = '';
        foreach ($leaveType as $k => $v) {
            if ($type == $k) {
                $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
            } else {
                $str .= '<option value="'.$k.'">'.$v.'</option>';
            }
        }
        return $str;
    }

}