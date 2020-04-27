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
<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <label class="layui-form-label">项目名</label>
        <div class="layui-form-mid" style="color: red">{$Request.param.name}</div>
        <input type="hidden" name="subject_id" value="{$Request.param.id}">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">开始时间</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-begin_date" name="begin_date" lay-verify="required" readonly autocomplete="off" placeholder="选择开始时间">
        </div>
        <div class="layui-form-mid red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">截止时间</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-end_date" name="end_date" lay-verify="required" readonly autocomplete="off" placeholder="选择结束时间">
        </div>
        <div class="layui-form-mid red">*</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">剩余工作量</label>
        <div class="layui-input-inline">
            <input type="number" class="layui-input field-remain_work" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" name="remain_work" lay-verify="required" autocomplete="off" value="100">
        </div>
        <div class="layui-form-mid red">%  *</div>
    </div>
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">阶段</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <select name="part" class="field-part" type="select" lay-filter="part" lay-verify="required">-->
<!--                {$part_option}-->
<!--            </select>-->
<!--        </div>-->
<!--        <div class="layui-form-mid" style="color: red">*</div>-->
<!--    </div>-->
<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">阶段系数</label>-->
<!--        <div class="layui-input-inline">-->
<!--            <input type="text" class="layui-input field-part_ratio" name="part_ratio" value="1.0" lay-verify="required" autocomplete="off" placeholder="请输入系数">-->
<!--        </div>-->
<!--        <div class="layui-form-mid" style="color: red">*</div>-->
<!--    </div>-->
    <div class="layui-form-item">
        <label class="layui-form-label">GL影响</label>
        <div class="layui-input-inline">
            <input type="radio" class="field-is_gl" name="is_gl" value="1" title="是" checked>
            <input type="radio" class="field-is_gl" name="is_gl" value="0" title="否">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">附件1内容<span style="color: red">*</span></label>
        <div class="layui-input-block">
            <textarea id="ckeditor" name="att1" class="field-att1" lay-verify="required"></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">附件2内容<span style="color: red">*</span></label>
        <div class="layui-input-block">
            <textarea id="ckeditor2" name="att2" class="field-att2" lay-verify="required"></textarea>
        </div>
    </div>
    {:editor(['ckeditor', 'ckeditor2'],'kindeditor')}
    <div class="layui-form-item">
        <div class="layui-input-block">
            <a href="javascript:history.back();" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
            <a href="#" onclick="yulan()" class="layui-btn layui-btn-normal">提交</a>
<!--            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>-->
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
var formData = {:json_encode($data_info)};

layui.use(['jquery', 'laydate','upload','form','element'], function() {
    var $ = layui.jquery, laydate = layui.laydate,upload = layui.upload,form = layui.form,element = layui.element;
    laydate.render({
        elem: '.field-begin_date',
        type: 'date',
        trigger: 'click',
        value: "{$time['start_time']}",
    });

    laydate.render({
        elem: '.field-end_date',
        type: 'date',
        trigger: 'click',
        value: "{$time['end_time']}",
    });

    element.render();
    form.render();
});

function yulan(){
    var open_url = "{:url('editX')}",data = $("form").serialize();
    $.post(open_url, data,function(res) {
        if (res.code == 1) {
            xieyi(res.data.xieyi_id)
        }else {
            layer.alert(res.msg);
        }
    });
}

function xieyi(xieyi_id){
    var open_url = "{:url('editXieyi')}?xieyi_id="+xieyi_id;
    if (open_url.indexOf('?') >= 0) {
        open_url += '&hisi_iframe=yes';
    } else {
        open_url += '?hisi_iframe=yes';
    }
    layer.open({
        type:2,
        title :'详情',
        maxmin: true,
        area: ['1000px', '600px'],
        content: open_url,
        success:function (layero, index) {
        }
    });
}
</script>
<script src="__ADMIN_JS__/footer.js"></script>