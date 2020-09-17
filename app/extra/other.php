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
        6=>'超时作废',
    ],
    'leave_type' =>[
        4=>'调休假',
        1=>'年假',
        2=>'事假',
        3=>'病假',
        5=>'婚假',
        6=>'产假',
        7=>'陪产假',
//        8=>'其他',
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
        1=>'臻彩彩印',
        2=>'三创图文',
        3=>'大志图文',
        4=>'其他',
    ],
    'store_type1' =>[
        1=>'待添加',
        4=>'其他',
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
        1=>'采购费',
        2=>'办公费',
        3=>'差旅费',
        4=>'市内交通费',
        5=>'打印费',
        6=>'车辆费用',
        7=>'招待费',
        8=>'工资',
        9=>'福利费',
        10=>'服务费',
        11=>'快递费',
        12=>'水电费',
        13=>'房租',
        14=>'劳保费',
        15=>'备用金',
        16=>'借支款',
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
            'title'=>'工作清单',
            'href'=>'admin/Approval/signBills'
        ],
        17=>[
            'title'=>'提现',
            'href'=>'admin/Approval/tixian'
        ],
        18=>[
            'title'=>'推荐信',
            'href'=>'admin/Approval/leaveOffice'
        ],
    ],
    'car_type' => [
        1 =>'车辆1',
        2 =>'车辆2',
        3 =>'车辆3',
        4 =>'车辆4',
    ],
    'report_type' => [
//        1=>[
//            'title'=>'日报',
//            'href'=>'admin/DailyReport/add'
//        ],
        2=>[
            'title'=>'日报',
            'href'=>'admin/DailyReport/administration'
        ],
        3=>[
            'title'=>'周报',
            'href'=>'admin/DailyReport/week'
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
        6=>'立方',
        7=>'株',
        8=>'斤',
        9=>'箱',
        10=>'把',
        11=>'个',
        12=>'件',
        13=>'包',
        14=>'捆',
        15=>'公斤',
        16=>'袋',
        17=>'车',
        18=>'块',
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
    'p_type' => [
        1=>'内部',
        2=>'公开',
    ],
    'time_type' => [
        1=>'其他日期',
        2=>'今日',
        3=>'本周',
    ],
    //暂时屏蔽2-3级
    'three_level' => [
        1=>'一级(负责人)',
//        2=>'二级(负责人+总负责人)',
//        3=>'三级(负责人+总负责人+审批人)',
    ],
    'is_period' => [
        1=>'常规任务',
        2=>'阶段任务',
    ],
    'is_private' => [
        0=>'公开',
        1=>'保密',
    ],
    'association_coefficient' => [
        1=>'阶段系数',
        2=>'合伙系数',
        3=>'GL排名系数',
    ],
    'part' => [
        1=>'第一阶段',
        2=>'第二阶段',
        3=>'第三阶段',
    ],
    'visible_range' => [
        1=>'公司内部',
        2=>'平台',
    ],
    'visible_range' => [
        1=>'公司内部',
        2=>'平台',
    ],
    'stage' => [
        '0101'=>'招标/资审公告',
        '0102'=>'开标记录',
        '0104'=>'交易结果公示',
        '0105'=>'招标/资审文件澄清',
    ],
    'pay_status' => [
        0=>'无需支付',
        1=>'待支付',
        2=>'已支付',
        3=>'无效订单',
        4=>'逾期未支付',
        5=>'退款成功',
        6=>'退款失败',
    ],
];