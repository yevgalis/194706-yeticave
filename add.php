<?php
    require_once('init.php');

    $title_max_length = 100;
    $decription_max_length = 500;
    $max_filesize = 1;  // MB
    $data = [];
    $invalid_values = [];
    $category_match = false;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $keys = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
        $file_name = '';

        foreach ($keys as $key) {
            if (isset($_POST[$key]) && !empty(trim($_POST[$key]))) {
                $data[$key] = trim($_POST[$key]);
            } else {
                $invalid_values[$key] = 'Это поле необходимо заполнить';
            }
        }

        // LOT TITLE CHECK
        if (empty($invalid_values['lot-name']) && strlen($data['lot-name']) > $title_max_length) {
            $invalid_values['lot-name'] = 'Наименование лота слишком длинное. Максимальное количество символов - ' . $title_max_length;
        }

        // LOT CATEGORY CHECK
        if (empty($invalid_values['category'])) {
            foreach ($categories as $category) {
                if (strtolower($category['name']) === strtolower($data['category'])) {
                    $category_match = true;
                }

                if ($category_match) {
                    $category_id = $category['category_id'];
                    $data['category_id'] = $category['category_id'];
                    break;
                }
            }

            if (!$category_match) {
                $invalid_values['category'] = 'Выберите категорию';
            }
        }


        // LOT DESCRIPTION CHECK
        if (empty($invalid_values['message']) && strlen($data['message']) > $decription_max_length) {
            $invalid_values['message'] = 'Описание слишком длинное. Максимальное количество символов - ' . $decription_max_length;
        }

        // LOT FILE CHECK
        if (isset($_FILES['item-photo']) && is_uploaded_file($_FILES['item-photo']['tmp_name'])) {
            $file_type = mime_content_type($_FILES['item-photo']['tmp_name']);
            $file_size = $_FILES['item-photo']['size'];

            if ($file_type !== 'image/png' && $file_type !== 'image/jpeg') {
                $invalid_values['item-photo'] = 'Неверный формат файла. Загрузите картинку в формате jpeg, jpg или png';
            } elseif ($file_size > $max_filesize * 1024 * 1024) {
                $invalid_values['item-photo'] = 'Размер файла превышает допустимый. Максимальный размер - ' . $max_filesize . 'мб';
            }

        } else {
            $invalid_values['item-photo'] = 'Загрузите фотографию';
        }

        // LOT RATE CHECK
        if (empty($invalid_values['lot-rate']) && intval($data['lot-rate']) <= 0) {
            $invalid_values['lot-rate'] = 'Введите целое положительное число';
        }

        // LOT STEP CHECK
        if (empty($invalid_values['lot-step']) && intval($data['lot-step']) <= 0) {
            $invalid_values['lot-step'] = 'Введите целое положительное число';
        }

        // LOT END DATE CHECK
        if (empty($invalid_values['lot-date'])) {
            $min_date = date_create('tomorrow + 1 day')->format('Y-m-d');
            $end_date = date('Y-m-d', strtotime($data['lot-date']));

            if ($end_date < $min_date) {
                $invalid_values['lot-date'] = 'Дата не может быть меньше чем ' . date('d.m.Y', strtotime($min_date));
            }
        }

        // IF THERE ARE NO ERRORS
        if (!$invalid_values) {
            $file_name = uniqid('lot-') . ($file_type === 'image/jpeg' ? '.jpg' : '.png');
            $data['filename'] = $file_name;
            $file_path = 'img/';

            move_uploaded_file($_FILES['item-photo']['tmp_name'], $file_path . $file_name);

            $new_lot_id = db_insert_data($con, $data);

            if ($new_lot_id) {
                header('Location: lot.php?id=' . $new_lot_id);
            }
        }
    }

    $page_content = include_template('add-lot.php', ['categories' => $categories, 'invalid_values' => $invalid_values]);
    $layout_content = include_template('layout.php', ['title' => 'Добавление лота', 'is_auth' => $is_auth, 'user_name' => $user_name, 'content' => $page_content, 'categories' => $categories]);

    print($layout_content);
?>
