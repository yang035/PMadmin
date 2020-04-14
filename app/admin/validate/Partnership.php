<?php
namespace app\admin\validate;

use think\Validate;

class Partnership extends Validate
{
    //定义验证规则
    protected $rule = [
        'name|分类名称' => 'require|unique:partnership,cid^name',
    ];

    //定义验证提示
    protected $message = [
        'name.require' => '请输入分类名称',
        'name.unique' => '分类名称已存在',
    ];
}
