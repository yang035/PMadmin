<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 17:16
 */

namespace app\admin\model;


use think\Model;

class Approval extends Model
{
    public static function getLeaveOption($type = 0)
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

    public static function getOption($type = 0)
    {
        $map = [
            'class_type'=>1,
            'cid'=>session('admin_user.cid'),
            'user_id'=>session('admin_user.uid'),
            'status'=>2,
        ];
        $fields = "id,class_type,user_id,date_format(start_time,'%Y-%m-%d') start_time,date_format(end_time,'%Y-%m-%d') end_time ";
        $list = self::where($map)->field($fields)->limit(3)->order('id desc')->select();
        $str = '';
        if ($list){
            foreach ($list as $k => $v) {
                $str .= '<input type="radio" name="leave_id" lay-skin="primary" title="'.$v['start_time'].' - '.$v['end_time'].'" value="'.$v['id'].'"><br>';
            }
        }
        return !empty($str) ? $str : '无';
    }

    public static function getMoneyByProject($project_id){
        $where = [
            'a.project_id'=>$project_id,
            'a.class_type'=>22,
            'a.status'=>2,
        ];
        $fields = 'sum(b.money) money';
        $waybill = db('approval')->alias('a')->field($fields)
            ->join("tb_approval_waybill b", 'a.id = b.aid', 'right')
            ->where($where)->find();
        $left_money = 0;
        if ($waybill){
            $left_money = $waybill['money'];
            $map = [
                'a.project_id'=>$project_id,
                'a.class_type'=>23,
                'a.status'=>2,
            ];
            $applypay = db('approval')->alias('a')->field($fields)
                ->join("tb_approval_applypay b", 'a.id = b.aid', 'right')
                ->where($map)->find();
            if ($applypay){
                $left_money = $waybill['money'] - $applypay['money'];
            }
        }
        return $left_money;
    }
}