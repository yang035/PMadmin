<?php
namespace app\admin\validate;

use think\Validate;

class ApprovalCarrecord extends Validate
{
    //定义验证规则
    protected $rule = [
        'start_address|起点' => 'require',
        'end_address|终点' => 'require',
        'mileage|行驶里程' => 'require',
        'reason|事由'   =>'length:0,255',
    ];

    //定义验证提示
    protected $message = [
        'start_address.require' => '起点必填',
        'end_address.require' => '终点必填',
        'mileage.require' => '行驶里程必填',
        'reason.length' => '事由超过限制255个字符数',
    ];
}
