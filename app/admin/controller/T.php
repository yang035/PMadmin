<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/18
 * Time: 9:33
 */

namespace app\admin\controller;


use think\Controller;
use app\admin\model\Project;
use app\admin\model\SubjectItem;
use think\Db;

class T extends Controller
{
    public function index()
    {
        $list = Project::where('pid', 0)->select();
//        print_r($list);exit();
        foreach ($list as $k => $v) {
            unset($v['pid'],$v['code'],$v['node'],$v['pic'],$v['contact'],$v['phone'],$v['result']);
//            print_r(json_decode(json_encode($v),true));exit();
            $v = json_decode(json_encode($v),true);
            $v['idcard'] = rand(100000,999999);
            $v['create_time'] = strtotime($v['create_time']);
            $v['update_time'] = strtotime($v['update_time']);
            SubjectItem::create($v);
        }
    }

    //先改变系统计算时差
    public function test()
    {
        $list = Project::select();
        foreach ($list as $k => $v) {
            $data['id'] = $v['id'];
            $data['time_long'] = $this->getTimeLong(strtotime($v['start_time']), strtotime($v['end_time']));
            Project::update($data);
        }
    }

    public function getTimeLong($time1, $time2)
    {
        $days = (int)(($time2 - $time1) / 3600 / 24);
        $hours = (int)(($time2 - $time1 - $days * 3600 * 24) / 3600);
        $mins = (int)(($time2 - $time1 - $days * 3600 * 24 - $hours * 3600) / 60);
        $sec = $time2 - $time1 - $days * 3600 * 24 - $hours * 3600 - $mins * 60;
        return $days . '天' . $hours . '小时' . $mins . '分钟' . $sec . '秒';
    }

    public function editApproval(){
        $approval = \db('approval')->column('class_type','id');
        foreach ($approval as $k=>$v) {
            switch ($v){
                case 1:
                    $table = 'approval_leave';
                    break;
                case 2:
                    $table = 'approval_expense';
                    break;
                case 3:
                    $table = 'approval_cost';
                    break;
                case 4:
                    $table = 'approval_business';
                    break;
                case 5:
                    $table = 'approval_procurement';
                    break;
                case 6:
                    $table = 'approval_overtime';
                    break;
                case 7:
                    $table = 'approval_goout';
                    break;
                case 8:
                    $table = 'approval_usecar';
                    break;
                case 9:
                    break;
                case 10:
                    break;
                case 11:
                    $table = 'approval_goods';
                    break;
                case 12:
                    $table = 'approval_print';
                    break;
                case 13:
                    $table = 'approval_dispatch';
                    break;
                case 14:
                    $table = 'approval_borrow';
                    break;
                case 15:
                    $table = 'approval_backleave';
                    break;
                case 16:
                    $table = 'approval_bills';
                    break;
                case 17:
                    $table = 'approval_tixian';
                    break;
                case 18:
                    $table = 'approval_leaveoffice';
                    break;
                case 19:
                    $table = 'approval_invoice';
                    break;
                case 20:
                    $table = 'approval_waybill';
                    break;
                case 21:
                    $table = 'approval_applypay';
                    break;
                case 22:
                    $table = 'approval_waybill';
                    break;
                case 23:
                    $table = 'approval_applypay';
                    break;
            }
            $reason = \db($table)->where(['aid'=>$k])->column('reason');
            if ($reason){
                \db('approval')->where(['id'=>$k])->setField('reason',$reason[0]);
            }
        }
    }


}