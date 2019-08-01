<div class="layui-tab-item layui-show layui-form-pane">
    <div style="text-align: center">
        <span style="font-size: large">{$data_info['title']}</span><br>
        <span>{$data_info['update_time']}</span><br>
    </div>
    <hr>
    <div>
        {$data_info['content']}
    </div>
    <div>
        {notempty name="data_info['attachment']"}
        {volist name="data_info['attachment']" id="vo"}
        <a target='_blank' class='mcolor' href="{$vo}" >附件{$i}</a><br>
        {/volist}
        {/notempty}
    </div>
</div>
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