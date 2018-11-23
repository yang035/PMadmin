<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <label class="layui-form-label">检查分类</label>
        <div class="layui-input-inline">
            <select name="cat_id" class="field-cat_id" type="select">
                {volist name='index_tab' id='vo'}
                <option value="{$key}">{$vo.title}</option>
                {/volist}
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-title" name="title" lay-verify="required" autocomplete="off" placeholder="请输入标题">
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">概述</label>
        <div class="layui-input-inline">
            <textarea type="text" class="layui-textarea field-summarize" name="summarize" autocomplete="off" placeholder="请输入概述"></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">作者</label>
        <div class="layui-input-inline">
            <input type="text" data-disabled class="layui-input field-author" name="author" lay-verify="required" autocomplete="off" placeholder="请输入作者">
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">标签</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-tags" name="tags" autocomplete="off" placeholder="标签用引文','隔开">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">缩略图</label>
        <div class="layui-input-inline upload">
            <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}">请上传首页缩略图</button>
            <input type="hidden" class="upload-input field-thumb" name="thumb" value="">
            <img src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">内容</label>
        <div class="layui-input-block">
            <textarea id="ckeditor" name="content" class="field-content"></textarea>
        </div>
        <div class="layui-form-mid" style="color: red">*(上传图片不能为中文名)</div>
    </div>
    {:editor(['ckeditor', 'ckeditor2'],'kindeditor')}
    <div class="layui-form-item">
        <label class="layui-form-label">推&nbsp;&nbsp;&nbsp;&nbsp;荐</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-is_push" name="is_push" value="1" title="是">
            <input type="radio" class="field-is_push" name="is_push" value="0" title="否" checked>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状&nbsp;&nbsp;&nbsp;&nbsp;态</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-status" name="status" value="1" title="启用">
            <input type="radio" class="field-status" name="status" value="0" title="禁用" checked>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
var formData = {:json_encode($data_info)};

layui.use(['jquery', 'laydate', 'upload'], function() {
    var $ = layui.jquery, laydate = layui.laydate, layer = layui.layer, upload = layui.upload;
    upload.render({
        elem: '.layui-upload',
        url: '{:url("admin/UploadFile/upload?thumb=no&water=no")}'
        ,method: 'post'
        ,before: function(input) {
            layer.msg('文件上传中...', {time:3000000});
        },done: function(res, index, upload) {
            var obj = this.item;
            if (res.code == 0) {
                layer.msg(res.msg);
                return false;
            }
            layer.closeAll();
            var input = $(obj).parents('.upload').find('.upload-input');
            if ($(obj).attr('lay-type') == 'image') {
                input.siblings('img').attr('src', res.data.file).show();
            }
            input.val(res.data.file);
        }
    });
    $('.upload img').attr('src', $('.field-thumb').val()).show();
    // 日期渲染
    laydate.render({elem: '.layui-date'});
});
</script>
<script src="__ADMIN_JS__/footer.js"></script>