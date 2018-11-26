<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 11:48
 */

namespace app\admin\controller;
use app\admin\model\AdminUser;
use app\admin\model\DailyReport as DailyReportModel;
use app\admin\model\Project as ProjectModel;
use app\admin\model\Score as ScoreModel;


class DailyReport extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '汇报',
                'url' => 'admin/DailyReport/index',
                'params' =>['atype'=>1],
            ],
            [
                'title' => '我的汇报',
                'url' => 'admin/DailyReport/index',
                'params' =>['atype'=>2],
            ],
            [
                'title' => '汇报给我的',
                'url' => 'admin/DailyReport/index',
                'params' =>['atype'=>3],
            ],
            [
                'title' => '抄送我的',
                'url' => 'admin/DailyReport/index',
                'params' =>['atype'=>4],
            ],
        ];
        $tab_data['current'] = url('index',['atype'=>1]);
        $this->tab_data = $tab_data;
    }
    public function index()
    {
        $params = $this->request->param();
        $map = [];
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        if (1 == $params['atype']){
            $panel_type = config('other.report_type');
            $this->assign('tab_data', $this->tab_data);
            $this->assign('tab_type', 1);
            $this->assign('isparams', 1);
            $this->assign('atype', $params['atype']);
            $this->assign('tab_url', url('index',['atype'=>$params['atype']]));
            $this->assign('panel_type', $panel_type);
            return $this->fetch('panel');
        }
        if ($params){
            if (!empty($params['project_code'])){
                $map['project_code'] = ['like', '%'.$params['project_code'].'%'];
            }
            if (!empty($params['user_id'])){
                $map['user_id'] = $params['user_id'];
            }
        }
        $uid = session('admin_user.uid');
        $con = '';
        switch ($params['atype']){
            case 2:
                $map['user_id'] = session('admin_user.uid');
                break;
            case 3:
                $con = "JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"')";
                break;
            case 4:
                $con = "JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"')";
                break;
            default:
                $con = "";
                break;
        }

        $list = DailyReportModel::where($map)->where($con)->order('create_time desc')->paginate(10, false, ['query' => input('get.')]);
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 1);
        $this->assign('isparams', 1);
        $this->assign('atype', $params['atype']);
        $this->assign('mytask', ProjectModel::getMyTask(0,0));
        $pages = $list->render();
        $this->assign('tab_url', url('index',['atype'=>$params['atype']]));
        $this->assign('data_list', $list);
        $this->assign('project_select', ProjectModel::inputSearchProject());
        $this->assign('user_select', AdminUser::inputSearchUser());
        $this->assign('pages', $pages);
        return $this->fetch();
    }

    public function read($id){
        $params = $this->request->param();
        $where = [
            'id'=>$params['id']
        ];
        $row = DailyReportModel::where($where)->find()->toArray();
        if ($row){
            $row['content'] = json_decode($row['content'],true);
            $row['plan'] = json_decode($row['plan'],true);
            $row['question'] = json_decode($row['question'],true);
            $row['tips'] = json_decode($row['tips'],true);
            $row['attachment'] = json_decode($row['attachment'],true);
            $row['send_user'] = $this->deal_data($row['send_user']);
            $row['copy_user'] = $this->deal_data($row['copy_user']);
        }
        //标记已读
        $uid = session('admin_user.uid');
        switch ($params['atype']){
            case 3:
                $sql = "UPDATE tb_daily_report SET send_user = JSON_SET(send_user, '$.\"{$uid}\"', 'a') WHERE id ={$params['id']}";
                break;
            case 4:
                $sql = "UPDATE tb_daily_report SET copy_user = JSON_SET(copy_user, '$.\"{$uid}\"', 'a') WHERE id ={$params['id']}";
                break;
            default:
                $sql = "UPDATE tb_daily_report SET send_user = JSON_SET(send_user, '$.\"{$uid}\"', 'a') WHERE id ={$params['id']}";
                break;
        }
        ProjectModel::execute($sql);
        $coment = ReportReply::getAll($params['id'],5);

        $this->assign('mytask', ProjectModel::getMyTask(0,0));
        $this->assign('data_list', $row);
        $this->assign('coment', $coment);
        return $this->fetch();
    }

    public function deal_data($x_user)
    {
        $x_user_arr = json_decode($x_user,true);
        $x_user = [];
        if ($x_user_arr){
            foreach ($x_user_arr as $key=>$val){
                $real_name = AdminUser::getUserById($key)['realname'];
                if ('a' == $val){
                    $real_name = "<font style='color: blue'>".$real_name."</font>";
                }
                $x_user[] = $real_name;
            }
            return implode(',',$x_user);
        }
    }

    public function add()
    {
        $d_model = new DailyReportModel();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'DailyReport');
            if($result !== true) {
                return $this->error($result);
            }

            $project_code = ProjectModel::getColumn('code');
            $gl_score = sumLineScore($data['content'])+sumLineScore($data['plan'])+sumLineScore($data['question'])+sumLineScore($data['tips']);
            $ins_data['content'] = json_encode(array_values(array_filter($data['content'])));
            $ins_data['plan'] = json_encode(array_values(array_filter($data['plan'])));
            $ins_data['question'] = json_encode(array_values(array_filter($data['question'])));
            $ins_data['tips'] = json_encode(array_values(array_filter($data['tips'])));
            $ins_data['attachment'] = explode(',',$data['attachment']);
            $ins_data['attachment'] = json_encode(array_values(array_filter($ins_data['attachment'])));
            $ins_data['send_user'] = json_encode(user_array($data['send_user']));
            $ins_data['copy_user'] = json_encode(user_array($data['copy_user']));
            $ins_data['cid'] = session('admin_user.cid');
            $ins_data['user_id'] = session('admin_user.uid');
            $ins_data_all = [];
            //以百分比为参考，当为空时中断循环
            foreach ($data['real_per'] as $k=>$v){
                if (empty($v)){
                    continue;
                }
                $tmp[$k]['project_id'] = $data['project_id'][$k];
                $tmp[$k]['project_code'] = $project_code[$data['project_id'][$k]];
                $tmp[$k]['real_per'] = $v;
                $ins_data_all[$k] = array_merge($tmp[$k],$ins_data);
            }

            //批量添加
            if (!$d_model->saveAll($ins_data_all)) {
                return $this->error('添加失败！');
            }else{
                //计算得分
                $sc = [
                    'project_id'=>0,
                    'user'=>session('admin_user.uid'),
                    'ml_add_score'=>0,
                    'ml_sub_score'=>0,
                    'gl_add_score'=>$gl_score + $this->scoreConfig()['gl']['common'],
                    'gl_sub_score'=>0,
                    'remark' => '日志得分'
                ];
                if (ScoreModel::addScore($sc)){
                    return $this->success("添加成功，奖励{$sc['gl_add_score']}GL分。",'DailyReport/index?atype=2');
                }

            }
            return $this->success('添加成功。','index');
        }

        $where = [
            'user_id'=>session('admin_user.uid'),
        ];
        $row = $d_model->where($where)->order('id desc')->limit(1)->select();
        if ($row){
            $data_info['plan'] = json_decode($row[0]['plan'],true);
            $data_info['create_time'] = explode(' ',$row[0]['create_time'])[0];
        }else{
            $data_info = [];
        }
//        print_r($data_info);
        $this->assign('data_info', $data_info);
        $this->assign('leave_type', DailyReportModel::getOption());
        $this->assign('mytask', ProjectModel::getMyTask(0));
        return $this->fetch();
    }
    public function annualSummary(){
        return $this->fetch();
    }
    public function annualPlan(){
        return $this->fetch();
    }

}