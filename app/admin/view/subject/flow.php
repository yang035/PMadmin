<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        {volist name="flow" id="f"}
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>{$flow_cat[$key]}</legend>
        </fieldset>
        {eq name="key" value="1"}
        {volist name="f" id="f1"}
        <dt>
            <input type="checkbox" name="flow[]" value="{$key}"  checked lay-skin="primary" title="{$f1}" disabled>
            ({$row[$key]})
        </dt>
        {/volist}
        {else/}
            {volist name="f" id="f1"}
            <dt>
                <input type="checkbox" name="flow[]" value="{$key}" {notempty name="subject_flow[$key]"}checked{/notempty} lay-skin="primary" title="{$f1}" lay-filter="flow[]">
            </dt>
            {notempty name="subject_flow[$key]"}
            <ul class="layui-timeline" style="padding-left:30px">
                {volist name="subject_flow[$key]" id="f2"}
                <li class="layui-timeline-item">
                    <i class="layui-icon layui-timeline-axis"></i>
                    <div class="layui-timeline-content layui-text">
                        <div class="layui-timeline-title">{$f2['remark']}    <a href="{$f2['file']}" target="_blank">{$f2['name']}</a>    {$f2['create_time']}</div>
                    </div>
                </li>
                {/volist}
            </ul>
            {/notempty}
            {/volist}
        {/eq}
        {/volist}
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};

    layui.use(['jquery', 'laydate','form'], function() {
        var $ = layui.jquery, laydate = layui.laydate, form = layui.form;
        laydate.render({
            elem: '.field-expire_time',
            min:'0'
        });

        $('#reset_expire').on('click', function(){
            $('input[name="expire_time"]').val(0);
        });

        form.on('checkbox(flow[])', function(data){
            // console.log(data.elem); //得到checkbox原始DOM对象
            // console.log(data.elem.checked); //是否被选中，true或者false
            // console.log(data.value); //复选框value值，也可以通过data.elem.value得到
            // console.log(data.othis); //得到美化后的DOM对象
            if (data.elem.checked){
                add_content(data.value,data.elem.title);
            }
        });
    });
    function add_content(flow_id,title) {
        var open_url = "{:url('addContent')}?subject_id={$Request.param.id}"+"&flow_id="+flow_id+"&title="+title;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :title,
            maxmin: true,
            area: ['900px', '600px'],
            content: open_url,
        });
    }
</script>
<script src="__ADMIN_JS__/footer.js"></script>