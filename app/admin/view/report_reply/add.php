<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <textarea type="text" rows="8" class="layui-textarea field-content" name="content" lay-verify="required" autocomplete="off" placeholder="请输入内容"></textarea>
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