<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
    <TITLE>jquery下拉列表树插件代码</TITLE>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="__PUBLIC_JS__/dtree/dtreeck.css?v=">
    <script src="__PUBLIC_JS__/dtree/dtreeck.js?v="></script>
    <script src="__PUBLIC_JS__/jquery.2.1.4.min.js?v="></script>
    <style type="text/css">
        body,td,th {
            font-size: 12px;
        }
    </style>
    <style type="text/css">
        a:link {
            font-family: "宋体";
            font-size: 12px;
            color: #0000FF;
            text-decoration: none;
        }
        a:visited {
            font-family: "宋体";
            font-size: 12px;
            color: #0000FF;
            text-decoration: none;
        }
        a:hover {
            font-family: "宋体";
            font-size: 12px;
            color: #CC6600;
            text-decoration: none;
        }
        a:active {
            font-family: "宋体";
            font-size: 12px;
            color: #006600;
            text-decoration: none;
        }
    </style>
</HEAD>
<BODY topmargin="0" leftmargin="0">
                <table width="70%" cellpadding="1" cellspacing="0" border="0" style="margin-top: 10px;">
                    <tr>
                        <td width="20%" align="right">父 菜 单： </td>
                        <td width="30%" align="left">
                            <input type="text" id="menu_parent_name" style="width: 150px;">
                            <input type="hidden" id="menu_parent" name="menu_parent">
                        </td>
                        <td width="20%" align="right"></td>
                        <td width="30%" align="left">
                        </td>
                    </tr>
                </table>
