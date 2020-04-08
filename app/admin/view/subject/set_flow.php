<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
    {volist name="flow" id="f"}
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
        <legend>{$flow_cat[$key]}</legend>
    </fieldset>
    <dl>
        <dd>
            {volist name="f" id="f1"}
            {in name="key" value="$d"}
            <input type="checkbox" name="flow[]" value="{$key}" checked lay-skin="primary" title="{$f1}">
            {else/}
            <input type="checkbox" name="flow[]" value="{$key}" lay-skin="primary" title="{$f1}">
            {/in}
            {/volist}
        </dd>
    </dl>
    {/volist}
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
        laydate.render({
            elem: '.field-expire_time',
            min:'0'
        });

        $('#reset_expire').on('click', function(){
            $('input[name="expire_time"]').val(0);
        });
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>