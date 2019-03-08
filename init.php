<?php
    date_default_timezone_set('Asia/Novosibirsk');
    require_once('functions.php');

    session_start();

    $user = empty($_SESSION['user']) ? [] : $_SESSION['user'];
    $is_index_page = false;
    $categories = [];
    $sql = '';

    //  SET DB CONNECTION
    $con = mysqli_connect('127.0.0.1', 'root', 'qwerty123', 'yeticave_194706');

    if ($con === false) {
        exit('Извините, ведутся технические работы');
    }

    mysqli_set_charset($con, "utf8");

    //  GET CATEGORIES
    $sql = 'select * from categories';
    $result = mysqli_query($con, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    //  GET USER DATA IF LOGGED IN
    if (!empty($user)) {
        $sql = 'SELECT * FROM users WHERE user_id = ?';
        $user = db_fetch_data($con, $sql, [$user['user_id']], true);
    }
?>
