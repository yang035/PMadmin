<style>
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">类型</label>
            <div class="layui-input-inline">
                <select name="cat_id" class="field-cat_id" type="select">
                    {$cat_option}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">专业名称</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-name" name="name" lay-verify="required" autocomplete="off" placeholder="请输入专业名称">
            </div>
            <div class="layui-form-mid red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">专业系数</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-ratio" name="ratio" value="1.0" maxlength="6" max="100" oninput="value=moneyInput(value)" onkeyup="if(value>1){value=1.00}" lay-verify="required" autocomplete="off" placeholder="请输入系数">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">关联系数</label>
            <div class="layui-input-inline" style="width: 500px">
                {volist name="association_coefficient" id="f1"}
                {in name="key" value="$data_info['association_coefficient']"}
                <input type="checkbox" name="association_coefficient[]" value="{$key}" checked lay-skin="primary" title="{$f1}">
                {else/}
                <input type="checkbox" name="association_coefficient[]" value="{$key}" lay-skin="primary" title="{$f1}">
                {/in}
                {/volist}
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">进度占比</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-jindu_per" name="jindu_per" value="0" maxlength="3" max="100" onkeyup="value=value.replace(/[^\d]/g,''); if(value>100){value=100}" autocomplete="off" placeholder="M">
            </div>
            <div class="layui-form-mid">%</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-inline">
                <textarea class="layui-textarea field-remark" name="remark" lay-verify="" autocomplete="off" placeholder="[选填]备注"></textarea>
            </div>
        </div>
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
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','upload','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate,upload = layui.upload,form = layui.form;
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>