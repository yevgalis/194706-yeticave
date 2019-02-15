<?php
    require_once('functions.php');
    require_once('data.php');

    $page_content = include_template('index.php', ['categories' => $categories, 'ads' => $ads]);
    $layout_content = include_template('layout.php', ['title' => 'Главная', 'content' => $page_content, 'categories' => $categories]);

    print($layout_content);
?>
