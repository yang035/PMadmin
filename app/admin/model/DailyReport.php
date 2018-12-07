<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 17:16
 */

namespace app\admin\model;


use think\Model;

class DailyReport extends Model
{
    protected $autoWriteTimestamp = 'datetime';

    public static function getOption($type = 0)
    {
        $leaveType = config('other.leave_type');
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