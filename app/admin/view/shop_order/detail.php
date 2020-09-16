<style>
    .layui-form-item .layui-input-inline {
        float: left;
        width: auto;
    }
</style>
<div class="page-toolbar">
    <div class="page-filter">
        <form class="layui-form layui-form-pane" action="{:url()}" method="get" id="search_form">
            <div class="layui-inline">
                <div class="layui-input-inline">
                    <button type="button" class="layui-btn layui-btn-primary" id="person_user_id">选择人员</button>
                    <div id="person_select_id"></div>
                    <input type="hidden" name="person_user" id="person_user" value="">
                </div>
            </div>
            <input type="hidden" name="item_id" value="{$Request.param.item_id}">
            <input type="hidden" name="export" value="">
            <button type="submit" class="layui-btn layui-btn-normal normal_btn">搜索</button>
            <input type="button" class="layui-btn layui-btn-primary layui-icon export_btn" value="导出">
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
            <th>姓名</th>
            <th>商品名称</th>
            <th>数量(份)</th>
            <th>总消耗(斗)</th>
            <th>额外支付(元)</th>
            <th>支付状态</th>
            <th>状态</th>
            <th>添加时间</th>
        </tr>
        </thead>
        <tbody>
        {volist name="data_list" id="vo"}
        <tr>
            <td><input type="checkbox" name="ids[]" class="layui-checkbox checkbox-ids" value="{$vo['id']}" lay-skin="primary"></td>
            <td class="font12">{$vo['realname']}</td>
            <td class="font12">{$vo['name']}</td>
            <td class="font12">{$vo['num']}</td>
            <td class="font12">{$vo['total_score']}</td>
            <td class="font12">{$vo['other_price']}</td>
            <td class="font12">
                {if condition="$vo['is_pay'] == 1"}
                    <a href="{$vo['pay_url']}" class="layui-btn-sm layui-btn-warm">{$pay_status[$vo['is_pay']]}</a>
                {elseif condition="$vo['is_pay'] == 0" /}
                    <span style="color: green">{$pay_status[$vo['is_pay']]}</span>
                {elseif condition="$vo['is_pay'] == 2" /}
                    <span style="color: green">{$pay_status[$vo['is_pay']]}</span>
                    <a href="{:url('refund')}?trade_no={{ d.trade_no }}" class="layui-btn layui-btn-xs layui-btn-normal">退款</a>
                {else/}
                    <span style="color: red">{$pay_status[$vo['is_pay']]}</span>
                {/if}
            </td>
            <td class="font12">
                <input type="checkbox" name="status" value="{$vo['status']}" lay-skin="switch" lay-filter="switchStatus" lay-text="已申请|已发放" {if condition="$vo['status'] eq 1"}checked {/if} data-href="{:url('status')}?table=shop_order&id={$vo['id']}">
            </td>
            <td class="font12">{$vo['create_time']}</td>
        </tr>
        {/volist}
        </tbody>
    </table>
    {$pages}
</div>

{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
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

        $('#person_user_id').on('click', function(){
            var person_user = $('#person_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=person&u="+person_user;
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type:2,
                title :'员工列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success:function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
        });
    });
</script>