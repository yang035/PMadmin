<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
    a:hover{
        cursor:pointer
    }
</style>
<div class="page-toolbar">
    <div class="page-filter">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get" id="hisi-table-search">
            <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">选择项目</label>
                <div class="layui-input-inline">
                    <select name="project_id" class="field-project_id" type="select" lay-filter="project_type" lay-search="">
                        {$mytask}
                    </select>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">任务名</label>
                <div class="layui-input-inline">
                    <input type="text" name="content" value="{:input('get.content')}" placeholder="任务名关键字" autocomplete="off" class="layui-input">
                </div>
            </div>
            <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
    <div class="layui-btn-group fl">
        <a href="{:url('addItem')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
        <a data-href="{:url('status?table=assignment_item&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>
        <a data-href="{:url('status?table=assignment_item&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>
        <a data-href="{:url('delItem')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>
    </div>
</div>
<table id="dataTable" class="layui-table" lay-filter="user_table"></table>
{include file="block/layui" /}
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|黑名单" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=assignment_item&id={{ d.id }}">
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a onclick="read({{ d.id }})" class="layui-btn layui-btn-xs layui-btn-normal">查看</a>
    <a href="{:url('editItem')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-normal">修改</a>
<!--    <a href="{:url('delItem')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-danger j-tr-del">删除</a>-->
</script>
<script type="text/javascript">
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
                {type:'checkbox'},
                // {field: 'cat_id', title: '类别',width:80, templet:function(d){
                //         return d.cat.name;
                //     }},
                {field: 'project_name', title: '项目名',width:200,},
                {field: 'content', title: '任务名',width:200, templet:function(d){
                        var open_url = "{:url('editItem')}?id="+d.id;
                        return "<a class='mcolor' href='"+open_url+"'>"+d.content+"</a>";
                }},
                {field: 'remark', title: '执行情况',width:150,edit: 'text'},
                {title: '操作', templet: '#buttonTpl',width:160},
                {field: 'ml', title: 'ML',width:80},
                // {field: 'gl', title: 'GL',width:80},
                {field: 'time_type', title: '日期类型',width:100},
                {field: 'start_time', title: '开始日期',width:110},
                {field: 'end_time', title: '结束日期',width:110},
                {field: 'send_user', title: '发送给',width:110},
                {field: 'deal_user', title: '执行人',width:110},
                {field: 'user_name', title: '添加人',width:80},
                {field: 'create_time', title: '添加时间',width:160},
            ]]
        });

        //监听单元格编辑
        table.on('edit(user_table)', function(obj){
            var value = obj.value //得到修改后的值
                ,data = obj.data //得到所在行所有键值
                ,field = obj.field; //得到字段
            // layer.msg('[ID: '+ data.id +'] ' + field + ' 字段更改为：'+ value);
            var open_url = "{:url('setKV')}";
            $.post(open_url, {
                t:'assignment_item',
                id:data.id,
                k:field,
                v:value,
            },function(res) {
                if (res.code == 1) {
                    layer.msg(res.msg);
                    // location.reload();
                }else {
                    layer.msg(res.msg);
                    // location.reload();
                }
            });
        });
    });

    function read(id){
        var open_url = "{:url('read')}?id="+id;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'详情',
            maxmin: true,
            area: ['800px', '600px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }
</script>