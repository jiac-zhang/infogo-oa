<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/11
 * Time: 11:03
 */

require_once 'base/db.php';
require_once 'base/page.php';
require_once 'base/function.php';
session_start();

check_login();
check_permission();

$db = db::getInstance();

$time = isset($_GET['time']) ? explode(',', $_GET['time']) : '';

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

$search_quarter = isset($time[1]) ? $time[1] : $quarter;

$select = [];
for ($i = 1; $i <= $quarter; $i++) {
    $select[$i]['value'] = $year. ',' .$i;
    $select[$i]['name'] = $year. '年 ' .$i. '季度';
    $select[$i]['selected'] = '';
    if ($i == $search_quarter) {
        $select[$i]['selected'] = 'selected="selected"';
    }
}


$count_sql = 'SELECT COUNT(id) AS total FROM info_users WHERE role_id=2';
$count_result = $db->query($count_sql);
$total = $count_result ? $count_result[0]['total'] : 0;

$page = new page($total);

$manager_sql = 'SELECT u.id,u.nickname,u.role_id,u.department_id,d.name as department_name FROM info_users u INNER JOIN info_departments d on u.department_id=d.id WHERE u.role_id=2';
$managers = $db->query($manager_sql);

$user_id = $_SESSION['user_id'];
$voted_sql = "SELECT vote_user_id,total FROM info_vote WHERE user_id={$user_id} AND year={$year} AND quarter={$search_quarter} ORDER BY total DESC";
$voted = $db->query($voted_sql);
$voted_manager = array_column($voted, 'vote_user_id');
$voted_score = array_column($voted, null, 'vote_user_id');
?>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>绩效考核管理 - Infogo</title>
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="/public/css/index.css">
    <script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
    <style>
        .content {
            text-align: center;
            display: inline-block;
            font-weight: 100;
            font-family: Helvetica, "Microsoft YaHei", Arial, sans-serif;
            color:#777;
        }

        .title {
            font-size: 66px;
        }
    </style>
</head>
<body>
<?php include 'public/views/nav.php'; ?>
<div class="container">
    <div class="row">
        <div class="form-group col-md-2">
            <select name="" id="time" class="form-control">
                <?php
                foreach ($select as $option) {
                    echo '<option value="'. $option['value'] .'" '. $option['selected'] .'>'. $option['name'] .'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>经理名称</th>
                <th>部门</th>
                <th>状态</th>
                <th>总得分</th>
                <th>年度</th>
                <th>季度</th>
                <th>编辑</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (empty($managers)) {
                echo '<tr><td colspan="7">暂无数据</td></tr>';
            } else {
                foreach ($managers as $manager) {
                    if (in_array($manager['id'], $voted_manager)) {
                        $status = '已投票';
                        $edit = '<a href="/votedetail.php?vote_user_id='. $manager['id'] .'&time='.$year.','.$search_quarter.'" class="btn btn-primary">查看详情</a>';
                    } else {
                        $status = '未投票';
                        if ($search_quarter < $quarter) {
                            $edit = '<button disabled class="btn btn-danger">已过期</button>';
                        } else {
                            $edit = '<a href="/dovote.php?vote_user_id='. $manager['id'] .'" class="btn btn-success">投票</a>';
                        }
                    }
                    echo '<tr><td>'. $manager['nickname'] .'</td><td>'. $manager['department_name'] .'</td><td>'. $status .'</td><td>'. (isset($voted_score[$manager['id']]['total']) ? $voted_score[$manager['id']]['total'] : '') .'</td><td>'. $year .'</td><td>'. $search_quarter .'</td><td>'. $edit .'</td></tr>';
                }
            }
            ?>
            </tbody>
        </table>
        <?php
        if ($page->getPageCount() > 1) {
            include 'public/views/pagination.php';
        }
        ?>
    </div>
</div>
<footer class="footer">
    <div class="container">
        <p class="float-left">
            <a href="javascript:void(0)" target="_blank">Harry</a> <span style="color: #e27575;font-size: 14px;">❤</span>
        </p>
    </div>
</footer>
<!-- Scripts -->
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
    $(function () {
        $('#time').on('change', function () {
            var time = $(this).val();
            window.location.href="/vote.php?time=" + time;
        })
    })
</script>
</body>
</html>