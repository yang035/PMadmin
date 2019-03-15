<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/15
 * Time: 14:22
 */

namespace app\admin\controller;

use app\admin\model\ReportCheck as ReportCheckModel;
class ReportCheck extends Admin
{
    public static function getAll($id=0,$limit){
        $report_id = input('id/d');
        if (!empty($id)){
            $report_id = $id;
        }
        $map['report_id'] = $report_id;
        $map['cid'] = session('admin_user.cid');
        $list = ReportCheckModel::getAll($map,$limit);
        return $list;
    }

}