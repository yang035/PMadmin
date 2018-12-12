<?php
namespace app\admin\validate;

use think\Validate;

class ApprovalGoods extends Validate
{
    //定义验证规则
    protected $rule = [
        'reason|事由'   =>'length:0,65',
    ];

    //定义验证提示
    protected $message = [
        'reason.length' => '事由超过限制65个字符数',
    ];
}
