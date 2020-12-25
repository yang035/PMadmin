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
                <label class="layui-form-label">姓名</label>
                <div class="layui-input-inline">
                    <input type="text" name="name" value="{:input('get.name')}" placeholder="姓名关键字" autocomplete="off" class="layui-input">
                </div>
            </div>
                <div class="layui-inline">
                    <label class="layui-form-label">公司</label>
                    <div class="layui-input-inline">
                        <input type="text" name="last_company" value="{:input('get.last_company')}" placeholder="公司名关键字" autocomplete="off" class="layui-input">
                    </div>
                </div>
            <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
    <div class="layui-btn-group fl">
        <a href="{:url('addItem')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
        <a data-href="{:url('status?table=resume_item&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>
        <a data-href="{:url('status?table=resume_item&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>
<!--        <a data-href="{:url('delItem')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>-->
    </div>
</div>
<table id="dataTable" class="layui-table" lay-filter="user_table"></table>
{include file="block/layui" /}
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|黑名单" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=resume_item&id={{ d.id }}">
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a onclick="read({{ d.id }})" class="layui-btn layui-btn-xs layui-btn-normal">查看</a>
    <a href="{:url('editItem')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-normal">修改</a>
<!--    <a href="{:url('delItem')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-danger j-tr-del">删除</a>-->
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
                // {field: 'cat_id', title: '类别',width:80, templet:function(d){
                //         return d.cat.name;
                //     }},
                {field: 'job', title: '面试岗位',width:100},
                {field: 'name', title: '姓名',width:80,templet:function(d){
                        return "<a class='mcolor' onclick='read("+d.id+")'>"+d.name+"</a>";
                    }},
                {field: 'mobile', title: '手机号码',width:120},
                {field: 'qq', title: 'QQ',width:80},
                {field: 'wechat', title: '微信',width:80},
                {field: 'attachment', title: '简历附件',width:90, templet:function(d){
                        return "<a target='_blank' class='mcolor' href='"+d.attachment+"' >附件</a>";
                    }},
                {field: 'last_company', title: '所在公司',width:120},
                {field: 'legalman', title: '法人/负责人',width:120},
                {field: 'legalman_contact', title: '联系方式',width:120},
                {field: 'com_address', title: '公司地址',width:120},
                {field: 'source', title: '来源',width:80},
                {field: 'resume_time', title: '面试时间',width:150},
                {field: 'is_resume', title: '是否面试',width:100},
                {field: 'is_pass', title: '是否通过',width:100},
                {field: 'is_duty', title: '是否到岗',width:100},
                {field: 'remark', title: '面试备注',width:150},
                {field: 'status', title: '状态',width:120, templet: '#statusTpl'},
                {field: 'user_name', title: '操作员',width:80},
                {field: 'create_time', title: '添加时间',width:160},
                {title: '操作', templet: '#buttonTpl',width:160}
            ]]
        });

        //监听单元格编辑
        table.on('edit(user_table)', function(obj){
            var value = obj.value //得到修改后的值
                ,data = obj.data //得到所在行所有键值
                ,field = obj.field; //得到字段
            // layer.msg('[ID: '+ data.id +'] ' + field + ' 字段更改为：'+ value);
            var open_url = "{:url('setKV')}";
            $.post(open_url, {
                t:'resume_item',
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
            area: ['600px', '500px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }
</script>