<?php

namespace app\common\controller;
use think\View;
use think\Controller;

/**
 * 框架公共控制器
 * @package app\common\controller
 */
class Common extends Controller
{
    protected function _initialize() {}

    /**
     * 解析和获取模板内容 用于输出
     * @param string    $template 模板文件名或者内容
     * @param array     $vars     模板输出变量
     * @param array     $replace 替换内容
     * @param array     $config     模板参数
     * @param bool      $renderContent     是否渲染内容
     * @return string
     * @throws Exception
     */
    final protected function fetch($template = '', $vars = [], $replace = [], $config = [], $renderContent = false)
    {
        if (defined('IS_PLUGINS')) {
            return self::pluginsFetch($template , $vars , $replace , $config , $renderContent);
        }
        return parent::fetch($template , $vars , $replace , $config , $renderContent);
    }
    
    /**
     * 渲染插件模板
     * @param string    $template 模板文件名或者内容
     * @param array     $vars     模板输出变量
     * @param array     $replace 替换内容
     * @param array     $config     模板参数
     * @param bool      $renderContent     是否渲染内容
     * @return string
     * @throws Exception
     */
    final protected function pluginsFetch($template = '', $vars = [], $replace = [], $config = [], $renderContent = false)
    {
        $plugin = $_GET['_p'];
        $controller = $_GET['_c'];
        $action = $_GET['_a'];
        if (!$template) {
            $template = $controller.'/'.$action;
        } elseif (strpos($template, '/') == false) {
            $template = $controller.'/'.$template;
        }

        if(defined('ENTRANCE') && ENTRANCE == 'admin') {
            $template = 'admin/'.$template;
        } else {
            $template = 'home/'.$template;
        }

        $template_path = strtolower("plugins/{$plugin}/view/{$template}.".config('template.view_suffix'));
        return parent::fetch($template_path, $vars, $replace, $config, $renderContent);
    }
}