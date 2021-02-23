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
<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">手机号码</label>
            <div class="layui-input-inline" style="width: 500px">
                <textarea  class="layui-textarea field-mobile" name="mobile" lay-verify="required" onkeyup="value=value.replace(/[^\d;]/g,'')" autocomplete="off" placeholder="若是多个手机号码，用英文';'隔开"></textarea>
            </div>
            <div class="layui-form-mid red">若是多个手机号码，用英文';'隔开*</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发送内容</label>
            <div class="layui-input-inline" style="width: 500px">
                <textarea  class="layui-textarea field-content" name="content" lay-verify="required" autocomplete="off" placeholder="发送内容"></textarea>
            </div>
            <div class="layui-form-mid red">*</div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">发送</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate'], function() {
        var $ = layui.jquery, laydate = layui.laydate;
        laydate.render({
            elem: '.field-expire_time',
            min:'0'
        });

        $('#reset_expire').on('click', function(){
            $('input[name="expire_time"]').val(0);
        });
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>