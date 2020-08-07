{include file="block/layui" /}
<div class="layui-card">
    <div class="layui-card-body">
        <b>订单编号：</b>{$payData['trade_no']}<br>
        <b>待支付：</b>{$payData['amount']} 元 <br>
    </div>
    <div><a href="{$pay_url}">支付宝支付</a> </div>
</div>
<script>
    var formData = {:json_encode($data_info)};
    layui.use(['jquery', 'laydate'], function() {
        var $ = layui.jquery, laydate = layui.laydate;
        laydate.render({
            elem: '.field-expire_time',
            min:'0'
        });
    });
</script>