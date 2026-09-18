<?php

require __DIR__ . '/controller/app.php';

$search = request_string(
    $_GET,
    'search'
);

$search = limit_text($search, 100);

$terms = $search !== ''
    ? Data::search_terms($search)
    : Data::get_terms();

$view_bag = [
    'title' => 'Glossary',
];

view('index', [
    'view_bag' => $view_bag,
    'items'    => $terms,
    'search'   => $search,
]);
