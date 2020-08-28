<?php

namespace app\admin\model;

use think\Model;
use think\Loader;
use think\Db;

class ProjectBudgetcaigou extends Model
{
    public static function getTotal(){
        $where = [
            'cid'=>session('admin_user.cid'),
        ];
        $data = self::where($where)->group('project_id')->column('SUM(caigou_zongjia)','project_id');
        return $data;
    }

    public static function getCaigouDetail($project_id){
        $where = [
            'cid'=>session('admin_user.cid'),
            'project_id'=>$project_id,
        ];
        $fields = "project_id,name,SUM(caigou_shuliang) as caigou_shuliang,SUM(caigou_zongjia) as caigou_zongjia";
        $data = self::where($where)->field($fields)->group('name')->select();
        $res = [];
        if ($data){
            foreach ($data as $k=>$v){
                $res[$v['name']] = $v;
            }
        }
        return $res;
    }

}