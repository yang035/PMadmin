<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="form1">
    <div class="layui-form-item">
        <label class="layui-form-label">模板文件</label>
        <div class="layui-form-mid"><a href="/template/tpl6.xlsx"><font style="color: red">点击下载模板</font></a></div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">项目名</label>
        <div class="layui-input-inline">
            <select name="project_id" class="layui-input field-project_id" type="select" lay-filter="project" lay-search>
                {$project_select}
            </select>
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-upload">
        <label class="layui-form-label">选择文件</label>
        <button type="button" class="layui-btn layui-btn-normal" id="choosefile">选择文件</button>
        <a href="javascript:void(0)" class="layui-btn layui-btn-danger" id="test9">导入</a>
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
            elem: '#choosefile'
            ,data: {
                project_id: function(){
                    return project_id;
                }
            }
            ,url: "{:url('jiesuanimport')}"
            ,auto: false
            ,accept: 'file' //普通文件
            ,bindAction: '#test9'
            ,before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                layer.load(); //上传loading
            }
            ,done: function(res){
                if(res.code==1){
                    layer.alert(res.msg, {icon: 6},function () {
                        var index = parent.layer.getFrameIndex(window.name);//获取窗口索引
                        parent.layer.close(index);//关闭layer
                        window.parent.location.reload();//刷新父页面
                    });
                }else{
                    layer.alert(res.msg, {icon: 5},function () {
                        window.location.reload();
                    });

                }

                $('#res').html(res.info);
            }
        });

    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>