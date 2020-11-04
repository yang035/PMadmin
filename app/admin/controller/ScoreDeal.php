<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 18:11
 */

namespace app\admin\controller;
use app\admin\model\DutyJob;
use app\admin\model\DutyUser;
use app\admin\model\Rule;
use app\admin\model\ScoreRule as RuleModel;
use app\admin\model\ScoreDeal as DealModel;
use app\admin\model\AdminUser;
use app\admin\model\Score as ScoreModel;
use think\Db;


class ScoreDeal extends Admin
{
    public $tab_data = [];
    protected function _initialize()
    {
        parent::_initialize();
        $sta_count = $this->getDealCount();
        $tab_data['menu'] = [
            [
                'title' => "积分奖扣<span class='layui-badge layui-bg-orange'>{$sta_count['user_num']}</span>",
                'url' => 'admin/ScoreDeal/index',
                'params' =>['atype'=>1],
            ],
            [
                'title' => "待我审批<span class='layui-badge'>{$sta_count['send_num']}</span>",
                'url' => 'admin/ScoreDeal/index',
                'params' =>['atype'=>2],
            ],
            [
                'title' => "已审批的<span class='layui-badge layui-bg-orange'>{$sta_count['has_num']}</span>",
                'url' => 'admin/ScoreDeal/index',
                'params' =>['atype'=>3],
            ],
            [
                'title' => "抄送我的<span class='layui-badge layui-bg-orange'>{$sta_count['copy_num']}</span>",
                'url' => 'admin/ScoreDeal/index',
                'params' =>['atype'=>4],
            ],
        ];
        $tab_data['current'] = url('index',['atype'=>1]);
        $this->tab_data = $tab_data;

        $cid = session('admin_user.cid');
        $redis = service('Redis');
        $default_user = $redis->get("pm:user:{$cid}");
        $o = [
            'self_user' => session('admin_user.uid'),
            'self_user_id' => session('admin_user.realname'),
        ];
        $user = [];
        if ($default_user){
            $user = (array)json_decode($default_user);
        }

        $this->assign('data_info', array_merge($user,$o));
    }

