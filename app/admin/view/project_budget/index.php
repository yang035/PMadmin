<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
</style>
{include file="block/layui" /}
<style type="text/css">
    a:hover{
        cursor: pointer;
    }
</style>
<div class="page-toolbar">
    <div class="page-filter">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get" id="hisi-table-search">
            <div class="layui-form-item">
                <label class="layui-form-label">项目名</label>
                <div class="layui-input-inline">
                    <select name="project_id" class="layui-input field-project_id" type="select" lay-filter="project" lay-search>
                        {$project_select}
                    </select>
                </div>
                <input type="hidden" class="field-cat_id" name="cat_id">
                <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
    <div class="layui-btn-group fl">
<!--        <a href="{:url('add')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>-->
<!--        <a data-href="{:url('status?table=shopping_record&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>-->
<!--        <a data-href="{:url('status?table=shopping_record&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>-->
<!--        <a data-href="{:url('del')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>-->
        <a href="javascript:import_excel();" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;导入</a>
    </div>
</div>
<table id="dataTable" class="layui-table" lay-filter="table1"></table>
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=shopping_record&id={{ d.id }}">
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a href="{:url('edit')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-normal">编辑</a>
<!--    <a href="{:url('del')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-danger j-tr-del">删除</a>-->
</script>
<script type="text/javascript">
    var _url = "{:url()}?project_id={$Request.param.project_id}";
    layui.use(['table'], function() {
        var table = layui.table;
        table.render({
            elem: '#dataTable'
            ,url: _url //数据接口
            ,page: true //开启分页
            ,limit: 30
            ,text: {
                none : '暂无相关数据'
            }
            ,cols: [[ //表头
                {type:'checkbox'}
                ,{field: 'project_name', title: '项目'}
                ,{field: 'name', title: '名称及规格'}
                ,{field: 'unit',  title: '单位'}
                ,{field: 'amount', title: '数量'}
                ,{field: 'market_price', title: '市场价(元)'}
                ,{field: 'total_price',  title: '合计(元)'}
                ,{field: 'user_id',  title: '操作员'}
                ,{field: 'create_time', title: '入库时间',width: 170}
                // ,{title: '操作', templet: '#buttonTpl'}
            ]]
        });
    });

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