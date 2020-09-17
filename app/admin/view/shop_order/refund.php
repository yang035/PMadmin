<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>名称：</b>{$data_info['name']}<br>
            <b>支付金额：</b>{$data_info['other_price']}<br>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button onclick="refund({$data_info['out_trade_no']})" class="layui-btn layui-btn-normal">确认退款</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>取消</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
    function refund(out_trade_no) {
        var open_url = "{:url('refundConfirm')}?out_trade_no="+out_trade_no;
        window.location.href = open_url;
    }
</script>
<script src="__ADMIN_JS__/footer.js"></script>