<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/10
 * Time: 17:22
 */
require_once 'base/db.php';
require_once 'base/function.php';
session_start();

check_login();

$type = (int)$_POST['tpye'];

if (in_array($type, [1, 2])) {
    check_permission_ajax();
} else {
    check_permission();
}

