<?php
namespace app\admin\validate;

use think\Validate;

class Project extends Validate
{
    //定义验证规则
    protected $rule = [
        'name|名称' => 'require',
        'remark|描述' => 'require',
        'score|分值' => 'require',
        'start_time|开始时间' => 'require|date',
        'end_time|结束时间' => 'require|date',
        'send_user|审批人'   => 'require',
        'reason|事由'   =>'length:0,65',
        'name' => 'unique:project,pid^name',
    ];

    //定义验证提示
    protected $message = [
        'name.require' => '请填写项目名称',
        'name.unique' => '同项目下节点名称不能重复',
        'remark.require' => '请填写项目描述',
        'score.require' => '请填写项目预设值',
        'start_time.require' => '选择开始时间',
        'end_time.require' => '选择结束时间',
        'send_user.require' => '选择审批人',
        'reason.length' => '事由超过限制65个字符数',
    ];
}
