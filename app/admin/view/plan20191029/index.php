<div class="page-toolbar">
    <div class="page-filter fr">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get">
        <div class="layui-form-item">
            <label class="layui-form-label">搜索</label>
            <div class="layui-input-inline">
                <input type="text" name="q" value="{:input('get.q')}" lay-verify="required" placeholder="用户名、邮箱、手机、昵称" autocomplete="off" class="layui-input">
            </div>
        </div>
        </form>
    </div>
    <div class="layui-btn-group fl">
        <a href="{:url('add')}" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
        <a data-href="{:url('status?table=admin_company&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>
        <a data-href="{:url('status?table=admin_company&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>
        <a data-href="{:url('del?table=admin_company')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>
    </div>
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
                <th>计划产量(斗)</th>
                <th>实际产量(斗)</th>
                <th>开始时间</th>
                <th>结束时间</th>
                <th>审批人</th>
                <th>添加时间</th>
                <th>操作</th>
            </tr> 
        </thead>
        <tbody>
            {volist name="data_list" id="vo"}
            <tr>
                <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['id']}" lay-skin="primary"></td>
                <td class="font12" title="{$vo['remark']}">
                    <strong class="mcolor">{$vo['name']}</strong>
                </td>
                <td class="font12">{$vo['score']}</td>
                <td class="font12">{$vo['real_score']}</td>
                <td class="font12">{$vo['start_time']}</td>
                <td class="font12">{$vo['end_time']}</td>
                <td class="font12">{$vo['send_user']}</td>
                <td class="font12">{$vo['create_time']}</td>
                <td>
                    <div class="layui-btn-group">
                        <div class="layui-btn-group">
                        <a href="{:url('edit?id='.$vo['id'])}" class="layui-btn layui-btn-primary layui-btn-sm"><i class="layui-icon">&#xe642;</i></a>
                        <a data-href="{:url('del?table=admin_company&ids='.$vo['id'])}" class="layui-btn layui-btn-primary layui-btn-sm j-tr-del"><i class="layui-icon">&#xe640;</i></a>
                        </div>
                    </div>
                </td>
            </tr>
            {/volist}
        </tbody>
    </table>
    {$pages}
</div>
</form>
{include file="block/layui" /}