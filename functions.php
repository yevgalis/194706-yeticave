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

    function error_redirect($err_code, $err_title, $err_desc, $page_title, $categories) {
        $header = '';
        $err_codes_desc = [
            '403' => 'HTTP/1.1 403 Forbidden',
            '404' => 'HTTP/1.1 404 Page Not Found'
        ];

        ($err_code !== '403' && $err_code !== '404') ? $header = 'HTTP/1.1 404 Page Not Found' : $err_codes_desc[$err_code];

        header($header);

        $page_content = include_template('error_redirect.php', [
            'categories' => $categories,
            'error_title' => $err_title,
            'error_text' => $err_desc,
            ]);

        $layout_content = include_template('layout.php', [
            'title' => $page_title,
            'content' => $page_content,
            'categories' => $categories
            ]);

        print($layout_content);
    }

    function db_fetch_data($link, $sql, $data = [], $is_single_res = false) {
        $result = [];
        $stmt = db_get_prepare_stmt($link, $sql, $data);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($res) {
            $result = $is_single_res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : mysqli_fetch_all($res, MYSQLI_ASSOC);
        }

        return $result;
    }

    function db_insert_data($link, $sql, $data = []) {
        $stmt = db_get_prepare_stmt($link, $sql, $data);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $result = mysqli_insert_id($link);
        } else {
            exit('Произошла ошибка');
        }

      return $result;
    }
?>
