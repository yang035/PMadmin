<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
    a:hover{
        cursor:pointer
    }
</style>
<div>
    <div>
        <form class="layui-form layui-form-pane" action="{:url()}" method="get" id="hisi-table-search">
            <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">类型</label>
                <div class="layui-input-inline">
                    <select name="cat_id" class="field-cat_id" type="select">
                        {$cat_option}
                    </select>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="name" value="{:input('get.name')}" placeholder="关键字" autocomplete="off" class="layui-input">
                </div>
            </div>
            <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
    <div class="layui-btn-group fl">
        <a href="{:url('add')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
        <a data-href="{:url('status?table=subject_item&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>
        <a data-href="{:url('status?table=subject_item&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>
        <!--            <a data-href="{:url('delItem')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>-->
    </div>
</div>
<table id="dataTable" class="layui-table" lay-filter="table1"></table>
{include file="block/layui" /}
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=subject_item&id={{ d.id }}">
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a href="{:url('edit')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-normal">修改</a>
</script>
<script type="text/javascript">
    layui.use(['jquery','table'], function() {
        var $ = layui.jquery,table = layui.table;
        table.render({
            elem: '#dataTable'
            ,height: 'full-200'
            ,url: '{:url()}' //数据接口
            ,page: true //开启分页
            ,limit: 30
            ,text: {
                none : '暂无相关数据'
            }
            ,cols: [[ //表头
                // {type:'checkbox',fixed: 'left'},
                {field: 'xuhao', title: '序号',type: 'numbers'},
                {field: 'name', title: '名称',width:200, templet:function(d){
                        return "<a class='mcolor' onclick='read("+d.id+")'>"+d.name+"</a>";
                    }},
                {field: 'idcard', title: '项目编号',width:150},
                {field: 'cat_id', title: '类别',width:80, templet:function(d){
                        return d.cat.name;
                    }},
                // {field: 's_status', title: '项目状态', templet:function(d){
                //         return d.s_status;
                //     }},
                // {field: 'leader_user', title: '总负责人',width:150},
                {field: 'status', title: '状态',width:100, templet: '#statusTpl'},
                {title: '操作', templet: '#buttonTpl',minWidth:600}
            ]]
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
            area: ['700px', '500px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }
</script>