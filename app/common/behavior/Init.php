<?php
namespace app\common\behavior;
use think\Request;
use app\admin\model\AdminModule as ModuleModel;
/**
 * 应用初始化行为
 */
class Init
{
    public function run(&$params)
    {
        define('IN_SYSTEM', true);
        $request = Request::instance();
        // 安装操作直接return
        if(defined('BIND_MODULE')) return;
        $_path = $request->path();
        $default_module = false;
        if ($_path != '/' && strtolower($_path) != 'index' && !defined('BIND_MODULE')) {
            $_path = explode('/', $_path);
            if (isset($_path[0]) && !empty($_path[0])) {
                if (is_dir('./app/'.$_path[0]) || $_path[0] == 'plugins') {
                    $default_module = true;
                    if ($_path[0] == 'plugins') {
                        define('BIND_MODULE', 'index');
                        define('PLUGIN_ENTRANCE', true);
                    }
                }
            }
        }
        if (!defined('PLUGIN_ENTRANCE') && !defined('CLOUD_ENTRANCE') && $default_module === false && !defined('BIND_MODULE')) {
            // 设置前台默认模块
            $map = [];
            $map['default'] = 1;
            $map['status'] = 2;
            $map['name'] =  ['neq', 'admin'];
            $def_mod = ModuleModel::where($map)->value('name');
            if ($def_mod && !defined('ENTRANCE')) {
                define('BIND_MODULE', $def_mod);
            }
        }
        // 后台强制关闭路由
        if (defined('ENTRANCE') && ENTRANCE == 'admin') {
            config('url_route_on', false);
            config('url_controller_layer', 'controller');
        } else {
            // 设置路由
            config('route_config_file', ModuleModel::moduleRoute());
        }
    }
}
