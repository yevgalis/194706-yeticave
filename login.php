<?php
    require_once('init.php');

    if (!empty($_SESSION['user'])) {
        header("Location: /");
        exit();
    }

    $data = [];
    $invalid_values = [];
    $user_data = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $keys = ['email', 'password'];

        foreach ($keys as $key) {
            if (isset($_POST[$key]) && !empty(trim($_POST[$key]))) {
                $data[$key] = trim($_POST[$key]);
            } else {
                $invalid_values[$key] = 'Это поле необходимо заполнить';
            }
        }

        // EMAIL CHECK
        if (empty($invalid_values['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $invalid_values['email'] = 'Неверный формат адреса email';
            } else {
                $sql = 'SELECT * FROM users WHERE email = ?';
                $user_data = db_fetch_data($con, $sql, [$data['email']], true);
            }
        }

        if (empty($invalid_values['email']) && empty($user_data)) {
            $invalid_values['email'] = 'Пользователь с таким email не найден';
        }

        // PASSWORD CHECK
        if (empty($invalid_values['password'])) {
            if (!empty($user_data) && password_verify($data['password'], $user_data['password'])) {
                $_SESSION['user'] = $user_data;
                header('Location: index.php');
            } elseif (!empty($user_data) && !password_verify($data['password'], $user_data['password'])) {
                $invalid_values['password'] = 'Вы ввели неверный пароль';
            }
        }
    }

    $page_content = include_template('login-page.php', [
        'categories' => $categories,
        'invalid_values' => $invalid_values,
        'data' => $data
        ]);

    $layout_content = include_template('layout.php', [
        'title' => 'Вход',
        'content' => $page_content,
        'categories' => $categories
        ]);

    print($layout_content);
?>
