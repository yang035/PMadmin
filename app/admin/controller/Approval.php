<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 11:48
 */

namespace app\admin\controller;

use app\admin\model\AdminDepartment;
use app\admin\model\Approval as ApprovalModel;
use app\admin\model\ApprovalBills;
use app\admin\model\ApprovalLeave as LeaveModel;
use app\admin\model\ApprovalBackleave as BackleaveModel;
use app\admin\model\ApprovalExpense as ExpenseModel;
use app\admin\model\ApprovalBusiness as BusinessModel;
use app\admin\model\ApprovalProcurement as ProcurementModel;
use app\admin\model\ApprovalOvertime as OvertimeModel;
use app\admin\model\ApprovalGoout as GooutModel;
use app\admin\model\ApprovalSenduser;
use app\admin\model\ApprovalUsecar as CarModel;
use app\admin\model\ApprovalCost as CostModel;
use app\admin\model\ApprovalDispatch as DispatchModel;
use app\admin\model\ApprovalBorrow;
use app\admin\model\AdminUser;
use app\admin\model\AssetItem as ItemModel;
use app\admin\model\ApprovalGoods;
use app\admin\model\ApprovalPrint;
use app\admin\model\Project as ProjectModel;
use app\admin\model\ApprovalReport as ApprovalReportModel;
use think\Db;


class Approval extends Admin
{
    public $tab_data = [];

    protected function _initialize()
    {
        parent::_initialize();
        $sta_count = $this->getApprovalCount();
        $tab_data['menu'] = [
            [
                'title' => "发起申请",
                'url' => 'admin/approval/index',
                'params' => ['atype' => 1],
            ],
            [
                'title' => "我的申请<span class='layui-badge layui-bg-orange'>{$sta_count['user_num']}</span>",
                'url' => 'admin/approval/index',
                'params' => ['atype' => 2],
            ],
            [
                'title' => "待我审批<span class='layui-badge'>{$sta_count['send_num']}</span>",
                'url' => 'admin/approval/index',
                'params' => ['atype' => 3],
            ],
            [
                'title' => "抄送我的<span class='layui-badge layui-bg-orange'>{$sta_count['copy_num']}</span>",
                'url' => 'admin/approval/index',
                'params' => ['atype' => 4],
            ],
            [
                'title' => "我参与的<span class='layui-badge layui-bg-orange'>{$sta_count['deal_num']}</span>",
                'url' => 'admin/approval/index',
                'params' => ['atype' => 5],
            ],
            [
                'title' => "已审批<span class='layui-badge layui-bg-orange'>{$sta_count['has_num']}</span>",
                'url' => 'admin/approval/index',
                'params' => ['atype' => 6],
            ],
            [
                'title' => "同行<span class='layui-badge layui-bg-orange'>{$sta_count['follow_num']}</span>",
                'url' => 'admin/approval/index',
                'params' => ['atype' => 7],
            ],
        ];
        $tab_data['current'] = url('index', ['atype' => 1]);
        $this->tab_data = $tab_data;

        $cid = session('admin_user.cid');
        $redis = service('Redis');
        $default_user = $redis->get("pm:user:{$cid}");
        if ($default_user) {
            $user = json_decode($default_user);
            $this->assign('data_info', (array)$user);
        }
        $this->assign('project_select', ProjectModel::inputSearchProject());
    }

    public function checkName($name){
        $where = [
            'company_id'=>session('admin_user.cid'),
            'realname'=>$name,
        ];
        $name = AdminUser::where($where)->select();
        $uid = '';
        if (count($name) == 1){
            $uid = $name[0]['id'];
        }
        return $uid;
    }

