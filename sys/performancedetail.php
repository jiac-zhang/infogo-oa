<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/11
 * Time: 11:59
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

$sort_arr = [
    'ability' => 'DESC',
    'attitude' => 'DESC',
    'leadership' => 'DESC',
    'total' => 'DESC',
];

$sort_key = isset($_GET['sort_key']) ? addslashes(strtolower($_GET['sort_key'])) : 'total';
$order = isset($_GET['order']) ? addslashes(strtoupper($_GET['order'])) : 'DESC';
$username = isset($_GET['username']) ? addslashes($_GET['username']) : 0;

$search_where = '';
if (!empty($username)) {
    $search_where = " AND u.nickname='{$username}'";
}

if (array_key_exists($sort_key, $sort_arr)) {
    $sort_arr[$sort_key] = $order == 'DESC' ? 'ASC' : 'DESC';
}

$year = isset($time[0]) ? $time[0] : date('Y');
$search_quarter = isset($time[1]) ? $time[1] : $quarter;

$count_sql = "SELECT COUNT(id) AS total FROM info_vote WHERE vote_user_id={$vote_user_id} AND year={$year} AND quarter={$search_quarter}";
$count_result = $db->query($count_sql);
$total = $count_result ? $count_result[0]['total'] : 0;

$search_where = '';
if (!empty($username)) {
    $search_where = " AND u.nickname='{$username}'";
    $total = 1;
}

$page = new page($total);

$sql = "SELECT u.nickname,v.ability,v.attitude,v.leadership,v.total FROM info_vote v INNER JOIN info_users u ON v.user_id=u.id WHERE v.year={$year} AND v.quarter={$search_quarter} AND v.vote_user_id={$vote_user_id} {$search_where} ORDER BY v.{$sort_key} {$order} LIMIT {$page->limit()}";

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
    <div class="row">
        <div class="form-group col-md-2">
            <input type="text" class="form-control" name="username" id="username" placeholder="投票者名称" value="<?php echo isset($_GET['username']) ? $_GET['username'] : ''; ?>">
        </div>
        <div class="form-group col-md-2">
            <button class="btn btn-primary" onclick="search_user()">搜索</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>投票者</th>
                <th>工作能力得分 <a href="javascript:void(0)" onclick="sort_score('ability')"><i class="glyphicon glyphicon-sort" style="color:darkgray" aria-hidden="true"></i></a></th>
                <th>态度得分 <a href="javascript:void(0)" onclick="sort_score('attitude')"><i class="glyphicon glyphicon-sort" style="color:darkgray" aria-hidden="true"></i></a></th>
                <th>领导力得分 <a href="javascript:void(0)" onclick="sort_score('leadership')"><i class="glyphicon glyphicon-sort" style="color:darkgray" aria-hidden="true"></i></a></th>
                <th>总得分 <a href="javascript:void(0)" onclick="sort_score('total')"><i class="glyphicon glyphicon-sort" style="color:darkgray" aria-hidden="true"></i></a></th>
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
            <a class="btn btn-success" href="performance.php">返回</a>
        </div>
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
    var sort_arr = '<?php echo json_encode($sort_arr) ?>';
    var vote_user_id = <?php echo isset($_GET['vote_user_id']) ? (int)$_GET['vote_user_id'] : 0;?>;
    var time = '<?php echo isset($_GET['time']) ? $_GET['time'] : '';?>';
    sort_arr = JSON.parse(sort_arr);
    function sort_score(sort_key) {
        var order = sort_arr[sort_key];

        window.location.href= 'performancedetail.php?vote_user_id=' + vote_user_id + '&time=' + time + '&sort_key=' + sort_key + '&order=' + order;
    }
    function search_user() {
        var username = $('#username').val();
        window.location.href= 'performancedetail.php?vote_user_id=' + vote_user_id + '&time=' + time + '&username=' + username;
    }
</script>
</body>
</html>