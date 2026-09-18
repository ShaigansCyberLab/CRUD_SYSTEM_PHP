<?php

define('CONFIG', [
    'data_file'   => APP_NAME . 'data.json',
    'db'          => 'mysql:dbname=glossary;host=127.0.0.1;port=3306;charset=utf8mb4',
    'db_user'     => getenv('DB_USER') ?: 'glossary_app',
    'db_password' => getenv('DB_PASS') ?: 'db123',
    'users'       => [
        'admin@admin.com' => '$2y$12$bSBFXUs8wtbToAQmwBNd2uTTYiyr4HPOjYop4hLz7jtQuAYwq6uLS',
    ],
]);
