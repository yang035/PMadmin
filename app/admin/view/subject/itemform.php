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
            <label class="layui-form-label">项目编号<span style="color: red">*</span></label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-idcard" name="idcard" lay-verify="required" autocomplete="off" placeholder="请输入项目编号">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">项目名称<span style="color: red">*</span></label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-name" name="name" lay-verify="required" autocomplete="off" placeholder="请输入项目名称">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">项目描述</label>
            <div class="layui-input-inline">
                <textarea  class="layui-textarea field-remark" name="remark" lay-verify="" autocomplete="off" placeholder="项目描述"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">项目面积</label>
            <div class="layui-input-inline">
                <input type="number" class="layui-input field-area" name="area" autocomplete="off" placeholder="请输入项目面积">
            </div>
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
            <label class="layui-form-label">开始时间<span style="color: red">*</span></label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-start_time" name="start_time" lay-verify="required" readonly autocomplete="off" placeholder="选择开始时间">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">结束时间<span style="color: red">*</span></label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-end_time" name="end_time" lay-verify="required" readonly autocomplete="off" placeholder="选择结束时间">
            </div>
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
            value: new Date(),
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
            value: getNextDate(),
            min: $("input[name='start_time']").val(),
            change: function(value){
                // $(".laydate-btns-time").click();
            },
            done: function(value, date, endDate){
                getTimeLong(value);
            },
        });

        function getNextDate() {
            var time = new Date().getTime();
            return new Date(time).Format('yyyy-MM-dd') + ' 23:59:59';
        }
        //写入时长
        getTimeLong(getNextDate());

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
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>