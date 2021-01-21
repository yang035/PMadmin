<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:00
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class MaterialPrice extends Model
{
    public static function getUnitOption($type = '')
    {
        $leaveType = config('other.unit2');
        $str = '<option value="">选择</option>';
        foreach ($leaveType as $k => $v) {
            if ($type == $v) {
                $str .= "<option value='".$v."' selected>".$v."</option>";
            } else {
                $str .= "<option value='".$v."'>".$v."</option>";
            }
        }
        return $str;
    }

    public static function getP($uid, $id = 0)
    {
        $where = [
            'm.user_id' => $uid,
            'p.pid' => 0,
            'p.status' => 1,
        ];
        $fields = 'distinct m.project_id id,p.name';
        $list = Db::table('tb_material_price m')
            ->join('tb_project p', 'm.project_id = p.id', 'left')
            ->field($fields)
            ->where($where)
            ->select();
        if ($list) {
            $str = "<option value=''>选择</option>";
            foreach ($list as $k => $v) {
                if ($id == $v['id']) {
                    $str .= "<option value='" . $v['id'] . "' selected>" . $v['name'] . "</option>";
                } else {
                    $str .= "<option value='" . $v['id'] . "'>" . $v['name'] . "</option>";
                }
            }
            return $str;
        }
    }

    public static function getMaterialList($project_id,$company_id, $id=0)
    {
        $where = [
            'project_id' => $project_id,
            'company_id' => $company_id,
        ];
        if ($id){
            $where['id'] = $id;
            $list = self::where($where)->find();
            if ($list) {
                return $list->toArray();
            }else{
                return [];
            }
        }else{
            $list = self::where($where)->column('name','id');
            if ($list) {
                $str = "<option value=''>选择</option>";
                foreach ($list as $k => $v) {
                    $str .= "<option value='".$k."'>".$v."</option>";
                }
                return $str;
            }
        }
    }

    public static function getProjectByCompany($company_id)
    {
        $where = [
            'm.company_id' => $company_id,
            'p.pid' => 0,
            'p.status' => 1,
        ];
        $fields = 'distinct m.project_id id,p.name';
        $list = Db::table('tb_material_price m')
            ->join('tb_project p', 'm.project_id = p.id', 'left')
            ->field($fields)
            ->where($where)
            ->select();
        if ($list) {
            $str = "<option value=''>选择</option>";
            foreach ($list as $k => $v) {
                $str .= "<option value='" . $v['id'] . "'>" . $v['name'] . "</option>";
            }
            return $str;
        }else{
            return [];
        }
    }
}