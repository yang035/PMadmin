<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>名称：</b>{$data_list['name']}<br>
            <b>缩略图：</b><img src="{$data_list['thumb']}" width="50" height="50" alt="{$data_list['name']}"><br>
            <b>价格：</b>{$data_list['marketprice']}(元)<br>
            <b>麦粒兑换：</b>{$data_list['score']}(斗)<br>
            <b>描述：</b>{$data_list['remark']}<br>
            <b>更新时间：</b>{$data_list['update_time']}<br>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>