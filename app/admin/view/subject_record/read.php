<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>项目：</b>{$cat_option[$data_list['subject_id']]}<br>
            <b>主题：</b>{$data_list['name']}<br>
            <b>内容：</b>{$data_list['content']}<br>
            <b>附件：</b>
            {notempty name="data_list['attachment']"}
            <a class='mcolor' href="{$data_list['attachment']}">{$data_list['name']}</a>
            {else/}
            无
            {/notempty}
            <br>
            <b>更新时间：</b>{$data_list['update_time']}<br>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>