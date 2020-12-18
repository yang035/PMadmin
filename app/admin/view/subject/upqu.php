<form class="layui-form" action="{:url()}" method="post">
    <div class="layui-tab-item layui-show layui-form-pane">
        <h3>项目名：{$row['name']}</h3>
        <fieldset class="layui-elem-field layui-field-title">
            <legend><a onclick="add_content1()" class="layui-btn layui-btn-warm">上传资料</a></legend>
        </fieldset>
                {notempty name="upqu"}
                <ul class="layui-timeline" style="padding-left:30px">
                    {volist name="upqu" id="f2"}
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis"></i>
                        <div class="layui-timeline-content layui-text">
                            <div class="layui-timeline-title">
                                {$f2['create_time']}--
                                {$f2['remark']}--
                                <a href="f2['attachment']" target="_blank">附件</a>
                            </div>
                        </div>
                    </li>
                    {/volist}
                </ul>
                {/notempty}
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

    });
    function add_content1() {
        var open_url = "{:url('addContent1')}?subject_id={$Request.param.id}";
        if (open_url.indexOf('?') >= 0) {
            open_url += '&hisi_iframe=yes';
        } else {
            open_url += '?hisi_iframe=yes';
        }
        layer.open({
            type:2,
            title :'提交资料',
            maxmin: true,
            area: ['900px', '600px'],
            content: open_url,
        });
    }

</script>
<script src="__ADMIN_JS__/footer.js"></script>