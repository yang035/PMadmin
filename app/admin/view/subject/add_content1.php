<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">描述</label>
            <div class="layui-input-inline">
                <textarea  class="layui-textarea field-remark" name="remark" lay-verify="" autocomplete="off" placeholder="[选填]分类简介"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">附件</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn layui-btn-normal" id="test3"><i class="layui-icon"></i>上传文件</button>
                <input class="layui-input attachment" type="hidden" name="attachment" value="{$data_info['attachment']|default=''}">
                <input class="layui-input att_name" type="hidden" name="att_name" id="attachment_name" value="{$data_info['att_name']|default=''}">
                <span class="att_name"><a target='_blank' class="mcolor" href="{$data_info['attachment']|default=''}">{$data_info['att_name']|default=''}</a></span>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-subject_id" name="subject_id" value="{$Request.param.subject_id}">
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
                    $('.attachment').val(res.data.file);
                    var att_name = $('.att_name').val();
                    att_name += "<a target='_blank' class='mcolor' href='"+res.data.file +"'>"+ res.data.name+"</a>";
                    $('.att_name').html(att_name);
                    $('#attachment_name').val(res.data.name);
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