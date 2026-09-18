<?php

require __DIR__ . '/../controller/app.php';

ensure_user_is_authenticated();

$terms = Data::get_terms();

$view_bag = [
    'title' => 'Admin - Glossary',
];

view('admin/index', [
    'view_bag' => $view_bag,
    'items'    => $terms,
]);
