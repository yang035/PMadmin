<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 9:43
 */

namespace app\admin\model;


use think\Model;

class ScoreDay extends Model
{
    /**
     * @param $data
     * @return bool|void
     * $data = [
    'project_id'=>0,
    'user'=>0,
    'ml_add_score'=>0,
    'ml_sub_score'=>0,
    'gl_add_score'=>0,
    'gl_sub_score'=>0
    'remark'=>''
    ];
     * 必须包含这几个参数，
     * 用于插入分值
     */
    public static function addScore($data){
        $data['user_id'] = session('admin_user.uid');
        if (!self::create($data)){
            return false;
        }
        return true;
    }

    //关联用户
    public function adminUser()
    {
        return $this->hasOne('AdminUser', 'id', 'user')->field('username,realname');
    }

    //关联项目
    public function scoreProject()
    {
        return $this->hasOne('Project', 'id', 'project_id')->field('*');
    }

}