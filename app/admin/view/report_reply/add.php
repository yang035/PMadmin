<style>
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <label class="layui-form-label">得到产量<span style="color: red">*</span></label>
        <div class="layui-input-inline" style="width: 120px">
            <input type="number" class="layui-input field-real_score" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="real_score" lay-verify="required" autocomplete="off" placeholder="请输入可得产值">
        </div>
        <div class="layui-form-mid">不能超过<span id="max_score" style="color: red;">{$row['score']}</span>斗,最后一次产值为最终所得</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">内容</label>
        <div class="layui-input-inline">
            <textarea type="text" rows="8" class="layui-textarea field-content" name="content" lay-verify="required" autocomplete="off" placeholder="请输入内容"></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-pid" name="pid">
            <input type="hidden" class="field-report_id" name="report_id">
            <input type="hidden" class="field-project_id" name="project_id">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
<!--            <a href="{:url('project/editTask')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>-->
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
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>