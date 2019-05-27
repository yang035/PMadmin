<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/20
 * Time: 16:52
 */

namespace app\admin\controller;


use think\Controller;
use think\Db;
use app\admin\model\Score as ScoreModel;

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
    }

}