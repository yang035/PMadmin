<style>
    .layui-upload-img {
        width: 92px;
        height: 92px;
        margin: 0 10px 10px 0;
        display: none;
    }
</style>
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-name" name="name" lay-verify="required" autocomplete="off" placeholder="请输入名称">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">描述</label>
        <div class="layui-input-inline">
            <textarea type="text" class="layui-textarea field-remark" name="remark" lay-verify="required" autocomplete="off" placeholder="请输入描述"></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">预设产量</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-score" name="score" lay-verify="required" autocomplete="off" placeholder="请输入产量">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">开始时间</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-start_time" name="start_time" lay-verify="required" autocomplete="off" placeholder="选择开始时间">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">结束时间</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-end_time" name="end_time" lay-verify="required" autocomplete="off" placeholder="选择结束时间">
        </div>
        <div class="layui-form-mid">斗</div>
    </div>
    <div class="layui-form-item hide">
        <label class="layui-form-label">历时</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-time_long" disabled name="time_long" autocomplete="off">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">图片说明</label>
        <div class="layui-input-inline">
            <div class="layui-upload">
                <button type="button" class="layui-btn" id="attachment-upload">选择图片</button>
                <div class="layui-upload-list">
                    <img class="layui-upload-img" id="attachment-upload-img">
                    <p id="attachment-upload-text"></p>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">审批人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="send_user_id">选择审批人</button>
            <div id="send_select_id"></div>
            <input type="hidden" name="send_user" id="send_user" value="">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">抄送人</label>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn" id="copy_user_id">选择抄送人</button>
            <div id="copy_select_id"></div>
            <input type="hidden" name="copy_user" id="copy_user" value="">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
var formData = {:json_encode($data_info)};

layui.use(['jquery', 'laydate','upload'], function() {
    var $ = layui.jquery, laydate = layui.laydate,upload = layui.upload;
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
        //计算出小时数
        var leave1=date3%(24*3600*1000);   //计算天数后剩余的毫秒数
        var hours=Math.floor(leave1/(3600*1000));
        //计算相差分钟数
        var leave2=leave1%(3600*1000);      //计算小时数后剩余的毫秒数
        var minutes=Math.floor(leave2/(60*1000));
        //计算相差秒数
        var leave3=leave2%(60*1000);      //计算分钟数后剩余的毫秒数
        var seconds=Math.round(leave3/1000);
        timeLong = days+"天 "+hours+"小时 "+minutes+" 分钟"+seconds+" 秒";
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
        var open_url = "{:url('Tool/getTreeUser')}";
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
        var open_url = "{:url('Tool/getTreeUser1')}";
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
});
</script>
<script src="__ADMIN_JS__/footer.js"></script>