<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="lots-by-category.php?category_id=<?=$category['category_id']; ?>"><?=$category['name']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<form class="form container<?=$invalid_values ? ' form--invalid' : ''; ?>" action="login.php" method="post" enctype="multipart/form-data">
    <h2>Вход</h2>
    <div class="form__item<?=$invalid_values['email'] ? ' form__item--invalid' : ''; ?>">
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail"<?=!empty($data['email']) ? ' value="' . $data['email'] . '"' : ''; ?> required>
        <span class="form__error"><?=$invalid_values['email'] ?></span>
        </div>
        <div class="form__item form__item--last<?=$invalid_values['password'] ? ' form__item--invalid' : ''; ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="password" placeholder="Введите пароль"<?=!empty($data['password']) ? ' value="' . $data['password'] . '"' : ''; ?> required>
        <span class="form__error"><?=$invalid_values['password'] ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
