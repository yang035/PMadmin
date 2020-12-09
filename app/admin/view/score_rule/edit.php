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
        <label class="layui-form-label">MLGL事件</label>
        <div class="layui-input-inline">
            <textarea class="layui-textarea field-name" name="name" lay-verify="required" autocomplete="off"></textarea>
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item" style="display: none;" id="tuijian_ml">
        <label class="layui-form-label">ML</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-ml" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="ml" autocomplete="off" placeholder="请输入奖扣值">
        </div>
        <div class="layui-form-mid">斗</div>
    </div>
    <div class="layui-form-item" style="display: none;" id="tuijian_gl">
        <label class="layui-form-label">GL</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-gl" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="gl" autocomplete="off" placeholder="请输入奖扣值">
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
            <input type="hidden" class="field-id" name="id" value="">
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

        if ((formData.code).length > 2){
            $('#tuijian_ml').show();
            $('#tuijian_gl').show();
        }
        form.on('select(pid)', function(data){
            if(1 == data.value){
                $('#tuijian_ml').hide();
                $('#tuijian_gl').hide();
            }else {
                $('#tuijian_ml').show();
                $('#tuijian_gl').show();
            }
        });
        // form.render();
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>