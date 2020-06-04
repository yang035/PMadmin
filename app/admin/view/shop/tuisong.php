<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">公司类型</label>
            <div class="layui-input-inline">
                <select name="tj_company_type" class="field-tj_company_type" type="select" lay-filter="sys_type">
                    {$sys_type}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">选择公司</label>
            <div class="layui-input-inline">
                <select name="tj_company" class="field-tj_company" type="select">
                    {$company_option}
                </select>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">确认</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate'], function () {
        var $ = layui.jquery, laydate = layui.laydate;
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>