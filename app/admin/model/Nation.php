<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/22
 * Time: 14:36
 */

namespace app\admin\model;


use think\Model;

class Nation extends Model
{
    public static function getOption($type = 0)
    {
        $data = self::select();
        $str = '';
        if ($data){
            foreach ($data as $k => $v) {
                if ($type == $k) {
                    $str .= '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                } else {
                    $str .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                }
            }
        }
        return $str;
    }

}