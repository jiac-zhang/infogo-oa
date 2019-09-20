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
                    <input type="hidden" name="type" value="1">
                    <input type="hidden" name="department_id" value="<?php echo isset($department_info['id']) ? $department_info['id']: 0; ?>">
                    <div class="form-group">
                        <input type="text" name="name" value="<?php echo isset($department_info['name']) ? $department_info['name'] : ''; ?>" placeholder="请填写部门名称" required="required" maxlength="8" class="form-control">
                    </div>
                    <div class="well well-sm">
                        <button class="btn btn-primary edit-btn"><i aria-hidden="true" class="far fa-save mr-2"></i> 保存</button>
                        <a class="btn btn-danger" href="javascript:window.history.back()"><i aria-hidden="true" class="far fa-save mr-2"></i> 返回</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
    $(function () {
        var department_name = '<?php echo isset($department_info['name']) ? $department_info['name'] : ''; ?>';
        $('.edit-btn').on('click', function () {
            var input_department_name = $('input[name=name]').val();
            var type = $('input[name=type]').val();
            var department_id = $('input[name=department_id]').val();
            if (department_name != '' && input_department_name == department_name) {
                alert('未修改');
                return false;
            }

            $.ajax({
                url: 'api/dpApi.php',
                type: 'post',
                data: {name:input_department_name, type:type, department_id:department_id},
                dataType: 'json',
                success: function (res) {
                    console.log(res.code);
                    if (res.code === 0) {
                        alert(res.msg);
                        window.location.href='department.php';
                    } else {
                        alert(res.msg)
                    }
                }
            })
        })
    })
</script>
</body>
</html>
