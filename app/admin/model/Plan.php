<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 15:03
 */

namespace app\admin\model;


use think\Model;

class Plan extends Model
{
    public static function getOption($id = 0)
    {
        $where = [];
        $res = self::where($where)->select();
        $str = '';
        if ($res){
            foreach ($res as $k => $v) {
                if ($id == $v['id']) {
                    $str .= '<option value="'.$v['id'].'" selected>'.$v['name'].'</option>';
                } else {
                    $str .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                }
            }
            return $str;
        }
    }

}