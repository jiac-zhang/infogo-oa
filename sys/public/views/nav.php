<?php
$user_id = $_SESSION['user_id'];
$user_info = $_SESSION['user_info'];
$logo = $_SESSION['logo'];
?>
<div class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
    <?php
    if ($logo) {
        ?>
        <div style="height: 50px;float: left">
            <img style="height: 100%" src="<?php echo $logo; ?>" alt="">
        </div>
        <?php
    }
    ?>
    <div class="container">
        <!-- Branding Image -->
        <a class="navbar-brand  hidden-sm" href="index.php">
            绩效考核管理
        </a>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav navbar-nav">
                <?php
                $permissions = $_SESSION['permissions'];
                foreach ($permissions as $permission) {
                    if (!$permission['is_show']) {
                        continue;
                    }

                    $class = '';
                    if ($permission['path'] == pathinfo($_SERVER['REQUEST_URI'])['filename']) {
                        $class = 'class="active"';
                    }
                    echo '<li '. $class .'><a href="'. $permission['path'] .'.php">'. $permission['permission_name'] .'</a></li>';
                }
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right hidden-sm">
                <li class="nav-item dropdown">
                    <a href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
                        <?php echo '欢迎你，'.$user_info['role_name'].' '.$user_info['nickname'] ?>
                    </a>
                    <div aria-labelledby="navbarDropdown" class="dropdown-menu">
                        <a id="logout" href="#" class="dropdown-item">
                            <form action="logout.php" method="POST" onsubmit="return confirm('您确定要退出吗？');">
                                <button type="submit" name="button" class="btn btn-block btn-danger">退出</button>
                            </form>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
