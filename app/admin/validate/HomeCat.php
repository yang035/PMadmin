<?php
namespace app\admin\validate;

use think\Validate;

class HomeCat extends Validate
{
    //定义验证规则
    protected $rule = [
        'name|名称' => 'require|unique:home_cat,cid^name',
    ];

    //定义验证提示
    protected $message = [
        'name.require' => '请输入名称',
        'name.unique'     => '名称已经存在',
    ];
}
