<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28
 * Time: 8:58
 */

namespace app\admin\controller;
use app\admin\model\ApprovalReportReply as ReplyModel;

class ApprovalReportReply extends Admin
{
    public static function getAll($id=0,$limit){
        $report_id = input('id/d');
        if (!empty($id)){
            $report_id = $id;
        }
        $map['report_id'] = $report_id;
        $map['cid'] = session('admin_user.cid');
        $list = ReplyModel::getAll($map,$limit);
        return $list;
    }

    public function add(){
        if ($this->request->isPost()){
            $data = $this->request->post();

            $data['cid'] = session('admin_user.cid');
            $data['user_id'] = session('admin_user.uid');
            $result = $this->validate($data, 'ApprovalReportReply');
            if($result !== true) {
                return $this->error($result);
            }
            if (!ReplyModel::create($data)) {
                return $this->error('添加失败！');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        return $this->fetch();
    }

    public function edit(){

    }

}