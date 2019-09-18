<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>项目名：</b>{$data_list['project_name']}<br>
            <b>任务名：</b>{$data_list['content']}<br>
            <b>ML(斗)：</b>{$data_list['ml']}<br>
            <b>GL(斗)：</b>{$data_list['gl']}<br>
            <b>日期类型：</b>{$data_list['time_type']}<br>
            <b>开始日期：</b>{$data_list['start_time']}<br>
            <b>结束日期：</b>{$data_list['end_time']}<br>
            <b>发送给：</b>{$data_list['send_user']}<br>
            <b>执行人：</b>{$data_list['deal_user']}<br>
            <b>备注：</b>{$data_list['remark']}<br>
            <b>附件：</b><a target='_blank' class='mcolor' href="{$data_list['attachment']}" >附件</a><br>
            <b>添加人：</b>{$data_list['user_name']}<br>
            <b>添加时间：</b>{$data_list['create_time']}<br>
            <b>更新时间：</b>{$data_list['update_time']}<br>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>