<div>
    <form class="layui-form layui-form-pane" action="{:url()}" method="get" id="search_form">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">类型</label>
                    <div class="layui-input-inline">
                        <select name="sys_type" class="field-sys_type" type="select">
                            {$systype_option}
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">名称关键字</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" value="{:input('get.name')}" placeholder="名称关键字" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">手机号码</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input field-cellphone" name="cellphone" onkeyup="value=value.replace(/[^\d]/g,'')" maxlength="11"
                               autocomplete="off" placeholder="请输入手机号码">
                    </div>
                </div>
                <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
<!--    <div class="layui-btn-group fl">-->
<!--        <a href="{:url('add')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>-->
<!--        <a data-href="{:url('status?table=admin_company&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>-->
<!--        <a data-href="{:url('status?table=admin_company&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>-->
<!--        <a data-href="{:url('del?table=admin_company')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>-->
<!--    </div>-->
<div class="layui-form">
    <table class="layui-table mt10" lay-even="" lay-skin="row">
        <colgroup>
            <col width="50">
        </colgroup>
        <thead>
            <tr>
                <th><input type="checkbox" lay-skin="primary" lay-filter="allChoose"></th>
                <th>名称</th>
                <th>法人</th>
                <th>手机号码</th>
                <th>状态</th>
                <th>添加时间</th>
                <th>操作</th>
            </tr> 
        </thead>
        <tbody>
            {volist name="data_list" id="vo"}
            <tr>
                <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['id']}" lay-skin="primary"></td>
                <td class="font12">
                    <a href="#" onclick="read_company({$vo['id']})"><strong class="mcolor">{$vo['name']}</strong></a>
                </td>
                <td class="font12">{$vo['legal_person']}</td>
                <td class="font12">{$vo['cellphone']}</td>
                <td><input type="checkbox" name="status" {if condition="$vo['status'] eq 1"}checked=""{/if} value="{$vo['status']}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" data-href="{:url('status?table=admin_company&ids='.$vo['id'])}"></td>
                <td class="font12">{$vo['create_time']}</td>
                <td>
                    <a href="#" onclick="read_company({$vo['id']})" class="layui-btn layui-btn-normal layui-btn-xs">查看</a>
                    {if condition="($Think.session.admin_user.role_id <= 3)"}
                    <a href="{:url('edit?id='.$vo['id'])}" class="layui-btn layui-btn-normal layui-btn-xs">编辑</a>
                    {/if}
                    {if condition="$Think.session.admin_user.role_id == 1"}
                    <a class="layui-btn layui-btn-normal layui-btn-xs" onclick="com_auth({$vo['id']})">设置权限</a>
                    {/if}
                </td>
            </tr>
            {/volist}
        </tbody>
    </table>
    {$pages}
</div>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate', 'form'], function() {
        var $ = layui.jquery, laydate = layui.laydate, form = layui.form;
    });
    function com_auth(id) {
        var open_url = "{:url('comAuth')}?id=" + id;
        window.location.href = open_url;
    }

    function read_company(id) {
        var open_url = "{:url('Company/read')}?id="+id;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'导入',
            maxmin: true,
            area: ['800px', '500px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }
</script>