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


$count_sql = "SELECT COUNT(DISTINCT vote_user_id)  AS total FROM info_vote WHERE year={$year} AND quarter={$search_quarter}";
$count_result = $db->query($count_sql);
$total = $count_result ? $count_result[0]['total'] : 0;

$page = new page($total);

$sql = "SELECT v.vote_user_id,u.nickname,SUM(v.total) AS total,count(v.id) as vote_users_total FROM info_vote v INNER JOIN info_users u ON v.vote_user_id=u.id WHERE YEAR={$year} AND QUARTER={$search_quarter} GROUP BY vote_user_id ORDER BY total DESC LIMIT {$page->limit()}";
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
                <th>总得分</th>
                <th>已投票人数</th>
                <th>平均得分</th>
                <th>年度</th>
                <th>季度</th>
                <th>编辑</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (empty($result)) {
                echo '<tr><td colspan="7">暂无数据</td></tr>';
            } else {
                foreach ($result as $vote) {
                    echo '<tr><td>'. $vote['nickname'] .'</td><td>'. $vote['total'] .'</td><td>'. $vote['vote_users_total'] .'</td><td>'. round($vote['total']/$vote['vote_users_total'],2) .'</td><td>'. $year .'</td><td>'. $search_quarter .'</td><td><a href="performancedetail.php?vote_user_id='. $vote['vote_user_id'] .'&time='.$year.','.$search_quarter.'" class="btn btn-primary">查看详情</a></td></tr>';
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

<!-- Scripts -->
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
    $(function () {
        $('#time').on('change', function () {
            var time = $(this).val();
            window.location.href="performance.php?time=" + time;
        })
    })
</script>
</body>
</html>