<?php
namespace app\admin\validate;

use think\Validate;

class ProjectReport extends Validate
{
    //定义验证规则
    protected $rule = [
        'realper|百分比' => 'require',
    ];

    //定义验证提示
    protected $message = [
        'realper.require' => '请填写项目名称',
    ];
}
