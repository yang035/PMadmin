<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28
 * Time: 8:58
 */

namespace app\admin\controller;
use app\admin\model\ApprovalReport as ReportModel;
//use app\admin\model\Project;

class ApprovalReport extends Admin
{
    public static function getAll($limit=0){
        $project_id = input('id/d');
        $map['aid'] = $project_id;
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
            $data['per'] = time_per($row['start_time'],$row['end_time']);
            $result = $this->validate($data, 'ApprovalReport');
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
            return $this->success("操作成功{$this->score_value}",url('index'));
        }
    }

    public function edit(){

    }

}