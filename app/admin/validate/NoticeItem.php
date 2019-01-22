<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:02
 */

namespace app\admin\validate;


use think\Validate;

class NoticeItem extends Validate
{
    //定义验证规则
    protected $rule = [
        'cat_id' => 'require',
        'title|标题' => 'require|unique:notice_item',
        'content' => 'require',
    ];

    //定义验证提示
    protected $message = [
        'cat_id.require' => '所属类别必填',
        'title.require' => '请输入标题',
        'title.unique'     => '标题已经存在',
        'content.require' => '请输入内容',
    ];

}