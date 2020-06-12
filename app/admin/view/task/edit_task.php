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
<div style="padding: 20px; background-color: #F2F2F2;">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">工作内容</div>
                <form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
                <div class="layui-form-item">
                    <label class="layui-form-label">名称<span style="color: red">*</span></label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input field-name" name="name" lay-verify="required" readonly
                               autocomplete="off" placeholder="请输入名称">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">描述<span style="color: red">*</span></label>
                    <div class="layui-input-inline">
                        <textarea type="text" class="layui-textarea field-remark" name="remark" lay-verify="required"
                                  readonly autocomplete="off" placeholder="请输入描述"></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">预设产量<span style="color: red">*</span></label>
                    <div class="layui-input-inline">
                        <input type="number" class="layui-input field-score" name="score"
                               onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))"
                               lay-verify="required" readonly autocomplete="off" placeholder="请输入预设值">
                    </div>
                    <div class="layui-form-mid">斗</div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">开始时间<span style="color: red">*</span></label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input field-start_time" name="start_time" lay-verify="required"
                               autocomplete="off" readonly placeholder="选择开始时间">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">结束时间<span style="color: red">*</span></label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input field-end_time" name="end_time" lay-verify="required"
                               autocomplete="off" readonly placeholder="选择结束时间">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">历时</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input field-time_long" name="time_long" readonly
                               autocomplete="off" readonly>
                    </div>
                    天
                </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">紧急程度</label>
                        <div class="layui-input-inline">
                            <select name="grade" class="field-grade" readonly type="select" lay-filter="grade">
                                {$grade_type}
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">附件说明</label>
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
                <div class="layui-form-item">
                    <label class="layui-form-label">负责人</label>
                    <div class="layui-input-inline">
                        <button type="button" class="layui-btn" id="manager_user_id" style="display: none;">选择负责人
                        </button>
                        (此任务由谁负责)
                        <div id="manager_select_id">{$data_info['manager_user_id']|default=''}</div>
                        <input type="hidden" name="manager_user" id="manager_user"
                               value="{$data_info['manager_user']|default=''}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">参与人</label>
                    <div class="layui-input-inline">
                        <button type="button" class="layui-btn" id="deal_user_id" style="display: none;">选择参与人</button>
                        (此任务具体哪些人做)
                        <div id="deal_select_id">{$data_info['deal_user_id']|default=''}</div>
                        <input type="hidden" name="deal_user" id="deal_user"
                               value="{$data_info['deal_user']|default=''}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">审批人</label>
                    <div class="layui-input-inline">
                        <button type="button" class="layui-btn" id="send_user_id" style="display: none;">选择审批人</button>
                        (此任务需要谁来审批)
                        <div id="send_select_id">{$data_info['send_user_id']|default=''}</div>
                        <input type="hidden" name="send_user" id="send_user" value="{$data_info['send_user']}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">抄送人</label>
                    <div class="layui-input-inline">
                        <button type="button" class="layui-btn" id="copy_user_id" style="display: none;">选择抄送人</button>
                        (此任务需要抄送给谁)
                        <div id="copy_select_id">{$data_info['copy_user_id']|default=''}</div>
                        <input type="hidden" name="copy_user" id="copy_user" value="{$data_info['copy_user']}">
                    </div>
                </div>
                    {if condition="$data_info['u_res'] eq 'a'"}
                    <div class="layui-form-item">
                        <label class="layui-form-label">是否确认</label>
                        <div class="layui-input-inline">
                            <span style="color: red;">{$data_info['u_res_str']}</span>
                        </div>
                    </div>
                    {else/}
                    <div class="layui-form-item">
                        <label class="layui-form-label">是否确认</label>
                        <div class="layui-input-block">
                            <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
                            <button type="button" onclick="accept_task({$data_info['id']},{$Request.param.type})" class="layui-btn layui-btn-normal">确认</button>
                        </div>
                    </div>
                    {/if}
                    {if condition="($data_info['status'] eq 0) && ($Request.param.type eq 2) "}
                    <div class="layui-form-item">
                        <label class="layui-form-label">是否完结</label>
                        <div class="layui-input-block">
                            <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
                            <button type="button" onclick="finish_task({$data_info['id']},{$Request.param.type})" class="layui-btn layui-btn-normal">点击完成</button>
                        </div>
                    </div>
                    {elseif condition="$data_info['status'] eq 1"}
                    <div class="layui-form-item">
                        <label class="layui-form-label">是否完结</label>
                        <div class="layui-input-inline">
                            <span style="color: red;">已完结</span>
                        </div>
                    </div>
                    {else/}
                    <div class="layui-form-item">
                        <label class="layui-form-label">是否完结</label>
                        <div class="layui-input-inline">
                            <span style="color: red;">进行中</span>
                        </div>
                    </div>
                    {/if}
                </form>
            </div>
        </div>
        <div class="layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">完成情况</div>
                <form class="layui-form layui-form-pane" action="{:url('ProjectReport/add')}" method="post" id="editForm">
                    <div class="layui-form-item">
                        <label class="layui-form-label">完成百分比<span style="color: red">*</span></label>
                        <div class="layui-input-inline">
                            <input type="number" class="layui-input field-realper" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="realper" lay-verify="required" autocomplete="off" placeholder="请输完成情况">
                        </div>%
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">备注<span style="color: red"></span></label>
                        <div class="layui-input-inline">
                            <textarea type="text" class="layui-textarea field-mark" name="mark"
                                      lay-verify="required" autocomplete="off" placeholder="请输入备注"></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">附件说明</label>
                        <div class="layui-input-block">
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
                        <div class="layui-input-block">
                            <input type="hidden" class="field-project_id" name="project_id" value="{$Request.param.id}">
                            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
                            <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="layui-card">
                <div class="layui-card-header">汇报记录</div>
                <ul class="layui-timeline">
                    {volist name="report_info" id="vo"}
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis"></i>
                        <div class="layui-timeline-content layui-text">
                            <div class="layui-timeline-title">
                                <span style="color: red">[{$vo['create_time']}]</span>
                                完成百分比：<span style="color: green">[{$vo['realper']}%]</span>
                                {neq name="type" value='1'}
                                计划百分比：<span style="color: green">[{$vo['per']}%]</span>
                                <a onclick="open_reply({$vo['id']},{$vo['project_id']})" class="layui-btn layui-btn-normal layui-btn-xs">回复</a>
                                {/neq}
                                <br>
                                {$vo['mark']}
                                <br>
                                {notempty name="vo['attachment']"}
                                附件：
                                <ul>
                                    {volist name="vo['attachment']" id="v"}
                                    <li>
                                        <a target="_blank" href="{$v}">附件{$i}</a>
                                    </li>
                                    {/volist}
                                </ul>
                                <br>
                                {/notempty}
                                <ul>
                                    {volist name="vo['reply']" id="v"}
                                    <li>
                                        <span style="color: grey">[{$v['create_time']}回复]</span><br>
                                        {$v['content']}
                                    </li>
                                    {/volist}
                                </ul>
                            </div>
                        </div>
                    </li>
                    {/volist}
                </ul>
            </div>
        </div>
    </div>
