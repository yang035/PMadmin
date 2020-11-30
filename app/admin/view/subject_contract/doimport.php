<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="form1">
    <div class="layui-form-item">
        <label class="layui-form-label">项目名</label>
        <div class="layui-input-inline">
            <select name="subject_id" class="layui-input field-subject_id" type="select" lay-filter="project" lay-search>
                {$project_select}
            </select>
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">项目编号</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-idcard" name="idcard" autocomplete="off" placeholder="请输入合同编号" value="">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">合同名称</label>
        <div class="layui-input-inline">
            <input class="layui-input field-name" name="name" lay-verify="required" autocomplete="off">
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">附件</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn layui-btn-normal" id="test3"><i class="layui-icon"></i>上传文件</button>
            <input class="layui-input attachment" type="hidden" name="attachment" value="">
            <span class="att_name"></span>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
    <div id="res"></div>
</form>
{include file="block/layui" /}
<script src="__PUBLIC_JS__/jquery.select.js?v="></script>
<script src="__PUBLIC_JS__/SelectBox.min.js?v="></script>
<script>
    var formData = {:json_encode($data_info)};
    layui.use(['form','upload'], function() {
        var $ = layui.jquery, form = layui.form, upload = layui.upload,project_id='';

        form.on('select(project)', function(data){
            project_id = data.value;
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
                    att_name += "<a target='_blank' href='"+res.data.file +"'>"+ res.data.name+"</a>";
                    $('.att_name').html(att_name);
                    $('.name').val(res.data.name);
                    layer.msg(res.msg);
                }else {
                    layer.msg(res.msg);
                }
            }
        });

    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>