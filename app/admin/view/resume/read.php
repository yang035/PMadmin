<form class="layui-form layui-form-pane" action="{:url()}" method="post" id="editForm">
    <div class="layui-card">
        <div class="layui-card-body">
            <b>类型：</b>{$cat_option[$data_list['cat_id']]}<br>
            <b>面试岗位：</b>{$data_list['job']}<br>
            <b>名称：</b>{$data_list['name']}<br>
            <b>手机号码：</b>{$data_list['mobile']}<br>
            <b>学历：</b>{$data_list['education']}<br>
            <b>所学专业：</b>{$data_list['major']}<br>
            <b>工作经验：</b>{$data_list['experience']}<br>
            <b>招聘来源：</b>{$data_list['source']}<br>
            <b>面试时间：</b>{$data_list['resume_time']}<br>
            <b>是否面试：</b>{$data_list['is_resume']}<br>
            <b>是否通过：</b>{$data_list['is_pass']}<br>
            <b>是否到岗：</b>{$data_list['is_duty']}<br>
            <b>面试备注：</b>{$data_list['remark']}<br>
            <b>简历：</b><a target='_blank' class='mcolor' href="{$data_list['attachment']}" >附件</a><br>
            <b>更新时间：</b>{$data_list['update_time']}<br>
        </div>
    </div>
</form>
{include file="block/layui" /}
<script>
    var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>