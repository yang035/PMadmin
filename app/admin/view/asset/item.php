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
                <label class="layui-form-label">资产类型</label>
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
            <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
    <div class="layui-btn-group fl">
        <a href="#" id="add_id" class="layui-btn layui-btn-primary layui-icon layui-icon-add-circle-fine">&nbsp;添加</a>
        <a data-href="{:url('status?table=asset_item&val=1')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-play" data-table="dataTable">&nbsp;启用</a>
        <a data-href="{:url('status?table=asset_item&val=0')}" class="layui-btn layui-btn-primary j-page-btns layui-icon layui-icon-pause" data-table="dataTable">&nbsp;禁用</a>
        <a data-href="{:url('delItem')}" class="layui-btn layui-btn-primary j-page-btns confirm layui-icon layui-icon-close red">&nbsp;删除</a>
    </div>
</div>
<div class="layui-form">
    <table id="dataTable" class="layui-table mt10" lay-even="" lay-skin="row">
        <colgroup>
            <col width="50">
        </colgroup>
        <thead>
        <tr>
            <th><input type="checkbox" lay-skin="primary" lay-filter="allChoose"></th>
            <th>名称</th>
            <th>数量</th>
            <th>存储人</th>
            <th>使用人</th>
            <th>操作员</th>
            <th>操作时间</th>
            <th>状态</th>
        </tr>
        </thead>
        <tbody>
        {volist name="data_list" id="vo"}
        <tr>
            <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['id']}" lay-skin="primary"></td>
            <td class="font12">
                <strong class="mcolor">{$vo['title']}</strong>
            </td>
            <td class="font12">{$vo['number']}</td>
            <td class="font12">{$vo['manager_user']}</td>
            <td class="font12">{$vo['deal_user']}</td>
            <td class="font12">{$vo['realname']}</td>
            <td class="font12">{$vo['update_time']}</td>
            <td class="font12">
                <input type="checkbox" name="status" value="{$vo['status']}" lay-skin="switch" lay-filter="switchStatus" lay-text="正常|关闭" {if condition="$vo['status'] eq 1"}checked {/if} data-href="{:url('status')}?table=asset_item&id={$vo['id']}">
            </td>
        </tr>
        {/volist}
        </tbody>
    </table>
    {$pages}
</div>
{include file="block/layui" /}
<script type="text/html" title="操作按钮模板" id="buttonTpl">
    {eq name="$Think.session.admin_user.role_id" value='3'}
<!--    <a href="{:url('editItem')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-normal">编辑</a>-->
<!--    <a href="{:url('delItem')}?id={{ d.id }}" class="layui-btn layui-btn-xs layui-btn-danger j-tr-del">删除</a>-->
    {/eq}
</script>
<script type="text/javascript">
    layui.use(['jquery','table'], function() {
        var $ = layui.jquery,table = layui.table;
        $('#add_id').on('click', function(){
            var good_cat = $('#good_cat').val();
            var open_url = "{:url('addItem')}";
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type:2,
                title :'物品列表',
                maxmin: true,
                area: ['900px', '600px'],
                content: open_url,
                success:function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
        });
    });
</script>