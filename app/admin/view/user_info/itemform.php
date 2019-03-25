<style>
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-collapse" lay-accordion="" style="border-width: 0px;">
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">基本资料</h2>
                <div class="layui-colla-content layui-show">
                    <div class="layui-col-md6">
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
                    </div>
                    <div class="layui-col-md6">
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
                            <label class="layui-form-label">状&nbsp;&nbsp;&nbsp;&nbsp;态</label>
                            <div class="layui-input-inline">
                                <input type="radio" class="field-status" name="status" value="1" title="启用" checked>
                                <input type="radio" class="field-status" name="status" value="0" title="禁用">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">家庭情况</h2>
                <div class="layui-colla-content">
                    <div class="layui-form-item">
                        <label class="layui-form-label">主要成员</label>
                        <div class="layui-input-inline" style="width: 100px">
                            <input type="text" class="layui-input field-main_user[]" name="main_user[]"
                                   autocomplete="off" placeholder="姓名">
                        </div>
                        <div class="layui-input-inline" style="width: 100px">
                            <select name="relation_type[]" class="field-relation_type[]" type="select">
                                {$relation_type}
                            </select>
                        </div>
                        <div class="layui-input-inline" style="width: 100px">
                            <input type="number" class="layui-input field-user_age[]" name="user_age[]"
                                   autocomplete="off" placeholder="年龄">
                        </div>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input field-company_address[]" name="company_address[]"
                                   autocomplete="off" placeholder="工作单位或住址">
                        </div>
                        <div class="layui-input-inline" style="width: 150px">
                            <input type="number" class="layui-input field-user_phone[]" name="user_phone[]"
                                   autocomplete="off" placeholder="手机号码">
                        </div>
                    </div>
                    <div class="new_task">
                        <a href="javascript:void(0);" class="aicon ai-tianjia field-jiating-add" style="float: left;margin-left:950px;font-size: 30px;"></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">教育背景(由高到底)</h2>
                <div class="layui-colla-content">
                    <div class="layui-form-item">
                        <label class="layui-form-label">教育情况</label>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input field-education_school[]" name="education_school[]"
                                   autocomplete="off" placeholder="学校名称/专业">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-education_date[]" name="education_date[]"
                                   autocomplete="off" placeholder="起止时间">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-education_certificate[]" name="education_certificate[]"
                                   autocomplete="off" placeholder="获得证书情况">
                        </div>
                    </div>
                    <div class="new_task1">
                        <a href="javascript:void(0);" class="aicon ai-tianjia field-education-add" style="float: left;margin-left:950px;font-size: 30px;"></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">培训经历</h2>
                <div class="layui-colla-content">
                    <div class="layui-form-item">
                        <label class="layui-form-label">培训情况</label>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-train_school[]" name="train_school[]"
                                   autocomplete="off" placeholder="培训机构">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-train_name[]" name="train_name[]"
                                   autocomplete="off" placeholder="培训名称">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-train_date[]" name="train_date[]"
                                   autocomplete="off" placeholder="起止时间">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-train_certificate[]" name="train_certificate[]"
                                   autocomplete="off" placeholder="获得证书情况">
                        </div>
                    </div>
                    <div class="new_task2">
                        <a href="javascript:void(0);" class="aicon ai-tianjia field-train-add" style="float: left;margin-left:950px;font-size: 30px;"></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">工作经历(由近及远)</h2>
                <div class="layui-colla-content">
                    <div class="layui-form-item">
                        <label class="layui-form-label">工作情况</label>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-work_date[]" name="work_date[]"
                                   autocomplete="off" placeholder="起止时间">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-work_place[]" name="work_place[]"
                                   autocomplete="off" placeholder="工作单位及部门">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-work_station[]" name="work_station[]"
                                   autocomplete="off" placeholder="职务">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-work_reason[]" name="work_reason[]"
                                   autocomplete="off" placeholder="离职原因">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-work_man[]" name="work_man[]"
                                   autocomplete="off" placeholder="证明人/电话">
                        </div>
                    </div>
                    <div class="new_task3">
                        <a href="javascript:void(0);" class="aicon ai-tianjia field-work-add" style="float: left;margin-left:950px;font-size: 30px;"></a>
                    </div>
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
    </div>
