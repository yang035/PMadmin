<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>名称：</b>{$data_list['name']}<br>
            <b>缩略图：</b><img src="{$data_list['thumb']}" width="50" height="50" alt="{$data_list['name']}"><br>
            <b>麦粒兑换：</b>{$data_list['score']}(斗)<br>
            <b>等价于：</b>{$data_list['marketprice']}(元)<br>
            <b>额外支付：</b>{$data_list['other_price']}(元)<br>
            <b>描述：</b>{$data_list['remark']}<br>
            <b>更新时间：</b>{$data_list['update_time']}<br>
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
            <textarea  class="layui-textarea field-yijian" name="yijian" autocomplete="off" placeholder=""></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="javascript:history.back()" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
    {/if}
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_list)};
    layui.use(['jquery', 'laydate','element', 'upload','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate,element = layui.element,upload = layui.upload,form = layui.form;
        form.on('radio(check_status)', function(data){
            if (2 == data.value){
                $('#remark').show();
            } else {
                $('#remark').hide();
            }
        });
    });
</script>
<script src="__ADMIN_JS__/footer.js"></script>