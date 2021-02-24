<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 15:03
 */

namespace app\admin\model;


use think\Model;
use app\admin\model\CarItem as ItemModel;

class ApprovalCarrecord extends Model
{
    public static function getOption1($type = 0)
    {
        $leaveType = config('other.car_type');
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

    public static function getOption($type = 0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
        ];
        $data = ItemModel::where($map)->select();
        $str = '';
        if ($data){
            foreach ($data as $k => $v) {
                if ($type == $k) {
                    $str .= '<option value="'.$v['id'].'" selected>'.$v['name'].'['.$v['idcard'].']'.'</option>';
                } else {
                    $str .= '<option value="'.$v['id'].'">'.$v['name'].'['.$v['idcard'].']'.'</option>';
                }
            }
        }
        return $str;
    }

    public static function getCarItem($type = 0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
        ];
        $data = ItemModel::where($map)->column('idcard','id');
        return $data;
    }

}