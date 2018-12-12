<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 16:14
 */
return [
    'approval_status' =>[
        1=>'审批中',
        2=>'已审批',
        3=>'撤销',
        4=>'驳回',
    ],
    'leave_type' =>[
        1=>'年假',
        2=>'事假',
        3=>'病假',
        4=>'调休假',
        5=>'婚假',
        6=>'产假',
        7=>'陪产假',
        8=>'其他',
    ],
    'expense_type' =>[
        1=>'差旅费',
        2=>'交通费',
        3=>'招待费',
        4=>'其他',
    ],
    'res_type' => [
        ''=>'待确认',
        'a'=>'已确认',
        'b'=>'已通过',
        'c'=>'已完成',
        'd'=>'有疑问',
    ],
    'grade_type' => [
        1=>'常规',
        2=>'<font style="color: green">低</font>',
        3=>'<font style="color: blue">中</font>',
        4=>'<font style="color: red">高</font>',
    ],
    'grade_type1' => [
        1=>'ganttGreen',
        2=>'ganttBlue',
        3=>'ganttOrange',
        4=>'ganttRed',
    ],
    'panel_type' => [
        1=>[
            'title'=>'请假调休',
            'href'=>'admin/Approval/leave'
        ],
        2=>[
            'title'=>'报销',
            'href'=>'admin/Approval/expense'
        ],
//        3=>[
//            'title'=>'费用',
//            'href'=>'admin/Approval/cost'
//        ],
        4=>[
            'title'=>'出差',
            'href'=>'admin/Approval/business'
        ],
        5=>[
            'title'=>'采购',
            'href'=>'admin/Approval/procurement'
        ],
        6=>[
            'title'=>'加班',
            'href'=>'admin/Approval/overtime'
        ],
        7=>[
            'title'=>'外出',
            'href'=>'admin/Approval/goout'
        ],
        8=>[
            'title'=>'用车',
            'href'=>'admin/Approval/useCar'
        ],
//        9=>[
//            'title'=>'用章',
//            'href'=>'admin/Approval/useSeal'
//        ],
//        10=>[
//            'title'=>'打卡补卡',
//            'href'=>'admin/Approval/clockIn'
//        ],
        11=>[
            'title'=>'申领用品',
            'href'=>'admin/Approval/officeGood'
        ],
    ],
    'car_type' => [
        1 =>'车辆1',
        2 =>'车辆2',
        3 =>'车辆3',
        4 =>'车辆4',
    ],
    'report_type' => [
        1=>[
            'title'=>'日报',
            'href'=>'admin/DailyReport/add'
        ],
        2=>[
            'title'=>'年度总结',
            'href'=>'admin/DailyReport/annualSummary'
        ],
        3=>[
            'title'=>'年度计划',
            'href'=>'admin/DailyReport/annualPlan'
        ],
    ],
    'index_tab' => [
        1=>[
            'title'=>'记事',
            'href'=>'index/index/lists/id/1',
            'img'=>'jishi.svg',
        ],
        2=>[
            'title'=>'推荐',
            'href'=>'index/index/lists/id/2',
            'img'=>'tuijian.svg',
        ],
        3=>[
            'title'=>'排名',
            'href'=>'index/index/lists/id/3',
            'img'=>'paiming.svg',
        ],
        4=>[
            'title'=>'ML/GL',
            'href'=>'index/index/lists/id/4',
            'img'=>'mg.svg',
        ],
        5=>[
            'title'=>'工作',
            'href'=>'index/index/lists/id/5',
            'img'=>'gongzuo.svg',
        ],
        6=>[
            'title'=>'学习',
            'href'=>'index/index/lists/id/6',
            'img'=>'xuexi.svg',
        ],
        7=>[
            'title'=>'生活',
            'href'=>'index/index/lists/id/7',
            'img'=>'shenghuo.svg',
        ],
        8=>[
            'title'=>'团队',
            'href'=>'index/index/lists/id/8',
            'img'=>'tuandui.svg',
        ],
        9=>[
            'title'=>'视频',
            'href'=>'index/index/lists/id/9',
            'img'=>'tuandui.svg',
        ],
        10=>[
            'title'=>'项目案例',
            'href'=>'index/index/lists/id/10',
            'img'=>'tuandui.svg',
        ],
        11=>[
            'title'=>'TPO',
            'href'=>'index/index/lists/id/11',
            'img'=>'tuandui.svg',
        ],
    ],
    'p_type' => [
        1=>'市政',
        2=>'住宅',
        3=>'旅游',
        5=>'其他',
    ],
    'p_source' => [
        1=>'投标',
        2=>'委托',
    ],
    'unit' => [
        1=>'支',
        2=>'个',
        3=>'本',
        4=>'台',
        5=>'套',
        6=>'盒',
        7=>'箱',
    ],

];