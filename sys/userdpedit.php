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

$db = db::getInstance();

$type = isset($_POST['type']) ? (int)$_POST['type'] : 0;
if ($type) {
    $department_id = isset($_POST['department_id']) ? (int)$_POST['department_id'] : 0;
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

    $check_sql = "SELECT id,role_id,department_id FROM info_users WHERE id = {$user_id}";

    $check_result = $db->query($check_sql);
    if (!empty($check_result)) {
        if ($check_result[0]['role_id'] == 2) {
            echo '<script>alert("请先取消经理身份再变更部门");window.location.href="user.php";</script>';die;
        }

        if ($check_result[0]['department_id'] == $department_id) {
            echo '<script>alert("重复设置！");window.location.href="user.php";</script>';die;
        }

        $update_sql = "UPDATE info_users SET department_id=? WHERE id = {$check_result[0]['id']}";

        //创建预处理语句
        $stmt = $db->prepare($update_sql);

        mysqli_stmt_bind_param($stmt, "i", $department_id);

        $res = mysqli_stmt_execute($stmt);

        // 关闭预处理语句
        mysqli_stmt_close($stmt);

        if ($res) {
            echo '<script>alert("设置成功");window.location.href="user.php";</script>';
            die;
        } else {
            echo '<script>alert("设置失败");window.location.href="user.php";</script>';
            die;
        }
    } else {
        echo '<script>alert("参数错误");window.location.href="user.php";</script>';
    }
    die;
}

$change_user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

if ($change_user_id) {

    $sql = "SELECT d.id as department_id, d.name as department_name,u.id as user_id,u.nickname FROM info_departments d RIGHT JOIN info_users u on d.id = u.department_id WHERE u.id = {$change_user_id}";

    $result = $db->query($sql);

    if ($result[0]['user_id']) {
        $change_user_info = $result[0];
    } else {
        echo '<script>alert("用户ID错误");window.location.href="user.php";</script>';die;
    }


    $departments_sql = "SELECT id,name FROM info_departments";
    $departments = $db->query($departments_sql);

} else {
    echo '<script>alert("用户ID错误");window.location.href="user.php";</script>';die;
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
                        设置部门
                    </h2>
                    <hr>
                    <form action="userdpedit.php" method="POST" accept-charset="UTF-8">
                        <input type="hidden" name="type" value="1">
                        <input type="hidden" name="user_id" value="<?php echo isset($change_user_id) ? $change_user_id : 0; ?>">
                        <div class="form-group">
                            <select name="department_id" required="required" class="form-control">
                                <option value="" hidden="hidden">请选择部门</option>
                                <?php
                                foreach ($departments as $department) {
                                    $select = '';
                                    if (isset($change_user_info['department_id']) && $change_user_info['department_id'] == $department['id']) {
                                        $select = 'selected="selected"';
                                    }
                                    echo '<option value="'. $department['id'] .'" '. $select .'>'. $department['name'] .'</option>';
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

<!-- Scripts -->
<script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