    public function getApprovalCount()
    {
        $map['cid'] = session('admin_user.cid');
        $uid = session('admin_user.uid');
        $fields = "SUM(IF(user_id='{$uid}',1,0)) user_num,
        SUM(IF(JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"') and status=1 and class_type <> 11,1,0)) send_num,
        SUM(IF(JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"') and status <> 3,1,0)) copy_num,
        SUM(IF(JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"') and status <> 3,1,0)) deal_num,
        SUM(IF(JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"') and status not in (1,3),1,0)) has_num,
        SUM(IF(JSON_CONTAINS_PATH(fellow_user,'one', '$.\"$uid\"') and status <> 3,1,0)) follow_num";
        $count = ApprovalModel::field($fields)->where($map)->find()->toArray();
        return $count;
    }

    public function index()
    {
        $params = $this->request->param();
        $map = [];
        $d = '';
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        $panel_type1 = $panel_type = config('other.panel_type');
        unset($panel_type1[2]);
        $approval_status = config('other.approval_status');
        $params['atype'] = isset($params['atype']) ? $params['atype'] : 1;
        if (1 == $params['atype']) {
            $this->assign('tab_data', $this->tab_data);
            $this->assign('tab_type', 1);
            $this->assign('isparams', 1);
            $this->assign('atype', $params['atype']);
            $this->assign('tab_url', url('index', ['atype' => $params['atype']]));
            $this->assign('panel_type', $panel_type1);
            return $this->fetch('panel');
        }
        if ($params) {
            if (!empty($params['class_type'])) {
                $map['class_type'] = $params['class_type'];
            }
            if (isset($params['search_date']) && !empty($params['search_date'])){
                $d = urldecode($params['search_date']);
                $d_arr = explode(' - ',$d);
                $d0 = $d_arr[0].' 00:00:00';
                $d1 = $d_arr[1].' 23:59:59';
                $map['start_time'] = ['egt',"$d0"];
                $map['end_time'] = ['elt',"$d1"];
            }
            if (!empty($params['person_user'])) {
                $person_user = trim($params['person_user'],',');
                $map['user_id'] = ['in',"{$person_user}"];
            }
        }
        $uid = session('admin_user.uid');
        $con = '';
        switch ($params['atype']) {
            case 2:
                $map['user_id'] = session('admin_user.uid');
                break;
            case 3:
                $con = "JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"') and class_type <> 11";
                $map['status'] = 1;
                break;
            case 4:
                if (31 != $uid){
                    $con = "JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"')";
                }
                $map['status'] = ['neq',3];
                break;
            case 5:
                $con = "JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"')";
                $map['status'] = ['neq',3];
                break;
            case 6:
                $con = "JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"')";
                $map['status'] = ['not in', '1,3'];
                break;
            case 7:
                $con = "JSON_CONTAINS_PATH(fellow_user,'one', '$.\"$uid\"')";
                $map['status'] = ['neq',3];
                break;
            default:
                $con = "";
                break;
        }
        $leave_type = config('other.leave_type');

        if (isset($params['export']) && 1 == $params['export']){
            set_time_limit(0);
            $list = ApprovalModel::where($map)->where($con)->order('create_time desc')->select();
//        print_r($list);exit();
            foreach ($list as $k => $v) {
                $list[$k]['send_user'] = strip_tags($this->deal_data($v['send_user']));
                $list[$k]['fellow_user'] = strip_tags($this->deal_data($v['fellow_user']));
                $list[$k]['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];

                $list[$k]['money'] = '#';
                $list[$k]['leave_type'] = '#';
                switch ($v['class_type']){
                    case 1://报销
                        $child = LeaveModel::where('aid',$v['id'])->find();
                        if ($child){
                            $list[$k]['leave_type'] = $leave_type[$child['type']];
                        }
                        break;
                    case 2://报销
                        $child = ExpenseModel::where('aid',$v['id'])->find();
                        if ($child){
                            $list[$k]['money'] = $child['total'];
                        }
                        break;
                    case 3://费用
                        $child = CostModel::where('aid',$v['id'])->find();
                        if ($child){
                            $list[$k]['money'] = $child['money'];
                        }
                        break;
                }
//                if ($v['create_time'] == $v['update_time']){
//                    $list[$k]['update_time'] = 0;
//                }else{
//                    $v['update_time'] = $v['update_time'];
//                }

                if ($v['project_id']){
                    $project_data = ProjectModel::getRowById($v['project_id']);
                }else{
                    $project_data = [
                        'name'=>'其他',
                    ];
                }
                $list[$k]['project_name'] = $project_data['name'];
                if (1 == $v['is_deal']){
                    $list[$k]['deal_mark'] = '未支付';
                }elseif (2 == $v['is_deal']){
                    $list[$k]['deal_mark'] = '支付-'.$v['deal_mark'].'-'.$v['deal_time'];
                }
            }
            vendor('PHPExcel.PHPExcel');
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', '姓名')
                ->setCellValue('B1', '类型')
                ->setCellValue('C1', '归属于')
                ->setCellValue('D1', '开始时间')
                ->setCellValue('E1', '结束时间')
                ->setCellValue('F1', '项目名称')
                ->setCellValue('G1', '金额(元)')
                ->setCellValue('H1', '审批人')
                ->setCellValue('I1', '添加时间')
                ->setCellValue('J1', '状态')
                ->setCellValue('K1', '审批意见')
                ->setCellValue('L1', '审批时间')
                ->setCellValue('M1', '支付结果')
                ->setCellValue('N1', '同行人');
//            print_r($data_list);exit();
            foreach ($list as $k => $v) {
                $num = $k + 2;
                $objPHPExcel->setActiveSheetIndex(0)
                    //Excel的第A列，uid是你查出数组的键值，下面以此类推
                    ->setCellValue('A' . $num, $v['user_id'])
                    ->setCellValue('B' . $num, $panel_type[$v['class_type']]['title'])
                    ->setCellValue('C' . $num, $v['leave_type'])
                    ->setCellValue('D' . $num, $v['start_time'])
                    ->setCellValue('E' . $num, $v['end_time'])
                    ->setCellValue('F' . $num, $v['project_name'])
                    ->setCellValue('G' . $num, $v['money'])
                    ->setCellValue('H' . $num, $v['send_user'])
                    ->setCellValue('I' . $num, $v['create_time'])
                    ->setCellValue('J' . $num, $approval_status[$v['status']])
                    ->setCellValue('K' . $num, $v['mark'])
                    ->setCellValue('L' . $num, $v['update_time'])
                    ->setCellValue('M' . $num, $v['deal_mark'])
                    ->setCellValue('N' . $num, $v['fellow_user']);
            }
            $d = !empty($d) ? $d : '全部';
            $name = $d.'日常审批统计';
            $objPHPExcel->getActiveSheet()->setTitle($d);
            $objPHPExcel->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $name . '.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }

        $list = ApprovalModel::where($map)->where($con)->order('create_time desc')->paginate(30, false, ['query' => input('get.')]);

        foreach ($list as $k => $v) {
            $list[$k]['send_user'] = $this->deal_data($v['send_user']);
            $list[$k]['fellow_user'] = strip_tags($this->deal_data($v['fellow_user']));
            $list[$k]['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];
            $list[$k]['money'] = '#';
            $list[$k]['leave_type'] = '#';
            switch ($v['class_type']){
                case 1://报销
                    $child = LeaveModel::where('aid',$v['id'])->find();
                    if ($child){
                        $list[$k]['leave_type'] = $leave_type[$child['type']];
                    }
                    break;
                case 2://报销
                    $child = ExpenseModel::where('aid',$v['id'])->find();
                    if ($child){
                        $list[$k]['money'] = $child['total'];
                    }
                    break;
                case 3://费用
                    $child = CostModel::where('aid',$v['id'])->find();
                    if ($child){
                        $list[$k]['money'] = $child['money'];
                    }
                    break;
            }
            if ($v['project_id']){
                $project_data = ProjectModel::getRowById($v['project_id']);
            }else{
                $project_data = [
                    'name'=>'其他',
                ];
            }
            $list[$k]['project_name'] = $project_data['name'];
            if (1 == $v['is_deal']){
                $list[$k]['deal_mark'] = '未支付';
            }elseif (2 == $v['is_deal']){
                $list[$k]['deal_mark'] = '支付-'.$v['deal_mark'].'-'.$v['deal_time'];
            }
        }

        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 1);
        $this->assign('isparams', 1);
        $this->assign('atype', $params['atype']);
        $pages = $list->render();
        $this->assign('tab_url', url('index', ['atype' => $params['atype']]));
        $this->assign('data_list', $list);
        $this->assign('panel_type', $panel_type);
        $this->assign('approval_status', $approval_status);
        $this->assign('pages', $pages);
        return $this->fetch();
    }

    public function leave()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            // 验证
            $result = $this->validate($data, 'ApprovalLeave');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);

            $send_user = html_entity_decode($data['send_user']);
            $send_user1 = json_decode($send_user,true);
            $start_time = $data['start_time'] . ' ' . $data['start_time1'];
            $end_time = $data['end_time'] . ' ' . $data['end_time1'];
//            $send_user1 = array_values(array_unique($send_user1, SORT_REGULAR));
            $send_user1 = array_values($send_user1);
            if ((strtotime($end_time) - strtotime($start_time) <= 24*3600) && count($send_user1) > 2){
                array_pop($send_user1);
            }
            $send_user2 = [];
            foreach ($send_user1 as $k=>$v) {
                $send_user2 += $v;
            }

            // 启动事务
            Db::startTrans();
            try {
                $approve = [
                    'project_id' => $data['project_id'],
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                    'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                    'time_long' => $data['time_long'],
                    'user_id' => session('admin_user.uid'),
                    'send_user' => json_encode($send_user2),
                    'copy_user' => user_array($data['copy_user']),
                ];

                $res = ApprovalModel::create($approve);

                $su = [];
                foreach ($send_user1 as $k=>$v) {
                    $su[$k] = [
                        'aid' => $res['id'],
                        'flow_num' => $k,
                        'send_user' => json_encode($v),
                    ];
                }
                $send_user_model = new ApprovalSenduser();
                $send_user_model->saveAll($su);

                $leave = [
                    'aid' => $res['id'],
                    'type' => $data['type'],
                    'reason' => $data['reason'],
                    'attachment' => $data['attachment'],
                ];
                $flag = LeaveModel::create($leave);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($flag) {
                return $this->success("操作成功{$this->score_value}", 'index');
            } else {
                return $this->error('添加失败！');
            }
        }

        $chain_user = $this->getFlowUser(0);
        $this->assign('send_user', htmlspecialchars($chain_user['manager_user']));
        $this->assign('leave_type', LeaveModel::getOption());
        return $this->fetch();
    }

    public function expense()
    {
        $params = $this->request->param();
        if (isset($params['id']) && !empty($params['id'])){
            $list1 = [];
            if (4 == $params['ct']){
                $table = 'tb_approval_business';
                $f = 'b.reason,b.address,b.attachment';
                $map = [
                    'a.id' => $params['id']
                ];
                $fields = 'a.*,' . $f;
                $list1 = db('approval')->alias('a')->field($fields)
                    ->join("{$table} b", 'a.id = b.aid', 'left')
                    ->where($map)->find();
                $list1['attachment'] = explode(',', substr($list1['attachment'], 0, -1));
                $list1['real_name'] = AdminUser::getUserById($list1['user_id'])['realname'];
                $list1['deal_user'] = $this->deal_data($list1['deal_user']);
                $list1['fellow_user'] = $this->deal_data($list1['fellow_user']);
                $list1['send_user'] = $this->deal_data($list1['send_user']);
                $list1['copy_user'] = $this->deal_data($list1['copy_user']);
                if ($list1['project_id']){
                    $project_data = ProjectModel::getRowById($list1['project_id']);
                }else{
                    $project_data = [
                        'name'=>'其他',
                    ];
                }
                $list1['project_name'] = $project_data['name'];
                $approval_status = config('other.approval_status');

                $report = ApprovalReport::getAll(5,$list1['id']);
                if ($report) {
                    foreach ($report as $k => $v) {
                        if (!empty($v['attachment'])){
                            $attachment = explode(',',$v['attachment']);
                            $report[$k]['attachment'] = array_filter($attachment);
                        }
                        $report[$k]['reply'] = ApprovalReportReply::getAll($v['id'], 5);
                    }
                }else{
                    return $this->error('请先补充出差报告！');
                }
                $this->assign('report_info', $report);

                $this->assign('approval_status', $approval_status);
                $this->assign('list1', $list1);
            }elseif (3 == $params['ct']){
                $table = 'tb_approval_cost';
                $f = 'b.type,b.reason,b.money,b.attachment';
                $map = [
                    'a.id' => $params['id']
                ];
                $fields = 'a.*,' . $f;
                $list1 = db('approval')->alias('a')->field($fields)
                    ->join("{$table} b", 'a.id = b.aid', 'left')
                    ->where($map)->find();
                $list1['attachment'] = explode(',', substr($list1['attachment'], 0, -1));
                $list1['real_name'] = AdminUser::getUserById($list1['user_id'])['realname'];
                $list1['send_user'] = $this->deal_data($list1['send_user']);
                $list1['copy_user'] = $this->deal_data($list1['copy_user']);
                if ($list1['project_id']){
                    $project_data = ProjectModel::getRowById($list1['project_id']);
                }else{
                    $project_data = [
                        'name'=>'其他',
                    ];
                }
                $list1['project_name'] = $project_data['name'];
                $approval_status = config('other.approval_status');
                $cost_type = config('other.expense_type');
                $this->assign('cost_type', $cost_type);
                $this->assign('approval_status', $approval_status);
                $this->assign('list1', $list1);
            }

            if ($this->request->isPost()) {
                $data = $this->request->post();
                $data['amount'] = array_filter($data['amount']);

                $send_user = html_entity_decode($data['send_user']);
                $send_user1 = json_decode($send_user,true);
                $send_user1 = array_values(array_unique($send_user1, SORT_REGULAR));
                $send_user2 = [];
                foreach ($send_user1 as $k=>$v) {
                    $send_user2 += $v;
                }

                // 启动事务
                Db::startTrans();
                try {
                    $approve = [
                        'project_id' => $list1['project_id'],
                        'class_type' => $data['class_type'],
                        'cid' => session('admin_user.cid'),
                        'start_time' => $list1['start_time'],
                        'end_time' => date('Y-m-d H:i:s'),
                        'time_long' => $list1['time_long'],
                        'user_id' => session('admin_user.uid'),
                        'send_user' => json_encode($send_user2),
                        'copy_user' => user_array($data['copy_user']),
                    ];
//                print_r($approve);exit();
                    $res = ApprovalModel::create($approve);

                    $su = [];
                    foreach ($send_user1 as $k=>$v) {
                        $su[$k] = [
                            'aid' => $res['id'],
                            'flow_num' => $k,
                            'send_user' => json_encode($v),
                        ];
                    }
                    $send_user_model = new ApprovalSenduser();
                    $send_user_model->saveAll($su);

                    $leave = [
                        'aid' => $res['id'],
                        'a_aid' => $list1['id'],
                        'reason' => $list1['reason'],
                        'attachment' => $data['attachment'],
                        'total' => $data['total'],
                    ];
                    if ($data['amount']) {
                        foreach ($data['amount'] as $k => $v) {
                            $leave['detail'][$k]['amount'] = $v;
                            $leave['detail'][$k]['type'] = $data['type'][$k];
                            $leave['detail'][$k]['mark'] = $data['mark'][$k];
                        }
                    }
                    $leave['detail'] = json_encode($leave['detail']);
                    $flag = ExpenseModel::create($leave);
                    // 提交事务
                    Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                }
                if ($flag) {
                    return $this->success("操作成功{$this->score_value}", 'index');
                } else {
                    return $this->error('添加失败！');
                }
            }
            $this->assign('expense_type', ExpenseModel::getOption());
            $this->assign('mytask', ProjectModel::getMyTask(0));
            return $this->fetch('expense1');
        }else{
            if ($this->request->isPost()) {
                $data = $this->request->post();
                if ('' == $data['project_id']){
                    return $this->error('请选择项目');
                }
                $data['amount'] = array_filter($data['amount']);
                // 验证
                $result = $this->validate($data, 'ApprovalExpense');
                if ($result !== true) {
                    return $this->error($result);
                }
                unset($data['id']);

                $send_user = html_entity_decode($data['send_user']);
                $send_user1 = json_decode($send_user,true);
                $send_user1 = array_values(array_unique($send_user1, SORT_REGULAR));
                $send_user2 = [];
                foreach ($send_user1 as $k=>$v) {
                    $send_user2 += $v;
                }

                // 启动事务
                Db::startTrans();
                try {
                    $approve = [
                        'project_id' => $data['project_id'],
                        'class_type' => $data['class_type'],
                        'cid' => session('admin_user.cid'),
                        'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                        'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                        'time_long' => $data['time_long'],
                        'user_id' => session('admin_user.uid'),
                        'send_user' => json_encode($send_user2),
                        'copy_user' => user_array($data['copy_user']),
                    ];
                    $res = ApprovalModel::create($approve);


                    $su = [];
                    foreach ($send_user1 as $k=>$v) {
                        $su[$k] = [
                            'aid' => $res['id'],
                            'flow_num' => $k,
                            'send_user' => json_encode($v),
                        ];
                    }
                    $send_user_model = new ApprovalSenduser();
                    $send_user_model->saveAll($su);

                    $leave = [
                        'aid' => $res['id'],
//                        'type' => $data['type'],
                        'reason' => $data['reason'],
                        'attachment' => $data['attachment'],
                        'total' => $data['total'],
                    ];
                    if ($data['amount']) {
                        foreach ($data['amount'] as $k => $v) {
                            $leave['detail'][$k]['amount'] = $v;
                            $leave['detail'][$k]['type'] = $data['type'][$k];
                            $leave['detail'][$k]['mark'] = $data['mark'][$k];
                        }
                    }
                    $leave['detail'] = json_encode($leave['detail']);
                    $flag = ExpenseModel::create($leave);
                    // 提交事务
                    Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                }
                if ($flag) {
                    return $this->success("操作成功{$this->score_value}", 'index');
                } else {
                    return $this->error('添加失败！');
                }
            }
            $this->assign('expense_type', ExpenseModel::getOption());
            $this->assign('mytask', ProjectModel::getMyTask(0));
            return $this->fetch();
        }
    }

    public function cost()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            // 验证
            $result = $this->validate($data, 'ApprovalCost');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);

            $send_user = html_entity_decode($data['send_user']);
            $send_user1 = json_decode($send_user,true);
            $send_user1 = array_values(array_unique($send_user1, SORT_REGULAR));
            $send_user2 = [];
            foreach ($send_user1 as $k=>$v) {
                $send_user2 += $v;
            }

            Db::startTrans();
            try {
                $approve = [
                    'project_id' => $data['project_id'],
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                    'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                    'time_long' => $data['time_long'],
                    'user_id' => session('admin_user.uid'),
                    'send_user' => json_encode($send_user2),
                    'copy_user' => user_array($data['copy_user']),
                ];
                $res = ApprovalModel::create($approve);

                $su = [];
                foreach ($send_user1 as $k=>$v) {
                    $su[$k] = [
                        'aid' => $res['id'],
                        'flow_num' => $k,
                        'send_user' => json_encode($v),
                    ];
                }
                $send_user_model = new ApprovalSenduser();
                $send_user_model->saveAll($su);

                $leave = [
                    'aid' => $res['id'],
                    'type' => $data['type'],
                    'reason' => $data['reason'],
                    'attachment' => $data['attachment'],
                    'money' => $data['money'],
                ];
                $flag = CostModel::create($leave);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($flag) {
                return $this->success("操作成功{$this->score_value}", 'index');
            } else {
                return $this->error('添加失败！');
            }
        }
        $this->assign('cost_option', CostModel::getOption());
        $this->assign('mytask', ProjectModel::getMyTask(0));
        return $this->fetch();
    }

    public function business()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            // 验证
            $result = $this->validate($data, 'ApprovalBusiness');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);

            $send_user = html_entity_decode($data['send_user']);
            $send_user1 = json_decode($send_user,true);
//            $send_user1 = array_values(array_unique($send_user1, SORT_REGULAR));
            $send_user1 = array_values($send_user1);
            if (count($send_user1) > 2){
                array_pop($send_user1);
            }
            $send_user2 = [];
            foreach ($send_user1 as $k=>$v) {
                $send_user2 += $v;
            }

            Db::startTrans();
            try {
                $approve = [
                    'project_id' => $data['project_id'],
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                    'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                    'time_long' => $data['time_long'],
                    'user_id' => session('admin_user.uid'),
                    'fellow_user' => user_array($data['fellow_user']),
                    'send_user' => json_encode($send_user2),
                    'copy_user' => user_array($data['copy_user']),
                ];
                $res = ApprovalModel::create($approve);

                $su = [];
                foreach ($send_user1 as $k=>$v) {
                    $su[$k] = [
                        'aid' => $res['id'],
                        'flow_num' => $k,
                        'send_user' => json_encode($v),
                    ];
                }
                $send_user_model = new ApprovalSenduser();
                $send_user_model->saveAll($su);

                $leave = [
                    'aid' => $res['id'],
                    'address' => $data['address'],
                    'reason' => $data['reason'],
                    'attachment' => $data['attachment'],
                ];
                $flag = BusinessModel::create($leave);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($flag) {
                return $this->success("操作成功{$this->score_value}", 'index');
            } else {
                return $this->error('添加失败！');
            }
        }
        $this->assign('mytask', ProjectModel::getMyTask(0));
        return $this->fetch();

    }

    public function procurement()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'ApprovalProcurement');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);

            $send_user = html_entity_decode($data['send_user']);
            $send_user1 = json_decode($send_user,true);
            $send_user1 = array_values(array_unique($send_user1, SORT_REGULAR));
            $send_user2 = [];
            foreach ($send_user1 as $k=>$v) {
                $send_user2 += $v;
            }

            Db::startTrans();
            try {
                $approve = [
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                    'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                    'time_long' => $data['time_long'],
                    'user_id' => session('admin_user.uid'),
//                    'send_user' => user_array($data['send_user']),
                    'send_user' => json_encode($send_user2),
                    'copy_user' => user_array($data['copy_user']),
                ];
                $res = ApprovalModel::create($approve);

                $su = [];
                foreach ($send_user1 as $k=>$v) {
                    $su[$k] = [
                        'aid' => $res['id'],
                        'flow_num' => $k,
                        'send_user' => json_encode($v),
                    ];
                }
                $send_user_model = new ApprovalSenduser();
                $send_user_model->saveAll($su);

                $leave = [
                    'aid' => $res['id'],
                    'cat_id' => $data['cat_id'],
                    'name' => $data['name'],
                    'number' => $data['number'],
                    'amount' => $data['amount'],
                    'reason' => $data['reason'],
                    'supplier' => $data['supplier'],
                    'url' => urlencode($data['url']),
                    'attachment' => $data['attachment'],
                ];
                $flag = ProcurementModel::create($leave);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($flag) {
                return $this->success("操作成功{$this->score_value}", 'index');
            } else {
                return $this->error('添加失败！');
            }
        }
        $chain_user = $this->getFlowUser(0);
        $this->assign('send_user', htmlspecialchars($chain_user['manager_user']));
        $this->assign('cat_option', ItemModel::getOption());
        return $this->fetch();

    }

    public function overtime()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            // 验证
            $result = $this->validate($data, 'ApprovalOvertime');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);

