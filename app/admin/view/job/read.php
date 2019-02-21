<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>职位名称：</b>{$data_list['name']}<br>
            <b>职位代码：</b>{$data_list['code']}<br>
            <b>职位职责：</b>{$data_list['remark']}<br>
            <b>任职要求：</b>{$data_list['requirements']}<br>
            <b>更新时间：</b>{$data_list['update_time']}<br>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>