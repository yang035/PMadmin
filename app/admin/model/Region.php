<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/22
 * Time: 14:36
 */

namespace app\admin\model;


use think\Model;

class Region extends Model
{
    public static function getOption($type = 0)
    {
        $data = self::select();
        $str = '';
        if ($data){
            foreach ($data as $k => $v) {
                if ($type == $k) {
                    $str .= '<option value="'.$v['RegionCode'].'" selected>'.$v['RegionName'].'</option>';
                } else {
                    $str .= '<option value="'.$v['RegionCode'].'">'.$v['RegionName'].'</option>';
                }
            }
        }
        return $str;
    }

    public static function getProvince($type = 0){
        $data = self::where('LENGTH(RegionCode)=2')->select();
        $str = '<option value="0" selected>选择</option>';
        if ($data){
            foreach ($data as $k => $v) {
                if ($type == $v['RegionCode']) {
                    $str .= '<option value="'.$v['RegionCode'].'" selected>'.$v['RegionName'].'</option>';
                } else {
                    $str .= '<option value="'.$v['RegionCode'].'">'.$v['RegionName'].'</option>';
                }
            }
        }
        return $str;
    }

    public static function getCity($province=0,$type = 0){
        $where = [
            'RegionCode' => ['like',"{$province}%"]
        ];
        $data = self::where($where)->where('LENGTH(RegionCode)=4')->select();
        $str = '<option value="0" selected>选择</option>';
        if ($data){
            foreach ($data as $k => $v) {
                if ($type == $v['RegionCode']) {
                    $str .= '<option value="'.$v['RegionCode'].'" selected>'.$v['RegionName'].'</option>';
                } else {
                    $str .= '<option value="'.$v['RegionCode'].'">'.$v['RegionName'].'</option>';
                }
            }
        }
        return $str;
    }

}