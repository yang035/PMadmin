<?php

namespace app\admin\model;

use think\Model;
use think\Loader;
use think\Db;

class ProjectBudget extends Model
{
    public static function getName($id=0,$project_id,$flag=false){
        $where = [
            'cid'=>session('admin_user.cid'),
            'project_id'=>$project_id,
        ];
        $fields = "id,name,caigou_danjia";
        $res = self::field($fields)->where($where)->select();
        $str = '';
        if ($res){
            if ($flag){
                return json_encode($res);
            }
            foreach ($res as $k => $v) {
                if ($id == $k) {
                    $str .= '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                } else {
                    $str .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                }
            }
            return $str;
        }
    }
}