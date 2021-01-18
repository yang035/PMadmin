<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 17:16
 */

namespace app\admin\model;


use think\Model;

class HezuoPerson extends Model
{
    public static function getRow($company_id,$uid)
    {
        $where = [
            'company_id' => $company_id,
            'person_id' => $uid,
        ];
        $row = self::where($where)->find();
        return $row;
    }

}