</form>
{include file="block/layui" /}
<script src="__PUBLIC_JS__/jquery.select.js?v="></script>
<script src="__PUBLIC_JS__/SelectBox.min.js?v="></script>
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','element','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate,element = layui.element,form = layui.form;
        $(".field-idcard").blur(function () {
            var idcard = $(".field-idcard").val();
            var brithday = idcard.slice(6,10)+'-'+idcard.slice(10,12)+'-'+idcard.slice(12,14);
            $(".field-birthday").val(brithday);
        });

        $(".field-jiating-add").click(function(){
            $(".new_task").before("<div class=\"layui-form-item\">\n" +
                "                        <label class=\"layui-form-label\">主要成员</label>\n" +
                "                        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
                "                            <input type=\"text\" class=\"layui-input field-main_user[]\" name=\"main_user[]\"\n" +
                "                                   autocomplete=\"off\" placeholder=\"姓名\">\n" +
                "                        </div>\n" +
                "                        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
                "                            <select name=\"relation_type[]\" class=\"field-relation_type[]\" type=\"select\">\n" +
                "                                {$relation_type}\n" +
                "                            </select>\n" +
                "                        </div>\n" +
                "                        <div class=\"layui-input-inline\" style=\"width: 100px\">\n" +
                "                            <input type=\"number\" class=\"layui-input field-user_age[]\" name=\"user_age[]\"\n" +
                "                                   autocomplete=\"off\" placeholder=\"年龄\">\n" +
                "                        </div>\n" +
                "                        <div class=\"layui-input-inline\">\n" +
                "                            <input type=\"text\" class=\"layui-input field-company_address[]\" name=\"company_address[]\"\n" +
                "                                   autocomplete=\"off\" placeholder=\"工作单位或住址\">\n" +
                "                        </div>\n" +
                "                        <div class=\"layui-input-inline\" style=\"width: 150px\">\n" +
                "                            <input type=\"number\" class=\"layui-input field-user_phone[]\" name=\"user_phone[]\"\n" +
                "                                   autocomplete=\"off\" placeholder=\"手机号码\">\n" +
                "                        </div>\n" +
                "                    </div>");
            form.render();
        });

        $(".field-education-add").click(function(){
            $(".new_task1").before("<div class=\"layui-form-item\">\n" +
                "                        <label class=\"layui-form-label\">教育情况</label>\n" +
                "                        <div class=\"layui-input-inline\">\n" +
                "                            <input type=\"text\" class=\"layui-input field-education_school[]\" name=\"education_school[]\"\n" +
                "                                   autocomplete=\"off\" placeholder=\"学校名称/专业\">\n" +
                "                        </div>\n" +
                "                        <div class=\"layui-input-inline\" style=\"width: 230px\">\n" +
                "                            <input type=\"text\" class=\"layui-input field-education_date[]\" name=\"education_date[]\"\n" +
                "                                   autocomplete=\"off\" placeholder=\"起止时间\">\n" +
                "                        </div>\n" +
                "                        <div class=\"layui-input-inline\" style=\"width: 230px\">\n" +
                "                            <input type=\"text\" class=\"layui-input field-education_certificate[]\" name=\"education_certificate[]\"\n" +
                "                                   autocomplete=\"off\" placeholder=\"获得证书情况\">\n" +
                "                        </div>\n" +
                "                    </div>");
            form.render();
        });

        $(".field-train-add").click(function(){
            $(".new_task2").before("<div class=\"layui-form-item\">\n" +
                "                        <label class=\"layui-form-label\">培训情况</label>\n" +
                "                        <div class=\"layui-input-inline\" style=\"width: 230px\">\n" +
                "                            <input type=\"text\" class=\"layui-input field-train_school[]\" name=\"train_school[]\"\n" +
                "                                   autocomplete=\"off\" placeholder=\"培训机构\">\n" +
                "                        </div>\n" +
                "                        <div class=\"layui-input-inline\" style=\"width: 230px\">\n" +
                "                            <input type=\"text\" class=\"layui-input field-train_name[]\" name=\"train_name[]\"\n" +
                "                                   autocomplete=\"off\" placeholder=\"培训名称\">\n" +
                "                        </div>\n" +
                "                        <div class=\"layui-input-inline\" style=\"width: 230px\">\n" +
                "                            <input type=\"text\" class=\"layui-input field-train_date[]\" name=\"train_date[]\"\n" +
                "                                   autocomplete=\"off\" placeholder=\"起止时间\">\n" +
                "                        </div>\n" +
                "                        <div class=\"layui-input-inline\" style=\"width: 230px\">\n" +
                "                            <input type=\"text\" class=\"layui-input field-train_certificate[]\" name=\"train_certificate[]\"\n" +
                "                                   autocomplete=\"off\" placeholder=\"获得证书情况\">\n" +
                "                        </div>\n" +
                "                    </div>");
            form.render();
        });

        $(".field-work-add").click(function(){
            $(".new_task3").before("<div class=\"layui-form-item\">\n" +
                "                        <label class=\"layui-form-label\">工作情况</label>\n" +
                "                        <div class=\"layui-input-inline\" style=\"width: 230px\">\n" +
                "                            <input type=\"text\" class=\"layui-input field-work_date[]\" name=\"work_date[]\"\n" +
                "                                   autocomplete=\"off\" placeholder=\"起止时间\">\n" +
                "                        </div>\n" +
                "                        <div class=\"layui-input-inline\" style=\"width: 230px\">\n" +
                "                            <input type=\"text\" class=\"layui-input field-work_place[]\" name=\"work_place[]\"\n" +
                "                                   autocomplete=\"off\" placeholder=\"工作单位及部门\">\n" +
                "                        </div>\n" +
                "                        <div class=\"layui-input-inline\" style=\"width: 230px\">\n" +
                "                            <input type=\"text\" class=\"layui-input field-work_station[]\" name=\"work_station[]\"\n" +
                "                                   autocomplete=\"off\" placeholder=\"职务\">\n" +
                "                        </div>\n" +
                "                        <div class=\"layui-input-inline\" style=\"width: 230px\">\n" +
                "                            <input type=\"text\" class=\"layui-input field-work_reason[]\" name=\"work_reason[]\"\n" +
                "                                   autocomplete=\"off\" placeholder=\"离职原因\">\n" +
                "                        </div>\n" +
                "                        <div class=\"layui-input-inline\" style=\"width: 230px\">\n" +
                "                            <input type=\"text\" class=\"layui-input field-work_man[]\" name=\"work_man[]\"\n" +
                "                                   autocomplete=\"off\" placeholder=\"证明人/电话\">\n" +
                "                        </div>\n" +
                "                    </div>");
            form.render();
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
        width:300,//宽
        height:37,//高
        optionMaxHeight:300//下拉框最大高度
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>