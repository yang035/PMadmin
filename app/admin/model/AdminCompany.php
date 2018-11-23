<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/11
 * Time: 10:45
 */

namespace app\admin\model;


use think\Model;

class AdminCompany extends Model
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
    public static function getOption($id = 0)
    {
        $rows = self::column('id,name');
        $str = '';
        foreach ($rows as $k => $v) {
            if ($k == 1) {// 过滤超级管理员角色
                continue;
            }
            if ($id == $k) {
                $str .= '<option value="'.$k.'" selected>'.$v.'</option>';
            } else {
                $str .= '<option value="'.$k.'">'.$v.'</option>';
            }
        }
        return $str;
    }

}