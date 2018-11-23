{include file="block/layui" /}
{volist name="panel_type" id="vo"}
<div class="layui-col-xs3 layui-col-sm3 layui-col-md3">
<div class="layui-card" style="margin: 10px">
    <a class="layui-btn layui-btn-lg layui-btn-normal layui-btn-radius" href="{:url($vo['href'],['class_type'=>$key])}">{$vo['title']}</a>
</div>
</div>
{/volist}
<script>
    var formData = {:json_encode($data_info)};
    layui.use(['jquery', 'laydate'], function() {
        var $ = layui.jquery, laydate = layui.laydate;
        laydate.render({
            elem: '.field-expire_time',
            min:'0'
        });


    });
</script>