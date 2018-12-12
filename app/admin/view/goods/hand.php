<style>
    .layui-upload-img {
        width: 92px;
        height: 92px;
        margin: 0 10px 10px 0;
        display: none;
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
        <label class="layui-form-label">姓名</label>
        <div class="layui-form-mid">{$data_info['realname']|default=''}</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">事由</label>
        <div class="layui-form-mid">{$data_info['reason']|default=''}</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">审批人</label>
        <div class="layui-form-mid">{$data_info['send_user']|default=''}</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-form-mid">{$data_info['status']|default=''}</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">申请时间</label>
        <div class="layui-form-mid">{$data_info['create_time']|default=''}</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">物品明细</label>
        {volist name="$data_info['goods']" id="vo"}
        <div class="layui-input-inline" style="width: 100px">
            <input type="text" class="layui-input field-name" name="name[]" lay-verify="required" value="{$vo['name']}" readonly autocomplete="off" placeholder="名称">
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <input type="hidden" class="layui-input field-good_id" name="good_id[]" lay-verify="required" value="{$vo['id']}">
            <input type="number" class="layui-input field-number" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="number[]" value="{$vo['number']}" lay-verify="required" autocomplete="off" placeholder="数量">
        </div>
        {/volist}
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
    layui.use(['form'], function() {
        var $ = layui.jquery, form = layui.form;
        if (formData) {
            $('.ass-level').val(parseInt($('.field-pid option:selected').attr('level'))+1);
        }
        $('.layui-btn-primary').click(function () {
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
        });
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>