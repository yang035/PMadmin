<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>类型：</b>{$cat_option[$data_list['cat_id']]}<br>
            <b>面试岗位：</b>{$data_list['job']}<br>
            <b>名称：</b>{$data_list['name']}<br>
            <b>手机号码：</b>{$data_list['mobile']}<br>
            <b>内容：</b>{$data_list['remark']}<br>
            <b>更新时间：</b>{$data_list['update_time']}<br>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>