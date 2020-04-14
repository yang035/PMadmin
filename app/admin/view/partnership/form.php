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
        <label class="layui-form-label">名称</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-name" name="name" lay-verify="required" autocomplete="off">
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">合伙系数</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-ratio" name="ratio" lay-verify="required" autocomplete="off" value="0">
        </div>
        <div class="layui-form-mid" style="color: red">*(介于0-1之间)</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">绩效指标</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-type" name="type" value="1" title="月度" checked>
            <input type="radio" class="field-type" name="type" value="2" title="季度">
            <input type="radio" class="field-type" name="type" value="3" title="年度">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">核定任务量</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-quantity" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="quantity" autocomplete="off" value="0">
        </div>
        <div class="layui-form-mid" style="color: red">%</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">项目红利</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-bonus" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="bonus" autocomplete="off" value="0">
        </div>
        <div class="layui-form-mid" style="color: red">%(占年度企业利润比例)</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">社保</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-social" name="social" value="1" title="有" checked>
            <input type="radio" class="field-social" name="social" value="2" title="无">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">公积金</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-accumulation" name="accumulation" value="1" title="有" checked>
            <input type="radio" class="field-accumulation" name="accumulation" value="2" title="无">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">个人意外险</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-accident_insurance" name="accident_insurance" value="1" title="有" checked>
            <input type="radio" class="field-accident_insurance" name="accident_insurance" value="2" title="无">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">带薪年假</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-annual_leave" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="annual_leave" value="0" autocomplete="off">
        </div>
        <div class="layui-form-mid" style="color: red">天/年&nbsp;&nbsp;*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">旅游金</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-travel_money" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="travel_money" value="0" autocomplete="off">
        </div>
        <div class="layui-form-mid" style="color: red">元/年&nbsp;&nbsp;*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">职业技能培训</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-train_per" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="train_per" value="0" autocomplete="off">
        </div>
        <div class="layui-form-mid" style="color: red">元/年&nbsp;&nbsp;*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">设备补贴</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-subsidy" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="subsidy" value="0" autocomplete="off">
        </div>
        <div class="layui-form-mid" style="color: red">元/年&nbsp;&nbsp;*</div>
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