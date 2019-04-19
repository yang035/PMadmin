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
        {notempty name="Request.param.id"}
    <div class="layui-form-item">
        <label class="layui-form-label">上级标题</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-pname" name="pname" value="{$p_res.name}" readonly lay-verify="required" autocomplete="off" placeholder="请输入名称">
        </div>
    </div>
        {/notempty}
    <div class="layui-form-item">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-name" name="name" lay-verify="required" autocomplete="off" placeholder="请输入名称">
        </div>
        <div class="layui-form-mid red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">描述</label>
        <div class="layui-input-inline">
            <textarea type="text" class="layui-textarea field-remark" name="remark" lay-verify="required" autocomplete="off" placeholder="请输入描述"></textarea>
        </div>
        <div class="layui-form-mid red">*</div>
    </div>
<!--    {empty name="Request.param.id"}-->
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">项目类别</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <select name="cat_id" class="field-cat_id" type="select" lay-filter="cat_id">-->
<!--                {$cat_id}-->
<!--            </select>-->
<!--        </div>-->
<!--    </div>-->
<!--    {else/}-->
<!--    <input type="hidden" class="layui-input field-cat_id" name="cat_id" value="{$p_res.cat_id}">-->
<!--    {/empty}-->
<!--    <div id="div_show">-->
<!--    {empty name="Request.param.id"}-->
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">建设单位</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <input type="text" class="layui-input field-development" name="development" autocomplete="off" placeholder="请输入建设单位">-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">甲方联系人</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <input type="text" class="layui-input field-contact" name="contact" autocomplete="off" placeholder="请输入甲方联系人">-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">甲方电话</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <input type="text" class="layui-input field-phone" name="phone" autocomplete="off" placeholder="请输入甲方电话">-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">项目地址</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <input type="text" class="layui-input field-address" name="address" autocomplete="off" placeholder="请输入项目地址">-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">项目面积</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <input type="text" class="layui-input field-area" name="area" autocomplete="off" placeholder="请输入项目面积">-->
<!--        </div>-->
<!--    </div>-->
<!---->
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">项目来源</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <select name="p_source" class="field-p_source" type="select" lay-filter="p_source">-->
<!--                {$p_source}-->
<!--            </select>-->
<!--        </div>-->
<!--    </div>-->
<!--    <hr>-->
<!--    {/empty}-->
<!--    </div>-->
    <div class="layui-form-item">
        <label class="layui-form-label">预设产量</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-score" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="score" lay-verify="required" autocomplete="off" placeholder="请输入预设值">
        </div>
        {notempty name="Request.param.id"}
        <div class="layui-form-mid">不能超过<span id="max_score" style="color: red;">{$p_res.max_score}</span>斗</div>
        {/notempty}
        <div class="layui-form-mid red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">开始时间</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-start_time" name="start_time" lay-verify="required" readonly autocomplete="off" placeholder="选择开始时间">
        </div>
        <div class="layui-form-mid red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">结束时间</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-end_time" name="end_time" lay-verify="required" readonly autocomplete="off" placeholder="选择结束时间">
        </div>
        <div class="layui-form-mid red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">历时</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-time_long" name="time_long" readonly autocomplete="off">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">紧急程度</label>
        <div class="layui-input-inline">
            <select name="grade" class="field-grade" type="select" lay-filter="grade">
                {$grade_type}
            </select>
        </div>
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
        <label class="layui-form-label">负责人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="manager_user_id">选择负责人</button>(此任务由谁负责)
            <div id="manager_select_id">{$data_info['manager_user_id']|default=''}</div>
            <input type="hidden" name="manager_user" id="manager_user" value="{$data_info['manager_user']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">参与人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="deal_user_id">选择参与人</button>(此任务具体哪些人做)
            <div id="deal_select_id"></div>
            <input type="hidden" name="deal_user" id="deal_user" value="">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">审批人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="send_user_id">选择审批人</button>(此任务需要谁来审批)
            <div id="send_select_id">{$data_info['send_user_id']|default=''}</div>
            <input type="hidden" name="send_user" id="send_user" value="{$data_info['send_user']|default=''}">
        </div>
        <div class="layui-form-mid red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">抄送人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="copy_user_id">选择抄送人</button>(此任务需要抄送给谁)
            <div id="copy_select_id">{$data_info['copy_user_id']|default=''}</div>
            <input type="hidden" name="copy_user" id="copy_user" value="{$data_info['copy_user']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id" value="{$p_res.id|default='0'}">
            <input type="hidden" class="field-pid" name="pid" value="{$p_res.pid|default='0'}">
            <input type="hidden" class="field-code" name="code" value="{$p_res.code|default=''}">
            <input type="hidden" class="field-cat_id" name="cat_id" value="{$Request.param.atype|default=''}">
            {notempty name="Request.param.id"}
            <input type="hidden" class="field-max_score" name="max_score" value="{$p_res.max_score}">
            {/notempty}
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="javascript:history.back();" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
var formData = {:json_encode($data_info)};

layui.use(['jquery', 'laydate','upload','form'], function() {
    var $ = layui.jquery, laydate = layui.laydate,upload = layui.upload,form = layui.form;

    form.on('select(cat_id)', function(data){
        if(5 == data.value){
            $('#div_show').hide();
        }else {
            $('#div_show').show();
        };
    });

    laydate.render({
        elem: '.field-start_time',
        type: 'datetime',
        trigger: 'click',
        value: getSatrtDate(),
        change: function(value){
            // $(".laydate-btns-time").click();
        },
        done: function (value, date, endDate) {
            // $("input[name='end_time']").val(value);
        }
    });

    laydate.render({
        elem: '.field-end_time',
        type: 'datetime',
        trigger: 'click',
        value: getEndDate(),
        // min: $("input[name='end_time']").val(),
        change: function(value){
            // $(".laydate-btns-time").click();
        },
        done: function(value, date, endDate){
            getTimeLong(value);
        },
    });
    // function getNowDate() {
    //     var time = new Date().getTime();
    //     return new Date(time).Format('yyyy-MM-dd') + ' 08:30:00';
    // }
    // function getNextDate() {
    //     var time = new Date().getTime();
    //     return new Date(time).Format('yyyy-MM-dd') + ' 18:00:00';
    // }
    //写入时长
    getTimeLong(getEndDate());
    //计算两个时间差
    function getTimeLong(value) {
        var timeLong,time1 = $('.field-start_time').val();
        var date3 = new Date(value).getTime() - new Date(time1).getTime();   //时间差的毫秒数
        if (date3 <= 0 ){
            layer.alert('结束时间不能小于开始时间');
        }
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
</script>
<script src="__ADMIN_JS__/footer.js"></script>