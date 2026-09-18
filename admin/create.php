<?php

require __DIR__ . '/../controller/app.php';

ensure_user_is_authenticated();

$view_bag = [
    'title' => 'Create Term',
];

$term = '';
$definition = '';

if (is_post()) {
    $term = request_string(
        $_POST,
        'term'
    );

    $definition = request_string(
        $_POST,
        'definition'
    );

    if (!verify_csrf_token()) {
        $view_bag['error'] =
            'Invalid request. Please try again.';
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
            !Data::add_term(
                $term,
                $definition
            )
        ) {
            $view_bag['error'] =
                'Unable to create the term.';
        } else {
            redirect('index.php');
        }
    }
}

view('admin/create', [
    'view_bag'  => $view_bag,
    'term'      => $term,
    'definition' => $definition,
]);
