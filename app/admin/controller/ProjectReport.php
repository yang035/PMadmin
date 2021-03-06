<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28
 * Time: 8:58
 */

namespace app\admin\controller;
use app\admin\model\ProjectReport as ReportModel;
use app\admin\model\Project;

class ProjectReport extends Admin
{
    public static function getAll($limit=0,$project_id=null){
        $project_id = empty($project_id) ? input('id/d') : $project_id;
        $map['project_id'] = $project_id;
        $map['cid'] = session('admin_user.cid');
//        $map['user_id'] = session('admin_user.uid');
        $list = ReportModel::getAll($map,$limit);
        return $list;
    }

    public function add20200911(){
        if ($this->request->isPost()){
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            $row = Project::getRowById($data['project_id'],'*,DATEDIFF(end_time,NOW()) hit');
//            print_r($row);exit();
            $time = ' 00:00:00';
            $start_time = strtotime(explode(' ',$row['start_time'])[0].$time);
            $end_time = strtotime ("+1 day", strtotime(explode(' ',$row['end_time'])[0].$time));
            $now_time = strtotime(date('Y-m-d').$time);
            $input_realper_max = ($end_time-$now_time)/86400*100;
            if ($data['realper'] > $input_realper_max){
                return $this->error("完成情况不能超过{$input_realper_max}");
            }

            if ($now_time > $end_time){
                return $this->error('超过任务截止时间，禁止提交！');
            }
            if (!($row['start_time'] == '0000-00-00 00:00:00' || $row['end_time'] == '0000-00-00 00:00:00' || $row['start_time'] >= $row['end_time'])){
                $fenzhi = 1;
                $fenmu = ($end_time-$start_time)/86400;
                if ($now_time < $start_time){
                    $data['per'] = 0;
                }elseif ($now_time > $end_time){
                    $data['per'] = 100;
                }else{
                    $data['per'] = round($fenzhi/$fenmu*100,2);
                }
            }else{
                $data['per'] = 0;
            }
            $result = $this->validate($data, 'ProjectReport');
            if($result !== true) {
                return $this->error($result);
            }
            if (!ReportModel::create($data)) {
                return $this->error('添加失败！');
            }else{
                $now_time = strtotime ("+1 day", strtotime(date('Y-m-d').$time));
                $data['per'] = ceil(($now_time-$start_time)/86400/$fenmu*100);
                $d = [
                    'id'=>$data['project_id'],
                    'per'=>$data['per'],
//                    'realper'=>$data['realper'],
                    'update_time'=>time(),
                ];
                if (!Project::update($d)){
                    return $this->error('添加失败！');
                }
            }
            return $this->success("操作成功{$this->score_value}",'');
        }
    }

    public function add(){
        if ($this->request->isPost()){
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            $row = Project::getRowById($data['project_id'],'*,DATEDIFF(end_time,NOW()) hit');
//            print_r($row);exit();
            if ($row['realper'] < 100 && $row['hit'] < -7){
                return $this->error('逾期超过7天，禁止提交！');
            }
            if (!($row['start_time'] == '0000-00-00 00:00:00' || $row['end_time'] == '0000-00-00 00:00:00' || $row['start_time'] >= $row['end_time'])){
                $fenzhi = (strtotime(date('Y-m-d').'23:59:59') - strtotime($row['start_time']))/3600;
                $fenmu = (strtotime($row['end_time']) - strtotime($row['start_time'])) / 3600;
                $row['time_per'] = ceil($fenzhi/$fenmu*100);
                $data['per'] = $row['time_per'] > 100 ? 100 : $row['time_per'];
            }else{
                $data['per'] = 0;
            }
            $result = $this->validate($data, 'ProjectReport');
            if($result !== true) {
                return $this->error($result);
            }
            if (!ReportModel::create($data)) {
                return $this->error('添加失败！');
            }else{
                $d = [
                    'id'=>$data['project_id'],
                    'per'=>$data['per'],
                    'realper'=>$data['realper'],
                    'update_time'=>time(),
                ];
                if (!Project::update($d)){
                    return $this->error('添加失败！');
                }
            }
            return $this->success("操作成功{$this->score_value}",'');
        }
    }

    public function edit(){

    }

}