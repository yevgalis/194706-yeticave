<?php
    require_once('mysql_helper.php');

    function include_template($name, $data) {
        $name = 'templates/' . $name;
        $result = '';

        if (!is_readable($name)) {
            return $result;
        }

        ob_start();
        extract($data);
        require $name;

        $result = ob_get_clean();

        return $result;
    }

    function render_price ($price, $show_money_sign = false) {
        $price = ceil($price);

        if ($price >= 1000) {
            $price = number_format($price, 0, '.', ' ');
        }

        return $show_money_sign === true ? $price . '<b class="rub">р</b>' : $price;
    }

    function show_remaining_time ($end_date) {
        $now = date_create('now');
        $close_date = date_create($end_date);
        $diff = date_diff($now, $close_date);

        if ($diff->d >= 1 && $diff->d <= 3) {
            $format = $diff->d . ' дн.';
        } else if ($diff->d > 3) {
            $format = date('d.m.Y', strtotime($end_date));
        } else {
            $format = $diff->h . ':' . $diff->i;
        }

        return $format;
    }

    function db_insert_data($link, $data = []) {
        $sql = "INSERT INTO lots (creation_date, name, description, image, start_price, end_date, step, author_id, winner_id, category_id) VALUES (CURRENT_TIMESTAMP(), ?, ?, ?, ?, ?, ?, 1, NULL, ?)";

        $stmt = db_get_prepare_stmt($link, $sql, [
            $data['lot-name'],
            $data['message'],
            $data['filename'],
            $data['lot-rate'],
            $data['lot-date'],
            $data['lot-step'],
            $data['category_id']
        ]);

        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $id = mysqli_insert_id($link);
        } else {
            exit('Произошла ошибка');
        }

        return $id;
    }
?>
