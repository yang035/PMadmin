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
            事件名称：{$data_list['rid']['fullname']}<br>
            说明：{$data_list['remark']}<br>
            奖扣人：{$data_list['score_user']}<br>
            GL值：{$data_list['rid']['score']}（斗）<br>
            审批人：{$data_list['send_user']}<br>
            抄送人：{$data_list['copy_user']}<br>
            添加人：{$data_list['user_id']}<br>
            更新时间：{$data_list['update_time']}<br>
            {if condition="($data_list['status'] eq 1) && ($Request.param.atype eq 2) "}
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
                <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
                <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
            </div>
        </div>
            {else/}
            <br>
            结果：{$approval_status[$data_list['status']]}<br>
            意见：{$data_list['mark']}<br>
            {/if}
        </div>
    </div>
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

    });

</script>
<script src="__ADMIN_JS__/footer.js"></script>