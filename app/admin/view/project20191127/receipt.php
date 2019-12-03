<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">问题描述</label>
            <div class="layui-form-mid red">{$q['check_name']}</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">修改建议</label>
            <div class="layui-form-mid red">{$q['mark']}</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">完成状态</label>
            <div class="layui-input-inline">
                <input type="radio" class="field-isfinish" name="isfinish" value="1" title="完成" checked>
                <input type="radio" class="field-isfinish" name="isfinish" value="0" title="待完成">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-inline">
                <textarea  class="layui-textarea field-remark" name="remark" lay-verify="" autocomplete="off" placeholder="备注"></textarea>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
            <input type="hidden" class="field-q_id" name="q_id" value="{$Request.param.q_id}">
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
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>