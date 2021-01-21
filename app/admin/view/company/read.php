<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>公司名称：</b>{$data_list['name']}<br>
            <b>地址：</b>{$data_list['address']}<br>
            <b>法人：</b>{$data_list['legal_person']}<br>
            <b>手机号码：</b>{$data_list['cellphone']}<br>
            <b>统一社会信用代码：</b>{$data_list['business_license']}<br>
            <b>网站域名：</b>{$data_list['domain_name']}<br>
            <b>网站备案号：</b>{$data_list['record_number']}<br>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>