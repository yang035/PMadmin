<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
</style>
<div class="page-toolbar">
    <div class="page-filter">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">日期</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" id="test1" name="search_date" placeholder="选择日期" readonly value="{$d}">
                    </div>
                </div>
                <input type="hidden" name="tuijianren" value="{$Request.param.tuijianren}">
                <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
    </div>
</div>
<div class="layui-form">
    <table class="layui-table mt10" lay-even="" lay-skin="row">
        <colgroup>
            <col width="50">
        </colgroup>
        <thead>
        <tr>
            <th><input type="checkbox" lay-skin="primary" lay-filter="allChoose"></th>
            <th>序号</th>
            <th>用户名</th>
            <th>电话号码</th>
            <th>登录次数</th>
        </tr>
        </thead>
        <tbody>
        {volist name="data_list" id="vo"}
        <tr>
            <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['id']}" lay-skin="primary"></td>
            <td class="font12" style="width: 100px">{$vo['xuhao']}</td>
            <td class="font12">
                <strong class="mcolor">{$vo['username']}</strong>
            </td>
            <td class="font12">{$vo['mobile']}</td>
            <td class="font12">{$vo['num']}</td>
        </tr>
        {/volist}
        </tbody>
    </table>
    {$pages}
</div>
{include file="block/layui" /}
<script>
    layui.use(['jquery', 'laydate','form'], function() {
        var laydate = layui.laydate;
        //年选择器
        laydate.render({
            elem: '#test1',
            range: true
        });

    });
</script>