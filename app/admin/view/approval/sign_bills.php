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
                <select name="project_id" id='project_id' class="field-project_id" type="select" lay-filter="project_type" lay-search="">
                    {$mytask}
                </select>
            </div>
        </div>
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">选择项目</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <div class="layui-input-inline box box1">-->
<!--            </div>-->
<!--            <input id="project_name" type="hidden" name="project_name" value="{$Request.param.project_name}">-->
<!--            <input id="project_id" type="hidden" name="project_id" value="{$Request.param.project_id}">-->
<!--        </div>-->
<!--        <div class="layui-form-mid red">*</div>-->
<!--    </div>-->
    <div class="layui-form-item hide">
        <label class="layui-form-label">开始时间</label>
        <div class="layui-input-inline" style="width: 250px">
            <input type="text" class="layui-input field-start_time" name="start_time" lay-verify="required" autocomplete="off" readonly placeholder="选择开始时间">
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <input type="text" class="layui-input field-start_time1" name="start_time1" autocomplete="off" readonly placeholder="选择开始时间">
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
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
        <label class="layui-form-label">历时</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-time_long" readonly name="time_long" autocomplete="off">
        </div>
    </div>
    <div class="layui-form-item hide">
        <label class="layui-form-label">申请事由</label>
        <div class="layui-input-inline">
            <textarea type="text" class="layui-textarea field-reason" name="reason" autocomplete="off" placeholder="请输入申请事由"></textarea>
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">日期</label>
        <div class="layui-input-inline" style="width: 250px">
            <input type="text" class="layui-input field-date" name="date" autocomplete="off" readonly placeholder="日期">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">内容</label>
        <div class="layui-input-inline" style="width: 200px">
<!--            <input type="text" class="layui-input field-content" name="content[]" autocomplete="off" placeholder="描述">-->
                <div class="layui-form-select layui-form-selected searchDiv1">
                    <div class="layui-select-title"><input type="text" placeholder="选择或输入" name="content[]" class="layui-input search_input1" id="search_input1"></div>
                    <dl class="layui-anim layui-anim-upbit" style="display: none;">
                    </dl>
                </div>
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <input type="number" class="layui-input field-num" name="num[]" onblur="amout_sum()" autocomplete="off" placeholder="计量">
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <select name="unit[]" class="field-unit" type="select">
                {$unit_option}
            </select>
        </div>
        <div class="layui-input-inline" style="width: 100px">
            <input type="number" class="layui-input field-per_price" name="per_price[]" onblur="amout_sum()" id="per_price1" autocomplete="off" placeholder="单价">
        </div>
        <div class="layui-form-mid" style="color: red">元</div>
    </div>
    <div class="new_task">
        <a href="javascript:void(0);" class="aicon ai-tianjia field-guige-add" style="float: left;margin-left:650px;font-size: 30px;"></a>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">合计</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-money" name="money" autocomplete="off" placeholder="0">
        </div>
        <div class="layui-form-mid">元</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">附件说明</label>
        <div class="layui-input-inline" style="width: 500px">
            <div class="layui-upload">
                <button type="button" class="layui-btn layui-btn-normal" id="testList">选择多文件</button>(工作量清单、现场照片等)
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
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">施工员</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <input type="text" class="layui-input field-shigong_select_id" name="shigong_select_id" onblur="check_name()" autocomplete="off" placeholder="姓名">-->
<!--            <input type="hidden" name="shigong_user" id="shigong_user" value="" lay-verify="required">-->
<!--        </div>-->
<!--        <div class="layui-form-mid" style="color: red">*</div>-->
<!--    </div>-->
    <div class="layui-form-item">
        <label class="layui-form-label">审批人</label>
        <div class="layui-input-inline">
            <!--            <button type="button" class="layui-btn" id="send_user_id">选择审批人</button>-->
            <div style="margin-top: 10px" id="send_select_id">默认流程(负责人级、部门级、总经理级)</div>
            <input type="hidden" name="send_user" id="send_user" value="" lay-verify="required">
        </div>
        <div class="layui-form-mid" style="color: red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">抄送人</label>
        <div class="layui-input-inline">
