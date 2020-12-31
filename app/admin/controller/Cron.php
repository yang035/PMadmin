<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/20
 * Time: 16:52
 */

namespace app\admin\controller;


use app\admin\model\AdminCompany;
use think\Controller;
use think\Db;
use app\admin\model\Score as ScoreModel;
use app\admin\model\Approval as ApprovalModel;

class Cron extends Controller
{
    protected function _initialize()
    {
        parent::_initialize();
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
            return 7;
        }elseif (in_array($day,$year_weekend_work)){
            return 8;
        }else{
            return date('w',strtotime($day));
        }
    }
    //每天凌晨执行
    public function dealScore(){
        $before_yesterday = date('Y-m-d',strtotime('-2 day'));
        $yesday = date('Y-m-d',strtotime('-1 day'));
        $today = date('Y-m-d');
        //工作日
        $working_day = [1,2,3,4,5,8];
        //休息日
        $day_off = [0,6,7];
        //周末
        $weekend = [0,6];
        //法定节假日
        $legal_holiday = [7];

        $b = $this->dealDay($before_yesterday);
        $y = $this->dealDay($yesday);
        $t = $this->dealDay($today);

        $num = 80;
        $remark = '';
        if (in_array($y,$working_day)){//工作日
//            if (in_array($b,$day_off)){
//                echo '工作日第一天';
//            }elseif (in_array($t,$day_off)){
//                echo '工作日最后一天';
//            }else{
//                echo '常规工作日';
//            }
            if (in_array($b,$legal_holiday)){
                $remark = '法定节假日后工作日第一天';
                $num += 40;
            }elseif (in_array($t,$legal_holiday)){
                $remark = '法定节假日后工作日最后一天';
                $num += 40;
            }elseif (in_array($b,$weekend)){
                $remark = '周末后工作日第一天';
                $num += 20;
            }elseif (in_array($t,$weekend)){
                $remark = '周末后工作日最后一天';
                $num += 20;
            }else{
                $remark = '常规工作日';
                $num += 0;
            }
        }else{//休息日
//            if (in_array($y,$legal_holiday)){
//                $remark = '法定节假日加班';
//                $num += 120;
//            }elseif (in_array($y,$weekend)){
//                $remark = '日常周末加班';
//                $num += 90;
//            }else{
//                $remark = '日常周末加班';
//                $num += 90;
//            }
        }
        $where = [
            'cid'=>2,
            'create_time'=>['between',[$yesday.' 17:00:00',$today.' 10:00:00']]
        ];
        $daily_report = Db::table('tb_daily_report')->field('user_id')->where($where)->group('user_id')->select();
        if ($daily_report){
            $daily_report = array_column($daily_report,'user_id');
        }
        $where = [
            'cid'=>2,
            'create_time'=>['between',[strtotime($yesday.' 17:00:00'),strtotime($today.' 10:00:00')]]
        ];
        $project_report = Db::table('tb_project_report')->field('user_id')->where($where)->group('user_id')->select();
        if ($project_report){
            $project_report = array_column($project_report,'user_id');
        }
        $merge_arr = array_unique(array_merge($daily_report,$project_report));

        if ($merge_arr){
            foreach ($merge_arr as $v){
                $sc = [
                    'cid' => 2,
                    'user' => $v,
                    'gl_add_score' => $num,
                    'remark' => $remark,
                ];
                if (ScoreModel::create($sc)) {
                    echo '更新成功\r\n';
                }
            }
        }
        $sys_user = [21,31];
        foreach ($sys_user as $v){
            $sc = [
                'cid' => 2,
                'user' => $v,
                'ml_add_score' => 100,
                'gl_add_score' => 100,
                'remark' => '系统自动计算',
            ];
            if (ScoreModel::create($sc)) {
                echo '更新成功\r\n';
            }
        }

        $h = mt_rand(8,20);
        $t = strtotime(date('Y-m-d', strtotime("-1 day")) . " {$h}:00:00");
        $sc = [
            'cid' => 2,
            'user' => 31,
            'ml_add_score' => 0,
            'gl_add_score' => 200,
            'remark' => '平台操作累计',
            'create_time'=>$t,
            'update_time'=>$t,
        ];
        if (ScoreModel::create($sc)) {
            echo '更新成功\r\n';
        }
    }

    public function dealApproval(){
        $where = [
            'status' => 1,
            'cid' => 2,
            'create_time' => ['<=',time() - 24*3600],
        ];
        $flag = ApprovalModel::where($where)->setField('status',6);
        if ($flag) {
            echo '更新成功\r\n';
        }
    }

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 系统定时计算排名系数
     */
    public function dealRank(){
        $cid = 2;
        $map['cid'] = $cid;
        $map1['id'] = ['neq', 1];
        $map1['is_show'] = ['eq', 0];
        $map1['status'] = 1;
        $fields = "`Score`.id,`Score`.subject_id,`Score`.user,sum(`Score`.ml_add_score) as ml_add_sum,sum(`Score`.ml_sub_score) as ml_sub_sum,sum(`Score`.gl_add_score) as gl_add_sum,sum(`Score`.gl_sub_score) as gl_sub_sum,`AdminUser`.realname";
        $data_list = ScoreModel::hasWhere('adminUser',$map1)->field($fields)->where($map)->group('`Score`.user')->order('gl_add_sum desc')->select();
        $tmp = [];
        if ($data_list) {
            $rankratio = AdminCompany::getCompanyById($cid);
            foreach ($data_list as $k => $v) {
                $tmp[$v['user']] = $k + 1;
            }

            $a = $rankratio['min_rankratio'];
            $b = $rankratio['max_rankratio'];
            $n = count($tmp);
            foreach ($tmp as $k => $v) {
                $tmp[$k] = round($b - ($b - $a) / ($n -1) * ($v-1),4);
            }
        }
        $where = [
            'cid' => $cid,
            'create_time' => ['between', [strtotime('yesterday'), strtotime(date('Y-m-d'))-1]],
        ];
        if ($tmp){
            $i = 1;
            foreach ($tmp as $k=>$v) {
                $where['user'] = $k;
                $data = [
                    'time_rank'=>$i,
                    'time_ratio'=>$v,
                ];
                ScoreModel::where($where)->update($data);
                $i++;
            }
        }
    }

    /**
     * 任务单延迟提交扣罚
     * 每天0:40分执行
     */
    public function Dealassignment()
    {
        $where = [
            'a.cid'=>2,
            'a.p_id'=>['>',0],
            'p.end_time'=>['between',['2021-01-01 00:00:00',date("Y-m-d H:i:s")]],
        ];
        $fields = "a.id,a.project_id,a.p_id,a.content,p.deal_user,r.id report_id";
        $data = Db::table('tb_assignment_item a')->field($fields)
            ->join('tb_project p','a.p_id=p.id','left')
            ->join('tb_project_report r','a.p_id=r.project_id','left')
            ->where($where)
            ->where('r.id is null')
            ->select();
        if ($data){
            foreach ($data as $k=>$v) {
                $deal_user = json_decode($v['deal_user'],true);
                if ($deal_user){
                    $sc = [];
                    foreach ($deal_user as $kk=>$vv){
                        $sc[$kk] = [
                            'cid' => 2,
                            'subject_id'=>$v['project_id'],
                            'project_id'=>$v['p_id'],
                            'user' => $kk,
                            'gl_sub_score' => 100,
                            'remark' => "任务未提交：{$v['content']},编号[{$v['id']}]",
                            'create_time'=>time(),
                            'update_time'=>time(),
                        ];
                    }
                    $flag = Db::table('tb_score')->insertAll($sc);
                    if ($flag) {
                        echo '更新成功\r\n';
                    }
                }
            }
        }
    }

}