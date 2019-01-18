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


}