<?php foreach ($lots as $lot): ?>
    <li class="lots__item lot">
        <div class="lot__image">
            <img src="<?=$lot['image']; ?>" width="350" height="260" alt="<?=$lot['title']; ?>">
        </div>
        <div class="lot__info">
            <span class="lot__category"><?=$lot['category']; ?></span>
            <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?=htmlspecialchars($lot['title']); ?></a></h3>
            <div class="lot__state">
                <div class="lot__rate">
                    <span class="lot__amount">Стартовая цена</span>
                    <span class="lot__cost"><?=render_price($lot['price']); ?></span>
                </div>
                <div class="lot__timer timer">
                    <?=show_remaining_time('tomorrow'); ?>
                </div>
            </div>
        </div>
    </li>
<?php endforeach; ?>
