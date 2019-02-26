<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <label class="layui-form-label">选择类型</label>
        <div class="layui-input-inline">
            <select name="pid" class="field-pid" type="select" lay-filter="pid">
                {$menu_option}
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">积分事件</label>
        <div class="layui-input-inline">
            <textarea class="layui-textarea field-name" name="name" lay-verify="required" autocomplete="off"></textarea>
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item" style="display: none;" id="tuijian_div">
        <label class="layui-form-label">产量</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-score" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="score" autocomplete="off" placeholder="请输入预设值">
        </div>
        <div class="layui-form-mid">斗</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状态设置</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-status" name="status" value="1" title="启用" checked readonly>
            <input type="radio" class="field-status" name="status" value="0" title="禁用" readonly>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id" value="{$p_res.id|default=0}">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
var formData = {:json_encode($data_info)};
layui.use(['form'], function() {
    var $ = layui.jquery, form = layui.form;

    form.on('select(pid)', function(data){
        if(1 == data.value){
            $('#tuijian_div').hide();
        }else {
            $('#tuijian_div').show();
        }
    });
    // form.render();
});
</script>
<script src="__ADMIN_JS__/footer.js"></script>