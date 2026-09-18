<?php

session_start();

require __DIR__ . '/../controller/app.php';

ensure_user_is_authenticated();

$view_bag = [
    'title' => 'Delete Term'
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

    view('admin/delete', [
        'view_bag' => $view_bag,
        'model'    => $term
    ]);

    exit;
}

if (is_post()) {

    if (!verify_csrf_token()) {

        $view_bag['error'] =
            'Invalid request. Please try again.';

        view('admin/delete', [
            'view_bag' => $view_bag
        ]);

        exit;
    }

    $term_id = filter_input(
        INPUT_POST,
        'term_id',
        FILTER_VALIDATE_INT
    );

    if (!$term_id) {

        $view_bag['error'] =
            'Invalid submission.';

        view('admin/delete', [
            'view_bag' => $view_bag
        ]);

        exit;
    }

    if (!Data::delete_term((int) $term_id)) {

        $view_bag['error'] =
            'Unable to delete the term.';

        $term = Data::get_term((int) $term_id);

        if ($term !== false) {
            view('admin/delete', [
                'view_bag' => $view_bag,
                'model'    => $term
            ]);
        } else {
            view('notfound', [
                'view_bag' => [
                    'title' => 'Not Found'
                ]
            ]);
        }

        exit;
    }

    redirect('index.php');
}
