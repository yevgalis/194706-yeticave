<?php
    require_once('init.php');

    $lots = [];
    $is_index_page = true;

    $sql = 'SELECT 	l.lot_id, l.name AS title, l.start_price, l.end_date, l.image,
                    CASE
                        WHEN (SELECT max(b.amount) FROM bets b WHERE b.lot_id = l.lot_id) IS NULL THEN l.start_price
                        ELSE (SELECT max(b.amount) FROM bets b WHERE b.lot_id = l.lot_id)
                    END price, c.name AS category
                FROM lots l
                INNER JOIN categories c USING(category_id)
                WHERE l.end_date > NOW()
                ORDER BY l.creation_date DESC
                LIMIT 6';

    $result = mysqli_query($con, $sql);

    if ($result) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    $page_content = include_template('index.php', [
        'categories' => $categories,
        'lots' => $lots
        ]);

    $layout_content = include_template('layout.php', [
        'title' => 'Главная',
        'is_index' => $is_index_page,
        'user' => $user,
        'content' => $page_content,
        'categories' => $categories
        ]);

    print($layout_content);
?>
