<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 15:03
 */

namespace app\admin\model;


use think\Model;

class ApprovalReportReply extends Model
{
    public static function getAll($where=[],$limit=0){
        $list = self::where($where)->order('id desc')->limit($limit)->select();
        if ($list){
            foreach ($list as $k=>$v){
                $list[$k]['child'] = [];
                $list[$k]['child'] = self::where('pid',$v['id'])->order('id desc')->limit($limit)->select();
            }
        }
        if ($list){
            return $list;
        }else{
            return [];
        }
    }

}