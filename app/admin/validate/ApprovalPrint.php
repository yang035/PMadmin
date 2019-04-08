<?php
namespace app\admin\validate;

use think\Validate;

class ApprovalPrint extends Validate
{
    //定义验证规则
    protected $rule = [
        'type|打印类型' => 'require',
        'project_id|项目编号' => 'require',
        'size_type|纸张类型' => 'require',
        'reason|事由'   =>'length:0,255',
    ];

    //定义验证提示
    protected $message = [
        'type.require' => '打印类型必填',
        'project_id.require' => '项目编号必填',
        'size_type.require' => '纸张类型必填',
        'reason.length' => '事由超过限制255个字符数',
    ];
}
