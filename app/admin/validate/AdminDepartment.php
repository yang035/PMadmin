<?php
namespace app\admin\validate;

use think\Validate;

class AdminDepartment extends Validate
{
    //定义验证规则
    protected $rule = [
        'name|名称' => 'require|length:1,50',
        'remark|部门描述'   => 'length:0,200',
        'name' => 'unique:admin_department,pid^name',
    ];

    //定义验证提示
    protected $message = [
        'name.require' => '请输入名称',
        'remark.length'     => '部门描述超过制定长度',
        'name.unique' => '同公司下部门已经存在',
    ];
}
