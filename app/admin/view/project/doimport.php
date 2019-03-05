<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="form1">
    <div class="layui-form-item">
        <label class="layui-form-label">模板文件</label>
        <div><a href="/template/tpl2.xlsx" class="layui-btn layui-btn-danger">点击下载模板</a></div>
        <div class="layui-form-mid red">注意：Excel中不是具体人员做的任务不填参与人</div>
    </div>
    <div class="layui-upload">
        <label class="layui-form-label">选择文件</label>
        <button type="button" class="layui-btn layui-btn-normal" id="choosefile">选择文件</button>
        <a href="javascript:void(0)" class="layui-btn layui-btn-danger" id="test9">导入</a>
    </div>
    <div id="res"></div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
    layui.use(['form','upload'], function() {
        var $ = layui.jquery, form = layui.form, upload = layui.upload;

        upload.render({
            elem: '#choosefile'
            ,url: "{:url('doimport')}"
            ,auto: false
            ,accept: 'file' //普通文件
            ,data: {id: '{$Request.param.id}'}
            ,bindAction: '#test9'
            ,before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                layer.load(); //上传loading
            }
            ,done: function(res){
                if(res.code==1){
                    layer.msg('导入成功', {icon: 6});
                    var index = parent.layer.getFrameIndex(window.name);//获取窗口索引
                    parent.layer.close(index);//关闭layer
                    window.parent.location.reload();//刷新父页面
                    layer.msg('导入成功', {icon: 6});
                }else{
                    layer.alert(res.msg, function () {
                        var index = parent.layer.getFrameIndex(window.name);//获取窗口索引
                        parent.layer.close(index);//关闭layer
                    },{icon: 5});
                }

                $('#res').html(res.info);
            }
        });

    });

</script>
<script src="__ADMIN_JS__/footer.js"></script>