            $send_user = html_entity_decode($data['send_user']);
            $send_user1 = json_decode($send_user,true);
//            $send_user1 = array_values(array_unique($send_user1, SORT_REGULAR));
            $send_user1 = array_values($send_user1);
            if (count($send_user1) > 2){
                array_pop($send_user1);
            }
            $send_user2 = [];
            foreach ($send_user1 as $k=>$v) {
                $send_user2 += $v;
            }
            $a = $data['start_time'] . ' ' . $data['start_time1'];
            $b = $data['end_time'] . ' ' . $data['end_time1'];
            $c = (int)((strtotime($b) - strtotime($a))/3600);
            if ($c < 2){
                return $this->error('加班时长不到2个小时');
            }
            Db::startTrans();
            try {
                $approve = [
                    'project_id' => $data['project_id'],
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $a,
                    'end_time' => $b,
                    'time_long' => $data['time_long'],
                    'user_id' => session('admin_user.uid'),
//                    'send_user' => user_array($data['send_user']),
                    'send_user' => json_encode($send_user2),
                    'copy_user' => user_array($data['copy_user']),
                ];
                $res = ApprovalModel::create($approve);

                $su = [];
                foreach ($send_user1 as $k=>$v) {
                    $su[$k] = [
                        'aid' => $res['id'],
                        'flow_num' => $k,
                        'send_user' => json_encode($v),
                    ];
                }
                $send_user_model = new ApprovalSenduser();
                $send_user_model->saveAll($su);

                $leave = [
                    'aid' => $res['id'],
                    'time_long1' => $data['time_long1'],
                    'reason' => $data['reason'],
                    'attachment' => $data['attachment'],
                    'overtime_type' => $data['overtime_type'],
                ];
                $flag = OvertimeModel::create($leave);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($flag) {
                return $this->success("操作成功{$this->score_value}", 'index');
            } else {
                return $this->error('添加失败！');
            }
        }
        $this->assign('overtime_option', OvertimeModel::getOption());
        $this->assign('mytask', ProjectModel::getMyTask(0));
        return $this->fetch();
    }

    public function goout()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            // 验证
            $result = $this->validate($data, 'ApprovalGoout');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
            Db::startTrans();
            try {
                $approve = [
                    'project_id' => $data['project_id'],
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                    'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                    'time_long' => $data['time_long'],
                    'user_id' => session('admin_user.uid'),
                    'fellow_user' => user_array($data['fellow_user']),
                    'send_user' => user_array($data['send_user']),
                    'copy_user' => user_array($data['copy_user']),
                ];
                $res = ApprovalModel::create($approve);
                $leave = [
                    'aid' => $res['id'],
                    'address' => $data['address'],
                    'reason' => $data['reason'],
                    'time_long1' => $data['time_long1'],
                    'attachment' => $data['attachment'],
                ];
                $flag = GooutModel::create($leave);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($flag) {
                return $this->success("操作成功{$this->score_value}", 'index');
            } else {
                return $this->error('添加失败！');
            }
        }
        $this->assign('mytask', ProjectModel::getMyTask(0));
        return $this->fetch();
    }

    public function useCar()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            // 验证
            $result = $this->validate($data, 'ApprovalUsecar');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
            Db::startTrans();
            try {
                $approve = [
                    'project_id' => $data['project_id'],
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                    'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                    'time_long' => $data['time_long'],
                    'user_id' => session('admin_user.uid'),
                    'deal_user' => user_array($data['deal_user']),
                    'send_user' => user_array($data['send_user']),
                    'copy_user' => user_array($data['copy_user']),
                ];
                $res = ApprovalModel::create($approve);
                $leave = [
                    'aid' => $res['id'],
                    'address' => $data['address'],
                    'reason' => $data['reason'],
                    'car_type' => $data['car_type'],
                    'time_long1' => $data['time_long1'],
                    'attachment' => $data['attachment'],
                ];
                $flag = CarModel::create($leave);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($flag) {
                return $this->success("操作成功{$this->score_value}", 'index');
            } else {
                return $this->error('添加失败！');
            }
        }
        $this->assign('car_type', CarModel::getOption());
        $this->assign('mytask', ProjectModel::getMyTask(0));
        return $this->fetch();
    }

    public function useSeal()
    {

    }

    public function clockIn()
    {

    }

    public function officeGood()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            // 验证
            $result = $this->validate($data, 'ApprovalGoods');
            if ($result !== true) {
                return $this->error($result);
            }
            $good_arr = [];
            if (isset($data['number']) && !empty($data['number'])) {
                foreach ($data['number'] as $k => $v) {
                    $good_arr[$k]['id'] = $data['good_id'][$k];
                    $good_arr[$k]['name'] = $data['name'][$k];
                    $good_arr[$k]['number'] = $v;
                }
            }
            $goods = json_encode($good_arr);
            unset($data['id']);
            Db::startTrans();
            try {
                $approve = [
                    'project_id' => $data['project_id'],
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                    'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                    'time_long' => $data['time_long'],
                    'user_id' => session('admin_user.uid'),
                    'send_user' => user_array($data['send_user']),
                    'copy_user' => user_array($data['copy_user']),
                ];
                $res = ApprovalModel::create($approve);
                $leave = [
                    'aid' => $res['id'],
                    'goods' => $goods,
                    'reason' => $data['reason'],
                    'attachment' => $data['attachment'],
                ];
                $flag = ApprovalGoods::create($leave);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($flag) {
                return $this->success("操作成功{$this->score_value}", 'index');
            } else {
                return $this->error('添加失败！');
            }
        }
