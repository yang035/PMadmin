<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 9:43
 */

namespace app\admin\model;


use think\Model;

class ReportCheck extends Model
{
    public static function getAll($where=[],$limit=0){
        $list = self::where($where)->order('id desc')->limit($limit)->select();
        if ($list){
            return $list;
        }else{
            return [];
        }
    }

    public static function getRowById($id = 1)
    {
        $map['cid'] = session('admin_user.cid');
        $map['id'] = $id;
        $data = self::where($map)->find();
        return $data;
    }


}