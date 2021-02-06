<?php
/**
 * 系统扩展配置，非TP框架配置
 */
return [
    // +----------------------------------------------------------------------
    // | 系统相关设置
    // +----------------------------------------------------------------------
    // 系统数据表
    'tables'            => [
        'admin_config', 
        'admin_menu', 
        'admin_module', 
        'admin_role', 
        'admin_user',
        'admin_hook',
        'admin_hook_plugins',
        'admin_plugins',
        'admin_member',
        'admin_member_level',
    ],
    // 系统会员等级，此处只为声明配置，app/common/behavior/Base.php 里面赋值
    'member_level'      => [],
    // 系统设置分组
    'config_group'      => [
        'base'      => '基础',
        'sys'       => '系统',
        'upload'    => '上传',
        'develop'   => '开发',
        'score'     => '分值',
        'databases'  => '数据库',
    ],
    // 系统标准模块
    'modules' => ['admin', 'common', 'index', 'install', 'hisiphp', 'plugin'],
    // 系统标准配置文件
    'config' => ['app', 'cache', 'cookie', 'database', 'log', 'queue', 'session', 'template', 'trace', 'tb_auth', 'hs_cloud', 'hs_system', 'hisiphp'],
    'sys_type' => [
        1 => '设计单位/设计师',
        2 => '供应商',
        3 => '项目合作',
    ],
    'gys_type' => [
        1 => '建筑建材',
        2 => '日用百货',
        3 => '材料供应',
    ],
    'taocan_year' => [
        1 => '一年',
        2 => '二年',
        3 => '三年',
        4 => '四年',
        5 => '五年',
//        6 => '六年',
    ],
    'experience'=>[
        1 => '1年以下',
        2 => '1-3年',
        3 => '3-5年',
        4 => '5-8年',
        5 => '8-10年',
        6 => '10年以上',
    ]
];