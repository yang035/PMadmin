<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/11
 * Time: 10:45
 */

namespace app\admin\model;


use think\Model;

class Partner extends Model
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

}