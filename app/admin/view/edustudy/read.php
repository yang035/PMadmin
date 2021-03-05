<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>类型：</b>{$cat_option[$data_list['cat_id']]}<br>
            <b>名称：</b>{$data_list['name']}<br>
            <b>简介：</b>{$data_list['remark']}<br>
            {notempty name="data_list['attachment']"}
            <b>附件：</b><a target='_blank' class='mcolor' href="{$data_list['attachment']}" >附件</a><br>
            {/notempty}
            <b>更新时间：</b>{$data_list['update_time']}<br>
            <b>扫码加入：</b><img src="/{$data_list['qrcode_url']}" style="height: 200px;width: 200px"><br>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>