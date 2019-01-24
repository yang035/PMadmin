<?php
namespace app\admin\validate;

use think\Validate;

class ApprovalReportReply extends Validate
{
    //定义验证规则
    protected $rule = [
        'content|内容' => 'require',
    ];

    //定义验证提示
    protected $message = [
        'content.require' => '请填写内容',
    ];
}
