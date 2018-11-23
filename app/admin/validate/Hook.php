<?php
namespace app\admin\validate;

use think\Validate;

class Hook extends Validate
{
    //定义验证规则
    protected $rule = [
        // 'name|钩子名称' => 'require|unique:admin_hook',
    ];
}
