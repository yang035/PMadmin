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
        <label class="layui-form-label">任务名称</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-name" name="name" value="{$Request.param.pname}" lay-verify="required" readonly autocomplete="off" placeholder="请输入名称">
        </div>
        <div class="layui-form-mid">公共<span id="max_score" style="color: red;">{$data_list['score']}</span>分</div>
    </div>
    {volist name="x_user" id="vo"}
    <div class="layui-form-item">
        <label class="layui-form-label">{$vo}</label>
        <input type="hidden" class="field-u_id" name="u_id[]" value="{$key}">
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-add_score" name="add_score[]" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" autocomplete="off" placeholder="得分">
        </div>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-sub_score" name="sub_score[]" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" autocomplete="off" placeholder="扣分">
        </div>
    </div>
    {/volist}
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
            <input type="hidden" class="field-code" name="code" value="{$Request.param.code}">
            <input type="hidden" class="field-pscore" name="pscore" value="{$data_list['score']}">
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
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>