    public function getDealCount(){
        $map['cid'] = session('admin_user.cid');
        $uid = session('admin_user.uid');
        $fields = "SUM(IF(user_id='{$uid}',1,0)) user_num,
        SUM(IF(JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"') and status = 1,1,0)) send_num,
        SUM(IF(status = 2,1,0)) has_num,
        SUM(IF(JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"'),1,0)) copy_num";
        $count = DealModel::field($fields)->where($map)->find()->toArray();
        return $count;
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

    public function deal_data_id($x_user){
        $x_user_arr = json_decode($x_user,true);
        if ($x_user_arr){
            $tmp = array_keys($x_user_arr);
            return implode(',',$tmp);
        }
        return '';
    }

    public function index()
    {
        $params = $this->request->param();
        $map = [];
        $cid = session('admin_user.cid');
        $map['cid'] = $cid;
        $panel_type = config('other.panel_type');
        $params['atype'] = isset($params['atype']) ? $params['atype'] : 1;
        if ($params){
            if (!empty($params['status'])){
                $map['status'] = $params['status'];
            }

            if (!empty($params['start_time']) || !empty($params['end_time'])){
                $start_time = !empty($params['start_time']) ? $params['start_time'].' 00:00:00' : '1970-01-01 00:00:00';
                $end_time = !empty($params['end_time']) ? $params['end_time'].' 23:59:59' : date('Y-m-d H:i:s',time());
                $map['create_time'] = ['between time', [$start_time,$end_time]];
            }
        }
//        print_r($map);
        $uid = session('admin_user.uid');
        $con = '';
        switch ($params['atype']){
            case 1:
                $map['user_id'] = session('admin_user.uid');
                break;
            case 2:
                $con = "JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"')";
                $map['status'] = 1;
                break;
            case 3:
//                $con = "JSON_CONTAINS_PATH(send_user,'one', '$.\"$uid\"')";
                $map['status'] = 2;
                break;
            case 4:
                $con = "JSON_CONTAINS_PATH(copy_user,'one', '$.\"$uid\"')";
                break;
            default:
                $con = "";
                break;
        }

        $list = DealModel::where($map)->where($con)->order('create_time desc')->paginate(30, false, ['query' => input('get.')]);
        foreach ($list as $k=>$v){
            $list[$k]['score_user'] = $this->deal_data($v['score_user']);
            $list[$k]['send_user'] = $this->deal_data($v['send_user']);
            $list[$k]['user_id'] = AdminUser::getUserById($v['user_id'])['realname'];
            $list[$k]['rid'] = RuleModel::getFullName($v['rid']);
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

    public function getChilds($id=0){
        $child_option = RuleModel::getChilds($id);
        echo json_encode($child_option);
    }

    public function agree($id)
    {
        $ids = input('param.ids/a') ? input('param.ids/a') : input('param.id/a');
        if ($ids) {
            $num = count($ids);
            foreach ($ids as $id) {
                $list = DealModel::getRowById($id);
                $data = [
                    'id' => $id,
                    'rid' => $list['rid'],
                    'status' => 2,
                ];
                $score_user = json_decode($list['score_user'], true);
                $score = [];
                $realname = session('admin_user.realname');
                $rule_row = RuleModel::getRowById($list['rid']);
                foreach ($score_user as $k => $v) {
                    $score[$k]['cid'] = session('admin_user.cid');
                    $score[$k]['user'] = $k;
                    $score[$k]['url'] = $this->request->url();
                    $score[$k]['remark'] = "事件积分({$realname})审批,{$rule_row['name']}";
                    $score[$k]['user_id'] = session('admin_user.uid');
                    if ($rule_row['ml'] > 0) {
                        $score[$k]['ml_add_score'] = $rule_row['ml'];
                    } else {
                        $score[$k]['ml_sub_score'] = abs($rule_row['ml']);
                    }
                    if ($rule_row['gl'] > 0) {
                        $score[$k]['gl_add_score'] = $rule_row['gl'];
                    } else {
                        $score[$k]['gl_sub_score'] = abs($rule_row['gl']);
                    }
                    $score[$k]['create_time'] = $score[$k]['update_time'] = time();
                }
                //开启事务
                Db::startTrans();
                try {
                    DealModel::update($data);
                    if (2 == $data['status']) {
                        RuleModel::where('id', $data['rid'])->setInc('num');
                    }
                    $score_model = new ScoreModel();
                    $res = $score_model->insertAll($score);
                    //事务提交
                    Db::commit();
                } catch (\Exception $e) {
                    //事务回滚
                    Db::rollback();
                }
                if (1 == $num) {
                    if ($res) {
                        return $this->success("操作成功{$this->score_value}");
                    } else {
                        return $this->error('操作失败');
                    }
                }
            }
            return $this->success('操作成功');
        }
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $p['cid'] = $data['cid'] = session('admin_user.cid');
            $p['user_id'] = session('admin_user.uid');
            $p['score_user'] = user_array($data['score_user']);
            $p['send_user'] = user_array($data['send_user']);
            $p['copy_user'] = user_array($data['copy_user']);
            $p['create_time'] = time();
            $p['update_time'] = time();
            $s = json_decode($p['send_user'],true);
            if (empty($s)){
                return $this->error('审批人不能为空或不能选择自己');
            }
            // 验证
            $result = $this->validate($data, 'ScoreDeal');
            if($result !== true) {
                return $this->error($result);
            }
            $tmp = [];
            if (is_array($data['rid'])){
                $t = array_unique($data['rid']);
                foreach ($t as $k=>$v){
                    $tmp[$k] = $p;
                    $tmp[$k]['rid'] = $v;
                    $tmp[$k]['remark'] = $data['remark'][$k];
                }
            }
            $model = new DealModel();
            if (!$model->insertAll($tmp)){
                return $this->error('添加失败！');
            }else{
                return $this->success("操作成功{$this->score_value}",'index');
            }
        }
        $this->assign('rule_option',RuleModel::getOption1());
        return $this->fetch();
    }

    public function read(){
        $params = $this->request->param();
        $list = DealModel::getRowById($params['id']);
        if ($this->request->isPost()){
            $data = $this->request->post();
            unset($data['atype']);

            $score_user = json_decode($list['score_user'],true);
            $score = [];
            $realname = session('admin_user.realname');
            $rule_row = RuleModel::getRowById($list['rid']);
            foreach ($score_user as $k=>$v) {
                $score[$k]['cid'] = session('admin_user.cid');
                $score[$k]['user'] = $k;
                $score[$k]['url'] = $this->request->url();
                $score[$k]['remark'] = "事件积分({$realname})审批,{$rule_row['name']}";
                $score[$k]['user_id'] = session('admin_user.uid');
                if ($rule_row['ml'] > 0){
                    $score[$k]['ml_add_score'] = $rule_row['ml'];
                }else{
                    $score[$k]['ml_sub_score'] = abs($rule_row['ml']);
                }
                if ($rule_row['gl'] > 0){
                    $score[$k]['gl_add_score'] = $rule_row['gl'];
                }else{
                    $score[$k]['gl_sub_score'] = abs($rule_row['gl']);
                }
                $score[$k]['create_time'] = $score[$k]['update_time'] = time();
            }
            //开启事务
            Db::startTrans();
            try{
                DealModel::update($data);
                if (2 == $data['status']){
                    RuleModel::where('id',$data['rid'])->setInc('num');
                }
                $score_model = new ScoreModel();
                $res = $score_model->insertAll($score);
                //事务提交
                Db::commit();
            }catch (\Exception $e){
                //事务回滚
                Db::rollback();
            }

            $w = [
                'job_id'=>session('admin_user.job_item'),
                'duty_id'=>1,
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
                'duty_id'=>1,
                'num'=>$num,
                'times'=>1,
                'url'=>$_SERVER['HTTP_REFERER'],
                'remark'=>'审批次数记录',
                'user_id'=>session('admin_user.uid'),
            ];
            DutyUser::create($duty_user);

            if ($res){
                return $this->success("操作成功{$this->score_value}");
            }else{
                return $this->error('操作失败');
            }
        }

//        print_r($score);exit();
        if ($list){
            $list['rule'] = RuleModel::getFullName($list['rid']);
            $list['score_user'] = $this->deal_data($list['score_user']);
            $list['send_user'] = $this->deal_data($list['send_user']);
            $list['copy_user'] = $this->deal_data($list['copy_user']);
            $list['user_id'] = AdminUser::getUserById($list['user_id'])['realname'];

        }else{
            $list = [];
        }
        $approval_status = config('other.approval_status');
        $this->assign('data_list',$list);
        $this->assign('approval_status',$approval_status);
        return $this->fetch();
    }

    public function dealBack($id)
    {
        $res= DealModel::where('id',$id)->setField('status',3);
        if (!$res){
            return $this->error('操作失败！');
        }
        return $this->success('操作成功。');
    }
}