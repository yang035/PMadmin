<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 17:16
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class HezuoCompany extends Model
{
    public static function getOption($id='')
    {
        $cid = session('admin_user.cid');
        $where = [
            'cid' => $cid,
        ];
        $field = 'hc.company_id,c.name';
        $rows = Db::table('tb_hezuo_company hc')
            ->join('tb_admin_company c','hc.company_id=c.id','left')
            ->field($field)
            ->where($where)->select();
        $str = '<option value="">选择</option>';
        foreach ($rows as $k => $v) {
            if ($id == $v['company_id']) {
                $str .= '<option value="'.$v['company_id'].'" selected>'.$v['name'].'</option>';
            } else {
                $str .= '<option value="'.$v['company_id'].'">'.$v['name'].'</option>';
            }
        }
        return $str;

    }

}