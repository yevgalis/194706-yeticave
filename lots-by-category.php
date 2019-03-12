<?php
    require_once('init.php');

    $sql = '';
    $lots = [];
    $category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

    if (empty($category_id)) {
        http_response_code(404);

        error_redirect(
            http_response_code(),
            'Страница не найдена',
            'Данной страницы не существует',
            'Страница не найдена',
            $categories
        );

        exit();
    }

    $sql = 'SELECT COUNT(*) AS cou FROM categories WHERE category_id = ?';
    $category_check = db_fetch_data($con, $sql, [$category_id], true);

    if (empty($category_check['cou'])) {
        http_response_code(404);

        error_redirect(
            http_response_code(),
            'Страница не найдена',
            'Данной страницы не существует',
            'Страница не найдена',
            $categories
        );

        exit();
    }

    //  PAGINATION
    $cur_page = $_GET['page'] ?? 1;
    $page_items = 6;

    $sql = "SELECT COUNT(*) AS cou FROM lots l WHERE l.end_date > NOW() AND l.category_id = ?";
    $items_count = db_fetch_data($con, $sql, [$category_id], true);

    $pages_count = ceil($items_count['cou'] / $page_items);
    $offset = ($cur_page - 1) * $page_items;

    $pages = range(1, $pages_count);
    $address = $_SERVER['PHP_SELF'] . '?category_id=' . $_GET['category_id'] . '&';

    //  GET LOTS FOR SPECIFIC CATEGORY
    $sql = 'SELECT 	l.lot_id, l.name AS title, l.start_price, l.end_date, l.image, COALESCE(max(b.amount), l.start_price) AS price, c.name AS category
            FROM lots l
                INNER JOIN categories c USING(category_id)
                LEFT JOIN bets b USING(lot_id)
            WHERE l.end_date > NOW() AND l.category_id = ?
            GROUP BY l.lot_id
            ORDER BY l.creation_date DESC
            LIMIT ' . $page_items . ' OFFSET ' . $offset;

    $lots = db_fetch_data($con, $sql, [$category_id]);

    //  GET CATEGORY NAME
    $sql = 'SELECT name FROM categories WHERE category_id = ?';
    $lots_category = db_fetch_data($con, $sql, [$category_id], true);

    //  RENDER PAGE
    $pagination_content = include_template('pagination.php', [
        'cur_page' => $cur_page,
        'pages' => $pages,
        'pages_count' => $pages_count,
        'address' => $address
        ]);

    $page_content = include_template('lots-by-category.php', [
        'categories' => $categories,
        'lots' => $lots,
        'lots_category' => $lots_category,
        'pagination_content' => $pagination_content
        ]);

    $layout_content = include_template('layout.php', [
        'title' => 'Все лоты',
        'user' => $user,
        'content' => $page_content,
        'categories' => $categories
        ]);

    print($layout_content);
?>
