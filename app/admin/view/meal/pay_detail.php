{include file="block/layui" /}
<div class="layui-card">
    <div class="layui-card-body">
        <b>订单编号：</b>{$payData['trade_no']}<br>
        <b>待支付：</b>{$payData['amount']} 元 <br>
    </div>
    <div><a href="{$pay_url}"><img src="__ADMIN_IMG__/zhifubao.png"></a> </div>
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