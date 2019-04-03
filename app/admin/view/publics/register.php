<!DOCTYPE html>
<html>
<head>
    <title>用户登陆</title>
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <link rel="stylesheet" href="__ADMIN_JS__/layui/css/layui.css">
    <style type="text/css">
        body {
            background-color: #009688
        }

        .login-head {
            position: fixed;
            left: 0;
            top: 0;
            width: 80%;
            height: 60px;
            line-height: 60px;
            background: #000;
            padding: 0 10%;
        }

        .login-head h1 {
            color: #fff;
            font-size: 20px;
            font-weight: 600
        }

        .login-box {
            position: absolute;
            left:50%;
            top:50%;
            margin-left:-300px;
            margin-top:-300px;
            width: 600px;
            background-color: #fff;
            padding: 15px 30px;
            border-radius: 10px;
            box-shadow: 5px 5px 15px #999;
        }

        .login-box .layui-input {
            font-size: 15px;
            font-weight: 400
        }

        .login-box .layui-form-item{
            margin-left: 50px;
        }

        .login-box input[name="password"] {
            letter-spacing: 5px;
            font-weight: 800
        }

        .login-box .copyright {
            text-align: center;
            height: 50px;
            line-height: 50px;
            font-size: 12px;
            color: #ccc
        }

        .login-box .copyright a {
            color: #ccc;
        }
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body>
<div id="mydiv">
    <div class="login-box">
        <form action="{:url()}" method="post" class="layui-form layui-form-pane">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>账号注册</legend>
            </fieldset>
<!--            <div class="layui-form-item">-->
<!--                <label class="layui-form-label">选择公司</label>-->
<!--                <div class="layui-input-inline">-->
<!--                    <select name="company_id" class="field-company_id" type="select">-->
<!--                        {$company_option}-->
<!--                    </select>-->
<!--                </div>-->
<!--            </div>-->
            <div class="layui-form-item">
                <label class="layui-form-label">类型</label>
                <div class="layui-input-inline">
                    <input type="radio" class="field-type" name="type" value="0" title="公司" checked lay-filter="type">
                    <input type="radio" class="field-type" name="type" value="1" title="个人" lay-filter="type">
                </div>
            </div>
            <div class="layui-form-item show">
                <label class="layui-form-label">公司名称</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-name" name="name" lay-verify="required"
                           autocomplete="off" placeholder="请输入公司名称">
                </div>
                <div class="layui-form-mid" style="color: red">*</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">用户名</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-username" name="username" lay-verify="required"
                           autocomplete="off" placeholder="请输入用户名">
                </div>
                <div class="layui-form-mid" style="color: red">*</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">真实姓名</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-realname" name="realname" lay-verify="required"
                           autocomplete="off" placeholder="请输入真实姓名">
                </div>
                <div class="layui-form-mid" style="color: red">*</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">联系手机</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-mobile" name="mobile" onkeypress="return (/[\d]/.test(String.fromCharCode(event.keyCode)))" lay-verify="required" maxlength="11"
                           autocomplete="off" placeholder="请输入手机号码">
                </div>
                <div class="layui-form-mid" style="color: red">*</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">登陆密码</label>
                <div class="layui-input-inline">
                    <input type="password" class="layui-input" name="password" lay-verify="required" autocomplete="off"
                           placeholder="******">
                </div>
                <div class="layui-form-mid" style="color: red">*</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">确认密码</label>
                <div class="layui-input-inline">
                    <input type="password" class="layui-input" name="password_confirm" lay-verify="required"
                           autocomplete="off" placeholder="******">
                </div>
                <div class="layui-form-mid" style="color: red">*</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">联系邮箱</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input field-email" name="email" autocomplete="off"
                           placeholder="请输入邮箱地址">
                </div>
            </div>
            {:token('__token__', 'sha1')}
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="formSubmit">注册</button>
                    <a href="{:url('index')}" class="layui-btn layui-btn-primary ml10"><i class="aicon ai-fanhui"></i>返回登录</a>
                </div>
            </div>
        </form>
        <div class="copyright">
        </div>
    </div>
</div>
<script src="__ADMIN_JS__/layui/layui.js"></script>

<script type="text/javascript">
    layui.define('form', function (exports) {
        var $ = layui.jquery, layer = layui.layer, form = layui.form;
        form.on('radio(type)',function (data) {
            if (1 == data.value){
                $('.field-name').attr('lay-verify','');
                $('.show').hide();
            }else {
                $('.field-name').attr('lay-verify','required');
                $('.show').show();
            }
        });
        form.on('submit(formSubmit)', function (data) {
            var _form = $(this).parents('form');
            layer.msg('数据提交中...', {time: 3000});
            $.ajax({
                type: "POST",
                url: _form.attr('action'),
                data: _form.serialize(),
                success: function (res) {
                    layer.msg(res.msg, {}, function () {
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
    window.onload = function () {
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