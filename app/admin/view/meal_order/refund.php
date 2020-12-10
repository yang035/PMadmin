<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>名称：</b>{$data_info['name']}<br>
            <b>支付金额：</b>{$data_info['other_price']}<br>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">退款原因</label>
            <div class="layui-input-inline">
                <select name="refund_option" class="field-refund_option" type="select" lay-filter="refund_option">
                    {$refund_option}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">说明</label>
            <div class="layui-input-inline">
                <textarea  class="layui-textarea field-comment" name="comment" autocomplete="off" placeholder="[选填]说明"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">附件</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn" id="test3"><i class="layui-icon"></i>上传文件</button>
                <input class="layui-input attach" type="hidden" name="attach" value="">
                <span class="att_name"></span>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">确认退款</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>取消</a>
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
                    $('.attach').val(res.data.file);
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
<script src="__ADMIN_JS__/footer.js"></script>