<link rel="stylesheet" href="__ADMIN_JS__/pictureViewer/css/pictureViewer.css">
<style>
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
    .layui-btn {
        display: inline-block;
        height: 38px;
        line-height: 38px;
        padding: 0 18px;
        background-color: #009688;
        color: #fff;
        white-space: nowrap;
        text-align: center;
        font-size: 14px;
        border: none;
        border-radius: 2px;
        margin-top: -28px;
        cursor: pointer;
    }
</style>
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            姓名：{$data_list['real_name']}<br>
            开始时间：{$data_list['start_time']}<br>
            结束时间：{$data_list['end_time']}<br>
            {switch name="class_type"}
            {case value="1"}
            请假类型：{$leave_type[$data_list['type']]}<br>
            {/case}
            {case value="2"}
            报销类型：{$expense_type[$data_list['type']]}<br>
            报销明细：
            {volist name="$data_list['detail']" id="vo"}
            {$vo['amount']}元(说明：{$vo['mark']})&nbsp;&nbsp;|&nbsp;
            {/volist}
            <br>
            合计：{$data_list['total']}元<br>
            {/case}
            {case value="3"}
            请假类型：{$leave_type[$data_list['type']]}<br>
            {/case}
            {case value="4"}
            地点：{$data_list['address']}<br>
            {/case}
            {case value="5"}
            物品名称：{$data_list['name']}<br>
            数量：{$data_list['number']}<br>
            总价：{$data_list['amount']}元<br>
            {/case}
            {case value="6"}
            加班时长：{$data_list['time_long']}小时<br>
            {/case}
            {case value="7"}
            外出地点：{$data_list['address']}<br>
            外出时长：{$data_list['time_long']}小时<br>
            {/case}
            {case value="8"}
            司机：{$data_list['deal_user']}<br>
            车辆类型：{$car_type[$data_list['car_type']]}<br>
            发车前照片：
            {notempty name="data_list['before_img']"}
            <div class="image-list">
                {volist name="data_list['before_img']" id="vo"}
                <div class="cover"><img src="{$vo}" style="height: 30px;width: 30px;"></div>
                {/volist}
            </div>
            {else/}
            <span>无</span>
            {/notempty}
            回来后照片：
            {notempty name="data_list['after_img']"}
            <div class="image-list">
                {volist name="data_list['after_img']" id="vo"}
                <div class="cover"><img src="{$vo}" style="height: 30px;width: 30px;"></div>
                {/volist}
            </div>
            {else/}
            <span>无</span>
            {/notempty}
            {/case}
            {case value="9"}
            {/case}
            {case value="10"}
            {/case}
            {case value="11"}
            {notempty name="data_list['goods']"}
            物品清单：
            <div>
                {volist name="data_list['goods']" id="vo"}
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$vo['name']}:{$vo['number']}<br>
                {/volist}
            </div>
            {else/}
            <span>无</span>
            {/notempty}
            {/case}
            {/switch}
            事由：{$data_list['reason']}<br>
            附件说明：
            {notempty name="data_list['attachment'][0]"}
            <div class="image-list">
                {volist name="data_list['attachment']" id="vo"}
                <div class="cover"><img src="{$vo}" style="height: 30px;width: 30px;"></div>
                {/volist}
            </div>
            {else/}
            <span>无</span>
            {/notempty}
            {if condition="($data_list['status'] eq 1) && ($Request.param.atype eq 3) "}
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input type="radio" name="status" value="2" title="同意" checked>
                <input type="radio" name="status" value="4" title="驳回">
            </div>
        </div>
            <div class="layui-form-item">
                <label class="layui-form-label">意见</label>
                <div class="layui-input-inline">
                    <textarea type="text" class="layui-textarea field-mark" name="mark" autocomplete="off" placeholder="请输入说明"></textarea>
                </div>
            </div>
            <br>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
                <input type="hidden" class="field-atype" name="atype" value="{$Request.param.atype}">
                <input type="hidden" class="field-class_type" name="class_type" value="{$Request.param.class_type}">
                <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
                <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
            </div>
        </div>
            {else/}
            <br>
            结果：{$approval_status[$data_list['status']]}<br>
            意见：{$data_list['mark']}<br>
            {/if}
        </div>
    </div>
    {if condition="($Request.param.class_type eq 8) && ($Request.param.atype eq 5) "}
    {empty name="$data_list['before_img']"}
    <div class="layui-form-item">
        <label class="layui-form-label" >发车前</label>
        <div class="layui-input-block upload">
            <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="img1">左前方照片</button>
            <input type="hidden" class="upload-input field-before_img" name="before_img[]" value="">
            <img src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
        </div>
        <div class="layui-input-block upload">
            <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="img2">右前方照片</button>
            <input type="hidden" class="upload-input field-before_img" name="before_img[]" value="">
            <img src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
        </div>
        <div class="layui-input-block upload">
            <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="img3">后面照片</button>
            <input type="hidden" class="upload-input field-before_img" name="before_img[]" value="">
            <img src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
        </div>
        <div class="layui-input-block upload">
            <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="img4">中控照片</button>
            <input type="hidden" class="upload-input field-before_img" name="before_img[]" value="">
            <img src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
        </div>
    </div>
    {/empty}
    {if condition="!empty($data_list['before_img']) && empty($data_list['after_img']) "}
    <div class="layui-form-item">
        <label class="layui-form-label" >回来后</label>
        <div class="layui-input-block upload">
            <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="img11">左前方照片</button>
            <input type="hidden" class="upload-input field-after_img" name="after_img[]" value="">
            <img src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
        </div>
        <div class="layui-input-block upload">
            <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="img22">右前方照片</button>
            <input type="hidden" class="upload-input field-after_img" name="after_img[]" value="">
            <img src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
        </div>
        <div class="layui-input-block upload">
            <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="img33">后面照片</button>
            <input type="hidden" class="upload-input field-after_img" name="after_img[]" value="">
            <img src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
        </div>
        <div class="layui-input-block upload">
            <button type="button" name="upload" class="layui-btn layui-btn-primary layui-upload" lay-type="image" lay-data="{exts:'{:str_replace(',', '|', config('upload.upload_image_ext'))}', accept:'file'}" id="img44">中控照片</button>
            <input type="hidden" class="upload-input field-after_img" name="after_img[]" value="">
            <img src="" style="display:none;border-radius:5px;border:1px solid #ccc" width="36" height="36">
        </div>
    </div>
    {/if}
    <br>
    {if condition="empty($data_list['before_img']) || empty($data_list['after_img']) "}
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
            <input type="hidden" class="field-atype" name="atype" value="{$Request.param.atype}">
            <input type="hidden" class="field-class_type" name="class_type" value="{$Request.param.class_type}">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
    {/if}
    {/if}
