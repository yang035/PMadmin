<div class="page-toolbar">
    <div class="layui-btn-group fl">
        <a href="{:url('addLevel')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
        <a data-href="{:url('status?table=admin_member_level&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>
        <a data-href="{:url('status?table=admin_member_level&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>
        <a data-href="{:url('delLevel')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>
    </div>
</div>
<table id="dataTable"></table>
{include file="block/layui" /}
<script type="text/html" title="状态模板" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=admin_member_level&id={{ d.id }}">
</script>

<script type="text/html" title="默认设置模板" id="defaultTpl">
    <input type="checkbox" name="default" value="{{ d.default }}" lay-skin="switch" lay-filter="switchStatus" lay-text="是|否" {{ d.default == 1 ? 'checked' : '' }} data-href="{:url('setDefault')}?id={{ d.id }}">
</script>

<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a href="{:url('editLevel')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-normal">修改</a>
    <a href="{:url('delLevel')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-danger j-tr-del">删除</a>
</script>
<script type="text/javascript">
    layui.use(['table'], function() {
        var table = layui.table;
        table.render({
            elem: '#dataTable'
            ,url: '{:url()}' //数据接口
            ,text: {
                none : '暂无相关数据'
            }
            ,cols: [[ //表头
                {type:'checkbox'}
                ,{field: 'name', title: '等级名称'}
                ,{field: 'intro', title: '等级简介'}
                ,{field: 'discount', title: '折扣%'}
                ,{field: 'min_exper', title: '最小经验值'}
                ,{field: 'max_exper', title: '最大经验值'}
                ,{field: 'status', title: '状态', width: 90, templet: '#statusTpl'}
                ,{field: 'default', title: '默认', width: 80, templet: '#defaultTpl'}
                ,{title: '操作', templet: '#buttonTpl'}
            ]]
        });
    });
</script>