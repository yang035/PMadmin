<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>类型：</b>{$cat_option[$data_list['cat_id']]}<br>
            <b>名称：</b>{$data_list['name']}<br>
            <b>颜色：</b>{$car_color[$data_list['color']]}<br>
            <b>车牌号：</b>{$data_list['idcard']}<br>
            <b>具体配置：</b>{$data_list['remark']}<br>
            <b>更新时间：</b>{$data_list['update_time']}<br>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>