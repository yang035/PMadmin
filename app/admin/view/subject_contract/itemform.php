<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">选择项目</label>
            <div class="layui-inline">
                <div class="layui-input-inline box box1">
                </div>
                <input id="project_name" type="hidden" name="subject_name" value="{$Request.param.subject_name}">
                <input id="subject_id" type="hidden" name="subject_id" value="{$subject_id}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">项目编号</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-idcard" name="idcard" autocomplete="off" placeholder="请输入合同编号" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">合同名称</label>
            <div class="layui-input-inline">
                <input class="layui-input field-name" name="name" lay-verify="required" autocomplete="off">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">选择类型</label>
            <div class="layui-input-inline">
                <select name="contract_cat" class="field-contract_cat" type="select" lay-filter="contract_cat">
                    {$contract_cat}
                </select>
                <select name="tpl_id" class="field-tpl_id" type="select" lay-filter="tpl_id">
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">内容</label>
            <div class="layui-input-block">
                <textarea id="ckeditor" name="content" class="field-content"></textarea>
            </div>
        </div>
        {:editor(['ckeditor', 'ckeditor2'],'kindeditor')}
        <div class="layui-form-item">
            <label class="layui-form-label">附件</label>
            <div class="layui-input-inline">
                <button type="button" class="layui-btn layui-btn-normal" id="test3"><i class="layui-icon"></i>上传文件</button>
                <input class="layui-input attachment" type="hidden" name="attachment" value="{$data_info['attachment']|default=''}">
                <input class="layui-input att_name" type="hidden" name="att_name" id="attachment_name" value="{$data_info['att_name']|default=''}">
                <span class="att_name"><a target='_blank' class="mcolor" href="{$data_info['attachment']|default=''}">{$data_info['att_name']|default=''}</a></span>
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
<script src="__PUBLIC_JS__/jquery.select.js?v="></script>
<script src="__PUBLIC_JS__/SelectBox.min.js?v="></script>
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','form', 'upload'], function() {
        var $ = layui.jquery, laydate = layui.laydate, form = layui.form, upload = layui.upload;

        upload.render({
            elem: '#test3',
            url: '{:url("admin/UploadFile/upload?thumb=no&water=no")}',
            accept: 'file', //普通文件
            size:"{:config('upload.upload_file_size')}",
            done: function(res){
                if(res.code == 1) { //上传成功
                    $('.attachment').val(res.data.file);
                    var att_name = $('.att_name').val();
                    att_name += "<a target='_blank' class='mcolor' href='"+res.data.file +"'>"+ res.data.name+"</a>";
                    $('.att_name').html(att_name);
                    $('#attachment_name').val(res.data.name);
                    layer.msg(res.msg);
                }else {
                    layer.msg(res.msg);
                }
            }
        });

        form.on('select(contract_cat)', function(data){
            var open_url = "{:url('getContractItem')}?cat_id="+data.value;
            $.post(open_url, function(data){
                $(".field-tpl_id").html(data);
                form.render('select');
            });
        });
        if (formData.cat_id){
            var open_url = "{:url('getContractItem')}?cat_id="+formData.cat_id+"&id="+formData.tpl_id;
            $.post(open_url, function(data){
                $(".field-tpl_id").html(data);
                form.render('select');
            });
        }
        form.on('select(tpl_id)', function(data){
            var open_url = "{:url('getItemById')}?id="+data.value;
            $.post(open_url, function(data){
                editor.insertHtml(data);//insertHtml和html有区别
            });
        });
    });

    function getIdcard(id) {
        var open_url = "{:url('getIdcard')}?subject_id="+id;
        $.post(open_url, function(data){
            $(".field-idcard").val(data);
        });
    }

    new SelectBox($('.box1'),{$project_select},function(result){
        if ('' != result.id){
            $('#project_name').val(result.name);
            $('#subject_id').val(result.id);
            getIdcard(result.id);
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
        defalut:'{$subject_name}',//默认显示内容。如果是'firstData',则默认显示第一个
        // allowInput:true,//是否允许输入
        width:300,//宽
        height:37,//高
        optionMaxHeight:300//下拉框最大高度
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>