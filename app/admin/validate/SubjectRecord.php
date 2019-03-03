<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:02
 */

namespace app\admin\validate;


use think\Validate;

class SubjectRecord extends Validate
{
    //定义验证规则
    protected $rule = [
        'subject_id|项目编号' => 'require',
        'status|状态设置'  => 'require|in:0,1',
        'content|洽商记录' => 'require',
//        'report|洽商报告' => 'require',
    ];

    //定义验证提示
    protected $message = [
        'subject_id.require' => '项目编号必填',
        'content.require' => '洽商记录必填',
        'status.require'    => '请设置状态',
//        'report.require' => '洽商报告必填',
    ];

}