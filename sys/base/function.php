<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/10
 * Time: 16:15
 */

function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location:login.php');
        die;
    }
}

function check_permission() {
    $path_info = pathinfo($_SERVER['REQUEST_URI']);

    $file_name = $path_info['filename'];
    if (!in_array($file_name,$_SESSION['permissions_path'])) {
        echo '<script>alert("没有权限访问此页面");window.location.href="index.php"</script>';
        die;
    }
}

function check_permission_ajax() {
    $path_info = pathinfo($_SERVER['REQUEST_URI']);

    $file_name = $path_info['filename'];
    if (!in_array($file_name,$_SESSION['permissions_path'])) {
        $return = [
            'code' => -1,
            'msg' => '没有权限',
        ];
        echo json_encode($return);
        die;
    }
}