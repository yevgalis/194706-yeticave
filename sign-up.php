<?php
    require_once('init.php');

    $pswd_min_length = 8;
    $name_max_length = 30;
    $contact_max_length = 60;
    $max_filesize = 1;  // MB
    $data = [];
    $invalid_values = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $keys = ['email', 'password', 'name', 'message'];
        $file_name = '';

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
                $email_check = db_fetch_data($con, $sql, [$data['email']]);

                if ($email_check) {
                    $invalid_values['email'] = 'Пользователь с таким email уже зарегистрирован';
                }
            }
        }

        // PASSWORD CHECK
        if (empty($invalid_values['password']) && strlen($data['password']) < $pswd_min_length) {
            $invalid_values['password'] = 'Пароль не может быть меньше ' . $pswd_min_length . ' символов';
        }

        // NAME CHECK
        if (empty($invalid_values['name']) && strlen($data['name']) > $name_max_length) {
            $invalid_values['name'] = 'Имя не может быть больше ' . $name_max_length . ' символов';
        }

        // CONTACTS CHECK
        if (empty($invalid_values['message']) && strlen($data['message']) > $contact_max_length) {
            $invalid_values['message'] = 'Максимальное количество символов - ' . $contact_max_length;
        }

        // AVATAR CHECK
        if (isset($_FILES['avatar']) && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
            $file_type = mime_content_type($_FILES['avatar']['tmp_name']);
            $file_size = $_FILES['avatar']['size'];

            if ($file_type !== 'image/png' && $file_type !== 'image/jpeg') {
                $invalid_values['avatar'] = 'Неверный формат файла. Загрузите картинку в формате jpeg, jpg или png';
            } elseif ($file_size > $max_filesize * 1024 * 1024) {
                $invalid_values['avatar'] = 'Размер файла превышает допустимый. Максимальный размер - ' . $max_filesize . 'мб';
            } else {
                $file_name = uniqid('avatar-') . ($file_type === 'image/jpeg' ? '.jpg' : '.png');
                $file_path = 'img/avatars/';
                $data['avatar'] = $file_name;
            }
        } else {
            $data['avatar'] = '';
        }

        // IF THERE ARE NO ERRORS
        if (!$invalid_values) {
            if (!empty($data['avatar'])) {
                move_uploaded_file($_FILES['avatar']['tmp_name'], $file_path . $file_name);
            }

            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (registration_date, email, username, password, avatar, contacts) VALUES (CURRENT_TIMESTAMP(), ?, ?, ?, ?, ?)";

            $new_user_id = db_insert_data($con, $sql, [
                $data['email'],
                $data['name'],
                $data['password'],
                $data['avatar'],
                $data['contacts']
            ]);

            if ($new_user_id) {
                header('Location: index.php');
            }
        }
    }

    $page_content = include_template('sign-up.php', ['categories' => $categories, 'invalid_values' => $invalid_values, 'data' => $data]);
    $layout_content = include_template('layout.php', ['title' => 'Регистрация', 'is_auth' => $is_auth, 'user_name' => $user_name, 'content' => $page_content, 'categories' => $categories]);

    print($layout_content);
?>
