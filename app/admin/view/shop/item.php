<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
    a:hover{
        cursor:pointer
    }
</style>
<div class="page-toolbar">
    <div class="page-filter">
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
                <div class="layui-inline">
                    <label class="layui-form-label">可见范围</label>
                    <div class="layui-input-inline">
                        <select name="visible_range" class="field-visible_range" type="select">
                            {$visible_range}
                        </select>
                    </div>
                </div>
            <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
    <div class="layui-btn-group fl">
        <a href="{:url('addItem')}?shop_type={$Request.param.p}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
        <a data-href="{:url('status?table=shop_item&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>
        <a data-href="{:url('status?table=shop_item&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>
        <a data-href="{:url('delItem')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>
    </div>
</div>
<table id="dataTable" class="layui-table" lay-filter="table1"></table>
{include file="block/layui" /}
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=shop_item&id={{ d.id }}">
</script>
<script type="text/html" id="statusTpl_1">
    <input type="checkbox" name="is_discount" value="{{ d.is_discount }}" lay-skin="switch" lay-filter="switchStatus" lay-text="有|无" {{ d.is_discount == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=shop_item&f=is_discount&id={{ d.id }}">
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a onclick="read({{ d.id }})" class="layui-btn layui-btn-xs layui-btn-normal">查看</a>
    <a href="{:url('editItem')}?id={{ d.id }}&shop_type={$Request.param.p}" class="layui-btn layui-btn-xs layui-btn-normal">修改</a>
    <a href="{:url('delItem')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-danger j-tr-del">删除</a>
    {eq name="$Think.session.admin_user.cid" value="2"}
    <a onclick="tuisong({{ d.id }})" class="layui-btn layui-btn-xs layui-btn-warm">推送</a>
    {/eq}
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl2">
    {{#  if(d.check_status == 1){ }}
    <span style="color: green">已审核({{ d.check_name }})</span>
    {{#  }else if(d.check_status == 2){ }}
    <a onclick="read2({{ d.id }})" class="layui-btn layui-btn-xs layui-btn-warm">审核</a>
    <span style="color: red">驳回({{ d.check_name }}_{{ d.yijian }})</span>
    {{#  }else{ }}
    <a onclick="read2({{ d.id }})" class="layui-btn layui-btn-xs layui-btn-warm">审核</a>
    {{#  } }}
</script>
<script type="text/javascript">
    layui.use(['table'], function() {
        var table = layui.table;
        table.render({
            elem: '#dataTable'
            ,url: '{:url()}' //数据接口
            ,where: {shop_type: '{$Request.param.p}'}
            ,page: true //开启分页
            ,limit: 20
            ,text: {
                none : '暂无相关数据'
            }
            ,cols: [[ //表头
                {type:'checkbox'},
                {field: 'name', title: '名称', templet:function(d){
                        return "<a class='mcolor' onclick='read("+d.id+")'>"+d.name+"</a>";
                    }},
                {field: 'cat_id', title: '类别', templet:function(d){
                        return d.cat.name;
                    }},
                {field: 'tuisong', title: '推送(类型_公司)'},
                {field: 'is_discount', title: '是否按平台优惠', templet: '#statusTpl_1'},
                {field: 'status', title: '状态', templet: '#statusTpl'},
                {title: '操作',width:250, templet: '#buttonTpl'},
                {title: '审核信息', templet: '#buttonTpl2'},
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
            area: ['1000px', '800px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }

    function tuisong(id){
        var open_url = "{:url('tuisong')}?id="+id;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'详情',
            maxmin: true,
            area: ['600px', '400px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }

    function read2(id){
        var open_url = "{:url('read')}?id="+id+"&p=1";
        window.location.href = open_url;
    }
</script>