<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/11
 * Time: 16:27
 */
require_once 'base/db.php';
require_once 'base/function.php';
session_start();

check_login();
check_permission();

$vote_user_id = isset($_GET['vote_user_id']) ? (int)$_GET['vote_user_id'] : 0;
$vote_user_id = isset($_POST['vote_user_id']) ? (int)$_POST['vote_user_id'] : $vote_user_id;
$type = isset($_POST['type']) ? (int)$_POST['type'] : 0;

if (!$vote_user_id) {
    echo '<script>alert("用户id错误");window.location.href="vote.php"</script>';die;
}

$db = db::getInstance();
$check_role_sql = "SELECT id,nickname from info_users WHERE id={$vote_user_id} and role_id=2";

$check_role_result = $db->query($check_role_sql);
if (empty($check_role_result)) {
    echo '<script>alert("只能给经理投票");window.location.href="vote.php"</script>';die;
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
    echo '<script>alert("不能投票给自己");window.location.href="vote.php"</script>';die;
}

$check_vote_sql = "SELECT id FROM info_vote WHERE user_id={$user_id} AND vote_user_id={$vote_user_id} AND year={$year} AND quarter={$quarter}";

$check_vote_result = $db->query($check_vote_sql);

if (!empty($check_vote_result)) {
    echo '<script>alert("不能重复投票");window.location.href="vote.php"</script>';die;
}

if ($type) {
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

    if ($res) {
        echo '<script>alert("投票成功");window.location.href="vote.php"</script>';
    } else {
        echo '<script>alert("投票失败");window.location.href="vote.php"</script>';
    }

    die;
}


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
</head>
<body>
<?php include 'public/views/nav.php'; ?>
<div class="container">
    <div class="container">
        <div class="col-md-10 offset-md-1">
            <div class="card ">
                <div class="card-body">
                    <h2><i class="far fa-edit"></i>
                        给 <?php echo $check_role_result[0]['nickname'];?> 投票
                    </h2>
                    <hr>
                    <form action="dovote.php" method="POST" accept-charset="UTF-8">
                        <input type="hidden" name="type" value="1">
                        <input type="hidden" name="vote_user_id" value="<?php echo isset($_GET['vote_user_id']) ? (int)$_GET['vote_user_id'] : 0;?>">
                        <div class="form-group">
                            <label for="ability">工作能力</label>
                            <select class="form-control" name="ability" id="ability">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="attitude">态度</label>
                            <select class="form-control" name="attitude" id="attitude">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="leadership">领导力</label>
                            <select class="form-control" name="leadership" id="leadership">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <div class="well well-sm">
                            <button type="submit" class="btn btn-primary"><i aria-hidden="true" class="far fa-save mr-2"></i> 提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
