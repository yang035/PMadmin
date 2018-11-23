<?php
namespace app\admin\model;

use think\Model;
use think\Loader;

class AdminHook extends Model
{
    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public function storage($data = [])
    {
        if (empty($data)) {
            $data = request()->post();
        }

        // 如果钩子名称存在直接返回true
        if (self::where('name', $data['name'])->find()) {
            return true;
        }

        // 验证
        $valid = Loader::validate('Hook');
        if($valid->check($data) !== true) {
            $this->error = $valid->getError();
            return false;
        }

        if (isset($data['id']) && !empty($data['id'])) {
            $res = $this->update($data);
        } else {
            $res = $this->create($data);
        }
        if (!$res) {
            $this->error = '保存失败！';
            return false;
        }
        
        return $res;
    }

    public static function delHook($source = '')
    {
        if (empty($source)) {
            return false;
        }

        if (self::where('source', $source)->delete() === false) {
            return false;
        }
        return true;
    }
}