<!--            <button type="button" class="layui-btn" id="copy_user_id">选择抄送人</button>-->
            <div style="margin-top: 10px" id="copy_select_id">{$data_info['finance_user_id']|default=''}</div>
            <input type="hidden" name="copy_user" id="copy_user" value="{$data_info['finance_user']|default=''}">
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
<script src="__PUBLIC_JS__/jquery.select.js?v="></script>
<script src="__PUBLIC_JS__/SelectBox.min.js?v="></script>
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','upload','element','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate,upload = layui.upload,element = layui.element,form = layui.form;
        laydate.render({
            elem: '.field-date',
            type: 'date',
            calendar: true,
            trigger: 'click',
            value: new Date(),
            min:0,
            max:0,
        });
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

        $(".search_input1").keyup(function(event){
            $(".searchDiv1").find("dl.layui-anim").show();
            if(''!=$(this).val().trim()){ //如果输入框的值没有改变就没必要发送ajax请求
                //根据用户输入的内容发送ajax请求查询以此内容开头的商品简码，从而查出符合要求的商品名字
                $.post("{:url('getBudget')}",{"project_id":$("#project_id").val()},function(data){
                    if(!!data){
                        //清除掉以前的值
                        $(".searchDiv1 dl.layui-anim").html("");
                        $.each($.parseJSON(data),function(index,item){
                            // console.log(index);
                            $(".searchDiv1").find("dl.layui-anim").append("<dd lay-value=\""+item.id +"\" onclick=\"changeSearchText(this,1,"+item.caigou_danjia+")\">"+item.name+"</dd>");
                            $(".searchDiv1").find("dl.layui-anim").children("dd:first").addClass("layui-this");
                        });
                        //重新渲染select
                        form.render('select');
                    }
                },'json')
            }
        });
        form.on('select(project_type)', function(data){
            select_union(data.value);
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
        // var i=1;
        $(".field-guige-add").click(function(){
            i++;
            $(".new_task").before("<div class=\"layui-form-item\">\n" +
                "        <label class=\"layui-form-label\">内容</label>\n" +
                "        <div class=\"layui-input-inline\" style=\"width: 200px\">\n" +
                "            <div class='layui-form-select layui-form-selected searchDiv"+i+"'>\n" +
                "                    <div class=\"layui-select-title\"><input type=\"text\" placeholder=\"选择或输入\" name=\"content[]\" class='layui-input search_input"+i+"' id='search_input"+i+"'></div>\n" +
                "                    <dl class=\"layui-anim layui-anim-upbit\" style=\"display: none;\">\n" +
                "                    </dl>\n" +
                "                </div>\n" +
                "        </div>\n" +
                "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
                "            <input type=\"number\" class=\"layui-input field-num\" name=\"num[]\" onblur=\"amout_sum()\" autocomplete=\"off\" placeholder=\"计量\">\n" +
                "        </div>\n" +
                "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
                "            <select name=\"unit[]\" class=\"field-unit\" type=\"select\">\n" +
                "                {$unit_option}\n" +
                "            </select>\n" +
                "        </div>\n" +
                "        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
                "            <input type=\"number\" class=\"layui-input field-per_price\" name=\"per_price[]\" onblur=\"amout_sum()\" id='per_price"+i+"' autocomplete=\"off\" placeholder=\"单价\">\n" +
                "        </div>\n" +
                "        <div class=\"layui-form-mid\" style=\"color: red\">元</div>\n" +
                "    </div>");
            form.render();

            $(".search_input"+i).keyup(function(event){
                $(".searchDiv"+i).find("dl.layui-anim").show();
                if(''!=$(this).val().trim()){ //如果输入框的值没有改变就没必要发送ajax请求
                    //根据用户输入的内容发送ajax请求查询以此内容开头的商品简码，从而查出符合要求的商品名字
                    $.post("{:url('getBudget')}",{"project_id":$("#project_id").val()},function(data){
                        if(!!data){
                            //清除掉以前的值
                            $(".searchDiv"+i+" dl.layui-anim").html("");
                            $.each($.parseJSON(data),function(index,item){
                                // console.log(index);
                                $(".searchDiv"+i).find("dl.layui-anim").append("<dd lay-value=\""+item.id +"\" onclick=\"changeSearchText(this,i,"+item.caigou_danjia+")\">"+item.name+"</dd>");
                                $(".searchDiv"+i).find("dl.layui-anim").children("dd:first").addClass("layui-this");
                            });
                            //重新渲染select
                            form.render('select');
                        }
                    },'json');
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

    function changeSearchText(obj,i,caigou_danjia){
        $("#search_input"+i).val(obj.innerHTML);
        $("#per_price"+i).val(caigou_danjia);
        $(".searchDiv"+i).find("dl.layui-anim").hide();
    }
    
    function dealHide(i) {
        $(".searchDiv"+i).find("dl.layui-anim").hide();
    }

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