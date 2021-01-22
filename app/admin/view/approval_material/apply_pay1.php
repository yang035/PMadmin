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
    span{
        line-height:35px;
        height:50px;
        margin-left: 20px;
        font-size: 15px;
    }
</style>
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <blockquote class="layui-elem-quote" style="color: grey">
                {if condition="($Request.param.ct eq 22) && $list1" /}
                    申请时间：{$list1['create_time']|date='Y-m-d H:i:s',###}<br>
                    姓名：{$list1['real_name']}<br>
                    开始时间：{$list1['start_time']}<br>
                    结束时间：{$list1['end_time']}<br>
                    项目名称：{$list1['project_name']}<br>
                    送货日期：{$list1['date']}<br>
                    清单明细：<br>
                    {volist name="$list1['detail']" id="vo"}
                    说明：{$vo['content']}&nbsp;&nbsp;|&nbsp;&nbsp;计量：{$vo['num']}{$vo['unit']}&nbsp;&nbsp;|&nbsp;&nbsp;单价：{$vo['per_price']}元（合计：{$vo['num']*$vo['per_price']}元）<br>
                    {/volist}
                    <br>
                    总计：{$list1['money']}元<br>
                    施工员：{$list1['shigong_user']}<br>
                    事由：{$list1['reason']}<br>
                    附件说明：
                    {notempty name="list1['attachment'][0]"}
                    <!--            <div class="image-list">-->
                    <ul>
                        {volist name="list1['attachment']" id="vo"}
                        <!--                <div class="cover"><img src="{$vo}" style="height: 30px;width: 30px;"></div>-->
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="{$vo}" style="color: #5c90d2">附件{$i}</a></li>
                        {/volist}
                    </ul>
                    <!--            </div>-->
                    {else/}
                    <span>无</span>
                    {/notempty}
                    <br>
                    审批人：{$list1['send_user']}<br>
                    抄送人：{$list1['copy_user']}<br>
                    结果：{$approval_status[$list1['status']]}<br>
                    备注：{$list1['mark']}<br>
                    批示时间：{$list1['update_time']|date='Y-m-d H:i:s',###}<br>
                {/if}
            </blockquote>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">支付金额</label>
        <div class="layui-input-inline" style="width: 200px;">
            <input type="text" class="layui-input field-total" name="total" value="{$list1['money']}" readonly>
        </div>
        <div class="layui-form-mid">元</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">支付比例</label>
        <div class="layui-input-inline" style="width: 200px;">
            <input type="text" class="layui-input field-per" name="per" value="100">
        </div>
        <div class="layui-form-mid">% *</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">备注</label>
        <div class="layui-input-inline">
            <textarea type="text" class="layui-textarea field-reason" name="reason" autocomplete="off" placeholder="请输入备注"></textarea>
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
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
        <label class="layui-form-label">审批人</label>
        <div class="layui-input-inline">
<!--            <button type="button" class="layui-btn" id="send_user_id">选择汇报人</button>-->
            <div style="margin-top: 10px" id="send_select_id">默认流程(负责人级、部门级、总经理级)</div>
            <input type="hidden" name="send_user" id="send_user" value="" lay-verify="required">
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">抄送人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn layui-btn-normal" id="copy_user_id">选择抄送人</button>
            <div id="copy_select_id">{$data_info['finance_user_id']|default=''}</div>
            <input type="hidden" name="copy_user" id="copy_user" value="{$data_info['finance_user']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
            <input type="hidden" class="field-id" name="ct" value="{$Request.param.ct}">
            <input type="hidden" class="field-class_type" name="class_type" value="{$Request.param.class_type}">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script src="__ADMIN_JS__/ChinaNumToEnglishNum.js"></script>
<script src="__PUBLIC_JS__/jquery.select.js?v="></script>
<script src="__PUBLIC_JS__/SelectBox.min.js?v="></script>
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','element','upload','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate,element = layui.element,upload = layui.upload,form = layui.form;
        laydate.render({
            elem: '.field-start_time',
            type: 'date',
            calendar: true,
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
            value: getStartTime(),
        });
        laydate.render({
            elem: '.field-end_time',
            type: 'date',
            calendar: true,
            trigger: 'click',
            value: new Date(),
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

    function amout_sum() {
        var total = 0;
        $("input[name^='amount']").each(function (i, el) {
            var amout = parseFloat($(this).val());
            if (isNaN(amout)){
                amout = 0;
            }
            total += amout;
        });
        $('.field-total').val(total.toFixed(2));
    }

    select_union({$list1['project_id']});

    new SelectBox($('.box1'),{$project_select},function(result){
        if ('' != result.id){
            $('#project_name').val(result.name);
            $('#project_id').val(result.id);
            select_union(result.id)
        }
    },{
        dataName:'name',//option的html
        dataId:'id',//option的value
        fontSize:'14',//字体大小
        optionFontSize:'14',//下拉框字体大小
        textIndent:4,//字体缩进
        color:'#000',//输入框字体颜色
        optionColor:'#000',//下拉框字体颜色
        arrowColor:'#D2D2D2',//箭头颜色
        backgroundColor:'#fff',//背景色颜色
        borderColor:'#D2D2D2',//边线颜色
        hoverColor:'#009688',//下拉框HOVER颜色
        borderWidth:1,//边线宽度
        arrowBorderWidth:0,//箭头左侧分割线宽度。如果为0则不显示
        // borderRadius:5,//边线圆角
        placeholder:'输入关键字搜索',//默认提示
        defalut:'{$Request.param.project_name}',//默认显示内容。如果是'firstData',则默认显示第一个
        // allowInput:true,//是否允许输入
        width:300,//宽
        height:37,//高
        optionMaxHeight:300//下拉框最大高度
    });

    function select_union(id){
        $.ajax({
            type: 'POST',
            url: "{:url('getFlowUser')}",
            data: {id:id},
            dataType:  'json',
            success: function(data){
                // $("#c_id").html("");
                // $.each(data, function(key, val) {
                //     var option1 = $("<option>").val(val.areaId).text(val.fullname);
                $('#send_select_id').html(data.manager_user_id);
                $('#send_user').val(data.manager_user);
                // form.render('select');
                // });
                // $("#c_id").get(0).selectedIndex=0;
            }
        });
    }
</script>
<script src="__ADMIN_JS__/footer.js"></script>