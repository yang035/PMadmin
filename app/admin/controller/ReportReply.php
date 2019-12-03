<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28
 * Time: 8:58
 */

namespace app\admin\controller;
use app\admin\model\ReportReply as ReplyModel;
use app\admin\model\Project;
use app\admin\model\ProjectReport;
use app\admin\model\Score;

class ReportReply extends Admin
{
    public static function getAll($id=0,$limit,$type=1){
        $report_id = input('id/d');
        if (!empty($id)){
            $report_id = $id;
        }
        $map['report_id'] = $report_id;
        $map['cid'] = session('admin_user.cid');
        $map['pid'] = 0;
        $map['type'] = $type;
        $list = ReplyModel::getAll($map,$limit);
        return $list;
    }

    public function add(){
        $params = $this->request->param();
        if ($params['project_id']){
            $row = Project::getRowById($params['project_id']);
        }else{
            $row = [];
        }
        if (isset($params['report_id'])){
            $params['id'] = $params['report_id'];
        }

        $row_report = ProjectReport::where('id',$params['id'])->find();

        if ($this->request->isPost()){
            $data = $this->request->post();
            if ($data['realper'] > $row_report['realper']){
                return $this->error("完成情况不能超过最大值[{$row_report['realper']}]");
            }

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $result = $this->validate($data, 'ReportReply');
            if($result !== true) {
                return $this->error($result);
            }

            if (!ReplyModel::create($data)) {
                return $this->error('添加失败！');
            }else{
                if (!empty($data['realper'])){
                    $tmp = [
                        'realper'=>$data['realper'],
                        'status'=>0,
                    ];
                    ProjectReport::where('id',$data['report_id'])->update($tmp);
                    $per_score = $row['score'] * $row_report['per'] / 100 * $data['realper'] / 100;
                    $left_score = $row['score'] - $row['real_score'];
                    if ($per_score > $left_score){
                        $per_score = $left_score;
                    }
                    $real_score = $row['real_score'] + $per_score;
                    $tmp = [
                        'realper' => $row['realper'] + round($row_report['per']*$data['realper']/100,2),
                        'real_score' => $real_score,
                    ];
                    Project::where('id',$data['project_id'])->update($tmp);

                    $score = [
                        'subject_id' => $row['subject_id'],
                        'project_id' => $row['id'],
                        'cid' => $data['cid'],
                        'project_code' => '',
                        'user' => $row_report['user_id'],
                        'ml_add_score' => $per_score,
                        'remark' => '最终核定计算产值',
                        'user_id' => $data['user_id'],
                    ];
                    Score::create($score);
                }
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $this->assign('row', $row);
        $this->assign('row_report', $row_report);
        return $this->fetch();
    }

    public function add20191127(){
        $params = $this->request->param();
        if ($params['project_id']){
            $row = Project::getRowById($params['project_id']);
        }else{
            $row = [];
        }

        if ($this->request->isPost()){
            $data = $this->request->post();
            if ($data['realper'] > $row['realper']){
                return $this->error("完成情况不能超过最大值[{$row['realper']}]");
            }

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $result = $this->validate($data, 'ReportReply');
            if($result !== true) {
                return $this->error($result);
            }

            if (!ReplyModel::create($data)) {
                return $this->error('添加失败！');
            }else{
                if (!empty($data['realper'])){
                    $tmp = [
                        'realper'=>$data['realper'],
                    ];
                    ProjectReport::where('id',$data['report_id'])->update($tmp);
                    Project::where('id',$data['project_id'])->update($tmp);
                }
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

    public function add1(){
        $params = $this->request->param();
        if ($params['project_id']){
            $row = Project::getRowById($params['project_id']);
        }else{
            $row = [];
        }

        if ($this->request->isPost()){
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $result = $this->validate($data, 'ReportReply');
            if($result !== true) {
                return $this->error($result);
            }

            if (!ReplyModel::create($data)) {
                return $this->error('添加失败！');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

    public function edit(){

    }

}