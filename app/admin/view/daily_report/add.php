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
    .layui-input,.layui-input-block{
        width: 519px;
    }
    .layui-form-select  {

    }
    .layui-form-item{
        margin-bottom: 5px;
    }
    .new_task{
        margin-left: 630px;
    }
    .layui-form-mid1 {
        float: left;
        display: block;
        padding: 9px 0!important;
        line-height: 20px;
        margin-right: 10px;
        font-size: 15px;
        color: grey;
    }
</style>
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <label class="layui-form-label">昨日计划</label>
        {notempty name="row"}
        <div class="layui-form-mid1">
            ({$row['create_time']})
            {volist name="row['plan']" id="vo"}
            {$i}.{$vo};
            {/volist}
        </div>
        {else/}
        <div class="layui-form-mid">无</div>
        {/notempty}

    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">任务名</label>
        <div class="layui-input-inline">
            <select name="project_id[]" class="layui-input field-project_id" type="select">
                {$mytask}
            </select>
        </div>
        <label class="layui-form-label">完成百分比</label>
        <div class="layui-input-inline" style="width: 100px">
            <input type="number" class="layui-input field-real_per" style="width: 100px" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="real_per[]" autocomplete="off" placeholder="请输入整数">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <textarea type="text" class="layui-textarea field-content" name="content[]" autocomplete="off" placeholder="工作内容"></textarea>
        </div>
    </div>
    <div class="new_task">
        <a href="javascript:void(0);" class="aicon ai-tianjia field-task-add" style="float: left;font-size: 30px;"></a>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">明日计划</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input field-plan" name="plan[]" autocomplete="off" placeholder="计划1">
        </div>
        <div class="layui-input-block">
            <input type="text" class="layui-input field-plan" name="plan[]" autocomplete="off" placeholder="计划2">
        </div>
        <div class="layui-input-block">
            <input type="text" class="layui-input fl field-plan" name="plan[]" autocomplete="off" placeholder="计划3">
        </div>
        <a href="javascript:void(0);" class="aicon ai-tianjia field-plan-add" style="float: left;font-size: 30px;"></a>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">存在问题</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input field-question" name="question[]" autocomplete="off" placeholder="问题1">
        </div>
        <div class="layui-input-block">
            <input type="text" class="layui-input field-question" name="question[]" autocomplete="off" placeholder="问题2">
        </div>
        <div class="layui-input-block">
            <input type="text" class="layui-input fl field-question" name="question[]" autocomplete="off" placeholder="问题3">
        </div>
        <a href="javascript:void(0);" class="aicon ai-tianjia field-question-add" style="float: left;font-size: 30px;"></a>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">心得</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input field-tips" name="tips[]" autocomplete="off" placeholder="心得1">
        </div>
        <div class="layui-input-block">
            <input type="text" class="layui-input field-tips" name="tips[]" autocomplete="off" placeholder="心得2">
        </div>
        <div class="layui-input-block">
            <input type="text" class="layui-input fl field-tips" name="tips[]" autocomplete="off" placeholder="心得3">
        </div>
        <a href="javascript:void(0);" class="aicon ai-tianjia field-tips-add" style="float: left;font-size: 30px;"></a>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">附件说明</label>
        <div class="layui-input-inline">
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
        <label class="layui-form-label">汇报给</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="send_user_id">选择汇报人</button>
            <div id="send_select_id"></div>
            <input type="hidden" name="send_user" id="send_user" value="">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">抄送给</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="copy_user_id">选择抄送人</button>
            <div id="copy_select_id"></div>
            <input type="hidden" name="copy_user" id="copy_user" value="">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','upload','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate,upload = layui.upload,form = layui.form;
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
            var manager_user = $('#manager_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=manager&u="+manager_user;
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

        $('#send_user_id').on('click', function(){
            var send_user = $('#send_user').val();
            var open_url = "{:url('Tool/getTreeUser')}?m=send&u="+send_user;
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

        $(".field-task-add").click(function(){
            $(".new_task").before("<div class=\"layui-form-item\">\n" +
                "        <label class=\"layui-form-label\">任务名</label>\n" +
                "        <div class=\"layui-input-inline\">\n" +
                "            <select name=\"project_id[]\" class=\"layui-input field-project_id\" type=\"select\">\n" +
                "                {$mytask}\n" +
                "            </select>\n" +
                "        </div>\n" +
                "        <label class=\"layui-form-label\">完成百分比</label>\n" +
                "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
                "            <input type=\"number\" class=\"layui-input field-real_per\" style=\"width: 100px\" onkeypress=\"return (/[\\d]/.test(String.fromCharCode(event.keyCode)))\" name=\"real_per[]\" autocomplete=\"off\" placeholder=\"请输入整数\">\n" +
                "        </div>\n" +
                "    </div>\n" +
                "    <div class=\"layui-form-item\">\n" +
                "        <div class=\"layui-input-block\">\n" +
                "            <textarea type=\"text\" class=\"layui-textarea field-content\" name=\"content[]\" autocomplete=\"off\" placeholder=\"工作内容\"></textarea>\n" +
                "        </div>\n" +
                "    </div>");
            form.render();
        });
        $('.field-real_per').keyup(function () {
            var num = $('.field-real_per').val();
            if (num > 100){
                layer.msg('百分比不能超过100');
            }
        })
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
        form.render();
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>