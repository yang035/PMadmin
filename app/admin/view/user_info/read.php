<link rel="stylesheet" href="__ADMIN_JS__/pictureViewer/css/pictureViewer.css">
<style>
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
    #pictureViewer > .content{
        background-color: #fff;
        position: absolute;
        width: 590px;
        height: 450px;
        margin: auto;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
</style>
<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-collapse" lay-accordion="" style="border-width: 0px;">
            <div class="layui-colla-item">
                <h2 class="layui-colla-title"><div class="layui-form-mid red">基本资料*</div></h2>
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
                            <div class="layui-form-mid red">*</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">婚姻状况</label>
                            <div class="layui-input-inline">
                                <select name="marital_status" class="field-marital_status" type="select">
                                    {$marital_type}
                                </select>
                            </div>
                            <div class="layui-form-mid red">*</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">民族</label>
                            <div class="layui-input-inline">
                                <select name="nation" class="field-nation" type="select">
                                    {$nation_type}
                                </select>
                            </div>
                            <div class="layui-form-mid red">*</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">身份证号</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input field-idcard" name="idcard" lay-verify="required|identity"
                                       autocomplete="off" maxlength="18" placeholder="请输入身份证号">
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
                            <div class="layui-form-mid red">*</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">现居住地址</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input field-address2" name="address2"
                                       autocomplete="off" placeholder="请输入现居住地址">
                            </div>
                            <div class="layui-form-mid red">*</div>
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
                            <div class="layui-form-mid red">*</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">毕业院校</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input field-school" name="school"
                                       autocomplete="off" placeholder="请输入毕业院校">
                            </div>
                            <div class="layui-form-mid red">*</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">学习专业</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input field-major" name="major"
                                       autocomplete="off" placeholder="请输入所学专业">
                            </div>
                            <div class="layui-form-mid red">*</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">电子邮箱</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input field-email" name="email"
                                       autocomplete="off" placeholder="请输入电子邮箱">
                            </div>
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
                                       autocomplete="off" maxlength="11" placeholder="请输入联系人电话">
                            </div>
                            <div class="layui-form-mid" style="color: red">*</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">二寸照片</label>
                            <div class="layui-input-inline upload">
                                <input type="hidden" class="upload-input field-thumb" name="thumb" value="">
                                <img id="thumb" src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
                            </div>
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
                <h2 class="layui-colla-title"><div class="layui-form-mid red">补充资料*</div></h2>
                <div class="layui-colla-content">
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">员工性质</label>
                            <div class="layui-input-inline">
                                <select name="man_type" class="field-man_type" type="select">
                                    {$man_type}
                                </select>
                            </div>
                            <div class="layui-form-mid red">*</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">工资卡开户行</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input field-open_bank" name="open_bank"
                                       autocomplete="off" placeholder="工资卡开户行">
                            </div>
                            <div class="layui-form-mid red">*</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">工资卡号</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input field-bank_num" name="bank_num"
                                       autocomplete="off" placeholder="工资卡号">
                            </div>
                            <div class="layui-form-mid red">*</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">入职时间</label>
                            <div class="layui-input-inline" style="width: 250px">
                                <input type="text" class="layui-input field-start_date" name="start_date" autocomplete="off" readonly placeholder="入职时间">
                            </div>
                            <div class="layui-form-mid red">*</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">离职时间</label>
                            <div class="layui-input-inline" style="width: 250px">
                                <input type="text" class="layui-input field-end_date" name="end_date" autocomplete="off" readonly placeholder="离职时间">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">合同开始时间</label>
                            <div class="layui-input-inline" style="width: 250px">
                                <input type="text" class="layui-input field-contract_start_date" name="contract_start_date" autocomplete="off" readonly placeholder="合同开始时间">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">合同结束时间</label>
                            <div class="layui-input-inline" style="width: 250px">
                                <input type="text" class="layui-input field-contract_end_date" name="contract_end_date" autocomplete="off" readonly placeholder="合同结束时间">
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md6">
                        <div class="layui-form-item">
                            <label class="layui-form-label">社保</label>
                            <div class="layui-input-inline">
                                <input type="radio" class="field-social" name="social" value="1" title="有" checked>
                                <input type="radio" class="field-social" name="social" value="0" title="无">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">社保开始时间</label>
                            <div class="layui-input-inline" style="width: 250px">
                                <input type="text" class="layui-input field-social_start_date" name="social_start_date" autocomplete="off" readonly placeholder="社保开始时间">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">社保结束时间</label>
                            <div class="layui-input-inline" style="width: 250px">
                                <input type="text" class="layui-input field-social_end_date" name="social_end_date" autocomplete="off" readonly placeholder="社保结束时间">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">公积金</label>
                            <div class="layui-input-inline">
                                <input type="radio" class="field-accumulation" name="accumulation" value="1" title="有" checked>
                                <input type="radio" class="field-accumulation" name="accumulation" value="0" title="无">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">公积金开始时间</label>
                            <div class="layui-input-inline" style="width: 250px">
                                <input type="text" class="layui-input field-accumulation_start_date" name="accumulation_start_date" autocomplete="off" readonly placeholder="公积金开始时间">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">公积金结束时间</label>
                            <div class="layui-input-inline" style="width: 250px">
                                <input type="text" class="layui-input field-accumulation_end_date" name="accumulation_end_date" autocomplete="off" readonly placeholder="公积金结束时间">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">家庭情况</h2>
                <div class="layui-colla-content">
                    {notempty name="data_info['jiating']"}
                    {volist name="data_info['jiating']" id="vo"}
                    <div class="layui-form-item">
                        <label class="layui-form-label">主要成员</label>
                        <div class="layui-input-inline" style="width: 100px">
                            <input type="text" class="layui-input field-main_user[]" name="main_user[]"
                                   autocomplete="off" placeholder="姓名" value="{$vo['main_user']}">
                        </div>
                        <div class="layui-input-inline" style="width: 100px">
                            <select name="relation_type[]" class="field-relation_type[]" type="select">
                                <option value="1" {eq name="vo['relation_type']" value="1"} selected {/eq}>父母</option>
                                <option value="2" {eq name="vo['relation_type']" value="2"} selected {/eq}>夫妻</option>
                                <option value="3" {eq name="vo['relation_type']" value="3"} selected {/eq}>子女</option>
                            </select>
                        </div>
                        <div class="layui-input-inline" style="width: 100px">
                            <input type="number" class="layui-input field-user_age[]" name="user_age[]"
                                   autocomplete="off" placeholder="年龄" value="{$vo['user_age']}">
                        </div>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input field-company_address[]" name="company_address[]"
                                   autocomplete="off" placeholder="工作单位或住址" value="{$vo['company_address']}">
                        </div>
                        <div class="layui-input-inline" style="width: 150px">
                            <input type="number" class="layui-input field-user_phone[]" name="user_phone[]"
                                   autocomplete="off" placeholder="手机号码" value="{$vo['user_phone']}">
                        </div>
                    </div>
                    {/volist}
                    {/notempty}
                </div>
            </div>
            <hr>
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">教育背景(由高到底)</h2>
                <div class="layui-colla-content">
                    {notempty name="data_info['jiaoyu']"}
                    {volist name="data_info['jiaoyu']" id="vo"}
                    <div class="layui-form-item">
                        <label class="layui-form-label">教育情况</label>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input field-education_school[]" name="education_school[]"
                                   autocomplete="off" placeholder="学校名称/专业" value="{$vo['education_school']}">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-education_date[]" name="education_date[]"
                                   autocomplete="off" placeholder="起止时间" value="{$vo['education_date']}">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-education_certificate[]" name="education_certificate[]"
                                   autocomplete="off" placeholder="获得证书情况" value="{$vo['education_certificate']}">
                        </div>
                    </div>
                    {/volist}
                    {/notempty}
                </div>
            </div>
            <hr>
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">培训经历</h2>
                <div class="layui-colla-content">
                    {notempty name="data_info['peixun']"}
                    {volist name="data_info['peixun']" id="vo"}
                    <div class="layui-form-item">
                        <label class="layui-form-label">培训情况</label>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-train_school[]" name="train_school[]"
                                   autocomplete="off" placeholder="培训机构" value="{$vo['train_school']}">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-train_name[]" name="train_name[]"
                                   autocomplete="off" placeholder="培训名称" value="{$vo['train_name']}">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-train_date[]" name="train_date[]"
                                   autocomplete="off" placeholder="起止时间" value="{$vo['train_date']}">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-train_certificate[]" name="train_certificate[]"
                                   autocomplete="off" placeholder="获得证书情况" value="{$vo['train_certificate']}">
                        </div>
                    </div>
                    {/volist}
                    {/notempty}
                </div>
            </div>
            <hr>
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">各种证书附件</h2>
                <div class="layui-colla-content">
                    {notempty name="data_info['attachment_show']"}
                    <div class="image-list">
                        {volist name="data_info['attachment_show']" id="vo"}
                        <div class="cover"><img src="{$vo}" style="height: 30px;width: 30px;"></div>
                        {/volist}
                    </div>
                    {/notempty}
                </div>
            </div>
            <hr>
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">工作经历(由近及远)</h2>
                <div class="layui-colla-content">
                    {notempty name="data_info['gongzuo']"}
                    {volist name="data_info['gongzuo']" id="vo"}
                    <div class="layui-form-item">
                        <label class="layui-form-label">工作情况</label>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-work_date[]" name="work_date[]"
                                   autocomplete="off" placeholder="起止时间" value="{$vo['work_date']}">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-work_place[]" name="work_place[]"
                                   autocomplete="off" placeholder="工作单位及部门" value="{$vo['work_place']}">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-work_station[]" name="work_station[]"
                                   autocomplete="off" placeholder="职务" value="{$vo['work_station']}">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-work_reason[]" name="work_reason[]"
                                   autocomplete="off" placeholder="离职原因" value="{$vo['work_reason']}">
                        </div>
                        <div class="layui-input-inline" style="width: 230px">
                            <input type="text" class="layui-input field-work_man[]" name="work_man[]"
                                   autocomplete="off" placeholder="证明人/电话" value="{$vo['work_man']}">
                        </div>
                    </div>
                    {/volist}
                    {/notempty}
                </div>
            </div>
        </div>
        {if condition="1 == $Request.param.p"}
        <hr>
        <div class="layui-form-item">
            <label class="layui-form-label">审核</label>
            <div class="layui-input-inline">
                <input type="radio" class="field-check_status" name="check_status" value="1" title="同意" checked lay-filter="check_status">
                <input type="radio" class="field-check_status" name="check_status" value="2" title="驳回" lay-filter="check_status">
            </div>
        </div>
        <div class="layui-form-item" style="display: none" id="remark">
            <label class="layui-form-label">意见</label>
            <div class="layui-input-inline">
                <textarea  class="layui-textarea field-remark" name="remark" autocomplete="off" placeholder=""></textarea>
            </div>
        </div>
        {/if}
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input type="hidden" class="field-id" name="id">
                {if condition="1 == $Request.param.p"}
                <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
                {/if}
                <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
            </div>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script src="__ADMIN_JS__/pictureViewer/js/pictureViewer.js"></script>
