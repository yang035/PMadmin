<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
</style>
<div class="page-toolbar">
    <div class="page-filter">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get" id="hisi-table-search">
            <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">洽商记录</label>
                <div class="layui-input-inline">
                    <input type="text" name="name" value="{:input('get.name')}" placeholder="关键字" autocomplete="off" class="layui-input">
                    <input type="hidden" name="subject_id" value="{$Request.param.subject_id}">
                </div>
            </div>
            <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
        <div class="layui-btn-group fl">
            <a href="#" onclick="add_user('addItem',{$Request.param.subject_id})" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
            <a data-href="{:url('status?table=contacts_item&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>
            <a data-href="{:url('status?table=contacts_item&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>
<!--            <a data-href="{:url('delItem')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>-->
        </div>
        <table id="dataTable" class="layui-table" lay-filter="table1"></table>
    </div>
</div>

{include file="block/layui" /}
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=contacts_item&id={{ d.id }}">
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a href="#" onclick="add_user('editItem',{{ d.subject_id }},{{ d.id }})" class="layui-btn layui-btn-xs layui-btn-normal">修改</a>
<!--    <a href="{:url('delItem')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-danger j-tr-del">删除</a>-->
</script>
<script type="text/javascript">
    layui.use(['jquery','table'], function() {
        var $ = layui.jquery,table = layui.table;
        table.render({
            elem: '#dataTable'
            ,url: '{:url()}' //数据接口
            ,where: {subject_id: '{$Request.param.subject_id}', }
            ,page: true //开启分页
            ,limit: 20
            ,text: {
                none : '暂无相关数据'
            }
            ,cols: [[ //表头
                {type:'checkbox'},
                {field: 'subject_id', title: '项目名称', templet:function(d){
                        return d.cat.name;
                    }},
                {field: 'content', title: '洽商记录'},
                {field: 'report', title: '洽商报告'},
                {field: 'update_time', title: '时间'},
                // {field: 'status', title: '状态', templet: '#statusTpl'},
                {title: '操作', templet: '#buttonTpl'}
            ]]
        });
    });

    function add_user(url,subject_id,id='') {
        var open_url = "{:url('"+url+"')}?subject_id="+subject_id+"&id="+id+"&subject_name={$Request.param.subject_name}";
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'添加/编辑记录',
            maxmin: true,
            area: ['800px', '500px'],
            content: open_url,
            success:function (layero, index) {
                var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
            }
        });
    }
</script>