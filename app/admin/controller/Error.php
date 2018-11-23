<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/10
 * Time: 8:30
 */

namespace app\admin\controller;


use think\Request;

class Error extends Admin
{
    public function index(Request $request)
    {
        $controller = $request->controller();
        $this->error("控制器{$controller}不存在",'/admin.php');
    }

}