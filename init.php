<?php
    date_default_timezone_set('Asia/Novosibirsk');
    require_once('functions.php');

    $con = mysqli_connect('127.0.0.1', 'root', 'qwerty123', 'yeticave_194706');

    if ($con === false) {
        exit('Извините, ведутся технические работы');
    }

    mysqli_set_charset($con, "utf8");

    $categories = [];
    $sql = 'select * from categories';
    $result = mysqli_query($con, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
?>
