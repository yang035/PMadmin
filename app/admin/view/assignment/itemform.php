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
</style>
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <label class="layui-form-label">选择项目</label>
        <div class="layui-input-inline">
            <select name="project_id" class="field-project_id" type="select" lay-filter="project_type" lay-search="">
                {$mytask}
            </select>
        </div>
    </div>
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">类型</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <select name="cat_id" class="field-cat_id" type="select">-->
<!--                {$cat_option}-->
<!--            </select>-->
<!--        </div>-->
<!--    </div>-->
    <div class="layui-form-item">
        <label class="layui-form-label">任务</label>
        <div class="layui-input-block" style="width: 536px">
            <input type="text" class="layui-input field-content" name="content[]" autocomplete="off" placeholder="描述">
        </div>
        <div class="layui-input-inline" style="width: 100px;margin-left: 109px">
            <input type="number" class="layui-input field-ml" name="ml[]" autocomplete="off" placeholder="ML">
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <input type="number" class="layui-input field-gl" name="gl[]" autocomplete="off" placeholder="GL">
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <select name="time_type[]" class="field-time_type" type="select">
                {$time_type}
            </select>
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <input type="text" class="layui-input field-start_time" name="start_time[]" autocomplete="off" readonly placeholder="开始时间">
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <input type="text" class="layui-input field-end_time" name="end_time[]" autocomplete="off" readonly placeholder="结束时间">
        </div>
    </div>
    <div class="new_task">
        <a href="javascript:void(0);" class="aicon ai-tianjia field-guige-add" style="float: left;margin-left:650px;font-size: 30px;"></a>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">附件说明</label>
        <div class="layui-input-inline" style="width: 500px">
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
        <label class="layui-form-label">发送给</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="send_user_id">选择发送人</button>
            <div id="send_select_id">{$data_info['assignment_user_id']|default=''}</div>
            <input type="hidden" name="send_user" id="send_user" value="{$data_info['assignment_user']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">执行人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="deal_user_id">选择执行人</button>
            <div id="deal_select_id"></div>
            <input type="hidden" name="deal_user" id="deal_user" value="">
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
<script src="__PUBLIC_JS__/jquery.select.js?v="></script>
<script src="__PUBLIC_JS__/SelectBox.min.js?v="></script>
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','upload','element','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate,upload = layui.upload,element = layui.element,form = layui.form;
        laydate.render({
            elem: '.field-start_time',
            type: 'date',
            calendar: true,
            trigger: 'click',
        });
        laydate.render({
            elem: '.field-start_time1',
            type: 'time',
            // format: 'HH',
            trigger: 'click',
            value: getStartTime(),
        });
        laydate.render({
            elem: '.field-end_time',
            type: 'date',
            calendar: true,
            trigger: 'click',
        });
        laydate.render({
            elem: '.field-end_time1',
            type: 'time',
            // format: 'HH',
            trigger: 'click',
            value: getEndTime(),
        });
        $('.field-reason').focus(function () {
            var time1 = $('.field-start_time').val()+' '+$('.field-start_time1').val();
            var time2 = $('.field-end_time').val()+' '+$('.field-end_time1').val();
            getTimeLong(time1,time2);
        });
        //计算两个时间差
        function getTimeLong(value1,value2) {
            var timeLong;
            var date3 = new Date(value2).getTime() - new Date(value1).getTime();   //时间差的毫秒数
            //计算出相差天数
            var days=Math.floor(date3/(24*3600*1000));
            //计算出小时数
            var leave1=date3%(24*3600*1000);   //计算天数后剩余的毫秒数
            var hours=Math.floor(leave1/(3600*1000));
            //计算相差分钟数
            var leave2=leave1%(3600*1000);      //计算小时数后剩余的毫秒数
            var minutes=Math.floor(leave2/(60*1000));
            //计算相差秒数
            var leave3=leave2%(60*1000);      //计算分钟数后剩余的毫秒数
            var seconds=Math.round(leave3/1000);
            timeLong = days+"天"+hours+"小时"+minutes+"分钟"+seconds+"秒";
            $('.field-time_long').val(timeLong);
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

        $('#send_user_id').on('click', function(){
            var send_user = $('#send_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=send&u="+send_user+'&path=1';
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
            var deal_user = $('#deal_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=deal&u="+deal_user;
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
            var copy_user = $('#copy_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=copy&u="+copy_user;
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

        $(".field-guige-add").click(function(){
            $(".new_task").before("<div class=\"layui-form-item\">\n" +
                "        <label class=\"layui-form-label\">任务</label>\n" +
                "        <div class=\"layui-input-block\" style=\"width: 536px\">\n" +
                "            <input type=\"text\" class=\"layui-input field-content\" name=\"content[]\" autocomplete=\"off\" placeholder=\"描述\">\n" +
                "        </div>\n" +
                "        <div class=\"layui-input-inline\" style=\"width: 100px;margin-left: 109px\">\n" +
                "            <input type=\"number\" class=\"layui-input field-ml\" name=\"ml[]\" autocomplete=\"off\" placeholder=\"ML\">\n" +
                "        </div>\n" +
                "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
                "            <input type=\"number\" class=\"layui-input field-gl\" name=\"gl[]\" autocomplete=\"off\" placeholder=\"GL\">\n" +
                "        </div>\n" +
                "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
                "            <select name=\"unit[]\" class=\"field-unit\" type=\"select\">\n" +
                "                {$time_type}\n" +
                "            </select>\n" +
                "        </div>\n" +
                "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
                "            <input type=\"text\" class=\"layui-input field-start_time\" name=\"start_time[]\" autocomplete=\"off\" readonly placeholder=\"开始时间\">\n" +
                "        </div>\n" +
                "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
                "            <input type=\"text\" class=\"layui-input field-end_time\" name=\"end_time[]\" autocomplete=\"off\" readonly placeholder=\"结束时间\">\n" +
                "        </div>\n" +
                "    </div>");
            form.render();
        });

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
            url: '{:url("admin/UploadFile/upload?thumb=no&water=no")}',
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
        form.render();
    });

    function select_union(id){
        $.ajax({
            type: 'POST',
            url: "{:url('getFlowUser')}",
            data: {id:id},
            dataType:  'json',
            success: function(data){
                $('#send_user').val(data.manager_user);
            }
        });
    }

    function check_name(){
        var name = $('.field-shigong_select_id').val();
        $.ajax({
            type: 'POST',
            url: "{:url('checkName')}",
            data: {name:name},
            dataType:  'json',
            success: function(data){
                if (data) {
                    $('#shigong_user').val(data);
                }else {
                    layer.alert('施工员不存在');
                }
            }
        });
    }

    function amout_sum() {
        var total = 0;
        $("input[name^='num']").each(function (i, el) {
            var num = parseFloat($(this).val());
            if (isNaN(num)){
                num = 0;
            }
            $("input[name^='per_price']").each(function (n, e) {
                var per_price = parseFloat($(this).val());
                if (isNaN(per_price)){
                    per_price = 0;
                }
                if (i == n){
                    total += num * per_price;
                }
            });
        });
        total = total.toFixed(2);
        $('.field-money').val(total);
    }


</script>
<script src="__ADMIN_JS__/footer.js"></script>