<?php
    $is_auth = rand(0, 1);
    $user_name = 'John Doe';

    $categories = [
        [
            'name' => 'Доски и лыжи',
            'class' => 'boards'
        ],
        [
            'name' => 'Крепления',
            'class' => 'attachment'
        ],
        [
            'name' => 'Ботинки',
            'class' => 'boots'
        ],
        [
            'name' => 'Одежда',
            'class' => 'clothing'
        ],
        [
            'name' => 'Инструменты',
            'class' => 'tools'
        ],
        [
            'name' => 'Разное',
            'class' => 'other'
        ]
    ];

    $lots = [
        [
            'title' => '2014 Rossignol District Snowboard',
            'category' => 'Доски и лыжи',
            'price' => 10999,
            'image' => 'img/lot-1.jpg'
        ],
        [
            'title' => 'DC Ply Mens 2016/2017 Snowboard',
            'category' => 'Доски и лыжи',
            'price' => 159999,
            'image' => 'img/lot-2.jpg'
        ],
        [
            'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
            'category' => 'Крепления',
            'price' => 8000,
            'image' => 'img/lot-3.jpg'
        ],
        [
            'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
            'category' => 'Ботинки',
            'price' => 10999,
            'image' => 'img/lot-4.jpg'
        ],
        [
            'title' => 'Куртка для сноуборда DC Mutiny Charocal',
            'category' => 'Одежда',
            'price' => 7500,
            'image' => 'img/lot-5.jpg'
        ],
        [
            'title' => 'Маска Oakley Canopy',
            'category' => 'Разное',
            'price' => 5400,
            'image' => 'img/lot-6.jpg'
        ]
    ];
?>
