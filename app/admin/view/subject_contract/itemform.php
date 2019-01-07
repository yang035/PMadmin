<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">合同名称</label>
            <div class="layui-input-inline">
                <input class="layui-input field-name" name="name" lay-verify="required" autocomplete="off">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">选择类型</label>
            <div class="layui-input-inline">
                <select name="contract_cat" class="field-contract_cat" type="select" lay-filter="contract_cat">
                    {$contract_cat}
                </select>
                <select name="tpl_id" class="field-tpl_id" type="select" lay-filter="tpl_id">
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">内容</label>
            <div class="layui-input-block">
                <textarea id="ckeditor" name="content" class="field-content"></textarea>
            </div>
        </div>
        {:editor(['ckeditor', 'ckeditor2'],'kindeditor')}
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
            <input type="hidden" class="field-subject_id" name="subject_id" value="{$Request.param.subject_id}">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate, form = layui.form;

        form.on('select(contract_cat)', function(data){
            var open_url = "{:url('getContractItem')}?cat_id="+data.value;
            $.post(open_url, function(data){
                $(".field-tpl_id").html(data);
                form.render('select');
            });
        });
        form.on('select(tpl_id)', function(data){
            var open_url = "{:url('getItemById')}?id="+data.value;
            $.post(open_url, function(data){
                KindEditor.html('#ckeditor',data);
            });
        });
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>