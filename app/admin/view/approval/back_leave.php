<style>
    .layui-upload-img {
        width: 92px;
        height: 92px;
        margin: 0 10px 10px 0;
        display: none;
    }
</style>
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <label class="layui-form-label">请假时间</label>
        <div class="layui-input-block">
            {$leave_option}
        </div>
    </div>
    <div class="layui-form-item hide">
        <label class="layui-form-label">开始时间</label>
        <div class="layui-input-inline" style="width: 250px">
            <input type="text" class="layui-input field-start_time" name="start_time" lay-verify="required" autocomplete="off" readonly placeholder="选择开始时间">
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <input type="text" class="layui-input field-start_time1" name="start_time1" autocomplete="off" readonly placeholder="选择开始时间">
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
        <div id="alert" class="layui-form-mid" style="color: red">年假需要提前一周申请</div>
    </div>
    <div class="layui-form-item hide">
        <label class="layui-form-label">结束时间</label>
        <div class="layui-input-inline" style="width: 250px">
            <input type="text" class="layui-input field-end_time" name="end_time" lay-verify="required" autocomplete="off" readonly placeholder="选择结束时间">
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <input type="text" class="layui-input field-end_time1" name="end_time1" autocomplete="off" readonly placeholder="选择开始时间">
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item hide">
        <label class="layui-form-label">请假时长</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-time_long" readonly name="time_long" autocomplete="off">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">销假说明</label>
        <div class="layui-input-inline">
            <textarea type="text" class="layui-textarea field-reason" name="reason" autocomplete="off" placeholder="请输入请假事由" lay-verify="required"></textarea>
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">附件说明</label>
        <div class="layui-input-inline" style="width: 500px">
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
        <label class="layui-form-label">审批人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn layui-btn-normal" id="send_user_id">选择汇报人</button>
            <div id="send_select_id"></div>
            <input type="hidden" name="send_user" id="send_user" value="" lay-verify="required">
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">抄送人</label>
        <div class="layui-input-inline">
<!--            <button type="button" class="layui-btn" id="copy_user_id">选择抄送人</button>-->
            <div id="copy_select_id">{$data_info['hr_finance_user_id']|default=''}</div>
            <input type="hidden" name="copy_user" id="copy_user" value="{$data_info['hr_finance_user']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <input type="hidden" class="field-class_type" name="class_type" value="{$Request.param.class_type}">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
var formData = {:json_encode($data_info)};

layui.use(['jquery', 'laydate','upload','element','form'], function() {
    var $ = layui.jquery, laydate = layui.laydate,upload = layui.upload,element = layui.element,form = layui.form;

    form.on('select(leave_type)', function(data){
        if(1 == data.value){
            $('#alert').html('年假需要提前一周申请');
        }else {
            $('#alert').html('正常都需要提前一天申请');
        };
    });

    laydate.render({
        elem: '.field-start_time',
        type: 'date',
        calendar: true,
        min: 0,
        trigger: 'click',
        value: new Date(),
        showBottom: false,
        done: function (value, date, endDate) {
            $("input[name='end_time']").val(value);
        }
    });
    laydate.render({
        elem: '.field-start_time1',
        type: 'time',
        // format: 'HH',
        trigger: 'click',
        min: '08:00:00',
        value: getStartTime(),
    });
    laydate.render({
        elem: '.field-end_time',
        type: 'date',
        calendar: true,
        trigger: 'click',
        value: new Date(),
        min: 0,
    });
    laydate.render({
        elem: '.field-end_time1',
        type: 'time',
        // format: 'HH',
        trigger: 'click',
        min: '08:00:00',
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
</script>
<script src="__ADMIN_JS__/footer.js"></script>