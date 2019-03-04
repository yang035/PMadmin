<?php
namespace app\admin\validate;

use think\Validate;

class Goods extends Validate
{
    //定义验证规则
    protected $rule = [
        'title|名称' => 'require|length:1,50',
        'description|概述'   => 'length:0,200',
        'title' => 'unique:shopping_goods,cat_id^title',
    ];

    //定义验证提示
    protected $message = [
        'title.require' => '请输入名称',
        'description.length'     => '概述超过限制长度',
        'title.unique' => '同类型下商品已经存在',
    ];
}
