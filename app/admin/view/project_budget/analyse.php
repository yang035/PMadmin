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
                // ,{field: 'project_name', title: '项目'}
                ,{field: 'name', title: '名称及规格'}
                ,{field: 'yusuan_danjia', title: '预算单价(元)'}
                ,{field: 'yusuan_shuliang', title: '预算数量',templet:function (d) {
                        return d.yusuan_shuliang+"("+d.unit+")";
                    }}
                // ,{field: 'unit',  title: '单位',width:60}
                ,{field: 'yusuan_zongjia',  title: '预算总价(元)'}
                ,{field: 'yusuan_fudong', title: '预算浮动比例%',width:130}
                ,{field: 'caigou_danjia', title: '采购单价(元)',edit: 'text',style:'background-color: #eef1f5;'}
                ,{field: 'caigou_shuliang', title: '采购数量',edit: 'text',style:'background-color: #eef1f5;',templet:function (d) {
                        return d.caigou_shuliang+"("+d.unit+")";
                    }}
                ,{field: 'caigou_zongjia', title: '采购总价(元)',edit: 'text',style:'background-color: #eef1f5;'}
                ,{field: 'jiesuan_danjia', title: '结算单价(元)',style:'background-color: #e6e6e6;'}
                ,{field: 'jiesuan_shuliang', title: '结算数量',style:'background-color: #e6e6e6;',templet:function (d) {
                        return d.jiesuan_shuliang+"("+d.unit+")";
                    }}
                ,{field: 'jiesuan_zongjia',  title: '结算总价(元)',style:'background-color: #e6e6e6;'}
                ,{field: 'jiesuan_fudong', title: '结算浮动比例%',width:130,style:'background-color: #e6e6e6;'}
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