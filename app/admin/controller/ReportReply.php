<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28
 * Time: 8:58
 */

namespace app\admin\controller;
use app\admin\model\FlowCat;
use app\admin\model\FlowItem;
use app\admin\model\ReportReply as ReplyModel;
use app\admin\model\Project;
use app\admin\model\ProjectReport;
use app\admin\model\Score;
use app\admin\model\SubjectCat;
use app\admin\model\SubjectFlow;
use think\Db;

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
            $flow_data = [
                'flow_sys'=>$data['flow_sys'],
                'flow_cat'=>$data['flow_cat'],
            ];
            unset($data['flow_sys'],$data['flow_cat']);
            if (isset($data['flow_item'])){
                $flow_data['flow_item'] = $data['flow_item'];
                unset($data['flow_item']);
            }
            if ($flow_data['flow_sys'] && !isset($flow_data['flow_item'])){
                return $this->error('同步位置不能为空');
            }

            if (!ReplyModel::create($data)) {
                return $this->error('添加失败！');
            }else{
                if (!empty($data['realper'])){
                    $tmp = [
                        'realper'=>$data['realper'],
                        'status'=>0,
                        'flow_sys'=>$flow_data['flow_sys'],
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

                    if ($flow_data['flow_sys']){
                        $p_row = Project::getRowById($row['subject_id']);
                        $s_flow = [
                            'cid' => $data['cid'],
                            'subject_id' => $p_row['subject_id'],
                            'flow_id' => $flow_data['flow_item'],
                            'remark' => $row_report['mark'],
                            'attachment' => $row_report['attachment'],
                            'user_id' => $data['user_id'],
                            'flag' => 1,
                        ];
                        SubjectFlow::create($s_flow);
                    }
                }
            }
            return $this->success("操作成功{$this->score_value}");
        }
        $this->assign('row', $row);
        $this->assign('row_report', $row_report);
        $this->assign('flow_cat', FlowCat::getOption1());
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

    public function flowCat(){
        $params = $this->request->param();
        $sql = "SELECT cat_id FROM tb_project WHERE id = (SELECT subject_id FROM tb_project WHERE id= {$params['project_id']} LIMIT 1) LIMIT 1";
        $cat_id = Db::query($sql);
        if (isset($cat_id[0]['cat_id']) && !empty($cat_id[0]['cat_id'])){
            $flow = SubjectCat::field('flow')->where(['id'=>$cat_id[0]['cat_id']])->find();
            $flow = json_decode($flow['flow'],true);
            $w = [
                'id'=>['in',$flow],
            ];
            $fl = FlowItem::where($w)->select();
            $r = [];
            if ($fl){
                foreach ($fl as $k=>$v) {
                    $r[$v['cat_id']][$v['id']] = $v['name'];
                }
            }
            $str = '';
            if (isset($r[$params['flow_cat']])){
                foreach ($r[$params['flow_cat']] as $k=>$v) {
                    $str .= "<option value='".$k."'>".$v."</option>";
                }
            }
            return $this->success("操作成功",'',$str);
        }else{
            return $this->error('联系管理员，配置设计流程！');
        }
    }

}