<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:02
 */

namespace app\admin\validate;


use think\Validate;

class SubjectContractLog extends Validate
{
    //定义验证规则
    protected $rule = [
        'subject_id|项目编号' => 'require',
        'content|洽商记录' => 'require',
        'contract_id|合同编号' => 'require',
    ];

    //定义验证提示
    protected $message = [
        'subject_id.require' => '项目编号必填',
        'content.require' => '洽商记录必填',
        'contract_id.require' => '合同编号必填',
    ];

}