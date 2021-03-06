<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="lots-by-category.php?category_id=<?=$category['category_id']; ?>"><?=$category['name']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<section class="lot-item container">
    <h2><?=htmlspecialchars($lot['title']); ?></h2>
    <div class="lot-item__content">
    <div class="lot-item__left">
        <div class="lot-item__image">
        <img src="img/<?=$lot['image']; ?>" width="730" height="548" alt="<?=htmlspecialchars($lot['title']); ?>">
        </div>
        <p class="lot-item__category">Категория: <span><?=$lot['category_name']; ?></span></p>
        <p class="lot-item__description"><?=htmlspecialchars($lot['description']); ?></p>
    </div>
    <div class="lot-item__right">
        <div class="lot-item__state">
            <div class="lot-item__timer timer">
                <?=show_remaining_time($lot['end_date'], true); ?>
            </div>
            <div class="lot-item__cost-state">
                <div class="lot-item__rate">
                    <span class="lot-item__amount">Текущая цена</span>
                    <span class="lot-item__cost"><?=render_price($lot['price']); ?></span>
                </div>
                <div class="lot-item__min-cost">
                    Мин. ставка <span><?=render_price($lot['price'] + $lot['step']); ?> p</span>
                </div>
            </div>
            <?php if (!empty($user) && (strtotime($lot['end_date']) - time()) > 0 && ($user['user_id'] !== $lot['author_id']) && ($user['user_id'] !== $lot['last_bet_user_id'])): ?>
                <form class="lot-item__form<?=$invalid_values ? ' form--invalid' : ''; ?>" action="lot.php?id=<?=$lot['lot_id']; ?>" method="post">
                    <p class="lot-item__form-item form__item<?=$invalid_values ? ' form__item--invalid' : ''; ?>">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="cost" placeholder="<?=$lot['price'] + $lot['step']; ?>" <?=!empty($data['cost']) ? ' value="' . $data['cost'] . '"' : ''; ?>>
                        <span class="form__error"><?=$invalid_values['cost'] ?></span>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
            <?php endif; ?>
            </div>
        <div class="history">
        <h3>История ставок (<span><?=count($bets); ?></span>)</h3>
        <table class="history__list">
            <?php foreach ($bets as $bet): ?>
                <tr class="history__item">
                    <td class="history__name"><?=$bet['username']; ?></td>
                    <td class="history__price"><?=render_price($bet['amount'], true); ?></td>
                    <td class="history__time"><?=show_bet_time($bet['bet_date']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        </div>
    </div>
    </div>
</section>
