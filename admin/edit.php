<?php

session_start();

require __DIR__ . '/../controller/app.php';

ensure_user_is_authenticated();

$view_bag = [
    'title' => 'Edit Term'
];

if (is_get()) {

    $id = filter_input(
        INPUT_GET,
        'key',
        FILTER_VALIDATE_INT
    );

    if (!$id) {
        redirect('index.php');
    }

    $term = Data::get_term((int) $id);

    if ($term === false) {

        view('notfound', [
            'view_bag' => [
                'title' => 'Not Found'
            ]
        ]);

        exit;
    }

    view('admin/edit', [
        'view_bag' => $view_bag,
        'model'    => $term
    ]);

    exit;
}

if (is_post()) {

    if (!verify_csrf_token()) {

        $view_bag['error'] =
            'Invalid request. Please try again.';

        view('admin/edit', [
            'view_bag' => $view_bag
        ]);

        exit;
    }

    $original_id = filter_input(
        INPUT_POST,
        'original_id',
        FILTER_VALIDATE_INT
    );

    $term = sanitize(
        trim($_POST['term'] ?? '')
    );

    $definition = sanitize(
        trim($_POST['definition'] ?? '')
    );

    if (
        !$original_id ||
        $term === '' ||
        $definition === ''
    ) {

        $view_bag['error'] =
            'Invalid submission.';

        view('admin/edit', [
            'view_bag' => $view_bag
        ]);

        exit;
    }

    if (
        !Data::update_term(
            (int) $original_id,
            $term,
            $definition
        )
    ) {

        $view_bag['error'] =
            'Unable to update the term.';

        $model = Data::get_term((int) $original_id);

        if ($model !== false) {
            view('admin/edit', [
                'view_bag' => $view_bag,
                'model'    => $model
            ]);
        }

        exit;
    }

    redirect('index.php');
}
