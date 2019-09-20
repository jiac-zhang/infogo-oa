<?php

require_once '../base/db.php';
require_once '../base/function.php';

error_reporting(E_ALL ^ E_NOTICE);
session_start();
check_login();

check_permission_ajax();


$data = [
    'code' => 0,
    'msg' => 'success'
];
try {

    $db = db::getInstance();

    $department_id = isset($_POST['department_id']) ? (int)$_POST['department_id'] : 0;
    $type = isset($_POST['type']) ? (int)$_POST['type'] : 0;

    switch ($type) {
        case 1:
            //编辑动作
            $name = isset($_POST['name']) ? addslashes($_POST['name']) : '';

            $check_sql = "SELECT id FROM info_departments WHERE name='{$name}'";

            $res = $db->query($check_sql);

            if ($res) {
                throw new Exception("部门名字重复");
            }

            $sql = $department_id ? "UPDATE info_departments SET name=? WHERE id = {$department_id}" : 'INSERT INTO info_departments (name) VALUES(?)';

            //创建预处理语句
            $stmt = $db->prepare($sql);

            mysqli_stmt_bind_param($stmt, 's', $name);

            $res = mysqli_stmt_execute($stmt);

            // 关闭预处理语句
            mysqli_stmt_close($stmt);

            $act = $department_id ? '编辑' : '新增';
            if (!$res) {
                throw new Exception("'. $act .'部门失败");
            }
            break;
        case 2:
            //删除动作
            $check_sql = "SELECT id FROM info_users WHERE department_id = {$department_id}";

            $check_result = $db->query($check_sql);
            if (!empty($check_result)) {
                $user_ids = array_column($check_result,'id');
                $user_ids_where = '('.join(',', $user_ids) . ')';
                $cancel_manager_sql = "UPDATE info_users SET role_id=?,department_id=? WHERE id = {$check_result[0]['id']}";

                //创建预处理语句
                $stmt = $db->prepare($cancel_manager_sql);

                $change_role_id = 3;
                $change_department_id = 0;
                mysqli_stmt_bind_param($stmt, "ii", $change_role_id, $change_department_id);

                $res = mysqli_stmt_execute($stmt);

                if (!$res) {
                    throw new Exception('删除失败！');
                }

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
                throw new Exception('删除失败！');
            }
            break;
        default:
            break;
    }
} catch (\Exception $e) {
    $data = [
        'code' => -1,
        'msg' => $e->getMessage()
    ];
}

echo json_encode($data);die;
