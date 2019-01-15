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
use app\admin\controller\ProjectReport;
use think\Db;

class Project extends Admin
{
    public $tab_data = [];

    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '市政',
                'url' => 'admin/Project/index',
                'params' => ['atype' => 1],
            ],
            [
                'title' => '住宅',
                'url' => 'admin/Project/index',
                'params' => ['atype' => 2],
            ],
            [
                'title' => '旅游',
                'url' => 'admin/Project/index',
                'params' => ['atype' => 3],
            ],
            [
                'title' => '其他',
                'url' => 'admin/Project/index',
                'params' => ['atype' => 5],
            ],
            [
                'title' => '全部',
                'url' => 'admin/Project/index',
                'params' => ['atype' => 4],
            ],
        ];
        $tab_data['current'] = url('index', ['atype' => 1]);
        $this->tab_data = $tab_data;
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
            case 1:
                $map['p_type'] = 1;
                break;
            case 2:
                $map['p_type'] = 2;
                break;
            case 3:
                $map['p_type'] = 3;
                break;
            case 4:
                break;
            case 5:
                $map['p_type'] = 5;
                break;
            default:
                $map['p_type'] = 1;
                break;
        }

        if ($params) {
            if (isset($params['name']) && !empty($params['name'])) {
                $map['name'] = ['like', '%' . $params['name'] . '%'];
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
        $list = ProjectModel::index($map);
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
            $sub_total_score = ProjectModel::where('pid', $params['id'])->column('sum(score)');
            $p_res['max_score'] = $p_res['score'] - $sub_total_score[0];
            $this->assign('p_res', $p_res);
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'Project');
            if ($result !== true) {
                return $this->error($result);
            }
            if (isset($data['max_score']) && $data['score'] > $data['max_score']) {
                return $this->error('预设分超过最大分值！');
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
//            print_r($data);exit();
            if (!ProjectModel::create($data)) {
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
        $this->assign('p_type', ProjectModel::getPtype());
        $this->assign('p_source', ProjectModel::getPsource());
        return $this->fetch('form');
    }

    public function add1()
    {
        $params = $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'Project');
            if ($result !== true) {
                return $this->error($result);
            }
            if (isset($data['max_score']) && $data['score'] > $data['max_score']) {
                return $this->error('预设分超过最大分值！');
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
            $this->assign('parent_type', $p_res[0]['p_type']);
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
        $this->assign('p_type', ProjectModel::getPtype());
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
            // 验证
            $result = $this->validate($data, 'Project');
            if ($result !== true) {
                return $this->error($result);
            }
            if (isset($data['max_score']) && $data['score'] > $data['max_score']) {
                return $this->error('预设分超过最大分值！');
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
            $data['send_user'] = json_encode(user_array($data['send_user']));
            $data['copy_user'] = json_encode(user_array($data['copy_user']));
            if (!ProjectModel::update($data)) {
                return $this->error('修改失败！');
            }
            return $this->success("修改成功{$this->score_value}", url('index'));
        }

//        $row['time_long'] = floor((strtotime($row['end_time'])-strtotime($row['start_time']))/86400);
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
        $this->assign('p_type', ProjectModel::getPtype($row['p_type']));
        $this->assign('p_source', ProjectModel::getPsource($row['p_source']));
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
        if ($params) {
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
        foreach ($list as $k => $v) {
            $list[$k]['manager_user'] = $this->deal_data($v['manager_user']);
            $list[$k]['deal_user'] = $this->deal_data($v['deal_user']);
            $list[$k]['copy_user'] = $this->deal_data($v['copy_user']);
            $list[$k]['send_user'] = $this->deal_data($v['send_user']);
            $list[$k]['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];
            $list[$k]['grade'] = $grade_type[$v['grade']];
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
//        print_r($list);

        $this->assign('tab_data', $tab_data);
        $this->assign('tab_type', 1);
        $this->assign('isparams', 1);
        $this->assign('type', $params['type']);
        $pages = $list->render();
        $this->assign('tab_url', url('mytask', ['type' => $params['type']]));
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
                $report[$k]['reply'] = ReportReply::getAll($v['id'], 5);
            }
        }
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
        $this->assign('report_info', $report);
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
        $where['cid'] = session('admin_user.cid');
        $where['status'] = 1;
        if ($this->request->isPost()) {
            $params = $this->request->post();
            $ins_data = [
                'project_id' => $params['id'],
                'score' => json_encode($params['score']),
                'mark' => json_encode($params['mark']),
                'total_score' => array_sum($params['score']),
                'user_id' => session('admin_user.uid'),
            ];
            if (!ScorelogModel::create($ins_data)) {
                return $this->error('添加失败！');
            }
            return $this->success('添加成功。', url('index'));
        }

        $res = ItemModel::with('cat')->where($where)->select();
        $data = [];
        if ($res) {
            foreach ($res as $k => $v) {
                $data[$v['cat_id']]['data'][] = $v;
            }
        }

        $params = $this->request->param();
        $map = [
            'project_id' => $params['id']
        ];
        $score_log = ScorelogModel::where($map)->order('id desc')->select();
        if ($score_log) {
            foreach ($score_log as $k => $v) {
                $score_log[$k]['score'] = json_decode($v['score'], true);
                $score_log[$k]['mark'] = json_decode($v['mark'], true);
            }
        }
//        print_r($score_log);
        if (!$score_log) {
            $score_log = [];
        }
        $this->assign('data_list', $data);
        $this->assign('score_log', $score_log);
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
            $checkformat = array('A' => '序号', 'B' => '层级关系', 'C' => '名称', 'D' => '描述', 'E' => '预设分', 'F' => '开始时间', 'G' => '结束时间', 'H' => '负责人', 'I' => '参与人', 'J' => '审批人', 'K' => '抄送人');
            $res = $excel->readUploadFile($file_name, $format, 8050, $checkformat);
            if ($res['status'] == 0) {
                $this->error($res['data']);
            } else {
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

                foreach ($res['data'] as $k => $v) {
                    $p_node = substr($v['B'], 0, strripos($v['B'], '.'));
                    $prow = ProjectModel::where('node', $p_node)->limit(1)->select();
                    $where = [
                        'cid' => session('admin_user.cid'),
                        'node' => $v['B'],
                    ];
                    $f = ProjectModel::where($where)->find();
                    if (!$f) {
                        $tmp = [
                            'pid' => $prow[0]['id'],
                            'cid' => session('admin_user.cid'),
                            'code' => $prow[0]['code'] . $prow[0]['id'] . 'p',
                            'name' => $v['C'],
                            'node' => $v['B'],
                            'remark' => $v['D'],
                            'p_type' => $prow[0]['p_type'],
                            'score' => (int)$v['E'],
                            'start_time' => $v['F'] ? gmdate('Y-m-d H:i:s', ($v['F'] - $d) * $s) : '0000-00-00 00:00:00',
                            'end_time' => $v['F'] ? gmdate('Y-m-d H:i:s', ($v['G'] - $d) * $s) : '0000-00-00 00:00:00',
                            'manager_user' => $this->userFormat($v['H']),
                            'deal_user' => $this->userFormat($v['I']),
                            'send_user' => $this->userFormat($v['J']),
                            'copy_user' => $this->userFormat($v['K']),
                            'user_id' => session('admin_user.uid'),
                        ];
                        ProjectModel::create($tmp);
                    } else {
                        $tmp = [
                            'id' => $f['id'],
                            'pid' => $prow[0]['id'],
                            'cid' => session('admin_user.cid'),
                            'code' => $prow[0]['code'] . $prow[0]['id'] . 'p',
                            'name' => $v['C'],
                            'p_type' => $prow[0]['p_type'],
                            'node' => $v['B'],
                            'remark' => $v['D'],
                            'score' => (int)$v['E'],
                            'start_time' => $v['F'] ? gmdate('Y-m-d H:i:s', ($v['F'] - $d) * $s) : '0000-00-00 00:00:00',
                            'end_time' => $v['F'] ? gmdate('Y-m-d H:i:s', ($v['G'] - $d) * $s) : '0000-00-00 00:00:00',
                            'manager_user' => $this->userFormat($v['H']),
                            'deal_user' => $this->userFormat($v['I']),
                            'send_user' => $this->userFormat($v['J']),
                            'copy_user' => $this->userFormat($v['K']),
                            'user_id' => session('admin_user.uid'),
                        ];
                        ProjectModel::update($tmp);
                    }
                }
            }
            return $this->success('导入成功。', url('index'));
        }

        return $this->fetch();
    }

    public function userFormat($val)
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
                $data[$v['id']] = '';
            }
            return json_encode($data);
        }
        return json_encode('');
    }
}