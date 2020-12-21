<div class="page-toolbar">
    <div class="page-filter fr">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get">
        <div class="layui-form-item">
            <label class="layui-form-label">搜索</label>
            <div class="layui-input-inline">
                <input type="text" name="q" value="{:input('get.q')}" lay-verify="required" placeholder="公司名称、法人手机号码" autocomplete="off" class="layui-input">
            </div>
        </div>
        </form>
    </div>
<!--    <div class="layui-btn-group fl">-->
<!--        <a href="{:url('add')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>-->
<!--        <a data-href="{:url('status?table=admin_company&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>-->
<!--        <a data-href="{:url('status?table=admin_company&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>-->
<!--        <a data-href="{:url('del?table=admin_company')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>-->
<!--    </div>-->
</div>
<form id="pageListForm">
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
                    <strong class="mcolor">{$vo['name']}</strong>
                </td>
                <td class="font12">{$vo['legal_person']}</td>
                <td class="font12">{$vo['cellphone']}</td>
                <td><input type="checkbox" name="status" {if condition="$vo['status'] eq 1"}checked=""{/if} value="{$vo['status']}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" data-href="{:url('status?table=admin_company&ids='.$vo['id'])}"></td>
                <td class="font12">{$vo['create_time']}</td>
                <td>
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
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate'], function() {
        var $ = layui.jquery, laydate = layui.laydate;
    });
    function com_auth(id) {
        var open_url = "{:url('comAuth')}?id=" + id;
        window.location.href = open_url;
    }
</script>