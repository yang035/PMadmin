<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 9:43
 */

namespace app\admin\model;


use think\Model;

class Score extends Model
{
    /**
     * @param $data
     * @return bool|void
     * $data = [
    'project_id'=>0,
    'user'=>0,
    'ml_add_score'=>0,
    'ml_sub_score'=>0,
    'gl_add_score'=>0,
    'gl_sub_score'=>0
    'remark'=>''
    ];
     * 必须包含这几个参数，
     * 用于插入分值
     */
    public static function addScore($data){
        $data['user_id'] = session('admin_user.uid');
        if (!self::create($data)){
            return false;
        }
        return true;
    }

    //关联用户
    public function adminUser()
    {
        return $this->hasOne('AdminUser', 'id', 'user')->field('username,realname');
    }

    //关联项目
    public function scoreProject()
    {
        return $this->hasOne('Project', 'id', 'project_id')->field('*');
    }

    /**
     * @param int $start_time
     * @param int $end_time
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 计算排名系数
     */
    public static function dealRank($start_time=0,$end_time=0)
    {
        $map['cid'] = session('admin_user.cid');
        if ($start_time && $end_time){
            $map['Score.create_time'] = ['between',[$start_time,$end_time]];
        }
        $map1['id'] = ['neq', 1];
        $map1['is_show'] = ['eq', 0];
        $map1['status'] = 1;

        $fields = "`Score`.id,`Score`.subject_id,`Score`.user,sum(`Score`.ml_add_score) as ml_add_sum,sum(`Score`.ml_sub_score) as ml_sub_sum,sum(`Score`.gl_add_score) as gl_add_sum,sum(`Score`.gl_sub_score) as gl_sub_sum,`AdminUser`.realname";
        $data_list = self::hasWhere('adminUser',$map1)->field($fields)->where($map)->group('`Score`.user')->order('gl_add_sum desc')->select();

        $tmp = [];
        if ($data_list) {
            $rankratio = AdminCompany::getCompanyById($map['cid']);
            foreach ($data_list as $k => $v) {
                $tmp[$v['user']]['rank'] = $k + 1;
            }
            $a = $rankratio['min_rankratio'];
            $b = $rankratio['max_rankratio'];
            $n = count($tmp);
            foreach ($tmp as $k => $v) {
                $tmp[$k]['rank_ratio'] = (($n -1) * ($v['rank']-1) != 0) ? round($b - ($b - $a) / ($n -1) * ($v['rank']-1),4) : 1;
            }
        }
        return $tmp;
    }

    public static function getTimePeriod($type = 0)
    {
        $leaveType = config('other.time_period');
        $str = '';
        foreach ($leaveType as $k => $v) {
            if ($type == $k) {
                $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
            } else {
                $str .= '<option value="'.$k.'">'.$v.'</option>';
            }
        }
        return $str;
    }

}