<?php
namespace app\admin\model;

use think\Model;
use app\admin\model\AdminUser as UserModel;
class AdminLog extends Model
{
    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    
    public function user()
    {
        return $this->hasOne('AdminUser', 'id', 'uid');
    }
}
