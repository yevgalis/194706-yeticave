<?php
    require_once('init.php');

    $lots = [];
    $is_index_page = true;

    //  PAGINATION
    $cur_page = $_GET['page'] ?? 1;
    $page_items = 6;

    $sql = "SELECT COUNT(*) AS cou FROM lots l WHERE l.end_date > NOW()";
    $items_count = db_fetch_data($con, $sql, [], true);

    $pages_count = ceil($items_count['cou'] / $page_items);
    $offset = ($cur_page - 1) * $page_items;

    $pages = range(1, $pages_count);
    $address = $_SERVER['PHP_SELF'] . '?';

    //  GET ACTIVE LOTS
    $sql = 'SELECT 	l.lot_id, l.name AS title, l.start_price, l.end_date, l.image, COALESCE(max(b.amount), l.start_price) AS price, c.name AS category
            FROM lots l
                INNER JOIN categories c USING(category_id)
                LEFT JOIN bets b USING(lot_id)
            WHERE l.end_date > NOW()
            GROUP BY l.lot_id
            ORDER BY l.creation_date DESC
            LIMIT ' . $page_items . ' OFFSET ' . $offset;

    $lots = db_fetch_data($con, $sql, []);

    //  RENDER PAGE
    $pagination_content = include_template('pagination.php', [
        'cur_page' => $cur_page,
        'pages' => $pages,
        'pages_count' => $pages_count,
        'address' => $address
        ]);

    $page_content = include_template('index.php', [
        'categories' => $categories,
        'lots' => $lots,
        'pagination_content' => $pagination_content
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
