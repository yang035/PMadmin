<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:02
 */

namespace app\admin\validate;


use think\Validate;

class SubjectContract extends Validate
{
    //定义验证规则
    protected $rule = [
        'subject_id|项目编号' => 'require',
        'status|状态设置'  => 'require|in:0,1',
        'content|合同内容' => 'require',
        'tpl_id|合同模板' => 'require',
        'name|合同名称' => 'require',
        'name' => 'unique:subject_contract,cid^name',
    ];

    //定义验证提示
    protected $message = [
        'subject_id.require' => '项目编号必填',
        'content.require' => '合同内容必填',
        'status.require'    => '请设置状态',
        'tpl_id.require' => '合同模板必填',
        'name.require' => '合同名称必填',
        'name.unique' => '合同名称重复',
    ];

}