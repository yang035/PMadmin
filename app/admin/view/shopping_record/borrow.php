<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <label class="layui-form-label">可借物品</label>
        <div class="layui-input-inline">
            <textarea type="text" class="layui-textarea field-title" rows="10" lay-verify="required" name="title" autocomplete="off" placeholder="请输入经典语录">{$data_info}</textarea>
        </div>
        <div class="layui-form-mid" style="color: red">*(一行一个，原来的数据不能删除)</div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id" value="1">
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