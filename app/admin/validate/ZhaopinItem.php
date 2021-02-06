<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:02
 */

namespace app\admin\validate;


use think\Validate;

class ZhaopinItem extends Validate
{
    //定义验证规则
    protected $rule = [
        'title|标题' => 'require|unique:zhaopin_item,cid^title',
        'status|状态设置'  => 'require|in:0,1',
    ];

    //定义验证提示
    protected $message = [
        'title.require' => '请输入标题',
        'title.unique' => '标题已存在',
        'status.require'    => '请设置状态',
    ];

}