<script src="__ADMIN_JS__/pictureViewer/js/jquery.mousewheel.min.js"></script>
<script src="__PUBLIC_JS__/jquery.select.js?v="></script>
<script src="__PUBLIC_JS__/SelectBox.min.js?v="></script>
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','element', 'upload','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate,element = layui.element,upload = layui.upload,form = layui.form;
        $(".field-idcard").blur(function () {
            var idcard = $(".field-idcard").val();
            var brithday = idcard.slice(6,10)+'-'+idcard.slice(10,12)+'-'+idcard.slice(12,14);
            $(".field-birthday").val(brithday);
        });

        $('.image-list').on('click', '.cover', function () {
            var this_ = $(this);
            var images = this_.parents('.image-list').find('.cover');
            var imagesArr = new Array();
            $.each(images, function (i, image) {
                imagesArr.push($(image).children('img').attr('src'));
            });
            $.pictureViewer({
                images: imagesArr, //需要查看的图片，数据类型为数组
                initImageIndex: this_.index() + 1, //初始查看第几张图片，默认1
                scrollSwitch: true //是否使用鼠标滚轮切换图片，默认false
            });
        });

        var uploadOneIns = upload.render({
            elem: '#oneImage',
            url: '{:url("admin/UploadFile/upload?group=front")}',
            method: 'post',
            size:120,
            before: function(input) {
                layer.msg('文件上传中...', {time:3000000});
            },
            done: function(res, index, upload) {
                var obj = this.item;
                if (res.code == 0) {
                    layer.msg(res.msg);
                    return false;
                }
                layer.closeAll();
                var input = $(obj).parents('.upload').find('.upload-input');
                if ($(obj).attr('lay-type') == 'image') {
                    input.siblings('img').attr('src', res.data.file).show();
                }
                input.val(res.data.file);
            }
        });
        $('#thumb').attr('src', $('.field-thumb').val()).show();

        form.on('radio(check_status)', function(data){
            if (2 == data.value){
                $('#remark').show();
            } else {
                $('#remark').hide();
            }
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