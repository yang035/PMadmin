<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 15:03
 */

namespace app\admin\model;


use think\Model;

class ApprovalBorrow extends Model
{
    public static function getOption()
    {
        $redis = service('Redis');
        $cid = session('admin_user.cid');
        $borrow = $redis->get("pm:zichan:borrow:".$cid);
        $borrow = json_decode($borrow,true);
        $str = '';
        if ($borrow){
            foreach ($borrow as $k => $v) {
                $str .= '<input type="checkbox" name="borrow_option[]" lay-skin="primary" title="'.$v.'" value="'.$v.'"><br>';
            }
        }
        return !empty($str) ? $str : '无';
    }

}