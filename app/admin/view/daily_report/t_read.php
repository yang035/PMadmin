<link rel="stylesheet" href="__ADMIN_JS__/pictureViewer/css/pictureViewer.css">
<style>
    .layui-upload-img {
        width: 92px;
        height: 92px;
        margin: 0 10px 10px 0;
        display: none;
    }

    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
    .layui-input,.layui-form-selected dl {
        width: 500px;
    }
    .layui-form-select .layui-edge {
        right: -200px;
    }
    span{
        line-height:35px;
        height:50px;
        font-size: 15px;
    }
</style>

<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    {volist name="data_list['arr']" id="vo"}
    <div class="layui-form-item">
        <label class="layui-form-label">任务名</label>
        <div class="layui-input-inline">
            <span>{$vo['project_name']}</span>
        </div>
        <label class="layui-form-label">完成情况</label>
        <div class="layui-input-inline">
            <span>{$vo['real_per']}%</span>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">工作内容</label>
        <div class="layui-input-inline">
            <span>{$vo['content']}</span>
        </div>
    </div>
    {/volist}
    <hr>
    <div class="layui-form-item">
        <label class="layui-form-label">明日计划</label>
        {notempty name="data_list['plan']"}
        {volist name="data_list['plan']" id="vo"}
        <div class="layui-input-block">
            <span>{$i}:{$vo}</span>
        </div>
        {/volist}
        {else/}
        <span>无</span>
        {/notempty}
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">存在问题</label>
        {notempty name="data_list['question']"}
        {volist name="data_list['question']" id="vo"}
        <div class="layui-input-block">
            <span>{$i}:{$vo}</span>
        </div>
        {/volist}
        {else/}
        <span>无</span>
        {/notempty}
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">心得</label>
        {notempty name="data_list['tips']"}
        {volist name="data_list['tips']" id="vo"}
        <div class="layui-input-block">
            <span>{$i}:{$vo}</span>
        </div>
        {/volist}
        {else/}
        <span>无</span>
        {/notempty}
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">附件说明</label>
        <div class="layui-input-inline">
            {notempty name="data_list['attachment']"}
            <div class="image-list">
            {volist name="data_list['attachment']" id="vo"}
                <div class="cover"><img src="{$vo}" style="height: 30px;width: 30px;"></div>
            {/volist}
            </div>
            {else/}
            <span>无</span>
            {/notempty}
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">汇报给</label>
        <div class="layui-input-inline">
            <span>{$data_list['send_user']}</span>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">抄送给</label>
        <div class="layui-input-inline">
            <span>{$data_list['copy_user']}</span>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script src="__ADMIN_JS__/pictureViewer/js/pictureViewer.js"></script>