</div>

{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
    layui.use(['jquery', 'laydate','element','flow', 'upload'], function () {
        var $ = layui.jquery, laydate = layui.laydate,element = layui.element, upload = layui.upload,flow = layui.flow;;

        //计算两个时间差
        function getTimeLong(value) {
            var timeLong, time1 = $('.field-start_time').val();
            var date3 = new Date(value).getTime() - new Date(time1).getTime();   //时间差的毫秒数
            //计算出相差天数
            var days = Math.floor(date3 / (24 * 3600 * 1000));
            $('.field-time_long').val(days);
        }

        var uploadInst = upload.render({
            elem: '#attachment-upload'
            , url: '/upload/'
            , before: function (obj) {
                //预读本地文件示例，不支持ie8
                obj.preview(function (index, file, result) {
                    $('#attachment-upload-img').toggle();
                    $('#attachment-upload-img').attr('src', result); //图片链接（base64）
                });
            }
            , done: function (res) {
                //如果上传失败
                if (res.code > 0) {
                    return layer.msg('上传失败');
                }
                //上传成功
            }
            , error: function () {
                //演示失败状态，并实现重传
                var demoText = $('#attachment-upload-text');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-mini demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function () {
                    uploadInst.upload();
                });
            }
        });


        $('#reset_expire').on('click', function () {
            $('input[name="expire_time"]').val(0);
        });

        $('#manager_user_id').on('click', function () {
            var open_url = "{:url('Tool/getTreeUser')}?m=manager";
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type: 2,
                title: '员工列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success: function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
        });

        $('#deal_user_id').on('click', function () {
            var open_url = "{:url('Tool/getTreeUser')}?m=deal";
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type: 2,
                title: '员工列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success: function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
        });

        $('#send_user_id').on('click', function () {
            var open_url = "{:url('Tool/getTreeUser')}?m=send";
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type: 2,
                title: '员工列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success: function (layero, index) {
                    var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                }
            });
        });

        $('#copy_user_id').on('click', function () {
            var open_url = "{:url('Tool/getTreeUser')}?m=copy";
            if (open_url.indexOf('?') >= 0) {
                open_url += '&hisi_iframe=yes';
            } else {
                open_url += '?hisi_iframe=yes';
            }
            layer.open({
                type: 2,
                title: '员工列表',
                maxmin: true,
                area: ['800px', '500px'],
                content: open_url,
                success: function (layero, index) {
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

    });
    function accept_task(id,type) {
        var open_url = "{:url('setConfirm')}?id="+id+"&type="+type;
        $.post(open_url, function(res) {
            if (res.code == 1) {
                layer.msg(res.msg);
                // location.reload();
            }else {
                layer.msg(res.msg);
                // location.reload();
            }
        });
    }

    function finish_task(id,type) {
        var open_url = "{:url('setStatus')}?id="+id+"&type="+type;
        $.post(open_url, function(res) {
            if (res.code == 1) {
                layer.alert(res.msg);
                // location.reload();
            }else {
                layer.alert(res.msg);
                // location.reload();
            }
        });
    }

    function open_reply(id,project_id) {
        var open_url = "{:url('ReportReply/add')}?id="+id+"&project_id="+project_id;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            maxmin: true,
            title :'回复',
            area: ['700px', '500px'],
            content: open_url,
            success:function (layero, index) {
                var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                body.contents().find(".field-report_id").val(id);
                body.contents().find(".field-project_id").val(project_id);
            }
        });
    }
</script>
<script src="__ADMIN_JS__/footer.js"></script>