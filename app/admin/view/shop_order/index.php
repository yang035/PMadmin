<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
</style>
<div>
    <div>
        <form class="layui-form layui-form-pane" action="{:url()}" method="get" id="search_form">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">商品名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" value="{:input('get.name')}" placeholder="商品名称" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">日期范围</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" id="test2" name="search_date" placeholder="选择日期" readonly value="{$d|default=''}">
                    </div>
                </div>
                <input type="hidden" name="type" value="{$Request.param.type}">
                <input type="hidden" name="export" value="">
                <button type="submit" class="layui-btn layui-btn-normal normal_btn">搜索</button>
<!--                <input type="button" class="layui-btn layui-btn-primary layui-icon export_btn" value="导出">-->
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
            <th>商品名称</th>
            <th>总数量(份)</th>
            <th>总消耗(斗)</th>
            <th>总额外支付(元)</th>
            <th>添加时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {volist name="data_list" id="vo"}
        <tr>
            <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['id']}" lay-skin="primary"></td>
            <td class="font12">
                <a href="{:url('detail?item_id='.$vo['item_id'])}"><strong class="mcolor">{$vo['name']}</strong></a>
            </td>
            <td class="font12">{$vo['num']}</td>
            <td class="font12">{$vo['total_score']}</td>
            <td class="font12">{$vo['other_price']}</td>
            <td class="font12">{$vo['create_time']}</td>
            <td>
                <div class="layui-btn-group">
                    <div class="layui-btn-group">
                        <a href="{:url('detail?item_id='.$vo['item_id'])}" class="layui-btn layui-btn-primary layui-btn-sm">明细</a>
                        <!--                                <a href="{:url('edit?id='.$vo['id'])}" class="layui-btn layui-btn-primary layui-btn-sm"><i class="layui-icon">&#xe642;</i></a>-->
                        <!--                                <a data-href="{:url('del?table=admin_company&ids='.$vo['id'])}" class="layui-btn layui-btn-primary layui-btn-sm j-tr-del"><i class="layui-icon">&#xe640;</i></a>-->
                    </div>
                </div>
            </td>
        </tr>
        {/volist}
        </tbody>
    </table>
    {$pages}
</div>
{include file="block/layui" /}
<script>
    layui.use(['jquery', 'laydate','form'], function() {
        var $ = layui.jquery,laydate = layui.laydate;
        //年选择器
        laydate.render({
            elem: '#test2',
            range: true
        });

        $('.export_btn').click(function () {
            if ($(this).val() == '导出'){
                $('input[name=export]').val(1);
                $('#search_form').submit();
            }
        });

        $('.normal_btn').click(function () {
            if ($(this).val() != '导出'){
                $('input[name=export]').val('');
                $('#search_form').submit();
            }
        });

    });

</script>