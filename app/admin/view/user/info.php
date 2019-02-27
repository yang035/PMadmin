<form class="layui-form" action="{:url()}" method="post" id="editForm">
<div class="page-form">
    <div class="layui-form-item">
        <label class="layui-form-label">所在公司</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-cname" name="cname" lay-verify="required" autocomplete="off" placeholder="请输入用户名" readonly="">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">所在部门</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-dep_name" name="dep_name" lay-verify="required" autocomplete="off" placeholder="请输入用户名" readonly="">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">工作岗位</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-job_item" name="job_item" lay-verify="" readonly autocomplete="off" placeholder="请输入工作岗位">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-username" name="username" lay-verify="required" autocomplete="off" placeholder="请输入用户名" readonly="">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">真实姓名</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-realname" name="realname" lay-verify="required" autocomplete="off" placeholder="请输入用户名" readonly="">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">昵&nbsp;&nbsp;&nbsp;&nbsp;称</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-nick" name="nick" lay-verify="required" autocomplete="off" placeholder="请输入用户名">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">登陆密码</label>
        <div class="layui-input-inline">
            <input type="password" class="layui-input" name="password" lay-verify="password" autocomplete="off" placeholder="******">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">确认密码</label>
        <div class="layui-input-inline">
            <input type="password" class="layui-input" name="password_confirm" lay-verify="password" autocomplete="off" placeholder="******">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">联系邮箱</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-email" name="email" lay-verify="" autocomplete="off" placeholder="请输入邮箱地址">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">联系手机</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-mobile" name="mobile" lay-verify="" autocomplete="off" placeholder="请输入手机号码">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">个性签名</label>
        <div class="layui-input-inline">
            <textarea type="text" class="layui-textarea field-signature" name="signature" lay-verify="" maxlength="30" autocomplete="off" placeholder="请输入个性签名"></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">登录时间</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input field-last_login_time" name="last_login_time" lay-verify="required" autocomplete="off" placeholder="请输入用户名" readonly="">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"> </label>
        <div class="layui-input-block">
            <input type="hidden" class="field-id" name="id">
            <button type="submit" class="layui-btn layui-btn-normal" lay-submit="" lay-filter="formSubmit">提交</button>
            <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回</a>
        </div>
    </div>
</div>
</form>
{include file="block/layui" /}
<script>
var formData = {:json_encode($data_info)};
</script>
<script src="__ADMIN_JS__/footer.js"></script>