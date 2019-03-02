<?php
    require_once('init.php');

    $lot_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    $lot = [];
    $sql = 'SELECT 	l.lot_id, l.name AS title, l.description, l.start_price, l.end_date, l.image,
            CASE
                WHEN (SELECT max(b.amount) FROM bets b WHERE b.lot_id = l.lot_id) IS NULL THEN l.start_price
                ELSE (SELECT max(b.amount) FROM bets b WHERE b.lot_id = l.lot_id)
            END price, l.step, c.name AS category_name
            FROM lots l
            INNER JOIN categories c USING(category_id)
            WHERE l.lot_id = ' . $lot_id;
    $result = mysqli_query($con, $sql);

    if ($result) {
        $lot = mysqli_fetch_array($result, MYSQLI_ASSOC);
    }

    if ($lot === NULL) {
        header('HTTP/1.0 404 Page Not Found');

        $page_not_found = include_template('404.php', ['categories' => $categories]);
        $layout_content = include_template('layout.php', ['title' => 'Страница не найдена', 'is_auth' => $is_auth, 'user_name' => $user_name, 'content' => $page_not_found, 'categories' => $categories]);

        print($layout_content);
        exit();
    }

    $lot_content = include_template('lot_details.php', [
        'categories' => $categories,
        'lot' => $lot
        ]);

    $layout_content = include_template('layout.php', [
        'title' => $lot['title'],
        // 'is_auth' => $is_auth,
        // 'user_name' => $user_name,
        'content' => $lot_content,
        'categories' => $categories]);

    print($layout_content);
?>
