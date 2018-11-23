<!DOCTYPE html>
<html>
<head>
    <title>用户登陆</title>
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <link rel="stylesheet" href="__ADMIN_JS__/layui/css/layui.css">
    <style type="text/css">
        body{background-color: #009688}
        .login-head{position:fixed;left:0;top:0;width:80%;height:60px;line-height:60px;background:#000;padding:0 10%;}
        .login-head h1{color:#fff;font-size:20px;font-weight:600}
        .login-box{margin:240px auto 0;width:400px;background-color:#fff;padding:15px 30px;border-radius:10px;box-shadow: 5px 5px 15px #999;}
        .login-box .layui-input{font-size:15px;font-weight:400}
        .login-box input[name="password"]{letter-spacing:5px;font-weight:800}
        .login-box .layui-btn{width:100%;}
        .login-box .copyright{text-align:center;height:50px;line-height:50px;font-size:12px;color:#ccc}
        .login-box .copyright a{color:#ccc;}
    </style>
</head>
<body>
<div id="mydiv">
<div class="login-box">
    <form action="{:url()}" method="post" class="layui-form layui-form-pane">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>账号登录</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">账号</label>
            <div class="layui-input-block">
                <input type="text" name="username" class="layui-input" lay-verify="required" placeholder="手机号码或用户名" autofocus="autofocus" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">密码</label>
            <div class="layui-input-block">
                <input type="password" name="password" class="layui-input" lay-verify="required" placeholder="******" value="">
            </div>
        </div>
<!--         <div class="layui-form-item">-->
<!--            <label class="layui-form-label">安全验证</label>-->
<!--            <div class="layui-input-block">-->
<!--                <input type="text" name="code" class="layui-input">-->
<!--                <div><img src="{123456|substr=1,3}" onclick="this.src='{:captcha_src()}?x='+Math.random();" /></div>-->
<!--            </div>-->
<!--        </div>-->
        {:token('__token__', 'sha1')}
        <input type="submit" value="登陆" lay-submit="" lay-filter="formLogin" class="layui-btn">
        <div class="layui-form-item">
            <div><span><a href="{:url('register')}" class="login-qq-a">注册</a></span><span style="float: right"><a href="{:url()}">忘记密码?</a></span></div>
        </div>
        <div>
            <li><a href="{:url('Qqlogin/index')}">QQ</a></li>
            <li><a href="{:url('Wxlogin/index')}">微信</a></li>
        </div>
    </form>
    <div class="copyright">
    </div>
</div>
</div>
<script src="__ADMIN_JS__/layui/layui.js"></script>

<script>
layui.config({
  base: '__ADMIN_JS__/'
}).use('global');
</script>
<script type="text/javascript">
layui.define('form', function(exports) {
    var $ = layui.jquery, layer = layui.layer, form = layui.form;
    form.on('submit(formLogin)', function(data) {
        var _form = $(this).parents('form');
        layer.msg('数据提交中...',{time:3000});
        $.ajax({
            type: "POST",
            url: _form.attr('action'),
            data: _form.serialize(),
            success: function(res) {
                layer.msg(res.msg, {},function() {
                    if (res.code == 1) {
                        if (typeof(res.url) != 'undefined' && res.url != null && res.url != '') {
                            location.href = res.url;
                        } else {
                            location.reload();
                        }
                    } else {
                        location.reload();
                    }
                });
            }
        });
        return false;
    });
});
window.onload = function() {
    //配置
    var config = {
        vx: 4,	//小球x轴速度,正为右，负为左
        vy: 4,	//小球y轴速度
        height: 2,	//小球高宽，其实为正方形，所以不宜太大
        width: 2,
        count: 200,		//点个数
        color: "121, 162, 185", 	//点颜色
        stroke: "130,255,255", 		//线条颜色
        dist: 6000, 	//点吸附距离
        e_dist: 20000, 	//鼠标吸附加速距离
        max_conn: 10 	//点到点最大连接数
    }
    //调用
    CanvasParticle(config);
}
</script>
<script src="__ADMIN_JS__/particles/canvas-particle.js"></script>
</body>
</html>