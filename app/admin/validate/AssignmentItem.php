<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:02
 */

namespace app\admin\validate;


use think\Validate;

class AssignmentItem extends Validate
{
    //定义验证规则
    protected $rule = [
        'content|名称' => 'require',
    ];

    //定义验证提示
    protected $message = [
        'name.require' => '请输入名称',
    ];

}