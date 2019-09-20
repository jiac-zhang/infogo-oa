<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/10
 * Time: 16:14
 */

require_once 'base/db.php';
require_once 'base/page.php';
require_once 'base/function.php';
session_start();

check_login();
check_permission();

$db = db::getInstance();

$where = '1=1';

$search_department_name = isset($_GET['department_name']) ? addslashes($_GET['department_name']) : '';

if (!empty($search_department_name)) {
    $where .= " AND d.name='{$search_department_name}'";
}

$count_sql = 'SELECT count(id) as total FROM info_departments d WHERE '.$where;
$count_result = $db->query($count_sql);
$total = $count_result ? $count_result[0]['total'] : 0;

$page = new page($total);

$sql = "SELECT d.*,u.id as user_id,u.nickname FROM info_departments d LEFT JOIN info_users u on d.id = u.department_id AND u.role_id = 2 WHERE {$where} GROUP BY d.id ORDER BY d.id ASC LIMIT {$page->limit()}";
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
    <div style="margin-bottom: 15px">
        <a href="dpedit.php" class="btn btn-success">新建部门</a>
    </div>
    <div class="row">
        <div class="form-group col-md-2">
            <input type="text" class="form-control" name="department_name" id="department_name" placeholder="部门名称" value="<?php echo isset($_GET['department_name']) ? $_GET['department_name'] : ''; ?>">
        </div>
        <div class="form-group col-md-2">
            <button class="btn btn-primary" onclick="search_department()">搜索</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>部门名称</th>
                <th>部门经理</th>
                <th>编辑</th>
            </tr>
            </thead>
            <tbody>
                <?php
                if (empty($result)) {
                    echo '<tr><td colspan="4">暂无数据</td></tr>';
                } else {
                    foreach ($result as $department) {
                        echo '<tr><td>'. $department['name'] .'</td><td>'. $department['nickname'] .'</td><td><a href="dpedit.php?id='. $department['id'] .'" class="btn btn-primary">编辑部门</a>&nbsp;<a href="manageredit.php?id='. $department['id'] .'" class="btn btn-primary">设置部门经理</a>&nbsp;<a href="javascript:void(0)" onclick="department_del(this,'. $department['id'] .')" class="btn btn-danger">删除</a></td></tr>';
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
    function department_del(e,department_id) {
        if (confirm('确认删除部门吗？将会取消部门下员工并不可逆转')) {
            $.ajax({
                type: "POST",
                url: "api/dpApi.php",
                data: {department_id:department_id,type:2},
                success: function(res){
                    if (res.code === 0) {
                        $(e).parents('tr').remove();
                        alert("删除成功!");
                    } else {
                        alert(res.msg)
                    }
                },
                dataType:'json'
            });
        }
    }
    function search_department() {
        var department_name = $('#department_name').val();
        window.location.href= 'department.php?department_name=' + department_name;
    }
</script>
</body>
</html>
