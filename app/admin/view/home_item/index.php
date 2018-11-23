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
                    <label class="layui-form-label">文章分类</label>
                    <div class="layui-input-inline">
                        <select name="cat_id" class="field-cat_id" type="select">
                            <option value="0">全部</option>
                            {volist name='index_tab' id='vo'}
                            <option value="{$key}">{$vo.title}</option>
                            {/volist}
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">标题</label>
                    <div class="layui-input-inline">
                        <input type="text" name="title" value="{:input('get.title')}" placeholder="标题关键字" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
        <div class="layui-btn-group fl">
            <a href="{:url('add')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
            <a data-href="{:url('status?table=home_item&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>
            <a data-href="{:url('status?table=home_item&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>
            <a data-href="{:url('delItem')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>
        </div>
        <table id="dataTable" class="layui-table" lay-filter="table1"></table>
    </div>
</div>

{include file="block/layui" /}
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=home_item&id={{ d.id }}">
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a href="{:url('home/detail')}?id={{ d.id }}&yulan=1" class="layui-btn layui-btn-xs layui-btn-normal">预览</a>
    <a href="{:url('edit')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-normal">修改</a>
    <a href="{:url('delItem')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-danger j-tr-del">删除</a>
</script>
<script type="text/javascript">
    layui.use(['table'], function() {
        var table = layui.table;
        table.render({
            elem: '#dataTable'
            ,url: '{:url()}' //数据接口
            ,page: true //开启分页
            ,limit: 20
            ,text: {
                none : '暂无相关数据'
            }
            ,cols: [[ //表头
                {type:'checkbox'},
                {field: 'title', title: '文章标题'},
                {field: 'cat_id', title: '文章分类'},
                {field: 'author', title: '作者'},
                {field: 'summarize', title: '摘要'},
                {field: 'status', title: '状态', templet: '#statusTpl'},
                {field: 'create_time', title: '添加时间'},
                {title: '操作', templet: '#buttonTpl'}
            ]]
        });
    });
</script>