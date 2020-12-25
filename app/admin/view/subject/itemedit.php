<style>
    .layui-upload-img {
        width: 92px;
        height: 92px;
        margin: 0 10px 10px 0;
        display: none;
    }

    .layui-form-label1 {
        width: 110px;
        padding: 8px 15px;
        height: 38px;
        line-height: 20px;
        border-width: 1px;
        /* border-style: solid; */
        border-radius: 2px 0 0 2px;
        text-align: center;
        /* background-color: #FBFBFB; */
        overflow: hidden;
        white-space: nowrap;
        /* text-overflow: ellipsis; */
        box-sizing: border-box;
    }
    .layui-input-inline1 {
        display: inline-block;
        vertical-align: middle;
        width: 110px;
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
<!--        <div class="layui-form-item">-->
<!--            <label class="layui-form-label">类型系数</label>-->
<!--            <div class="layui-input-inline">-->
<!--                <input type="text" class="layui-input field-ratio" name="ratio" value="1.0" lay-verify="required" autocomplete="off" placeholder="请输入系数">-->
<!--            </div>-->
<!--            <div class="layui-form-mid" style="color: red">*</div>-->
<!--        </div>-->
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
        {volist name="data_info['small_major_deal']" id="f"}
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>
                <div class="layui-form-item">
<!--                    <label class="layui-form-label1">{$f['name']}</label>-->
                    <div class="layui-input-inline1">
                        <input type="text" class="layui-input field-cat_name" name="cat_name[{$f['id']}][name]" value="{$f['name']}" lay-verify="required" >
                    </div>
                    <div class="layui-input-inline1">
                        <input type="text" class="layui-input field-cat_ratio" name="cat_name[{$f['id']}][ratio]" value="{$f['value']/100}" lay-verify="required" >
                    </div>
                </div>
            </legend>
        </fieldset>
        <div class="layui-form-item" style="margin-left: 100px">
            <div class="layui-form-mid" style="margin-left: 30px">专业</div>
            <div class="layui-form-mid" style="margin-left: 80px">配比</div>
            <div class="layui-form-mid" style="margin-left: 50px">进度(不可编辑)</div>
        </div>
        {volist name="f['child']" id="f1"}
        <div class="layui-form-item" style="margin-left: 100px">
<!--            <label class="layui-form-label1">{$f1['name']}</label>-->
            <div class="layui-input-inline1">
                <input type="text" class="layui-input field-item_name" name="item_name[{$f['id']}][{$f1['id']}][name]" value="{$f1['name']}" lay-verify="required" >
            </div>
            <div class="layui-input-inline1">
                <input type="text" class="layui-input field-item_ratio" name="item_name[{$f['id']}][{$f1['id']}][ratio]" value="{$f1['value']/100}" lay-verify="required" >
            </div>
            <div class="layui-input-inline1">
                <input type="text" class="layui-input field-jindu_per"  name="item_name[{$f['id']}][{$f1['id']}][jindu_per]" value="{$f1['jindu_per']|default=0}" lay-verify="required" >
            </div>
        </div>
        {/volist}
        <div class="layui-form-item new_major{$f['id']}">
            <a href="javascript:void(0);" class="aicon ai-tianjia" onclick="major_add({$f['id']})" style="margin-left:500px;font-size: 30px;"></a>
        </div>
        {/volist}
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
            <label class="layui-form-label">合同总价</label>
            <div class="layui-input-inline">
                <input type="number" class="layui-input field-total_price" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="total_price" lay-verify="required" autocomplete="off" >
            </div>
            <div class="layui-form-mid red">元*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">项目面积</label>
            <div class="layui-input-inline">
                <input type="number" class="layui-input field-area" name="area" lay-verify="required" autocomplete="off" placeholder="请输入项目面积">
            </div>
            <div class="layui-form-mid red">㎡*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">产量系数</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-score_ratio" name="score_ratio" value="" lay-verify="required" autocomplete="off" placeholder="请输入系数">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">预设产量</label>
            <div class="layui-input-inline">
                <input type="number" class="layui-input field-score" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="score" lay-verify="required" autocomplete="off" placeholder="请输入预设值">
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
<!--        <div class="layui-form-item">-->
<!--            <label class="layui-form-label">项目属性</label>-->
<!--            <div class="layui-input-inline">-->
<!--                <select name="t_type" class="field-t_type" type="select" lay-filter="t_type">-->
<!--                    {$t_type}-->
<!--                </select>-->
<!--            </div>-->
<!--        </div>-->
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

        $('.field-real_per').keyup(function () {
            var num = $('.field-real_per').val();
            if (num > 100){
                layer.msg('百分比不能超过100');
            }
        });

    });

    function major_add(v){
        var name = $(".new_major"+v).prev().find('div input').attr('name');
        var last_num = name.lastIndexOf("][");
        var name1 = name.substring(10,last_num);
        var name_arr = name1.split('][');
        var a1 = name_arr[0],a2 = name_arr[1]*1 + 1*1;
        // console.log(name_arr);
        $(".new_major"+v).before("<div class=\"layui-form-item\" style=\"margin-left: 100px\">\n" +
            "            <div class=\"layui-input-inline1\">\n" +
            "                <input type=\"text\" class=\"layui-input field-item_name\" name=\"item_name["+a1+"]["+a2+"][name]\" value=\"\" lay-verify=\"required\" >\n" +
            "            </div>\n" +
            "            <div class=\"layui-input-inline1\">\n" +
            "                <input type=\"text\" class=\"layui-input field-item_ratio\" name=\"item_name["+a1+"]["+a2+"][ratio]\" value=\"0\" lay-verify=\"required\" >\n" +
            "            </div>\n" +
            "            <div class=\"layui-input-inline1\">\n" +
            "                <input type=\"text\" class=\"layui-input field-jindu_per\"  name=\"item_name["+a1+"]["+a2+"][jindu_per]\" value=\"0\" lay-verify=\"required\" >\n" +
            "            </div>\n" +
            "        </div>");
    }

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

    $(".field-score_ratio").blur(function () {
        var area = parseInt($('.field-area').val()), score_ratio= parseFloat($(this).val()),score=0;
        score = parseFloat(area*score_ratio);
        $('.field-score').val(score.toFixed(0));
    });

    $(".field-score").blur(function () {
        var area = parseInt($('.field-area').val()), score= parseInt($(this).val()),score_ratio=0;
        if (area > 0){
            score_ratio = score/area;
            $('.field-score_ratio').val(score_ratio.toFixed(2));
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