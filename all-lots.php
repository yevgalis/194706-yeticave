<?php
    require_once('init.php');

    $lots = [];
    $category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

    if (empty($category_id)) {
        error_redirect(
            '404',
            '404 Страница не найдена',
            'Данной страницы не существует',
            'Страница не найдена',
            $categories
        );

        exit();
    }

    $sql = 'SELECT 	l.lot_id, l.name AS title, l.start_price, l.end_date, l.image,
                    CASE
                        WHEN (SELECT max(b.amount) FROM bets b WHERE b.lot_id = l.lot_id) IS NULL THEN l.start_price
                        ELSE (SELECT max(b.amount) FROM bets b WHERE b.lot_id = l.lot_id)
                    END price, c.name AS category
                FROM lots l
                INNER JOIN categories c USING(category_id)
                WHERE l.end_date > NOW() AND l.category_id = ?
                ORDER BY l.creation_date DESC
                LIMIT 6';

    $lots = db_fetch_data($con, $sql, [$category_id]);

    $page_content = include_template('all-lots.php', [
        'categories' => $categories,
        'lots' => $lots
        ]);

    $layout_content = include_template('layout.php', [
        'title' => 'Все лоты',
        'is_index' => $is_index_page,
        'user' => $user,
        'content' => $page_content,
        'categories' => $categories
        ]);

    print($layout_content);
?>
