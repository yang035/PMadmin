<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <label class="layui-form-label">分类</label>
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
        <label class="layui-form-label">内容</label>
        <div class="layui-input-block">
            <textarea id="ckeditor" name="content" class="field-content"></textarea>
        </div>
    </div>
    {:editor(['ckeditor', 'ckeditor2'],'kindeditor')}
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
            <input type="text" class="layui-input field-tags" name="tags" autocomplete="off" placeholder="标签用英文','隔开">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">缩略图</label>
        <div class="layui-input-inline upload">
            <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="oneImage">请上传首页缩略图</button>
            <input type="hidden" class="upload-input field-thumb" name="thumb" value="">
            <img id="thumb" src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">附件说明</label>
        <div class="layui-input-block">
            <!--            <div class="layui-upload">-->
            <!--                <button type="button" class="layui-btn" id="attachment-upload">选择附件</button>-->
            <!--                <div class="layui-upload-list">-->
            <!--                    <img class="layui-upload-file" id="attachment-upload-file">-->
            <!--                    <p id="attachment-upload-text"></p>-->
            <!--                </div>-->
            <!--            </div>-->
            <div class="layui-upload">
                <button type="button" class="layui-btn layui-btn-normal" id="testList">选择多文件</button>
                <div class="other-div" style="display: none">
                    <div class="layui-upload-list">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>文件名</th>
                                <th>大小</th>
                                <th>上传进度</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody id="demoList"></tbody>
                        </table>
                    </div>
                    <button type="button" class="layui-btn layui-btn-danger" id="testListAction">开始上传</button>
                    <input class="layui-input field-attachment" type="hidden" name="attachment" value="">
                </div>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">推&nbsp;&nbsp;&nbsp;&nbsp;荐</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-is_push" name="is_push" value="1" title="是" lay-filter="is_push">
            <input type="radio" class="field-is_push" name="is_push" value="0" title="否" checked lay-filter="is_push">
        </div>
    </div>
    <div class="layui-form-item" style="display: none" id="tuijian_div">
        <label class="layui-form-label">推荐位图片</label>
        <div class="layui-input-inline upload">
            <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="tuijianImage">请上传首页缩略图</button>
            <input type="hidden" class="upload-input field-tuijian" name="tuijian" value="">
            <img src="" id="tuijian" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
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

layui.use(['jquery', 'laydate','element', 'upload','form'], function() {
    var $ = layui.jquery, laydate = layui.laydate,element = layui.element, layer = layui.layer, upload = layui.upload, form = layui.form;
    var uploadOneIns = upload.render({
        elem: '#oneImage',
        url: '{:url("admin/UploadFile/upload?group=front")}',
        method: 'post',
        size:120,
        before: function(input) {
            layer.msg('文件上传中...', {time:3000000});
        },
        done: function(res, index, upload) {
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
    $('#thumb').attr('src', $('.field-thumb').val()).show();

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
    var uploadOneIns = upload.render({
        elem: '#tuijianImage',
        url: '{:url("admin/UploadFile/upload?group=front")}',
        method: 'post',
        size:120,
        before: function(input) {
            layer.msg('文件上传中...', {time:3000000});
        },
        done: function(res, index, upload) {
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
    $('#tuijian').attr('src', $('.field-tuijian').val()).show();

    //创建监听函数
    var xhrOnProgress=function(fun) {
        xhrOnProgress.onprogress = fun; //绑定监听
        //使用闭包实现监听绑
        return function() {
            //通过$.ajaxSettings.xhr();获得XMLHttpRequest对象
            var xhr = $.ajaxSettings.xhr();
            //判断监听函数是否为函数
            if (typeof xhrOnProgress.onprogress !== 'function')
                return xhr;
            //如果有监听函数并且xhr对象支持绑定时就把监听函数绑定上去
            if (xhrOnProgress.onprogress && xhr.upload) {
                xhr.upload.onprogress = xhrOnProgress.onprogress;
            }
            return xhr;
        }
    };

    //多文件列表示例
    var demoListView = $('#demoList'),uploadListIns = upload.render({
        elem: '#testList',
        url: '{:url("admin/UploadFile/upload?group=front")}',
        accept: 'file',
        size:"{:config('upload.upload_file_size')}",
        multiple: true,
        auto: false,
        bindAction: '#testListAction',
        xhr:xhrOnProgress,
        progress:function(value,obj){
            $("#demoList").find('.layui-progress ').each(function () {
                if ($(this).attr("file") == obj.name) {
                    var progressBarName = $(this).attr("lay-filter");
                    var percent = Math.floor((value.loaded / value.total) * 100);//计算百分比
                    element.progress(progressBarName, percent + '%');//设置页面进度条
                }
            })},
        choose: function(obj){
            var files = this.files = obj.pushFile(); //将每次选择的文件追加到文件队列
            var count = 0;
            //读取本地文件
            obj.preview(function(index, file, result){
                count++;
                var tr = $(['<tr id="upload-'+ index +'">'
                    ,'<td>'+ file.name +'</td>'
                    ,'<td>'+ (file.size/1014).toFixed(1) +'kb</td>'
                    ,'<td>'
                    +'<div  file="'+file.name+'" class="layui-progress layui-progress-big" lay-showpercent="true"   lay-filter="progressBar'+count+'">'
                    +'<div  class="layui-progress-bar layui-bg-red" lay-percent="0%"></div>'
                    +'</div>'
                    , '</td>'
                    ,'<td>等待上传</td>'
                    ,'<td>'
                    ,'<button class="layui-btn layui-btn-xs demo-reload layui-hide">重传</button>'
                    ,'<button class="layui-btn layui-btn-xs layui-btn-danger demo-delete">删除</button>'
                    ,'</td>'
                    ,'</tr>'].join(''));

                //单个重传
                tr.find('.demo-reload').on('click', function(){
                    obj.upload(index, file);
                });

                //删除
                tr.find('.demo-delete').on('click', function(){
                    delete files[index]; //删除对应的文件
                    tr.remove();
                    uploadListIns.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
                });

                demoListView.append(tr);
            });
            $('.other-div').show();
        }
        ,done: function(res, index, upload){
            if(res.code == 1){ //上传成功
                var tr = demoListView.find('tr#upload-'+ index)
                    ,tds = tr.children();
                tds.eq(3).html('<span style="color: #5FB878;">上传成功</span>');
                tds.eq(4).html(''); //清空操作
                var new_value = $('.field-attachment').val();
                new_value += res.data.file+',';
                $('.field-attachment').val(new_value);
                return delete this.files[index]; //删除文件队列已经上传成功的文件
            }
            this.error(index, upload);
        }
        ,error: function(index, upload){
            var tr = demoListView.find('tr#upload-'+ index)
                ,tds = tr.children();
            tds.eq(3).html('<span style="color: #FF5722;">上传失败</span>');
            tds.eq(4).find('.demo-reload').removeClass('layui-hide'); //显示重传
        }
    });
    // 日期渲染
    laydate.render({elem: '.layui-date'});
    form.render();
});
</script>
<script src="__ADMIN_JS__/footer.js"></script>