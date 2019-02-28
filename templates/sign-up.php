    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($categories as $key => $value): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?=$value['name']; ?></a>
            </li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <form class="form container<?=$invalid_values ? ' form--invalid' : ''; ?>" action="sign-up.php" method="post" enctype="multipart/form-data">
      <h2>Регистрация нового аккаунта</h2>
      <div class="form__item<?=$invalid_values['email'] ? ' form__item--invalid' : ''; ?>">
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail"<?=!empty($data['email']) ? ' value="' . $data['email'] . '"' : ''; ?> required>
        <span class="form__error"><?=$invalid_values['email'] ?></span>
      </div>
      <div class="form__item<?=$invalid_values['password'] ? ' form__item--invalid' : ''; ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="password" placeholder="Введите пароль"<?=!empty($data['password']) ? ' value="' . $data['password'] . '"' : ''; ?> required>
        <span class="form__error"><?=$invalid_values['password'] ?></span>
      </div>
      <div class="form__item<?=$invalid_values['name'] ? ' form__item--invalid' : ''; ?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="name" placeholder="Введите имя"<?=!empty($data['name']) ? ' value="' . $data['name'] . '"' : ''; ?> required>
        <span class="form__error"><?=$invalid_values['name'] ?></span>
      </div>
      <div class="form__item<?=$invalid_values['message'] ? ' form__item--invalid' : ''; ?>">
        <label for="message">Контактные данные*</label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться" required><?=!empty($data['message']) ? $data['message'] : ''; ?></textarea>
        <span class="form__error"><?=$invalid_values['message'] ?></span>
      </div>
      <div class="form__item form__item--file form__item--last<?=$invalid_values['avatar'] ? ' form__item--invalid' : ''; ?>">
        <label>Аватар</label>
        <div class="preview">
          <button class="preview__remove" type="button">x</button>
          <div class="preview__img">
            <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
          </div>
        </div>
        <div class="form__input-file">
          <input class="visually-hidden" name="avatar" type="file" id="photo2" value="">
          <label for="photo2">
            <span>+ Добавить</span>
          </label>
        </div>
        <span class="form__error"><?=$invalid_values['avatar'] ?></span>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Зарегистрироваться</button>
      <a class="text-link" href="login.php">Уже есть аккаунт</a>
    </form>
