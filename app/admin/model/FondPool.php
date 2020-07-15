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

class FondPool extends Model
{
    public static function getSta()
    {
        $where = [
            'cid' => session('admin_user.cid'),
            'user' => session('admin_user.uid'),
            'is_fafang' => 1,
        ];
        $field = 'sum(add_fond) as total_fond,sum(sub_fond) as has_tixian';
        $row = self::field($field)->where($where)->find();
        if ($row){
            $row['no_tixian'] = $row['total_fond'] - $row['has_tixian'];
        }else{
            $row['no_tixian'] = 0;
        }
        return $row;
    }
}