<?php
namespace app\admin\validate;

use think\Validate;

class ScoreRule extends Validate
{
    //定义验证规则
    protected $rule = [
        'name|描述'   => 'require|length:0,200',
        'name' => 'unique:score_rule,cid^name',
    ];

    //定义验证提示
    protected $message = [
        'name.require'     => '事件描述必填',
        'name.length'     => '描述超过制定长度',
        'name.unique' => '同类型下规则已经存在',
    ];
}
