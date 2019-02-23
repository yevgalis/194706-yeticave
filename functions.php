<?php
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
?>
