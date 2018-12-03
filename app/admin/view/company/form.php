<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <label class="layui-form-label">公司名称</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-name" name="name" lay-verify="required" autocomplete="off" placeholder="请输入公司名称">
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">地址</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-address" name="address" lay-verify="required" autocomplete="off" placeholder="请输入地址">
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">法人</label>
        <div class="layui-input-inline">
            <input type="text" data-disabled class="layui-input field-legal_person" name="legal_person" autocomplete="off" placeholder="请输入法人姓名">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">手机号码</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-cellphone" name="cellphone" autocomplete="off" placeholder="请输入手机号码">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">工商营业执照</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-business_license" name="business_license" autocomplete="off" placeholder="请输入工商营业执照">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">营业许可证</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-license" name="license" autocomplete="off" placeholder="请输入营业许可证">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">社会信用代码</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-credit_code" name="credit_code" autocomplete="off" placeholder="请输入社会信用代码">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">网站域名</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-domain_name" name="domain_name" autocomplete="off" placeholder="请输入网站域名">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">网站备案号</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-record_number" name="record_number" autocomplete="off" placeholder="请输入网站备案号">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状&nbsp;&nbsp;&nbsp;&nbsp;态</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-status" name="status" value="1" title="启用" checked>
            <input type="radio" class="field-status" name="status" value="0" title="禁用">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
var formData = {:json_encode($data_info)};

layui.use(['jquery', 'laydate'], function() {
    var $ = layui.jquery, laydate = layui.laydate;
    laydate.render({
        elem: '.field-expire_time',
        min:'0'
    });

    $('#reset_expire').on('click', function(){
        $('input[name="expire_time"]').val(0);
    });
    //获取设备信息
    // var device = layui.device();
    // alert(JSON.stringify(device));
});
</script>
<script src="__ADMIN_JS__/footer.js"></script>