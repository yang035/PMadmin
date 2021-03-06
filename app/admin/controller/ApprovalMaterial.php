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
use app\admin\model\ApprovalWaybill;
use app\admin\model\ApprovalLeave as LeaveModel;
use app\admin\model\ApprovalBackleave as BackleaveModel;
use app\admin\model\ApprovalExpense as ExpenseModel;
use app\admin\model\ApprovalBusiness as BusinessModel;
use app\admin\model\ApprovalProcurement as ProcurementModel;
use app\admin\model\ApprovalOvertime as OvertimeModel;
use app\admin\model\ApprovalGoout as GooutModel;
use app\admin\model\ApprovalSenduser;
use app\admin\model\ApprovalFinanceuser;
use app\admin\model\ApprovalUsecar as CarModel;
use app\admin\model\ApprovalCost as CostModel;
use app\admin\model\ApprovalDispatch as DispatchModel;
use app\admin\model\ApprovalBorrow;
use app\admin\model\AdminUser;
use app\admin\model\AssetItem as ItemModel;
use app\admin\model\ApprovalGoods;
use app\admin\model\ApprovalPrint;
use app\admin\model\DutyJob;
use app\admin\model\DutyUser;
use app\admin\model\JobItem;
use app\admin\model\Project as ProjectModel;
use app\admin\model\ApprovalReport as ApprovalReportModel;
use app\admin\model\ApprovalTixian as TixianModel;
use app\admin\model\FondPool as FondPoolModel;
use app\admin\model\ProjectBudget as BudgetModel;
use app\admin\model\ProjectBudgetcaigou as BudgetcaigouModel;
use app\admin\model\ApprovalLeaveoffice as LeaveofficeModel;
use app\admin\model\ApprovalInvoice as InvoiceModel;
use app\admin\model\UserInfo;
use app\admin\model\LeaveFile as LeaveFileModel;
use app\admin\model\LeaveList as LeaveListModel;
use app\admin\model\SubjectItem as SubjectItemModel;
use app\admin\model\MaterialPrice;
use app\admin\model\ApprovalApplypay;
use think\Db;


class ApprovalMaterial extends Admin
{
    public $tab_data = [];
    public $class_type = [];

