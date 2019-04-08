<?php
namespace app\admin\validate;

use think\Validate;

class ApprovalGoout extends Validate
{
    //定义验证规则
    protected $rule = [
        'address|地点' => 'require',
        'reason|事由'   =>'length:0,255',
    ];

    //定义验证提示
    protected $message = [
        'address.require' => '地点必填',
        'reason.length' => '事由超过限制255个字符数',
    ];
}
