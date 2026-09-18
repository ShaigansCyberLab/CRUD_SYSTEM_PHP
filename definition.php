<?php

require __DIR__ . '/controller/app.php';

$definition = request_string(
    $_GET,
    'definition'
);

$definition = limit_text(
    $definition,
    500
);

if ($definition === '') {
    redirect('index.php');
}

$term = Data::get_def($definition);

if ($term === false) {
    view('notfound', [
        'view_bag' => [
            'title' => 'Not Found',
        ],
    ]);

    exit;
}

$view_bag = [
    'title' => 'Details Of ' . $term->definition,
];

view('definition', [
    'view_bag' => $view_bag,
    'model'    => $term,
]);
