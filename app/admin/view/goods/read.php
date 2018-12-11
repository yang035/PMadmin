<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-tab-item layui-form layui-show">
        <div class="layui-form-item">
            <label class="layui-form-label">选择类型</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-cat_name" id="menu_parent_name" lay-verify="required"
                       name="cat_name" autocomplete="off" readonly value="{$data_info['category']['name']|default=''}">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-title" name="title" lay-verify="required"
                       autocomplete="off" readonly placeholder="请输入物品名称">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">描述</label>
            <div class="layui-input-inline">
                <textarea type="text" class="layui-textarea field-description" name="description" lay-verify="required"
                          autocomplete="off" readonly placeholder="请输入不超过200字的描述" maxlength="120"></textarea>
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">市场价格</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-marketprice" name="marketprice" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" lay-verify="required" maxlength="11"
                       autocomplete="off" readonly placeholder="请输入市场价格">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">库存数</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-total" name="total" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" lay-verify="required" maxlength="11"
                       autocomplete="off" readonly placeholder="请输入市场价格">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">单位</label>
            <div class="layui-input-inline">
                <select name="unit" class="field-unit" type="select" readonly="">
                    {$unit_option}
                </select>
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">缩略图</label>
            <div class="layui-input-inline">
                <img id="thumb" src="{$data_info['thumb']|default=''}" style="border-radius:5px;border:1px solid #ccc" width="36" height="36">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">内容</label>
            <div class="layui-input-inline">
                <textarea id="ckeditor" name="content" class="layui-textarea field-content" readonly></textarea>
            </div>
        </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
    layui.use(['jquery', 'laydate', 'upload','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate, layer = layui.layer, upload = layui.upload, form = layui.form;

        if(1 == formData.is_push){
            $('#tuijian_div').show();
        }

        form.on('radio(is_push)', function(data){
            if(1 == data.value){
                $('#tuijian_div').show();
            }else {
                $('#tuijian_div').hide();
            }
        });

        // 日期渲染
        laydate.render({elem: '.layui-date'});
        form.render();
    });

</script>
<script src="__ADMIN_JS__/footer.js"></script>