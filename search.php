<?php
    require_once('init.php');

    $lots = [];
    $search = (isset($_GET['search']) && !empty(trim($_GET['search']))) ? trim($_GET['search']) : '';

    //  PAGINATION
    $cur_page = $_GET['page'] ?? 1;
    $page_items = 6;

    $sql = "SELECT COUNT(*) AS cou FROM lots WHERE MATCH(name, description) AGAINST(? IN BOOLEAN MODE) AND end_date > NOW()";
    $items_count = db_fetch_data($con, $sql, [$search], true);

    $pages_count = ceil($items_count['cou'] / $page_items);
    $offset = ($cur_page - 1) * $page_items;

    $pages = range(1, $pages_count);
    $address = $_SERVER['PHP_SELF'] . '?search=' . $search . '&find=Найти&';

    //  SEARCH LOTS
    if (!empty($search)) {
        $sql = 'SELECT 	l.lot_id, l.name AS title, l.end_date, l.image,
                        CASE
                            WHEN (SELECT max(b.amount) FROM bets b WHERE b.lot_id = l.lot_id) IS NULL THEN l.start_price
                            ELSE (SELECT max(b.amount) FROM bets b WHERE b.lot_id = l.lot_id)
                        END price, c.name AS category
                FROM lots l
                    INNER JOIN categories c USING(category_id)
                WHERE MATCH(l.name, l.description) AGAINST(? IN BOOLEAN MODE) AND l.end_date > NOW()
                LIMIT ' . $page_items . ' OFFSET ' . $offset;

        $lots = db_fetch_data($con, $sql, [$search]);
    }

    //  RENDER PAGE
    $pagination_content = include_template('pagination.php', [
        'cur_page' => $cur_page,
        'pages' => $pages,
        'pages_count' => $pages_count,
        'address' => $address
        ]);

    $page_content = include_template('search.php', [
        'categories' => $categories,
        'lots' => $lots,
        'search' => $search,
        'pagination_content' => $pagination_content
        ]);

    $layout_content = include_template('layout.php', [
        'title' => 'Результаты поиска',
        'is_index' => $is_index_page,
        'user' => $user,
        'content' => $page_content,
        'categories' => $categories,
        'search' => $search,
        ]);

    print($layout_content);
?>
