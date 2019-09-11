<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/11
 * Time: 18:25
 */
require_once 'base/db.php';
require_once 'base/function.php';
session_start();

check_login();
check_permission();

//获得$_FILES当中五个基本信息
$name = $_FILES['logo']['name'];
$size = $_FILES['logo']['size'];
$type = $_FILES['logo']['type'];
$tmp_name = $_FILES['logo']['tmp_name'];
$error = $_FILES['logo']['error'];
$info = pathinfo($name);
$subfix = $info['extension'];

$path = './public/upload/';

$allow_subfix = ['jpg','png','jpeg','gif'];
$allow_mime = ['image/png','image/jpg','image/jpeg','image/pjpeg','image/gif'];
$allow_size = 1048576;

if ($error > 0) {
    echo '<script>alert("图片上传错误,错误码：'.$error.'");window.history.go(-1);</script>';die;
}

if (!in_array($type, $allow_mime)) {
    echo '<script>alert("图片类型错误");window.history.go(-1);</script>';die;
}

if (!in_array($subfix, $allow_subfix)) {
    echo '<script>alert("图片格式错误");window.history.go(-1);</script>';die;
}

if($size > $allow_size) {
    echo '<script>alert("图片过大");window.history.go(-1);</script>';die;
}

$res = move_uploaded_file($tmp_name, $path.$name);

if ($res) {
    $db = db::getInstance();

    $sql = 'UPDATE info_logo SET path=? WHERE id=1';

    $stmt = $db->prepare($sql);

    $new_file = $path.$name;

    mysqli_stmt_bind_param($stmt, 's', $new_file);

    $result = mysqli_stmt_execute($stmt);

    // 关闭预处理语句
    mysqli_stmt_close($stmt);

    if ($result) {
        echo '<script>alert("Logo保存成功,下次登录时生效");window.history.go(-1);</script>';die;
    } else {
        echo '<script>alert("图片保存失败");window.history.go(-1);</script>';die;
    }

} else {
    echo '<script>alert("图片保存失败");window.history.go(-1);</script>';die;
}


