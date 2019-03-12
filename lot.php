<?php
    require_once('init.php');

    $sql = '';
    $lot_data = [];
    $form_data = [];
    $invalid_values = [];
    $lot_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    //  GET LOT DATA
    $sql = 'SELECT 	l.lot_id, l.name AS title, l.description, l.start_price, l.end_date, l.image, COALESCE(max(b.amount), l.start_price) AS price, l.step, c.name AS category_name, l.author_id, COALESCE((SELECT t.user_id FROM bets t WHERE t.lot_id = l.lot_id ORDER BY t.bet_id DESC LIMIT 1), 0) AS last_bet_user_id
            FROM lots l
                INNER JOIN categories c USING(category_id)
                LEFT JOIN bets b USING(lot_id)
            WHERE l.lot_id = ?
            GROUP BY l.lot_id';

    $lot_data = db_fetch_data($con, $sql, [$lot_id], true);

    if (empty($lot_data)) {
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

    //  BET FORM PROCESSING
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //  AUTHORIZATION CHECK
        if (empty($user)) {
            http_response_code(401);

            error_redirect(
                http_response_code(),
                'Доступ только для авторизированных пользователей',
                'Для добавления ставки <a href="login.php">Войдите на сайт</a> или <a href="sign-up.php">Зарегистрируйтесь</a>',
                'Доступ запрещен',
                $categories
            );

            exit();
        }

        //  LOT END DATE CHECK
        if ((strtotime($lot_data['end_date']) - time()) < 0) {
            http_response_code(403);

            error_redirect(
                http_response_code(),
                'Доступ запрещен',
                'Время аукциона истекло',
                'Время аукциона истекло',
                $categories);

            exit();
        }

        //  LOT AUTHOR CHECK
        if ($user['user_id'] === $lot_data['author_id']) {
            header("Location: /index.php");
            exit();
        }

        //  LAST BET USER CHECK
        if ($user['user_id'] === $lot_data['last_bet_user_id']) {
            header("Location: /index.php");
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

            //  UPDATE CURRENT LOT DATA && CLEAR INPUT
            $lot_data['price'] = $form_data['cost'];
            $lot_data['last_bet_user_id'] = $user['user_id'];
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
        'user' => $user,
        'content' => $lot_content,
        'categories' => $categories
        ]);

    print($layout_content);
?>
