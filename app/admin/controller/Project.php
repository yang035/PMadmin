<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 12:15
 */

namespace app\admin\controller;

use app\admin\model\AdminUser;
use app\admin\model\Project as ProjectModel;
use app\admin\model\CheckCat as CatModel;
use app\admin\model\CheckItem as ItemModel;
use app\admin\model\ProjectScorelog as ScorelogModel;
use app\admin\model\ReportCheck as ReportCheckModel;
use app\admin\controller\ProjectReport;
use app\admin\model\SubjectCat;
use app\admin\model\SubjectItem;
use app\admin\controller\ReportCheck;
use app\admin\model\Score as ScoreModel;
use think\Db;
use think\Exception;

class Project extends Admin
{
    public $tab_data = [];

    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = $this->getMenu();
        $tab_data['current'] = url('index', ['atype' => 0]);
        $this->tab_data = $tab_data;
    }

    public function getMenu(){
        $where = [
            'cid'=>session('admin_user.cid'),
        ];
        $list = [];
        $list = SubjectCat::where($where)->column('name','id');
        $tmp = [0=>'全部'];
        $data = $tmp + $list;
        foreach ($data as $k=>$v){
            $res[] = [
                'title' => $v,
                'url' => 'admin/Project/index',
                'params' => ['atype' => $k],
            ];
        }
        return $res;
    }

    public function deal_data($x_user)
    {
        $x_user_arr = json_decode($x_user, true);
        $x_user = [];
        if ($x_user_arr) {
            foreach ($x_user_arr as $key => $val) {
                $real_name = AdminUser::getUserById($key)['realname'];
                if ('a' == $val) {
                    $real_name = "<font style='color: blue'>" . $real_name . "</font>";
                }
                $x_user[] = $real_name;
            }
            return implode(',', $x_user);
        }
    }

    public function deal_data_id($x_user)
    {
        $x_user_arr = json_decode($x_user, true);
        if ($x_user_arr) {
            $tmp = array_keys($x_user_arr);
            return implode(',', $tmp);
        }
        return '';
    }

    public function index()
    {
        $map = [];
        $params = $this->request->param();
//        print_r($params['atype']);exit();
        $params['atype'] = isset($params['atype']) ? $params['atype'] : 1;
        switch ($params['atype']) {
            case 0:
                break;
            default:
                $map['cat_id'] = $params['atype'];
                break;
        }
        $subject_id = 0;
        if ($params) {
            if (isset($params['project_id']) && !empty($params['project_id'])) {
                $map['subject_id'] = $params['project_id'];
                $subject_id = $params['project_id'];
            }

            if (isset($params['start_time']) && !empty($params['start_time'])) {
                $start_time = $params['start_time'];
                $start_time_arr = explode(' - ', $start_time);//这里分隔符两边加空格
                $map['start_time'] = ['between', [$start_time_arr['0'], $start_time_arr['1']]];
            }

            if (isset($params['end_time']) && !empty($params['end_time'])) {
                $end_time = $params['end_time'];
                $end_time_arr = explode(' - ', $end_time);//这里分隔符两边加空格
                $map['end_time'] = ['between', [$end_time_arr['0'], $end_time_arr['1']]];
            }
        }
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        $map['t_type'] = 1;

        if (empty($subject_id)){
            $map['pid'] = 0;
            $list = ProjectModel::index1($map);
        }else{
            $map['pid'] = 0;
            $list = ProjectModel::getAll($map);
        }

//        $aa = new ProjectModel();
//        echo $aa->getLastSql();exit();
        $grade_type = config('other.grade_type');
        $myPro = ProjectModel::getProTask(0,0);

        foreach ($list as $kk => $vv) {
            $list[$kk]['manager_user'] = $this->deal_data($vv['manager_user']);
            $list[$kk]['deal_user'] = $this->deal_data($vv['deal_user']);
            $list[$kk]['copy_user'] = $this->deal_data($vv['copy_user']);
            $list[$kk]['send_user'] = $this->deal_data($vv['send_user']);
            $list[$kk]['user_id'] = AdminUser::getUserById($vv['user_id'])['realname'];
            $list[$kk]['grade'] = $grade_type[$vv['grade']];

            if (0 != $vv['pid']){
                $list[$kk]['project_name'] = $myPro[$vv['subject_id']];
            }else{
                $list[$kk]['project_name'] = $vv['name'];
            }

            $report = ProjectReport::getAll(5,$vv['id']);

            if ($report) {
                foreach ($report as $k => $v) {
                    if (!empty($v['attachment'])){
                        $attachment = explode(',',$v['attachment']);
                        $report[$k]['attachment'] = array_filter($attachment);
                    }
                    $report_user = AdminUser::getUserById($v['user_id'])['realname'];
                    $report[$k]['real_name'] = !empty($report_user) ? $report_user : '';
                    $report[$k]['check_catname'] = ItemModel::getCat()[$v['check_cat']];
                    if (empty($row['child'])){
                        $report[$k]['reply'] = ReportReply::getAll($v['id'], 5);
                    }else{
                        $reply = ReportCheck::getAll($v['id'], 1);
                        if ($reply){
                            foreach ($reply as $key=>$val){
                                $content = json_decode($val['content'], true);
                                if ($content){
                                    foreach ($content as $kk=>$vv){
                                        $content[$kk]['flag'] = $vv['flag'] ? '有' : '无';
                                        $content[$kk]['person_user'] = $this->deal_data(json_encode(user_array($vv['person_user'])));
                                        if (!isset($vv['isfinish'])){
                                            $content[$kk]['isfinish'] = 0;
                                        }
                                        if (!isset($vv['remark'])){
                                            $content[$kk]['remark'] = '';
                                        }
                                    }
                                }
                                $reply[$key]['content'] = $content;
                                $reply[$key]['user_name'] = AdminUser::getUserById($val['user_id'])['realname'];
                            }
                        }
                        $report[$k]['reply'] = $reply;
                    }

                }
                $list[$kk]['report'] = $report;
            }

        }
//        print_r($list);
        if ($this->request->isAjax()) {
            $data = [];
            $data['code'] = 0;
            $data['msg'] = 'ok';
            $data['data'] = $list;
            return json($data);
        }
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 1);
        $this->assign('tab_url', url('index', ['atype' => $params['atype']]));
        $this->assign('isparams', 1);
        $this->assign('atype', $params['atype']);
        $this->assign('subject_item', SubjectItem::getItemOption($subject_id,$params['atype']));
        return $this->fetch();
    }

    public function index1()
    {
        $map = [];
        $params = $this->request->param();
//        print_r($params['atype']);exit();
        $params['atype'] = isset($params['atype']) ? $params['atype'] : 1;
        switch ($params['atype']) {
            case 0:
                break;
            default:
                $map['cat_id'] = $params['atype'];
                break;
        }
        $subject_id = 0;
        if ($params) {
            if (isset($params['project_id']) && !empty($params['project_id'])) {
                $map['subject_id'] = $params['project_id'];
                $subject_id = $params['project_id'];
            }

            if (isset($params['start_time']) && !empty($params['start_time'])) {
                $start_time = $params['start_time'];
                $start_time_arr = explode(' - ', $start_time);//这里分隔符两边加空格
                $map['start_time'] = ['between', [$start_time_arr['0'], $start_time_arr['1']]];
            }

            if (isset($params['end_time']) && !empty($params['end_time'])) {
                $end_time = $params['end_time'];
                $end_time_arr = explode(' - ', $end_time);//这里分隔符两边加空格
                $map['end_time'] = ['between', [$end_time_arr['0'], $end_time_arr['1']]];
            }
        }
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        $map['t_type'] = 1;

        if (empty($subject_id)){
            $map['pid'] = 0;
            $list = ProjectModel::index1($map);
        }else{
            $map['pid'] = 0;
            $list = ProjectModel::getAll($map);
        }

//        $aa = new ProjectModel();
//        echo $aa->getLastSql();exit();
        $grade_type = config('other.grade_type');
        foreach ($list as $k => $v) {
            $list[$k]['manager_user'] = $this->deal_data($v['manager_user']);
            $list[$k]['deal_user'] = $this->deal_data($v['deal_user']);
            $list[$k]['copy_user'] = $this->deal_data($v['copy_user']);
            $list[$k]['send_user'] = $this->deal_data($v['send_user']);
            $list[$k]['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];
            $list[$k]['grade'] = $grade_type[$v['grade']];
        }
        if ($this->request->isAjax()) {
            $data = [];
            $data['code'] = 0;
            $data['msg'] = 'ok';
            $data['data'] = $list;
            return json($data);
        }
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 1);
        $this->assign('tab_url', url('index', ['atype' => $params['atype']]));
        $this->assign('isparams', 1);
        $this->assign('atype', $params['atype']);
        $this->assign('subject_item', SubjectItem::getItemOption($subject_id,$params['atype']));
        return $this->fetch();
    }

    public function read()
    {
        $params = $this->request->param();
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        $map['id'] = $params['id'];
        $row = ProjectModel::where($map)->find()->toArray();
//        $row['time_long'] = floor((strtotime($row['end_time'])-strtotime($row['start_time']))/86400);
        $row['manager_user_id'] = $this->deal_data($row['manager_user']);
        $row['deal_user_id'] = $this->deal_data($row['deal_user']);
        $row['copy_user_id'] = $this->deal_data($row['copy_user']);
        $row['send_user_id'] = $this->deal_data($row['send_user']);
        if ($row){
            if (!empty($row['attachment'])){
                $attachment = explode(',',$row['attachment']);
                $row['attachment_show'] = array_filter($attachment);
            }
        }
        if ($row['pid']) {
            $map = [];
            $map['cid'] = $cid;
            $map['id'] = $row['pid'];
            $res = ProjectModel::where($map)->find()->toArray();
            $this->assign('pname', $res['name']);
        } else {
            $this->assign('pname', '顶级项目');
        }
        $this->assign('data_info', $row);
        $this->assign('grade_type', ProjectModel::getGrade($row['grade']));
        return $this->fetch();
    }

    public function add()
    {
        $params = $this->request->param();
        if (!empty($params['id'])) {
            $p_res = ProjectModel::where('id', $params['id'])->find();
            if (!$p_res) {
                return $this->error('计划编号不存在');
            }
            if (empty($p_res['pid'])){
                $m_id = $p_res['id'];
            }else{
                $m_id = $p_res['subject_id'];
            }
            $sub_total_score = ProjectModel::where('pid', $params['id'])->column('sum(score)');
            $p_res['max_score'] = $p_res['score'] - $sub_total_score[0];
            $this->assign('p_res', $p_res);
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();

            if (isset($data['max_score']) && $data['score'] > $data['max_score']) {
                return $this->error('预设值超过最大值！');
            }
            $data['cid'] = session('admin_user.cid');
            if($data['start_time'] >= $data['end_time']){
                return $this->error('结束时间不能小于开始时间');
            }
            if ($data['pid'] == '') {
                $data['pid'] = 0;
            } else {
                $data['pid'] = $data['id'];
            }
            if (empty($data['code'])) {
                $data['code'] = $data['cid'] . 'p';
            } else {
                $data['code'] = $this->getCode($data['code'], $data['pid']);
            }

            $data['manager_user'] = json_encode(user_array($data['manager_user']));
            $data['deal_user'] = json_encode(user_array($data['deal_user']));
            $data['send_user'] = json_encode(user_array1($data['send_user']));
            $data['copy_user'] = json_encode(user_array($data['copy_user']));
            $data['subject_id'] = empty($p_res['pid']) ? $data['id'] : $p_res['subject_id'];

            unset($data['id'], $data['pname'], $data['max_score']);
            $data['user_id'] = session('admin_user.uid');

            // 验证
            $result = $this->validate($data, 'Project');
            if ($result !== true) {
                return $this->error($result);
            }

            Db::startTrans();
            try{
                $res = ProjectModel::create($data);
                $ids = str_replace('p',',',substr(stristr($data['code'],'p'),1,-1));
                $w = [
                    'id' => ['in',$ids]
                ];
                $r = ProjectModel::where($w)->select();
                if ($r){
                    foreach ($r as $k=>$v){
                        $u = [
                            'end_time' =>$data['end_time'],
                            'update_time'=>time(),
                        ];
                        ProjectModel::where('id',$v['id'])->update($u);
                    }
                }
                Db::commit();
            }catch (\Exception $e){
                Db::rollback();
            }

            if (!$res) {
                return $this->error('添加失败！');
            }
            return $this->success("操作成功{$this->score_value}", url('index'));
        }


        $cid = session('admin_user.cid');
        $redis = service('Redis');
        $default_user = $redis->get("pm:user:{$cid}");
        if ($default_user) {
            $user = json_decode($default_user);
            $this->assign('data_info', (array)$user);
        }

        $this->assign('grade_type', ProjectModel::getGrade());
        $this->assign('cat_id', ProjectModel::getPtype());
        $this->assign('p_source', ProjectModel::getPsource());
        $this->assign('major_option', ProjectModel::getOption1($m_id));
        return $this->fetch('form');
    }

    public function getMajorItem($id,$major_cat=0,$major_item=0){
        $p_res = ProjectModel::where('id', $id)->find();
        if (!$p_res) {
            return $this->error('计划编号不存在');
        }
        if (empty($p_res['pid'])){
            $m_id = $p_res['id'];
        }else{
            $m_id = $p_res['subject_id'];
        }
        $child_option = ProjectModel::getChilds($m_id,$major_cat,$major_item);
        echo json_encode($child_option);
    }

    public function add1()
    {
        $params = $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();

            if (isset($data['max_score']) && $data['score'] > $data['max_score']) {
                return $this->error('预设值超过最大值！');
            }
            $data['cid'] = session('admin_user.cid');
            if ($data['pid'] == '') {
                $data['pid'] = 0;
            } else {
                $data['pid'] = $data['id'];
            }
            if (empty($data['code'])) {
                $data['code'] = $data['cid'] . 'p';
            } else {
                $data['code'] = $this->getCode($data['code'], $data['pid']);
            }

            $data['manager_user'] = json_encode(user_array($data['manager_user']));
            $data['deal_user'] = json_encode(user_array($data['deal_user']));
            $data['send_user'] = json_encode(user_array($data['send_user']));
            $data['copy_user'] = json_encode(user_array($data['copy_user']));

            unset($data['id'], $data['pname'], $data['max_score']);
            $data['user_id'] = session('admin_user.uid');
            // 验证
            $result = $this->validate($data, 'Project');
            if ($result !== true) {
                return $this->error($result);
            }
            if (!ProjectModel::create($data)) {
                return $this->error('添加失败！');
            }
            return $this->success("操作成功{$this->score_value}", url('index'));
        }
        if (!empty($params['pname'])) {
            $sub_total_score = ProjectModel::where('pid', $params['id'])->column('sum(score)');
            $max_score = $params['pscore'] - $sub_total_score[0];
            $p_res = ProjectModel::where('name', $params['pname'])->limit(1)->select();
//            print_r($p_res[0]['p_type']);
            $this->assign('pname', $params['pname']);
            $this->assign('parent_type', $p_res[0]['cat_id']);
            $this->assign('max_score', $max_score);
        }

        $cid = session('admin_user.cid');
        $redis = service('Redis');
        $default_user = $redis->get("pm:user:{$cid}");
        if ($default_user) {
            $user = json_decode($default_user);
            $this->assign('data_info', (array)$user);
        }

        $this->assign('grade_type', ProjectModel::getGrade());
        $this->assign('cat_id', ProjectModel::getPtype());
        $this->assign('p_source', ProjectModel::getPsource());
        return $this->fetch('form');
    }

    public function edit()
    {
        $params = $this->request->param();
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        $map['id'] = $params['id'];
        $row = ProjectModel::where($map)->find()->toArray();
        if ($this->request->isPost()) {
            $data = $this->request->post();

            if (isset($data['max_score']) && $data['score'] > $data['max_score']) {
                return $this->error('预设值超过最大值！');
            }
            if($data['start_time'] >= $data['end_time']){
                return $this->error('结束时间不能小于开始时间');
            }
            $data['cid'] = session('admin_user.cid');
            if ($data['pid'] == '') {
                $data['pid'] = 0;
            }
            if (empty($data['code']) || empty($data['pid'])) {
                $data['code'] = $data['cid'] . 'p';
            } else {
                $data['code'] = $this->getCode($data['code'], $data['pid']);
            }

            unset($data['pname'], $data['max_score']);
            $data['user_id'] = session('admin_user.uid');
            $data['manager_user'] = json_encode(user_array($data['manager_user']));
            $data['deal_user'] = json_encode(user_array($data['deal_user']));
            $data['send_user'] = json_encode(user_array1($data['send_user']));
            $data['copy_user'] = json_encode(user_array($data['copy_user']));
            // 验证
            $result = $this->validate($data, 'Project');
            if ($result !== true) {
                return $this->error($result);
            }
            if (!ProjectModel::update($data)) {
                return $this->error('修改失败！');
            }
            return $this->success("修改成功{$this->score_value}", url('index'));
        }

//        $row['time_long'] = floor((strtotime($row['end_time'])-strtotime($row['start_time']))/86400);
        if ($row){
            if (!empty($row['attachment'])){
                $attachment = explode(',',$row['attachment']);
                $row['attachment_show'] = array_filter($attachment);
            }
            if (empty($row['pid'])){
                $m_id = $row['id'];
            }else{
                $m_id = $row['subject_id'];
            }
        }
        $row['manager_user_id'] = $this->deal_data($row['manager_user']);
        $row['deal_user_id'] = $this->deal_data($row['deal_user']);
        $row['copy_user_id'] = $this->deal_data($row['copy_user']);
        $row['send_user_id'] = $this->deal_data($row['send_user']);

        $row['manager_user'] = $this->deal_data_id($row['manager_user']);
        $row['deal_user'] = $this->deal_data_id($row['deal_user']);
        $row['copy_user'] = $this->deal_data_id($row['copy_user']);
        $row['send_user'] = $this->deal_data_id($row['send_user']);


        if ($row['pid']) {
            $map = [];
            $map['cid'] = $cid;
            $map['id'] = $row['pid'];
            $res = ProjectModel::where($map)->find()->toArray();
            $sub_total_score = ProjectModel::where("pid = {$row['pid']} and id <> {$row['id']}")->column('sum(score)');
            $max_score = $res['score'] - $sub_total_score[0];
            $this->assign('max_score', $max_score);
            $this->assign('pname', $res['name']);
        } else {
            $this->assign('pname', '顶级项目');
        }
//        print_r($row);
        $this->assign('data_info', $row);
        $this->assign('grade_type', ProjectModel::getGrade($row['grade']));
        $this->assign('cat_id', ProjectModel::getPtype($row['cat_id']));
        $this->assign('p_source', ProjectModel::getPsource($row['p_source']));
        $this->assign('major_option', ProjectModel::getOption1($m_id,$row['major_cat']));
        return $this->fetch();
    }

    public function del()
    {
        $params = $this->request->param();
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        $map['pid|id'] = $params['ids'];
        $map['status'] = 1;
        $res = ProjectModel::where($map)->select();
        if ($res) {
            return $this->error('当前任务或子任务正在使用');
        }
        $flag = ProjectModel::where(['id' => $params['ids']])->delete();
        if ($flag) {
            return $this->success('删除成功');
        }
    }

    public function getCode($pcode = '', $pid = 0)
    {
        $result = ProjectModel::getRowById($pid);
        if ($result['code'] . $pid . 'p' == $pcode) {
            return $pcode;
        } else {
            return $result['code'] . $pid . 'p';
        }
    }

    public function schedule()
    {
        $tab_data['menu'] = [
            [
                'title' => '计划管理',
                'url' => 'admin/Project/schedule',
            ],
        ];
        $params = $this->request->param();
        $map = [];
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        $map['t_type'] = 1;
        $map['user_id'] = session('admin_user.uid');
        if ($params) {
            if (!empty($params['name'])) {
                $map['name'] = ['like', '%' . $params['name'] . '%'];
            }
            if (!empty($params['start_time'])) {
                $map['start_time'] = ['egt', $params['start_time']];
            }
            if (!empty($params['end_time'])) {
                $map['end_time'] = ['elt', $params['end_time']];
            }
        }
        $list = ProjectModel::where($map)->order('grade desc,create_time desc')->paginate(20, false, ['query' => input('get.')]);
        $data = [];
        $grade_type1 = config('other.grade_type1');
        if ($list) {
            foreach ($list as $k => $v) {
                $data[$k]['name'] = $v['name'];
                $data[$k]['desc'] = $v['remark'];
                $data[$k]['values'][$k]['id'] = $v['id'];
                $data[$k]['values'][$k]['from'] = "/Date(" . strtotime($v['start_time']) . "000)/";
                $data[$k]['values'][$k]['to'] = "/Date(" . strtotime($v['end_time']) . "000)/";
                $data[$k]['values'][$k]['desc'] = '';
                $data[$k]['values'][$k]['label'] = $v['realper'] . "% / " . $v['per'] . "%<span class='s_tip_" . $v['id'] . "'></span>";
                $data[$k]['values'][$k]['customClass'] = $grade_type1[$v['grade']];
                $data[$k]['values'][$k]['dataObj'] = $v;

            }
        }
        $this->assign('data_list', json_encode(array_values($data)));
        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 2);
        return $this->fetch();
    }

    public function mytask($type = 1)
    {
        $params = $this->request->param();
        $map = [];
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        $map['t_type'] = 1;
        $subject_id = 0;

        if ($params) {
            if (!empty($params['project_id']) && is_numeric($params['project_id'])){
                $map['id'] = $params['project_id'];
                $subject_id = $params['project_id'];
            }else{
                $map['pid'] = 0;
            }

            if (isset($params['start_time']) && !empty($params['start_time'])) {
                $start_time = $params['start_time'];
                $start_time_arr = explode(' - ', $start_time);//这里分隔符两边加空格
                $map['start_time'] = ['between', [$start_time_arr['0'].' 00:00:00', $start_time_arr['1'].' 23:59:59']];
            }

            if (isset($params['end_time']) && !empty($params['end_time'])) {
                $end_time = $params['end_time'];
                $end_time_arr = explode(' - ', $end_time);//这里分隔符两边加空格
                $map['end_time'] = ['between', [$end_time_arr['0'].' 00:00:00', $end_time_arr['1'].' 23:59:59']];
            }
            if (!empty($params['name'])) {
                $map['name'] = ['like', '%' . $params['name'] . '%'];
            }
            if (isset($params['status'])) {
                $map['status'] = $params['status'];
            }
        }
        $uid = session('admin_user.uid');

        $fields = "SUM(IF(JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"') AND send_user LIKE '%a%',1,0)) deal_num,
        SUM(IF(JSON_CONTAINS_PATH(manager_user,'one', '$.\"$uid\"'),1,0)) manager_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = '',1,0)) send_num,
        SUM(IF(JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"'),1,0)) copy_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = 'a',1,0)) has_num";
        $sta_count = ProjectModel::field($fields)->where($map)->find()->toArray();

        $tab_data['menu'] = [
            [
                'title' => "我参与的<span class='layui-badge layui-bg-orange'>{$sta_count['deal_num']}</span>",
                'url' => 'admin/project/mytask',
                'params' => ['type' => 1],
            ],
            [
                'title' => "我负责的<span class='layui-badge layui-bg-orange'>{$sta_count['manager_num']}</span>",
                'url' => 'admin/project/mytask',
                'params' => ['type' => 2],
            ],
            [
                'title' => "待我审批<span class='layui-badge'>{$sta_count['send_num']}</span>",
                'url' => 'admin/project/mytask',
                'params' => ['type' => 3],
            ],
            [
                'title' => "抄送我的<span class='layui-badge layui-bg-orange'>{$sta_count['copy_num']}</span>",
                'url' => 'admin/project/mytask',
                'params' => ['type' => 4],
            ],
            [
                'title' => "已审批的<span class='layui-badge layui-bg-orange'>{$sta_count['has_num']}</span>",
                'url' => 'admin/project/mytask',
                'params' => ['type' => 5],
            ],
        ];
        $tab_data['current'] = url('mytask', ['type' => 1]);

        switch ($params['type']) {
            case 1:
                $con = "JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"') AND send_user LIKE '%a%'";
                break;
            case 2:
                $con = "JSON_CONTAINS_PATH(manager_user,'one', '$.\"$uid\"')";
                break;
            case 3:
                $con = "JSON_EXTRACT(send_user,'$.\"$uid\"') = ''";
                break;
            case 4:
                $con = "JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"')";
                break;
            case 5:
                $con = "JSON_EXTRACT(send_user,'$.\"$uid\"') = 'a'";
                break;
            default:
                $con = "JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"')";
                break;
        }
        $field = "*,DATEDIFF(end_time,NOW()) hit,JSON_EXTRACT(manager_user,'$.\"{$uid}\"') m_res,JSON_EXTRACT(send_user,'$.\"{$uid}\"') s_res,JSON_EXTRACT(deal_user,'$.\"{$uid}\"') d_res,JSON_EXTRACT(copy_user,'$.\"{$uid}\"') c_res";

        if (empty($subject_id)) {
//            $list = ProjectModel::field($field)->where($map)->where($con)->order('grade desc,create_time desc')->select();
            $st = strtotime('-7 days');
            $et = strtotime('+3 days');
            $map['update_time'] = ['between',[$st,$et]];
            $result = ProjectModel::field($field)->where($map)->order('grade desc,create_time desc')->select();
            if ($result){
                $ids = array_column($result,'id');
                $map['subject_id'] = ['in',implode(',',$ids)];
                $map['pid'] =['<>',0];
                $result1 = ProjectModel::field($field)->where($map)->where($con)->order('grade desc,create_time desc')->select();
                if ($result1){
                    foreach ($result1 as $k=>$v){
                        if ($v['realper'] < 100){
                            if ($v['hit'] < 0){
                                $v['name'] = "<font style='color: red;font-weight:bold'>[逾期]</font>".$v['name'];
                            }elseif ($v['hit'] == 0){
                                $v['name'] = "<font style='color: blue;font-weight:bold'>[当日]</font>".$v['name'];
                            }else{
                                $v['name'] = "<font style='color: green'>[待完成]</font>".$v['name'];
                            }
                        }else{
                            if ($v['real_score'] == 0 && $params['type'] == 2){
                                $v['name'] = "<font style='color: darkturquoise;font-weight:bold'>[待评定]</font>".$v['name'];
                            }
                        }
                    }
                }
                $list = array_unique(array_merge($result1,$result));//顺序不能颠倒
            }else{
                $list = [];
            }

        }else{
            $result = ProjectModel::field($field)->where($map)->order('grade desc,create_time desc')->limit(1)->select();
            if ($result){
                $map['subject_id'] = $result[0]['id'];
                unset($map['id']);
                $result1 = ProjectModel::field($field)->where($map)->where($con)->order('grade desc,create_time desc')->select();
                if ($result1){
                    foreach ($result1 as $k=>$v){
                        if ($v['realper'] < 100){
                            if ($v['hit'] < 0){
                                $v['name'] = "<font style='color: red;font-weight:bold'>[逾期]</font>".$v['name'];
                            }elseif ($v['hit'] == 0){
                                $v['name'] = "<font style='color: blue;font-weight:bold'>[当日]</font>".$v['name'];
                            }else{
                                $v['name'] = "<font style='color: green'>[待完成]</font>".$v['name'];
                            }
                        }else{
                            if ($v['real_score'] == 0 && $params['type'] == 2){
                                $v['name'] = "<font style='color: darkturquoise;font-weight:bold'>[待评定]</font>".$v['name'];
                            }
                        }
                    }
                }
                $list = array_unique(array_merge($result1,$result));//顺序不能颠倒
            }else{
                $list = [];
            }
        }

//        $aaa = new  ProjectModel();
//        print_r($aaa->getLastSql());
        $grade_type = config('other.grade_type');
        $u_res_conf = config('other.res_type');
//        print_r($list);
        if ($list){
            $myPro = ProjectModel::getProTask(0,0);
            foreach ($list as $kk => $vv) {
                $list[$kk]['manager_user'] = $this->deal_data($vv['manager_user']);
                $list[$kk]['deal_user'] = $this->deal_data($vv['deal_user']);
                $list[$kk]['copy_user'] = $this->deal_data($vv['copy_user']);
                $list[$kk]['send_user'] = $this->deal_data($vv['send_user']);
                $list[$kk]['user_id'] = AdminUser::getUserById($vv['user_id'])['realname'];
                $list[$kk]['grade'] = $grade_type[$vv['grade']];
                if (0 != $vv['pid']){
                    $list[$kk]['project_name'] = $myPro[$vv['subject_id']];
                }else{
                    $list[$kk]['project_name'] = $vv['name'];
                }
                $child = ProjectModel::getChildCount($vv['id']);
                if ($child){
                    $list[$kk]['child'] = 1;
                }else{
                    $list[$kk]['child'] = 0;
                }

                switch ($params['type']) {
                    case 1:
                        $u_res = $vv['d_res'];
                        break;
                    case 2:
                        $u_res = $vv['m_res'];
                        break;
                    case 3:
                        $u_res = $vv['s_res'];
                        break;
                    case 4:
                        $u_res = $vv['c_res'];
                        break;
                    default:
                        $u_res = $vv['d_res'];
                        break;
                }
                $list[$kk]['u_res'] = trim($u_res, '"');

                $list[$kk]['u_res_str'] = $u_res_conf[$list[$kk]['u_res']];

                $report = ProjectReport::getAll(5,$vv['id']);

                if ($report) {
                    foreach ($report as $k => $v) {
                        if (!empty($v['attachment'])){
                            $attachment = explode(',',$v['attachment']);
                            $report[$k]['attachment'] = array_filter($attachment);
                        }
                        $report_user = AdminUser::getUserById($v['user_id'])['realname'];
                        $report[$k]['real_name'] = !empty($report_user) ? $report_user : '';
                        $report[$k]['check_catname'] = ItemModel::getCat()[$v['check_cat']];
                        if (empty($row['child'])){
                            $report[$k]['reply'] = ReportReply::getAll($v['id'], 5);
                        }else{
                            $reply = ReportCheck::getAll($v['id'], 1);
                            if ($reply){
                                foreach ($reply as $key=>$val){
                                    $content = json_decode($val['content'], true);
                                    if ($content){
                                        foreach ($content as $kk=>$vv){
                                            $content[$kk]['flag'] = $vv['flag'] ? '有' : '无';
                                            $content[$kk]['person_user'] = $this->deal_data(json_encode(user_array($vv['person_user'])));
                                            if (!isset($vv['isfinish'])){
                                                $content[$kk]['isfinish'] = 0;
                                            }
                                            if (!isset($vv['remark'])){
                                                $content[$kk]['remark'] = '';
                                            }
                                        }
                                    }
                                    $reply[$key]['content'] = $content;
                                    $reply[$key]['user_name'] = AdminUser::getUserById($val['user_id'])['realname'];
                                }
                            }
                            $report[$k]['reply'] = $reply;
                        }

                    }
                    $list[$kk]['report'] = $report;
                }

            }
        }
        if ($this->request->isAjax()) {
//            print_r($list);
            $data = [];
            $data['code'] = 0;
            $data['msg'] = 'ok';
            $data['data'] = $list;
            return json($data);
        }

        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        $this->assign('isparams', 1);
        $this->assign('type', $params['type']);
//        $pages = $list->render();
        $this->assign('tab_url', url('mytask', ['type' => $params['type']]));
        $this->assign('project_select', ProjectModel::inputSearchProject());
//        $this->assign('data_list', $list);
//        $this->assign('pages', $pages);
//        return $this->fetch();

//        $this->assign('tab_data', $this->tab_data);
//        $this->assign('tab_type', 1);
//        $this->assign('tab_url', url('index', ['atype' => $params['atype']]));
//        $this->assign('isparams', 1);
//        $this->assign('atype', $params['atype']);
        $this->assign('subject_item', SubjectItem::getItemOption($subject_id));
        return $this->fetch();
    }

    public function mytask1($type = 1)
    {
        $params = $this->request->param();
        $map = [];
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        $map['t_type'] = 1;
        if ($params) {
            if (!empty($params['project_id'])){
                $map['subject_id'] = $params['project_id'];
            }
            if (!empty($params['name'])) {
                $map['name'] = ['like', '%' . $params['name'] . '%'];
            }
            if (isset($params['status'])) {
                $map['status'] = $params['status'];
            }
        }
        $uid = session('admin_user.uid');

        $fields = "SUM(IF(JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"') AND send_user LIKE '%a%',1,0)) deal_num,
        SUM(IF(JSON_CONTAINS_PATH(manager_user,'one', '$.\"$uid\"'),1,0)) manager_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = '',1,0)) send_num,
        SUM(IF(JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"'),1,0)) copy_num,
        SUM(IF(JSON_EXTRACT(send_user,'$.\"$uid\"') = 'a',1,0)) has_num";
        $sta_count = ProjectModel::field($fields)->where($map)->find()->toArray();

        $tab_data['menu'] = [
            [
                'title' => "我参与的<span class='layui-badge layui-bg-orange'>{$sta_count['deal_num']}</span>",
                'url' => 'admin/project/mytask',
                'params' => ['type' => 1],
            ],
            [
                'title' => "我负责的<span class='layui-badge layui-bg-orange'>{$sta_count['manager_num']}</span>",
                'url' => 'admin/project/mytask',
                'params' => ['type' => 2],
            ],
            [
                'title' => "待我审批<span class='layui-badge'>{$sta_count['send_num']}</span>",
                'url' => 'admin/project/mytask',
                'params' => ['type' => 3],
            ],
            [
                'title' => "抄送我的<span class='layui-badge layui-bg-orange'>{$sta_count['copy_num']}</span>",
                'url' => 'admin/project/mytask',
                'params' => ['type' => 4],
            ],
            [
                'title' => "已审批的<span class='layui-badge layui-bg-orange'>{$sta_count['has_num']}</span>",
                'url' => 'admin/project/mytask',
                'params' => ['type' => 5],
            ],
        ];
        $tab_data['current'] = url('mytask', ['type' => 1]);

        switch ($params['type']) {
            case 1:
                $con = "JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"') AND send_user LIKE '%a%'";
                break;
            case 2:
                $con = "JSON_CONTAINS_PATH(manager_user,'one', '$.\"$uid\"')";
                break;
            case 3:
                $con = "JSON_EXTRACT(send_user,'$.\"$uid\"') = ''";
                break;
            case 4:
                $con = "JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"')";
                break;
            case 5:
                $con = "JSON_EXTRACT(send_user,'$.\"$uid\"') = 'a'";
                break;
            default:
                $con = "JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"')";
                break;
        }
        $field = "*,JSON_EXTRACT(manager_user,'$.\"{$uid}\"') m_res,JSON_EXTRACT(send_user,'$.\"{$uid}\"') s_res,JSON_EXTRACT(deal_user,'$.\"{$uid}\"') d_res,JSON_EXTRACT(copy_user,'$.\"{$uid}\"') c_res";
        $list = ProjectModel::field($field)->where($map)->where($con)->order('grade desc,create_time desc')->paginate(10, false, ['query' => input('get.')]);
//        $aaa = new  ProjectModel();
//        print_r($aaa->getLastSql());
        $grade_type = config('other.grade_type');
        $u_res_conf = config('other.res_type');
//        print_r($list);
        if ($list){
            $myPro = ProjectModel::getMyTask(0,0);
            foreach ($list as $k => $v) {
                $list[$k]['manager_user'] = $this->deal_data($v['manager_user']);
                $list[$k]['deal_user'] = $this->deal_data($v['deal_user']);
                $list[$k]['copy_user'] = $this->deal_data($v['copy_user']);
                $list[$k]['send_user'] = $this->deal_data($v['send_user']);
                $list[$k]['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];
                $list[$k]['grade'] = $grade_type[$v['grade']];
                if (0 != $v['pid']){
                    $list[$k]['project_name'] = $myPro[$v['subject_id']];
                }else{
                    $list[$k]['project_name'] = $v['name'];
                }

                switch ($params['type']) {
                    case 1:
                        $u_res = $v['d_res'];
                        break;
                    case 2:
                        $u_res = $v['m_res'];
                        break;
                    case 3:
                        $u_res = $v['s_res'];
                        break;
                    case 4:
                        $u_res = $v['c_res'];
                        break;
                    default:
                        $u_res = $v['d_res'];
                        break;
                }
                $list[$k]['u_res'] = trim($u_res, '"');

                $list[$k]['u_res_str'] = $u_res_conf[$list[$k]['u_res']];
            }
        }

//        print_r($list);

        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        $this->assign('isparams', 1);
        $this->assign('type', $params['type']);
        $pages = $list->render();
        $this->assign('tab_url', url('mytask', ['type' => $params['type']]));
        $this->assign('project_select', ProjectModel::inputSearchProject());
        $this->assign('data_list', $list);
        $this->assign('pages', $pages);
        return $this->fetch();
    }

    public function editTask()
    {
        $params = $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();

            if (!ProjectModel::update($data)) {
                return $this->error('修改失败！');
            }
            return $this->success('修改成功。', url('index'));
        }
        $cid = session('admin_user.cid');
        $uid = session('admin_user.uid');
        $map['cid'] = $cid;
        $map['id'] = $params['id'];
        $field = "*,JSON_EXTRACT(manager_user,'$.\"{$uid}\"') m_res,JSON_EXTRACT(send_user,'$.\"{$uid}\"') s_res,JSON_EXTRACT(deal_user,'$.\"{$uid}\"') d_res,JSON_EXTRACT(copy_user,'$.\"{$uid}\"') c_res";
        $row = ProjectModel::field($field)->where($map)->find()->toArray();
        $row['time_long'] = floor((strtotime($row['end_time']) - strtotime($row['start_time'])) / 86400);
        $row['manager_user_id'] = $this->deal_data($row['manager_user']);
        $row['deal_user_id'] = $this->deal_data($row['deal_user']);
        $row['copy_user_id'] = $this->deal_data($row['copy_user']);
        $row['send_user_id'] = $this->deal_data($row['send_user']);
        if ($row){
            if (!empty($row['attachment'])){
                $attachment = explode(',',$row['attachment']);
                $row['attachment_show'] = array_filter($attachment);
            }
        }
//        print_r($row);
        $child = ProjectModel::getChildCount($row['id']);
//        print_r($child);
        if ($child){
            $row['child'] = 1;
        }else{
            $row['child'] = 0;
        }
        if (!($row['start_time'] == '0000-00-00 00:00:00' || $row['end_time'] == '0000-00-00 00:00:00' || $row['start_time'] >= $row['end_time'])){
            $fenzhi = (strtotime(date('Y-m-d').'23:59:59') - strtotime($row['start_time']))/3600;
            $fenmu = (strtotime($row['end_time']) - strtotime($row['start_time'])) / 3600;
            $row['time_per'] = ceil($fenzhi/$fenmu*100);
            $row['time_per'] = $row['time_per'] > 100 ? 100 : $row['time_per'];
        }else{
            $row['time_per'] = 0;
        }
        if (time() > $row['end_time']){
            $row['span'] = "(限定完成时间{$row['end_time']})";
        }else{
            $row['span'] = '';
        }


        switch ($params['type']) {
            case 1:
                $u_res = $row['d_res'];
                break;
            case 2:
                $u_res = $row['m_res'];
                break;
            case 3:
                $u_res = $row['s_res'];
                break;
            case 4:
                $u_res = $row['c_res'];
                break;
            default:
                $u_res = $row['d_res'];
                break;
        }
        $row['u_res'] = trim($u_res, '"');
        $u_res_conf = config('other.res_type');
        $row['u_res_str'] = $u_res_conf[$row['u_res']];
//print_r($row);
        $report = ProjectReport::getAll(5);

        if ($report) {
            foreach ($report as $k => $v) {
                if (!empty($v['attachment'])){
                    $attachment = explode(',',$v['attachment']);
                    $report[$k]['attachment'] = array_filter($attachment);
                }
                if ($v['create_time'] > $row['end_time']){
                    $report[$k]['span'] = "(限定完成时间{$row['end_time']})";
                }else{
                    $report[$k]['span'] = '';
                }
                $report_user = AdminUser::getUserById($v['user_id'])['realname'];
                $report[$k]['real_name'] = !empty($report_user) ? $report_user : '';
                $report[$k]['check_catname'] = ItemModel::getCat()[$v['check_cat']];
                if (empty($row['child'])){
                    $report[$k]['reply'] = ReportReply::getAll($v['id'], 5);
                }else{
                    $reply = ReportCheck::getAll($v['id'], 1);
                    if ($reply){
                        foreach ($reply as $key=>$val){
                            $content = json_decode($val['content'], true);
                            if ($content){
                                foreach ($content as $kk=>$vv){
                                    $content[$kk]['flag'] = $vv['flag'] ? '有' : '无';
                                    $content[$kk]['person_user'] = $this->deal_data(json_encode(user_array($vv['person_user'])));
                                    if (!isset($vv['isfinish'])){
                                        $content[$kk]['isfinish'] = 0;
                                    }
                                    if (!isset($vv['remark'])){
                                        $content[$kk]['remark'] = '';
                                    }
                                }
                            }
                            $reply[$key]['content'] = $content;
                            $reply[$key]['user_name'] = AdminUser::getUserById($val['user_id'])['realname'];
                        }
                    }
                    $report[$k]['reply'] = $reply;
                }

            }
        }
//        print_r($report);
        if ($params['pid']) {
            $map = [];
            $map['cid'] = $cid;
            $map['id'] = $params['pid'];
            $res = ProjectModel::where($map)->find()->toArray();
            $this->assign('pname', $res['name']);
        } else {
            $this->assign('pname', '顶级项目');
        }

        $this->assign('data_info', $row);
        $this->assign('grade_type', ProjectModel::getGrade($row['grade']));
        $this->assign('type', $params['type']);
        $this->assign('cat_option',ItemModel::getOption());
        $this->assign('report_info', $report);
        return $this->fetch();
    }

    public function receipt(){
        $params = $this->request->param();
        $row = ReportCheckModel::getRowById($params['id']);
        $q = [];
        if ($row){
            $content = json_decode($row['content'], true);
            $q = $content[$params['q_id']];
        }
        $tmp = [
            'isfinish' => 0,
            'remark' => '',
        ];
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ($content){
                foreach ($content as $k=>$v){
                    if ($k == $params['q_id']){
                        $tmp = [
                            'isfinish' => $data['isfinish'],
                            'remark' => $data['remark'],
                        ];
                        $content[$k] = array_merge($v,$tmp);

                    }else{
                        $content[$k] = array_merge($v,$tmp);
                    }
                }
            }
            $d = [
                'id'=>$data['id'],
                'content'=>json_encode($content),
            ];

            $person_user = explode(',',trim($q['person_user'],','));
            $score = [];
            $p = ProjectModel::where('id',$row['project_id'])->find();
            foreach ($person_user as $k=>$v){
                $score[$k]['subject_id'] = $p['subject_id'];
                $score[$k]['project_id'] = $row['project_id'];
                $score[$k]['cid'] = session('admin_user.cid');
                $score[$k]['project_code'] = $p['code'];
                $score[$k]['user'] = $v;
                if ($q['ml'] > 0){
                    $score[$k]['ml_add_score'] = $q['ml'];
                }else{
                    $score[$k]['ml_sub_score'] = abs($q['ml']);
                }
                if ($q['gl'] > 0){
                    $score[$k]['gl_add_score'] = $q['gl'];
                }else{
                    $score[$k]['gl_sub_score'] = abs($q['gl']);
                }
                $score[$k]['remark'] = "任务主题编号[{$row['project_id']}],阶段审核编号[{$row['report_id']}],问题编号[{$q['check_id']}],出现问题[{$q['check_name']}]";
                $score[$k]['user_id'] = session('admin_user.uid');
                $score[$k]['create_time'] = time();
                $score[$k]['update_time'] = time();

            }
            //事务开始
            Db::startTrans();
            try{
                ReportCheckModel::update($d);
                $res = db('score')->insertAll($score);
                //提交事务
                Db::commit();
            }catch (\Exception $e){
                //回滚事务
                Db::rollback();
            }
            if ($res){
                return $this->success("操作成功{$this->score_value}");
            }else{
                return $this->error('添加失败');
            }
        }
//        print_r($q);
        $this->assign('q', $q);
        return $this->fetch();
    }

    public function getConfirm(){
        $params = $this->request->param();
        $row = ReportCheckModel::getRowById($params['id']);
        $q = [];
        if ($row){
            $content = json_decode($row['content'], true);
        }
        $tmp = [
            'confirm' => 0,
        ];
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ($content){
                foreach ($content as $k=>$v){
                    if ($k == $params['q_id']){
                        $tmp = [
                            'isfinish' => $data['isfinish'],
                            'remark' => $data['remark'],
                        ];
                        $content[$k] = array_merge($v,$tmp);

                    }else{
                        $content[$k] = array_merge($v,$tmp);
                    }
                }
            }
            $d = [
                'id'=>$data['id'],
                'content'=>json_encode($content),
            ];

            if (!ReportCheckModel::update($d)) {
                return $this->error('修改失败！');
            }
            return $this->success('修改成功。', url('index'));
        }

        $this->assign('q', $q);
        return $this->fetch();
    }

    public function addScore(){
        $params = $this->request->param();
        $uid = session('admin_user.uid');
        $map = [
            'id'=>$params['id'],
        ];
        $row = ProjectModel::where($map)->find()->toArray();
        $manager = json_decode($row['manager_user'],true);
        $where = [
            'project_id' => $params['id'],
            'user_id' =>$uid,
        ];
        $count = ScorelogModel::where($where)->count('DISTINCT user_id');
//        print_r($params);
//        print_r($manager);
//        print_r($count);exit();
        $cc = ScorelogModel::where('project_id',$params['id'])->count('DISTINCT user_id');
        $p = [
            count($manager),$cc+1
        ];

//        print_r(ScorelogModel::where('project_id',$params['id'])->count());

//        $s_d = ScorelogModel::where('project_id',$params['id'])->select();
//        $s = [];
//        if ($s_d){
//            foreach ($s_d as $k=>$v){
//                $summary = json_decode($v['summary'],true);
//                foreach ($summary as $key=>$val){
//                    if (key_exists($key,$s)){
//                        $s[$key] += $val['score'];
//                    }else{
//                        $s[$key] = $val['score'];
//                    }
//                }
//            }
//        }
//        print_r($s);
//        exit();
        if ($this->request->isPost()){
            $data = $this->request->post();
            $score = [];
            $sum_add_score = 0;
            $sub_sub_score = 0;
            foreach ($data['u_id'] as $k=>$v){
                $score[$k]['subject_id'] = $row['subject_id'];
                $score[$k]['project_id'] = $data['id'];
                $score[$k]['cid'] = session('admin_user.cid');
                $score[$k]['project_code'] = $data['code'];
                $score[$k]['user'] = $data['u_id'][$k];
                $score[$k]['ml_add_score'] = !empty($data['add_score'][$k]) ? $data['add_score'][$k] : 0;
//                $score[$k]['ml_sub_score'] = !empty($data['sub_score'][$k]) ? $data['sub_score'][$k] : 0;
                $score[$k]['remark'] = !empty($data['mark'][$k]) ? $data['mark'][$k] : '';
                $score[$k]['user_id'] = $uid;
                $score[$k]['create_time'] = time();
                $score[$k]['update_time'] = time();

                $sum_add_score += $score[$k]['ml_add_score'];
//                $sub_sub_score += $score[$k]['ml_sub_score'];
            }
            if ($sum_add_score > $data['pscore']){
                return $this->error('得分合计不能超过任务总分！');
            }
//            if ($sub_sub_score > $data['pscore']){
//                return $this->error('扣分合计不能超过任务总分！');
//            }
            $score_log = [
                'project_id' => $params['id'],
                'user' => json_encode($data['u_id']),
                'score' => json_encode($data['add_score']),
                'mark' => json_encode($data['mark']),
                'total_score' => array_sum($data['add_score']),
                'user_id' => session('admin_user.uid'),
            ];
            foreach ($data['u_id'] as $k=>$v){
                $t[$v]['score'] = $data['add_score'][$k];
                $t[$v]['mark'] = $data['mark'][$k];
            }
            $score_log['summary'] = json_encode($t);

            if (count($manager) < 2){
                //只有一个负责人情况
                //事务开始
                Db::startTrans();
                try{
                    db('score')->insertAll($score);
                    ScorelogModel::create($score_log);
                    $tmp = [
                        'real_score'=>$sum_add_score,
                    ];
                    $res = ProjectModel::where('id',$data['id'])->update($tmp);
                    //提交事务
                    Db::commit();
                }catch (\Exception $e){
                    //回滚事务
                    Db::rollback();
                }
            }else{
                //有多个负责人情况
                if(empty($count)){
                    $res = ScorelogModel::create($score_log);
                } else {
                    return $this->error('之前已经操作成功，等待系统计算');
                }
                $c = ScorelogModel::where('project_id',$params['id'])->count('DISTINCT user_id');
                if ($c == count($manager)){
                    $s_d = ScorelogModel::where('project_id',$params['id'])->select();
                    $s = [];
                    if ($s_d){
                        foreach ($s_d as $k=>$v){
                            $summary = json_decode($v['summary'],true);
                            foreach ($summary as $key=>$val){
                                if (key_exists($key,$s)){
                                    $s[$key] = $val['score'];//暂时以最后一次的评定为主
                                }else{
                                    $s[$key] = $val['score'];
                                }
                            }
                        }
                    }
                    $score = [];
                    $sum_add_score = 0;
                    foreach ($s as $k=>$v){
                        $score[$k]['subject_id'] = $row['subject_id'];
                        $score[$k]['project_id'] = $data['id'];
                        $score[$k]['cid'] = session('admin_user.cid');
                        $score[$k]['project_code'] = $data['code'];
                        $score[$k]['user'] = $k;
                        $score[$k]['ml_add_score'] = $v;
                        $score[$k]['remark'] = '最终核定计算产值';
                        $score[$k]['user_id'] = $uid;
                        $score[$k]['create_time'] = time();
                        $score[$k]['update_time'] = time();

                        $sum_add_score += $v;
                    }

                    //事务开始
                    Db::startTrans();
                    try{
                        db('score')->insertAll($score);
                        $tmp = [
                            'real_score'=>$sum_add_score,
                        ];
                        $res = ProjectModel::where('id',$data['id'])->update($tmp);
                        //提交事务
                        Db::commit();
                    }catch (\Exception $e){
                        //回滚事务
                        Db::rollback();
                    }
                }
            }

            if ($res){
                return $this->success("操作成功{$this->score_value}");
            }else{
                return $this->error('添加失败');
            }
        }

        if ($row){
            $x_user_arr = json_decode($row['deal_user'],true);
            $x_user = [];
            if ($x_user_arr){
                foreach ($x_user_arr as $key=>$val){
                    $real_name = AdminUser::getUserById($key)['realname'];
                    $x_user[$key] = $real_name;
                }
            }
        }
        $this->assign('p', $p);
        $this->assign('x_user', $x_user);
        $this->assign('data_list', $row);
        return $this->fetch();
    }

    public function setConfirm()
    {
        $params = $this->request->param();
        $uid = session('admin_user.uid');
        switch ($params['type']) {
            case 1:
                $sql = "UPDATE tb_project SET deal_user = JSON_SET(deal_user, '$.\"{$uid}\"', 'a') WHERE id ={$params['id']}";
//                $data['deal_user'] = "JSON_SET(deal_user, '$.\"$uid\"', 'a')";
                break;
            case 2:
                $sql = "UPDATE tb_project SET manager_user = JSON_SET(manager_user, '$.\"{$uid}\"', 'a') WHERE id ={$params['id']}";
//                $data['manager_user'] = "JSON_SET(manager_user, '$.\"$uid\"', 'a')";
                break;
            case 3:
                $sql = "UPDATE tb_project SET send_user = JSON_SET(send_user, '$.\"{$uid}\"', 'a') WHERE id ={$params['id']}";
//                $data['send_user'] = "JSON_SET(send_user, '$.\"$uid\"', 'a')";
                break;
            case 4:
                $sql = "UPDATE tb_project SET copy_user = JSON_SET(copy_user, '$.\"{$uid}\"', 'a') WHERE id ={$params['id']}";
//                $data['copy_user'] = "JSON_SET(copy_user, '$.\"$uid\"', 'a')";
                break;
            default:
                $sql = "UPDATE tb_project SET deal_user = JSON_SET(deal_user, '$.\"{$uid}\"', 'a') WHERE id ={$params['id']}";
//                $data['deal_user'] = "JSON_SET(deal_user, '$.\"$uid\"', 'a')";
                break;
        }

        if (!ProjectModel::execute($sql)) {
            return $this->error('修改失败！');
        }
        return $this->success('修改成功。', url('index'));
    }

    public function setStatus()
    {
        $params = $this->request->param();
        $data = [
            'id' => $params['id'],
            'status' => 1,
        ];
        $row = ProjectModel::getRowById($data['id']);
        if ($row['realper'] < 100) {
            return $this->error('此任务进度为：' . $row['realper']);
        }
        if (!ProjectModel::update($data)) {
            return $this->error('修改失败！');
        }
        return $this->success('修改成功。', url('index'));
    }

    public function checkResult()
    {
        $params = $this->request->param();
        $where['cid'] = session('admin_user.cid');

        if ($this->request->isPost()) {
            $data = $this->request->post();
            $tmp = [];
            if ($data){
                foreach ($data['flag'] as $k=>$v){
                    if ($v == 1){
                        if (empty($data['person_user'][$k])){
                            return $this->error('有问题项，责任人必选！');
                        }
                        if (empty($data['ml'][$k])){
                            $data['ml'][$k] = $data['check_ml'][$k];
                        }elseif ($data['ml'][$k] > 0){
                            $data['ml'][$k] = '-'.$data['ml'][$k];
                        }
                    }else{
                        if (empty($data['ml'][$k]) || $data['ml'][$k] < 0){
                            $data['ml'][$k] = 0;
                        }else{
                            $data['ml'][$k] = (int)$data['ml'][$k];
                        }
                    }
                    $tmp[$k] = [
                        'check_id' => $data['check_id'][$k],
                        'check_name' => $data['check_name'][$k],
                        'check_ml' => $data['check_ml'][$k],
                        'flag' => $data['flag'][$k],
                        'person_user' => $data['person_user'][$k],
                        'ml' => $data['ml'][$k],
                        'gl' => $data['gl'][$k],
                        'mark' => $data['mark'][$k],
                    ];

                }
                $ins_data = [
                    'cid' => $where['cid'],
                    'report_id' => $data['report_id'],
                    'project_id' => $data['project_id'],
                    'content' => json_encode($tmp),
                    'user_id' => session('admin_user.uid'),
                ];

            if (!ReportCheckModel::create($ins_data)) {
                return $this->error('添加失败！');
            }
            return $this->success('添加成功。', url('index'));
            }
        }
        $where['status'] = 1;
        $where['cat_id'] = $params['check_cat'];
        $res = ItemModel::with('cat')->where($where)->select();
        $list = [];
        if ($res) {
            foreach ($res as $k => $v) {
                $list[$v['cat_id']]['data'][] = $v;
            }
        }

        $map = [
            'report_id' => $params['report_id']
        ];
        $check_log = ReportCheckModel::where($map)->order('id desc')->select();
        if ($check_log) {
            foreach ($check_log as $k => $v) {
                $content = json_decode($v['content'], true);
                if ($content){
                    foreach ($content as $key=>$val){
                        $content[$key]['flag_name'] = $val['flag'] ? '有' : '无';
                        $content[$key]['person_select_id'] = $this->deal_data(json_encode(user_array($val['person_user'])));
                    }
                }
                $check_log[$k]['content'] = $content;
                $check_log[$k]['user_name'] = AdminUser::getUserById($v['user_id'])['realname'];
            }
        }
//        print_r($check_log);
        if (!$check_log) {
            $check_log = [];
        }
//        print_r($list);
//        print_r($check_log);
        $this->assign('data_list', $list);
        $this->assign('check_log', $check_log);
        $this->assign('cat_option', ItemModel::getCat());
        $this->assign('item_option', ItemModel::getItem());
        return $this->fetch();
    }

    public function doimport()
    {
        if ($this->request->isAjax()) {
            $file = request()->file('file');
            $params = $this->request->post();
            // 上传附件路径
            $_upload_path = ROOT_PATH . 'public/upload' . DS . 'excel' . DS . date('Ymd') . DS;
            // 附件访问路径
            $_file_path = ROOT_DIR . 'upload/excel/' . date('Ymd') . '/';

            // 移动到upload 目录下
            $upfile = $file->rule('md5')->move($_upload_path);//以md5方式命名
            if (!is_file($_upload_path . $upfile->getSaveName())) {
                return self::result('文件上传失败！');
            }
            $file_name = $_upload_path . $upfile->getSaveName();
            set_time_limit(0);
            $excel = \service('Excel');
            $format = array('A' => 'line', 'B' => 'pid', 'C' => 'name', 'D' => 'remark', 'E' => 'score', 'F' => 'start_time', 'G' => 'end_time', 'H' => 'manager_user', 'I' => 'deal_user', 'J' => 'send_user', 'K' => 'copy_user');
            $checkformat = array('A' => '序号', 'B' => '层级关系', 'C' => '名称', 'D' => '描述', 'E' => '预设值', 'F' => '开始时间', 'G' => '结束时间', 'H' => '负责人', 'I' => '参与人', 'J' => '审批人', 'K' => '抄送人');
            $res = $excel->readUploadFile($file_name, $format, 8050, $checkformat);
            if ($res['status'] == 0) {
                $this->error($res['data']);
            } else {
                if ('1.1' != $res['data'][0]['B']){
                    $this->error('层级关系列从1.1编号开始');
                }
                foreach ($res['data'] as $k => $v) {
                    $res['data'][$k]['B'] = session('admin_user.cid') . '.' . $params['id'] . substr($v['B'], 1);
                }
                $old_pid = array_column($res['data'], 'B');
                $new_pid = array_unique($old_pid);
                if (count($old_pid) != count($new_pid)) {
                    $this->error('层级关系有重复的');
                }
                $old_name = array_column($res['data'], 'C');
                $new_name = array_unique($old_name);
                if (count($old_name) != count($new_name)) {
                    $diff_row = array_diff_assoc($old_name, $new_name);
                    return $this->error("以下名称有重复的，请修改之后导入：<br>" . implode("<br>", $diff_row));
                }

                $d = 25569;
                $s = 24 * 60 * 60;

                $i = 0;
                foreach ($res['data'] as $k => $v) {
                    $p_node = substr($v['B'], 0, strripos($v['B'], '.'));
                    if ($p_node == session('admin_user.cid')){
                        $p_node = $params['id'];
                    }
                    $prow = ProjectModel::where('node', $p_node)->limit(1)->select();
                    $where = [
                        'cid' => session('admin_user.cid'),
                        'node' => $v['B'],
                    ];
                    $f = ProjectModel::where($where)->find();

                    $ff = trim($v['F']);
                    $ff = empty($ff) ? 0 : (int)$ff;
                    $gg = trim($v['G']);
                    $gg = empty($gg) ? 0 : (int)$gg;
                    if (!$f) {
                        $tmp = [
                            'pid' => $prow[0]['id'],
                            'cid' => session('admin_user.cid'),
                            'subject_id' => $params['id'],
                            'code' => $prow[0]['code'] . $prow[0]['id'] . 'p',
                            'name' => $v['C'],
                            'node' => $v['B'],
                            'remark' => $v['D'],
                            'cat_id' => $prow[0]['cat_id'],
                            'score' => (int)$v['E'],
                            'start_time' => $ff > $d ? gmdate('Y-m-d H:i:s', ($ff - $d) * $s) : '0000-00-00 00:00:00',
                            'end_time' => $gg > $d ? gmdate('Y-m-d H:i:s', ($gg - $d) * $s) : '0000-00-00 00:00:00',
                            'manager_user' => $this->userFormat($v['H']),
                            'deal_user' => $this->userFormat($v['I']),
                            'send_user' => $this->userFormat($v['J']),
                            'copy_user' => $this->userFormat($v['K']),
                            'user_id' => session('admin_user.uid'),
                        ];
                        $f1 = ProjectModel::create($tmp);
                    } else {
                        $tmp = [
                            'id' => $f['id'],
                            'pid' => $prow[0]['id'],
                            'cid' => session('admin_user.cid'),
                            'subject_id' => $params['id'],
                            'code' => $prow[0]['code'] . $prow[0]['id'] . 'p',
                            'name' => $v['C'],
                            'cat_id' => $prow[0]['cat_id'],
                            'node' => $v['B'],
                            'remark' => $v['D'],
                            'score' => (int)$v['E'],
                            'start_time' => $ff > $d ? gmdate('Y-m-d H:i:s', ($ff - $d) * $s) : '0000-00-00 00:00:00',
                            'end_time' => $gg > $d ? gmdate('Y-m-d H:i:s', ($gg - $d) * $s) : '0000-00-00 00:00:00',
                            'manager_user' => $this->userFormat($v['H']),
                            'deal_user' => $this->userFormat($v['I']),
                            'send_user' => $this->userFormat($v['J'],'a'),
                            'copy_user' => $this->userFormat($v['K']),
                            'user_id' => session('admin_user.uid'),
                        ];
                        $f1 = ProjectModel::update($tmp);
                    }
                    if ($f1){
                        $i++;
                    }
                }
                if ($i){
                    //计算得分
                    $sc = [
                        'project_id'=>0,
                        'cid'=>session('admin_user.cid'),
                        'user'=>session('admin_user.uid'),
                        'ml_add_score'=>0,
                        'ml_sub_score'=>0,
                        'gl_add_score'=>$i,
                        'gl_sub_score'=>0,
                        'remark' => 'ML工作，项目计划导入Excel得分'
                    ];
                    if (ScoreModel::addScore($sc)){
                        return $this->success("添加成功，奖励{$sc['gl_add_score']}GL分。",'index');
                    }
                }else{
                    return $this->error("导入失败");
                }
            }
        }

        return $this->fetch();
    }

    public function userFormat($val,$f='')
    {
        $name = preg_replace("/(\n)|(\s)|(\t)|(\')|(')|(，)|(\.)/", ',', $val);
        $where = [
            'company_id' => session('admin_user.cid'),
            'status' => 1,
            'realname' => ['in', $name],
        ];
        $ids = AdminUser::where($where)->field('id')->select();
        if (!empty($ids)) {
            foreach ($ids as $k => $v) {
                $data[$v['id']] = $f;
            }
            return json_encode($data);
        }
        return json_encode('');
    }
}