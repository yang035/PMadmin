<table id="dataTable" class="layui-table" lay-filter="table1"></table>
{include file="block/layui" /}
<script type="text/javascript">
    layui.use(['table'], function() {
        var _url = "{:url()}?user_id={$Request.param.user_id}";
        var table = layui.table;
        table.render({
            elem: '#dataTable'
            ,url: _url //数据接口
            ,page: true //开启分页
            ,limit: 20
            ,text: {
                none : '暂无相关数据'
            }
            ,cols: [[ //表头
                {field: 'xuhao', title: '序号',type: 'numbers'}
                ,{field: 'real_name', title: '姓名'}
                ,{field: 'com_name', title: '公司'}
                ,{field: 'login_ip', title: '登录IP'}
                ,{field: 'login_time', title: '登录时间'}
            ]]
        });
    });
</script>