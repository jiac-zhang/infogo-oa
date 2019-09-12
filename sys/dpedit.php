<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/10
 * Time: 17:22
 */
require_once 'base/db.php';
require_once 'base/function.php';
session_start();

check_login();

$type = isset($_POST['type']) ? (int)$_POST['type'] : 0;

if (in_array($type, [1, 2])) {
    check_permission_ajax();

    $data = [
        'code' => 0,
        'msg' => 'success'
    ];

    $db = db::getInstance();

    $department_id = isset($_POST['department_id']) ? (int)$_POST['department_id'] : 0;

    switch ($type) {
        case 1:
            //编辑动作
            $name = isset($_POST['name']) ? addslashes($_POST['name']) : '';

            $check_sql = "SELECT id FROM info_departments WHERE name='{$name}'";

            $res = $db->query($check_sql);

            if ($res) {
                echo '<script>alert("部门名字重复！");window.location.href="department.php"</script>';die;
            }

            $sql = $department_id ? "UPDATE info_departments SET name=? WHERE id = {$department_id}" : 'INSERT INTO info_departments (name) VALUES(?)';

            //创建预处理语句
            $stmt = $db->prepare($sql);

            mysqli_stmt_bind_param($stmt, 's', $name);

            $res = mysqli_stmt_execute($stmt);

            // 关闭预处理语句
            mysqli_stmt_close($stmt);

            $act = $department_id ? '编辑' : '新增';
            if ($res) {
                echo '<script>alert("'. $act .'部门成功");window.location.href="department.php"</script>';
            } else {
                echo '<script>alert("'. $act .'部门失败");window.location.href="department.php"</script>';
            }
            break;
        case 2:
            //删除动作
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

            $del_dp_sql = "DELETE FROM info_departments WHERE id = ?";

            //创建预处理语句
            $stmt = $db->prepare($del_dp_sql);
            mysqli_stmt_bind_param($stmt, "i", $department_id);

            $result = mysqli_stmt_execute($stmt);

            // 关闭预处理语句
            mysqli_stmt_close($stmt);


            if (!$result) {
                $data = [
                    'code' => -1,
                    'msg' => '删除失败！'
                ];
            }
            break;
        default:
            break;
    }
    echo json_encode($data);die;
}

check_permission();

$department_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$title = '新建部门';

$db = db::getInstance();
if ($department_id) {
    $title = '编辑部门';

    $sql = "SELECT id,name FROM info_departments WHERE id={$department_id}";

    $result = $db->query($sql);
    if (empty($result)) {
        echo '<script>alert("部门ID有误，请检查重试");window.history.back()</script>';
        die;
    }

    $department_info = $result[0];
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
                        <?php echo $title;?>
                    </h2>
                    <hr>
                    <form action="dpedit.php" method="POST" accept-charset="UTF-8">
                        <input type="hidden" name="type" value="1">
                        <input type="hidden" name="department_id" value="<?php echo isset($department_info['id']) ? $department_info['id']: 0; ?>">
                        <div class="form-group">
                            <input type="text" name="name" value="<?php echo isset($department_info['name']) ? $department_info['name'] : ''; ?>" placeholder="请填写部门名称" required="required" class="form-control">
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
