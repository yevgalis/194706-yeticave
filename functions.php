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

    function renderPrice ($price) {
        $price = ceil($price);

        if ($price >= 1000) {
            $price = number_format($price, 0, '.', ' ');
        }

        return  $price . '<b class="rub">р</b>';
    }

    function getTimeDifference () {
        date_default_timezone_set("Asia/Novosibirsk");

        $curDate = date_create("now");
        $tomorrowMidnight = date_create("tomorrow midnight");
        $diff = date_interval_format(date_diff($curDate, $tomorrowMidnight), '%H:%i');

        return $diff;
    }
?>
