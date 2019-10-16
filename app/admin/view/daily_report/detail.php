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
<!--                <div class="layui-inline">-->
<!--                    <label class="layui-form-label">真实姓名</label>-->
<!--                    <div class="layui-input-inline">-->
<!--                        <input type="text" name="realname" value="{$Request.param.realname}" placeholder="真实姓名" autocomplete="off" class="layui-input">-->
<!--                    </div>-->
<!--                </div>-->
                <div class="layui-inline">
                    <label class="layui-form-label">日期</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" id="test2" name="search_date" placeholder="选择日期" value="{$d}">
                    </div>
                </div>
                <input type="hidden" name="type" value="{$Request.param.type}">
                <input type="hidden" name="uid" value="{$Request.param.uid}">
                <button type="submit" class="layui-btn layui-btn-normal">搜索</button>
            </div>
        </form>
        <div class="layui-form">
            <table class="layui-table mt10" lay-even="" lay-skin="row">
                <colgroup>
                    <col width="50">
                </colgroup>
                <thead>
                <tr>
                    <th><input type="checkbox" lay-skin="primary" lay-filter="allChoose"></th>
                    <th>姓名</th>
                    <th>收获(斗)</th>
                    <th>时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                {volist name="data_list" id="vo"}
                <tr>
                    <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['id']}" lay-skin="primary"></td>
                    <td class="font12">
                        <strong class="mcolor">{$vo['realname']}</strong>
                    </td>
                    <td class="font12">{$vo['real_total']}</td>
                    <td class="font12">{$vo['create_time']}</td>
                    <td><a href="{:url('tRead',['id'=>$vo['id'],'user_id'=>$vo['user_id'],'create_time'=>$vo['create_time']])}" class="layui-btn layui-btn-normal layui-btn-xs">明细</a></td>
                </tr>
                {/volist}
                </tbody>
            </table>
            {$pages}
        </div>
    </div>
</div>

{include file="block/layui" /}
<script>
    layui.use(['jquery', 'laydate','form'], function() {
        var laydate = layui.laydate;
        //年选择器
        laydate.render({
            elem: '#test2',
            range: true
        });

    });
</script>