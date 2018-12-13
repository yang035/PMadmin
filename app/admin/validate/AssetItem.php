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
        'number|数量' => 'require',
        'manager_user|存储人' => 'require',
        'deal_user|使用人'   => 'require',
    ];

    //定义验证提示
    protected $message = [
        'number.require' => '物品数量不能为空',
        'manager_user.require' => '存储人不能为空',
        'deal_user.require'   => '使用人不能为空',
    ];

}