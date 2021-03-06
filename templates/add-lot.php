<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="lots-by-category.php?category_id=<?=$category['category_id']; ?>"><?=$category['name']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<form class="form form--add-lot container<?=$invalid_values ? ' form--invalid' : ''; ?>" action="add.php" method="post" enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
    <div class="form__item<?=$invalid_values['lot-name'] ? ' form__item--invalid' : ''; ?>">
        <label for="lot-name">Наименование</label>
        <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота"<?=!empty($data['lot-name']) ? ' value="' . $data['lot-name'] . '"' : ''; ?> required>
        <span class="form__error"><?=$invalid_values['lot-name'] ?></span>
    </div>
    <div class="form__item<?=$invalid_values['category'] ? ' form__item--invalid' : ''; ?>">
        <label for="category">Категория</label>
        <select id="category" name="category" required>
            <option>Выберите категорию</option>
            <?php foreach ($categories as $key => $value): ?>
                <option <?=(!empty($data['category_id']) && $data['category_id'] === $value['category_id']) ? ' selected' : ''; ?> ><?=$value['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <span class="form__error"><?=$invalid_values['category'] ?></span>
    </div>
    </div>
    <div class="form__item form__item--wide<?=$invalid_values['message'] ? ' form__item--invalid' : ''; ?>">
    <label for="message">Описание</label>
    <textarea id="message" name="message" placeholder="Напишите описание лота" required><?=!empty($data['message']) ? $data['message'] : ''; ?></textarea>
    <span class="form__error"><?=$invalid_values['message'] ?></span>
    </div>
    <div class="form__item form__item--file<?=$invalid_values['item-photo'] ? ' form__item--invalid' : ''; ?>">
    <label>Изображение</label>
    <div class="preview">
        <button class="preview__remove" type="button">x</button>
        <div class="preview__img">
        <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
        </div>
    </div>
    <div class="form__input-file">
        <input class="visually-hidden" type="file" id="photo2" name="item-photo">
        <label for="photo2">
        <span>+ Добавить</span>
        </label>
    </div>
    <span class="form__error"><?=$invalid_values['item-photo'] ?></span>
    </div>
    <div class="form__container-three">
    <div class="form__item form__item--small<?=$invalid_values['lot-rate'] ? ' form__item--invalid' : ''; ?>">
        <label for="lot-rate">Начальная цена</label>
        <input id="lot-rate" type="number" name="lot-rate" placeholder="0"<?=!empty($data['lot-rate']) ? ' value="' . $data['lot-rate'] . '"' : ''; ?> required>
        <span class="form__error"><?=$invalid_values['lot-rate'] ?></span>
    </div>
    <div class="form__item form__item--small<?=$invalid_values['lot-step'] ? ' form__item--invalid' : ''; ?>">
        <label for="lot-step">Шаг ставки</label>
        <input id="lot-step" type="number" name="lot-step" placeholder="0"<?=!empty($data['lot-step']) ? ' value="' . $data['lot-step'] . '"' : ''; ?> required>
        <span class="form__error"><?=$invalid_values['lot-step'] ?></span>
    </div>
    <div class="form__item<?=$invalid_values['lot-date'] ? ' form__item--invalid' : ''; ?>">
        <label for="lot-date">Дата окончания торгов</label>
        <input class="form__input-date" id="lot-date" type="date" name="lot-date"<?=!empty($data['lot-date']) ? ' value="' . $data['lot-date'] . '"' : ''; ?> required>
        <span class="form__error"><?=$invalid_values['lot-date'] ?></span>
    </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>
