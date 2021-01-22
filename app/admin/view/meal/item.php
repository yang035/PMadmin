<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
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
                        <input type="text" name="name" value="{:input('get.name')}" placeholder="名称关键字" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
    <div class="layui-btn-group fl">
        <a href="{:url('addItem')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
        <a data-href="{:url('status?table=meal_item&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>
        <a data-href="{:url('status?table=meal_item&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>
<!--        <a data-href="{:url('delItem')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>-->
    </div>
</div>
<table id="dataTable" class="layui-table" lay-filter="table1"></table>
{include file="block/layui" /}
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=meal_item&id={{ d.id }}">
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
            ,where: {qu_type: '{$Request.param.qu_type}', }
            ,page: true //开启分页
            ,limit: 20
            ,text: {
                none : '暂无相关数据'
            }
            ,cols: [[ //表头
                {type:'checkbox'},
                {field: 'name', title: '项目名称'},
                {field: 'cat_id', title: '类别', templet:function(d){
                        return d.cat.name;
                    }},
                {field: 'taocan_1', title: "{$taocan_config['taocan_1']}", templet:function(d){
                    if (d.meal_type == 1){
                        return d.taocan_1 == 1 ? '&#10003' : '&#10005';
                    }else {
                        return d.taocan_1;
                    }
                }},
                {field: 'taocan_2', title:"{$taocan_config['taocan_2']}", templet:function(d){
                        if (d.meal_type == 1){
                            return d.taocan_2 == 1 ? '&#10003' : '&#10005';
                        }else {
                            return d.taocan_2;
                        }
                    }},
                {field: 'taocan_3', title: "{$taocan_config['taocan_3']}", templet:function(d){
                        if (d.meal_type == 1){
                            return d.taocan_3 == 1 ? '&#10003' : '&#10005';
                        }else {
                            return d.taocan_3;
                        }
                    }},
                {field: 'taocan_4', title: "{$taocan_config['taocan_4']}", templet:function(d){
                        if (d.meal_type == 1){
                            return d.taocan_4 == 1 ? '&#10003' : '&#10005';
                        }else {
                            return d.taocan_4;
                        }
                    }},
                {field: 'taocan_5', title:"{$taocan_config['taocan_5']}", templet:function(d){
                        if (d.meal_type == 1){
                            return d.taocan_5 == 1 ? '&#10003' : '&#10005';
                        }else {
                            return d.taocan_5;
                        }
                    }},
                {field: 'status', title: '状态', templet: '#statusTpl'},
                {title: '操作', templet: '#buttonTpl'}
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
            area: ['800px', '600px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }
</script>