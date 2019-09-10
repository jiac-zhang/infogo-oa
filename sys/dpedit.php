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

$type = (int)$_POST['type'];

if (in_array($type, [1, 2])) {
    check_permission_ajax();

    $data = [
        'code' => 0,
        'msg' => 'success'
    ];

    $db = db::getInstance();

    $department_id = (int)$_POST['department_id'];

    switch ($type) {
        case 1:
            //编辑动作
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
} else {
    check_permission();
}

