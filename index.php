<?php

session_start();

require __DIR__ . '/controller/app.php';

$search = trim($_GET['search'] ?? '');

$terms = $search !== ''
    ? Data::search_terms($search)
    : Data::get_terms();

$view_bag = [
    'title' => 'Glossary'
];

view('index', [
    'view_bag' => $view_bag,
    'items'    => $terms,
    'search'   => $search
]);
