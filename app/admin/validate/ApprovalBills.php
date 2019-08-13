<?php
namespace app\admin\validate;

use think\Validate;

class ApprovalBills extends Validate
{
    //定义验证规则
    protected $rule = [
        'project_id|项目编号' => 'require',
//        'reason|事由'   =>'length:0,255',
    ];

    //定义验证提示
    protected $message = [
        'project_id.require' => '项目编号必填',
//        'reason.length' => '事由超过限制255个字符数',
    ];
}
