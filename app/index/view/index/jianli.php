{__NOLAYOUT__}
<link rel="stylesheet" href="/static/admin/js/layui/css/layui.css?v=">
<script src="/static/admin/js/layui/layui.js?v="></script>
<link rel="stylesheet" href="/static/js/layer/skin/default/layer.css?v=">
<script src="/static/js/layer/layer.js?v="></script>

<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">手机号码</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-mobile" name="mobile" lay-verify="required" onkeyup="value=value.replace(/[^\d]/g,'')" lay-verify="phone" maxlength="11"
                       autocomplete="off" placeholder="请输入手机号码">
            </div>
            <div class="layui-form-mid red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">简历</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn layui-btn-warm" id="test3"><i class="layui-icon"></i>上传附件</button>
                <input class="layui-input attachment" type="hidden" name="attachment" value="" lay-verify="required">
                <span class="att_name"></span>
            </div>
            <div class="layui-form-mid red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-inline">
                <textarea  class="layui-textarea field-remark" name="remark" lay-verify="" autocomplete="off" placeholder="[选填]备注"></textarea>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" name="zhaopin_id" value="{$Request.param.id}">
            <input type="hidden" name="cid" value="{$Request.param.cid}">
            <input type="hidden" name="job" value="{$Request.param.title}">
            <button type="submit" class="layui-btn layui-btn-warm" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
<script>

    layui.use(['jquery', 'laydate','upload'], function() {
        var $ = layui.jquery, laydate = layui.laydate, upload = layui.upload;

        upload.render({
            elem: '#test3',
            url: '{:url("admin/UploadFile/upload?thumb=no&water=no")}',
            accept: 'file', //普通文件
            size:"{:config('upload.upload_file_size')}",
            done: function(res){
                if(res.code == 1) { //上传成功
                    $('.attachment').val(res.data.file);
                    var att_name = $('.att_name').val();
                    att_name += "<a target='_blank' href='"+res.data.file +"'>"+ res.data.name+"</a>,";
                    $('.att_name').html(att_name);
                    layer.msg(res.msg);
                }else {
                    layer.msg(res.msg);
                }
            }
        });
    });
    </script>