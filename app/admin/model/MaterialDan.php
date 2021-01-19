<?php

namespace app\admin\model;

use think\Model;
use think\Loader;
use think\Db;

class MaterialDan extends Model
{
    public static function getProject($type = 0){
        $w1 = [
            'cid'=>session('admin_user.cid'),
        ];
        $project_id = self::where($w1)->column('distinct(project_id) project_id');
        $str = "<option value=''>选择</option>";
        if ($project_id){
            $w2 = [
                'id'=>['in',$project_id],
            ];
            $p_data = Project::where($w2)->column('name','id');
            if ($type){
                return $p_data;
            }
            foreach ($p_data as $k=>$v){
                $str .= "<option value='".$k."'>".$v."</option>";
            }
        }
        return $str;
    }
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