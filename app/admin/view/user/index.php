<div class="page-toolbar">
    <div class="page-filter fr">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get" id="hisi-table-search">
        <div class="layui-form-item">
            <label class="layui-form-label">搜索</label>
            <div class="layui-input-inline">
                <input type="text" name="q" value="{:input('get.q')}" lay-verify="required" placeholder="用户名、邮箱、手机、昵称" autocomplete="off" class="layui-input">
            </div>
        </div>
        </form>
    </div>
    <div class="layui-btn-group fl">
        <a href="{:url('addUser')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
        <a data-href="{:url('status?table=admin_user&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>
        <a data-href="{:url('status?table=admin_user&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>
<!--        <a data-href="{:url('delUser')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>-->
    </div>
</div>
<table id="dataTable" class="layui-table" lay-filter="user_table"></table>
{include file="block/layui" /}
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.status }}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" {{ d.status == 1 ? 'checked' : '' }} data-href="{:url('status')}?table=admin_user&id={{ d.id }}">
</script>
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    <a href="{:url('editUser')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-normal">编辑</a>
    <a href="{:url('UserInfo/addItem')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-normal">入职备案</a>
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
                {type:'checkbox'}
                ,{field: 'username', title: '用户名'}
                ,{field: 'realname', title: '真实姓名'}
                ,{field: 'sex', title: '性别', templet:function(d){
                        if (d.sex == 2){
                            return '女';
                        }else {
                            return '男'
                        }
                    }}
                ,{field: 'role_id', title: '角色', templet:function(d){
                    if (d.role){
                        return d.role.name;
                    }else {
                        return '无'
                    }
                }}
                ,{field: 'department_id', title: '部门', templet:function(d){
                        if (d.role){
                            return d.dep.name;
                        }else {
                            return '无'
                        }
                    }}
                ,{field: 'job_item', title: '岗位'}
                ,{field: 'mobile', title: '手机号码',edit: 'text',}
                ,{field: 'last_login_time', width: 150, title: '最后登陆时间'}
                ,{field: 'status', title: '状态', templet: '#statusTpl'}
                ,{title: '操作', templet: '#buttonTpl'}
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
                    id:data.id,
                    k:field,
                    v:value,
                },function(res) {
                    if (res.code == 1) {
                        layer.msg(res.msg);
                        location.reload();
                    }else {
                        layer.msg(res.msg);
                        location.reload();
                    }
                });
            });
    });
</script>