<div id="treediv" style="display: none;position:absolute;overflow:scroll;  width: 150px;height:200px;  padding: 5px;background: #fff;color: #fff;border: 1px solid #cccccc">
    <div align="right"><a href="##" id="closed"><font color="#000">关闭&nbsp;</font></a></div>
    <script language="JavaScript" type="text/JavaScript">
        //树代码
        mydtree = new dTree('mydtree','/static/js/dtree/img/','no','no');
        mydtree.add(0,
            -1,
            "根目录",
            "javascript:setvalue('0','根目录')",
            "根目录",
            "_self",
            false);
        mydtree.add(37,
            0,
            'PDA消息管理',
            "javascript:setvalue('37','PDA消息管理')",
            'PDA消息管理',
            '_self',
            false);
        mydtree.add(40,
            0,
            '法律法规管理',
            "javascript:setvalue('40','法律法规管理')",
            '法律法规管理',
            '_self',
            false);
        mydtree.add(44,
            0,
            '基础信息管理',
            "javascript:setvalue('44','基础信息管理')",
            '基础信息管理',
            '_self',
            false);
        mydtree.add(47,
            0,
            '卫生监督',
            "javascript:setvalue('47','卫生监督')",
            '卫生监督',
            '_self',
            false);
        mydtree.add(50,
            0,
            '地图',
            "javascript:setvalue('50','地图')",
            '地图',
            '_self',
            false);
        mydtree.add(66,
            0,
            '信息查询',
            "javascript:setvalue('66','信息查询')",
            '信息查询',
            '_self',
            false);
        mydtree.add(73,
            0,
            '量化分级管理',
            "javascript:setvalue('73','量化分级管理')",
            '量化分级管理',
            '_self',
            false);
        mydtree.add(80,
            0,
            '专家库管理',
            "javascript:setvalue('80','专家库管理')",
            '专家库管理',
            '_self',
            false);
        mydtree.add(92,
            0,
            '调度通知信息',
            "javascript:setvalue('92','调度通知信息')",
            '调度通知信息',
            '_self',
            false);
        mydtree.add(94,
            0,
            '统计报表',
            "javascript:setvalue('94','统计报表')",
            '统计报表',
            '_self',
            false);
        mydtree.add(84,
            0,
            '任务管理',
            "javascript:setvalue('84','任务管理')",
            '任务管理',
            '_self',
            false);
        mydtree.add(89,
            0,
            '考勤管理',
            "javascript:setvalue('89','考勤管理')",
            '考勤管理',
            '_self',
            false);
        mydtree.add(96,
            0,
            '卫生保障',
            "javascript:setvalue('96','卫生保障')",
            '卫生保障',
            '_self',
            false);
        mydtree.add(103,
            0,
            '预警信息',
            "javascript:setvalue('103','预警信息')",
            '预警信息',
            '_self',
            false);
        mydtree.add(110,
            0,
            '工作量统计',
            "javascript:setvalue('110','工作量统计')",
            '工作量统计',
            '_self',
            false);
        mydtree.add(1,
            0,
            '系统管理',
            "javascript:setvalue('1','系统管理')",
            '系统管理',
            '_self',
            false);
        mydtree.add(2,
            1,
            '角色管理',
            "javascript:setvalue('2','角色管理')",
            '角色管理',
            '_self',
            false);
        mydtree.add(3,
            1,
            '权限管理',
            "javascript:setvalue('3','权限管理')",
            '权限管理',
            '_self',
            false);
        mydtree.add(4,
            1,
            '菜单管理',
            "javascript:setvalue('4','菜单管理')",
            '菜单管理',
            '_self',
            false);
        mydtree.add(5,
            1,
            '部门管理',
            "javascript:setvalue('5','部门管理')",
            '部门管理',
            '_self',
            false);
        mydtree.add(28,
            1,
            '数据字典管理',
            "javascript:setvalue('28','数据字典管理')",
            '数据字典管理',
            '_self',
            false);
        mydtree.add(29,
            1,
            '组织人员管理',
            "javascript:setvalue('29','组织人员管理')",
            '组织人员管理',
            '_self',
            false);
        mydtree.add(30,
            1,
            '人员管理',
            "javascript:setvalue('30','人员管理')",
            '人员管理',
            '_self',
            false);
        mydtree.add(152,
            1,
            '许可证信息维护',
            "javascript:setvalue('152','许可证信息维护')",
            '许可证信息维护',
            '_self',
            false);
        mydtree.add(39,
            37,
            '通知通告',
            "javascript:setvalue('39','通知通告')",
            '通知通告',
            '_self',
            false);
        mydtree.add(86,
            37,
            '消息管理',
            "javascript:setvalue('86','消息管理')",
            '消息管理',
            '_self',
            false);
        mydtree.add(88,
            37,
            '应急预案',
            "javascript:setvalue('88','应急预案')",
            '应急预案',
            '_self',
            false);
        mydtree.add(41,
            40,
            '法律法规管理',
            "javascript:setvalue('41','法律法规管理')",
            '法律法规管理',
            '_self',
            false);
        mydtree.add(42,
            40,
            '法律法规查看',
            "javascript:setvalue('42','法律法规查看')",
            '法律法规查看',
            '_self',
            false);
        mydtree.add(45,
            44,
            '检查项目管理',
            "javascript:setvalue('45','检查项目管理')",
            '检查项目管理',
            '_self',
            false);
        mydtree.add(49,
            44,
            '被监督单位管理',
            "javascript:setvalue('49','被监督单位管理')",
            '被监督单位管理',
            '_self',
            false);
        mydtree.add(62,
            44,
            '单位大类别管理',
            "javascript:setvalue('62','单位大类别管理')",
            '单位大类别管理',
            '_self',
            false);
        mydtree.add(63,
            44,
            '单位小类别管理',
            "javascript:setvalue('63','单位小类别管理')",
            '单位小类别管理',
            '_self',
            false);
        mydtree.add(64,
            44,
            '检查类别管理',
            "javascript:setvalue('64','检查类别管理')",
            '检查类别管理',
            '_self',
            false);
        mydtree.add(65,
            44,
            '检查小类管理',
            "javascript:setvalue('65','检查小类管理')",
            '检查小类管理',
            '_self',
            false);
        mydtree.add(48,
            47,
            '巡查监督',
            "javascript:setvalue('48','巡查监督')",
            '巡查监督',
            '_self',
            false);
        mydtree.add(82,
            47,
            '监督记录',
            "javascript:setvalue('82','监督记录')",
            '监督记录',
            '_self',
            false);
        mydtree.add(83,
            47,
            '单位信息查询',
            "javascript:setvalue('83','单位信息查询')",
            '单位信息查询',
            '_self',
            false);
        mydtree.add(51,
            50,
            '地图操作',
            "javascript:setvalue('51','地图操作')",
            '地图操作',
            '_self',
            false);
        mydtree.add(67,
            66,
            '执业医师查询',
            "javascript:setvalue('67','执业医师查询')",
            '执业医师查询',
            '_self',
            false);
        mydtree.add(74,
            73,
            '量化分级管理',
            "javascript:setvalue('74','量化分级管理')",
            '量化分级管理',
            '_self',
            false);
        mydtree.add(81,
            80,
            '专家库管理',
            "javascript:setvalue('81','专家库管理')",
            '专家库管理',
            '_self',
            false);
        mydtree.add(85,
            84,
            '科长分配任务',
            "javascript:setvalue('85','科长分配任务')",
            '科长分配任务',
            '_self',
            false);
        mydtree.add(100,
            84,
            '科员任务',
            "javascript:setvalue('100','科员任务')",
            '科员任务',
            '_self',
            false);
        mydtree.add(101,
            84,
            '所长查看科长任务',
            "javascript:setvalue('101','所长查看科长任务')",
            '所长查看科长任务',
            '_self',
            false);
        mydtree.add(90,
            89,
            '我的考勤记录',
            "javascript:setvalue('90','我的考勤记录')",
            '我的考勤记录',
            '_self',
            false);
        mydtree.add(91,
            89,
            '考勤记录管理',
            "javascript:setvalue('91','考勤记录管理')",
            '考勤记录管理',
            '_self',
            false);
        mydtree.add(93,
            92,
            '调度通知信息管理',
            "javascript:setvalue('93','调度通知信息管理')",
            '调度通知信息管理',
            '_self',
            false);
        mydtree.add(95,
            94,
            '统计报表录入',
            "javascript:setvalue('95','统计报表录入')",
            '统计报表录入',
            '_self',
            false);
        mydtree.add(102,
            94,
            '统计报表查看',
            "javascript:setvalue('102','统计报表查看')",
            '统计报表查看',
            '_self',
            false);
        mydtree.add(105,
            94,
            '报表查看',
            "javascript:setvalue('105','报表查看')",
            '报表查看',
            '_self',
            false);
        mydtree.add(106,
            94,
            '周工作量统计(工作量)',
            "javascript:setvalue('106','周工作量统计(工作量)')",
            '周工作量统计(工作量)',
            '_self',
            false);
        mydtree.add(109,
            94,
            '周工作量统计(违规累计)',
            "javascript:setvalue('109','周工作量统计(违规累计)')",
            '周工作量统计(违规累计)',
            '_self',
            false);
        mydtree.add(97,
            96,
            '卫生保障数据',
            "javascript:setvalue('97','卫生保障数据')",
            '卫生保障数据',
            '_self',
            false);
        mydtree.add(99,
            96,
            '卫生保障数据统计',
            "javascript:setvalue('99','卫生保障数据统计')",
            '卫生保障数据统计',
            '_self',
            false);
        mydtree.add(104,
            103,
            '预警发证后6个月',
            "javascript:setvalue('104','预警发证后6个月')",
            '预警发证后6个月',
            '_self',
            false);
        mydtree.add(107,
            103,
            '预警许可证已过期',
            "javascript:setvalue('107','预警许可证已过期')",
            '预警许可证已过期',
            '_self',
            false);
        mydtree.add(108,
            103,
            '预警许可证即将到期',
            "javascript:setvalue('108','预警许可证即将到期')",
            '预警许可证即将到期',
            '_self',
            false);
        mydtree.add(111,
            110,
            '周工作量统计',
            "javascript:setvalue('111','周工作量统计')",
            '周工作量统计',
            '_self',
            false);
        mydtree.add(112,
            110,
            '月工作量统计',
            "javascript:setvalue('112','月工作量统计')",
            '月工作量统计',
            '_self',
            false);
        mydtree.add(115,
            114,
            '错错错下订单',
            "javascript:setvalue('115','错错错下订单')",
            '错错错下订单',
            '_self',
            false);
        mydtree.add(151,
            150,
            '许可证信息维护',
            "javascript:setvalue('151','许可证信息维护')",
            '许可证信息维护',
            '_self',
            false);
        document.write(mydtree);
    </script>
