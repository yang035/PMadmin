<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>类型：</b>{$cat_option[$data_list['cat_id']]}<br>
            <b>指职责名称：</b>{$data_list['name']}<br>
            <b>ML(斗)：</b>{$data_list['ml']}<br>
            <b>GL(斗)：</b>{$data_list['gl']}<br>
            <b>备注：</b>{$data_list['remark']}<br>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>