<?php
namespace app\admin\validate;

use think\Validate;

class ApprovalInvoice extends Validate
{
    //定义验证规则
    protected $rule = [
        'type|费用类型' => 'require',
        'money|金额' => 'require',
        'reason|事由'   =>'length:0,255',
    ];

    //定义验证提示
    protected $message = [
        'type.require' => '费用类型必填',
        'money.require' => '金额必填',
        'reason.length' => '事由超过限制255个字符数',
    ];
}
