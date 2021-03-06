<!DOCTYPE html>
<html>
<head>
    <title>用户登陆</title>
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <link rel="stylesheet" href="__ADMIN_JS__/layui/css/layui.css">
    <style type="text/css">
        body {
            color:#999;
            /*background:url('http://img.infinitynewtab.com/wallpaper/{:date("Ymd")%4000}.jpg');*/
            background:rgb(153, 153, 153);
            background-size:cover;
        }
        .profile-img-card {
            width: 220px;
            height: 150px;
            margin: 20px auto;
            display: block;
            /*-moz-border-radius: 50%;*/
            /*-webkit-border-radius: 50%;*/
            /*border-radius: 50%;*/
        }
        .profile-name-card {
            text-align: center;
        }
        .login-head{position:fixed;left:0;top:0;width:80%;height:60px;line-height:60px;background:#000;padding:0 10%;}
        .login-head h1{color:#fff;font-size:20px;font-weight:600}
        /*.login-box{margin:240px auto 0;width:400px;background-color:rgba(250, 250, 250, 0.7);padding:15px 30px;border-radius:10px;box-shadow: 5px 5px 15px #999;}*/
        .login-box{
            margin:200px auto 0;width:400px;
            border-radius: 3px;
            /*-webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);*/
            /*box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);*/
            /*background: rgba(255,255,255, 0.2);*/
        }
        .login-box .layui-input{font-size:15px;font-weight:400}
        .login-box input[name="password"]{letter-spacing:5px;font-weight:800}
        .login-box .layui-btn{width:100%;}
        .login-box .copyright{text-align:center;height:50px;line-height:50px;font-size:12px;color:#fbfbfb}
        .login-box .copyright a{color:#fbfbfb;}
        a {
            color:#fe9900;
        }
        .layui-input{
            height: 38px;
            line-height: 1.3;
            line-height: 38px \9;
            border-width: 1px;
            border-style: solid;
            background-color: rgb(153, 153, 153);
            border-radius: 2px;
        }
    </style>
</head>
<body>
<div id="mydiv">
    <div class="login-box">
        <img id="profile-img" class="profile-img-card" src="__ADMIN_IMG__/avatar1.png" />
        <p id="profile-name" class="profile-name-card"></p>
        <form action="{:url()}" method="post" class="layui-form layui-form-pane">

            <div class="layui-form-item">
                <label class="layui-form-label">手机号码</label>
                <div class="layui-form-mid" style="color: #0b0b0b">{$Request.param.mobile}</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">验证码</label>
                <div class="layui-input-block">
                    <div class="layui-input-inline" style="width: 120px;">
                        <input style="width: 100px" type="text" class="layui-input field-checkcode" name="checkcode" lay-verify="required" maxlength="6" autocomplete="off">
                    </div>
                    <div class="layui-input-inline" style="width: 120px;">
                        <input id="code_id" class="layui-btn layui-btn-warm" onclick="send_code(this)" value="发送验证码">
                    </div>
                </div>
            </div>
            {:token('__token__', 'sha1')}
            <input type="hidden" name="username" value="{$Request.param.username}">
            <input type="hidden" name="company_id" value="{$Request.param.company_id}">
            <input type="submit" value="确定" lay-submit="" lay-filter="formLogin" class="layui-btn layui-btn-warm" style="background-color: #fe9900">
            <div>
                <!--            <a href="{:url('Qqlogin/index')}" title="QQ"><img src="__ADMIN_IMG__/qq.png"/></a>-->
                <!--            <a href="{:url('Wxlogin/index')}" title="微信"><img src="__ADMIN_IMG__/wx.png"/></a>-->
            </div>
        </form>
<!--        <div class="copyright">-->
<!--            <a style="color:#FFB800" target="_blank" href="http://www.imlgl.com">Powered By IMLGL</a>-->
<!--        </div>-->
    </div>
</div>
<script src="__ADMIN_JS__/layui/layui.js"></script>
<script src="__PUBLIC_JS__/jquery.2.1.4.min.js?v="></script>
<script>
    layui.config({
        base: '__ADMIN_JS__/'
    }).use('global');
</script>
<script type="text/javascript">
    layui.define('form','jquery', function(exports) {
        var $ = layui.jquery, layer = layui.layer, form = layui.form;
    });

    var countdown = 60;
    var opr = 0;//判断标识
    function send_code(val) {
        if (opr == 0) {
            var username='{$Request.param.username}',company_id='{$Request.param.company_id}';
            $.ajax({
                type: 'POST',
                url: "{:url('sendCode')}",
                data: {username: username,company_id: company_id},
                dataType: 'json',
                success: function (data) {
                    if (data){
                        layer.msg('发送成功');
                    }
                }
            });
        }
        if (countdown == 0) {
            opr = 0;
            val.removeAttribute("disabled");
            val.value = "发送验证码";
            countdown = 60;
        } else {
            opr = 1;
            val.setAttribute("disabled", true);
            val.value = "重新发送(" + countdown + ")";
            countdown--;
            setTimeout(function () {
                send_code(val);
            }, 1000);
        }

    }

    // window.onload = function() {
    //     //配置
    //     var config = {
    //         vx: 4,	//小球x轴速度,正为右，负为左
    //         vy: 4,	//小球y轴速度
    //         height: 2,	//小球高宽，其实为正方形，所以不宜太大
    //         width: 2,
    //         count: 200,		//点个数
    //         color: "121, 162, 185", 	//点颜色
    //         stroke: "130,255,255", 		//线条颜色
    //         dist: 6000, 	//点吸附距离
    //         e_dist: 20000, 	//鼠标吸附加速距离
    //         max_conn: 10 	//点到点最大连接数
    //     }
    //     //调用
    //     CanvasParticle(config);
    // }
</script>
<!--<script src="__ADMIN_JS__/particles/canvas-particle.js"></script>-->
</body>
</html>