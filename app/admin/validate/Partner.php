<?php
namespace app\admin\validate;

use think\Validate;

class Partner extends Validate
{
    //定义验证规则
    protected $rule = [
//        'name|名称' => 'require|unique:admin_company',
//        'cellphone|手机号'   => 'requireWith:mobile|regex:^1\d{10}',
    ];

    //定义验证提示
    protected $message = [
//        'name.require' => '请输入名称',
//        'mobile.regex'     => '手机号不正确',
    ];
}
