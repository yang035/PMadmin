<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">洽商记录</label>
            <div class="layui-input-block">
                <textarea id="ckeditor" name="content" class="field-content">{$record_format|default=''}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">洽商报告</label>
            <div class="layui-input-block">
                <textarea id="ckeditor2" name="report" class="field-report"></textarea>
            </div>
        </div>
        {:editor(['ckeditor', 'ckeditor2'],'kindeditor')}
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                <input type="radio" class="field-status" name="status" value="1" title="启用" checked>
                <input type="radio" class="field-status" name="status" value="0" title="禁用">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <input type="hidden" class="field-subject_id" name="subject_id" value="{$Request.param.subject_id}">
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