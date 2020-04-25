<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:02
 */

namespace app\admin\validate;


use think\Validate;

class Xieyi extends Validate
{
    //定义验证规则
    protected $rule = [
        'subject_id|项目ID' => 'require',
        'begin_date|开始时间' => 'require',
        'end_date|截止时间' => 'require',
        'remain_work|剩余工作量' => 'require',
//        'part|阶段' => 'require',
//        'part_ratio|阶段系数'  => 'require|in:0,1',
    ];

    //定义验证提示
    protected $message = [
        'subject_id.require' => '项目不存在',
        'begin_date.require' => '开始时间必填',
        'end_date.require' => '截止时间必填',
        'remain_work.require'    => '剩余工作量必填',
//        'part.require' => '阶段必选',
//        'part_ratio.require'    => '阶段系数必填',
    ];

}