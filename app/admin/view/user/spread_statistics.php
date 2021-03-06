<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
</style>
<div>
    <form class="layui-form layui-form-pane" action="{:url()}" method="get" id="search_form">
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">日期范围</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" id="test2" name="search_date" placeholder="选择日期" readonly value="{$d}">
                </div>
            </div>
            <input type="hidden" name="export" value="">
            <button type="submit" class="layui-btn layui-btn-normal normal_btn">搜索</button>
<!--            <input type="button" class="layui-btn layui-btn-primary layui-icon export_btn" value="导出">-->
        </div>
    </form>
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
            <th>姓名</th>
            <th>数量</th>
        </tr>
        </thead>
        <tbody>
        {volist name="data_list" id="vo"}
        <tr>
            <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['tuijianren']}" lay-skin="primary"></td>
            <td class="font12" style="width: 100px">{$vo['xuhao']}</td>
            <td class="font12">
                {neq name="vo['name']" value="无"}
                <a href="{:url('spread',['tuijianren'=>$vo['tuijianren'],'search_date'=>$d])}"><strong class="mcolor">{$vo['name']}</strong></a>
                {else/}
                {$vo['tuijianren']}
                {/neq}
            </td>
            <td class="font12">
                {neq name="vo['name']" value="无"}
                <a href="{:url('spread',['tuijianren'=>$vo['tuijianren'],'search_date'=>$d])}"><strong class="mcolor">{$vo['num']}</strong></a>
                {else/}
                {$vo['num']}
                {/neq}
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