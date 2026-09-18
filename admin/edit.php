<?php

require __DIR__ . '/../controller/app.php';

ensure_user_is_authenticated();

$view_bag = [
    'title' => 'Edit Term',
];

if (is_get()) {
    $id = filter_input(
        INPUT_GET,
        'key',
        FILTER_VALIDATE_INT,
        [
            'options' => [
                'min_range' => 1,
            ],
        ]
    );

    if ($id === false || $id === null) {
        redirect('index.php');
    }

    $term = Data::get_term((int) $id);

    if ($term === false) {
        view('notfound', [
            'view_bag' => [
                'title' => 'Not Found',
            ],
        ]);

        exit;
    }

    view('admin/edit', [
        'view_bag' => $view_bag,
        'model'    => $term,
    ]);

    exit;
}

if (is_post()) {
    $original_id = filter_input(
        INPUT_POST,
        'original_id',
        FILTER_VALIDATE_INT,
        [
            'options' => [
                'min_range' => 1,
            ],
        ]
    );

    $term = request_string(
        $_POST,
        'term'
    );

    $definition = request_string(
        $_POST,
        'definition'
    );

    $model = new GlossaryTerm();

    $model->id =
        $original_id !== false &&
        $original_id !== null
            ? (int) $original_id
            : 0;

    $model->term = $term;
    $model->definition = $definition;

    if (!verify_csrf_token()) {
        $view_bag['error'] =
            'Invalid request. Please try again.';
    } elseif (
        $original_id === false ||
        $original_id === null
    ) {
        $view_bag['error'] =
            'Invalid term identifier.';
    } else {
        $validation_error =
            validate_glossary_data(
                $term,
                $definition
            );

        if ($validation_error !== null) {
            $view_bag['error'] =
                $validation_error;
        } elseif (
            !Data::update_term(
                (int) $original_id,
                $term,
                $definition
            )
        ) {
            $view_bag['error'] =
                'Unable to update the term.';
        } else {
            redirect('index.php');
        }
    }

    view('admin/edit', [
        'view_bag' => $view_bag,
        'model'    => $model,
    ]);

    exit;
}

http_response_code(405);
header('Allow: GET, POST');
exit('Method Not Allowed.');
