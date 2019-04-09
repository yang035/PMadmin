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
                    <label class="layui-form-label">姓名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" value="{:input('get.name')}" placeholder="姓名关键字" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <input type="hidden" class="field-cat_id" name="cat_id">
                <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
</div>
<table id="dataTable" class="layui-table" lay-filter="table1"></table>
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=shopping_goods&id={{ d.id }}">
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    {{# if(d.status == 1){ }}
    <a href="{:url('hand')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-normal">分发</a>
    {{# } }}
<!--    <a href="{:url('del')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-danger j-tr-del">删除</a>-->
</script>
<script type="text/javascript">
    var unit_option = {:json_encode($unit_option)};

    layui.use(['table'], function() {
        var table = layui.table;
        table.render({
            elem: '#dataTable'
            ,url: "{:url('apply',[$atype])}" //数据接口
            ,page: true //开启分页
            ,limit: 20
            ,text: {
                none : '暂无相关数据'
            }
            ,cols: [[ //表头
                {type:'checkbox'}
                ,{field: 'realname', title: '姓名',width:100}
                ,{field: 'reason', title: '事由'}
                ,{field: 'goods', title: '物品',width:400, templet:function(d){
                    var g = d.goods,t="";
                    $.each(g,function (i) {
                        t += g[i]['name']+"<span class='red'>[ "+g[i]['number']+" ]</span>"+',';
                    });
                    return t;
                    }}
                ,{field: 'send_user',  title: '审批人'}
                ,{field: 'status_name',  title: '状态'}
                ,{field: 'create_time', title: '申请时间'}
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
            area: ['700px', '500px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }
</script>