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
                <label class="layui-form-label">材料名</label>
                <div class="layui-input-inline">
                    <input name="name" class="layui-input field-name" placeholder="输入关键词" class="layui-input">
                </div>
                <input type="hidden" class="field-project_id" name="project_id" value="{$Request.param.project_id}">
                <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
</div>
<table id="dataTable" class="layui-table" lay-filter="table1"></table>
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=shopping_record&id={{ d.id }}">
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a href="{:url('analyse')}?project_id={{ d.project_id }}&m_p_id={{ d.m_p_id }}&p=1" class="layui-btn layui-btn-xs layui-btn-normal">明细</a>
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
                ,{field: 'caigou_shuliang', title: '数量',templet:function (d) {
                        return d.caigou_shuliang+"("+d.unit+")";
                    }}
                ,{field: 'caigou_zongjia',  title: '总价(元)'}
                ,{title: '操作', templet: '#buttonTpl'}
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