</form>
{include file="block/layui" /}
<script src="__ADMIN_JS__/pictureViewer/js/pictureViewer.js"></script>
<script src="__ADMIN_JS__/pictureViewer/js/jquery.mousewheel.min.js"></script>
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','upload'], function() {
        var $ = layui.jquery, laydate = layui.laydate, upload = layui.upload;
        laydate.render({
            elem: '.field-expire_time',
            min:'0'
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

        upload.render({
            elem: '#img1',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
            method: 'post',
            size:"{:config('upload.upload_image_size')}",
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
        upload.render({
            elem: '#img2',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
            method: 'post',
            size:"{:config('upload.upload_image_size')}",
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
        upload.render({
            elem: '#img3',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
            method: 'post',
            size:"{:config('upload.upload_image_size')}",
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
        upload.render({
            elem: '#img4',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
            method: 'post',
            size:"{:config('upload.upload_image_size')}",
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
        upload.render({
            elem: '#img11',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
            method: 'post',
            size:"{:config('upload.upload_image_size')}",
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
        upload.render({
            elem: '#img22',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
            method: 'post',
            size:"{:config('upload.upload_image_size')}",
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
        upload.render({
            elem: '#img33',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
            method: 'post',
            size:"{:config('upload.upload_image_size')}",
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
        upload.render({
            elem: '#img44',
            url: '{:url("admin/UploadFile/upload?group=sys")}',
            method: 'post',
            size:"{:config('upload.upload_image_size')}",
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
        $('.upload img').attr('src', $('.field-img').val()).show();
    });

</script>
<script src="__ADMIN_JS__/footer.js"></script>