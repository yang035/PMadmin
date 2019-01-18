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
use app\admin\model\AdminUser;
use app\admin\model\AssetItem as ItemModel;
use app\admin\model\ApprovalGoods;
use app\admin\model\ApprovalPrint;
use app\admin\model\Project as ProjectModel;
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
        SUM(IF(JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"') and status>1,1,0)) has_num";
        $count = ApprovalModel::field($fields)->where($map)->find()->toArray();
        return $count;
    }

    public function index()
    {
        $params = $this->request->param();
        $map = [];
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        $panel_type = config('other.panel_type');
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
            if (!empty($params['start_time'])) {
                $map['create_time'] = ['egt', $params['start_time']];
            }
            if (!empty($params['end_time'])) {
                $map['create_time'] = ['elt', $params['end_time']];
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
            default:
                $con = "";
                break;
        }

        $list = ApprovalModel::where($map)->where($con)->order('create_time desc')->paginate(10, false, ['query' => input('get.')]);
        foreach ($list as $k => $v) {
            $list[$k]['send_user'] = $this->deal_data($v['send_user']);
            $list[$k]['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];
        }
        $approval_status = config('other.approval_status');
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
                    'project_id' => $data['project_id'],
                    'size_type' => $data['size_type'],
                    'reason' => $data['reason'],
                    'attachment' => $data['attachment'],
                    'money' => $data['money'],
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
        $this->assign('mytask', ProjectModel::getMyTask(0));
        return $this->fetch();
    }

    public function read()
    {
        $params = $this->request->param();
        if ($this->request->isPost()) {
            $data = $this->request->post();
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
                $f = 'b.reason,b.name,b.number,b.amount,b.attachment';
                break;
            case 6:
                $table = 'tb_approval_overtime';
                $f = 'b.reason,b.time_long1,b.attachment';
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
                $f = 'b.project_id,b.type,b.size_type,b.reason,b.money,b.attachment';
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
                break;
            case 5:
                break;
            case 6:
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
                $this->assign('print_type', $print_type);
                $size_type = config('other.size_type');
                $this->assign('size_type', $size_type);
                $this->assign('project_name', ProjectModel::getRowById($list['project_id']));
                break;
            default:
                break;
        }
        $list['attachment'] = explode(',', substr($list['attachment'], 0, -1));
        $list['real_name'] = AdminUser::getUserById($list['user_id'])['realname'];
        $list['deal_user'] = $this->deal_data($list['deal_user']);
//        print_r($list);
        $approval_status = config('other.approval_status');
        $this->assign('data_list', $list);
        $this->assign('approval_status', $approval_status);
        $this->assign('class_type', $params['class_type']);
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

}