//        $this->assign('cat_option',ItemModel::getOption());
        $this->assign('mytask', ProjectModel::getMyTask(0));
        return $this->fetch();
    }

    public function borrow()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            if (!isset($data['borrow_option'])){
                return $this->error('请选择借用物品');
            }
            // 验证
            $result = $this->validate($data, 'ApprovalBorrow');
            if ($result !== true) {
                return $this->error($result);
            }

            unset($data['id']);
            Db::startTrans();
            try {
                $approve = [
                    'project_id' => $data['project_id'],
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                    'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                    'time_long' => $data['time_long'],
                    'user_id' => session('admin_user.uid'),
                    'send_user' => user_array($data['send_user']),
                    'copy_user' => user_array($data['copy_user']),
                ];
                $res = ApprovalModel::create($approve);
                $leave = [
                    'aid' => $res['id'],
                    'borrow' => json_encode($data['borrow_option']),
                    'reason' => $data['reason'],
                    'attachment' => $data['attachment'],
                ];
                $flag = ApprovalBorrow::create($leave);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($flag) {
                return $this->success("操作成功{$this->score_value}", 'index');
            } else {
                return $this->error('添加失败！');
            }
        }
        $this->assign('borrow_option',ApprovalBorrow::getOption());
        $this->assign('mytask', ProjectModel::getMyTask(0));
        return $this->fetch();
    }

    public function printView()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            // 验证
            $result = $this->validate($data, 'ApprovalPrint');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);

            if (!empty($data['num'])){
                foreach ($data['num'] as $k=>$v){
                    if (empty($v)){
                        unset($data['num'][$k],$data['quality'][$k],$data['size_type'][$k]);
                    }
                }
            }

            $send_user = html_entity_decode($data['send_user']);
            $send_user1 = json_decode($send_user,true);
            $send_user1 = array_values(array_unique($send_user1, SORT_REGULAR));
            $send_user2 = [];
            foreach ($send_user1 as $k=>$v) {
                $send_user2 += $v;
            }

            Db::startTrans();
            try {
                $approve = [
                    'project_id' => $data['project_id'],
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                    'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                    'time_long' => $data['time_long'],
                    'user_id' => session('admin_user.uid'),
//                    'send_user' => user_array($data['send_user']),
                    'send_user' => json_encode($send_user2),
                    'copy_user' => user_array($data['copy_user']),
                ];
                $res = ApprovalModel::create($approve);

                $su = [];
                foreach ($send_user1 as $k=>$v) {
                    $su[$k] = [
                        'aid' => $res['id'],
                        'flow_num' => $k,
                        'send_user' => json_encode($v),
                    ];
                }
                $send_user_model = new ApprovalSenduser();
                $send_user_model->saveAll($su);

                $leave = [
                    'aid' => $res['id'],
                    'type' => $data['type'],
                    'project_id' => $data['project_id'],
                    'application' => $data['application'],
                    'size_type' => json_encode($data['size_type']),
                    'quality' => json_encode($data['quality']),
                    'num' => json_encode($data['num']),
                    'store_id' => $data['store_id'],
                    'reason' => $data['reason'],
                    'attachment' => $data['attachment'],
//                    'money' => $data['money'],
                ];
                $flag = ApprovalPrint::create($leave);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($flag) {
                return $this->success("操作成功{$this->score_value}", 'index');
            } else {
                return $this->error('添加失败！');
            }
        }
        $this->assign('print_option', ApprovalPrint::getPrintOption());
        $this->assign('size_option', ApprovalPrint::getSizeOption());
        $this->assign('quality_option', ApprovalPrint::getQualityOption());
        $this->assign('store_option', ApprovalPrint::getStoreOption());
        $this->assign('mytask', ProjectModel::getMyTask(0));
        return $this->fetch();
    }

    public function dispatch()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            // 验证
            $result = $this->validate($data, 'ApprovalDispatch');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
            Db::startTrans();
            try {
                $approve = [
                    'project_id' => $data['project_id'],
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                    'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                    'time_long' => $data['time_long'],
                    'user_id' => session('admin_user.uid'),
                    'deal_user' => user_array($data['deal_user']),
                    'send_user' => user_array($data['send_user']),
                    'copy_user' => user_array($data['copy_user']),
                ];
                $res = ApprovalModel::create($approve);
                $leave = [
                    'aid' => $res['id'],
                    'address' => $data['address'],
                    'reason' => $data['reason'],
                    'contacts' => $data['contacts'],
                    'belongs' => $data['belongs'],
                    'time_long1' => $data['time_long1'],
                    'attachment' => $data['attachment'],
                ];
                $flag = DispatchModel::create($leave);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($flag) {
                return $this->success("操作成功{$this->score_value}", 'index');
            } else {
                return $this->error('添加失败！');
            }
        }
        $this->assign('mytask', ProjectModel::getMyTask(0));
        return $this->fetch();
    }

    public function batch(){
        $data = $this->request->param();

        if (!isset($data['class_type']) || empty($data['class_type'])) {
            return $this->error('请选择类型');
        }
        if (!isset($data['id']) || empty($data['id'])) {
            return $this->error('参数传递错误[1]！');
        }
        if (!isset($data['table']) || empty($data['table'])) {
            return $this->error('参数传递错误[2]！');
        }
        // 获取主键
        $pk = Db::name($data['table'])->getPk();
        $map = [];
        $map[$pk] = ['in', $data['id']];

        $list1 = ApprovalModel::where($map)->select();
        //事务提交，保证数据一致性
        if ($list1){
            foreach ($list1 as $list){
                Db::startTrans();
                try {
                    if (6 == $data['class_type'] && 2 == $data['val']){
                        $c = (int)((strtotime($list['end_time']) - strtotime($list['start_time']))/3600);
                        $per_score = config('score.hour_score') ? config('score.hour_score') : 0;
                        $gl_add_score = $per_score*$c;
                        $score = [
                            'subject_id' => $list['project_id'],
                            'project_id' => 0,
                            'cid' => session('admin_user.cid'),
                            'project_code' => '',
                            'user' => $list['user_id'],
                            'gl_add_score' => $gl_add_score,
                            'remark' => "加班时间段{$list['start_time']}~{$list['end_time']},计算{$c}小时，鼓励{$gl_add_score}斗",
                            'user_id' => session('admin_user.uid'),
                            'create_time' => time(),
                            'update_time' => time(),
                        ];
                        db('score')->insert($score);
                    }

                    $time = time();
                    $uid = session('admin_user.uid');
                    $sql = "UPDATE tb_approval SET send_user = JSON_SET(send_user, '$.\"{$uid}\"', 'a'),status={$data['val']},update_time={$time} WHERE id ={$list['id']}";
                    $res = ApprovalModel::execute($sql);
                    // 提交事务
                    Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                }
            }
        }

        if ($res === false) {
            return $this->error('状态设置失败');
        }
        return $this->success('状态设置成功');
    }

    public function dealDay($day){
        $legal_holiday = config('config_score.legal_holiday');
        $weekend_work = config('config_score.weekend_work');
        $year = substr($day,0,4);
        $year_legal_holiday = $legal_holiday[$year];
        $year_weekend_work = $weekend_work[$year];
        /**
         * 星期一 1
         * 星期二 2
         * 星期三 3
         * 星期四 4
         * 星期五 5
         * 星期六 6
         * 星期日 0
         * 法定节假日 7
         * 周末调整工作日 8
         */
        if (in_array($day,$year_legal_holiday)){
            $n = 7;
        }elseif (in_array($day,$year_weekend_work)){
            $n = 8;
        }else{
            $n = date('w',strtotime($day));
        }
        //工作日
        $working_day = [1,2,3,4,5,8];
        $f = false;
        if (in_array($n,$working_day)){
            $f = true;
        }
        return $f;
    }

    public function backLeave()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'ApprovalBackleave');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
            // 启动事务
            Db::startTrans();
            try {
                $approve = [
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                    'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                    'time_long' => $data['time_long'],
                    'user_id' => session('admin_user.uid'),
                    'send_user' => user_array($data['send_user']),
                    'copy_user' => user_array($data['copy_user']),
                ];

                $res = ApprovalModel::create($approve);
                $leave = [
                    'aid' => $res['id'],
                    'leave_id' => $data['leave_id'],
                    'reason' => $data['reason'],
                    'attachment' => $data['attachment'],
                ];
                $flag = BackleaveModel::create($leave);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($flag) {
                return $this->success("操作成功{$this->score_value}", 'index');
            } else {
                return $this->error('添加失败！');
            }
        }
        $this->assign('leave_option', ApprovalModel::getOption());
        return $this->fetch();
    }

    public function signBills()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            if (empty($data['shigong_user'])){
                return $this->error('施工员不存在，请输入正确姓名');
            }
            $data['content'] = array_unique(array_filter($data['content']));
            // 验证
            $result = $this->validate($data, 'ApprovalBills');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);

            $send_user = html_entity_decode($data['send_user']);
            $send_user1 = json_decode($send_user,true);
            array_unshift($send_user1,[$data['shigong_user']=>'']);//在头部插入元素
            array_pop($send_user1);//删除尾部的元素
            $send_user2 = [];
            foreach ($send_user1 as $k=>$v) {
                $send_user2 += $v;
            }

            Db::startTrans();
            try {
                $approve = [
                    'project_id' => $data['project_id'],
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                    'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                    'time_long' => $data['time_long'],
                    'user_id' => session('admin_user.uid'),
//                    'send_user' => user_array($data['send_user']),
                    'send_user' => json_encode($send_user2),
                    'copy_user' => user_array($data['copy_user']),
                ];
                $res = ApprovalModel::create($approve);

                $su = [];
                foreach ($send_user1 as $k=>$v) {
                    $su[$k] = [
                        'aid' => $res['id'],
                        'flow_num' => $k,
                        'send_user' => json_encode($v),
                    ];
                }
                $send_user_model = new ApprovalSenduser();
                $send_user_model->saveAll($su);

                $leave = [
                    'aid' => $res['id'],
                    'date' => $data['date'],
                    'money' => $data['money'],
                    'shigong_user' => $data['shigong_user'],
                    'reason' => $data['reason'],
                    'attachment' => $data['attachment'],
                ];
                if ($data['content']) {
                    foreach ($data['content'] as $k => $v) {
                        $leave['detail'][$k]['content'] = $v;
                        $leave['detail'][$k]['num'] = !empty($data['num'][$k]) ? $data['num'][$k] : 0;
                        $leave['detail'][$k]['unit'] = !empty($data['unit'][$k]) ? $data['unit'][$k] : 1;
                        $leave['detail'][$k]['per_price'] = !empty($data['per_price'][$k]) ? $data['per_price'][$k] : 0;
                    }
                }
                $leave['detail'] = json_encode($leave['detail']);
                $flag = ApprovalBills::create($leave);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($flag) {
                return $this->success("操作成功{$this->score_value}", 'index');
            } else {
                return $this->error('添加失败！');
            }
        }
        $this->assign('unit_option', ApprovalBills::getUnitOption());
        $this->assign('mytask', ProjectModel::getMyTask(0));
        return $this->fetch();
    }

    public function read()
    {
        $params = $this->request->param();

        switch ($params['class_type']) {
            case 1:
                $table = 'tb_approval_leave';
                $f = 'b.type,b.reason,b.attachment';
                break;
            case 2:
                $table = 'tb_approval_expense';
                $f = 'b.a_aid,b.type,b.reason,b.detail,b.total,b.attachment';
                break;
            case 3:
                $table = 'tb_approval_cost';
                $f = 'b.type,b.reason,b.money,b.attachment';
                break;
            case 4:
                $table = 'tb_approval_business';
                $f = 'b.reason,b.address,b.attachment';
                break;
            case 5:
                $table = 'tb_approval_procurement';
                $f = 'b.reason,b.name,b.number,b.amount,b.attachment,b.supplier,b.url';
                break;
            case 6:
                $table = 'tb_approval_overtime';
                $f = 'b.reason,b.time_long1,b.attachment,b.overtime_type';
                break;
            case 7:
                $table = 'tb_approval_goout';
                $f = 'b.reason,b.address,b.time_long1,b.attachment';
                break;
            case 8:
                $table = 'tb_approval_usecar';
                $f = 'b.reason,b.address,b.time_long1,b.attachment,b.car_type,b.before_img,b.after_img';
                break;
            case 9:
                break;
            case 10:
                break;
            case 11:
                $table = 'tb_approval_goods';
                $f = 'b.reason,b.goods,b.attachment';
                break;
            case 12:
                $table = 'tb_approval_print';
                $f = 'b.project_id,b.reason,b.type,b.size_type,b.money,b.application,b.quality,b.num,b.store_id,b.attachment';
                break;
            case 13:
                $table = 'tb_approval_dispatch';
                $f = 'b.reason,b.address,b.time_long1,b.attachment,b.contacts,b.belongs';
                break;
            case 14:
                $table = 'tb_approval_borrow';
                $f = 'b.reason,b.borrow,b.attachment';
                break;
            case 15:
                $table = 'tb_approval_backleave';
                $f = 'b.leave_id,b.reason,b.attachment';
                break;
            case 16:
                $table = 'tb_approval_bills';
                $f = 'b.reason,b.date,b.detail,b.money,b.shigong_user,b.attachment';
                break;
            default:
                $table = 'tb_approval_leave';
                $f = 'b.type,b.reason,b.attachment';
                break;
        }
        $map = [
            'a.id' => $params['id']
        ];
        $fields = 'a.*,' . $f;
        $list = db('approval')->alias('a')->field($fields)
            ->join("{$table} b", 'a.id = b.aid', 'left')
            ->where($map)->find();

        $map1 = [
            'aid' => $params['id']
        ];
        $su_list = ApprovalSenduser::where($map1)->select();
        $su_list_count = count($su_list);
        $status = [];
        if ($su_list){
            foreach ($su_list as $k=>$v) {
                $su_list[$k]['send_user_id'] = array_keys(json_decode($v['send_user'],true));
                $su_list[$k]['send_user'] = $this->deal_data($v['send_user']);
                $su_list[$k]['cunzai'] = false;
                if (in_array(session('admin_user.uid'),$su_list[$k]['send_user_id'])){
                    $su_list[$k]['cunzai'] = true;
                }
                $status[$v['flow_num']] = $v['status'];
                if ($v['flow_num'] == 0){
                    $status[-1] = 2;
                }
            }
        }else{
            $su_list = [];
        }
//        print_r($su_list);
        $this->assign('su_list', $su_list);
        $this->assign('status', $status);
//print_r($list);
        $res = false;
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (!empty($data['atype'])) {
                if (5 == $data['atype'] && 8 == $data['class_type']) {
                    if (isset($data['before_img'])) {
                        $data['before_img'] = array_filter($data['before_img']);
                        if (count($data['before_img']) < 4) {
                            return $this->error('照片上传数量不够！');
                        }
                        $tmp['before_img'] = json_encode($data['before_img']);
                    }
                    if (isset($data['after_img'])) {
                        $data['after_img'] = array_filter($data['after_img']);
                        if (count($data['after_img']) < 4) {
                            return $this->error('照片上传数量不够！');
                        }
                        $tmp['after_img'] = json_encode($data['after_img']);
                    }

                    $res = CarModel::where('aid', $data['id'])->update($tmp);
                } elseif (3 == $data['atype']) {
                    //延迟审批扣除对应GL
                    $day_len = floor((time()-$list['update_time'])/3600/24);
                    if ($day_len > 0){
                        $score = [
                            'subject_id' => $list['project_id'],
                            'project_id' => 0,
                            'cid' => session('admin_user.cid'),
                            'project_code' => '',
                            'user' => session('admin_user.uid'),
                            'gl_sub_score' => $day_len * 10,//每超过一天扣10斗
                            'remark' => "日常审批时差".date('Y-m-d H:i:s')."~".date('Y-m-d H:i:s',$list['update_time']).",超过{$day_len}天，扣除". $day_len * 10 ."斗，编号[{$list['id']}]",
                            'user_id' => session('admin_user.uid'),
                            'create_time' => time(),
                            'update_time' => time(),
                        ];
                    }else{
                        $score = [
                            'subject_id' => $list['project_id'],
                            'project_id' => 0,
                            'cid' => session('admin_user.cid'),
                            'project_code' => '',
                            'user' => session('admin_user.uid'),
                            'gl_add_score' => 10,//正常审批奖励10
                            'remark' => "及时审批奖励10斗，编号[{$list['id']}]",
                            'user_id' => session('admin_user.uid'),
                            'create_time' => time(),
                            'update_time' => time(),
                        ];
                    }
                    db('score')->insert($score);
//                $res= ApprovalModel::where('id',$data['id'])->setField('status',$data['status']);

                    //事务提交，保证数据一致性
                    Db::startTrans();
                    try {
//                        if (1 == $data['class_type'] && 2 == $data['status']){
//                            $start_time = explode(' ',$list['start_time']);
//                            $end_time = explode(' ',$list['end_time']);
//                            if ($start_time[0] == $end_time[0]){
//                                $c = (int)((strtotime($list['end_time']) - strtotime($list['start_time']))/3600);
//                            }else{
//                                $d = round((strtotime($end_time[0])-strtotime($start_time[0]))/3600/24);
//                                $c = 0;
//                                for ($i=0; $i<=$d; $i++) {
//                                    $dd = date('Y-m-d',strtotime("{$start_time[0]} +{$i} day"));
//                                    if ($this->dealDay($dd)){
//                                        $c++;
//                                    }
//                                }
//                                $c *= 8;
//                            }
//
//                            $per_score = config('score.hour_score') ? config('score.hour_score') : 0;
//                            $gl_sub_score = $per_score*$c;
//                            $score = [
//                                'subject_id' => $list['project_id'],
//                                'project_id' => 0,
//                                'cid' => session('admin_user.cid'),
//                                'project_code' => '',
//                                'user' => $list['user_id'],
//                                'gl_sub_score' => $gl_sub_score,
//                                'remark' => "请假调休时间段{$list['start_time']}~{$list['end_time']},计算{$c}小时，扣除{$gl_sub_score}斗(编号{$list['id']})",
//                                'user_id' => session('admin_user.uid'),
//                                'create_time' => time(),
//                                'update_time' => time(),
//                            ];
//                            db('score')->insert($score);
//                        }
//
//                        if (6 == $data['class_type'] && 2 == $data['status']){
//                            $c = (int)((strtotime($list['end_time']) - strtotime($list['start_time']))/3600);
//                            $per_score = config('score.hour_score') ? config('score.hour_score') : 0;
//                            $gl_add_score = $per_score*$c;
//                            $score = [
//                                'subject_id' => $list['project_id'],
//                                'project_id' => 0,
//                                'cid' => session('admin_user.cid'),
//                                'project_code' => '',
//                                'user' => $list['user_id'],
//                                'gl_add_score' => $gl_add_score,
//                                'remark' => "加班时间段{$list['start_time']}~{$list['end_time']},计算{$c}小时，鼓励{$gl_add_score}斗",
//                                'user_id' => session('admin_user.uid'),
//                                'create_time' => time(),
//                                'update_time' => time(),
//                            ];
//                            db('score')->insert($score);
//                        }
//                        unset($data['atype'], $data['class_type']);

                    $uid = session('admin_user.uid');
                    $ap = [
                        'id'=>$data['id'],
                        'status'=>$data['status'],
                        'mark'=>$data['mark'],
                        'update_time'=>time(),
                    ];
                    if (!empty($su_list)){
                        $w = [
                            'aid'=>$data['id'],
                            'status'=>1,
                        ];
                        $w1 = "JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"')";

                        $u = [
                            'status'=>$data['status'],
                            'mark'=>$data['mark'],
                            'update_time'=>time(),
                        ];

                        $s = new ApprovalSenduser();
                        $s->where($w)->where($w1)->update($u);

                        $sql = "UPDATE tb_approval_senduser SET send_user = JSON_REPLACE(send_user, '$.\"{$uid}\"', 'a') WHERE aid ={$data['id']} and status={$data['status']}";
                        $res = ApprovalSenduser::execute($sql);

                        $sql = "UPDATE tb_approval SET send_user = JSON_SET(send_user, '$.\"{$uid}\"', 'a'),update_time=UNIX_TIMESTAMP() WHERE id ={$data['id']}";
                        $res = ApprovalModel::execute($sql);

                        $last = ApprovalSenduser::where($map1)->order('id desc')->limit(1)->find();
//                    print_r($last);exit();
                        if (2 == $last['status'] || 2 != $data['status']){
                            $res = ApprovalModel::update($ap);
                        }
                        if (2 == $last['status']){
                            if (1 == $data['class_type'] && 2 == $data['status']){
                                $start_time = explode(' ',$list['start_time']);
                                $end_time = explode(' ',$list['end_time']);
                                if ($start_time[0] == $end_time[0]){
                                    $c = (int)((strtotime($list['end_time']) - strtotime($list['start_time']))/3600);
                                }else{
                                    $d = round((strtotime($end_time[0])-strtotime($start_time[0]))/3600/24);
                                    $c = 0;
                                    for ($i=0; $i<=$d; $i++) {
                                        $dd = date('Y-m-d',strtotime("{$start_time[0]} +{$i} day"));
                                        if ($this->dealDay($dd)){
                                            $c++;
                                        }
                                    }
                                    $c *= 8;
                                }

                                $per_score = config('score.hour_score') ? config('score.hour_score') : 0;
                                $gl_sub_score = $per_score*$c;
                                $score = [
                                    'subject_id' => $list['project_id'],
                                    'project_id' => 0,
                                    'cid' => session('admin_user.cid'),
                                    'project_code' => '',
                                    'user' => $list['user_id'],
                                    'gl_sub_score' => $gl_sub_score,
                                    'remark' => "请假调休时间段{$list['start_time']}~{$list['end_time']},计算{$c}小时，扣除{$gl_sub_score}斗(编号{$list['id']})",
                                    'user_id' => session('admin_user.uid'),
                                    'create_time' => time(),
                                    'update_time' => time(),
                                ];
                                db('score')->insert($score);
                            }

                            if (6 == $data['class_type'] && 2 == $data['status']){
                                $c = (int)((strtotime($list['end_time']) - strtotime($list['start_time']))/3600);
                                $per_score = config('score.hour_score') ? config('score.hour_score') : 0;
                                $gl_add_score = $per_score*$c;
                                $score = [
                                    'subject_id' => $list['project_id'],
                                    'project_id' => 0,
                                    'cid' => session('admin_user.cid'),
                                    'project_code' => '',
                                    'user' => $list['user_id'],
                                    'gl_add_score' => $gl_add_score,
                                    'remark' => "加班时间段{$list['start_time']}~{$list['end_time']},计算{$c}小时，鼓励{$gl_add_score}斗",
                                    'user_id' => session('admin_user.uid'),
                                    'create_time' => time(),
                                    'update_time' => time(),
                                ];
                                db('score')->insert($score);
                            }
                        }
                    }else{
                        ApprovalModel::update($ap);
                        $sql = "UPDATE tb_approval SET send_user = JSON_SET(send_user, '$.\"{$uid}\"', 'a') WHERE id ={$data['id']}";
                        $res = ApprovalModel::execute($sql);

                        if (1 == $data['class_type'] && 2 == $data['status']){
                            $start_time = explode(' ',$list['start_time']);
                            $end_time = explode(' ',$list['end_time']);
                            if ($start_time[0] == $end_time[0]){
                                $c = (int)((strtotime($list['end_time']) - strtotime($list['start_time']))/3600);
                            }else{
                                $d = round((strtotime($end_time[0])-strtotime($start_time[0]))/3600/24);
                                $c = 0;
                                for ($i=0; $i<=$d; $i++) {
                                    $dd = date('Y-m-d',strtotime("{$start_time[0]} +{$i} day"));
                                    if ($this->dealDay($dd)){
                                        $c++;
                                    }
                                }
                                $c *= 8;
                            }

                            $per_score = config('score.hour_score') ? config('score.hour_score') : 0;
                            $gl_sub_score = $per_score*$c;
                            $score = [
                                'subject_id' => $list['project_id'],
                                'project_id' => 0,
                                'cid' => session('admin_user.cid'),
                                'project_code' => '',
                                'user' => $list['user_id'],
                                'gl_sub_score' => $gl_sub_score,
                                'remark' => "请假调休时间段{$list['start_time']}~{$list['end_time']},计算{$c}小时，扣除{$gl_sub_score}斗(编号{$list['id']})",
                                'user_id' => session('admin_user.uid'),
                                'create_time' => time(),
                                'update_time' => time(),
                            ];
                            db('score')->insert($score);
                        }

                        if (6 == $data['class_type'] && 2 == $data['status']){
                            $c = (int)((strtotime($list['end_time']) - strtotime($list['start_time']))/3600);
                            $per_score = config('score.hour_score') ? config('score.hour_score') : 0;
                            $gl_add_score = $per_score*$c;
                            $score = [
                                'subject_id' => $list['project_id'],
                                'project_id' => 0,
                                'cid' => session('admin_user.cid'),
                                'project_code' => '',
                                'user' => $list['user_id'],
                                'gl_add_score' => $gl_add_score,
                                'remark' => "加班时间段{$list['start_time']}~{$list['end_time']},计算{$c}小时，鼓励{$gl_add_score}斗",
                                'user_id' => session('admin_user.uid'),
                                'create_time' => time(),
                                'update_time' => time(),
                            ];
                            db('score')->insert($score);
                        }
                    }

                        // 提交事务
                        Db::commit();
                    } catch (\Exception $e) {
                        // 回滚事务
                        Db::rollback();
                    }
                }elseif (4 == $data['atype']) {
                    if (empty($data['is_deal'])){
                        return $this->error('请选择支付结果');
                    }
                    //事务提交，保证数据一致性
                    Db::startTrans();
                    try {
                        unset($data['atype'], $data['class_type']);
                        $data['deal_time'] = date('Y-m-d H:i:s');
                        $uid = session('admin_user.uid');
                        $sql = "UPDATE tb_approval SET copy_user = JSON_SET(copy_user, '$.\"{$uid}\"', 'a') WHERE id ={$data['id']}";
                        ApprovalModel::execute($sql);
                        $res = ApprovalModel::update($data);
                        // 提交事务
                        Db::commit();
                    } catch (\Exception $e) {
                        // 回滚事务
                        Db::rollback();
                    }
                }
            }elseif (4 == $data['class_type']){
                unset($data['class_type'],$data['id']);
                $data['cid'] = session('admin_user.cid');
                $data['user_id'] = session('admin_user.uid');
//                print_r($data);exit();
                $res = ApprovalReportModel::create($data);
            }
            if (!$res) {
                return $this->error('处理失败！');
            }
            return $this->success("操作成功{$this->score_value}");
        }

        if ($list['project_id']){
            $project_data = ProjectModel::getRowById($list['project_id']);
        }else{
            $project_data = [
                'name'=>'其他',
            ];
        }

        switch ($params['class_type']) {
            case 1:
                $leave_type = config('other.leave_type');
                $this->assign('leave_type', $leave_type);
                break;
            case 2:
                $ct = ApprovalModel::where('id',$list['a_aid'])->column('class_type');
                if ($ct){
                    if (!empty($list['a_aid']) && $ct[0] == 4){
                        $table = 'tb_approval_business';
                        $f = 'b.reason,b.address,b.attachment';
                        $map = [
                            'a.id' => $list['a_aid']
                        ];
                        $fields = 'a.*,' . $f;
                        $list1 = db('approval')->alias('a')->field($fields)
                            ->join("{$table} b", 'a.id = b.aid', 'left')
                            ->where($map)->find();
                        $list1['attachment'] = explode(',', substr($list1['attachment'], 0, -1));
                        $list1['real_name'] = AdminUser::getUserById($list1['user_id'])['realname'];
                        $list1['deal_user'] = $this->deal_data($list1['deal_user']);
                        $list1['fellow_user'] = $this->deal_data($list1['fellow_user']);
                        $list1['send_user'] = $this->deal_data($list1['send_user']);
                        $list1['copy_user'] = $this->deal_data($list1['copy_user']);
                        if ($list1['project_id']){
                            $project_data = ProjectModel::getRowById($list1['project_id']);
                        }else{
                            $project_data = [
                                'name'=>'其他',
                            ];
                        }
                        $list1['project_name'] = $project_data['name'];

                        $report = ApprovalReport::getAll(5,$list1['id']);
                        if ($report) {
                            foreach ($report as $k => $v) {
                                if (!empty($v['attachment'])){
                                    $attachment = explode(',',$v['attachment']);
                                    $report[$k]['attachment'] = array_filter($attachment);
                                }
                                $report[$k]['reply'] = ApprovalReportReply::getAll($v['id'], 5);
                            }
                        }
                        $this->assign('report_info', $report);

                    }elseif (!empty($list['a_aid']) && $ct[0] == 3){
                        $table = 'tb_approval_cost';
                        $f = 'b.type,b.reason,b.money,b.attachment';
                        $map = [
                            'a.id' => $list['a_aid']
                        ];
                        $fields = 'a.*,' . $f;
                        $list1 = db('approval')->alias('a')->field($fields)
                            ->join("{$table} b", 'a.id = b.aid', 'left')
                            ->where($map)->find();
                        $list1['attachment'] = explode(',', substr($list1['attachment'], 0, -1));
                        $list1['real_name'] = AdminUser::getUserById($list1['user_id'])['realname'];
                        $list1['deal_user'] = $this->deal_data($list1['deal_user']);
                        $list1['fellow_user'] = $this->deal_data($list1['fellow_user']);
                        $list1['send_user'] = $this->deal_data($list1['send_user']);
                        $list1['copy_user'] = $this->deal_data($list1['copy_user']);
                        if ($list1['project_id']){
                            $project_data = ProjectModel::getRowById($list1['project_id']);
                        }else{
                            $project_data = [
                                'name'=>'其他',
                            ];
                        }
                        $list1['project_name'] = $project_data['name'];
                    }else{
                        $list1 = [];
                    }
                }else{
                    $ct[0] = 0;
                    $list1 = [];
                }

                $list['detail'] = json_decode($list['detail'], true);
                if (!isset($list['detail'][0]['type'])){
                    foreach ($list['detail'] as $k=>$v) {
                        $list['detail'][$k]['type'] = 1;
                    }
                }

                $expense_type = config('other.expense_type');
                $this->assign('ct', $ct[0]);
                $this->assign('expense_type', $expense_type);
                $this->assign('list1', $list1);
                break;
            case 3:
                $cost_type = config('other.expense_type');
                $this->assign('expense_type', $cost_type);
                break;
            case 4:
                $send_user_arr = json_decode($list['send_user'],true);
                $send_user_arr = array_keys($send_user_arr);
                array_push($send_user_arr,$list['user_id']);
                if ($project_data['is_private'] && !in_array(session('admin_user.uid'),$send_user_arr)){
//                if (!in_array(session('admin_user.uid'),$send_user_arr)){
                    $list['address'] = '***';
                    $list['fellow_user'] = '***';
                    $list['reason'] = '***';
                }
                $report = ApprovalReport::getAll(5);
                if ($report) {
                    foreach ($report as $k => $v) {
                        if (!empty($v['attachment'])){
                            $attachment = explode(',',$v['attachment']);
                            $report[$k]['attachment'] = array_filter($attachment);
                        }
                        $report[$k]['reply'] = ApprovalReportReply::getAll($v['id'], 5);
                    }
                }
                $this->assign('report_info', $report);
                break;
            case 5:
                $list['url'] = urldecode($list['url']);
                break;
            case 6:
                $overtime_type = config('other.overtime_type');
                $this->assign('overtime_type', $overtime_type);
                break;
            case 7:
                break;
            case 8:
                $list['before_img'] = json_decode($list['before_img'], true);
                $list['after_img'] = json_decode($list['after_img'], true);
//                $car_type = config('other.car_type');
                $this->assign('car_type', CarModel::getCarItem());
                break;
            case 9:
                break;
            case 10:
                break;
            case 11:
                $list['goods'] = json_decode($list['goods'], true);
                break;
            case 12:
                $print_type = config('other.print_type');
                $size_type = config('other.size_type');
                $quality_type = config('other.quality_type');
                $store_type = config('other.store_type');
                $list['size_type'] = json_decode($list['size_type'],true);
                $list['quality'] = json_decode($list['quality'],true);
                $list['num'] = json_decode($list['num'],true);
                $list['s'] = '';
                foreach ($list['num'] as $k=>$v){
                    $list['s'] .= $size_type[$list['size_type'][$k]].'--'.$quality_type[$list['quality'][$k]].'--'.$v."--页<br>";
                }
                $list['type'] = $print_type[$list['type']];
                $list['store_id'] = $store_type[$list['store_id']];
                break;
            case 13:
                break;
            case 14:
                $list['borrow'] = json_decode($list['borrow'], true);
                break;
            case 15:
                $table = 'tb_approval_leave';
                $f = 'b.type,b.reason,b.attachment';
                $map = [
                    'a.id' => $list['leave_id']
                ];
                $fields = 'a.*,' . $f;
                $list1 = db('approval')->alias('a')->field($fields)
                    ->join("{$table} b", 'a.id = b.aid', 'left')
                    ->where($map)->find();
                $leave_type = config('other.leave_type');
                $list1['attachment'] = explode(',', substr($list1['attachment'], 0, -1));
                $list1['real_name'] = AdminUser::getUserById($list1['user_id'])['realname'];
                $list1['deal_user'] = $this->deal_data($list1['deal_user']);
                $list1['fellow_user'] = $this->deal_data($list1['fellow_user']);
                $list1['send_user'] = $this->deal_data($list1['send_user']);
                $list1['copy_user'] = $this->deal_data($list1['copy_user']);
                if ($list1['project_id']){
                    $project_data = ProjectModel::getRowById($list1['project_id']);
                }else{
                    $project_data = [
                        'name'=>'其他',
                    ];
                }
                $list1['type'] = $leave_type[$list1['type']];
                $list1['project_name'] = $project_data['name'];
                $this->assign('list1', $list1);
                break;
            case 16:
                $list['detail'] = json_decode($list['detail'], true);
                $list['shigong_user'] = AdminUser::getUserById($list['shigong_user'])['realname'];
                $unit2_type = config('other.unit2');
                $this->assign('unit_type', $unit2_type);
                break;
            default:
                break;
        }
        $list['attachment'] = explode(',', substr($list['attachment'], 0, -1));
        $list['real_name'] = AdminUser::getUserById($list['user_id'])['realname'];
        $list['deal_user'] = $this->deal_data($list['deal_user']);
        $list['fellow_user'] = $this->deal_data($list['fellow_user']);
        $list['send_user'] = $this->deal_data($list['send_user']);
        $list['copy_user'] = $this->deal_data($list['copy_user']);

