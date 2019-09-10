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
$password = md5(md5($password));

$db = db::getInstance();

$sql = "SELECT id,username,nickname,password,department_id FROM info_users WHERE username = '$username' AND password = '$password'";

$result = $db->query($sql);

if (empty($result)) {
    echo '<script>alert("用户名或密码错误。");window.history.back()</script>';
    die;
}

$user_info = $result[0];
$user_role_id = $user_info['role_id'];

$sql = "SELECT rp.role_id,rp.permission_id,r.name,p.name,p.path,p.pid,p.is_show FROM info_role_permissions rp 
            RIGHT JOIN info_roles r ON r.id = rp.role_id 
            LEFT JOIN info_permissions p ON rp.permission_id = p.id
            WHERE rp.role_id = 1";

$result = $db->query($sql);
var_dump($result);


$_SESSION['username'] = $user_info['username'];


