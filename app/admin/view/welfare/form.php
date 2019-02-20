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
        <div class="layui-inline">
            <label class="layui-form-label">目标值比例范围</label>
            <div class="layui-input-inline" style="width: 100px;">
                <input type="number" name="min_num" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" placeholder="小值" autocomplete="off" class="layui-input field-min_num">
            </div>
            <div class="layui-form-mid">%&nbsp;&nbsp; < X <= </div>
            <div class="layui-input-inline" style="width: 100px;">
                <input type="number" name="max_num" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" placeholder="大值" autocomplete="off" class="layui-input field-max_num">
            </div>
            <div class="layui-form-mid">%</div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">奖励百分比</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-prize_ratio" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="prize_ratio" value="0" lay-verify="required" autocomplete="off" placeholder="请输入奖励百份比">
        </div>
        <div class="layui-form-mid" style="color: red">%&nbsp;&nbsp;*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">带薪年假</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-annual_leave" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="annual_leave" value="0" autocomplete="off" placeholder="请输入天数">
        </div>
        <div class="layui-form-mid" style="color: red">天&nbsp;&nbsp;*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">旅游金</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-travel_money" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="travel_money" value="0" autocomplete="off" placeholder="请输入金额">
        </div>
        <div class="layui-form-mid" style="color: red">元&nbsp;&nbsp;*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">设备折旧比例</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-davice_use" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="davice_use" value="0" autocomplete="off" placeholder="请输入整数">
        </div>
        <div class="layui-form-mid" style="color: red">‰&nbsp;&nbsp;*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">技能培训比例</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-train_per" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="train_per" value="0" autocomplete="off" placeholder="请输入整数">
        </div>
        <div class="layui-form-mid" style="color: red">‰&nbsp;&nbsp;*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">就业指导比例</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-job_train" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="job_train" value="0" autocomplete="off" placeholder="请输入整数">
        </div>
        <div class="layui-form-mid" style="color: red">‰&nbsp;&nbsp;*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">损差补偿比例</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-compensation" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="compensation" value="0" autocomplete="off" placeholder="请输入整数">
        </div>
        <div class="layui-form-mid" style="color: red">‰&nbsp;&nbsp;*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">社保</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-social" name="social" value="1" title="有" checked>
            <input type="radio" class="field-social" name="social" value="0" title="无">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">公积金</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-accumulation" name="accumulation" value="1" title="有" checked>
            <input type="radio" class="field-accumulation" name="accumulation" value="0" title="无">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">个人意外险</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-accident_insurance" name="accident_insurance" value="1" title="有" checked>
            <input type="radio" class="field-accident_insurance" name="accident_insurance" value="0" title="无">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">家人幸福险</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-happy_insurance" name="happy_insurance" value="1" title="有" checked>
            <input type="radio" class="field-happy_insurance" name="happy_insurance" value="0" title="无">
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