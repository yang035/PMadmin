{include file="block/layui" /}
<div class="layui-tab-item layui-form menu-dl {if condition="$k eq 1"}layui-show{/if}">
<div class="page-toolbar">
    <div class="page-filter layui-form-pane">
        <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">辅助搜索</label>
            <div class="layui-input-inline">
                <input id="edt-search" type="text" class="layui-input field-search-name" placeholder="名称关键字" style="width: 120px;"/>
            </div>
        </div>
        </div>
    </div>
</div>
<!-- 内容主体区域 -->
<div class="layui-btn-group">
    <button class="layui-btn" id="btn-expand">全部展开</button>
    <button class="layui-btn" id="btn-fold">全部折叠</button>
    <button class="layui-btn" id="btn-refresh">刷新表格</button>
</div>
<table id="table1" class="layui-table" lay-filter="table1"></table>
<!-- 操作列 -->
<script type="text/html" id="oper-col">
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="add">添加</a>
    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">修改</a>
<!--    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>-->
</script>
<script>
    var _url = "{:url('admin/Category/index')}";

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
                treeSpid: 0,
                treeIdName: 'id',
                treePidName: 'pid',
                treeDefaultClose: true,
                treeLinkage: false,
                elem: '#table1',
                url: _url,
                page: false,
                cols: [[
                    {type: 'numbers'},
                    {field: 'name', title: '名称'},
                    // {field: 'code', title: '编码'},
                    {field: 'remark', title: '说明'},
                    {templet: '#oper-col', title: '操作'}
                ]],
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

        $('#edt-search').keyup(function () {
            var keyword = $('#edt-search').val();
            var searchCount = 0;
            $('#table1').next('.treeTable').find('.layui-table-body tbody tr td').each(function () {
                $(this).css('background-color', 'transparent');
                var text = $(this).text();
                if (keyword != '' && text.indexOf(keyword) >= 0) {
                    $(this).css('background-color', 'rgba(250,230,160,0.5)');
                    if (searchCount == 0) {
                        treetable.expandAll('#table1');
                        $('html,body').stop(true);
                        $('html,body').animate({scrollTop: $(this).offset().top - 150}, 500);
                    }
                    searchCount++;
                }
            });
            if (keyword == '') {
                layer.msg("请输入搜索内容", {icon: 5});
            } else if (searchCount == 0) {
                layer.msg("没有匹配结果", {icon: 5});
            }
        });

        //监听工具条
        table.on('tool(table1)', function (obj) {
            var data = obj.data;
            var layEvent = obj.event;
            var id=data.id,pid=data.pid,code=data.code,pname=data.name;

            if (layEvent === 'del') {
                var open_url = "{:url('del')}?ids="+id;
                layer.confirm('删除之后无法恢复，您确定要删除吗？', {title:false, closeBtn:0}, function(index){
                    $.get(open_url, function(res) {
                        if (res.code == 0) {
                            layer.msg(res.msg);
                        } else {
                            that.parents('tr').remove();
                        }
                    });
                    layer.close(index);
                });
            }else if (layEvent === 'add') {
                var open_url = "{:url('add')}?id="+id+"&pid="+pid+"&code="+code+"&pname="+name;
                if (open_url.indexOf('?') >= 0) {
                    open_url += '&hisi_iframe=yes';
                } else {
                    open_url += '?hisi_iframe=yes';
                }
                layer.open({
                    type:2,
                    title :'添加',
                    maxmin: true,
                    area: ['800px', '500px'],
                    content: open_url,
                    success:function (layero, index) {
                        var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                        body.contents().find(".field-id").val(id);
                        body.contents().find(".field-pid").val(pid);
                        body.contents().find(".field-code").val(code);
                        body.contents().find(".field-pname").val(pname);
                    }
                });
            } else if (layEvent === 'edit') {
                var open_url = "{:url('edit')}?id="+id+"&pid="+pid+"&code="+code+"&pname="+name;
                if (open_url.indexOf('?') >= 0) {
                    open_url += '&hisi_iframe=yes';
                } else {
                    open_url += '?hisi_iframe=yes';
                }
                layer.open({
                    type:2,
                    maxmin: true,
                    title :'编辑',
                    area: ['800px', '500px'],
                    content: open_url,
                    success:function (layero, index) {
                        var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                        body.contents().find(".field-id").val(id);
                        body.contents().find(".field-pid").val(pid);
                        body.contents().find(".field-code").val(code);
                    }
                });
            }
        });
    });
</script>
</div>