<script src="__ADMIN_JS__/pictureViewer/js/jquery.mousewheel.min.js"></script>
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','upload'], function() {
        var $ = layui.jquery, laydate = layui.laydate,upload = layui.upload;
        laydate.render({
            elem: '.field-start_time',
            type: 'date'
        });
        laydate.render({
            elem: '.field-end_time',
            type: 'date',
            done: function(value, date, endDate){
                getTimeLong(value);
            },
        });
        //计算两个时间差
        function getTimeLong(value) {
            var timeLong,time1 = $('.field-start_time').val();
            var date3 = new Date(value).getTime() - new Date(time1).getTime();   //时间差的毫秒数
            //计算出相差天数
            var days=Math.floor(date3/(24*3600*1000));
            $('.field-time_long').val(days);
        }
        var uploadInst = upload.render({
            elem: '#attachment-upload'
            ,url: '/upload/'
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#attachment-upload-img').toggle();
                    $('#attachment-upload-img').attr('src', result); //图片链接（base64）
                });
            }
            ,done: function(res){
                //如果上传失败
                if(res.code > 0){
                    return layer.msg('上传失败');
                }
                //上传成功
            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#attachment-upload-text');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-mini demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst.upload();
                });
            }
        });


        $('#reset_expire').on('click', function(){
            $('input[name="expire_time"]').val(0);
        });

        $('#manager_user_id').on('click', function(){
            var open_url = "{:url('Tool/getTreeUser')}?m=manager";
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type:2,
                title :'员工列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success:function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
        });

        $('#deal_user_id').on('click', function(){
            var open_url = "{:url('Tool/getTreeUser')}?m=deal";
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type:2,
                title :'员工列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success:function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
        });

        $('#send_user_id').on('click', function(){
            var open_url = "{:url('Tool/getTreeUser')}?m=send"+'&path=1';
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type:2,
                title :'员工列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success:function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
        });

        $('#copy_user_id').on('click', function(){
            var open_url = "{:url('Tool/getTreeUser')}?m=copy";
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type:2,
                title :'员工列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success:function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
        });

        $(".field-content-add").click(function(){
            $(".field-content-add").before("<div class=\"layui-input-block\">\n" +
                "            <input type=\"text\" class=\"layui-input fl field-content\" name=\"content[]\" autocomplete=\"off\" placeholder=\"增加内容\">\n" +
                "        </div>");
        });
        $(".field-plan-add").click(function(){
            $(".field-plan-add").before("<div class=\"layui-input-block\">\n" +
                "            <input type=\"text\" class=\"layui-input fl field-plan\" name=\"plan[]\" autocomplete=\"off\" placeholder=\"增加计划\">\n" +
                "        </div>");
        });
        $(".field-question-add").click(function(){
            $(".field-question-add").before("<div class=\"layui-input-block\">\n" +
                "            <input type=\"text\" class=\"layui-input fl field-question\" name=\"question[]\" autocomplete=\"off\" placeholder=\"增加问题\">\n" +
                "        </div>");
        });
        $(".field-tips-add").click(function(){
            $(".field-tips-add").before("<div class=\"layui-input-block\">\n" +
                "            <input type=\"text\" class=\"layui-input fl field-tips\" name=\"tips[]\" autocomplete=\"off\" placeholder=\"增加心得\">\n" +
                "        </div>");
        });

        //点击预览图片
        $('.image-list').on('click', '.cover', function () {
            var this_ = $(this);
            var images = this_.parents('.image-list').find('.cover');
            var imagesArr = new Array();
            $.each(images, function (i, image) {
                imagesArr.push($(image).children('img').attr('src'));
            });
            $.pictureViewer({
                images: imagesArr, //需要查看的图片，数据类型为数组
                initImageIndex: this_.index() + 1, //初始查看第几张图片，默认1
                scrollSwitch: true //是否使用鼠标滚轮切换图片，默认false
            });
        });

        //多文件列表示例
        var demoListView = $('#demoList'),uploadListIns = upload.render({
            elem: '#testList',
            url: '{:url("admin/UploadFile/upload?thumb=no&water=no")}',
            accept: 'file',
            size:"{:config('upload.upload_file_size')}",
            multiple: true,
            auto: false,
            bindAction: '#testListAction',
            choose: function(obj){
                var files = this.files = obj.pushFile(); //将每次选择的文件追加到文件队列
                //读取本地文件
                obj.preview(function(index, file, result){
                    var tr = $(['<tr id="upload-'+ index +'">'
                        ,'<td>'+ file.name +'</td>'
                        ,'<td>'+ (file.size/1014).toFixed(1) +'kb</td>'
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
                    tds.eq(2).html('<span style="color: #5FB878;">上传成功</span>');
                    tds.eq(3).html(''); //清空操作
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
                tds.eq(2).html('<span style="color: #FF5722;">上传失败</span>');
                tds.eq(3).find('.demo-reload').removeClass('layui-hide'); //显示重传
            }
        });
    });

    function open_reply(pid,report_id,project_id) {
        var open_url = "{:url('ReportReply/add')}?id="+report_id+"&project_id="+project_id;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            maxmin: true,
            title :'回复',
            area: ['600px', '400px'],
            content: open_url,
            success:function (layero, index) {
                var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                body.contents().find(".field-pid").val(pid);
                body.contents().find(".field-report_id").val(report_id);
                body.contents().find(".field-project_id").val(project_id);
            }
        });
    }
</script>
<script src="__ADMIN_JS__/footer.js"></script>