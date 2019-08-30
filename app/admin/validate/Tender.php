<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 17:17
 */

namespace app\admin\validate;


use think\Validate;

class Tender extends Validate
{
    //定义验证规则
    protected $rule = [
//        'real_per|百分比' => 'number|between:1,100',
        'send_user|汇报人' => 'require',
    ];

    //定义验证提示
    protected $message = [
//        'real_per.number' => '必须是数字',
//        'real_per.between' => '百分比只能在1-100之间',
        'send_user.require' => '汇报人不能为空',
    ];

}