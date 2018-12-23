<?php
namespace app\admin\validate;

use think\Validate;

class ScoreDeal extends Validate
{
    //定义验证规则
    protected $rule = [
        'rid|事件'   => 'require',
        'remark|描述' => 'require|length:0,200',
    ];

    //定义验证提示
    protected $message = [
        'rid.require'     => '事件必填',
        'remark.require'     => '事件描述必填',
        'remark.length'     => '描述超过制定长度',
    ];
}
