<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>名称：</b>{$data_info['name']}<br>
            <b>支付金额：</b>{$data_info['other_price']}<br>
            <b>退款原因：</b>{$data_info['refund_option']}<br>
            <b>说明：</b>{$data_info['comment']}<br>
            <b>附件：</b><a href="{$data_info['attach']}" target="_blank">查看</a><br>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <a href="{:url('refundConfirm')}?trade_no={$data_info['trade_no']}" class="layui-btn layui-btn-normal">退款</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>