<?php
namespace app\admin\validate;

use think\Validate;

class Partner extends Validate
{
    //定义验证规则
    protected $rule = [
        'partnership_grade' => 'unique:partner,cid^partnership_grade',
//        'name|名称' => 'require|unique:admin_company',
//        'cellphone|手机号'   => 'requireWith:mobile|regex:^1\d{10}',
    ];

    //定义验证提示
    protected $message = [
        'partnership_grade.unique' => '同公司下合伙级别不能重复',
//        'mobile.regex'     => '手机号不正确',
    ];
}
