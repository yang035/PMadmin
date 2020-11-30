<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
    a:hover{
        cursor:pointer
    }
</style>
<div>
    <div>
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
                    <input type="text" name="name" value="{:input('get.name')}" placeholder="关键字" autocomplete="off" class="layui-input">
                </div>
            </div>
                <div class="layui-inline">
                    <label class="layui-form-label">项目状态</label>
                    <div class="layui-input-inline">
                        <select name="s_status" class="field-s_status" type="select">
                            {$s_status}
                        </select>
                    </div>
                </div>
            <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
</div>
<table id="dataTable" class="layui-table" lay-filter="table1"></table>
{include file="block/layui" /}
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=subject_item&id={{ d.id }}">
</script>
{empty name="pr"}
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a href="{:url('flow')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-warm">提交进度</a>
    {{# if(d.step==0){ }}
    <a href="{:url('progressReview')}?id={{ d.id }}&step=1" class="layui-btn layui-btn-xs layui-btn-normal">一级审核</a>
    {{# } }}
    {{# if(d.step==1){ }}
    <a href="{:url('progressReview')}?id={{ d.id }}&step=2" class="layui-btn layui-btn-xs layui-btn-warm">二级审核</a>
    {{# } }}
    {{# if(d.step==2){ }}
    当月已审核
    {{# } }}
</script>
{else/}
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a href="{:url('flow')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-warm">查看</a>
</script>
{/empty}
<script type="text/javascript">
    layui.use(['jquery','table'], function() {
        var $ = layui.jquery,table = layui.table;
        table.render({
            elem: '#dataTable'
            ,height: 'full-200'
            ,url: '{:url()}' //数据接口
            ,page: true //开启分页
            ,limit: 30
            ,text: {
                none : '暂无相关数据'
            }
            ,cols: [[ //表头
                {type:'checkbox'},
                {field: 'xuhao', title: '序号',type: 'numbers'},
                {field: 'name', title: '名称',width:200, templet:function(d){
                        return "<a class='mcolor' onclick='read("+d.id+")'>"+d.name+"</a>";
                    }},
                {field: 'idcard', title: '项目编号',width:150},
                {field: 'cat_id', title: '类别',width:80, templet:function(d){
                        return d.cat.name;
                    }},
                {field: 'leader_user', title: '总负责人',width:150},
                {title: '操作', templet: '#buttonTpl',minWidth:600}
            ]]
        });
    });

    function a_user(id) {
        var open_url = "{:url('Contacts/index')}?subject_id="+id;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'添加人员',
            maxmin: true,
            area: ['900px', '600px'],
            content: open_url,
            success:function (layero, index) {
                var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
            }
        });
    }

    function b_user(id) {
        var open_url = "{:url('addB')}?id="+id;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'添加人员',
            maxmin: true,
            area: ['900px', '600px'],
            content: open_url,
            success:function (layero, index) {
                var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
            }
        });
    }

    function zujian_user(id) {
        var open_url = "{:url('addBaseUser')}?id="+id;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'添加人员',
            maxmin: true,
            area: ['900px', '600px'],
            content: open_url,
            success:function (layero, index) {
                var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
            }
        });
    }

    function partner_user(id) {
        var open_url = "{:url('configPartner')}?id="+id;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'添加人员',
            maxmin: true,
            area: ['900px', '600px'],
            content: open_url,
            success:function (layero, index) {
                var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
            }
        });
    }

    function discuss_record(id,subject_name) {
        var open_url = "{:url('SubjectRecord/index')}?subject_id="+id+"&subject_name="+subject_name;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'添加记录',
            maxmin: true,
            area: ['900px', '600px'],
            content: open_url,
            success:function (layero, index) {
                var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
            }
        });
    }

    function contract(id,subject_name) {
        var open_url = "{:url('SubjectContract/index')}?subject_id="+id+"&subject_name="+subject_name;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'添加合同',
            maxmin: true,
            area: ['900px', '600px'],
            content: open_url,
            success:function (layero, index) {
                var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
            }
        });
    }

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
            area: ['800px', '600px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }
</script>