<style>
    .layui-upload-img {
        width: 92px;
        height: 92px;
        margin: 0 10px 10px 0;
        display: none;
    }

    .layui-form-pane .layui-form-label {
        width: 130px;
        padding: 8px 15px;
        height: 38px;
        line-height: 20px;
        border-width: 1px;
        border-style: solid;
        border-radius: 2px 0 0 2px;
        text-align: center;
        background-color: #FBFBFB;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        box-sizing: border-box;
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
                    <div class="layui-card-body">
                        <form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
                            项目名称：{$Request.param.project_name}
                            <br>
                            任务主题：{$data_info['name']}
                            <br>
                            描述：{$data_info['remark']}
                            <br>
                            预设产量：{$data_info['score']}
                            <br>
                            开始时间：{$data_info['start_time']}
                            <br>
                            结束时间：{$data_info['end_time']}
                            <br>
                            历时：{$data_info['time_long']}
                            <br>
                            附件说明：{$data_info['attachment']}
                            <br>
                            负责人：{$data_info['manager_user_id']|default=''}
                            <br>
                            参与人：{$data_info['deal_user_id']|default=''}
                            <br>
                            审批人：{$data_info['send_user_id']|default=''}
                            <br>
                            抄送人：{$data_info['copy_user_id']|default=''}
                            <br>
                            是否确认：
                            {if condition="$data_info['u_res'] eq 'a'"}
                                <span class="red">{$data_info['u_res_str']}</span>
                            {else/}
                                <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
                                <button type="button" onclick="accept_task({$data_info['id']},{$Request.param.type})" class="layui-btn layui-btn-normal">确认</button>
                            {/if}
                        </form>
                    </div>
            </div>
            {if condition="$Request.param.type eq 1"}
            <div class="layui-card">
                <div class="layui-card-header">成果反馈</div>
                <form class="layui-form layui-form-pane" action="{:url('ProjectReport/add')}" method="post" id="editForm">
                    <div class="layui-form-item">
                        <label class="layui-form-label">计划完成百分比</label>
                        <div class="layui-form-mid red">{$data_info['time_per']}%{$data_info['span']}</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">实际完成百分比</label>
                        <div class="layui-input-inline">
                            <input type="number" class="layui-input field-realper" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="realper" lay-verify="required" autocomplete="off" placeholder="请输完成情况">
                        </div>
                        <div class="layui-form-mid red">%*</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">备注<span style="color: red"></span></label>
                        <div class="layui-input-inline">
                            <textarea type="text" class="layui-textarea field-mark" name="mark"
                                      lay-verify="required" autocomplete="off" placeholder="请输入备注"></textarea>
                        </div>
                        <div class="layui-form-mid red">*</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">附件说明</label>
                        <div class="layui-input-inline">
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
                        <div class="layui-input-block">
                            <input type="hidden" class="field-project_id" name="project_id" value="{$Request.param.id}">
                            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
                            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
                        </div>
                    </div>
                </form>
            </div>
            {/if}
        </div>
        <div class="layui-col-md6">
            {empty name="data_info['child']"}
            <div class="layui-card">
                <div class="layui-card-header">汇报记录</div>
                <ul class="layui-timeline">
                    {volist name="report_info" id="vo"}
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis"></i>
                        <div class="layui-timeline-content layui-text">
                            <div class="layui-timeline-title">
                                <span style="color: red">[{$vo['real_name']}]</span>
                                <span style="color: red">[{$vo['create_time']}]</span>
                                完成百分比：<span style="color: green">[{$vo['realper']}%]</span>
                                {neq name="type" value='1'}
                                计划百分比：<span style="color: green">[{$vo['per']}%]</span>
                                <a onclick="open_reply({$vo['id']},{$vo['project_id']})" class="layui-btn layui-btn-normal layui-btn-xs">评价</a>
                                {/neq}
                                <br>
                                {$vo['mark']}
                                <br>
                                {notempty name="vo['span']"}
                                {$vo['span']}
                                <br>
                                {/notempty}
                                {notempty name="vo['attachment']"}
                                附件：
                                <ul>
                                    {volist name="vo['attachment']" id="v"}
                                    <li>
                                        <a target="_blank" href="{$v}">{$v}</a>
                                    </li>
                                    {/volist}
                                </ul>
                                <br>
                                {/notempty}
                                <ul>
                                    {volist name="vo['reply']" id="v"}
                                    <li>
                                        <span style="color: green">[{$v['real_name']}]</span>
                                        <span style="color: grey">[{$v['create_time']}评价]</span><br>
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
            {else/}
            <div class="layui-card">
                <div class="layui-card-header">成果记录</div>
                <ul class="layui-timeline">
                    {volist name="report_info" id="vo"}
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis"></i>
                        <div class="layui-timeline-content layui-text">
                            <div class="layui-timeline-title">
                                <span style="color: red">[{$vo['real_name']}]</span>
                                <span style="color: red">[{$vo['create_time']}]</span>
                                完成百分比：<span style="color: green">[{$vo['realper']}%]</span>
                                {neq name="type" value='1'}
                                计划百分比：<span style="color: green">[{$vo['per']}%]</span>
                                <a onclick="check_result({$data_info['id']},'{$data_info['name']}')" class="layui-btn layui-btn-normal layui-btn-xs">审核校对</a>
                                {/neq}
                                <br>
                                {$vo['mark']}
                                <br>
                                {notempty name="vo['span']"}
                                {$vo['span']}
                                <br>
                                {/notempty}
                                {notempty name="vo['attachment']"}
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
                                        <span style="color: green">[{$v['real_name']}]</span>
                                        <span style="color: grey">[{$v['create_time']}审核]</span><br>
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
            {/empty}
        </div>
    </div>
</div>

{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
    layui.use(['jquery', 'laydate','flow', 'upload'], function () {
        var $ = layui.jquery, laydate = layui.laydate, upload = layui.upload,flow = layui.flow;

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

        //多文件列表示例
        var demoListView = $('#demoList'), uploadListIns = upload.render({
            elem: '#testList',
            url: '{:url("admin/UploadFile/upload?thumb=no&water=no")}',
            accept: 'file',
            size: "{:config('upload.upload_file_size')}",
            multiple: true,
            auto: false,
            bindAction: '#testListAction',
            choose: function (obj) {
                var files = this.files = obj.pushFile(); //将每次选择的文件追加到文件队列
                //读取本地文件
                obj.preview(function (index, file, result) {
                    var tr = $(['<tr id="upload-' + index + '">'
                        , '<td>' + file.name + '</td>'
                        , '<td>' + (file.size / 1014).toFixed(1) + 'kb</td>'
                        , '<td>等待上传</td>'
                        , '<td>'
                        , '<button class="layui-btn layui-btn-xs demo-reload layui-hide">重传</button>'
                        , '<button class="layui-btn layui-btn-xs layui-btn-danger demo-delete">删除</button>'
                        , '</td>'
                        , '</tr>'].join(''));

                    //单个重传
                    tr.find('.demo-reload').on('click', function () {
                        obj.upload(index, file);
                    });

                    //删除
                    tr.find('.demo-delete').on('click', function () {
                        delete files[index]; //删除对应的文件
                        tr.remove();
                        uploadListIns.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
                    });

                    demoListView.append(tr);
                });
                $('.other-div').show();
            }
            , done: function (res, index, upload) {
                if (res.code == 1) { //上传成功
                    var tr = demoListView.find('tr#upload-' + index)
                        , tds = tr.children();
                    tds.eq(2).html('<span style="color: #5FB878;">上传成功</span>');
                    tds.eq(3).html(''); //清空操作
                    var new_value = $('.field-attachment').val();
                    new_value += res.data.file + ',';
                    $('.field-attachment').val(new_value);
                    return delete this.files[index]; //删除文件队列已经上传成功的文件
                }
                this.error(index, upload);
            }
            , error: function (index, upload) {
                var tr = demoListView.find('tr#upload-' + index)
                    , tds = tr.children();
                tds.eq(2).html('<span style="color: #FF5722;">上传失败</span>');
                tds.eq(3).find('.demo-reload').removeClass('layui-hide'); //显示重传
            }
        });

    });
    function accept_task(id,type) {
        var open_url = "{:url('setConfirm')}?id="+id+"&type="+type;
        $.post(open_url, function(res) {
            if (res.code == 1) {
                layer.msg(res.msg);
                location.reload();
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
                location.reload();
            }else {
                layer.alert(res.msg);
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
            title :'评价',
            area: ['600px', '400px'],
            content: open_url,
            success:function (layero, index) {
                var body = layer.getChildFrame('body', index);  //巧妙的地方在这里哦
                body.contents().find(".field-report_id").val(id);
                body.contents().find(".field-project_id").val(project_id);
            }
        });
    }

    function check_result(id,pname){
        var open_url = "{:url('Project/checkResult')}?id="+id+"&pname="+pname;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :pname,
            maxmin: true,
            area: ['900px', '700px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }
</script>
<script src="__ADMIN_JS__/footer.js"></script>