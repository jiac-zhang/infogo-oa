<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/11
 * Time: 9:54
 */

require_once 'base/db.php';
require_once 'base/function.php';
session_start();

check_login();
check_permission();

$type = isset($_POST['type']) ? (int)$_POST['type'] : 0;

$db = db::getInstance();

if ($type) {
    $department_id = isset($_POST['department_id']) ? (int)$_POST['department_id'] : 0;
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

    $check_sql = "SELECT id FROM info_users WHERE role_id = 2 AND department_id = {$department_id}";

    $check_result = $db->query($check_sql);
    if (!empty($check_result)) {

        $cancel_manager_sql = "UPDATE info_users SET role_id=? WHERE id = {$check_result[0]['id']}";

        //创建预处理语句
        $stmt = $db->prepare($cancel_manager_sql);

        $change_role_id = 3;
        mysqli_stmt_bind_param($stmt, "i", $change_role_id);

        $res = mysqli_stmt_execute($stmt);

        // 关闭预处理语句
        mysqli_stmt_close($stmt);
    }

    $update_manager_sql = "UPDATE info_users SET role_id=? WHERE id = {$user_id}";

    //创建预处理语句
    $stmt = $db->prepare($update_manager_sql);

    $change_role_id = 2;
    mysqli_stmt_bind_param($stmt, "i", $change_role_id);

    $result = mysqli_stmt_execute($stmt);

    // 关闭预处理语句
    mysqli_stmt_close($stmt);


    if ($result) {
        echo '<script>alert("设置成功");window.location.href="/department.php";</script>';
    } else {
        echo '<script>alert("设置失败");window.location.href="/department.php";</script>';
    }
    die;
}

$department_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($department_id) {

    $sql = "SELECT d.id, d.name as department_name,u.id as user_id,u.nickname FROM info_departments d LEFT JOIN info_users u on d.id = u.department_id AND u.role_id = 2 WHERE d.id = {$department_id}";

    $result = $db->query($sql);
    if ($result[0]['user_id']) {
        $manager_info = $result[0];
    }

    if (!$result[0]['department_name']) {
        echo '<script>alert("部门ID错误");window.location.href="/department.php";</script>';die;
    }


    $users_sql = "SELECT id,nickname,role_id FROM info_users WHERE department_id = {$department_id} and role_id in (2,3)";
    $users = $db->query($users_sql);

} else {
    echo '<script>alert("部门ID错误");window.location.href="/department.php";</script>';die;
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
    <link rel="stylesheet" href="/public/css/index.css">
</head>
<body>
<?php include 'public/views/nav.php'; ?>
<div class="container">
    <div class="container">
        <div class="col-md-10 offset-md-1">
            <div class="card ">
                <div class="card-body">
                    <h2><i class="far fa-edit"></i>
                        编辑部门经理
                    </h2>
                    <hr>
                    <form action="/manageredit.php" method="POST" accept-charset="UTF-8">
                        <input type="hidden" name="type" value="1">
                        <input type="hidden" name="department_id" value="<?php echo isset($department_id) ? $department_id : 0; ?>">
                        <div class="form-group">
                            <select name="user_id" required="required" class="form-control">
                                <option value="" hidden="hidden">请选择经理</option>
                                <?php
                                foreach ($users as $user) {
                                    $select = '';
                                    if (isset($manager_info['user_id']) && $manager_info['user_id'] == $user['id']) {
                                        $select = 'selected="selected"';
                                    }
                                    echo '<option value="'. $user['id'] .'" '. $select .'>'. $user['nickname'] .'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="well well-sm">
                            <button type="submit" class="btn btn-primary"><i aria-hidden="true" class="far fa-save mr-2"></i> 保存</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
<script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
