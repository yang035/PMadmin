<?php
namespace app\admin\validate;

use think\Validate;

class ApprovalOvertime extends Validate
{
    //定义验证规则
    protected $rule = [
        'time_long|时长' => 'require',
        'reason|事由'   =>'length:0,255',
    ];

    //定义验证提示
    protected $message = [
        'time_long.require' => '时长必填',
        'reason.length' => '事由超过限制255个字符数',
    ];
}
