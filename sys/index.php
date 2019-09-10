<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/10
 * Time: 10:18
 */
error_reporting(0);
session_start();

$user_id = $_SESSION['user_id'];

if (empty($user_id)) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location:/login.php');
}