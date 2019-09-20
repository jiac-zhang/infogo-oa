<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/12
 * Time: 16:46
 */

require_once 'base/db.php';
require_once 'base/page.php';
require_once 'base/function.php';
session_start();

check_login();
check_permission();

$db = db::getInstance();

$where = 'u.department_id >= 0';
$username = isset($_GET['username']) ? addslashes($_GET['username']) : '';
$role_id = isset($_GET['role_id']) ? addslashes($_GET['role_id']) : '';
$department_id = isset($_GET['department_id']) ? addslashes($_GET['department_id']) : '';

if (!empty($username)) {
    $where .= " AND u.nickname='{$username}'";
}

if (!empty($role_id)) {
    $where .= " AND u.role_id='{$role_id}'";
}

if (!empty($department_id)) {
    $where .= " AND u.department_id='{$department_id}'";
}

$count_sql = "SELECT count(id) as total FROM info_users u WHERE {$where}";
$count_result = $db->query($count_sql);
$total = $count_result ? $count_result[0]['total'] : 0;

$page = new page($total);

$sql = "SELECT u.id,u.nickname,u.department_id,u.role_id,IFNULL(d.name,'无部门') as department_name FROM info_users u LEFT JOIN info_departments d ON u.department_id=d.id WHERE {$where} ORDER BY u.role_id ASC,u.department_id ASC LIMIT {$page->limit()}";
$result = $db->query($sql);

$departments_sql = 'SELECT id,name FROM info_departments';

$departments = $db->query($departments_sql);

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
            <select class="form-control"  name="department_id" id="department_id">
                <option value="">部门</option>
                <?php
                if (!empty($departments)) {
                    foreach ($departments as $department) {
                        $select = '';
                        if (isset($_GET['department_id']) && $department['id'] == $_GET['department_id']) {
                            $select = 'selected="selected"';
                        }
                        echo '<option value="'. $department['id'] .'" '. $select .'>'. $department['name'] .'</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group col-md-2">
            <select class="form-control"  name="role_id" id="role_id">
                <option value="">职位</option>
                <option value="2" <?php if (isset($_GET['role_id']) && 2 == $_GET['role_id']) echo 'selected="selected"' ?>>经理</option>
                <option value="3" <?php if (isset($_GET['role_id']) && 3 == $_GET['role_id']) echo 'selected="selected"' ?>>员工</option>
            </select>
        </div>
        <div class="form-group col-md-2">
            <input type="text" class="form-control" name="username" id="username" placeholder="员工名称" value="<?php echo isset($_GET['username']) ? $_GET['username'] : ''; ?>">
        </div>
        <div class="form-group col-md-2">
            <button class="btn btn-primary" onclick="search_user()">搜索</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>员工名称</th>
                <th>职位</th>
                <th>部门</th>
                <th>编辑</th>
            </tr>
            </thead>
            <tbody>
                <?php
                if (empty($result)) {
                    echo '<tr><td colspan="4">暂无数据</td></tr>';
                } else {
                    foreach ($result as $user) {
                        echo '<tr><td>'. $user['nickname'] .'</td><td>'. trans_role($user['role_id']) .'</td><td>'. $user['department_name'] .'</td><td>'. ($user['role_id'] == 2 ? '<a href="javascript:void(0)" onclick="manager_del(this,'. $user['id'] .')" class="btn btn-danger">取消经理</a>':'<a href="userdpedit.php?user_id='.$user['id'].'" class="btn btn-primary">部门设置</a>') .'</td></tr>';
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
    function manager_del(e,manager_id) {
        if (confirm('确认取消经理身份吗？')) {
            $.ajax({
                type: "POST",
                url: "manageredit.php",
                data: {user_id:manager_id,type:2},
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
    function search_user() {
        var param = {};
        param['department_id'] = $('#department_id').val();
        param['role_id'] = $('#role_id').val();
        param['username'] = $('#username').val();

        var str = '';
        $.each(param,function (i,v) {
            if (v != '') {
                if (str.indexOf('?') >= 0) {
                    str += '&' + i + '=' + v;
                } else {
                    str += '?' + i + '=' + v;
                }
            }
        })
        window.location.href= 'user.php' + str;
    }
</script>
</body>
</html>
