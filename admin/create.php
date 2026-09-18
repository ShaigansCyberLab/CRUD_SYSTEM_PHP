<?php

session_start();

require __DIR__ . '/../controller/app.php';

ensure_user_is_authenticated();

$view_bag = [
    'title' => 'Create Term'
];

if (is_post()) {

    if (!verify_csrf_token()) {

        $view_bag['error'] = 'Invalid request. Please try again.';

        view('admin/create', [
            'view_bag' => $view_bag
        ]);

        exit;
    }

    $term = sanitize(
        trim($_POST['term'] ?? '')
    );

    $definition = sanitize(
        trim($_POST['definition'] ?? '')
    );

    if ($term === '' || $definition === '') {

        $view_bag['error'] =
            'Both term and definition are required.';

        view('admin/create', [
            'view_bag' => $view_bag
        ]);

        exit;
    }

    if (!Data::add_term($term, $definition)) {

        $view_bag['error'] =
            'Unable to create the term.';

        view('admin/create', [
            'view_bag' => $view_bag
        ]);

        exit;
    }

    redirect('index.php');
}

view('admin/create', [
    'view_bag' => $view_bag
]);
