<?php
    require_once('init.php');
    //require_once('data.php');

    $lots_content = include_template('lot.php', ['lots' => $lots]);
    $page_content = include_template('index.php', ['categories' => $categories, 'lots' => $lots_content]);
    $layout_content = include_template('layout.php', ['title' => 'Главная', 'is_auth' => $is_auth, 'user_name' => $user_name, 'content' => $page_content, 'categories' => $categories]);

    print($layout_content);
?>
