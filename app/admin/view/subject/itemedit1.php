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
    .layui-input-block{
        width: 519px;
    }
    .new_task{
        margin-left: 630px;
    }
</style>
<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">项目类型</label>
            <div class="layui-input-inline">
                <select name="cat_id" class="field-cat_id" type="select">
                    {$subject_option}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">类型系数</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-ratio" name="ratio" value="1.0" lay-verify="required" autocomplete="off" placeholder="请输入系数">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">项目编号</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-idcard" name="idcard" lay-verify="required" autocomplete="off" placeholder="请输入项目编号" value="{$cur_time}">
            </div>
            <div class="layui-form-mid red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">项目名称</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-name" name="name" lay-verify="required" autocomplete="off" placeholder="请输入项目名称">
            </div>
            <div class="layui-form-mid red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">项目描述</label>
            <div class="layui-input-block">
                <textarea  class="layui-textarea field-remark" name="remark" lay-verify="" autocomplete="off" placeholder="项目描述"></textarea>
            </div>
        </div>
        {notempty name="data_info['big_major']"}
        {volist name="data_info['big_major']" id="vo"}
        <div class="layui-form-item">
            <label class="layui-form-label">专业配比</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-big_major1" name="big_major[]" autocomplete="off" onblur='big_major_match()' placeholder="大专业配比" value="{$vo}">
            </div>
            <div class="layui-form-mid red"></div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <textarea type="text" class="layui-textarea field-small_major1" name="small_major[]" onblur='small_major_match()' autocomplete="off" placeholder="小专业配比">{$data_info['small_major'][$key]}</textarea>
            </div>
            <div class="layui-form-mid red"></div>
        </div>
        {/volist}
        {else/}
        <div class="layui-form-item">
            <label class="layui-form-label">专业配比</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-big_major1" name="big_major[]" onblur='big_major_match()' autocomplete="off" placeholder="大专业配比">
            </div>
            <div class="layui-form-mid red"></div>
        </div>
        <div class="layui-field-box" style="margin-left: 100px;width: 519px; color: #666;">
            <font style="color: red">规则 </font>方案设计：100
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <textarea type="text" class="layui-textarea field-small_major1" name="small_major[]" onblur='small_major_match()' autocomplete="off" placeholder="小专业配比"></textarea>
            </div>
            <div class="layui-form-mid red"></div>
        </div>
        <div class="layui-field-box" style="margin-left: 100px;width: 519px; color: #666;">
            <font style="color: red">规则 </font>方案创意：25，文本：16，效果表现：35，估算：2，植物：3，审核校对：4，项目负责：10，设计服务：5
        </div>
        {/notempty}
        <div class="new_task">
            <a href="javascript:void(0);" class="aicon ai-tianjia field-task-add" style="float: left;font-size: 30px;"></a>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">项目面积</label>
            <div class="layui-input-inline">
                <input type="number" class="layui-input field-area" name="area" autocomplete="off" placeholder="请输入项目面积">
            </div>
            <div class="layui-form-mid">㎡</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">建设单位</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-development" name="development" autocomplete="off" placeholder="请输入建设单位">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">项目地址</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-address" name="address" autocomplete="off" placeholder="请输入项目地址">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">项目来源</label>
            <div class="layui-input-inline">
                <select name="p_source" class="field-p_source" type="select" lay-filter="p_source">
                    {$p_source}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">预设产量</label>
            <div class="layui-input-inline">
                <input type="number" class="layui-input field-score" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="score" lay-verify="required" autocomplete="off" placeholder="请输入ML">
            </div>
            <div class="layui-form-mid red">斗*</div>
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
            <label class="layui-form-label">打分机制</label>
            <div class="layui-input-inline">
                <select name="level" class="field-level" type="select" lay-filter="level">
                    {$three_level}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">项目属性</label>
            <div class="layui-input-inline">
                <select name="t_type" class="field-t_type" type="select" lay-filter="t_type">
                    {$t_type}
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
                <div class="layui-timeline-content layui-text">
                    {notempty name="data_info['attachment_show']"}
                    <ul>
                        {volist name="data_info['attachment_show']" id="v"}
                        <li>
                            <a target="_blank" href="{$v}">附件{$i}</a>
                        </li>
                        {/volist}
                    </ul>
                    <br>
                    {/notempty}
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">项目状态</label>
            <div class="layui-input-inline">
                <select name="s_status" class="field-s_status" type="select">
                    {$s_status}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否公开</label>
            <div class="layui-input-inline">
                <select name="is_private" class="field-is_private" type="select">
                    {$is_private}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                <input type="radio" class="field-status" name="status" value="1" title="启用" checked>
                <input type="radio" class="field-status" name="status" value="0" title="禁用">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
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
            type: 'datetime',
            trigger: 'click',
            change: function(value){
                // $(".laydate-btns-time").click();
            },
            done: function (value, date, endDate) {
                $("input[name='end_time']").val(value);
            }
        });
        laydate.render({
            elem: '.field-end_time',
            type: 'datetime',
            trigger: 'click',
            min: $("input[name='start_time']").val(),
            change: function(value){
                // $(".laydate-btns-time").click();
            },
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

        $(".field-task-add").click(function(){
            $(".new_task").before("<div class=\"layui-form-item\">\n" +
                "            <label class=\"layui-form-label\">专业配比</label>\n" +
                "            <div class=\"layui-input-inline\">\n" +
                "                <input type=\"text\" class=\"layui-input field-big_major1\" name=\"big_major[]\" onblur='big_major_match()' autocomplete=\"off\" placeholder=\"大专业配比\">\n" +
                "            </div>\n" +
                "            <div class=\"layui-form-mid red\"></div>\n"+
                "        </div>\n" +
                "        <div class=\"layui-form-item\">\n" +
                "            <div class=\"layui-input-block\">\n" +
                "                <textarea type=\"text\" class=\"layui-textarea field-small_major1\" name=\"small_major[]\" onblur='small_major_match()' autocomplete=\"off\" placeholder=\"小专业配比\"></textarea>\n" +
                "            </div>\n" +
                "            <div class=\"layui-form-mid red\"></div>\n"+
                "        </div>");
            form.render();
        });
        $('.field-real_per').keyup(function () {
            var num = $('.field-real_per').val();
            if (num > 100){
                layer.msg('百分比不能超过100');
            }
        });

    });

    $(".field-big_major1").blur(function () {
        var a = $(this).val(),
            re = /^[\u4E00-\u9FA5A-Za-z]+[：]{1}[\d]+$/,
            b = a.match(re),
            c = $(this).parent("div").next("div");
        if (null != b) {
            c.html("正确");
        } else {
            c.html("格式不正确");
        }
    });

    $(".field-small_major1").blur(function () {
        var a = $(this).val(),
            re = /^[\u4E00-\u9FA5A-Za-z]+[：]{1}[\d]+$/,
            re1 = /^[\u4E00-\u9FA5A-Za-z][\u4E00-\u9FA5A-Za-z：\d，]+[\d]+$/,
            b = a.match(re1),
            c = $(this).parent("div").next("div"),
            d = a.split('，'),
            e,
            f=0;
        if (null == b) {
            f = f+1;
        }
        $.each(d, function (i, v) {
            e = v.match(re);
            if (null == e) {
                f = f+1;
            }
        });

        if (f > 0) {
            c.html("格式不正确");
        } else {
            c.html("正确");
        }

    });

    function big_major_match(){
        $(".field-big_major1").blur(function () {
            var a = $(this).val(),
                re = /^[\u4E00-\u9FA5A-Za-z]+[：]{1}[\d]+$/,
                b = a.match(re),
                c = $(this).parent("div").next("div");
            if (null != b) {
                c.html("正确");
            } else {
                c.html("格式不正确");
            }
        });
    }
    function small_major_match(){
        $(".field-small_major1").blur(function () {
            var a = $(this).val(),
                re = /^[\u4E00-\u9FA5A-Za-z]+[：]{1}[\d]+$/,
                re1 = /^[\u4E00-\u9FA5A-Za-z][\u4E00-\u9FA5A-Za-z：\d，]+[\d]+$/,
                b = a.match(re1),
                c = $(this).parent("div").next("div"),
                d = a.split('，'),
                e,
                f=0;
            if (null == b) {
                f = f+1;
            }
            $.each(d, function (i, v) {
                e = v.match(re);
                if (null == e) {
                    f = f+1;
                }
            });

            if (f > 0) {
                c.html("格式不正确");
            } else {
                c.html("正确");
            }

        });

    }

</script>
<script src="__ADMIN_JS__/footer.js"></script>