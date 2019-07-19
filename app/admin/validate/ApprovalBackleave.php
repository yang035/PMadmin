<?php
namespace app\admin\validate;

use think\Validate;

class ApprovalBackleave extends Validate
{
    //定义验证规则
    protected $rule = [
        'leave_id|请假' => 'require|number',
        'reason|事由'   =>'length:0,255',
    ];

    //定义验证提示
    protected $message = [
        'leave_id.require' => '选择请假时间段',
        'reason.length' => '事由超过限制255个字符数',
    ];
}
