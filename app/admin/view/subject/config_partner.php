<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        {notempty name="data_info['partner_user']"}
        {volist name="data_info['partner_user']" id="vo"}
            <div class="layui-form-item">
                <label class="layui-form-label">{$vo['realname']}</label>
                <div class="layui-input-inline">
                    <select name="partner_user[{$key}]" class="field-partner_user" type="select">
                        <option value="">请选择</option>
                        {volist name="partner_grade" id="v"}
                        <option value="{$key}" {eq name="key" value="$vo['p']"} selected {/eq}>{$v}</option>
                        {/volist}
                    </select>
                </div>
            </div>
        {/volist}
        {/notempty}
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
    layui.use(['jquery', 'laydate'], function() {
        var $ = layui.jquery, laydate = layui.laydate;
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>