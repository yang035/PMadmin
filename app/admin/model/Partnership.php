<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/11
 * Time: 10:45
 */

namespace app\admin\model;


use think\Model;

class Partnership extends Model
{
    protected $autoWriteTimestamp = true;

    public static function getCompanyById($id){
        $result = [];
        $where = [
            'id'=>$id
        ];
        $result = self::where($where)->find()->toArray();
        return $result;
    }
    public static function getPartnershipGrade($grade = 0)
    {
        $grade_type = config('other.partnership_grade');
        $str = '';
        foreach ($grade_type as $k => $v) {
            if ($grade == $k) {
                $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
            } else {
                $str .= '<option value="'.$k.'">'.$v.'</option>';
            }
        }
        return $str;
    }

    public static function getPartnerGrade($grade = 0)
    {
        $grade_type = config('other.partnership_grade');
        $map = [
            'cid'=>session('admin_user.cid'),
        ];
        $data = self::where($map)->column('partnership_grade','id');
        $tmp = [];
        if ($data){
            foreach ($data as $k=>$v) {
                $tmp[$v] = $grade_type[$v];
            }
        }
        return $tmp;
    }

}