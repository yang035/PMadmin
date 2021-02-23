<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:02
 */

namespace app\admin\validate;


use think\Validate;

class Sms extends Validate
{
    //定义验证规则
    protected $rule = [
        'mobile|手机号码' => 'require',
        'content|内容'  => 'require',
    ];

    //定义验证提示
    protected $message = [
        'mobile.require' => '请输入正确手机号码',
        'content.unique' => '请输入内容',
    ];

}