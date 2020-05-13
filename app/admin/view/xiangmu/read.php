<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>项目类型：</b>{$subject_option[$data_info['cat_id']]}<br>
            <b>项目编号：</b>{$data_info['idcard']}<br>
            <b>项目规模：</b>{$data_info['name']}({$data_info['area']}平方米)<br>
            <b>项目描述：</b>{$data_info['remark']}<br>
            <b>合同总价：</b>{$data_info['total_price']} 元<br>
            <b>建设单位：</b>{$data_info['development']}<br>
            <b>项目地址：</b>{$data_info['address']}<br>
            <b>附件：</b>
            {notempty name="$data_info['attachment_show']"}
            {volist name="$data_info['attachment_show']" id="vo"}
            <a class="mcolor" href="{$vo}">附件{$i}</a>
            {/volist}
            {/notempty}
            <br>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>