<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 15:03
 */

namespace app\admin\model;


use think\Model;

class ApprovalPrint extends Model
{
    public static function getPrintOption($type = 0)
    {
        $leaveType = config('other.print_type');
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

    public static function getSizeOption($type = 0)
    {
        $leaveType = config('other.size_type');
        $str = '';
        foreach ($leaveType as $k => $v) {
            if ($type == $k) {
                $str .= "<option value='".$k."' selected>".$v."</option>";
            } else {
                $str .= "<option value='".$k."'>".$v."</option>";
            }
        }
        return $str;
    }

    public static function getQualityOption($type = 0)
    {
        $leaveType = config('other.quality_type');
        $str = '';
        foreach ($leaveType as $k => $v) {
            if ($type == $k) {
                $str .= "<option value='".$k."' selected>".$v."</option>";
            } else {
                $str .= "<option value='".$k."'>".$v."</option>";
            }
        }
        return $str;
    }
}