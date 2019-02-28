<?php
namespace app\admin\validate;

use think\Validate;

class ApprovalProcurement extends Validate
{
    //定义验证规则
    protected $rule = [
        'name|名称' => 'require',
        'number|数量' => 'require',
        'amount|总价' => 'require',
        'reason|事由'   =>'length:0,255',
    ];

    //定义验证提示
    protected $message = [
        'name.require' => '名称必填',
        'number.require' => '数量必填',
        'amount.require' => '总价必填',
        'reason.length' => '事由超过限制255个字符数',
    ];
}
