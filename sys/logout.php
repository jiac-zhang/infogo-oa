<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/10
 * Time: 15:43
 */

session_start();

session_destroy();

header('Location:index.php');
die;