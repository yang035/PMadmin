<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>类型：</b>{$cat_option[$data_list['cat_id']]}<br>
            <b>岗位：</b>{$data_list['title']}<br>
            <b>年薪(万元)：</b>{$data_list['min_money']} ~ {$data_list['max_money']}<br>
            <b>标签：</b>{$data_list['tags']}<br>
            <b>招聘内容：</b><br>{$data_list['content']}<br>
            <b>发布公司：</b>{$data_list['company_name']}<br>
            <b>发布者：</b>{$data_list['user_name']}<br>
            {notempty name="data_list['attachment']"}
            <b>附件：</b><a target='_blank' class='mcolor' href="{$data_list['attachment']}" >附件</a><br>
            {/notempty}
            <b>更新时间：</b>{$data_list['update_time']}<br>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>