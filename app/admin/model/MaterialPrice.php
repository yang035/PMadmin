<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:00
 */

namespace app\admin\model;


use think\Model;

class MaterialPrice extends Model
{
    public static function getUnitOption($type = '')
    {
        $leaveType = config('other.unit2');
        $str = '<option value="">选择</option>';
        foreach ($leaveType as $k => $v) {
            if ($type == $v) {
                $str .= "<option value='".$v."' selected>".$v."</option>";
            } else {
                $str .= "<option value='".$v."'>".$v."</option>";
            }
        }
        return $str;
    }
}