    protected function _initialize()
    {
        parent::_initialize();
        $this->class_type = [22,23];
        $sta_count = $this->getApprovalCount();
        $tab_data['menu'] = [
            [
                'title' => "发起申请",
                'url' => 'admin/ApprovalMaterial/index',
                'params' => ['atype' => 1],
            ],
            [
                'title' => "我的申请<span class='layui-badge layui-bg-orange'>{$sta_count['user_num']}</span>",
                'url' => 'admin/ApprovalMaterial/index',
                'params' => ['atype' => 2],
            ],
            [
                'title' => "待我审批<span class='layui-badge'>{$sta_count['send_num']}</span>",
                'url' => 'admin/ApprovalMaterial/index',
                'params' => ['atype' => 3],
            ],
            [
                'title' => "抄送我的<span class='layui-badge layui-bg-orange'>{$sta_count['copy_num']}</span>",
                'url' => 'admin/ApprovalMaterial/index',
                'params' => ['atype' => 4],
            ],
            [
                'title' => "我参与的<span class='layui-badge layui-bg-orange'>{$sta_count['deal_num']}</span>",
                'url' => 'admin/ApprovalMaterial/index',
                'params' => ['atype' => 5],
            ],
            [
                'title' => "已审批<span class='layui-badge layui-bg-orange'>{$sta_count['has_num']}</span>",
                'url' => 'admin/ApprovalMaterial/index',
                'params' => ['atype' => 6],
            ],
            [
                'title' => "同行<span class='layui-badge layui-bg-orange'>{$sta_count['follow_num']}</span>",
                'url' => 'admin/ApprovalMaterial/index',
                'params' => ['atype' => 7],
            ],
            [
                'title' => "财务待审<span class='layui-badge layui-bg-orange'>{$sta_count['finance_num']}</span>",
                'url' => 'admin/ApprovalMaterial/index',
                'params' => ['atype' => 8],
            ],
            [
                'title' => "财务已审<span class='layui-badge layui-bg-orange'>{$sta_count['finance_has_num']}</span>",
                'url' => 'admin/ApprovalMaterial/index',
                'params' => ['atype' => 9],
            ],
        ];
        $tab_data['current'] = url('index', ['atype' => 1]);
        $this->tab_data = $tab_data;

        $cid = session('admin_user.cid');
        $redis = service('Redis');
        $default_user = $redis->get("pm:user:{$cid}");
        if ($default_user) {
            $user = json_decode($default_user,true);
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
//        $map['cid'] = session('admin_user.cid');
        $map['class_type'] = ['in',$this->class_type];
        $uid = session('admin_user.uid');
        $fields = "SUM(IF(user_id='{$uid}',1,0)) user_num,
        SUM(IF(JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"') and status=1 and class_type <> 11,1,0)) send_num,
        SUM(IF(JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"') and status <> 3,1,0)) copy_num,
        SUM(IF(JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"') and status <> 3,1,0)) deal_num,
        SUM(IF(JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"') and status not in (1,3),1,0)) has_num,
        SUM(IF(JSON_CONTAINS_PATH(fellow_user,'one', '$.\"$uid\"') and status <> 3,1,0)) follow_num,
        SUM(IF(JSON_CONTAINS_PATH(finance_user,'one', '$.\"$uid\"') and status=2 and finance_status=1,1,0)) finance_num,
        SUM(IF(JSON_CONTAINS_PATH(finance_user,'one', '$.\"$uid\"') and status=2 and finance_status=2,1,0)) finance_has_num";
        $count = ApprovalModel::field($fields)->where($map)->find()->toArray();
        return $count;
    }

    public function index()
    {
        $params = $this->request->param();
        $map = [];
        $d = '';
        $cid = session('admin_user.cid');
//        $map['cid'] = $cid;
        $map['class_type'] = ['in',$this->class_type];
        $panel_type1 = $panel_type = config('other.panel_type');
        foreach ($panel_type1 as $k=>$v){
            if (!in_array($k,$this->class_type)){
                unset($panel_type1[$k]);
            }
        }
        $approval_status = config('other.approval_status');
        $finance_status1 = config('other.finance_status');
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
            if (!empty($params['id'])) {
                $map['id'] = $params['id'];
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
            case 8:
                $con = "JSON_CONTAINS_PATH(finance_user,'one', '$.\"$uid\"')";
                $map['status'] = 2;
                break;
            case 9:
                $con = "JSON_CONTAINS_PATH(finance_user,'one', '$.\"$uid\"')";
                $map['status'] = 2;
                $map['finance_status'] = 2;
                break;
            default:
                $con = "";
                break;
        }
        $leave_type = config('other.leave_type');

        if (isset($params['export']) && 1 == $params['export']){
            set_time_limit(0);
            $list = ApprovalModel::where($map)->where($con)->order('id desc')->select();
//        print_r($list);exit();
            foreach ($list as $k => $v) {
                $list[$k]['send_user'] = strip_tags($this->deal_data($v['send_user']));
                $list[$k]['fellow_user'] = strip_tags($this->deal_data($v['fellow_user']));
                $list[$k]['realname'] = AdminUser::getUserById($v['user_id'])['realname'];

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
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(30);
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
                ->setCellValue('N1', '同行人')
                ->setCellValue('O1', '审批编号')
                ->setCellValue('P1', '事由');
//            print_r($data_list);exit();
            foreach ($list as $k => $v) {
                $num = $k + 2;
                $objPHPExcel->setActiveSheetIndex(0)
                    //Excel的第A列，uid是你查出数组的键值，下面以此类推
                    ->setCellValue('A' . $num, $v['realname'])
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
                    ->setCellValue('N' . $num, $v['fellow_user'])
                    ->setCellValue('O' . $num, $v['id'])
                    ->setCellValue('P' . $num, $v['reason']);
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

        $list = ApprovalModel::where($map)->where($con)->order('id desc')->paginate(30, false, ['query' => input('get.')]);

        foreach ($list as $k => $v) {
            $list[$k]['send_user'] = $this->deal_data($v['send_user']);
            $list[$k]['fellow_user'] = strip_tags($this->deal_data($v['fellow_user']));
            $list[$k]['realname'] = AdminUser::getUserById($v['user_id'])['realname'];
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
                case 22://费用
                    $child = ApprovalWaybill::where('aid',$v['id'])->find();
                    if ($child){
                        $list[$k]['money'] = $child['money'];
                    }
                    break;
                case 23://费用
                    $child = ApprovalApplypay::where('aid',$v['id'])->find();
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
            if (in_array($params['atype'], [8, 9])) {
                $list[$k]['finance_user'] = $this->deal_data($v['finance_user']);
                $list[$k]['finance_time'] = date('Y-m-d', $v['finance_time']);
            }
            $list[$k]['h'] = 1;
            $h = date('H',strtotime($v['end_time']));
            if (!in_array($h,['22','23','00','01','02','03','04','05','06']) && 6 == $v['class_type']){
                $list[$k]['h'] = 0;
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
        $this->assign('finance_status1', $finance_status1);
        $this->assign('approval_status', $approval_status);
        $this->assign('pages', $pages);
        return $this->fetch();
    }

    public function leave()
    {
        $over_time = $this->dealOvertime();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            $role_id = session('admin_user.role_id');

            if ($data['type'] == 4){
                if ($over_time <= 0){
                    return $this->error('没有调休假可用');
                }
                //限制节假日前后不能用调休假
                $day_off = [0,6,7];
                $s = $data['start_time'];
                $before_s = date('Y-m-d', strtotime("{$s} -1 day"));
                $after_s = date('Y-m-d', strtotime("{$s} +1 day"));
                if (in_array(dealDay($before_s),$day_off) || in_array(dealDay($after_s),$day_off)){
                    return $this->error("根据公司制度 {$s} 不在调休假使用范围");
                }
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
                    'reason' => $data['reason'],
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
        $this->assign('left_time',$over_time);
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
                $f = 'b.type,b.reason,b.money,b.attachment,b.payee,b.bank,b.card_num';
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
            }elseif (6 == $params['ct']){
                $table = 'tb_approval_overtime';
                $f = 'b.reason,b.time_long1,b.attachment,b.overtime_type';
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
                $overtime_type = config('other.overtime_type');
                $this->assign('overtime_type', $overtime_type);
                $this->assign('approval_status', $approval_status);
                $this->assign('list1', $list1);
            }elseif (7 == $params['ct']){
                $table = 'tb_approval_goout';
                $f = 'b.reason,b.address,b.time_long1,b.attachment';
                $map = [
                    'a.id' => $params['id']
                ];
                $fields = 'a.*,' . $f;
                $list1 = db('approval')->alias('a')->field($fields)
                    ->join("{$table} b", 'a.id = b.aid', 'left')
                    ->where($map)->find();
                $list1['attachment'] = explode(',', substr($list1['attachment'], 0, -1));
                $list1['real_name'] = AdminUser::getUserById($list1['user_id'])['realname'];
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
                $this->assign('approval_status', $approval_status);
                $this->assign('list1', $list1);
            }

            if ($this->request->isPost()) {
                $data = $this->request->post();
                if (empty($data['attachment'])){
                    return $this->error('请上传附件');
                }
                $data['amount'] = array_filter($data['amount']);

                $send_user = html_entity_decode($data['send_user']);
                $send_user1 = json_decode($send_user,true);
                $send_user1 = array_values(array_unique($send_user1, SORT_REGULAR));
                $send_user2 = [];
                foreach ($send_user1 as $k=>$v) {
                    $send_user2 += $v;
                }

                $money_relation = config('other.money_relation');
                $f_user = $this->getFlowUser4();

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

                    if (in_array($data['class_type'],$money_relation) && $f_user){
                        $approve['finance_user'] = json_encode($f_user[1]);
                    }

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

                    if (in_array($data['class_type'],$money_relation) && $f_user){
                        $fin = [];
                        foreach ($f_user[0] as $k=>$v) {
                            $fin[$k] = [
                                'aid' => $res['id'],
                                'flow_num' => $k,
                                'finance_user' => json_encode($v),
                            ];
                        }
                        $finance_user_model = new ApprovalFinanceuser();
                        $finance_user_model->saveAll($fin);
                    }

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
                if (empty($data['attachment'])){
                    return $this->error('请上传附件');
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

                $money_relation = config('other.money_relation');
                $f_user = $this->getFlowUser4();

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
                        'reason' => $data['reason'],
                        'user_id' => session('admin_user.uid'),
                        'send_user' => json_encode($send_user2),
                        'copy_user' => user_array($data['copy_user']),
                    ];

                    if (in_array($data['class_type'],$money_relation) && $f_user){
                        $approve['finance_user'] = json_encode($f_user[1]);
                    }

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

                    if (in_array($data['class_type'],$money_relation) && $f_user){
                        $fin = [];
                        foreach ($f_user[0] as $k=>$v) {
                            $fin[$k] = [
                                'aid' => $res['id'],
                                'flow_num' => $k,
                                'finance_user' => json_encode($v),
                            ];
                        }
                        $finance_user_model = new ApprovalFinanceuser();
                        $finance_user_model->saveAll($fin);
                    }

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

            $money_relation = config('other.money_relation');
            $f_user = $this->getFlowUser4();

            Db::startTrans();
            try {
                $approve = [
                    'project_id' => $data['project_id'],
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                    'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                    'time_long' => $data['time_long'],
                    'reason' => $data['reason'],
                    'user_id' => session('admin_user.uid'),
                    'send_user' => json_encode($send_user2),
                    'copy_user' => user_array($data['copy_user']),
                ];

                if (in_array($data['class_type'],$money_relation) && $f_user){
                    $approve['finance_user'] = json_encode($f_user[1]);
                }

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

                if (in_array($data['class_type'],$money_relation) && $f_user){
                    $fin = [];
                    foreach ($f_user[0] as $k=>$v) {
                        $fin[$k] = [
                            'aid' => $res['id'],
                            'flow_num' => $k,
                            'finance_user' => json_encode($v),
                        ];
                    }
                    $finance_user_model = new ApprovalFinanceuser();
                    $finance_user_model->saveAll($fin);
                }

                $leave = [
                    'aid' => $res['id'],
                    'type' => $data['type'],
                    'reason' => $data['reason'],
                    'attachment' => $data['attachment'],
                    'money' => $data['money'],
                    'payee' => $data['payee'],
                    'bank' => $data['bank'],
                    'card_num' => $data['card_num'],
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
        $uid = session('admin_user.uid');
        $bank_info = UserInfo::getRowByUid($uid);
        $bank_info['real_name'] = AdminUser::getUserById($uid)['realname'];
        $this->assign('bank_info', $bank_info);
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
                    'reason' => $data['reason'],
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
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            // 验证
            $result = $this->validate($data, 'ApprovalProcurement');
            if ($result !== true) {
                return $this->error($result);
            }
            if (!isset($data['cat_id'])){
                return $this->error('联系管理员配置资产类型');
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
                    'reason' => $data['reason'],
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

    public function dealOvertime($uid = null)
    {
        if (empty($uid)){
            $uid = session('admin_user.uid');
        }
        //计算可调休总时长
        $map1 = [
            'a.cid' => session('admin_user.cid'),
            'a.class_type' => 6,
            'a.user_id' => $uid,
            'a.status' => 2,
            'a.create_time' => ['>=',strtotime('2020-09-01')],
            'b.overtime_type' => ['in',[2,3]],
        ];
        $table1 = 'tb_approval_overtime';
        $fields = "SUM(TIMESTAMPDIFF(HOUR,a.start_time,a.end_time)) long_time";
        $list1 = db('approval')->alias('a')->field($fields)
            ->join("{$table1} b", 'a.id = b.aid', 'left')
            ->where($map1)->find();
        if (!$list1['long_time']){
            $list1['long_time'] = 0;
        }

        //计算已用调休假
        $map2 = [
            'a.cid' => session('admin_user.cid'),
            'a.class_type' => 1,
            'a.user_id' => $uid,
            'a.status' => 2,
            'a.create_time' => ['>=',strtotime('2020-09-01')],
            'b.type' => 4,
        ];
        $table2 = 'tb_approval_leave';
        $fields = "TIMESTAMPDIFF(HOUR,a.start_time,a.end_time) long_time,a.start_time,a.end_time";
        $list2 = db('approval')->alias('a')->field($fields)
            ->join("{$table2} b", 'a.id = b.aid', 'left')
            ->where($map2)->select();
        if ($list2){
            foreach ($list2 as $k=>$v) {
                $start_time = explode(' ',$v['start_time'])[0];
                $end_time = explode(' ',$v['end_time'])[0];
                $sub = (strtotime($end_time) - strtotime($start_time))/86400;
                if ($sub > 0){
                    $list2[$k]['long_time'] = 8*($sub+1);
                }
            }
        }
        $list2['long_time'] = array_sum(array_column($list2,'long_time'));
        if (!$list2){
            $list2['long_time'] = 0;
        }
        //计算剩余可用调休假
        $left_hour = $list1['long_time'] - $list2['long_time'];
        $other_hour = [
            8 => 2,
            52 => 57.5,
            63 => 38,
            86 => 44.5,
            100 => 6,
            13 => 14,
            49 => 31,
            113 => 0.5,
            109 => 4,
            95 => 28.5,
            36 => 20.5,
            142 => 12,
            31 => 3,
            54 => 12,
            105 => 19,
            119 => 96,
            440 => 32,
            439 => 16,
            16 => 4,
        ];
        if (key_exists($uid,$other_hour)){
            $left_hour += $other_hour[$uid];
        }
        return $left_hour;
    }

    public function overtime()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            if (empty($data['overtime_type'])){
                return $this->error('请选择加班类型');
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
                    'reason' => $data['reason'],
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
                    'time_long1' => $c,
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
                    'reason' => $data['reason'],
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
                    'reason' => $data['reason'],
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
                    'reason' => $data['reason'],
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

    public function getOfficeGoodKuCun($id){
        $row = \app\admin\model\Goods::getRowById($id,'total,sales');
        $kucun = (int)($row['total']-$row['sales']);
        return json($kucun > 0 ? $kucun : 0);
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
                    'reason' => $data['reason'],
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
            if (!isset($data['store_id'])){
                return $this->error('请选择或先配置图文公司');
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
                    'reason' => $data['reason'],
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
        $this->assign('store_option', \app\admin\model\GraphicCompany::getOption());
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
                    'reason' => $data['reason'],
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
                    'reason' => $data['reason'],
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

    public function getBudget($project_id){
        if (isset($project_id)){
            $data = BudgetModel::getName(0,$project_id,true);
            echo json_encode($data);
        }else{
            echo '';
        }
    }

    public function signBills()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
//            if (empty($data['shigong_user'])){
//                return $this->error('施工员不存在，请输入正确姓名');
//            }
//            $data['content'] = array_unique(array_filter($data['content']));
            $data['content'] = array_filter($data['content']);
            // 验证
            $result = $this->validate($data, 'ApprovalBills');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);

            $send_user = html_entity_decode($data['send_user']);
            $send_user1 = json_decode($send_user,true);
//            array_unshift($send_user1,[$data['shigong_user']=>'']);//在头部插入元素
//            array_pop($send_user1);//删除尾部的元素
//            $send_user2 = [];
//            foreach ($send_user1 as $k=>$v) {
//                $send_user2 += $v;
//            }
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
                    'reason' => $data['reason'],
                    'user_id' => session('admin_user.uid'),
//                    'send_user' => user_array($data['send_user']),
                    'send_user' => json_encode($send_user2),
                    'copy_user' => user_array($data['copy_user']),
                ];
                $res = ApprovalModel::create($approve);

                $su = [];
//            $send_user1 = [$send_user1];
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
//                    'shigong_user' => $data['shigong_user'],
                    'reason' => $data['reason'],
                    'attachment' => $data['attachment'],
                ];
//                $leave['money'] = 0;
                if ($data['content']) {
                    foreach ($data['content'] as $k => $v) {
                        $leave['detail'][$k]['content'] = $v;
                        $leave['detail'][$k]['num'] = !empty($data['num'][$k]) ? $data['num'][$k] : 0;
                        $leave['detail'][$k]['unit'] = !empty($data['unit'][$k]) ? $data['unit'][$k] : 1;
                        $leave['detail'][$k]['per_price'] = !empty($data['per_price'][$k]) ? $data['per_price'][$k] : 0;
//                        $leave['money'] += $leave['detail'][$k]['num']*$leave['detail'][$k]['per_price'];
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

    public function waybill()
    {
        $uid = session('admin_user.uid');
        $cid = session('admin_user.cid');
        $mytask = MaterialPrice::getProjectByCompany($cid);
        if (!$mytask){
            return $this->error('请联系对方项目部或公司进行入库操作');
        }

        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            if (empty($data['shigong_user'])){
                return $this->error('请选择采购员');
            }
//            $data['content'] = array_unique(array_filter($data['content']));
            $data['content'] = array_filter($data['content']);
            $w = [
                'id' => ['in',$data['content']]
            ];
            $m_p = MaterialPrice::where($w)->select();
            $m_p_arr = $detail = [];
            if ($m_p){
                foreach ($m_p as $k=>$v){
                    $m_p_arr[$v['id']] = $v;
                }
            }
            if ($data['content']) {
                foreach ($data['content'] as $k => $v) {
                    $detail[$k]['m_id'] = $v;
                    $detail[$k]['content'] = $m_p_arr[$v]['name'];
                    $detail[$k]['num'] = !empty($data['num'][$k]) ? $data['num'][$k] : 0;
                    $detail[$k]['unit'] = $m_p_arr[$v]['unit'];
                    $detail[$k]['per_price'] = !empty($data['per_price'][$k]) ? $data['per_price'][$k] : 0;
                    $detail[$k]['caigou_danjia'] = $m_p_arr[$v]['caigou_danjia'];
                }
            }
            // 验证
            $result = $this->validate($data, 'ApprovalWaybill');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);

            $send_user = html_entity_decode($data['send_user']);
            $send_user1 = json_decode($send_user,true);
            array_unshift($send_user1,[$data['shigong_user']=>'']);//在头部插入元素
//            array_pop($send_user1);//删除尾部的元素
            $send_user2 = [];
            foreach ($send_user1 as $k=>$v) {
                $send_user2 += $v;
            }
//            $send_user1 = array_values(array_unique($send_user1, SORT_REGULAR));
//            $send_user2 = [];
//            foreach ($send_user1 as $k=>$v) {
//                $send_user2 += $v;
//            }

            Db::startTrans();
            try {
                $approve = [
                    'project_id' => $data['project_id'],
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                    'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                    'time_long' => $data['time_long'],
                    'reason' => $data['reason'],
                    'user_id' => $uid,
//                    'send_user' => user_array($data['send_user']),
                    'send_user' => json_encode($send_user2),
                    'copy_user' => user_array($data['copy_user']),
                ];
                $res = ApprovalModel::create($approve);

                $su = [];
//            $send_user1 = [$send_user1];
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
                    'detail' => json_encode($detail),
                ];
                $flag = ApprovalWaybill::create($leave);
                $this->insertScore('日常审批[送货单]填写');
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
        $this->assign('mytask', $mytask);
        $this->assign('unit_option', ApprovalWaybill::getUnitOption());
        return $this->fetch();
    }

    public function getMaterialList($project_id,$company_id=0,$id = 0){
        if (!$company_id){
            $company_id = session('admin_user.cid');
        }
        $list = MaterialPrice::getMaterialList($project_id,$company_id,$id);
        return json($list);
    }

    public function getShigongUser($project_id){
        $list = ProjectModel::getShigongUser($project_id);
        return json($list);
    }

    public function applyPay()
    {
        $params = $this->request->param();
        if (isset($params['id']) && !empty($params['id'])) {
            $list1 = [];
            if (22 == $params['ct']) {
                $table = 'tb_approval_waybill';
                $f = 'b.reason,b.date,b.detail,b.money,b.shigong_user,b.attachment';
                $map = [
                    'a.id' => $params['id']
                ];
                $fields = 'a.*,' . $f;
                $list1 = db('approval')->alias('a')->field($fields)
                    ->join("{$table} b", 'a.id = b.aid', 'left')
                    ->where($map)->find();
                $list1['attachment'] = explode(',', substr($list1['attachment'], 0, -1));
                $list1['real_name'] = AdminUser::getUserById($list1['user_id'])['realname'];
                $list1['shigong_user'] = AdminUser::getUserById($list1['shigong_user'])['realname'];
                $list1['send_user'] = $this->deal_data($list1['send_user']);
                $list1['copy_user'] = $this->deal_data($list1['copy_user']);
                $list1['detail'] = json_decode($list1['detail'], true);
                if ($list1['project_id']) {
                    $project_data = ProjectModel::getRowById($list1['project_id']);
                } else {
                    $project_data = [
                        'name' => '其他',
                    ];
                }
//print_r($list1);exit();
                $unit2_type = config('other.unit2');
                $this->assign('unit_type', $unit2_type);
                $list1['project_name'] = $project_data['name'];
                $approval_status = config('other.approval_status');
                $unit2_type = config('other.unit2');
                $this->assign('unit_type', $unit2_type);
                $this->assign('approval_status', $approval_status);
                $this->assign('list1', $list1);
            }

            if ($this->request->isPost()) {
                $data = $this->request->post();

                $send_user = html_entity_decode($data['send_user']);
                $send_user1 = json_decode($send_user, true);
                $send_user1 = array_values(array_unique($send_user1, SORT_REGULAR));
                $send_user2 = [];
                foreach ($send_user1 as $k => $v) {
                    $send_user2 += $v;
                }

                $money_relation = config('other.money_relation');
                $f_user = $this->getFlowUser4();

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

                    if (in_array($data['class_type'],$money_relation) && $f_user){
                        $approve['finance_user'] = json_encode($f_user[1]);
                    }

//                print_r($approve);exit();
                    $res = ApprovalModel::create($approve);

                    $su = [];
                    foreach ($send_user1 as $k => $v) {
                        $su[$k] = [
                            'aid' => $res['id'],
                            'flow_num' => $k,
                            'send_user' => json_encode($v),
                        ];
                    }
                    $send_user_model = new ApprovalSenduser();
                    $send_user_model->saveAll($su);

                    if (in_array($data['class_type'],$money_relation) && $f_user){
                        $fin = [];
                        foreach ($f_user[0] as $k=>$v) {
                            $fin[$k] = [
                                'aid' => $res['id'],
                                'flow_num' => $k,
                                'finance_user' => json_encode($v),
                            ];
                        }
                        $finance_user_model = new ApprovalFinanceuser();
                        $finance_user_model->saveAll($fin);
                    }

                    $leave = [
                        'aid' => $res['id'],
                        'a_aid' => $list1['id'],
                        'total' => $data['total'],
                        'per' => $data['per'],
                        'money' => round($data['total']*$data['per']/100,2),
                        'reason' => $list1['reason'],
                        'attachment' => $data['attachment'],
                    ];
                    $flag = ApprovalApplypay::create($leave);
                    $this->insertScore('日常审批[申请支付]填写');
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
            return $this->fetch('apply_pay1');
        } else {
            $cid = session('admin_user.cid');
            $mytask = MaterialPrice::getProjectByCompany($cid);
            if (!$mytask){
                return $this->error('暂没有合作项目');
            }
            if ($this->request->isPost()) {
                $data = $this->request->post();
                if ('' == $data['project_id']) {
                    return $this->error('请选择项目');
                }
                if (empty($data['attachment'])) {
                    return $this->error('请上传附件');
                }
                $money = round($data['total']*$data['per']/100,2);
                if ($money > $data['total']){
                    return $this->error('支付金额不能超过剩余金额');
                }
                if ($data['money'] > $money || $data['money'] > $data['total']){
                    return $this->error('支付金额有误');
                }
                if (empty($data['shigong_user'])){
                    return $this->error('请选择采购');
                }
                // 验证
                $result = $this->validate($data, 'ApprovalExpense');
                if ($result !== true) {
                    return $this->error($result);
                }
                unset($data['id']);

                $send_user = html_entity_decode($data['send_user']);
                $send_user1 = json_decode($send_user, true);
                array_unshift($send_user1,[$data['shigong_user']=>'']);//在头部插入元素
//                $send_user1 = array_values(array_unique($send_user1, SORT_REGULAR));
                $send_user2 = [];
                foreach ($send_user1 as $k => $v) {
                    $send_user2 += $v;
                }

                $money_relation = config('other.money_relation');
                $f_user = $this->getFlowUser4();

                // 启动事务
                Db::startTrans();
                try {
                    $approve = [
                        'project_id' => $data['project_id'],
                        'class_type' => $data['class_type'],
                        'cid' => session('admin_user.cid'),
                        'start_time' => date('Y-m-d H:i:s'),
                        'end_time' => date('Y-m-d H:i:s'),
                        'time_long' => 0,
                        'user_id' => session('admin_user.uid'),
                        'send_user' => json_encode($send_user2),
                        'copy_user' => user_array($data['copy_user']),
                    ];

                    if (in_array($data['class_type'],$money_relation) && $f_user){
                        $approve['finance_user'] = json_encode($f_user[1]);
                    }

                    $res = ApprovalModel::create($approve);


                    $su = [];
                    foreach ($send_user1 as $k => $v) {
                        $su[$k] = [
                            'aid' => $res['id'],
                            'flow_num' => $k,
                            'send_user' => json_encode($v),
                        ];
                    }
                    $send_user_model = new ApprovalSenduser();
                    $send_user_model->saveAll($su);

                    if (in_array($data['class_type'],$money_relation) && $f_user){
                        $fin = [];
                        foreach ($f_user[0] as $k=>$v) {
                            $fin[$k] = [
                                'aid' => $res['id'],
                                'flow_num' => $k,
                                'finance_user' => json_encode($v),
                            ];
                        }
                        $finance_user_model = new ApprovalFinanceuser();
                        $finance_user_model->saveAll($fin);
                    }

                    $leave = [
                        'aid' => $res['id'],
                        'total' => $data['total'],
                        'per' => $data['per'],
                        'shigong_user' => $data['shigong_user'],
                        'money' => $money,
                        'reason' => $data['reason'],
                        'attachment' => $data['attachment'],
                    ];
                    $flag = ApprovalApplypay::create($leave);
                    $this->insertScore('日常审批[申请支付]填写');
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
            $this->assign('mytask', $mytask);
            return $this->fetch();
        }
    }

    public function getMoneyByProject($project_id){
         $money = ApprovalModel::getMoneyByProject($project_id);
         return json($money);
    }

    public function tixian()
    {
        $pool = FondPoolModel::getSta();
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ($data['money'] > $pool['no_tixian']){
                return $this->error('超出可提现范围');
            }
//            if ('' == $data['project_id']){
//                return $this->error('请选择项目');
//            }
            // 验证
            $result = $this->validate($data, 'ApprovalTixian');
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

            $money_relation = config('other.money_relation');
            $f_user = $this->getFlowUser4();

            // 启动事务
            Db::startTrans();
            try {
                $approve = [
//                    'project_id' => $data['project_id'],
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                    'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                    'time_long' => $data['time_long'],
                    'reason' => $data['reason'],
                    'user_id' => session('admin_user.uid'),
                    'send_user' => json_encode($send_user2),
                    'copy_user' => user_array($data['copy_user']),
                ];

                if (in_array($data['class_type'],$money_relation) && $f_user){
                    $approve['finance_user'] = json_encode($f_user[1]);
                }

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

                if (in_array($data['class_type'],$money_relation) && $f_user){
                    $fin = [];
                    foreach ($f_user[0] as $k=>$v) {
                        $fin[$k] = [
                            'aid' => $res['id'],
                            'flow_num' => $k,
                            'finance_user' => json_encode($v),
                        ];
                    }
                    $finance_user_model = new ApprovalFinanceuser();
                    $finance_user_model->saveAll($fin);
                }

                $leave = [
                    'aid' => $res['id'],
                    'money' => $data['money'],
                    'reason' => $data['reason'],
                    'attachment' => $data['attachment'],
                ];
                $flag = TixianModel::create($leave);
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

        $chain_user = $this->getFlowUser1();
        $this->assign('chain_user', $chain_user);
        $this->assign('send_user', htmlspecialchars($chain_user['manager_user']));
        $this->assign('leave_type', LeaveModel::getOption());
        $this->assign('pool', $pool);
        return $this->fetch();
    }

    public function leaveOffice()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'ApprovalLeaveoffice');
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
//            if ((strtotime($end_time) - strtotime($start_time) <= 24*3600) && count($send_user1) > 2){
//                array_pop($send_user1);
//            }
            $send_user2 = [];
            foreach ($send_user1 as $k=>$v) {
                $send_user2 += $v;
            }

            // 启动事务
            Db::startTrans();
            try {
                $approve = [
                    'project_id' => 0,
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                    'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                    'time_long' => $data['time_long'],
                    'reason' => $data['reason'],
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
                    'reason' => $data['reason'],
                    'attachment' => $data['attachment'],
                ];
                $flag = LeaveofficeModel::create($leave);
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

        $chain_user = $this->getFlowUser2();
        $this->assign('chain_user', $chain_user);
        $this->assign('send_user', htmlspecialchars($chain_user['manager_user']));
        $this->assign('leave_type', LeaveModel::getOption());
        return $this->fetch();
    }

    public function Invoice()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if ('' == $data['project_id']){
                return $this->error('请选择项目');
            }
            // 验证
            $result = $this->validate($data, 'ApprovalInvoice');
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
                    'reason' => $data['reason'],
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

                $invoice = [
                    'aid' => $res['id'],
                    'reason' => $data['reason'],
                    'name' => $data['name'],
                    'identity_number' => $data['identity_number'],
                    'address' => $data['address'],
                    'bank' => $data['bank'],
                    'card_num' => $data['card_num'],
                    'type' => $data['type'],
                    'money' => $data['money'],
                    'contract_number' => $data['contract_number'],
                    'total_money' => $data['total_money'],
                    'has_money' => $data['has_money'],
                    'infomation' => $data['infomation'],
                    'attachment' => $data['attachment'],
                ];
                $flag = InvoiceModel::create($invoice);
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
        $this->assign('cost_option', InvoiceModel::getOption());
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
                $f = 'b.type,b.reason,b.money,b.attachment,b.payee,b.bank,b.card_num';
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
            case 17:
                $table = 'tb_approval_tixian';
                $f = 'b.money,b.reason,b.attachment';
                break;
            case 18:
                $table = 'tb_approval_leaveoffice';
                $f = 'b.reason,b.attachment';
                break;
            case 19:
                $table = 'tb_approval_invoice';
                $f = 'b.reason,b.name,b.identity_number,b.address,b.bank,b.card_num,b.type,b.money,b.contract_number,b.total_money,b.has_money,b.infomation,b.attachment';
                break;
            case 20:
                $table = 'tb_approval_waybill';
                $f = 'b.reason,b.date,b.detail,b.money,b.shigong_user,b.attachment';
                break;
            case 21:
                $table = 'tb_approval_applypay';
                $f = 'b.a_aid,b.per,b.reason,b.money,b.total,b.attachment';
                break;
            case 22:
                $table = 'tb_approval_waybill';
                $f = 'b.reason,b.date,b.detail,b.money,b.shigong_user,b.attachment';
                break;
            case 23:
                $table = 'tb_approval_applypay';
                $f = 'b.a_aid,b.per,b.reason,b.money,b.total,b.attachment';
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
        $this->assign('su_list', $su_list);
        $this->assign('status', $status);

        $fin_list = ApprovalFinanceuser::where($map1)->select();
        $fin_list_count = count($fin_list);
        $finance_status = [];
        if ($fin_list){
            foreach ($fin_list as $k=>$v) {
                $fin_list[$k]['finance_user_id'] = array_keys(json_decode($v['finance_user'],true));
                $fin_list[$k]['finance_user'] = $this->deal_data($v['finance_user']);
                $fin_list[$k]['cunzai'] = false;
                if (in_array(session('admin_user.uid'),$fin_list[$k]['finance_user_id'])){
                    $fin_list[$k]['cunzai'] = true;
                }
                $finance_status[$v['flow_num']] = $v['finance_status'];
                if ($v['flow_num'] == 0){
                    $finance_status[-1] = 2;
                }
            }
        }else{
            $fin_list = [];
        }

//        print_r($su_list);
        $this->assign('fin_list', $fin_list);
        $this->assign('finance_status', $finance_status);
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
                        if (in_array($data['class_type'],$this->class_type) && 1 == $data['is_verify']){
                            $last['status'] = 1;

                            $s_u = [
                                'aid' => $data['id'],
                                'flow_num' => $last['flow_num']+1,
                                'send_user' => json_encode([$data['verify_user']=>'']),
                            ];
                            ApprovalSenduser::create($s_u);
                            $d = [
                                'id' => $data['id'],
                                'is_verify' => $data['is_verify'],
                            ];
                            $send_user = json_decode($list['send_user'],true);
                            $send_user[$uid] = 'a';
                            $send_user[$data['verify_user']] = '';
                            $d['send_user'] = json_encode($send_user);
                            if (1 == $list['is_verify']){
                                $verify_user = json_decode($list['verify_user'],true);
                                array_push($verify_user,$data['verify_user']);
                                $d['verify_user'] = json_encode($verify_user);
                            }else{
                                $d['verify_user'] = json_encode([$data['verify_user']]);
                            }
                            ApprovalModel::update($d);
                        }
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

                            if (17 == $data['class_type'] && 2 == $data['status']){
                                $fond_data = [
                                    'cid' => session('admin_user.cid'),
                                    'user' => $list['user_id'],
                                    'sub_fond' => $list['money'],
                                    'remark' => $list['reason'],
                                    'user_id' => session('admin_user.uid'),
                                ];
                                FondPoolModel::create($fond_data);
                            }

                            if (16 == $data['class_type'] && 2 == $data['status']){
                                $detail = json_decode($list['detail'], true);
                                if ($detail){
                                    $budgetcaigou = [];
                                    foreach ($detail as $k=>$v){
                                        $budgetcaigou[$k] = [
                                            'cid' => session('admin_user.cid'),
                                            'project_id' => $list['project_id'],
                                            'name' => $v['content'],
                                            'caigou_danjia' => $v['per_price'],
                                            'caigou_shuliang' => $v['num'],
                                            'caigou_zongjia' => round($v['per_price']*$v['num'],2),
                                            'user' => $list['user_id'],
                                            'user_id' => session('admin_user.uid'),
                                            'create_time' => time(),
                                            'update_time' => time(),
                                        ];
                                    }
                                    \db('project_budgetcaigou')->insertAll($budgetcaigou);
                                }
                            }

                            if (22 == $data['class_type'] && 2 == $data['status'] && 0 == $data['is_verify']){
                                $detail = json_decode($list['detail'], true);
                                if ($detail){
                                    $material_dan = [];
                                    foreach ($detail as $k=>$v){
                                        $material_dan[$k] = [
                                            'cid' => session('admin_user.cid'),
                                            'project_id' => $list['project_id'],
                                            'm_p_id' => $v['m_id'],
                                            'name' => $v['content'],
                                            'unit' => $v['unit'],
                                            'per_price' => $v['per_price'],
                                            'caigou_danjia' => $v['caigou_danjia'],
                                            'caigou_shuliang' => $v['num'],
                                            'caigou_zongjia' => round($v['per_price']*$v['num'],2),
                                            'user' => $list['user_id'],
                                            'user_id' => session('admin_user.uid'),
                                            'create_time' => time(),
                                            'update_time' => time(),
                                        ];
                                    }
                                    \db('material_dan')->insertAll($material_dan);
                                }
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

                        if (17 == $data['class_type'] && 2 == $data['status']){
                            $fond_data = [
                                'cid' => session('admin_user.cid'),
                                'user' => $list['user_id'],
                                'sub_fond' => $list['money'],
                                'remark' => $list['reason'],
                                'user_id' => session('admin_user.uid'),
                            ];
                            FondPoolModel::create($fond_data);
                        }

                        if (16 == $data['class_type'] && 2 == $data['status']){
                            $detail = json_decode($list['detail'], true);
                            if ($detail){
                                $budgetcaigou = [];
                                foreach ($detail as $k=>$v){
                                    $budgetcaigou[$k] = [
                                        'cid' => session('admin_user.cid'),
                                        'project_id' => $list['project_id'],
                                        'name' => $v['content'],
                                        'caigou_danjia' => $v['per_price'],
                                        'caigou_shuliang' => $v['num'],
                                        'caigou_zongjia' => round($v['per_price']*$v['num'],2),
                                        'user' => $list['user_id'],
                                        'user_id' => session('admin_user.uid'),
                                        'create_time' => time(),
                                        'update_time' => time(),
                                    ];
                                }
                                \db('project_budgetcaigou')->insertAll($budgetcaigou);
                            }
                        }

                        if (22 == $data['class_type'] && 2 == $data['status'] && 0 == $data['is_verify']){
                            $detail = json_decode($list['detail'], true);
                            if ($detail){
                                $material_dan = [];
                                foreach ($detail as $k=>$v){
                                    $material_dan[$k] = [
                                        'cid' => session('admin_user.cid'),
                                        'project_id' => $list['project_id'],
                                        'm_p_id' => $v['m_id'],
                                        'name' => $v['content'],
                                        'unit' => $v['unit'],
                                        'per_price' => $v['per_price'],
                                        'caigou_danjia' => $v['caigou_danjia'],
                                        'caigou_shuliang' => $v['num'],
                                        'caigou_zongjia' => round($v['per_price']*$v['num'],2),
                                        'user' => $list['user_id'],
                                        'user_id' => session('admin_user.uid'),
                                        'create_time' => time(),
                                        'update_time' => time(),
                                    ];
                                }
                                \db('material_dan')->insertAll($material_dan);
                            }
                        }
                    }

                        // 提交事务
                        Db::commit();
                    } catch (\Exception $e) {
                        // 回滚事务
                        Db::rollback();
                    }
                }elseif (8 == $data['atype']) {
//                    print_r($data);exit();
                    //事务提交，保证数据一致性
                    Db::startTrans();
                    try {
                        $uid = session('admin_user.uid');
                        $ap = [
                            'id'=>$data['id'],
                            'finance_status'=>$data['finance_status'],
                            'finance_mark'=>$data['finance_mark'],
                            'finance_time'=>time(),
                        ];
                        if (!empty($fin_list)) {
                            $w = [
                                'aid' => $data['id'],
                                'finance_status' => 1,
                            ];
                            $w1 = "JSON_CONTAINS_PATH(finance_user,'one', '$.\"$uid\"')";

                            $u = [
                                'finance_status' => $data['finance_status'],
                                'finance_mark' => $data['finance_mark'],
                                'update_time' => time(),
                            ];

                            $s = new ApprovalFinanceuser();
                            $s->where($w)->where($w1)->update($u);

                            $sql = "UPDATE tb_approval_financeuser SET finance_user = JSON_REPLACE(finance_user, '$.\"{$uid}\"', 'a') WHERE aid ={$data['id']} and finance_status={$data['finance_status']}";
                            $res = ApprovalFinanceuser::execute($sql);

                            $sql = "UPDATE tb_approval SET finance_user = JSON_SET(finance_user, '$.\"{$uid}\"', 'a'),finance_time=UNIX_TIMESTAMP() WHERE id ={$data['id']}";
                            $res = ApprovalModel::execute($sql);

                            $last = ApprovalFinanceuser::where($map1)->order('id desc')->limit(1)->find();
//                    print_r($last);exit();
                            if (2 == $last['finance_status'] || 2 != $data['finance_status']) {
                                $res = ApprovalModel::update($ap);
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

            $w = [
                'job_id'=>session('admin_user.job_item'),
                'duty_id'=>2,
            ];
            $num_arr = DutyJob::field('num')->where($w)->find();
            if ($num_arr){
                $num = $num_arr['num'];
            }else{
                $num = 0;
            }
            $duty_user = [
                'cid'=>session('admin_user.cid'),
                'job_id'=>session('admin_user.job_item'),
                'duty_id'=>2,
                'num'=>$num,
                'times'=>1,
                'url'=>$_SERVER['HTTP_REFERER'],
                'remark'=>'审批次数记录',
                'user_id'=>session('admin_user.uid'),
                'create_time'=> date('Y-m-d H:i:s'),
                'update_time'=> date('Y-m-d H:i:s'),
            ];

            DutyUser::create($duty_user);

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
                        $f = 'b.type,b.reason,b.money,b.attachment,b.payee,b.bank,b.card_num';
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
                    }elseif (!empty($list['a_aid']) && $ct[0] == 6){
                        $table = 'tb_approval_overtime';
                        $f = 'b.reason,b.time_long1,b.attachment,b.overtime_type';
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
                        $overtime_type = config('other.overtime_type');
                        $this->assign('overtime_type', $overtime_type);
                    }elseif (!empty($list['a_aid']) && $ct[0] == 7){
                        $table = 'tb_approval_goout';
                        $f = 'b.reason,b.address,b.time_long1,b.attachment';
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
                $store_type = \app\admin\model\GraphicCompany::getOption1();
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
//                $list['shigong_user'] = AdminUser::getUserById($list['shigong_user'])['realname'];
                $unit2_type = config('other.unit2');
                $this->assign('unit_type', $unit2_type);
                break;
            case 17:
                break;
            case 18:
                break;
            case 19:
                $cost_type = config('other.invoice_type');
                $this->assign('invoice_type', $cost_type);
                break;
            case 20:
                $list['detail'] = json_decode($list['detail'], true);
                $list['shigong_user'] = AdminUser::getUserById($list['shigong_user'])['realname'];
                $unit2_type = config('other.unit2');
                $this->assign('unit_type', $unit2_type);
                break;
            case 21:
                $ct = ApprovalModel::where('id',$list['a_aid'])->column('class_type');
                if ($ct){
                    if (!empty($list['a_aid']) && $ct[0] == 20){
                        $table = 'tb_approval_waybill';
                        $f = 'b.reason,b.date,b.detail,b.money,b.shigong_user,b.attachment';
                        $map = [
                            'a.id' => $list['a_aid']
                        ];
                        $fields = 'a.*,' . $f;
                        $list1 = db('approval')->alias('a')->field($fields)
                            ->join("{$table} b", 'a.id = b.aid', 'left')
                            ->where($map)->find();
                        $list1['attachment'] = explode(',', substr($list1['attachment'], 0, -1));
                        $list1['real_name'] = AdminUser::getUserById($list1['user_id'])['realname'];
                        $list1['shigong_user'] = AdminUser::getUserById($list1['shigong_user'])['realname'];
                        $list1['deal_user'] = $this->deal_data($list1['deal_user']);
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

                $this->assign('ct', $ct[0]);
                $this->assign('list1', $list1);
                break;
            case 22:
                $list['detail'] = json_decode($list['detail'], true);
                $list['shigong_user'] = AdminUser::getUserById($list['shigong_user'])['realname'];
                $unit2_type = config('other.unit2');
                $this->assign('unit_type', $unit2_type);
                break;
            case 23:
                $ct = ApprovalModel::where('id',$list['a_aid'])->column('class_type');
                if ($ct){
                    if (!empty($list['a_aid']) && $ct[0] == 22){
                        $table = 'tb_approval_waybill';
                        $f = 'b.reason,b.date,b.detail,b.money,b.shigong_user,b.attachment';
                        $map = [
                            'a.id' => $list['a_aid']
                        ];
                        $fields = 'a.*,' . $f;
                        $list1 = db('approval')->alias('a')->field($fields)
                            ->join("{$table} b", 'a.id = b.aid', 'left')
                            ->where($map)->find();

                        $list1['attachment'] = explode(',', substr($list1['attachment'], 0, -1));
                        $list1['real_name'] = AdminUser::getUserById($list1['user_id'])['realname'];
                        $list1['shigong_user'] = AdminUser::getUserById($list1['shigong_user'])['realname'];
                        $list1['deal_user'] = $this->deal_data($list1['deal_user']);
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

                $this->assign('ct', $ct[0]);
                $this->assign('list1', $list1);
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
        $finance_status1 = config('other.finance_status');
        $this->assign('data_list', $list);
        $this->assign('approval_status', $approval_status);
        $this->assign('finance_status1', $finance_status1);
        $this->assign('class_type', $params['class_type']);
        $this->assign('project_name', $project_data);
        $this->assign('select_user', AdminUser::selectUser());
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
        if ($role_id > 4){
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
        $items = $data_list->items();
        foreach ($items as $k => $v){
            $items[$k]['left_time'] = $this->dealOvertime($v['id']);
        }
        // 分页
        $pages = $data_list->render();
        $this->assign('data_list', $data_list);
        $this->assign('items',$items);
        $this->assign('pages', $pages);
        $this->assign('d', $d);
        $this->assign('panel_type', $panel_type);
        return $this->fetch();
    }

    public function certificate()
    {
        $params = $this->request->param();

        $table = 'tb_approval_leaveoffice';
        $f = 'b.reason,b.attachment,b.qrcode_url';
        $map = [
            'a.id' => $params['id']
        ];
        $fields = 'a.*,' . $f;
        $list = db('approval')->alias('a')->field($fields)
            ->join("{$table} b", 'a.id = b.aid', 'left')
            ->where($map)->find();
        if (isset($params['end_date'])){
            db('user_info')->where('user_id',$list['user_id'])->update(['end_date'=>$params['end_date']]);
        }

        $data = [];
        if ($list){
            $where = [
                'cid'=>session('admin_user.cid'),
                'user'=>$list['user_id'],
            ];
            $flag = LeaveFileModel::where($where)->find();
            if (!$flag){
                return $this->error('请人事先进行离职数据归档');
            }

            $data = db('admin_user')->alias('a')->field('a.id,a.realname,a.job_item,b.idcard,b.start_date,b.end_date')
                ->join("tb_user_info b", 'a.id = b.user_id', 'left')
                ->where('a.id',$list['user_id'])->find();
            if (strtotime($data['start_date']) > strtotime($data['end_date'])){
                $this->assign('data_info',$data);
                return $this->fetch('step1');
            }
            $job = JobItem::getItem1();
            $data['job_name'] = isset($job[$data['job_item']]) ? $job[$data['job_item']] : '无';

            $fields = "COUNT(DISTINCT subject_id) as num,SUM(ml_add_score) AS ml_add_sum,SUM(gl_add_score) AS gl_add_sum";
            $score = \app\admin\model\Score::field($fields)->where('user',$list['user_id'])->find();
            $this->assign('score',$score);

            if ($list['qrcode_url']){
                $qcode_url = $list['qrcode_url'];
            }else{
                $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                $qcode_url = scerweima1($url);
                LeaveofficeModel::where('aid',$list['id'])->update(['qrcode_url'=>$qcode_url]);
            }
        }
        $this->assign('qcode_png',$qcode_url);
        $this->assign('data_info',$data);
        return $this->fetch();
    }

    public function ExpenseReport()
    {
        $params = $this->request->param();
        if ($this->request->isAjax()) {
            $where = $data = [];
            $page = input('param.page/d', 1);
            $limit = input('param.limit/d', 30);

            $where['a.cid'] = session('admin_user.cid');
            if (isset($params['project_id']) && !empty($params['project_id'])) {
                $where['a.project_id'] = $params['project_id'];
            }
            $where['a.status'] = 2;
            $myPro = ProjectModel::getProTask(0, 0);
            $fields = 'a.project_id,SUM(c.money) total';
            $data['data'] = \db('approval')->alias('a')->field($fields)
                ->join("tb_approval_cost c", 'a.id = c.aid', 'right')
                ->where($where)->group('a.project_id')->order('total desc')
                ->page($page)->limit($limit)->select();

            foreach ($data['data'] as $k => $v) {
                $data['data'][$k]['project_name'] = isset($myPro[$v['project_id']]) ? $myPro[$v['project_id']] : '其他';
            }
            $data['count'] = \db('approval')->alias('a')->field($fields)
                ->join("tb_approval_cost c", 'a.id = c.aid', 'right')
                ->where($where)->group('a.project_id')->order('total desc')->count();
            $data['code'] = 0;
            $data['msg'] = '';
            return json($data);
        }
        $this->assign('project_select', ProjectModel::getMyTask());
        return $this->fetch();
    }

    public function reportDetail()
    {
        $params = $this->request->param();
        $where = $data = [];

        $where['cid'] = session('admin_user.cid');
        if (isset($params['project_id']) && !empty($params['project_id'])) {
            $where['project_id'] = $params['project_id'];
        }
        $where['status'] = 2;
        $where['class_type'] = 3;
        $myPro = ProjectModel::getProTask(0, 0);
        $list = ApprovalModel::where($where)->order('create_time desc')->paginate(30, false, ['query' => input('get.')]);

        foreach ($list as $k => $v) {
            $list[$k]['send_user'] = $this->deal_data($v['send_user']);
            $list[$k]['fellow_user'] = strip_tags($this->deal_data($v['fellow_user']));
            $list[$k]['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];
            $list[$k]['money'] = '#';
            $list[$k]['leave_type'] = '#';
            switch ($v['class_type']) {
                case 1://报销
                    break;
                case 2://报销
                    $child = ExpenseModel::where('aid', $v['id'])->find();
                    if ($child) {
                        $list[$k]['money'] = $child['total'];
                    }
                    break;
                case 3://费用
                    $child = CostModel::where('aid', $v['id'])->find();
                    if ($child) {
                        $list[$k]['money'] = $child['money'];
                    }
                    break;
            }
            if ($v['project_id']) {
                $project_data = ProjectModel::getRowById($v['project_id']);
            } else {
                $project_data = [
                    'name' => '其他',
                ];
            }
            $list[$k]['project_name'] = $project_data['name'];
            if (1 == $v['is_deal']) {
                $list[$k]['deal_mark'] = '未支付';
            } elseif (2 == $v['is_deal']) {
                $list[$k]['deal_mark'] = '支付-' . $v['deal_mark'] . '-' . $v['deal_time'];
            }
        }
        $panel_type = config('other.panel_type');
        $approval_status = config('other.approval_status');
        $pages = $list->render();

        $this->assign('project_select', ProjectModel::getMyTask($params['project_id']));
        $this->assign('data_list', $list);
        $this->assign('panel_type', $panel_type);
        $this->assign('approval_status', $approval_status);
        $this->assign('pages', $pages);
        $this->assign('atype', 1);
        return $this->fetch();
    }

    public function leaveFile()
    {
        $params = $this->request->param();
        $user = $params['user'];
        $approval_id = $params['approval_id'];
        $cid = session('admin_user.cid');
        $where = [
            'cid'=>$cid,
            'user'=>$user,
        ];
        $flag = LeaveFileModel::where($where)->find();
        if ($flag){
            if (isset($params['read'])){
                $realname = AdminUser::getUserById($user)['realname'];
                $flag['ml_data'] = json_decode($flag['ml_data'],true);
                $this->assign('tmp', $flag);
                $this->assign('realname', $realname);
                return $this->fetch();
            }else{
                return $this->redirect(url('Approval/leaveList',['user'=>$user,'approval_id'=>$approval_id,'read'=>1]));
            }
        }
        $s_c = new Score();
        $tmp2 = $s_c->listPeoplePM($user,1);
        if ($this->request->isPost()){
            $data = $this->request->post();

            $subject_data = SubjectItemModel::getOwner($user);
            $data['subject_data'] = json_encode($subject_data);
            $data['ml_data'] = json_encode($tmp2);
            $data['cid'] = $cid;
            $data['approval_id'] = $params['approval_id'];
            $flag = LeaveFileModel::where($where)->find();
            if (!$flag){
                LeaveFileModel::create($data);
            }else{
                LeaveFileModel::where($where)->update($data);
            }
            UserInfo::where(['user_id'=>$user,'cid'=>$cid])->setField('approval_id',$approval_id);
            return $this->success('离职前数据归档成功',url('Approval/leaveList',['user'=>$user,'approval_id'=>$approval_id]));
        }
        $realname = AdminUser::getUserById($user)['realname'];
        $this->assign('tmp', $tmp2);
        $this->assign('realname', $realname);
        return $this->fetch();
    }

    public function leaveList()
    {
        $params = $this->request->param();
        $user = $params['user'];
        $approval_id = $params['approval_id'];
        $where = [
            'cid'=>session('admin_user.cid'),
            'user'=>$user,
        ];
        $row = LeaveFileModel::where($where)->find();
        $s_data = [];
        if ($row){
            $subject_data = json_decode($row['subject_data'],true);
            if ($subject_data){
                foreach ($subject_data as $k=>$v) {
                    $s_data[$k]['name'] = $v;
                    $s_data[$k]['flag'] = 1;
                }
            }
            $p_data = SubjectItemModel::getOwner($user);
            if ($p_data){
                foreach ($p_data as $k=>$v) {
                    if (key_exists($k,$s_data)){
                        $s_data[$k]['flag'] = 0;
                    }
                }
            }
        }
        if ($this->request->isPost()){
            $data = $this->request->post();
            foreach ($s_data as $k=>$v) {
                if (!$v['flag']){
                    return $this->error('项目所属内容还未处理完');
                }
            }
            $cid = session('admin_user.cid');
            $data['subject_data'] = json_encode($s_data);
            $data['cid'] = $cid;
            $data['approval_id'] = $params['approval_id'];
            $where = [
                'cid'=>$cid,
                'user'=>$data['user'],
            ];

            $flag = LeaveListModel::where($where)->find();
            if (!$flag){
                LeaveListModel::create($data);
            }else{
                LeaveListModel::where($where)->update($data);
            }
            return $this->success('提交成功',url('Approval/certificate',['id'=>$approval_id]));
        }
        $realname = AdminUser::getUserById($user)['realname'];
        $this->assign('realname', $realname);
        $this->assign('s_data', $s_data);
        return $this->fetch();
    }

}