<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:02
 */

namespace app\admin\validate;


use think\Validate;

class AssetItem extends Validate
{
    //定义验证规则
    protected $rule = [
        'name|名称' => 'require',
        'deal_user|审批人'   => 'require',
    ];

    //定义验证提示
    protected $message = [
        'name.require' => '请输入名称',
        'deal_user|审批人'   => 'require',
    ];

}