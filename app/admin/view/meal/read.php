<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>内容：</b>{$data_list['name']}<br>
            <b>分类：</b>{$cat_option[$data_list['cat_id']]}<br>
            <b>备注：</b>{$data_list['name']}<br>
            <b>更新时间：</b>{$data_list['update_time']}<br>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>