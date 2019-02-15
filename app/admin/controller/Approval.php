<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 11:48
 */

namespace app\admin\controller;

use app\admin\model\Approval as ApprovalModel;
use app\admin\model\ApprovalLeave as LeaveModel;
use app\admin\model\ApprovalExpense as ExpenseModel;
use app\admin\model\ApprovalBusiness as BusinessModel;
use app\admin\model\ApprovalProcurement as ProcurementModel;
use app\admin\model\ApprovalOvertime as OvertimeModel;
use app\admin\model\ApprovalGoout as GooutModel;
use app\admin\model\ApprovalUsecar as CarModel;
use app\admin\model\ApprovalCost as CostModel;
use app\admin\model\ApprovalDispatch as DispatchModel;
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

    public function getApprovalCount()
    {
        $map['cid'] = session('admin_user.cid');
        $uid = session('admin_user.uid');
        $fields = "SUM(IF(user_id='{$uid}',1,0)) user_num,
        SUM(IF(JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"') and status=1,1,0)) send_num,
        SUM(IF(JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"'),1,0)) copy_num,
        SUM(IF(JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"'),1,0)) deal_num,
        SUM(IF(JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"') and status>1,1,0)) has_num,
        SUM(IF(JSON_CONTAINS_PATH(fellow_user,'one', '$.\"$uid\"'),1,0)) follow_num";
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
        $panel_type = config('other.panel_type');
        $approval_status = config('other.approval_status');
        $params['atype'] = isset($params['atype']) ? $params['atype'] : 1;
        if (1 == $params['atype']) {
            $this->assign('tab_data', $this->tab_data);
            $this->assign('tab_type', 1);
            $this->assign('isparams', 1);
            $this->assign('atype', $params['atype']);
            $this->assign('tab_url', url('index', ['atype' => $params['atype']]));
            $this->assign('panel_type', $panel_type);
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
                $con = "JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"')";
                $map['status'] = 1;
                break;
            case 4:
                $con = "JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"')";
                break;
            case 5:
                $con = "JSON_CONTAINS_PATH(deal_user,'one', '$.\"$uid\"')";
                break;
            case 6:
                $con = "JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"')";
                $map['status'] = ['>', 1];
                break;
            case 7:
                $con = "JSON_CONTAINS_PATH(fellow_user,'one', '$.\"$uid\"')";
                break;
            default:
                $con = "";
                break;
        }

        if (isset($params['export']) && 1 == $params['export']){
            set_time_limit(0);
            $list = ApprovalModel::where($map)->where($con)->order('create_time desc')->select();
//        print_r($data_list);
            foreach ($list as $k => $v) {
                $list[$k]['send_user'] = strip_tags($this->deal_data($v['send_user']));
                $list[$k]['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];
                if ($v['project_id']){
                    $project_data = ProjectModel::getRowById($v['project_id']);
                }else{
                    $project_data = [
                        'name'=>'其他',
                    ];
                }
                $list[$k]['project_name'] = $project_data['name'];
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
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', '姓名')
                ->setCellValue('B1', '类型')
                ->setCellValue('C1', '开始时间')
                ->setCellValue('D1', '结束时间')
                ->setCellValue('E1', '项目名称')
                ->setCellValue('F1', '审批人')
                ->setCellValue('G1', '添加时间')
                ->setCellValue('H1', '状态');
//            print_r($data_list);exit();
            foreach ($list as $k => $v) {
                $num = $k + 2;
                $objPHPExcel->setActiveSheetIndex(0)
                    //Excel的第A列，uid是你查出数组的键值，下面以此类推
                    ->setCellValue('A' . $num, $v['user_id'])
                    ->setCellValue('B' . $num, $panel_type[$v['class_type']]['title'])
                    ->setCellValue('C' . $num, $v['start_time'])
                    ->setCellValue('D' . $num, $v['end_time'])
                    ->setCellValue('E' . $num, $v['project_name'])
                    ->setCellValue('F' . $num, $v['send_user'])
                    ->setCellValue('G' . $num, $v['create_time'])
                    ->setCellValue('H' . $num, $approval_status[$v['status']]);
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
            $list[$k]['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];
            if ($v['project_id']){
                $project_data = ProjectModel::getRowById($v['project_id']);
            }else{
                $project_data = [
                    'name'=>'其他',
                ];
            }
            $list[$k]['project_name'] = $project_data['name'];
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
            // 验证
            $result = $this->validate($data, 'ApprovalLeave');
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
                    'send_user' => json_encode(user_array($data['send_user'])),
                    'copy_user' => json_encode(user_array($data['copy_user'])),
                ];

                $res = ApprovalModel::create($approve);
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
        $this->assign('leave_type', LeaveModel::getOption());
        return $this->fetch();
    }

    public function expense()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'ApprovalExpense');
            if ($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
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
                    'send_user' => json_encode(user_array($data['send_user'])),
                    'copy_user' => json_encode(user_array($data['copy_user'])),
                ];
                $res = ApprovalModel::create($approve);
                $leave = [
                    'aid' => $res['id'],
                    'type' => $data['type'],
                    'reason' => $data['reason'],
                    'attachment' => $data['attachment'],
                    'total' => $data['total'],
                ];
                if ($data['amount']) {
                    foreach ($data['amount'] as $k => $v) {
                        $leave['detail'][$k]['amount'] = $v;
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

    public function cost()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'ApprovalCost');
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
                    'send_user' => json_encode(user_array($data['send_user'])),
                    'copy_user' => json_encode(user_array($data['copy_user'])),
                ];
                $res = ApprovalModel::create($approve);
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
            // 验证
            $result = $this->validate($data, 'ApprovalBusiness');
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
                    'fellow_user' => json_encode(user_array($data['fellow_user'])),
                    'send_user' => json_encode(user_array($data['send_user'])),
                    'copy_user' => json_encode(user_array($data['copy_user'])),
                ];
                $res = ApprovalModel::create($approve);
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
            Db::startTrans();
            try {
                $approve = [
                    'class_type' => $data['class_type'],
                    'cid' => session('admin_user.cid'),
                    'start_time' => $data['start_time'] . ' ' . $data['start_time1'],
                    'end_time' => $data['end_time'] . ' ' . $data['end_time1'],
                    'time_long' => $data['time_long'],
                    'user_id' => session('admin_user.uid'),
                    'send_user' => json_encode(user_array($data['send_user'])),
                    'copy_user' => json_encode(user_array($data['copy_user'])),
                ];
                $res = ApprovalModel::create($approve);
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
        $this->assign('cat_option', ItemModel::getOption());
        return $this->fetch();

    }

    public function overtime()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'ApprovalOvertime');
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
                    'send_user' => json_encode(user_array($data['send_user'])),
                    'copy_user' => json_encode(user_array($data['copy_user'])),
                ];
                $res = ApprovalModel::create($approve);
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
                    'fellow_user' => json_encode(user_array($data['fellow_user'])),
                    'send_user' => json_encode(user_array($data['send_user'])),
                    'copy_user' => json_encode(user_array($data['copy_user'])),
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
                    'deal_user' => json_encode(user_array($data['deal_user'])),
                    'send_user' => json_encode(user_array($data['send_user'])),
                    'copy_user' => json_encode(user_array($data['copy_user'])),
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
                    'send_user' => json_encode(user_array($data['send_user'])),
                    'copy_user' => json_encode(user_array($data['copy_user'])),
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

    public function printView()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
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
                    'send_user' => json_encode(user_array($data['send_user'])),
                    'copy_user' => json_encode(user_array($data['copy_user'])),
                ];
                $res = ApprovalModel::create($approve);
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
                    'deal_user' => json_encode(user_array($data['deal_user'])),
                    'send_user' => json_encode(user_array($data['send_user'])),
                    'copy_user' => json_encode(user_array($data['copy_user'])),
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

    public function read()
    {
        $params = $this->request->param();
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
                    unset($data['atype'], $data['class_type']);
//                $res= ApprovalModel::where('id',$data['id'])->setField('status',$data['status']);
                    //事务提交，保证数据一致性
                    Db::startTrans();
                    try {
                        ApprovalModel::update($data);
                        $uid = session('admin_user.uid');
                        $sql = "UPDATE tb_approval SET send_user = JSON_SET(send_user, '$.\"{$uid}\"', 'a') WHERE id ={$data['id']}";
                        $res = ApprovalModel::execute($sql);
                        // 提交事务
                        Db::commit();
                    } catch (\Exception $e) {
                        // 回滚事务
                        Db::rollback();
                    }
                }
            }elseif (4 == $data['class_type']){
                unset($data['class_type']);
                $data['cid'] = session('admin_user.cid');
                $data['user_id'] = session('admin_user.uid');
                $res = ApprovalReportModel::create($data);
            }
            if (!$res) {
                return $this->error('处理失败！');
            }
            return $this->success("操作成功{$this->score_value}");
        }
        switch ($params['class_type']) {
            case 1:
                $table = 'tb_approval_leave';
                $f = 'b.type,b.reason,b.attachment';
                break;
            case 2:
                $table = 'tb_approval_expense';
                $f = 'b.type,b.reason,b.detail,b.total,b.attachment';
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
        switch ($params['class_type']) {
            case 1:
                $leave_type = config('other.leave_type');
                $this->assign('leave_type', $leave_type);
                break;
            case 2:
                $list['detail'] = json_decode($list['detail'], true);
                $expense_type = config('other.expense_type');
                $this->assign('expense_type', $expense_type);
                break;
            case 3:
                $cost_type = config('other.cost_type');
                $this->assign('cost_type', $cost_type);
                break;
            case 4:
                $report = ApprovalReport::getAll(5);
                if ($report) {
                    foreach ($report as $k => $v) {
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
                $list['type'] = $print_type[$list['store_id']];
                $list['store_id'] = $store_type[$list['store_id']];
                break;
            case 13:
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
        if ($list['project_id']){
            $project_data = ProjectModel::getRowById($list['project_id']);
        }else{
            $project_data = [
                'name'=>'其他',
            ];
        }
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
        $res = ApprovalModel::where('id', $id)->setField('status', 3);
        if (!$res) {
            return $this->error('操作失败！');
        }
        return $this->success('操作成功。');
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
        $t = '';
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
                ->where($where)->order('u.id asc')->select();
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
                ->setCellValue('G1', '加班')
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
                    ->setCellValue('G' . $num, $v['num_6'])
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
            ->where($where)->order('u.id asc')->paginate(30, false, ['query' => input('get.')]);
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