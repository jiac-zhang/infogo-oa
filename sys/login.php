<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/10
 * Time: 10:05
 */
error_reporting(0);
session_start();

$user_id = $_SESSION['user_id'];

if (!empty($user_id)) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location:/index.php');
}
?>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>绩效考核管理 - Infogo</title>
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
        /* universal */

        body {
            background-color: #f8fafc;
            font-family: Helvetica, "Microsoft YaHei", Arial, sans-serif;
            font-size: 14px;
        }

        /* header */

        .navbar-static-top {
            border-color: #e7e7e7;
            background-color: #fff;
            box-shadow: 0px 1px 11px 2px rgba(42, 42, 42, 0.1);
            border-top: 4px solid #00b5ad;
            border-bottom: 1px solid #e8e8e8;
            margin-bottom: 40px;
            margin-top: 0px;
        }
        .bg-light {
            background-color: #f8f9fa !important;
        }
        .navbar-light .navbar-brand {
            color: rgba(0, 0, 0, 0.9);
        }
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            /* Set the fixed height of the footer here */
            height: 60px;
            background-color: #000;
        }
        .footer>.container {
            padding-right: 15px;
            padding-left: 15px;
        }

        .footer>.container>p {
            margin: 19px 0;
            color: #c1c1c1;
        }
        .footer>.container>p>a {
            color: inherit;
        }
        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 0.25rem;
        }
        .card-header {
            padding: 0.75rem 1.25rem;
            margin-bottom: 0;
            background-color: rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }
        .card-body {
            flex: 1 1 auto;
            padding: 1.25rem;
        }
        .text-md-right {
            text-align: right !important;
        }
    </style>
</head>

<body>

<div class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
    <div class="container">
        <!-- Branding Image -->
        <a class="navbar-brand  hidden-sm" href="http://bbs.harryzhang.com.cn">
            绩效考核管理
        </a>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <!--<ul class="navbar-nav mr-auto">
                <li class="nav-item "><a class="nav-link" href="http://bbs.harryzhang.com.cn/topics">话题</a></li>
                <li class="nav-item "><a class="nav-link" href="http://bbs.harryzhang.com.cn/categories/1">分享</a></li>
                <li class="nav-item "><a class="nav-link" href="http://bbs.harryzhang.com.cn/categories/2">教程</a></li>
                <li class="nav-item "><a class="nav-link" href="http://bbs.harryzhang.com.cn/categories/3">问答</a></li>
                <li class="nav-item "><a class="nav-link" href="http://bbs.harryzhang.com.cn/categories/4">公告</a></li>
            </ul>-->
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right hidden-sm">
                <li><a href="/login.php">登录</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">登录</div>

                    <div class="card-body">
                        <form method="POST" action="/doLogin.php">
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">用户名</label>

                                <div class="col-md-6">
                                    <input id="username" type="text" class="form-control" name="username" value="" required="" autofocus="">

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">密码</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required="">

                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        登录
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container">
        <p class="float-left">
            <a href="javascript:void(0)" target="_blank">Harry</a> <span style="color: #e27575;font-size: 14px;">❤</span>
        </p>
    </div>
</footer>
<!-- Scripts -->
<script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>