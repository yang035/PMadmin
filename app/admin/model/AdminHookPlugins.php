<?php
namespace app\admin\model;

use think\Model;
use app\admin\model\AdminHook as HookModel;

class AdminHookPlugins extends Model
{

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public static function storage($hooks = [], $plugins = '')
    {
        if (!empty($hooks) && is_array($hooks)) {
            $hook_mod = new HookModel();
            // 添加钩子
            foreach ($hooks as $k => $v) {
                if (is_numeric($k)) {
                    $k = $v;
                }
                if (!$hook_mod->storage(['name' => $k, 'source' => 'plugins.'.$plugins, 'intro' => $v])) {
                    return false;
                }
            }

            $data = [];
            foreach ($hooks as $k => $v) {
                if (is_numeric($k)) {
                    $k = $v;
                }
                // 清除重复数据
                if (self::where(['hook' => $k, 'plugins' => $plugins])->find()) {
                    continue;
                }
                $data[] = [
                    'hook'      => $k,
                    'plugins'   => $plugins,
                    'ctime'     => request()->time(),
                    'mtime'     => request()->time(),
                ];
            }
            
            if (empty($data)) {
                return true;
            }

            return self::insertAll($data);
        }
        return false;
    }

    public static function del($plugins = '')
    {
        if (!empty($plugins)) {
            // 删除插件钩子
            if (!HookModel::delHook('plugins.'.$plugins)) {
                return false;
            }
            // 删除索引
            if (self::where('plugins', $plugins)->delete() === false) {
                return false;
            }
        }
        return true;
    }
}