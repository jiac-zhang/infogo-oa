<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/10
 * Time: 11:18
 */

require_once 'base/db.php';
session_start();

$username = addslashes($_POST['username']);
$password = addslashes($_POST['password']);
$password = md5($password);

if (strlen($username) > 15) {
    echo '<script>alert("用户名长度过长。");window.history.back()</script>';
    die;
}

$db = db::getInstance();

$sql = "SELECT id,username,nickname,password,department_id,role_id FROM info_users WHERE username = '$username'";

$result = $db->query($sql);

if (empty($result) || $password != $result[0]['password']) {
    echo '<script>alert("用户名或密码错误。");window.history.back()</script>';
    die;
}


$user_info = $result[0];
$user_role_id = $user_info['role_id'];

$sql = "SELECT rp.role_id,rp.permission_id,r.name as role_name,p.name as permission_name,p.path,p.is_show FROM info_role_permissions rp 
            RIGHT JOIN info_roles r ON r.id = rp.role_id 
            LEFT JOIN info_permissions p ON rp.permission_id = p.id
            WHERE rp.role_id = {$user_role_id}";

$result = $db->query($sql);
$permissions = array_column($result, null, 'permission_id');
$user_info['role_name'] = current($permissions)['role_name'];

$logo_sql = 'SELECT path FROM info_logo WHERE id=1';
$logo_result = $db->query($logo_sql);

$_SESSION['permissions'] = $permissions;
$_SESSION['user_id'] = $user_info['id'];
$_SESSION['user_info'] = $user_info;
$_SESSION['permissions_path'] = array_column($permissions,'path');
$_SESSION['logo'] = isset($logo_result[0]['path']) ? $logo_result[0]['path'] : '';

header('Location:index.php');
die;


