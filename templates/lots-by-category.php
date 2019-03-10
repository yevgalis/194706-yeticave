<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="lots-by-category.php?category_id=<?=$category['category_id']; ?>"><?=$category['name']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<div class="container">
    <section class="lots">
        <h2>Все лоты в категории <span><?=$lots_category['name']; ?></span></h2>
        <ul class="lots__list">
            <?php foreach ($lots as $lot): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="img/<?=$lot['image']; ?>" width="350" height="260" alt="<?=htmlspecialchars($lot['title']); ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=$lot['category']; ?></span>
                        <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$lot['lot_id']; ?>"><?=htmlspecialchars($lot['title']); ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?=render_price($lot['price'], true); ?></span>
                            </div>
                            <div class="lot__timer timer">
                                <?=show_remaining_time($lot['end_date']); ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?=$pagination_content; ?>
</div>
