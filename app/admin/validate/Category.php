<?php
namespace app\admin\validate;

use think\Validate;

class Category extends Validate
{
    //定义验证规则
    protected $rule = [
        'name|名称' => 'require|length:1,50',
        'remark|类型描述'   => 'length:0,200',
        'name' => 'unique:shopping_category,pid^name',
    ];

    //定义验证提示
    protected $message = [
        'name.require' => '请输入名称',
        'remark.length'     => '类型描述超过制定长度',
        'name.unique' => '同公司下类型已经存在',
    ];
}
