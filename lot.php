<?php
    require_once('init.php');

    $sql = '';
    $lot = [];
    $lot_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    $sql = 'SELECT 	l.lot_id, l.name AS title, l.description, l.start_price, l.end_date, l.image,
            CASE
                WHEN (SELECT max(b.amount) FROM bets b WHERE b.lot_id = l.lot_id) IS NULL THEN l.start_price
                ELSE (SELECT max(b.amount) FROM bets b WHERE b.lot_id = l.lot_id)
            END price, l.step, c.name AS category_name
            FROM lots l
            INNER JOIN categories c USING(category_id)
            WHERE l.lot_id = ?';

    $lot = db_fetch_data($con, $sql, [$lot_id], true);

    if (empty($lot)) {
        header('HTTP/1.1 404 Page Not Found');

        $page_content = include_template('error_redirect.php', [
            'categories' => $categories,
            'error_title' => '404 Страница не найдена',
            'error_text' => 'Данной страницы не существует на сайте',
            ]);

        $layout_content = include_template('layout.php', [
            'title' => 'Страница не найдена',
            'content' => $page_content,
            'categories' => $categories
            ]);

        print($layout_content);
        exit();
    } else {
        $sql = 'SELECT b.*, u.username FROM bets b INNER JOIN users u USING(user_id) WHERE lot_id = ?';
        $bets = db_fetch_data($con, $sql, [$lot_id]);
    }

    $lot_content = include_template('lot_details.php', [
        'categories' => $categories,
        'lot' => $lot,
        'bets' => $bets
        ]);

    $layout_content = include_template('layout.php', [
        'title' => $lot['title'],
        'content' => $lot_content,
        'categories' => $categories]);

    print($layout_content);
?>
