<?php
    date_default_timezone_set('Asia/Novosibirsk');
    require_once('functions.php');

    $con = mysqli_connect('127.0.0.1', 'root', 'qwerty123', 'yeticave_194706');
    mysqli_set_charset($con, "utf8");

    if ($con === false) {
        exit('Извините, ведутся технические работы');
    }

    $categories = [];
    $lots = [];

    $sql = 'select * from categories';
    $result = mysqli_query($con, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    $sql = 'SELECT 	l.name AS title, l.start_price, l.image,
                CASE
                    WHEN (SELECT max(b.amount) FROM bets b WHERE b.lot_id = l.lot_id) IS NULL THEN l.start_price
                    ELSE (SELECT max(b.amount) FROM bets b WHERE b.lot_id = l.lot_id)
                END price, c.name AS category
            FROM lots l
            INNER JOIN categories c USING(category_id)
            WHERE l.end_date > NOW()
            ORDER BY l.creation_date DESC';
    $result = mysqli_query($con, $sql);

    if ($result) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
?>
