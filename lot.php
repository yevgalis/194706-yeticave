<?php
    require_once('init.php');

    $sql = '';
    $lot_data = [];
    $form_data = [];
    $invalid_values = [];
    $lot_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    //  GET LOT DATA
    $sql = 'SELECT 	l.lot_id, l.name AS title, l.description, l.start_price, l.end_date, l.image,
            CASE
                WHEN (SELECT max(b.amount) FROM bets b WHERE b.lot_id = l.lot_id) IS NULL THEN l.start_price
                ELSE (SELECT max(b.amount) FROM bets b WHERE b.lot_id = l.lot_id)
            END price, l.step, c.name AS category_name
            FROM lots l
            INNER JOIN categories c USING(category_id)
            WHERE l.lot_id = ?';

    $lot_data = db_fetch_data($con, $sql, [$lot_id], true);

    if (empty($lot_data)) {
        error_redirect(
            '404',
            '404 Страница не найдена',
            'Данной страницы не существует на сайте',
            'Страница не найдена',
            $categories
        );

        exit();
    }

    //  BET FORM PROCESSING
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //  AUTHORIZATION CHECK
        if (empty($user)) {
            error_redirect(
                '403',
                'Доступ запрещен',
                'Для добавления ставки <a href="login.php">Войдите на сайт</a> или <a href="sign-up.php">Зарегистрируйтесь</a>',
                'Доступ запрещен',
                $categories
            );

            exit();
        }

        //  LOT END DATE CHECK
        if ((strtotime($lot_data['end_date']) - time()) < 0) {
            error_redirect(
                '403',
                'Время аукциона истекло',
                'Время аукциона истекло',
                'Время аукциона истекло',
                $categories);

            exit();
        }

        //  FORM VALIDATION
        $keys = ['cost'];

        foreach ($keys as $key) {
            if (isset($_POST[$key]) && !empty(trim($_POST[$key]))) {
                $form_data[$key] = trim($_POST[$key]);
            } else {
                $invalid_values[$key] = 'Это поле необходимо заполнить';
            }
        }

        // BET AMOUNT CHECK
        if (empty($invalid_values['cost']) && intval($form_data['cost']) < ($lot_data['price'] + $lot_data['step'])) {
            $invalid_values['cost'] = 'Введите целое положительное число не меньше ' . ($lot_data['price'] + $lot_data['step']);
        }

        //  NO ERRORS
        if (empty($invalid_values)) {
            $sql = "INSERT INTO bets (amount, user_id, lot_id) VALUES (?, ?, ?)";

            $new_lot_id = db_insert_data($con, $sql, [
                $form_data['cost'],
                $user['user_id'],
                $lot_data['lot_id']
            ]);

            $lot_data['price'] = $form_data['cost'];
            $form_data['cost'] = '';
        }
    }

    //  GET CURRENT BET HISTORY
    $sql = 'SELECT b.*, u.username FROM bets b INNER JOIN users u USING(user_id) WHERE lot_id = ? ORDER BY b.bet_date DESC';
    $bets = db_fetch_data($con, $sql, [$lot_id]);

    //  RENDER PAGE
    $lot_content = include_template('lot_details.php', [
        'categories' => $categories,
        'lot' => $lot_data,
        'bets' => $bets,
        'invalid_values' => $invalid_values,
        'data' => $form_data,
        'user' => $user
        ]);

    $layout_content = include_template('layout.php', [
        'title' => $lot_data['title'],
        'is_index' => $is_index_page,
        'user' => $user,
        'content' => $lot_content,
        'categories' => $categories
        ]);

    print($layout_content);
?>
