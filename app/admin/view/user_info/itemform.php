<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">选择人员</label>
            <div class="layui-input-inline">
                <div class="layui-input-inline box box2">
                </div>
                <input id="real_name" type="hidden" class="field-real_name" name="real_name" value="{$Request.param.real_name}">
                <input id="user_id" type="hidden" class="field-user_id" name="user_id" value="{$Request.param.user_id}">
            </div>
            <div class="layui-form-mid red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">性别</label>
            <div class="layui-input-inline">
                <select name="sex" class="field-sex" type="select">
                    {$sex_type}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">婚姻状况</label>
            <div class="layui-input-inline">
                <select name="marital_status" class="field-marital_status" type="select">
                    {$marital_type}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">民族</label>
            <div class="layui-input-inline">
                <select name="nation" class="field-nation" type="select">
                    {$nation_type}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">身份证号</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-idcard" name="idcard" lay-verify="required|identity"
                       autocomplete="off" placeholder="请输入身份证号">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">生日</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-birthday" name="birthday" readonly
                       autocomplete="off" placeholder="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">户口所在地</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-address1" name="address1"
                       autocomplete="off" placeholder="请输入户口具体地址">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">现居住地址</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-address2" name="address2"
                       autocomplete="off" placeholder="请输入现居住地址">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">学历</label>
            <div class="layui-input-inline">
                <select name="education" class="field-education" type="select">
                    {$education_type}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">毕业院校</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-school" name="school"
                       autocomplete="off" placeholder="请输入毕业院校">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">学习专业</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-major" name="major"
                       autocomplete="off" placeholder="请输入所学专业">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">电子邮箱</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-email" name="email" lay-verify="required|email"
                       autocomplete="off" placeholder="请输入电子邮箱">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">紧急联系人</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-linkman" name="linkman" lay-verify="required"
                       autocomplete="off" placeholder="请输入紧急联系人">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系人电话</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input field-linkman_phone" name="linkman_phone" lay-verify="required|phone|number"
                       autocomplete="off" placeholder="请输入联系人电话">
            </div>
            <div class="layui-form-mid" style="color: red">*</div>
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

    layui.use(['jquery', 'laydate'], function() {
        var $ = layui.jquery, laydate = layui.laydate;
        $(".field-idcard").blur(function () {
            var idcard = $(".field-idcard").val();
            var brithday = idcard.slice(6,10)+'-'+idcard.slice(10,12)+'-'+idcard.slice(12,14);
            $(".field-birthday").val(brithday);
        });
    });

    new SelectBox($('.box2'),{$user_select},function(result){
        if ('' != result.id){
            $('#real_name').val(result.name);
            $('#user_id').val(result.id);
        }
    },{
        dataName:'realname',//option的html
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
        placeholder:'输入关键字',//默认提示
        defalut:'{$real_name}',//默认显示内容。如果是'firstData',则默认显示第一个
        // allowInput:true,//是否允许输入
        width:200,//宽
        height:37,//高
        optionMaxHeight:300//下拉框最大高度
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>