<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:00
 */

namespace app\admin\model;


use think\Model;

class Xieyi extends Model
{
    public function cat()
    {
        return $this->hasOne('SubjectItem', 'id', 'subject_id');
    }
}