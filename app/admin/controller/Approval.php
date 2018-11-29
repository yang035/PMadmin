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
use app\admin\model\AdminUser;
use app\admin\model\AssetItem as ItemModel;


class Approval extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();

        $tab_data['menu'] = [
            [
                'title' => '发起申请',
                'url' => 'admin/approval/index',
                'params' =>['atype'=>1],
            ],
            [
                'title' => '我的申请',
                'url' => 'admin/approval/index',
                'params' =>['atype'=>2],
            ],
            [
                'title' => '待我审批',
                'url' => 'admin/approval/index',
                'params' =>['atype'=>3],
            ],
            [
                'title' => '抄送我的',
                'url' => 'admin/approval/index',
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
        $panel_type = config('other.panel_type');
        if (1 == $params['atype']){
            $this->assign('tab_data', $this->tab_data);
            $this->assign('tab_type', 1);
            $this->assign('isparams', 1);
            $this->assign('atype', $params['atype']);
            $this->assign('tab_url', url('index',['atype'=>$params['atype']]));
            $this->assign('panel_type', $panel_type);
            return $this->fetch('panel');
        }
        if ($params){
            if (!empty($params['class_type'])){
                $map['class_type'] = $params['class_type'];
            }
            if (!empty($params['start_time'])){
                $map['create_time'] = ['egt', $params['start_time']];
            }
            if (!empty($params['end_time'])){
                $map['create_time'] = ['elt', $params['end_time']];
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

        $list = ApprovalModel::where($map)->where($con)->order('create_time desc')->paginate(10, false, ['query' => input('get.')]);
        foreach ($list as $k=>$v){
            $list[$k]['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];
        }
        $approval_status = config('other.approval_status');
        $this->assign('tab_data', $this->tab_data);
        $this->assign('tab_type', 1);
        $this->assign('isparams', 1);
        $this->assign('atype', $params['atype']);
        $pages = $list->render();
        $this->assign('tab_url', url('index',['atype'=>$params['atype']]));
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
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
            $approve = [
                'class_type'=>$data['class_type'],
                'cid'=>session('admin_user.cid'),
                'start_time'=>$data['start_time'],
                'end_time'=>$data['end_time'],
                'user_id'=>session('admin_user.uid'),
                'send_user'=>json_encode(user_array($data['send_user'])),
                'copy_user'=>json_encode(user_array($data['copy_user'])),
            ];
            $res = ApprovalModel::create($approve);
            if ($res) {
                $leave = [
                    'aid'=>$res['id'],
                    'type'=>$data['type'],
                    'reason'=>$data['reason'],
                    'attachment'=>$data['attachment'],
                ];
                if (!LeaveModel::create($leave)){
                    return $this->error('添加失败！');
                }
            }else{
                return $this->error('添加失败！');
            }
            return $this->success('添加成功。','index');
        }
        $this->assign('leave_type', LeaveModel::getOption());
        return $this->fetch();
    }

    public function expense(){
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'ApprovalExpense');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
            $approve = [
                'class_type'=>$data['class_type'],
                'cid'=>session('admin_user.cid'),
                'start_time'=>$data['start_time'],
                'end_time'=>$data['end_time'],
                'user_id'=>session('admin_user.uid'),
                'send_user'=>json_encode(user_array($data['send_user'])),
                'copy_user'=>json_encode(user_array($data['copy_user'])),
            ];
            $res = ApprovalModel::create($approve);
            if ($res) {
                $leave = [
                    'aid'=>$res['id'],
                    'type'=>$data['type'],
                    'reason'=>$data['reason'],
                    'attachment'=>$data['attachment'],
                    'total'=>$data['total'],
                ];
                if ($data['amount']){
                    foreach ($data['amount'] as $k=>$v){
                        $leave['detail'][$k]['amount'] = $v;
                        $leave['detail'][$k]['mark'] = $data['mark'][$k];
                    }
                }
                $leave['detail'] = json_encode($leave['detail']);
                if (!ExpenseModel::create($leave)){
                    return $this->error('添加失败！');
                }
            }else{
                return $this->error('添加失败！');
            }
            return $this->success('添加成功。','index');
        }
        $this->assign('expense_type', ExpenseModel::getOption());
        return $this->fetch();
    }

    public function cost()
    {
        
    }

    public function business()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'ApprovalBusiness');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
            $approve = [
                'class_type'=>$data['class_type'],
                'cid'=>session('admin_user.cid'),
                'start_time'=>$data['start_time'],
                'end_time'=>$data['end_time'],
                'user_id'=>session('admin_user.uid'),
                'send_user'=>json_encode(user_array($data['send_user'])),
                'copy_user'=>json_encode(user_array($data['copy_user'])),
            ];
            $res = ApprovalModel::create($approve);
            if ($res) {
                $leave = [
                    'aid'=>$res['id'],
                    'address'=>$data['address'],
                    'reason'=>$data['reason'],
                    'attachment'=>$data['attachment'],
                ];
                if (!BusinessModel::create($leave)){
                    return $this->error('添加失败！');
                }
            }else{
                return $this->error('添加失败！');
            }
            return $this->success('添加成功。','index');
        }
        return $this->fetch();
        
    }

    public function procurement()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'ApprovalProcurement');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
            $approve = [
                'class_type'=>$data['class_type'],
                'cid'=>session('admin_user.cid'),
                'start_time'=>$data['start_time'],
                'end_time'=>$data['end_time'],
                'user_id'=>session('admin_user.uid'),
                'send_user'=>json_encode(user_array($data['send_user'])),
                'copy_user'=>json_encode(user_array($data['copy_user'])),
            ];
            $res = ApprovalModel::create($approve);
            if ($res) {
                $leave = [
                    'aid'=>$res['id'],
                    'cat_id'=>$data['cat_id'],
                    'name'=>$data['name'],
                    'number'=>$data['number'],
                    'amount'=>$data['amount'],
                    'reason'=>$data['reason'],
                    'attachment'=>$data['attachment'],
                ];
                if (!ProcurementModel::create($leave)){
                    return $this->error('添加失败！');
                }
            }else{
                return $this->error('添加失败！');
            }
            return $this->success('添加成功。','index');
        }
        $this->assign('cat_option',ItemModel::getOption());
        return $this->fetch();
        
    }

    public function overtime()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'ApprovalOvertime');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
            $approve = [
                'class_type'=>$data['class_type'],
                'cid'=>session('admin_user.cid'),
                'start_time'=>$data['start_time'],
                'end_time'=>$data['end_time'],
                'user_id'=>session('admin_user.uid'),
                'send_user'=>json_encode(user_array($data['send_user'])),
                'copy_user'=>json_encode(user_array($data['copy_user'])),
            ];
            $res = ApprovalModel::create($approve);
            if ($res) {
                $leave = [
                    'aid'=>$res['id'],
                    'time_long'=>$data['time_long'],
                    'reason'=>$data['reason'],
                    'attachment'=>$data['attachment'],
                ];
                if (!OvertimeModel::create($leave)){
                    return $this->error('添加失败！');
                }
            }else{
                return $this->error('添加失败！');
            }
            return $this->success('添加成功。','index');
        }
        return $this->fetch();
    }

    public function goout()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'ApprovalGoout');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
            $approve = [
                'class_type'=>$data['class_type'],
                'cid'=>session('admin_user.cid'),
                'start_time'=>$data['start_time'],
                'end_time'=>$data['end_time'],
                'user_id'=>session('admin_user.uid'),
                'send_user'=>json_encode(user_array($data['send_user'])),
                'copy_user'=>json_encode(user_array($data['copy_user'])),
            ];
            $res = ApprovalModel::create($approve);
            if ($res) {
                $leave = [
                    'aid'=>$res['id'],
                    'address'=>$data['address'],
                    'reason'=>$data['reason'],
                    'time_long'=>$data['time_long'],
                    'attachment'=>$data['attachment'],
                ];
                if (!GooutModel::create($leave)){
                    return $this->error('添加失败！');
                }
            }else{
                return $this->error('添加失败！');
            }
            return $this->success('添加成功。','index');
        }
        return $this->fetch();
    }

    public function useCar()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'ApprovalUsecar');
            if($result !== true) {
                return $this->error($result);
            }
            unset($data['id']);
            $approve = [
                'class_type'=>$data['class_type'],
                'cid'=>session('admin_user.cid'),
                'start_time'=>$data['start_time'],
                'end_time'=>$data['end_time'],
                'user_id'=>session('admin_user.uid'),
                'send_user'=>json_encode(user_array($data['send_user'])),
                'copy_user'=>json_encode(user_array($data['copy_user'])),
            ];
            $res = ApprovalModel::create($approve);
            if ($res) {
                $leave = [
                    'aid'=>$res['id'],
                    'address'=>$data['address'],
                    'reason'=>$data['reason'],
                    'car_type'=>$data['car_type'],
                    'time_long'=>$data['time_long'],
                    'attachment'=>$data['attachment'],
                ];
                if (!CarModel::create($leave)){
                    return $this->error('添加失败！');
                }
            }else{
                return $this->error('添加失败！');
            }
            return $this->success('添加成功。','index');
        }
        $this->assign('car_type', CarModel::getOption());
        return $this->fetch();
    }

    public function useSeal(){

    }

    public function clockIn()
    {
        
    }

    public function read(){
        $params = $this->request->param();
        if ($this->request->isPost()){
            $data = $this->request->post();
            $res= ApprovalModel::where('id',$data['id'])->setField('status',$data['status']);
            if (!$res){
                return $this->error('处理失败！');
            }
            return $this->success('处理成功。');
        }
        switch ($params['class_type']){
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
                $f = 'b.type,b.reason,b.attachment';
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
                $f = 'b.reason,b.time_long,b.attachment';
                break;
            case 7:
                $table = 'tb_approval_goout';
                $f = 'b.reason,b.address,b.time_long,b.attachment';
                break;
            case 8:
                $table = 'tb_approval_usecar';
                $f = 'b.reason,b.address,b.time_long,b.attachment,b.car_type';
                break;
            default:
                $table = 'tb_approval_leave';
                $f = 'b.type,b.reason,b.attachment';
                break;
        }
        $map = [
            'a.id' =>$params['id']
        ];
        $fields = 'a.*,'.$f;
        $list = db('approval')->alias('a')->field($fields)
            ->join("{$table} b",'a.id = b.aid','left')
            ->where($map)->find();
        switch ($params['class_type']){
            case 1:
                $leave_type = config('other.leave_type');
                $this->assign('leave_type',$leave_type);
                break;
            case 2:
                $list['detail'] = json_decode($list['detail'],true);
                $expense_type = config('other.expense_type');
                $this->assign('expense_type',$expense_type);
                break;
            case 3:
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
                $car_type = config('other.car_type');
                $this->assign('car_type',$car_type);
            default:
                break;
        }
        $list['attachment'] = explode(',',substr($list['attachment'],0,-1));
        $list['real_name'] = AdminUser::getUserById($list['user_id'])['realname'];
        $approval_status = config('other.approval_status');
        $this->assign('data_list',$list);
        $this->assign('approval_status',$approval_status);
        $this->assign('class_type',$params['class_type']);
        return $this->fetch();
    }

    public function approvalBack($id)
    {
        $res= ApprovalModel::where('id',$id)->setField('status',3);
        if (!$res){
            return $this->error('操作失败！');
        }
        return $this->success('操作成功。');
    }

}