</div>
<script type="text/javascript">
    //生成弹出层的代码
    <!-- 弹出层-->
    xOffset = 0;//向右偏移量
    yOffset = 25;//向下偏移量
    var toshow = "treediv";//要显示的层的id
    var target = "menu_parent_name";//目标控件----也就是想要点击后弹出树形菜单的那个控件id
    $("#"+target).click(function (){
        $("#"+toshow)
            .css("position", "absolute")
            .css("left", $("#"+target).position().left+xOffset + "px")
            .css("top", $("#"+target).position().top+yOffset +"px").show();
    });
    //关闭层
    $("#closed").click(function(){
        $("#"+toshow).hide();
    });
    //判断鼠标在不在弹出层范围内
    function   checkIn(id){
        var yy = 20;   //偏移量
        var str = "";
        var   x=window.event.clientX;
        var   y=window.event.clientY;
        var   obj=$("#"+id)[0];
        if(x>obj.offsetLeft&&x<(obj.offsetLeft+obj.clientWidth)&&y>(obj.offsetTop-yy)&&y<(obj.offsetTop+obj.clientHeight)){
            return true;
        }else{
            return false;
        }
    }
    //点击body关闭弹出层
    $(document).click(function(){
        var is = checkIn("treediv");
        if(!is){
            $("#"+toshow).hide();
        }
    });
    <!-- 弹出层-->
    //生成弹出层的代码
    //点击菜单树给文本框赋值------------------菜单树里加此方法
    function setvalue(id,name){
        alert(id);
        $("#menu_parent_name").val(name);
        $("#menu_parent").val(id);
        $("#treediv").hide();
    }
</script>
</BODY>
</HTML>