//        print_r($list);
        $approval_status = config('other.approval_status');
        $this->assign('data_list', $list);
        $this->assign('approval_status', $approval_status);
        $this->assign('class_type', $params['class_type']);
        $this->assign('project_name', $project_data);
        return $this->fetch();
    }

    public function approvalBack($id)
    {
        $res = ApprovalModel::where('id', $id)->update(['status'=>3,'update_time'=>time()]);
        if (!$res) {
            return $this->error('操作失败！');
        }else{
            $s = ApprovalSenduser::where('aid', $id)->select();
            if ($s){
                ApprovalSenduser::where('aid', $id)->update(['status'=>3,'update_time'=>time()]);
            }
            return $this->success('操作成功。');
        }
    }

    public function statistics(){
        $params = $this->request->param();
        $cid = session('admin_user.cid');
        $d = date('Y-m-01').' - '.date('Y-m-d');
        if (isset($params['search_date']) && !empty($params['search_date'])){
            $d = $params['search_date'];
        }
        $d_arr = explode(' - ',$d);
        $d0 = strtotime($d_arr[0].' 00:00:00');
        $d1 = strtotime($d_arr[1].' 23:59:59');

        $fields = 'u.id,u.realname,tmp.*';
        $panel_type = config('other.panel_type');
        $t = ',SUM(IF(class_type=6,TIMESTAMPDIFF(HOUR,start_time,end_time),0)) over_time ';
        foreach ($panel_type as $k=>$v){
            $t.=",sum(if(class_type={$k},1,0)) num_{$k} ";
        }

//echo $t;exit();
        $where =[
            'u.company_id'=>$cid,
            'u.role_id'=>['not in',[1,2]],
            'u.status'=>1,
            'u.is_show'=>0,
            'u.department_id'=>['>',2]
        ];

        if ($params){
            if (!empty($params['realname'])){
                $where['u.realname'] = ['like', '%'.$params['realname'].'%'];
            }
        }
        $role_id = session('admin_user.role_id');
        if ($role_id > 3){
            $where['u.id'] = session('admin_user.uid');
        }

        if (isset($params['export']) && 1 == $params['export']){
            set_time_limit(0);
            $data_list = Db::table('tb_admin_user u')->field($fields)
                ->join("(SELECT user_id{$t} FROM tb_approval WHERE cid={$cid} and status=2 and create_time between '{$d0}' and '{$d1}' GROUP BY user_id) tmp",'u.id=tmp.user_id','left')
                ->where($where)->order('over_time desc,u.id asc')->select();
            vendor('PHPExcel.PHPExcel');
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', '姓名')
                ->setCellValue('B1', '请假调休')
                ->setCellValue('C1', '报销')
                ->setCellValue('D1', '费用')
                ->setCellValue('E1', '出差')
                ->setCellValue('F1', '采购')
                ->setCellValue('G1', '加班(小时)')
                ->setCellValue('H1', '外出')
                ->setCellValue('I1', '用车')
                ->setCellValue('J1', '申领物品')
                ->setCellValue('K1', '出图')
                ->setCellValue('L1', '派遣');
//            print_r($data_list);exit();
            foreach ($data_list as $k => $v) {
                $num = $k + 2;
                $objPHPExcel->setActiveSheetIndex(0)
                    //Excel的第A列，uid是你查出数组的键值，下面以此类推
                    ->setCellValue('A' . $num, $v['realname'])
                    ->setCellValue('B' . $num, $v['num_1'])
                    ->setCellValue('C' . $num, $v['num_2'])
                    ->setCellValue('D' . $num, $v['num_3'])
                    ->setCellValue('E' . $num, $v['num_4'])
                    ->setCellValue('F' . $num, $v['num_5'])
                    ->setCellValue('G' . $num, $v['over_time'])
                    ->setCellValue('H' . $num, $v['num_7'])
                    ->setCellValue('I' . $num, $v['num_8'])
                    ->setCellValue('J' . $num, $v['num_11'])
                    ->setCellValue('K' . $num, $v['num_12'])
                    ->setCellValue('L' . $num, $v['num_13']);
            }
            $d = !empty($d) ? $d : '全部';
            $name = $d.'日常审批报表';
            $objPHPExcel->getActiveSheet()->setTitle($d);
            $objPHPExcel->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $name . '.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }

        $data_list = Db::table('tb_admin_user u')->field($fields)
            ->join("(SELECT user_id{$t} FROM tb_approval WHERE cid={$cid} and status=2 and create_time between {$d0} and {$d1} GROUP BY user_id) tmp",'u.id=tmp.user_id','left')
            ->where($where)->order('over_time desc,u.id asc')->paginate(30, false, ['query' => input('get.')]);
//        $data_list = Db::table('tb_admin_user u')->field($fields)
//        ->join("(SELECT user_id,class_type{$t} FROM tb_approval WHERE cid={$cid} and status=2 and create_time between {$d0} and {$d1} GROUP BY user_id,class_type) tmp",'u.id=tmp.user_id','left')
//            ->where($where)->buildSql();
//        print_r($data_list);exit();
        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('pages', $pages);
        $this->assign('d', $d);
        $this->assign('panel_type', $panel_type);
        return $this->fetch();
    }

}