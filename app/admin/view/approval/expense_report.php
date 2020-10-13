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
</div>
<table id="dataTable" class="layui-table" lay-filter="table1"></table>
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=shopping_record&id={{ d.id }}">
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a href="{:url('reportDetail')}?project_id={{ d.project_id }}" class="layui-btn layui-btn-xs layui-btn-normal">明细</a>
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
                ,{field: 'total', title: '总支出(元)'}
                ,{title: '操作', templet: '#buttonTpl'}
            ]]
        });
        //监听单元格编辑
        table.on('edit(table1)', function(obj){
            var value = obj.value //得到修改后的值
                ,data = obj.data //得到所在行所有键值
                ,field = obj.field; //得到字段
            // layer.msg('[ID: '+ data.id +'] ' + field + ' 字段更改为：'+ value);
            var open_url = "{:url('setKV')}";
            $.post(open_url, {
                t:'project_budget',
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

    function import_excel1() {
        var open_url = "{:url('jiesuanimport')}";
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