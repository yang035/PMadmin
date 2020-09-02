<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-form-item">
        <label class="layui-form-label">员工姓名</label>
        <div class="layui-form-mid" style="color: red">{$data_info['realname']}</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">离职日期</label>
        <div class="layui-input-inline" style="width: 250px">
            <input type="text" class="layui-input field-end_date" name="end_date" autocomplete="off" readonly placeholder="日期">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <a onclick="step1()" class="layui-btn layui-btn-normal">下一步</a>
            <a href="javascript:history.back();" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
    layui.use(['jquery', 'laydate','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate,form = layui.form;
        laydate.render({
            elem: '.field-end_date',
            type: 'date',
            calendar: true,
            trigger: 'click',
            value: new Date(),
        });
    });

    function step1(){
        var  id={$Request.param.id};
        var  end_date=$("input[name='end_date']").val();
        var open_url = "{:url('certificate')}?id="+id+"&end_date="+end_date;
        window.location.href = open_url;
    }
</script>
<script src="__ADMIN_JS__/footer.js"></script>