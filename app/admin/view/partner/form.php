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
        <label class="layui-form-label">合伙级别</label>
        <div class="layui-input-inline">
            <select name="partnership_grade" class="field-partnership_grade" type="select" lay-filter="partnership_grade">
                {$partnership_grade}
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">最低目标产值</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-min_target" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="min_target" lay-verify="required" autocomplete="off" placeholder="请输入最小目标产值">
        </div>
        <div class="layui-form-mid">万</div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">年收益比例</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-year_per" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="year_per" autocomplete="off" placeholder="请输入年收益分配比例">
        </div>
        <div class="layui-form-mid">%</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">月收益比例</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-month_per" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="month_per" autocomplete="off" placeholder="请输入月收益分配比例">
        </div>
        <div class="layui-form-mid">%</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">工资发放形式</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-type" name="type" value="1" title="按年薪" checked>
            <input type="radio" class="field-type" name="type" value="0" title="按月薪">
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