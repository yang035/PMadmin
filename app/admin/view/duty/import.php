<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="form1">
    <div class="layui-form-item">
        <label class="layui-form-label">模板文件</label>
        <div class="layui-form-mid"><a href="/template/mlgl0007.xlsx"><font style="color: red">点击下载模板</font></a></div>
    </div>
    <div class="layui-upload">
        <label class="layui-form-label">选择文件</label>
        <button type="button" class="layui-btn layui-btn-normal" id="choosefile">选择文件</button>
        <a href="javascript:void(0)" class="layui-btn layui-btn-danger" id="test9">导入</a>
    </div>
    <div id="res"></div>
    <div><span style="color: red">备注：Excel模板中“类别”要与系统中填写的对应</span></div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
    layui.use(['form','upload'], function() {
        var $ = layui.jquery, form = layui.form, upload = layui.upload;

        upload.render({
            elem: '#choosefile'
            ,url: "{:url('import')}"
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