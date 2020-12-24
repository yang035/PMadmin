<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">选择项目</label>
            <div class="layui-inline">
                <div class="layui-input-inline box box1">
                </div>
                <input id="project_name" type="hidden" name="subject_name" value="{$Request.param.subject_name}">
                <input id="subject_id" type="hidden" name="subject_id" value="{$Request.param.subject_id}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">主题</label>
            <div class="layui-input-inline">
                <input  class="layui-input field-title" name="title" lay-verify="" autocomplete="off" placeholder="名称">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">描述</label>
            <div class="layui-input-inline">
                <textarea  class="layui-textarea field-remark" name="remark" lay-verify="" autocomplete="off" placeholder="描述"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">附件</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn layui-btn-normal" id="test3"><i class="layui-icon"></i>上传文件</button>
                <input class="layui-input attachment" type="hidden" name="attachment" value="">
                <span class="att_name"></span>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script src="__PUBLIC_JS__/jquery.select.js?v="></script>
<script src="__PUBLIC_JS__/SelectBox.min.js?v="></script>
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','upload'], function() {
        var $ = layui.jquery, laydate = layui.laydate, upload = layui.upload;
        laydate.render({
            elem: '.field-expire_time',
            min:'0'
        });

        $('#reset_expire').on('click', function(){
            $('input[name="expire_time"]').val(0);
        });

        upload.render({
            elem: '#test3',
            url: '{:url("admin/UploadFile/upload?thumb=no&water=no")}',
            accept: 'file', //普通文件
            size:"{:config('upload.upload_file_size')}",
            done: function(res){
                if(res.code == 1) { //上传成功
                    $('.attachment').val(res.data.file);
                    var att_name = $('.att_name').val();
                    att_name += "<a target='_blank' href='"+res.data.file +"'>"+ res.data.name+"</a>,";
                    $('.att_name').html(att_name);
                    layer.msg(res.msg);
                }else {
                    layer.msg(res.msg);
                }
            }
        });
    });
    function check_ratio(e) {
        var num = parseInt($(e).val()),ma=e.getAttribute('max'),mi=e.getAttribute('min');
        if (isNaN(num)) {
            num = 0;
        }
        if (num > ma) {
            layer.msg('比例只能在'+mi+'~'+ma+'之间');
            num = ma;
        }
        if (num < mi) {
            layer.msg('比例只能在'+mi+'~'+ma+'之间');
            num = mi;
        }
        $('.field-ratio').val(num);
    }

    new SelectBox($('.box1'),{$project_select},function(result){
        if ('' != result.id){
            $('#project_name').val(result.name);
            $('#subject_id').val(result.id);
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
        defalut:'',//默认显示内容。如果是'firstData',则默认显示第一个
        // allowInput:true,//是否允许输入
        width:300,//宽
        height:37,//高
        optionMaxHeight:300//下拉框最大高度
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>