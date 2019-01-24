<?php
namespace app\admin\validate;

use think\Validate;

class ApprovalReport extends Validate
{
    //定义验证规则
    protected $rule = [
        'mark|内容' => 'require',
    ];

    //定义验证提示
    protected $message = [
        'mark.require' => '请填写内容',
    ];
}
