<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">学习时长</label>
            <div class="layui-input-inline">
                <input  class="layui-input field-time_long" name="time_long" lay-verify="required" onkeyup="value=value.replace(/[^\d,]/g,'')" autocomplete="off" >
            </div>
            <div class="layui-form-mid red">小时</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">心得体会</label>
            <div class="layui-input-block">
                <textarea id="ckeditor" name="remark" class="field-remark"></textarea>
            </div>
        </div>
        {:editor(['ckeditor', 'ckeditor2'],'kindeditor')}
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate'], function() {
        var $ = layui.jquery, laydate = layui.laydate;
    });

</script>
<script src="__ADMIN_JS__/footer.js"></script>