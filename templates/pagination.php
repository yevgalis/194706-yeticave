<?php if ($pages_count > 1): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
            <?php if (($cur_page - 1) > 0): ?>
                <a href="<?=$address; ?>page=<?=$cur_page - 1; ?>">Назад</a>
            <?php else: ?>
                <a>Назад</a>
            <?php endif; ?>
        </li>
        <?php foreach ($pages as $page): ?>
            <li class="pagination-item <?=(intval($page) === intval($cur_page)) ? ' pagination-item-active' : ''; ?>">
                <a href="<?=$address; ?>page=<?=$page; ?>"><?=$page; ?></a>
            </li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next">
            <?php if (($cur_page + 1) <= $pages_count): ?>
                <a href="<?=$address; ?>page=<?=$cur_page + 1; ?>">Вперед</a>
            <?php else: ?>
                <a>Вперед</a>
            <?php endif; ?>
        </li>
    </ul>
<?php endif; ?>
