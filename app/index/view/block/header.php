<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>首页</title>
    <!-- Bootstrap -->
    <link href="__ADMIN_JS__/home/css/bootstrap.min.css" rel="stylesheet">
    <link href="__ADMIN_JS__/home/css/style.css" rel="stylesheet">
    <link href="__ADMIN_JS__/home/css/font-awesome.min.css" rel="stylesheet">
    <link href="__ADMIN_JS__/home/css/fontello.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="__ADMIN_JS__/home/css/animsition.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Merriweather:300,300i,400,400i,700,700i" rel="stylesheet">
    <!-- owl Carousel Css -->
    <link href="__ADMIN_JS__/home/css/owl.carousel.css" rel="stylesheet">
    <link href="__ADMIN_JS__/home/css/owl.theme.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="animsition">
<div class="collapse searchbar" id="searchbar">
    <div class="search-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="关键字...">
                        <span class="input-group-btn">
            <button class="btn btn-default" type="button">搜索</button>
            </span> </div>
                    <!-- /input-group -->
                </div>
                <!-- /.col-lg-6 -->
            </div>
        </div>
    </div>
</div>
<div class="top-bar">
    <!-- top-bar -->
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-sm-8 col-xs-8">
                <p class="mail-text">祝您在此学习和生活愉快！</p>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-4">
                <div class="top-nav"> <span class="top-text">
                        {empty name="$Think.session.admin_user"}
                        <a target="_blank" href="/admin.php">登录</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href="{:url('admin.php/publics/register')}" target="_blank">注册</a>
                        {else/}
                        <a data-toggle="dropdown">{$Think.session.admin_user.username}<span class="caret"></span></a>
                        <ul class="dropdown-menu" style="min-width: 110px;" role="menu">
                            <li role="presentation">
                                <a role="menuitem" tabindex="-1" href="#">总积分</a>
                            </li>
                            <li role="presentation">
                                <a role="menuitem" tabindex="-1" href="#">我的信息</a>
                            </li>
                            <li role="presentation">
                                <a role="menuitem" tabindex="-1" href="{:url('admin/publics/logout')}">退出</a>
                            </li>
                        </ul>
                        {/empty}
                    </span></div>
            </div>
        </div>
    </div>
</div>
<!-- /.top-bar -->
<div class="header">
    <div class="container">
        <div class="row">
            <div class="col-md-2 col-sm-6 col-xs-6">
                <!-- logo -->
                <div class="logo">
                    <a href=''><img src="__ADMIN_JS__/home/images/logo.png" alt="Borrow - Loan Company Website Template"></a>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 col-xs-6">
                <!-- logo -->
                <div style="margin: 12px">
                    <a href="/admin.php" target="_blank" class="btn-sm btn-default">进入工作</a>
                </div>
            </div>
            <!-- logo -->
            <div class="col-md-7 col-sm-12 col-xs-12">
                <div id="navigation">
                    <!-- navigation start-->
                    <ul>
                        <li class="active"><a href="/" class="animsition-link">首页</a></li>
                        <li><a href="/" class="animsition-link">生活区</a></li>
                        <li><a href="/" class="animsition-link">学习区</a></li>
                        <li><a href="/admin.php" target="_blank">工作区</a></li>
                        <li><a href="/" title="Contact us" class="animsition-link">联系我们</a></li>
                    </ul>
                </div>
                <!-- /.navigation start-->
            </div>
            <div class="col-md-1 hidden-sm">
                <!-- search start-->
                <div class="search-nav"> <a class="search-btn" role="button" data-toggle="collapse" href="#searchbar" aria-expanded="false"><i class="fa fa-search"></i></a> </div>
            </div>
            <!-- /.search start-->
        </div>
    </div>
</div>