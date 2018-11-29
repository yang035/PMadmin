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
            车辆类型：{$car_type[$data_list['car_type']]}<br>
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
            <div class="layui-input-block">
                <input type="hidden" class="field-id" name="id" value="{$Request.param.id}">
                <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            </div>
        </div>
            {else/}
            <br>
            结果：{$approval_status[$data_list['status']]}
            {/if}
        </div>
    </div>
</form>
{include file="block/layui" /}
<script src="__ADMIN_JS__/pictureViewer/js/pictureViewer.js"></script>
<script src="__ADMIN_JS__/pictureViewer/js/jquery.mousewheel.min.js"></script>
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate'], function() {
        var $ = layui.jquery, laydate = layui.laydate;
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