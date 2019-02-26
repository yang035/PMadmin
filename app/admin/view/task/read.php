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
        {notempty name="Request.param.pid"}
    <div class="layui-form-item">
        <label class="layui-form-label">上级标题</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-pname" name="pname" value="{$pname}" readonly lay-verify="required" autocomplete="off" placeholder="请输入名称">
        </div>
    </div>
        {/notempty}
    <div class="layui-form-item">
        <label class="layui-form-label">名称<span style="color: red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-name" name="name" lay-verify="required" readonly autocomplete="off" placeholder="请输入名称">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">描述<span style="color: red">*</span></label>
        <div class="layui-input-block">
            <textarea type="text" rows="15" class="layui-textarea field-remark" name="remark" lay-verify="required" readonly autocomplete="off" placeholder="请输入描述"></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">预设产量<span style="color: red">*</span></label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-score" name="score" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" lay-verify="required" readonly autocomplete="off" placeholder="请输入预设值">
        </div>
        <div class="layui-form-mid">斗</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">开始时间<span style="color: red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-start_time" name="start_time" lay-verify="required" autocomplete="off" readonly placeholder="选择开始时间">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">结束时间<span style="color: red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-end_time" name="end_time" lay-verify="required" autocomplete="off" readonly placeholder="选择结束时间">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">历时</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-time_long" name="time_long" readonly autocomplete="off" readonly>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">紧急程度</label>
        <div class="layui-input-inline">
            <select name="grade" class="field-grade" type="select" lay-filter="grade" readonly="">
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
                <button type="button" class="layui-btn layui-btn-normal" id="testList" style="display: none;">选择多文件</button>
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
            <button type="button" class="layui-btn" id="manager_user_id" style="display: none;">选择负责人</button>(此任务由谁负责)
            <div id="manager_select_id">{$data_info['manager_user_id']|default=''}</div>
            <input type="hidden" name="manager_user" id="manager_user" value="{$data_info['manager_user']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">参与人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="deal_user_id" style="display: none;">选择参与人</button>(此任务具体哪些人做)
            <div id="deal_select_id">{$data_info['deal_user_id']|default=''}</div>
            <input type="hidden" name="deal_user" id="deal_user" value="{$data_info['deal_user']|default=''}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">审批人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="send_user_id" style="display: none;">选择审批人</button>(此任务需要谁来审批)
            <div id="send_select_id">{$data_info['send_user_id']|default=''}</div>
            <input type="hidden" name="send_user" id="send_user" value="{$data_info['send_user']}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">抄送人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="copy_user_id" style="display: none;">选择抄送人</button>(此任务需要抄送给谁)
            <div id="copy_select_id">{$data_info['copy_user_id']|default=''}</div>
            <input type="hidden" name="copy_user" id="copy_user" value="{$data_info['copy_user']}">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
            <input type="hidden" class="field-pid" name="pid" value="{$Request.param.pid}">
            <input type="hidden" class="field-code" name="code" value="{$Request.param.code}">
            <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
var formData = {:json_encode($data_info)};

layui.use(['jquery', 'laydate','upload'], function() {
    var $ = layui.jquery, laydate = layui.laydate,upload = layui.upload;
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
        var open_url = "{:url('Tool/getTreeUser')}?m=manager";
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
        var open_url = "{:url('Tool/getTreeUser')}?m=deal";
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
        var open_url = "{:url('Tool/getTreeUser')}?m=send"+'&path=1';
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
        var open_url = "{:url('Tool/getTreeUser')}?m=copy";
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