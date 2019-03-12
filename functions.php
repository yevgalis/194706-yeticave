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

    function show_remaining_time ($end_date, $is_lot_details = false) {
        $format = '';
        $now = time();
        $close_date = strtotime($end_date);
        $date_diff = $close_date - $now;

        if ($is_lot_details) {
            $hours = $date_diff / 3600;
            $hours_formated = strlen(floor($hours)) < 2 ? '0' . floor($hours) : floor($hours);
            $minutes = ($hours - $hours_formated) * 60;
            $minutes_formated = strlen(floor($minutes)) < 2 ? '0' . floor($minutes) : floor($minutes);

            $format = $hours_formated . ':' . $minutes_formated;
        } else {
            $days = $date_diff / 86400;
            $days_formated = floor($days);
            $hours = ($days - $days_formated) * 24;
            $hours_formated = strlen(floor($hours)) < 2 ? '0' . floor($hours) : floor($hours);
            $minutes = ($hours - $hours_formated) * 60;
            $minutes_formated = strlen(floor($minutes)) < 2 ? '0' . floor($minutes) : floor($minutes);

            if ($days >= 1 && $days <= 3) {
                $format = $days_formated . ' дн. ' . $hours_formated . ':' . $minutes_formated;
            } else if ($days > 3) {
                $format = date('d.m.Y', strtotime($end_date));
            } else {
                $format = $hours_formated . ':' . $minutes_formated;
            }
        }

        return $format;
    }

    function show_bet_time ($bet_date) {
        $format = '';
        $now = time();
        $bet_time = strtotime($bet_date);
        $date_diff = $now - $bet_time;

        $days = $date_diff / 86400;
        $days_formated = floor($days);
        $hours = ($days - $days_formated) * 24;
        $hours_formated = floor($hours);
        $minutes = ($hours - $hours_formated) * 60;
        $minutes_formated = floor($minutes);

        if ($bet_time < strtotime('yesterday')) {
            $format = date('d.m.Y H:i', $bet_time);
        }

        if ($bet_time >= strtotime('yesterday') && $bet_time < strtotime('today')) {
            $format = 'Вчера в ' . date('H:i', $bet_time);
        }

        if ($bet_time >= strtotime('today')) {
            if ($minutes_formated < 1 && $hours_formated < 1) {
                $format = 'Только что';
            } else if ($minutes_formated >= 1 && $hours_formated < 1) {
                $format = $minutes_formated . ' мин. назад';
            } else if ($hours_formated >= 1 && $hours_formated <= 3) {
                $format = $hours_formated . ' ч. назад';
            } else {
                $format = 'Сегодня в ' . date('H:i', $bet_time);
            }
        }

        return $format;
    }

    function error_redirect($err_code, $err_title, $err_desc, $page_title, $categories) {
        $header = '';

        switch ($err_code) {
            case 401: $header = 'HTTP/1.1 401 Unauthorized';
            break;
            case 403: $header = 'HTTP/1.1 403 Forbidden';
            break;
            case 404: $header = 'HTTP/1.1 404 Page Not Found';
            break;
            case 500: $header = 'HTTP/1.1 500 Internal Server Error';
            break;
            case 503: $header = 'HTTP/1.1 503 Service Unavailable';
            break;
            default:
                $header = 'HTTP/1.1 404 Page Not Found';
            break;
        }

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

        if (!$stmt) {
            exit('503 Внутренняя ошибка сервера. Попробуйте позже');
        }

        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if (!$res) {
            exit('503 Внутренняя ошибка сервера. Попробуйте позже');
        }

        $result = $is_single_res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : mysqli_fetch_all($res, MYSQLI_ASSOC);

        return $result;
    }

    function db_insert_data($link, $sql, $data = []) {
        $stmt = db_get_prepare_stmt($link, $sql, $data);
        $result = mysqli_stmt_execute($stmt);

        if (!$result) {
            exit('Произошла ошибка');
        }

        $result = mysqli_insert_id($link);

        return $result;
    }
?>
