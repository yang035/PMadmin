<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:02
 */

namespace app\admin\validate;


use think\Validate;

class Ziyuan extends Validate
{
    //定义验证规则
    protected $rule = [
        'name|名称' => 'require|unique:ziyuan,cid^name',
        'status|状态设置'  => 'require|in:0,1',
    ];

    //定义验证提示
    protected $message = [
        'name.require' => '请输入名称',
        'name.unique' => '名称已存在',
        'status.require'    => '请设置状态',
    ];

}