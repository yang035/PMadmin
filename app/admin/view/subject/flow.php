<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        {volist name="flow" id="f"}
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
            <legend>{$flow_cat[$key]} {notempty name="score_arr[$key]"}({$score_arr[$key]}%){/notempty}</legend>
        </fieldset>
        {eq name="key" value="1"}
        {volist name="f" id="f1"}
        <dt>
            <input type="checkbox" name="flow[]" value="{$key}"  checked lay-skin="primary" title="{$f1['name']}" disabled>
            ({$row[$key]})
        </dt>
        {/volist}
        {else/}
            {volist name="f" id="f1"}
            <dt>
                <input type="checkbox" name="flow[]" value="{$key}" {notempty name="subject_flow[$key]"}checked{/notempty} lay-skin="primary" placeholder="{$f1['ratio']}" title="{$f1['name']}" lay-filter="flow[]">
            </dt>
            {notempty name="subject_flow[$key]"}
            <ul class="layui-timeline" style="padding-left:30px">
                {volist name="subject_flow[$key]" id="f2"}
                <li class="layui-timeline-item">
                    <i class="layui-icon layui-timeline-axis"></i>
                    <div class="layui-timeline-content layui-text">
                        <div class="layui-timeline-title">
                            {if condition="(1 == $f2['flag']) && (0 == $f2['agree'])"}
                                {eq name="$f1['ratio']" value="0"}
                                <button onclick="agree({$f2['id']},{$f1['ratio']})" type="button" class="layui-btn layui-btn-sm">确认</button>
                                {else/ }
                                <button onclick="agree1({$f2['id']},{$f1['ratio']})" type="button" class="layui-btn layui-btn-sm">确认</button>
                                {/eq}
                            {/if}
                            {$f2['remark']}--
                            {volist name="f2['attachment']" id='f3'}
                            <a href="{$f3}" target="_blank">附件{$i}</a>
                            {/volist}
                            --{$f2['create_time']} {notempty name="f2['ratio']"}--<span class="ratio red">{$f2['ratio']}</span>%{/notempty}</div>
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
    var formData = {:json_encode($row)};

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
                add_content(data.value,data.elem.title,data.elem.placeholder);
            }
        });
    });
    function add_content(flow_id,title,placeholder) {
        var open_url = "{:url('addContent')}?subject_id={$Request.param.id}"+"&flow_id="+flow_id+"&placeholder="+placeholder;
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

    function agree(flow_id,placeholder){
        var open_url = "{:url('agree')}";
        $.post(open_url,{'flow_id':flow_id,'placeholder':placeholder},function(res) {
            if (res.code == 1) {
                layer.msg(res.msg);
                location.reload();
            }else {
                layer.msg(res.msg);
            }
        });
    }

    function agree1(flow_id,placeholder){
        var open_url = "{:url('agree')}?flow_id="+flow_id+"&placeholder="+placeholder;
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'进度',
            maxmin: true,
            area: ['600px', '300px'],
            content: open_url,
            success:function (layero, index) {
            }
        });
    }
</script>
<script src="__ADMIN_JS__/footer.js"></script>