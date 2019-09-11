<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/11
 * Time: 17:16
 */
require_once 'base/db.php';
require_once 'base/function.php';
session_start();

check_login();
check_permission();

?>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>绩效考核管理 - Infogo</title>
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="/public/css/index.css">
</head>
<body>
<?php include 'public/views/nav.php'; ?>
<div class="container">
    <div class="container">
        <div class="col-md-10 offset-md-1">
            <div class="card ">
                <div class="card-body">
                    <h2><i class="far fa-edit"></i>
                        上传Logo
                    </h2>
                    <hr>
                    <form action="/doupload.php" method="post"  enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="logo">选择Logo</label>
                            <input type="file" id="logo" name="logo">
                            <p class="help-block">仅支持png,jpg,gif格式，大小1M以内</p>
                        </div>
                        <div class="well well-sm">
                            <input type="submit" value="保存" class="btn btn-primary">
                        </div>
                    </form>
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



