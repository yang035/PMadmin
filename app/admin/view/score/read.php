<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            {volist name="tmp" id="vo"}
            <b>员工编号：</b>{$user[$vo['uid']]['id_card']}<br>
            <b>累计ML：</b>{$vo['ml']}<br>
            <b>可发放ML：</b>{$vo['finish_ml']}<br>
<!--            <b>已发放ML：</b>{$vo['total_fafang']}<br>-->
<!--            <b>未发放ML：</b>{$vo['finish_ml']-$vo['total_fafang']}<br>-->
            <b>未完成ML：</b>{$vo['ml']-$vo['finish_ml']}<br>
            <b>当月完成：</b>{$vo['finish_ml_month']}<br>
            <b>当月发放：</b>{$vo['benci_fafang']}<br>
            <b>累计GL：</b>{$gl[$vo['uid']]['gl_add_sum']}<br>
            <b>当月GL：</b>{$gl_month[$vo['uid']]['gl_add_sum']}<br>
            <b>当月GL排名：</b>{$gl_month[$vo['uid']]['sort']}<br>
            {/volist}
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>