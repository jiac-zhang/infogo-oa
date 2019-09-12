<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/11
 * Time: 16:09
 */

require_once 'base/db.php';
require_once 'base/page.php';
require_once 'base/function.php';
session_start();

check_login();
check_permission();

$db = db::getInstance();

$vote_user_id = isset($_GET['vote_user_id']) ? (int)$_GET['vote_user_id'] : 0;
$time = isset($_GET['time']) ? explode(',', $_GET['time']) : '';

if (!$vote_user_id) {
    echo '<script>alert("用户id错误");window.location.href="performance.php"</script>';die;
}
if (!$time) {
    echo '<script>alert("查询时间错误");window.location.href="performance.php"</script>';die;
}

$year = isset($time[0]) ? $time[0] : date('Y');
$search_quarter = isset($time[1]) ? $time[1] : $quarter;

$user_id = $_SESSION['user_id'];

$sql = "SELECT u.nickname,v.ability,v.attitude,v.leadership,v.total FROM info_vote v INNER JOIN info_users u ON v.vote_user_id=u.id WHERE v.year={$year} AND v.quarter={$search_quarter} AND v.vote_user_id={$vote_user_id} AND v.user_id={$user_id} LIMIT 1";

$result = $db->query($sql);
?>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>绩效考核管理 - Infogo</title>
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="public/css/index.css">
    <script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>
<?php include 'public/views/nav.php'; ?>
<div class="container">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>经理</th>
                <th>工作能力得分</th>
                <th>态度得分</th>
                <th>领导力得分</th>
                <th>总得分</th>
                <th>年度</th>
                <th>季度</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (empty($result)) {
                echo '<tr><td colspan="7">暂无数据</td></tr>';
            } else {
                foreach ($result as $vote) {
                    echo '<tr><td>'. $vote['nickname'] .'</td><td>'. $vote['ability'] .'</td><td>'. $vote['attitude'] .'</td><td>'. $vote['leadership'] .'</td><td>'. $vote['total'] .'</td><td>'. $year .'</td><td>'. $search_quarter .'</td></tr>';
                }
            }
            ?>
            </tbody>
        </table>
        <div class="">
            <a href="javascript:window.history.go(-1)" class="btn btn-success">返回</a>
        </div>
    </div>
</div>

<!-- Scripts -->
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>