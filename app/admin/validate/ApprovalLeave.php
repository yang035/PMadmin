<?php
namespace app\admin\validate;

use think\Validate;

class ApprovalLeave extends Validate
{
    //定义验证规则
    protected $rule = [
        'type|类型' => 'require|number',
        'reason|事由'   =>'length:0,255',
    ];

    //定义验证提示
    protected $message = [
        'type.require' => '选择请假类型',
        'reason.length' => '事由超过限制255个字符数',
    ];
}
