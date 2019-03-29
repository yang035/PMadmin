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
    public static function getAll($limit=0){
        $project_id = input('id/d');
        $map['project_id'] = $project_id;
        $map['cid'] = session('admin_user.cid');
//        $map['user_id'] = session('admin_user.uid');
        $list = ReportModel::getAll($map,$limit);
        return $list;
    }

    public function add(){
        if ($this->request->isPost()){
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            unset($data['id']);
            $row = Project::getRowById($data['project_id']);
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