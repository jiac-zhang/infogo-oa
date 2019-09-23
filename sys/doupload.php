<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/11
 * Time: 18:25
 */
require_once 'base/db.php';
require_once 'base/upload.php';
require_once 'base/function.php';
error_reporting(E_ALL ^ E_NOTICE);
session_start();

check_login();
check_permission();

//获得$_FILES当中五个基本信息
$upload = new upload();

$result = $upload->uploadFile('logo');


if ($result['code'] == 0) {
    $db = db::getInstance();

    $sql = 'UPDATE info_logo SET path=? WHERE id=1';

    $stmt = $db->prepare($sql);

    $new_file = $result['path'];

    mysqli_stmt_bind_param($stmt, 's', $new_file);

    $res = mysqli_stmt_execute($stmt);

    // 关闭预处理语句
    mysqli_stmt_close($stmt);

    if ($res) {
        echo '<script>alert("Logo保存成功,下次登录时生效");window.history.go(-1);</script>';die;
    } else {
        echo '<script>alert("图片保存失败");window.history.go(-1);</script>';die;
    }

} else {
    echo '<script>alert("'. $result['msg'] .'");window.history.go(-1);</script>';die;
}


