<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>公司名称：</b>{$data_list['name']}<br>
            <b>地址：</b>{$data_list['address']}<br>
            <b>法人：</b>{$data_list['legal_person']}<br>
            <b>手机号码：</b>{$data_list['cellphone']}<br>
            <b>网站域名：</b>{$data_list['domain_name']}<br>
            <b>网站备案号：</b>{$data_list['record_number']}<br>
            <b>GL排名系数最大值：</b>{$data_list['max_rankratio']}<br>
            <b>GL排名系数最小值：</b>{$data_list['min_rankratio']}<br>
            <b>开票名称：</b>{$data_list['piao_name']}<br>
            <b>纳税人识别号：</b>{$data_list['identity_number']}<br>
            <b>开票地址和电话：</b>{$data_list['piao_address']}<br>
            <b>开户银行：</b>{$data_list['bank']}<br>
            <b>开户账号：</b>{$data_list['card_num']}<br>
            {notempty name="$data_list['code_path']"}
            <b>开票二维码：</b><img src="/{$data_list['code_path']}" style="height: 150px;width: 150px"><br>
            {/notempty}
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>