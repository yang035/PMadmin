<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        {notempty name="Request.param.placeholder"}
        <div class="layui-form-item">
            <label class="layui-form-label">完成比例</label>
            <div class="layui-input-inline">
                <input type="number" class="layui-input field-ratio" name="ratio" value="0" min="0" max="{$Request.param.placeholder}" onblur="check_ratio(this)" autocomplete="off" placeholder="请输入完成比例">
            </div>
            <div class="layui-form-mid red">% 最大值  {$Request.param.placeholder}</div>
        </div>
        {/notempty}
        <div class="layui-form-item">
            <label class="layui-form-label">描述</label>
            <div class="layui-input-inline">
                <textarea  class="layui-textarea field-remark" name="remark" lay-verify="" autocomplete="off" placeholder="[选填]分类简介"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">附件</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="test3"><i class="layui-icon"></i>上传文件</button>
                <input class="layui-input upload_id" type="hidden" name="upload_id" value="">
                <span class="att_name"></span>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-subject_id" name="subject_id" value="{$Request.param.subject_id}">
            <input type="hidden" class="field-flow_id" name="flow_id" value="{$Request.param.flow_id}">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','upload'], function() {
        var $ = layui.jquery, laydate = layui.laydate, upload = layui.upload;
        laydate.render({
            elem: '.field-expire_time',
            min:'0'
        });

        $('#reset_expire').on('click', function(){
            $('input[name="expire_time"]').val(0);
        });

        upload.render({
            elem: '#test3',
            url: '{:url("admin/UploadFile/upload?thumb=no&water=no")}',
            accept: 'file', //普通文件
            size:"{:config('upload.upload_file_size')}",
            done: function(res){
                if(res.code == 1) { //上传成功
                    $('.upload_id').val(res.data.id);
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
    function check_ratio(e) {
        var num = parseInt($(e).val()),ma=e.getAttribute('max'),mi=e.getAttribute('min');
        if (isNaN(num)) {
            num = 0;
        }
        if (num > ma) {
            layer.msg('比例只能在'+mi+'~'+ma+'之间');
            num = ma;
        }
        if (num < mi) {
            layer.msg('比例只能在'+mi+'~'+ma+'之间');
            num = mi;
        }
        $('.field-ratio').val(num);
    }
</script>
<script src="__ADMIN_JS__/footer.js"></script>