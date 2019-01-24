<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 15:03
 */

namespace app\admin\model;


use think\Model;

class ApprovalReport extends Model
{
    public static function getAll($where=[],$limit=0){
        $list = self::where($where)->order('id desc')->limit($limit)->select();
        if ($list){
            return $list;
        }else{
            return [];
        }
    }

}