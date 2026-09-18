<?php

session_start();

require __DIR__ . '/controller/app.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    redirect('index.php');
}

$term = Data::get_term((int) $id);

if ($term === false) {
    view('notfound', [
        'view_bag' => ['title' => 'Not Found']
    ]);
    exit;
}

$view_bag = [
    'title' => 'Detail For ' . $term->term
];

view('detail', [
    'view_bag' => $view_bag,
    'model'    => $term
]);
