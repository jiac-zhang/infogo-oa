<?php
$pagination = $page->rendor();
?>
<nav aria-label="Page navigation">
    <ul class="pagination">
        <li>
            <a href="<?php echo $pagination['first'] ?>" aria-label="Previous">
                <span aria-hidden="true">首页</span>
            </a>
        </li>
        <li><a href="<?php echo $pagination['prev'] ?>">上一页</a></li>
        <li><a href="<?php echo $pagination['next'] ?>">下一页</a></li>
        <li>
            <a href="<?php echo $pagination['end'] ?>" aria-label="Next">
                <span aria-hidden="true">末页</span>
            </a>
        </li>
    </ul>
</nav>
