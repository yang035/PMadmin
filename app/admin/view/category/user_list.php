{include file="block/layui" /}
<div class="layui-tab-item layui-form menu-dl {if condition="$k eq 1"}layui-show{/if}">
<!-- 内容主体区域 -->
<div class="layui-btn-group">
    <button class="layui-btn" id="btn-expand">全部展开</button>
    <button class="layui-btn" id="btn-fold">全部折叠</button>
    <button class="layui-btn" id="btn-refresh">刷新表格</button>
</div>
<table id="table1" class="layui-table" lay-filter="user_table" width="100px"></table>
<!-- 操作列 -->
<script type="text/html" id="oper-col">
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="add">添加</a>
</script>
<style>
    .treeTable-icon .layui-icon-file:before {
        content: "\e66f";
    }
</style>
<script>
    var _url = "{:url('admin/department/userList')}";

    layui.config({
        base: '/../../static/js/'
    }).extend({
        treetable: 'treetable-lay/treetable'
    }).use(['layer', 'table', 'treetable'], function () {
        var $ = layui.jquery;
        var table = layui.table;
        var layer = layui.layer;
        var treetable = layui.treetable;

        // 渲染表格
        var renderTable = function () {
            layer.load(2);
            treetable.render({
                treeColIndex: 1,
                treeSpid: -1,
                treeIdName: 'id',
                treePidName: 'pid',
                treeDefaultClose: true,
                treeLinkage: false,
                elem: '#table1',
                url: _url,
                page: false,
                cols: [{$data}],
                done: function () {
                    layer.closeAll('loading');
                }
            });
        };

        renderTable();

        $('#btn-expand').click(function () {
            treetable.expandAll('#table1');
        });

        $('#btn-fold').click(function () {
            treetable.foldAll('#table1');
        });

        $('#btn-refresh').click(function () {
            renderTable();
        });

        //监听工具条
        table.on('checkbox(user_table)', function (obj) {
            var data = obj.data;
            var layEvent = obj.event;
            var id=data.id,pid=data.pid,code=data.code,pname=data.name;
// alert(pname);
            // if (layEvent === 'add') {
            //     var open_url = "{:url('add')}?id="+id+"&pid="+pid+"&code="+code+"&pname="+name;
            //     if (open_url.indexOf('?') >= 0) {
            //         open_url += '&hisi_iframe=yes';
            //     } else {
            //         open_url += '?hisi_iframe=yes';
            //     }
            //     layer.open({
            //         type:2,
            //         title :'添加',
            //         maxmin: true,
            //         area: ['800px', '500px'],
            //         content: open_url,
            //         success:function (layero, index) {
            //             var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
            //             body.contents().find(".field-id").val(id);
            //             body.contents().find(".field-pid").val(pid);
            //             body.contents().find(".field-code").val(code);
            //             body.contents().find(".field-pname").val(pname);
            //         }
            //     });
            // }
        });
    });
</script>
</div>
