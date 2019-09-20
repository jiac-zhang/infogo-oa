<?php

require_once '../base/db.php';
require_once '../base/function.php';

error_reporting(E_ALL ^ E_NOTICE);
session_start();
check_login();

check_permission_ajax();

$data = array(
    'code' => 0,
    'msg' => 'success'
);

try {
    $vote_user_id = isset($_POST['vote_user_id']) ? (int)$_POST['vote_user_id'] : 0;

    if (!$vote_user_id) {
       throw new Exception('用户ID错误');
    }

    $db = db::getInstance();

    $check_role_sql = "SELECT id,nickname from info_users WHERE id={$vote_user_id} and role_id=2";

    $check_role_result = $db->query($check_role_sql);
    if (empty($check_role_result)) {
        throw new Exception('只能给经理投票');
    }


    $year = isset($time[0]) ? $time[0] : date('Y');
    $month = date('m');

    $quarter = 0;
    if ($month >= 1 && $month <= 3) {
        $quarter = 1;
    } elseif ($month >= 4 && $month <= 6) {
        $quarter = 2;
    } elseif ($month >= 7 && $month <= 9) {
        $quarter = 3;
    } else {
        $quarter = 4;
    }

    $user_id = $_SESSION['user_id'];

    if ($vote_user_id == $user_id) {
        throw new Exception('不能投票给自己');
    }

    $check_vote_sql = "SELECT id FROM info_vote WHERE user_id={$user_id} AND vote_user_id={$vote_user_id} AND year={$year} AND quarter={$quarter}";

    $check_vote_result = $db->query($check_vote_sql);

    if (!empty($check_vote_result)) {
        throw new Exception('不能重复投票');
    }

    $ability = isset($_POST['ability']) ? (int)$_POST['ability'] : 0;
    $attitude = isset($_POST['attitude']) ? (int)$_POST['attitude'] : 0;
    $leadership = isset($_POST['leadership']) ? (int)$_POST['leadership'] : 0;
    $total = $ability + $attitude + $leadership;

    $sql = 'INSERT INTO info_vote (user_id,vote_user_id,ability,attitude,leadership,total,year,quarter) VALUES(?,?,?,?,?,?,?,?)';

    //创建预处理语句
    $stmt = $db->prepare($sql);

    mysqli_stmt_bind_param($stmt, 'iiiiiiii', $user_id, $vote_user_id, $ability, $attitude, $leadership, $total, $year, $quarter);

    $res = mysqli_stmt_execute($stmt);

    // 关闭预处理语句
    mysqli_stmt_close($stmt);

    if (!$res) {
        throw new Exception('投票失败');
    }

} catch (\Exception $e) {
    $data = array(
        'code' => -1,
        'msg' => $e->getMessage()
    );
}
echo json_encode($data);die;
