<style>
    .layui-form-pane .layui-form-label {
        width: 150px;
        padding: 8px 15px;
        height: 38px;
        line-height: 20px;
        border-width: 1px;
        border-style: solid;
        border-radius: 2px 0 0 2px;
        text-align: center;
        background-color: #FBFBFB;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        box-sizing: border-box;
    }
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
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
            <input type="text" class="layui-input field-cellphone" name="cellphone" onkeyup="value=value.replace(/[^\d]/g,'')" maxlength="11" autocomplete="off" placeholder="请输入手机号码">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">纳税人识别号</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-business_license" name="business_license" onkeyup="value=value.replace(/[^\w\.\/]/ig,'')" maxlength="18" autocomplete="off" placeholder="请输入纳税人识别号">
        </div>
    </div>
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">营业许可证</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <input type="text" class="layui-input field-license" name="license" autocomplete="off" placeholder="请输入营业许可证">-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">社会信用代码</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <input type="text" class="layui-input field-credit_code" name="credit_code" autocomplete="off" placeholder="请输入社会信用代码">-->
<!--        </div>-->
<!--    </div>-->
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
        <label class="layui-form-label">GL排名系数最大值</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-max_rankratio" name="max_rankratio" oninput="value=moneyInput(value)" autocomplete="off" placeholder="GL排名系数最大值" maxlength="6" value="1.00">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">GL排名系数最小值</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-min_rankratio" name="min_rankratio" oninput="value=moneyInput(value)" autocomplete="off" placeholder="GL排名系数最小值" maxlength="6" value="1.00">
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

function moneyInput(value) {
    //修复第一个字符是小数点 的情况.
    let fa = '';
    if (value !== '' && value.substr(0, 1) === '.') {
        value = "";
    }
    value = value.replace(/^0*(0\.|[1-9])/, '$1');//解决 粘贴不生效
    value = value.replace(/[^\d.]/g, "");  //清除“数字”和“.”以外的字符
    value = value.replace(/\.{2,}/g, "."); //只保留第一个. 清除多余的
    value = value.replace(".", "$#$").replace(/\./g, "").replace("$#$", ".");
    value = value.replace(/^(\-)*(\d+)\.(\d\d).*$/, '$1$2.$3'); //只能输入两个小数
    if (value.indexOf(".") < 0 && value !== "") { //以上已经过滤，此处控制的是如果没有小数点，首位不能为类似于 01、02的金额
        if (value.substr(0, 1) === '0' && value.length === 2) {
            value = value.substr(1, value.length);
        }
    }
    value = fa + value;
    return value;
}
</script>
<script src="__ADMIN_JS__/footer.js"></script>