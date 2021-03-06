<style>
    a{
        cursor:pointer
    }
</style>
<div class="page-toolbar">
    <div class="page-filter">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get" id="hisi-table-search">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">真实姓名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="realname" value="{:input('get.realname')}" placeholder="真实姓名" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">手机号码</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input field-cellphone" name="cellphone" value="{:input('get.cellphone')}" onkeyup="value=value.replace(/[^\d]/g,'')" maxlength="11"
                               autocomplete="off" placeholder="请输入手机号码">
                    </div>
                </div>
                <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
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
<!--    <a href="{:url('UserInfo/addItem')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-normal">入职备案</a>-->
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
                ,{field: 'xuhao', title: '序号',type: 'numbers'}
                ,{field: 'username', title: '用户名'}
                ,{field: 'realname', title: '真实姓名'}
                ,{field: 'nick', title: '英文名'}
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
                // ,{field: 'job_item', title: '岗位'}
                // ,{field: 'work_cat', title: '日常工作'}
                ,{field: 'mobile', title: '手机号码',edit: 'text'}
                ,{field: 'times', title: '登录次数', templet:function(d){
                        if (d.times > 0){
                            return "<a class='mcolor' onclick='read_log("+d.id+")'>"+d.times+"</a>";
                        }else {
                            return d.times;
                        }
                    }}
                ,{field: 'last_login_time', width: 150, title: '最后登陆时间'}
                ,{field: 'status', title: '状态', templet: '#statusTpl'}
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
                    t:'admin_user',
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

    function read_log(id){
        var open_url = "{:url('UserLogin/index')}?user_id="+id;
        window.location.href = open_url;
    }
</script>