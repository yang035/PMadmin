<?php
namespace app\admin\validate;

use think\Validate;

class ApprovalExpense extends Validate
{
    //定义验证规则
    protected $rule = [
        'reason|事由'   =>'length:0,255',
    ];

    //定义验证提示
    protected $message = [
        'reason.length' => '事由超过限制255个字符数',
    ];
}
