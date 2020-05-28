<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 15:02
 */

namespace app\admin\validate;


use think\Validate;

class SubjectItem extends Validate
{
    //定义验证规则
    protected $rule = [
        'name|名称' => 'require|unique:subject_item,cid^name',
        'idcard|项目编号' => 'require|unique:subject_item,cid^idcard',
        'status|状态设置'  => 'require|in:0,1',
        'score|预设值'  => 'require',
        'cat_id|项目类型'  => 'require',
    ];

    //定义验证提示
    protected $message = [
        'name.require' => '请输入名称',
        'name.unique' => '名称已存在',
        'idcard.require' => '项目编号必填',
        'idcard.unique' => '项目编号重复',
        'status.require'    => '请设置状态',
        'score'  => '预设值必填',
        'cat_id'  => '请先完善项目类型',
    ];

}