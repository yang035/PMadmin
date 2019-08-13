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
        2=>'同意',
        3=>'撤销',
        4=>'驳回',
        5=>'已发放',
    ],
    'leave_type' =>[
        4=>'调休假',
        1=>'年假',
        2=>'事假',
        3=>'病假',
        5=>'婚假',
        6=>'产假',
        7=>'陪产假',
        8=>'其他',
    ],
    'sex_type' =>[
        1=>'男',
        2=>'女',
    ],
    'relation_type' =>[
        1=>'父母',
        2=>'夫妻',
        3=>'子女',
    ],
    'man_type' =>[
        1=>'正式',
        2=>'试用',
    ],
    'cost_type' =>[
        1=>'差旅费',
        2=>'办公费',
        3=>'招待费',
        4=>'市内交通费',
        5=>'通讯费',
        6=>'采购付款',
        7=>'预支款',
        8=>'其他',
    ],
    'print_type' =>[
        1=>'文本',
        2=>'图册',
        3=>'白图',
        4=>'蓝图',
        5=>'投标文件',
        6=>'电子文件',
        7=>'其它',
    ],
    'size_type' =>[
        1=>'A0',
        2=>'A1',
        3=>'A2',
        4=>'A3',
        5=>'A4',
    ],
    'quality_type' =>[
        1=>'常规',
        2=>'中等',
        3=>'高等',
    ],
    'store_type' =>[
        1=>'A打印店',
        2=>'B打印店',
        3=>'C打印店',
    ],
    'job_rule' =>[
        1=>'点工',
        2=>'日工',
        3=>'计量工',
        4=>'物料名',
    ],
    'job_per_price' =>[
        'hour'=>'15',
        'day'=>'90',
        'square'=>'0',
        'ton'=>'0',
    ],
    'product_type' =>[
        1=>'建筑',
        2=>'景观',
        3=>'园林',
        4=>'其他',
    ],
    'product_ways' =>[
        1=>'使用',
        2=>'推荐',
        3=>'代理',
    ],
    'car_color' =>[
        1=>'白色',
        2=>'黑色',
        3=>'灰色',
        4=>'红色',
        5=>'蓝色',
        6=>'银色',
    ],
    'expense_type' =>[
        1=>'差旅费',
        2=>'交通费',
        3=>'招待费',
        4=>'其他',
    ],
    'overtime_type' =>[
        1=>'晚上',
        2=>'周末/法定调休',
        3=>'法定节假日',
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
        3=>[
            'title'=>'费用申请',
            'href'=>'admin/Approval/cost'
        ],
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
        12=>[
            'title'=>'出图',
            'href'=>'admin/Approval/printView'
        ],
        13=>[
            'title'=>'派遣',
            'href'=>'admin/Approval/dispatch'
        ],
        14=>[
            'title'=>'物品借用',
            'href'=>'admin/Approval/borrow'
        ],
        15=>[
            'title'=>'销假',
            'href'=>'admin/Approval/backLeave'
        ],
        16=>[
            'title'=>'施工签单',
            'href'=>'admin/Approval/signBills'
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
            'title'=>'行政日报',
            'href'=>'admin/DailyReport/administration'
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
    'cat_id' => [
        1=>'市政',
        2=>'住宅',
        3=>'旅游',
        5=>'其他',
    ],
    'p_source' => [
        1=>'投标',
        2=>'委托',
    ],
    't_type' => [
        1=>'长期项目',
        2=>'临时任务',
    ],
    'unit' => [
        1=>'支',
        2=>'个',
        3=>'本',
        4=>'台',
        5=>'套',
        6=>'盒',
        7=>'箱',
        8=>'张',
        9=>'瓶',
        10=>'卷',
        11=>'把',
        12=>'块',
    ],
    'unit2' => [
        1=>'小时',
        2=>'米',
        3=>'平方',
        4=>'吨',
        5=>'天',
    ],
    'bbs_url' => 'http://bbs.imlgl.com/?user-login.htm',
    'partnership_grade' => [
        1=>'一级合伙',
        2=>'二级合伙',
        3=>'三级合伙',
        4=>'四级合伙',
        5=>'五级合伙',
        6=>'六级合伙',
        7=>'七级合伙',
        8=>'八级合伙',
        9=>'九级合伙',
        10=>'十级合伙',
    ],
    'week' => [
        1=>'周一',
        2=>'周二',
        3=>'周三',
        4=>'周四',
        5=>'周五',
        6=>'周六',
        0=>'周日',
    ],
    'p_status' => [
        0=>'全部',
        1=>'当日',
        2=>'逾期',
        3=>'待完成',
        4=>'待评定',
    ],
    's_status' => [
        0=>'全部',
        1=>'立项',
        2=>'进行中',
        3=>'完成待回款',
        4=>'结束',
        5=>'撤销',
    ],
    'gl_give' => 1000,
];