<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
</style>
<link href="__PUBLIC_JS__/dtree/dtreeck.css?v=">
{include file="block/layui" /}
<style type="text/css">
    .dTreeNode a:link {
        font-family: "宋体";
        font-size: 12px;
        color: #0000FF;
        text-decoration: none;
    }

    .dTreeNode a:visited {
        font-family: "宋体";
        font-size: 12px;
        color: #0000FF;
        text-decoration: none;
    }

    .dTreeNode a:hover {
        font-family: "宋体";
        font-size: 12px;
        color: #CC6600;
        text-decoration: none;
    }

    .dTreeNode a:active {
        font-family: "宋体";
        font-size: 12px;
        color: #006600;
        text-decoration: none;
    }
    a:hover{
        cursor: pointer;
    }
</style>
<script src="__PUBLIC_JS__/dtree/dtreeck.js?v="></script>
<div class="page-toolbar">
    <div class="page-filter">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get" id="hisi-table-search">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">资产类型</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input field-cat_name" id="menu_parent_name" lay-verify="required"
                               name="cat_name" autocomplete="off" value="{$data_info['category']['name']|default=''}">
                        <div id="treediv" style="overflow:scroll;position: relative;z-index:9999;display:none;">
                            <div align="right"><a href="##" id="closed"><font color="#000">关闭&nbsp;</font></a></div>
                            <script language="JavaScript" type="text/JavaScript">
                                mydtree = new dTree('mydtree', '/static/js/dtree/img/', 'no', 'no');
                                var ajax_url = "{:url('admin/tool/getTreeCat')}";
                                // var jsonstr={"cid":1};
                                $.ajax({
                                    url: ajax_url,
                                    async: false,
                                    type: "post",
                                    // data:jsonstr,
                                    dataType: "json",
                                    success: function (data) {
                                        // alert(JSON.stringify(data));
                                        //根目录
                                        mydtree.add(0, -1, "根目录", "", "根目录", "_self", false);
                                        for (var i = 0; i < data.length; i++) {
                                            mydtree.add(data[i].id, data[i].pid, data[i].name, "javascript:setvalue('" + data[i].id + "','" + data[i].pid + "','" + data[i].name + "','" + data[i].code + "')", data[i].name, "_self", false);
                                        }
                                        document.write(mydtree);
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" value="{:input('get.name')}" placeholder="名称关键字" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <input type="hidden" class="field-cat_id" name="cat_id">
                <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
    <div class="layui-btn-group fl">
        <a href="{:url('add')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
        <a data-href="{:url('status?table=shopping_goods&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>
        <a data-href="{:url('status?table=shopping_goods&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>
        <a data-href="{:url('del')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>
        <a href="javascript:import_excel();" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;导入</a>
    </div>
</div>
<table id="dataTable" class="layui-table" lay-filter="table1"></table>
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=shopping_goods&id={{ d.id }}">
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a href="{:url('edit')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-normal">编辑</a>
<!--    <a href="{:url('del')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-danger j-tr-del">删除</a>-->
</script>
<script type="text/javascript">
    var unit_option = {:json_encode($unit_option)};

    xOffset = 0;//向右偏移量
    yOffset = 25;//向下偏移量
    var toshow = "treediv";//要显示的层的id
    var target = "menu_parent_name";//目标控件----也就是想要点击后弹出树形菜单的那个控件id
    $("#" + target).click(function () {
        $("#" + toshow)
            .css("left", $("#" + target).position().left + xOffset + "px")
            .css("top", $("#" + target).position().top + yOffset + "px").show();
    });
    //关闭层
    $("#closed").click(function () {
        $("#" + toshow).hide();
    });

    //判断鼠标在不在弹出层范围内
    function checkIn(id) {
        var yy = 20;   //偏移量
        var str = "";
        var x = window.event.clientX;
        var y = window.event.clientY;
        var obj = $("#" + id)[0];
        if (x > obj.offsetLeft && x < (obj.offsetLeft + obj.clientWidth) && y > (obj.offsetTop - yy) && y < (obj.offsetTop + obj.clientHeight)) {
            return true;
        } else {
            return false;
        }
    }

    //点击body关闭弹出层
    // $(document).click(function(){
    //     var is = checkIn("treediv");
    //     if(!is){
    //         $("#"+toshow).hide();
    //     }
    // });
    <!-- 弹出层-->
    //生成弹出层的代码
    //点击菜单树给文本框赋值------------------菜单树里加此方法
    function setvalue(id, pid, name, code) {
        $("#menu_parent_name").val(name);
        $(".field-cat_id").val(id);
        $(".field-pid").val(pid);
        $(".field-code").val(code);
        $("#treediv").hide();
    }

    layui.use(['table'], function() {
        var table = layui.table;
        table.render({
            elem: '#dataTable'
            ,url: '{:url()}' //数据接口
            ,page: true //开启分页
            ,limit: 30
            ,text: {
                none : '暂无相关数据'
            }
            ,cols: [[ //表头
                {type:'checkbox'}
                ,{field: 'title', title: '名称',width: 300, templet:function(d){
                        return "<a class='mcolor' onclick='open_div("+d.id+")'>"+d.title+"</a>";
                    }}
                ,{field: 'cat_id', title: '类型', templet:function(d){
                        if (d.category){
                            return d.category.name;
                        }else {
                            return '无'
                        }
                    }}
                ,{field: 'thumb', title: '缩略图', templet:function(d){
                        return "<img src='"+ d.thumb +"'>";
                    }}
                ,{field: 'unit', title: '单位', templet:function(d){
                        return unit_option[d.unit];
                    }}
                ,{field: 'description', title: '概述'}
                ,{field: 'marketprice', title: '价格(元)'}
                ,{field: 'total', title: '库存数'}
                ,{field: 'sales',  title: '分发数'}
                ,{field: 'viewcount', title: '浏览次数'}
                ,{field: 'status', title: '状态', templet: '#statusTpl'}
                ,{title: '操作', templet: '#buttonTpl'}
            ]]
        });
    });
    function open_div(id){
        var open_url = "{:url('Goods/read')}?id="+id;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'查看',
            maxmin: true,
            area: ['800px', '600px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }

    function import_excel() {
        var open_url = "{:url('doimport')}";
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'导入',
            maxmin: true,
            area: ['800px', '500px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }
</script>