<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/11
 * Time: 10:45
 */

namespace app\admin\model;


use think\Model;

class GraphicCompany extends Model
{
    protected $autoWriteTimestamp = true;

    public static function getOption($type = 0)
    {
        $map = [
            'cid'=>session('admin_user.cid'),
            'status'=>1,
        ];
        $data = self::where($map)->select();
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

    public static function getOption1(){
        $map = [
            'cid'=>session('admin_user.cid'),
        ];
        $data = self::where($map)->column('name','id');
        return $data;
    }
}