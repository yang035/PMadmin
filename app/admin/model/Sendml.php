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

class Sendml extends Model
{
    public static function getSendmlSta($user){
        $sql = "SELECT * FROM (SELECT * FROM tb_sendml where user={$user} ORDER BY id DESC LIMIT 10000) s GROUP BY s.subject_id";
        $sendml = Db::query($sql);
        $ml = [];
        if ($sendml){
            foreach ($sendml as $v) {
                $ml[$v['subject_id']] = [
                    'benci_fafang' => $v['benci_fafang'],
                    'total_fafang' => $v['total_fafang'],
                ];
            }
        }
        return $ml;
    }
}