<?php
    require_once('init.php');

    $title_max_length = 100;
    $decription_max_length = 500;
    $max_filesize = 1;  // MB
    $category_match;
    $invalid_values = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // LOT TITLE CHECK
        if (!trim($_POST['lot-name'])) {
            $invalid_values += ['lot-name' => 'Введите наименование лота'];
        }

        if (strlen(trim($_POST['lot-name'])) > $title_max_length) {
            $invalid_values += ['lot-name' => 'Максимальное количество символов - ' . $title_max_length . '. Текущее количество - ' . strlen(trim($_POST['lot-name']))];
        }

        // LOT CATEGORY CHECK
        foreach ($categories as $category) {
            strtolower($category['name']) === strtolower($_POST['category']) ? $category_match = true : $category_match = false;

            if ($category_match) {
                $category_id = $category['category_id'];
                break;
            }
        }

        if (!$category_match) {
            $invalid_values += ['category' => 'Выберите категорию'];
        }

        // LOT DESCRIPTION CHECK
        if (!trim($_POST['message'])) {
            $invalid_values += ['message' => 'Напишите описание лота'];
        }

        if (strlen(trim($_POST['message'])) > $decription_max_length) {
            $invalid_values += ['message' => 'Максимальное количество символов - ' . $decription_max_length . '. Текущее количество - ' . strlen(trim($_POST['message']))];
        }

        // LOT FILE CHECK
        if (isset($_FILES['item-photo']) && is_uploaded_file($_FILES['item-photo']['tmp_name'])) {
            $file_type = mime_content_type($_FILES['item-photo']['tmp_name']);
            $file_size = $_FILES['item-photo']['size'];

            if ($file_type !== 'image/png' && $file_type !== 'image/jpeg') {
                $invalid_values += ['item-photo' => 'Неверный формат файла. Загрузите картинку в формате jpeg, jpg или png'];
            } elseif ($file_size > $max_filesize * 1024 * 1024) {
                $invalid_values += ['item-photo' => 'Размер файла превышает допустимый. Максимальный размер - ' . $max_filesize . 'мб'];
            }

        } else {
            $invalid_values += ['item-photo' => 'Загрузите фотографию'];
        }

        // LOT RATE CHECK
        if (!is_numeric($_POST['lot-rate']) || $_POST['lot-rate'] <= 0) {
            $invalid_values += ['lot-rate' => 'Введите целое положительное число больше 0'];
        }

        // LOT STEP CHECK
        if (!is_numeric($_POST['lot-step']) || $_POST['lot-step'] <= 0) {
            $invalid_values += ['lot-step' => 'Введите целое положительное число больше 0'];
        }

        // LOT END DATE CHECK
        $min_date = date_create('tomorrow + 1 day')->format('Y-m-d');
        $end_date = date('Y-m-d', strtotime($_POST['lot-date']));

        if ($end_date < $min_date) {
            $invalid_values += ['lot-date' => 'Дата не может быть меньше чем ' . date('d.m.Y', strtotime($min_date))];
        }

        if (!$invalid_values) {
            $new_file_name = uniqid('lot-') . ($file_type === 'image/jpeg' ? '.jpg' : '.png');
            $file_path = 'img/';
            move_uploaded_file($_FILES['item-photo']['tmp_name'], $file_path . $new_file_name);

            $lot_title = trim($_POST['lot-name']);
            $lot_desc = $_POST['message'];
            $lot_rate = intval($_POST['lot-rate']);
            $lot_date = $_POST['lot-date'];
            $lot_step = intval($_POST['lot-step']);

            $sql = "INSERT INTO lots (creation_date, name, description, image, start_price, end_date, step, author_id, winner_id, category_id)
            VALUES (CURRENT_TIMESTAMP(), '$lot_title', '$lot_desc', '" . $new_file_name . "', '$lot_rate', '$lot_date', '$lot_step', 1, NULL, '$category_id')";

            $result = mysqli_query($con, $sql);

            if ($result) {
                $new_lot_id = mysqli_insert_id($con);
                header('Location: lot.php?id=' . $new_lot_id);
            } else {
                var_dump(mysqli_error($con));
            }
        }
    }

    $page_content = include_template('add-lot.php', ['categories' => $categories, 'invalid_values' => $invalid_values]);
    $layout_content = include_template('layout.php', ['title' => 'Добавление лота', 'is_auth' => $is_auth, 'user_name' => $user_name, 'content' => $page_content, 'categories' => $categories]);

    print($layout_content);
?>
