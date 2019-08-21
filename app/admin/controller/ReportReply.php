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
            }else{
                $tmp = [
                    'realper'=>$data['realper'],
                ];
                ProjectReport::where('id',$data['report_id'])->update($tmp);
                Project::where('id',$data['project_id'])->update($tmp);
            }
            return $this->success("操作成功{$this->score_value}",'');
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
            return $this->success("操作成功{$this->score_value}",'');
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

    public function edit(){

    }

}