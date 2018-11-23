<?php
namespace app\common\controller;

use think\View;
use think\Exception;

/**
 * 插件类
 * @package app\common\controller
 */
abstract class Plugins
{
    /**
     * @var 视图实例对象
     */
    protected $view = null;

    /**
     * @var string 错误信息
     */
    protected $error = '';

    /**
     * @var string 插件名
     */
    public $pluginsName = '';

    /**
     * @var string 插件路径
     */
    public $pluginsPath = '';

    /**
     * 插件构造方法
     */
    public function __construct()
    {
        // 获取插件名
        $class = get_class($this);
        $this->pluginsName = substr($class, strrpos($class, '\\') + 1);
        $this->pluginsPath = ROOT_PATH.'plugins/'.$this->pluginsName.'/';
        $this->view = new View();
    }

    /**
     * 获取插件基础信息
     * @param string $key 主键
     * @return mixed
     */
    final protected function getInfo($key = '')
    {
        $info = model('admin/AdminPlugins')->where('name', $this->pluginsName)->find();
        if (!$info) {
            return '';
        }

        if ($key && isset($info[$key])) {
            return $info[$key];
        }

        return $info;
    }

    /**
     * 获取插件配置
     * @param string $key 主键
     * @return mixed
     */
    final protected function getConfig($key = '')
    {
        $config = model('admin/AdminPlugins')->where('name', $this->pluginsName)->value('config');
        if (!$config) {
            return '';
        } else {
            $config = json_decode($config, 1);
        }

        if ($key && isset($config[$key])) {
            return $config[$key];
        }

        return $config;
    }

    /**
     * 插件模板变量赋值
     * @param string $name 模板变量
     * @param string $value 变量的值
     * @return $this
     */
    final protected function assign($name = '', $value='')
    {
        $this->view->assign($name, $value);
        return $this;
    }

    /**
     * 模板渲染[仅限钩子方法调用]
     * @param string $template 模板名
     * @param array $vars 模板输出变量
     * @param array $replace 替换内容
     * @param array $config 模板参数
     * @return mixed
     */
//    final protected function fetch($template = '', $vars = [], $replace = [], $config = [], $renderContent = false)
//    {
//        if ($template) {
//            $template = $this->pluginsPath. 'view/widget/'. $template . '.' . config('template.view_suffix');
//        } else {
//            throw new Exception('钩子模板不允许为空');
//        }
//
//        $this->view->engine->layout(false);
//        echo $this->view->fetch($template, $vars, $replace, $config, $renderContent);
//    }

    /**
     * 获取错误信息
     * @return string
     */
    final public function getError()
    {
        return $this->error;
    }

    /**
     * 插件安装方法
     * @return mixed
     */
    abstract public function install();

    /**
     * 安装后的业务处理
     * @return bool
     */
    abstract public function installAfter();

    /**
     * 插件卸载方法
     * @return mixed
     */
    abstract public function uninstall();

    /**
     * 卸载后的业务处理
     * @return bool
     */
    abstract public function uninstallAfter();
}